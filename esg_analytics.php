<?php

require_once "config/database.php";

/* =========================
   FETCH LATEST ESG DATA
========================= */

$sql = "SELECT * FROM dashboard_metrics ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No analytics data found.");
}


/* =========================
   ESG SCORES
========================= */

$environmental = (float)$data['environmental_score'];
$social = (float)$data['social_score'];
$governance = (float)$data['governance_score'];

$overall_score = round(
    ($environmental + $social + $governance) / 3,
    1
);


/* =========================
   GRADE FUNCTION
========================= */

function getGrade($score)
{
    if ($score >= 90) {
        return "A+";
    } elseif ($score >= 80) {
        return "A";
    } elseif ($score >= 70) {
        return "B+";
    } elseif ($score >= 60) {
        return "B";
    } else {
        return "C";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ESG Analytics - ESG Sentinel</title>

    <link rel="stylesheet" href="assets/css/dashboard.css?v=1002">

    <style>

        /* =========================
           HEADER
        ========================= */

        .analytics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .analytics-title h1 {
            margin: 0;
            color: #ffffff;
            font-size: 30px;
        }

        .analytics-title p {
            margin-top: 7px;
            color: #9fb8b3;
            font-size: 14px;
        }

        .analytics-status {
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
           SCORE CARDS
        ========================= */

        .score-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .score-card {
            background: #12322f;
            border: 1px solid #234d47;
            border-radius: 14px;
            padding: 20px;
            min-height: 155px;
            box-sizing: border-box;
            transition: 0.2s ease;
        }

        .score-card:hover {
            transform: translateY(-3px);
            border-color: #3c866f;
        }

        .score-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .score-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1d4942;
            border-radius: 10px;
            font-size: 18px;
        }

        .score-type {
            color: #a9c1bd;
            font-size: 12px;
            font-weight: 500;
        }

        .score-number {
            color: #ffffff;
            font-size: 30px;
            font-weight: 700;
        }

        .score-label {
            color: #78948e;
            font-size: 12px;
            margin-top: 4px;
        }

        .score-grade {
            display: inline-block;
            margin-top: 12px;
            padding: 4px 9px;
            background: #173f38;
            border: 1px solid #28624f;
            border-radius: 12px;
            color: #69e39d;
            font-size: 11px;
            font-weight: 700;
        }


        /* =========================
           ANALYTICS GRID
        ========================= */

        .analytics-grid {
            display: grid;
            grid-template-columns: 1.4fr 0.8fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .analytics-panel {
            background: #102b28;
            border: 1px solid #234d47;
            border-radius: 14px;
            padding: 24px;
            box-sizing: border-box;
        }

        .panel-heading {
            margin-bottom: 25px;
        }

        .panel-heading h2 {
            margin: 0;
            color: #ffffff;
            font-size: 18px;
        }

        .panel-heading p {
            margin-top: 5px;
            color: #819c96;
            font-size: 12px;
        }


        /* =========================
           PROGRESS BARS
        ========================= */

        .score-row {
            margin-bottom: 24px;
        }

        .score-row:last-child {
            margin-bottom: 0;
        }

        .score-title {
            display: flex;
            justify-content: space-between;
            margin-bottom: 9px;
            color: #dfeae7;
            font-size: 13px;
        }

        .score-title strong {
            color: #ffffff;
        }

        .progress-background {
            height: 10px;
            background: #1c3b37;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: #36a269;
            border-radius: 10px;
        }

        .environment-bar {
            background: #4fc58a;
        }

        .social-bar {
            background: #58b6d1;
        }

        .governance-bar {
            background: #b6a86a;
        }


        /* =========================
           OVERALL SCORE
        ========================= */

        .overall-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 300px;
        }

        .overall-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1d4942;
            border-radius: 14px;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .overall-panel h2 {
            color: #ffffff;
            font-size: 18px;
            margin: 0;
        }

        .overall-number {
            color: #ffffff;
            font-size: 58px;
            font-weight: 700;
            margin-top: 15px;
        }

        .overall-label {
            color: #8ba59f;
            font-size: 12px;
            margin-top: 3px;
        }

        .overall-grade {
            margin-top: 18px;
            color: #69e39d;
            background: #173f38;
            border: 1px solid #28624f;
            padding: 7px 16px;
            border-radius: 18px;
            font-size: 13px;
            font-weight: 700;
        }


        /* =========================
           OPERATIONAL INDICATORS
        ========================= */

        .indicator-panel {
            background: #102b28;
            border: 1px solid #234d47;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 25px;
        }

        .indicator-header {
            margin-bottom: 18px;
        }

        .indicator-header h2 {
            color: #ffffff;
            font-size: 18px;
            margin: 0;
        }

        .indicator-header p {
            color: #819c96;
            font-size: 12px;
            margin-top: 5px;
        }

        .indicator-wrapper {
            overflow-x: auto;
        }

        .indicator-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        .indicator-table th {
            padding: 13px 12px;
            text-align: left;
            color: #8fa9a4;
            font-size: 12px;
            font-weight: 600;
            border-bottom: 1px solid #294b46;
        }

        .indicator-table td {
            padding: 15px 12px;
            color: #e8f0ee;
            font-size: 13px;
            border-bottom: 1px solid #203f3b;
        }

        .indicator-table tr:last-child td {
            border-bottom: none;
        }

        .indicator-table tr:hover {
            background: #14332f;
        }

        .monitored-status {
            color: #65dc98 !important;
            font-weight: 600;
        }


        /* =========================
           DATA INFORMATION
        ========================= */

        .data-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .data-card {
            background: #0c2422;
            border: 1px solid #234842;
            border-radius: 12px;
            padding: 18px;
        }

        .data-label {
            color: #78948e;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .data-value {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .score-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .analytics-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 650px) {

            .analytics-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .score-cards {
                grid-template-columns: 1fr;
            }

            .data-info {
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

        <div class="analytics-header">

            <div class="analytics-title">

                <h1>ESG Analytics</h1>

                <p>
                    Environmental, Social & Governance performance analysis
                </p>

            </div>


            <div class="analytics-status">

                <span class="status-dot"></span>

                Analytics Active

            </div>

        </div>



        <!-- =========================
             SCORE CARDS
        ========================= -->

        <div class="score-cards">


            <!-- ENVIRONMENTAL -->

            <div class="score-card">

                <div class="score-card-top">

                    <div class="score-icon">
                        🌿
                    </div>

                    <span class="score-type">
                        ENVIRONMENT
                    </span>

                </div>

                <div class="score-number">
                    <?php echo $environmental; ?>%
                </div>

                <div class="score-label">
                    Environmental Score
                </div>

                <span class="score-grade">
                    Grade <?php echo getGrade($environmental); ?>
                </span>

            </div>



            <!-- SOCIAL -->

            <div class="score-card">

                <div class="score-card-top">

                    <div class="score-icon">
                        👥
                    </div>

                    <span class="score-type">
                        SOCIAL
                    </span>

                </div>

                <div class="score-number">
                    <?php echo $social; ?>%
                </div>

                <div class="score-label">
                    Social Score
                </div>

                <span class="score-grade">
                    Grade <?php echo getGrade($social); ?>
                </span>

            </div>



            <!-- GOVERNANCE -->

            <div class="score-card">

                <div class="score-card-top">

                    <div class="score-icon">
                        🏛️
                    </div>

                    <span class="score-type">
                        GOVERNANCE
                    </span>

                </div>

                <div class="score-number">
                    <?php echo $governance; ?>%
                </div>

                <div class="score-label">
                    Governance Score
                </div>

                <span class="score-grade">
                    Grade <?php echo getGrade($governance); ?>
                </span>

            </div>



            <!-- OVERALL -->

            <div class="score-card">

                <div class="score-card-top">

                    <div class="score-icon">
                        📊
                    </div>

                    <span class="score-type">
                        OVERALL
                    </span>

                </div>

                <div class="score-number">
                    <?php echo $overall_score; ?>%
                </div>

                <div class="score-label">
                    Overall ESG Score
                </div>

                <span class="score-grade">
                    Grade <?php echo getGrade($overall_score); ?>
                </span>

            </div>


        </div>



        <!-- =========================
             PERFORMANCE + OVERALL
        ========================= -->

        <div class="analytics-grid">


            <!-- ESG PERFORMANCE -->

            <div class="analytics-panel">

                <div class="panel-heading">

                    <h2>ESG Performance</h2>

                    <p>
                        Current performance across all ESG dimensions
                    </p>

                </div>


                <!-- ENVIRONMENTAL -->

                <div class="score-row">

                    <div class="score-title">

                        <span>Environmental</span>

                        <strong>
                            <?php echo $environmental; ?>%
                        </strong>

                    </div>

                    <div class="progress-background">

                        <div
                            class="progress-bar environment-bar"
                            style="width: <?php echo $environmental; ?>%;">
                        </div>

                    </div>

                </div>



                <!-- SOCIAL -->

                <div class="score-row">

                    <div class="score-title">

                        <span>Social</span>

                        <strong>
                            <?php echo $social; ?>%
                        </strong>

                    </div>

                    <div class="progress-background">

                        <div
                            class="progress-bar social-bar"
                            style="width: <?php echo $social; ?>%;">
                        </div>

                    </div>

                </div>



                <!-- GOVERNANCE -->

                <div class="score-row">

                    <div class="score-title">

                        <span>Governance</span>

                        <strong>
                            <?php echo $governance; ?>%
                        </strong>

                    </div>

                    <div class="progress-background">

                        <div
                            class="progress-bar governance-bar"
                            style="width: <?php echo $governance; ?>%;">
                        </div>

                    </div>

                </div>

            </div>



            <!-- OVERALL SCORE -->

            <div class="analytics-panel overall-panel">

                <div class="overall-icon">
                    📈
                </div>

                <h2>
                    Overall ESG Performance
                </h2>

                <div class="overall-number">
                    <?php echo $overall_score; ?>%
                </div>

                <div class="overall-label">
                    Combined ESG Performance Score
                </div>

                <div class="overall-grade">
                    Grade <?php echo getGrade($overall_score); ?>
                </div>

            </div>


        </div>



        <!-- =========================
             OPERATIONAL INDICATORS
        ========================= -->

        <div class="indicator-panel">

            <div class="indicator-header">

                <h2>Operational Indicators</h2>

                <p>
                    Latest environmental and operational measurements
                </p>

            </div>


            <div class="indicator-wrapper">

                <table class="indicator-table">

                    <thead>

                        <tr>

                            <th>Indicator</th>

                            <th>Current Value</th>

                            <th>Unit</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>


                        <tr>

                            <td>⚡ Energy Consumption</td>

                            <td>
                                <?php echo $data['energy']; ?>
                            </td>

                            <td>kWh</td>

                            <td class="monitored-status">
                                ● Monitored
                            </td>

                        </tr>


                        <tr>

                            <td>💧 Water Usage</td>

                            <td>
                                <?php echo $data['water']; ?>
                            </td>

                            <td>Litres</td>

                            <td class="monitored-status">
                                ● Monitored
                            </td>

                        </tr>


                        <tr>

                            <td>🗑️ Waste Generated</td>

                            <td>
                                <?php echo $data['waste']; ?>
                            </td>

                            <td>kg</td>

                            <td class="monitored-status">
                                ● Monitored
                            </td>

                        </tr>


                        <tr>

                            <td>🌱 Carbon Footprint</td>

                            <td>
                                <?php echo $data['carbon']; ?>
                            </td>

                            <td>tCO₂e</td>

                            <td class="monitored-status">
                                ● Monitored
                            </td>

                        </tr>


                        <tr>

                            <td>🏭 Production</td>

                            <td>
                                <?php echo $data['production']; ?>
                            </td>

                            <td>Units</td>

                            <td class="monitored-status">
                                ● Monitored
                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>

        </div>



        <!-- =========================
             DATA INFORMATION
        ========================= -->

        <div class="data-info">


            <div class="data-card">

                <div class="data-label">
                    Data Source
                </div>

                <div class="data-value">
                    ESG Monitoring Database
                </div>

            </div>


            <div class="data-card">

                <div class="data-label">
                    Latest Record
                </div>

                <div class="data-value">
                    #<?php echo $data['id']; ?>
                </div>

            </div>


            <div class="data-card">

                <div class="data-label">
                    Last Updated
                </div>

                <div class="data-value">
                    <?php echo $data['created_at']; ?>
                </div>

            </div>


        </div>


    </main>

</div>

</body>

</html>