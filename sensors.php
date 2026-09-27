<?php

require_once "config/database.php";

/* =========================
   FETCH LATEST SENSOR DATA
========================= */

$sql = "SELECT * FROM dashboard_metrics ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No sensor data found.");
}

$energy = (float)$data['energy'];
$water = (float)$data['water'];
$waste = (float)$data['waste'];
$carbon = (float)$data['carbon'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sensors - ESG Sentinel</title>

    <link rel="stylesheet" href="assets/css/dashboard.css?v=1002">

    <style>

        /* =========================
           SENSOR PAGE
        ========================= */

        .sensor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .sensor-title h1 {
            margin: 0;
            color: #ffffff;
            font-size: 30px;
        }

        .sensor-title p {
            margin-top: 7px;
            color: #9fb8b3;
            font-size: 14px;
        }

        .system-status {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #123d35;
            border: 1px solid #285c50;
            color: #69e39d;
            padding: 9px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #36a269;
            border-radius: 50%;
        }


        /* =========================
           SENSOR KPI CARDS
        ========================= */

        .sensor-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .sensor-kpi {
            background: #12322f;
            border: 1px solid #234d47;
            border-radius: 14px;
            padding: 20px;
            min-height: 165px;
            box-sizing: border-box;
            transition: 0.2s ease;
        }

        .sensor-kpi:hover {
            transform: translateY(-3px);
            border-color: #3c866f;
        }

        .sensor-kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .sensor-icon {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #1d4942;
            border-radius: 10px;
            font-size: 19px;
        }

        .online-badge {
            color: #69e39d;
            background: #153f36;
            border: 1px solid #28624f;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .sensor-kpi h3 {
            margin: 0 0 8px;
            color: #a9c1bd;
            font-size: 13px;
            font-weight: 500;
        }

        .sensor-value {
            color: #ffffff;
            font-size: 27px;
            font-weight: 700;
        }

        .sensor-unit {
            color: #78948e;
            font-size: 12px;
            margin-top: 5px;
        }

        .sensor-online {
            color: #65dc98;
            font-size: 12px;
            margin-top: 13px;
        }


        /* =========================
           PANEL
        ========================= */

        .sensor-panel {
            background: #102b28;
            border: 1px solid #234d47;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 25px;
        }

        .sensor-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .sensor-panel-header h2 {
            margin: 0;
            color: #ffffff;
            font-size: 18px;
        }

        .sensor-panel-header p {
            margin-top: 5px;
            color: #819c96;
            font-size: 12px;
        }

        .connected-badge {
            color: #69e39d;
            background: #173f38;
            border: 1px solid #28624f;
            padding: 6px 11px;
            border-radius: 15px;
            font-size: 11px;
        }


        /* =========================
           TABLE
        ========================= */

        .sensor-table-wrapper {
            overflow-x: auto;
        }

        .sensor-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        .sensor-table th {
            padding: 14px 12px;
            text-align: left;
            color: #8fa9a4;
            font-size: 12px;
            font-weight: 600;
            border-bottom: 1px solid #294b46;
        }

        .sensor-table td {
            padding: 16px 12px;
            color: #e8f0ee;
            font-size: 13px;
            border-bottom: 1px solid #203f3b;
        }

        .sensor-table tr:last-child td {
            border-bottom: none;
        }

        .sensor-table tr:hover {
            background: #14332f;
        }

        .table-sensor {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #ffffff;
        }

        .table-icon {
            width: 30px;
            height: 30px;
            background: #1d4942;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table-online {
            color: #65dc98;
            font-weight: 600;
        }


        /* =========================
           INFORMATION
        ========================= */

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .info-card {
            background: #0c2422;
            border: 1px solid #234842;
            border-radius: 12px;
            padding: 18px;
        }

        .info-label {
            color: #78948e;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .info-value {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .sensor-kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 700px) {

            .sensor-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .sensor-kpi-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>

<div class="dashboard-container">

    <?php include "sidebar.php"; ?>


    <main class="main-content">


        <!-- =========================
             HEADER
        ========================= -->

        <div class="sensor-header">

            <div class="sensor-title">

                <h1>Sensor Management</h1>

                <p>
                    Monitor connected environmental sensors
                </p>

            </div>


            <div class="system-status">

                <span class="status-dot"></span>

                System Operational

            </div>

        </div>



        <!-- =========================
             SENSOR CARDS
        ========================= -->

        <div class="sensor-kpi-grid">


            <!-- ENERGY -->

            <div class="sensor-kpi">

                <div class="sensor-kpi-top">

                    <div class="sensor-icon">
                        ⚡
                    </div>

                    <span class="online-badge">
                        ONLINE
                    </span>

                </div>

                <h3>Energy Sensor</h3>

                <div class="sensor-value">
                    <?php echo $energy; ?>
                </div>

                <div class="sensor-unit">
                    kWh
                </div>

                <div class="sensor-online">
                    ● Connected
                </div>

            </div>



            <!-- WATER -->

            <div class="sensor-kpi">

                <div class="sensor-kpi-top">

                    <div class="sensor-icon">
                        💧
                    </div>

                    <span class="online-badge">
                        ONLINE
                    </span>

                </div>

                <h3>Water Sensor</h3>

                <div class="sensor-value">
                    <?php echo $water; ?>
                </div>

                <div class="sensor-unit">
                    Litres
                </div>

                <div class="sensor-online">
                    ● Connected
                </div>

            </div>



            <!-- WASTE -->

            <div class="sensor-kpi">

                <div class="sensor-kpi-top">

                    <div class="sensor-icon">
                        🗑️
                    </div>

                    <span class="online-badge">
                        ONLINE
                    </span>

                </div>

                <h3>Waste Sensor</h3>

                <div class="sensor-value">
                    <?php echo $waste; ?>
                </div>

                <div class="sensor-unit">
                    kg
                </div>

                <div class="sensor-online">
                    ● Connected
                </div>

            </div>



            <!-- CARBON -->

            <div class="sensor-kpi">

                <div class="sensor-kpi-top">

                    <div class="sensor-icon">
                        🌱
                    </div>

                    <span class="online-badge">
                        ONLINE
                    </span>

                </div>

                <h3>Carbon Sensor</h3>

                <div class="sensor-value">
                    <?php echo $carbon; ?>
                </div>

                <div class="sensor-unit">
                    tCO₂e
                </div>

                <div class="sensor-online">
                    ● Connected
                </div>

            </div>


        </div>



        <!-- =========================
             CONNECTED SENSORS
        ========================= -->

        <div class="sensor-panel">


            <div class="sensor-panel-header">

                <div>

                    <h2>Connected Sensors</h2>

                    <p>
                        Current readings from all connected sensors
                    </p>

                </div>

                <span class="connected-badge">
                    4 Sensors Connected
                </span>

            </div>


            <div class="sensor-table-wrapper">

                <table class="sensor-table">

                    <thead>

                        <tr>

                            <th>Sensor</th>

                            <th>Category</th>

                            <th>Latest Reading</th>

                            <th>Unit</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>


                        <!-- ENERGY -->

                        <tr>

                            <td>

                                <div class="table-sensor">

                                    <span class="table-icon">
                                        ⚡
                                    </span>

                                    Energy Sensor

                                </div>

                            </td>

                            <td>
                                Environment
                            </td>

                            <td>
                                <?php echo $energy; ?>
                            </td>

                            <td>
                                kWh
                            </td>

                            <td class="table-online">
                                ● Online
                            </td>

                        </tr>



                        <!-- WATER -->

                        <tr>

                            <td>

                                <div class="table-sensor">

                                    <span class="table-icon">
                                        💧
                                    </span>

                                    Water Sensor

                                </div>

                            </td>

                            <td>
                                Environment
                            </td>

                            <td>
                                <?php echo $water; ?>
                            </td>

                            <td>
                                Litres
                            </td>

                            <td class="table-online">
                                ● Online
                            </td>

                        </tr>



                        <!-- WASTE -->

                        <tr>

                            <td>

                                <div class="table-sensor">

                                    <span class="table-icon">
                                        🗑️
                                    </span>

                                    Waste Sensor

                                </div>

                            </td>

                            <td>
                                Environment
                            </td>

                            <td>
                                <?php echo $waste; ?>
                            </td>

                            <td>
                                kg
                            </td>

                            <td class="table-online">
                                ● Online
                            </td>

                        </tr>



                        <!-- CARBON -->

                        <tr>

                            <td>

                                <div class="table-sensor">

                                    <span class="table-icon">
                                        🌱
                                    </span>

                                    Carbon Sensor

                                </div>

                            </td>

                            <td>
                                Environment
                            </td>

                            <td>
                                <?php echo $carbon; ?>
                            </td>

                            <td>
                                tCO₂e
                            </td>

                            <td class="table-online">
                                ● Online
                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>

        </div>



        <!-- =========================
             SENSOR INFORMATION
        ========================= -->

        <div class="sensor-panel">

            <div class="sensor-panel-header">

                <div>

                    <h2>Sensor Information</h2>

                    <p>
                        Information about the latest sensor data
                    </p>

                </div>

            </div>


            <div class="info-grid">


                <div class="info-card">

                    <div class="info-label">
                        Data Source
                    </div>

                    <div class="info-value">
                        ESG Monitoring Database
                    </div>

                </div>


                <div class="info-card">

                    <div class="info-label">
                        Latest Record ID
                    </div>

                    <div class="info-value">
                        #<?php echo $data['id']; ?>
                    </div>

                </div>


                <div class="info-card">

                    <div class="info-label">
                        Last Recorded
                    </div>

                    <div class="info-value">
                        <?php echo $data['created_at']; ?>
                    </div>

                </div>


            </div>

        </div>


    </main>

</div>

</body>

</html>