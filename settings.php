<?php

require_once "config/database.php";

/* Get existing settings */
$checkQuery = "SELECT * FROM settings ORDER BY id ASC LIMIT 1";
$checkResult = $conn->query($checkQuery);
$settings = $checkResult->fetch_assoc();

$successMessage = "";
$errorMessage = "";


/* Save / Update settings */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $companyName = trim($_POST["company_name"]);
    $reportingYear = trim($_POST["reporting_year"]);
    $adminName = trim($_POST["admin_name"]);
    $alertThreshold = (float) $_POST["alert_threshold"];


    if ($settings) {

        $sql = "UPDATE settings
                SET company_name = ?,
                    reporting_year = ?,
                    admin_name = ?,
                    alert_threshold = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssdi",
            $companyName,
            $reportingYear,
            $adminName,
            $alertThreshold,
            $settings["id"]
        );

        if ($stmt->execute()) {
            $successMessage = "Settings saved successfully!";
        } else {
            $errorMessage = "Unable to save settings.";
        }

        $stmt->close();

    } else {

        $sql = "INSERT INTO settings
                (company_name, reporting_year, admin_name, alert_threshold)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssd",
            $companyName,
            $reportingYear,
            $adminName,
            $alertThreshold
        );

        if ($stmt->execute()) {
            $successMessage = "Settings saved successfully!";
        } else {
            $errorMessage = "Unable to save settings.";
        }

        $stmt->close();
    }


    /* Reload settings */
    $checkResult = $conn->query($checkQuery);
    $settings = $checkResult->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Settings - ESG Sentinel</title>

    <link rel="stylesheet"
          href="assets/css/dashboard.css?v=1004">

    <style>

        /* ==============================
           SETTINGS CONTAINER
        ============================== */

        .settings-container {
            max-width: 850px;
        }


        .settings-info {
            color: #a9c5bf !important;
            margin-bottom: 22px;
            font-size: 14px;
        }


        /* ==============================
           SUCCESS MESSAGE
        ============================== */

        .success-message {
            background: #123f32 !important;
            color: #6ee7b7 !important;
            border: 1px solid #267257 !important;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }


        /* ==============================
           ERROR MESSAGE
        ============================== */

        .error-message {
            background: #4a2020 !important;
            color: #fecaca !important;
            border: 1px solid #b91c1c !important;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }


        /* ==============================
           SETTINGS PANEL
        ============================== */

        .settings-form {
            background: #123b36 !important;
            border: 1px solid #285b52 !important;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.20);
        }


        /* ==============================
           FORM GROUP
        ============================== */

        .form-group {
            margin-bottom: 22px;
        }


        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
        }


        /* ==============================
           INPUT
        ============================== */

        .form-group input {
            width: 100%;
            box-sizing: border-box;

            padding: 12px 14px;

            background: #0d302d !important;
            color: #ffffff !important;

            border: 1px solid #38685f !important;
            border-radius: 8px;

            font-size: 14px;

            transition: 0.2s ease;
        }


        .form-group input::placeholder {
            color: #86a8a1 !important;
            opacity: 1;
        }


        .form-group input:focus {
            outline: none;

            border-color: #4fd18b !important;

            box-shadow: 0 0 0 2px rgba(79,209,139,0.15);
        }


        /* ==============================
           SAVE BUTTON
        ============================== */

        .save-btn {
            background: #36a269 !important;
            color: #ffffff !important;

            border: none;

            padding: 12px 25px;

            border-radius: 8px;

            cursor: pointer;

            font-size: 14px;

            font-weight: 700;

            transition: 0.2s ease;
        }


        .save-btn:hover {
            background: #2d8758 !important;
            transform: translateY(-1px);
        }


        /* ==============================
           RESPONSIVE
        ============================== */

        @media (max-width: 700px) {

            .settings-form {
                padding: 20px;
            }

        }

    </style>

</head>


<body>

<div class="dashboard-container">


    <?php include "sidebar.php"; ?>


    <main class="main-content">


        <!-- HEADER -->

        <div class="top-header">

            <div>

                <h1>Settings</h1>

                <p>
                    Manage ESG Sentinel system settings
                </p>

            </div>

        </div>


        <div class="settings-container">


            <p class="settings-info">

                Update company, reporting and alert configuration.

            </p>


            <?php if ($successMessage): ?>

                <div class="success-message">

                    ✓ <?php echo htmlspecialchars($successMessage); ?>

                </div>

            <?php endif; ?>


            <?php if ($errorMessage): ?>

                <div class="error-message">

                    ✕ <?php echo htmlspecialchars($errorMessage); ?>

                </div>

            <?php endif; ?>


            <!-- SETTINGS FORM -->

            <div class="settings-form">


                <form method="POST">


                    <!-- COMPANY -->

                    <div class="form-group">

                        <label>
                            Company Name
                        </label>

                        <input
                            type="text"
                            name="company_name"
                            value="<?php
                                echo htmlspecialchars(
                                    $settings["company_name"] ?? ""
                                );
                            ?>"
                            placeholder="Enter company name"
                            required>

                    </div>


                    <!-- REPORTING YEAR -->

                    <div class="form-group">

                        <label>
                            Reporting Year
                        </label>

                        <input
                            type="text"
                            name="reporting_year"
                            value="<?php
                                echo htmlspecialchars(
                                    $settings["reporting_year"] ?? "2026"
                                );
                            ?>"
                            placeholder="2026"
                            required>

                    </div>


                    <!-- ADMIN -->

                    <div class="form-group">

                        <label>
                            Admin Name
                        </label>

                        <input
                            type="text"
                            name="admin_name"
                            value="<?php
                                echo htmlspecialchars(
                                    $settings["admin_name"] ?? ""
                                );
                            ?>"
                            placeholder="Enter admin name"
                            required>

                    </div>


                    <!-- THRESHOLD -->

                    <div class="form-group">

                        <label>
                            Alert Threshold
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="alert_threshold"
                            value="<?php
                                echo htmlspecialchars(
                                    $settings["alert_threshold"] ?? "80"
                                );
                            ?>"
                            placeholder="80"
                            required>

                    </div>


                    <!-- SAVE -->

                    <button
                        type="submit"
                        class="save-btn">

                        Save Settings

                    </button>


                </form>

            </div>

        </div>


    </main>

</div>

</body>

</html>