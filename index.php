<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ESG Sentinel</title>

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

        .container {
            text-align: center;
            width: 90%;
            max-width: 600px;
        }

        .logo {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .logo span {
            color: #4fd18b;
        }

        .subtitle {
            color: #a9c0bb;
            font-size: 17px;
            margin-bottom: 40px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;

            padding: 13px 30px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 15px;
            font-weight: 600;

            transition: 0.2s;
        }

        .login-btn {
            background: #4fd18b;
            color: #071411;
        }

        .login-btn:hover {
            background: #63e29c;
        }

        .register-btn {
            background: transparent;
            color: white;

            border: 1px solid #4fd18b;
        }

        .register-btn:hover {
            background: #193f3a;
        }

        .description {
            margin-top: 45px;
            color: #718a85;
            font-size: 13px;
            line-height: 1.6;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="logo">
        ESG<span>SENTINEL</span>
    </div>

    <div class="subtitle">
        ESG Monitoring & Sustainability Management Platform
    </div>

    <div class="buttons">

        <a href="login.php" class="btn login-btn">
            Login
        </a>

        <a href="register.php" class="btn register-btn">
            Create Account
        </a>

    </div>

    <div class="description">
        Secure access to ESG monitoring, analytics, reports and insights.
    </div>

</div>

</body>

</html>