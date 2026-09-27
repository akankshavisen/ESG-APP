<?php

require_once "config/database.php";

/* Latest ESG data */
$sql = "SELECT * FROM dashboard_metrics ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

$alerts = [];

/* Energy Alert */
if ($data['energy'] > 800) {
    $alerts[] = [
        "type" => "Energy Consumption",
        "message" => "Energy consumption is above the recommended threshold.",
        "severity" => "Warning"
    ];
}

/* Water Alert */
if ($data['water'] > 4500) {
    $alerts[] = [
        "type" => "Water Consumption",
        "message" => "Water consumption is above the recommended threshold.",
        "severity" => "Warning"
    ];
}

/* Waste Alert */
if ($data['waste'] > 150) {
    $alerts[] = [
        "type" => "Waste Generation",
        "message" => "Waste generation is above the recommended threshold.",
        "severity" => "Critical"
    ];
}

/* Carbon Alert */
if ($data['carbon'] > 2.0) {
    $alerts[] = [
        "type" => "Carbon Emission",
        "message" => "Carbon emission level requires attention.",
        "severity" => "Warning"
    ];
}

/* Store alerts only once per day */
foreach ($alerts as $alert) {

    $check = $conn->prepare(
        "SELECT id FROM alerts
         WHERE alert_type = ?
         AND message = ?
         AND DATE(created_at) = CURDATE()"
    );

    $check->bind_param(
        "ss",
        $alert['type'],
        $alert['message']
    );

    $check->execute();

    $checkResult = $check->get_result();

    if ($checkResult->num_rows == 0) {

        $insert = $conn->prepare(
            "INSERT INTO alerts
            (alert_type, message, severity, status)
            VALUES (?, ?, ?, 'Active')"
        );

        $insert->bind_param(
            "sss",
            $alert['type'],
            $alert['message'],
            $alert['severity']
        );

        $insert->execute();
        $insert->close();
    }

    $check->close();
}

/* Fetch alerts */
$alertQuery = "SELECT * FROM alerts ORDER BY id DESC";
$alertResult = $conn->query($alertQuery);

/* Active alerts count */
$activeQuery = "
    SELECT COUNT(*) AS total
    FROM alerts
    WHERE status = 'Active'
";

$activeResult = $conn->query($activeQuery);
$activeData = $activeResult->fetch_assoc();

/* Current ESG Score */
$score = (
    $data['environmental_score'] +
    $data['social_score'] +
    $data['governance_score']
) / 3;

$score = round($score);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Alerts - ESG Sentinel</title>

    <link rel="stylesheet"
          href="assets/css/dashboard.css?v=1003">

    <style>

        /* ==============================
           ALERT SUMMARY
        ============================== */

        .alert-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .alert-card {
            background: #123b36 !important;
            border: 1px solid #285b52 !important;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.20);
        }

        .alert-card h3 {
            color: #a9c5bf !important;
            font-size: 14px;
            font-weight: 500;
            margin: 0 0 10px 0;
        }

        .alert-number {
            color: #ffffff !important;
            font-size: 30px;
            font-weight: 700;
        }


        /* ==============================
           ALERT TABLE PANEL
        ============================== */

        .alerts-panel {
            background: #123b36;
            border: 1px solid #285b52;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.20);
        }

        .alerts-panel h2 {
            color: #ffffff;
            margin: 0 0 20px 0;
            font-size: 20px;
        }


        /* ==============================
           TABLE
        ============================== */

        .alerts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .alerts-table th {
            color: #9fbdb7 !important;
            font-size: 13px;
            font-weight: 600;
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #285b52;
        }

        .alerts-table td {
            color: #ffffff !important;
            font-size: 14px;
            padding: 16px 14px;
            border-bottom: 1px solid #244d47;
        }

        .alerts-table tbody tr:hover {
            background: #16453f;
        }


        /* ==============================
           WARNING
        ============================== */

        .severity-warning {
            display: inline-block;
            background: #fef3c7 !important;
            color: #92400e !important;
            border: 1px solid #f59e0b;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }


        /* ==============================
           CRITICAL
        ============================== */

        .severity-critical {
            display: inline-block;
            background: #fee2e2 !important;
            color: #991b1b !important;
            border: 1px solid #ef4444;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }


        /* ==============================
           ACTIVE STATUS
        ============================== */

        .status-active {
            display: inline-block;
            color: #6ee7b7 !important;
            background: #164e3b;
            border: 1px solid #267257;
            padding: 5px 11px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }


        /* ==============================
           NO ALERT
        ============================== */

        .no-alert {
            text-align: center;
            padding: 35px !important;
            color: #9fbdb7 !important;
        }


        /* ==============================
           RESPONSIVE
        ============================== */

        @media (max-width: 800px) {

            .alert-summary {
                grid-template-columns: 1fr;
            }

            .alerts-panel {
                overflow-x: auto;
            }

            .alerts-table {
                min-width: 700px;
            }
        }
/* FINAL FIX FOR ALERT BADGES */

td .severity-warning {
    display: inline-block !important;
    background-color: #fff3cd !important;
    color: #7a4b00 !important;
    border: 2px solid #f0ad00 !important;
    padding: 8px 16px !important;
    border-radius: 20px !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    white-space: nowrap !important;
}

td .severity-critical {
    display: inline-block !important;
    background-color: #ffe0e0 !important;
    color: #8b0000 !important;
    border: 2px solid #e53935 !important;
    padding: 8px 16px !important;
    border-radius: 20px !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    white-space: nowrap !important;
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

                <h1>Alerts</h1>

                <p>
                    Monitor important ESG events and threshold violations
                </p>

            </div>

        </div>


        <!-- SUMMARY CARDS -->

        <div class="alert-summary">


            <!-- Total Alerts -->

            <div class="alert-card">

                <h3>Total Alerts</h3>

                <div class="alert-number">

                    <?php
                    echo $alertResult->num_rows;
                    ?>

                </div>

            </div>


            <!-- Active Alerts -->

            <div class="alert-card">

                <h3>Active Alerts</h3>

                <div class="alert-number">

                    <?php
                    echo $activeData['total'];
                    ?>

                </div>

            </div>


            <!-- ESG Score -->

            <div class="alert-card">

                <h3>Current ESG Score</h3>

                <div class="alert-number">

                    <?php
                    echo $score;
                    ?>

                </div>

            </div>


        </div>


        <!-- ALERT HISTORY -->

        <div class="alerts-panel">

            <h2>Alert History</h2>


            <table class="alerts-table">

                <thead>

                    <tr>

                        <th>Alert Type</th>

                        <th>Message</th>

                        <th>Severity</th>

                        <th>Status</th>

                        <th>Date</th>

                    </tr>

                </thead>


                <tbody>


                <?php if ($alertResult->num_rows > 0): ?>


                    <?php while ($row = $alertResult->fetch_assoc()): ?>


                        <tr>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row['alert_type']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row['message']
                                );
                                ?>

                            </td>


                            <td>


                                <?php if ($row['severity'] == 'Critical'): ?>

                                    <span class="severity-critical">
                                        Critical
                                    </span>

                                <?php else: ?>

                                    <span class="severity-warning">
                                        Warning
                                    </span>

                                <?php endif; ?>


                            </td>


                            <td>

                                <span class="status-active">

                                    <?php
                                    echo htmlspecialchars(
                                        $row['status']
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row['created_at']
                                );
                                ?>

                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="5"
                            class="no-alert">

                            No alerts available.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>

            </table>

        </div>


    </main>

</div>

</body>

</html>