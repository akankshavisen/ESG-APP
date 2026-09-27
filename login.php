<?php

session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $error = "Please enter email and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role 
             FROM users 
             WHERE email = ? 
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["user_role"] = $user["role"];

                header("Location: dashboard.php");
                exit;

            } else {

                $error = "Invalid email or password.";
            }

        } else {

            $error = "Invalid email or password.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - ESG Sentinel</title>

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
        }

        .login-container {
            width: 420px;
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
            margin-bottom: 30px;
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
            margin-bottom: 20px;

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

        .login-btn {
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

        .login-btn:hover {
            background: #63e29c;
        }

        .error {
            background: #4a2020;
            color: #ffb4b4;
            border: 1px solid #8b3a3a;

            padding: 12px;
            border-radius: 8px;

            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            color: #718a85;
            font-size: 12px;
        }

    </style>

</head>

<body>

<div class="login-container">

    <div class="logo">
        ESG<span>SENTINEL</span>
    </div>

    <div class="subtitle">
        Secure Admin Login
    </div>

    <?php if (!empty($error)): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Email Address</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >

        <button type="submit" class="login-btn">
            Login
        </button>

    </form>

    <div class="footer">
        ESG Sentinel • Secure Access
    </div>

</div>

</body>

</html>