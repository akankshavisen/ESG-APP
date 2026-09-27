<?php

require_once "config/database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {

        $message = "Please fill all fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    } else {

        // Check if email already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "An account with this email already exists.";
            $message_type = "error";

        } else {

            // Secure password hashing
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = "User";

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssss",
                $name,
                $email,
                $hashed_password,
                $role
            );

            if ($stmt->execute()) {

                $message = "Account created successfully! You can now login.";
                $message_type = "success";

                // Clear form values
                $name = "";
                $email = "";

            } else {

                $message = "Something went wrong. Please try again.";
                $message_type = "error";
            }

            $stmt->close();
        }

        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account - ESG Sentinel</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            font-family: Arial, sans-serif;

            background: #0b1716;
            color: white;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;
        }

        .register-container {
            width: 430px;

            background: #102522;

            padding: 40px;

            border-radius: 16px;

            box-shadow: 0 10px 35px rgba(0,0,0,0.4);
        }

        .logo {
            text-align: center;

            font-size: 28px;
            font-weight: bold;

            margin-bottom: 8px;
        }

        .logo span {
            color: #4fd18b;
        }

        .subtitle {
            text-align: center;

            color: #a9c0bb;

            margin-bottom: 28px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            color: #d9e7e4;

            font-size: 14px;
        }

        input {
            width: 100%;

            padding: 13px;

            margin-bottom: 18px;

            border: 1px solid #31524d;

            border-radius: 8px;

            background: #0b1c1a;

            color: white;

            font-size: 14px;

            outline: none;
        }

        input:focus {
            border-color: #4fd18b;
        }

        .register-btn {
            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 8px;

            background: #4fd18b;

            color: #071411;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;
        }

        .register-btn:hover {
            background: #63e29c;
        }

        .message {
            padding: 12px;

            border-radius: 8px;

            margin-bottom: 20px;

            text-align: center;

            font-size: 14px;
        }

        .error {
            background: #4a2020;

            color: #ffb4b4;

            border: 1px solid #8b3a3a;
        }

        .success {
            background: #173d2c;

            color: #9ff0bd;

            border: 1px solid #3c8f61;
        }

        .login-link {
            text-align: center;

            margin-top: 22px;

            color: #8da6a1;

            font-size: 13px;
        }

        .login-link a {
            color: #4fd18b;

            text-decoration: none;

            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="register-container">

    <div class="logo">
        ESG<span>SENTINEL</span>
    </div>

    <div class="subtitle">
        Create Your Account
    </div>

    <?php if (!empty($message)): ?>

        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Full Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter your name"
            value="<?php echo htmlspecialchars($name ?? ''); ?>"
            required
        >

        <label>Email Address</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            value="<?php echo htmlspecialchars($email ?? ''); ?>"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Create a password"
            required
        >

        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm your password"
            required
        >

        <button type="submit" class="register-btn">
            Create Account
        </button>

    </form>

    <div class="login-link">

        Already have an account?

        <a href="login.php">
            Login
        </a>

    </div>

</div>

</body>

</html>