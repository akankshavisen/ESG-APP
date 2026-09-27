<?php

require_once "config/database.php";

/* =========================
   FETCH LATEST MONITORING DATA
========================= */

$sql = "SELECT * FROM dashboard_metrics ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No monitoring data found.");
}

/* =========================
   CALCULATE UTILIZATION
========================= */

$energy = (float)$data['energy'];
$water = (float)$data['water'];
$waste = (float)$data['waste'];
$production = (int)$data['production'];
$carbon = (float)$data['carbon'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Live Monitoring - ESG Sentinel</title>

    <link rel="stylesheet" href="assets/css/dashboard.css?v=1002">

    <style>

        /* =========================
           LIVE MONITORING PAGE
        ========================= */

        .monitor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .monitor-title h1 {
            margin: 0;
            font-size: 30px;
            color: #ffffff;
        }

        .monitor-title p {
            margin-top: 7px;
            color: #9fb8b3;
            font-size: 14px;
        }

        .live-status {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #123d35;
            border: 1px solid #245b4f;
            color: #69e39d;
            padding: 9px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #36a269;
            border-radius: 50%;
            display: inline-block;
        }


        /* =========================
           MONITORING KPI GRID
        ========================= */

        .monitor-kpi-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .monitor-kpi {
            background: #12322f;
            border: 1px solid #234d47;
            border-radius: 14px;
            padding: 20px;
            min-height: 150px;
            box-sizing: border-box;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .monitor-kpi:hover {
            transform: translateY(-3px);
            border-color: #3c866f;
        }

        .monitor-kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 17px;
        }

        .monitor-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #1d4942;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        .monitor-live {
            font-size: 11px;
            color: #69e39d;
            background: #153f36;
            border: 1px solid #28624f;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .monitor-kpi h3 {
            color: #a9c1bd;
            font-size: 13px;
            font-weight: 500;
            margin: 0 0 8px;
        }

        .monitor-value {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.1;
        }

        .monitor-unit {
            color: #78948e;
            font-size: 12px;
            margin-top: 6px;
        }


        /* =========================
           MAIN MONITORING PANEL
        ========================= */

        .monitor-panel {
            background: #102b28;
            border: 1px solid #234d47;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 25px;
        }

        .monitor-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .monitor-panel-header h2 {
            color: #ffffff;
            font-size: 18px;
            margin: 0;
        }

        .monitor-panel-header p {
            color: #819c96;
            font-size: 12px;
            margin-top: 5px;
        }

        .panel-badge {
            background: #173f38;
            color: #69e39d;
            border: 1px solid #28624f;
            padding: 6px 10px;
            border-radius: 15px;
            font-size: 11px;
        }


        /* =========================
           SENSOR GRID
        ========================= */

        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .sensor-card {
            background: #0c2422;
            border: 1px solid #234842;
            border-radius: 12px;
            padding: 18px;
        }

        .sensor-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sensor-name {
            color: #dce9e6;
            font-size: 14px;
            font-weight: 600;
        }

        .sensor-icon {
            font-size: 18px;
        }

        .sensor-status {
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 7px;
            color: #65dc98;
            font-size: 12px;
        }

        .online-dot {
            width: 7px;
            height: 7px;
            background: #36a269;
            border-radius: 50%;
        }


        /* =========================
           INFORMATION SECTION
        ========================= */

        .monitor-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .info-box {
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
            font-size: 15px;
            font-weight: 600;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {

            .monitor-kpi-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .sensor-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media (max-width: 700px) {

            .monitor-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .monitor-kpi-grid {
                grid-template-columns: 1fr;
            }

            .sensor-grid {
                grid-template-columns: 1fr;
            }

            .monitor-info-grid {
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
             PAGE HEADER
        ========================= -->

        <div class="monitor-header">

            <div class="monitor-title">

                <h1>Live Monitoring</h1>

                <p>
                    Real-time environmental and operational monitoring
                </p>

            </div>


            <div class="live-status">

                <span class="live-dot"></span>

                System Live

            </div>

        </div>



        <!-- =========================
             KPI CARDS
        ========================= -->

        <div class="monitor-kpi-grid">


            <!-- ENERGY -->

            <div class="monitor-kpi">

                <div class="monitor-kpi-top">

                    <div class="monitor-icon">⚡</div>

                    <span class="monitor-live">
                        LIVE
                    </span>

                </div>

                <h3>Energy Consumption</h3>

                <div class="monitor-value">
                    <?php echo $energy; ?>
                </div>

                <div class="monitor-unit">
                    kWh
                </div>

            </div>



            <!-- WATER -->

            <div class="monitor-kpi">

                <div class="monitor-kpi-top">

                    <div class="monitor-icon">💧</div>

                    <span class="monitor-live">
                        LIVE
                    </span>

                </div>

                <h3>Water Usage</h3>

                <div class="monitor-value">
                    <?php echo $water; ?>
                </div>

                <div class="monitor-unit">
                    Litres
                </div>

            </div>



            <!-- WASTE -->

            <div class="monitor-kpi">

                <div class="monitor-kpi-top">

                    <div class="monitor-icon">🗑️</div>

                    <span class="monitor-live">
                        LIVE
                    </span>

                </div>

                <h3>Waste Generated</h3>

                <div class="monitor-value">
                    <?php echo $waste; ?>
                </div>

                <div class="monitor-unit">
                    kg
                </div>

            </div>



            <!-- PRODUCTION -->

            <div class="monitor-kpi">

                <div class="monitor-kpi-top">

                    <div class="monitor-icon">🏭</div>

                    <span class="monitor-live">
                        LIVE
                    </span>

                </div>

                <h3>Production</h3>

                <div class="monitor-value">
                    <?php echo $production; ?>
                </div>

                <div class="monitor-unit">
                    Units
                </div>

            </div>



            <!-- CARBON -->

            <div class="monitor-kpi">

                <div class="monitor-kpi-top">

                    <div class="monitor-icon">🌱</div>

                    <span class="monitor-live">
                        LIVE
                    </span>

                </div>

                <h3>Carbon Footprint</h3>

                <div class="monitor-value">
                    <?php echo $carbon; ?>
                </div>

                <div class="monitor-unit">
                    tCO₂e
                </div>

            </div>

        </div>



        <!-- =========================
             SENSOR STATUS
        ========================= -->

        <div class="monitor-panel">

            <div class="monitor-panel-header">

                <div>

                    <h2>Sensor Status</h2>

                    <p>
                        Current status of connected monitoring sensors
                    </p>

                </div>

                <span class="panel-badge">
                    4 Sensors Connected
                </span>

            </div>


            <div class="sensor-grid">


                <div class="sensor-card">

                    <div class="sensor-top">

                        <span class="sensor-name">
                            Energy Sensor
                        </span>

                        <span class="sensor-icon">
                            ⚡
                        </span>

                    </div>

                    <div class="sensor-status">

                        <span class="online-dot"></span>

                        Online

                    </div>

                </div>



                <div class="sensor-card">

                    <div class="sensor-top">

                        <span class="sensor-name">
                            Water Sensor
                        </span>

                        <span class="sensor-icon">
                            💧
                        </span>

                    </div>

                    <div class="sensor-status">

                        <span class="online-dot"></span>

                        Online

                    </div>

                </div>



                <div class="sensor-card">

                    <div class="sensor-top">

                        <span class="sensor-name">
                            Waste Sensor
                        </span>

                        <span class="sensor-icon">
                            🗑️
                        </span>

                    </div>

                    <div class="sensor-status">

                        <span class="online-dot"></span>

                        Online

                    </div>

                </div>



                <div class="sensor-card">

                    <div class="sensor-top">

                        <span class="sensor-name">
                            Carbon Sensor
                        </span>

                        <span class="sensor-icon">
                            🌱
                        </span>

                    </div>

                    <div class="sensor-status">

                        <span class="online-dot"></span>

                        Online

                    </div>

                </div>


            </div>

        </div>



        <!-- =========================
             MONITORING INFORMATION
        ========================= -->

        <div class="monitor-panel">

            <div class="monitor-panel-header">

                <div>

                    <h2>Monitoring Information</h2>

                    <p>
                        Latest information received from the ESG monitoring database
                    </p>

                </div>

            </div>


            <div class="monitor-info-grid">


                <div class="info-box">

                    <div class="info-label">
                        Data Source
                    </div>

                    <div class="info-value">
                        ESG Monitoring Database
                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">
                        Latest Data ID
                    </div>

                    <div class="info-value">
                        #<?php echo $data['id']; ?>
                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">
                        Monitoring Status
                    </div>

                    <div class="info-value">
                        ● Live & Updated
                    </div>

                </div>


            </div>

        </div>


    </main>

</div>

</body>

</html>