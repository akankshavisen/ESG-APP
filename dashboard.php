<?php

require_once "auth.php";

require_once "config/database.php";

$sql = "SELECT * FROM dashboard_metrics ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No dashboard data found.");
}
// Fetch recent alerts
$alert_sql = "SELECT * FROM alerts ORDER BY id DESC LIMIT 3";
$alert_result = $conn->query($alert_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ESG Sentinel Dashboard</title>

    <!-- Dashboard CSS -->
   <link rel="stylesheet" href="assets/css/dashboard.css?v=1001">
<style>
/* ================= DASHBOARD PROFESSIONAL UI ================= */

body {
    background: #0b1716 !important;
    color: #ffffff !important;
}

/* Main area */
.main-content {
    background: #0b1716 !important;
    color: #ffffff !important;
}

/* ================= HEADER ================= */

.top-header {
    margin-bottom: 28px !important;
}

.top-header h1 {
    color: #ffffff !important;
    font-size: 30px !important;
    font-weight: 700 !important;
    letter-spacing: -0.5px;
}

.top-header p {
    color: #a8bbb7 !important;
    margin-top: 6px !important;
}

/* ================= KPI CARDS ================= */

.kpi-grid {
    gap: 18px !important;
    margin-bottom: 25px !important;
}

.kpi-card {
    background: #142624 !important;
    border: 1px solid #23413d !important;
    border-radius: 14px !important;
    padding: 22px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.20) !important;
    transition: transform 0.2s ease, border-color 0.2s ease,
                box-shadow 0.2s ease !important;
}

.kpi-card:hover {
    transform: translateY(-3px);
    border-color: #3c806e !important;
    box-shadow: 0 12px 30px rgba(0,0,0,0.30) !important;
}

.kpi-card h3 {
    color: #a8bbb7 !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    margin-bottom: 12px !important;
}

.kpi-card .kpi-value {
    color: #ffffff !important;
    font-size: 30px !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
}

.kpi-card .kpi-unit {
    color: #7fa39b !important;
    font-size: 13px !important;
    margin-top: 5px !important;
}

/* ================= PANELS ================= */

.panel {
    background: #142624 !important;
    border: 1px solid #23413d !important;
    border-radius: 14px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.20) !important;
}

.panel h2 {
    color: #ffffff !important;
    font-size: 18px !important;
    font-weight: 600 !important;
}

/* ================= ESG SCORES ================= */

.score {
    margin-bottom: 22px !important;
}

.score-header {
    color: #ffffff !important;
}

.score-header span {
    color: #dce8e5 !important;
}

.score-header strong {
    color: #ffffff !important;
    font-weight: 700 !important;
}

.progress {
    background: #263b38 !important;
    height: 10px !important;
    border-radius: 10px !important;
    overflow: hidden !important;
}

.progress-bar {
    background: #4fd18b !important;
    height: 100% !important;
    border-radius: 10px !important;
}

/* ================= ALERTS ================= */

.alert {
    background: #1b312e !important;
    color: #dce8e5 !important;
    border: 1px solid #2d514b !important;
    border-radius: 9px !important;
    padding: 13px 15px !important;
    margin-bottom: 10px !important;
}

/* ================= DIGITAL TWIN ================= */

.digital-twin {
    background: #10201e !important;
    border: 1px dashed #3c665e !important;
    border-radius: 10px !important;
    padding: 18px !important;
}

.digital-twin p {
    color: #dce8e5 !important;
    margin-bottom: 7px !important;
}

.digital-twin small {
    color: #8da9a3 !important;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 900px) {

    .kpi-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }

}

@media (max-width: 600px) {

    .kpi-grid {
        grid-template-columns: 1fr !important;
    }

    .top-header h1 {
        font-size: 24px !important;
    }

}
/* ================= KPI ICONS ================= */

.kpi-icon {
    width: 42px !important;
    height: 42px !important;
    border-radius: 10px !important;
    background: #1d3b36 !important;
    border: 1px solid #315b53 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    font-size: 20px !important;
    margin-bottom: 15px !important;

    box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
}
/* ================= ESG PERFORMANCE ================= */

.score {
    padding: 14px 0 !important;
    margin-bottom: 8px !important;
}

.score-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 9px !important;
}

.score-header span {
    color: #dce8e5 !important;
    font-size: 14px !important;
    font-weight: 500 !important;
}

.score-header strong {
    color: #4fd18b !important;
    font-size: 15px !important;
    font-weight: 700 !important;
}

.progress {
    width: 100% !important;
    height: 9px !important;
    background: #263b38 !important;
    border-radius: 20px !important;
    overflow: hidden !important;
}

.progress-bar {
    height: 100% !important;
    background: linear-gradient(
        90deg,
        #36a269,
        #4fd18b
    ) !important;
    border-radius: 20px !important;
    transition: width 0.5s ease !important;
}
/* ================= DYNAMIC ALERT COLORS ================= */

.alert strong {
    display: inline-block !important;
    margin-right: 8px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}

.alert {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
}

/* Warning */
.alert-warning {
    background: #332b18 !important;
    border: 1px solid #80651f !important;
    color: #f6d365 !important;
}

/* Critical */
.alert-critical {
    background: #351d20 !important;
    border: 1px solid #7f3038 !important;
    color: #ff8f98 !important;
}

/* Normal */
.alert-normal {
    background: #183028 !important;
    border: 1px solid #2d6251 !important;
    color: #78d9ad !important;
}
/* ================= PROFESSIONAL DASHBOARD HEADER ================= */

.dashboard-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    gap: 20px !important;
    padding: 18px 22px !important;
    margin-bottom: 22px !important;

    background: linear-gradient(
        135deg,
        #102522,
        #132e2a
    ) !important;

    border: 1px solid #234b44 !important;
    border-radius: 14px !important;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.25) !important;
}

/* Title */

.dashboard-title {
    display: flex !important;
    align-items: center !important;
    gap: 14px !important;
}

.dashboard-title h1 {
    margin: 0 !important;
    color: #ffffff !important;
    font-size: 28px !important;
    font-weight: 700 !important;
}

.dashboard-title p {
    margin: 5px 0 0 0 !important;
    color: #8eaaa4 !important;
    font-size: 13px !important;
}

/* Small icon */

.title-icon {
    width: 46px !important;
    height: 46px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    border-radius: 12px !important;

    background: #173c35 !important;
    border: 1px solid #347461 !important;

    color: #4fd18b !important;
    font-size: 25px !important;
    font-weight: 700 !important;
}

/* System status */

.system-status {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;

    padding: 10px 15px !important;

    background: #10201e !important;
    border: 1px solid #285148 !important;
    border-radius: 10px !important;

    white-space: nowrap !important;
}

.system-status strong {
    display: block !important;
    color: #4fd18b !important;
    font-size: 13px !important;
}

.system-status small {
    display: block !important;
    color: #78958e !important;
    font-size: 10px !important;
    margin-top: 2px !important;
}

.status-dot {
    width: 9px !important;
    height: 9px !important;

    background: #36d98b !important;
    border-radius: 50% !important;

    box-shadow: 0 0 10px #36d98b !important;
}

/* Mobile */

@media (max-width: 700px) {

    .dashboard-header {
        align-items: flex-start !important;
        flex-direction: column !important;
    }

    .system-status {
        width: 100% !important;
        box-sizing: border-box !important;
    }

}
/* ================= PROFESSIONAL KPI CARDS ================= */

.kpi-card {
    position: relative !important;
    overflow: hidden !important;
    padding: 20px !important;
    min-height: 145px !important;
}

.kpi-card::after {
    content: "" !important;
    position: absolute !important;
    width: 80px !important;
    height: 80px !important;
    right: -30px !important;
    bottom: -30px !important;
    border-radius: 50% !important;
    background: rgba(79, 209, 139, 0.06) !important;
}

.kpi-top {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin-bottom: 15px !important;
}

.kpi-icon {
    margin: 0 !important;
    width: 40px !important;
    height: 40px !important;
    border-radius: 10px !important;
    background: #173c35 !important;
    border: 1px solid #326d5e !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 19px !important;
}

.kpi-status {
    color: #4fd18b !important;
    background: rgba(54, 162, 105, 0.10) !important;
    border: 1px solid #285b4c !important;
    padding: 4px 8px !important;
    border-radius: 20px !important;
    font-size: 10px !important;
    font-weight: 600 !important;
}

.kpi-card h3 {
    margin: 0 0 7px 0 !important;
    color: #9eb5b0 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
}

.kpi-card .kpi-value {
    color: #ffffff !important;
    font-size: 29px !important;
    font-weight: 700 !important;
    line-height: 1.1 !important;
}

.kpi-card .kpi-unit {
    color: #6f9189 !important;
    font-size: 11px !important;
    margin-top: 5px !important;
}
/* ================= ESG SCORE CARDS ================= */

.esg-performance-panel {
    padding: 22px !important;
}

.section-heading {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    margin-bottom: 20px !important;
}

.section-heading h2 {
    margin: 0 !important;
    color: #ffffff !important;
}

.section-heading p {
    margin: 5px 0 0 0 !important;
    color: #78958e !important;
    font-size: 12px !important;
}

.esg-live {
    color: #4fd18b !important;
    font-size: 11px !important;
    background: #102c25 !important;
    border: 1px solid #285b4c !important;
    padding: 5px 9px !important;
    border-radius: 20px !important;
}

.esg-score-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 14px !important;
}

.esg-score-card {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    gap: 14px !important;
    padding: 18px !important;
    min-height: 105px !important;

    background: #102522 !important;
    border: 1px solid #285148 !important;
    border-radius: 12px !important;

    transition: transform 0.2s ease,
                border-color 0.2s ease !important;
}

.esg-score-card:hover {
    transform: translateY(-3px) !important;
    border-color: #4a8878 !important;
}

.esg-score-icon {
    width: 58px !important;
    height: 58px !important;
    min-width: 58px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    border-radius: 50% !important;

    background: #173c35 !important;
    border: 2px solid #36a269 !important;

    font-size: 24px !important;
}

.esg-score-info h3 {
    margin: 0 0 5px 0 !important;
    color: #b7cbc6 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
}

.esg-grade {
    color: #4fd18b !important;
    font-size: 23px !important;
    font-weight: 700 !important;
    line-height: 1 !important;
}

.esg-score-percent {
    display: block !important;
    margin-top: 5px !important;
    color: #78958e !important;
    font-size: 11px !important;
}

/* Different subtle accents */

.esg-score-card.social .esg-score-icon {
    border-color: #3b9bd6 !important;
}

.esg-score-card.social .esg-grade {
    color: #55b7ef !important;
}

.esg-score-card.governance .esg-score-icon {
    border-color: #c99b45 !important;
}

.esg-score-card.governance .esg-grade {
    color: #e0b65e !important;
}


/* Responsive */

@media (max-width: 850px) {

    .esg-score-grid {
        grid-template-columns: 1fr !important;
    }

}
/* ================= STEP 10 - ALERTS & OVERALL SCORE ================= */

.alerts-panel {
    padding: 22px !important;
}

.alert-count {
    min-width: 24px !important;
    height: 24px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 50% !important;
    background: #173c35 !important;
    border: 1px solid #326d5e !important;
    color: #4fd18b !important;
    font-size: 11px !important;
    font-weight: 700 !important;
}

.alerts-list {
    display: flex !important;
    flex-direction: column !important;
    gap: 10px !important;
}

.alerts-list .alert {
    margin-bottom: 0 !important;
    min-height: 48px !important;
    box-sizing: border-box !important;
}

.alert-indicator {
    width: 7px !important;
    height: 7px !important;
    min-width: 7px !important;
    border-radius: 50% !important;
}

.alert-content {
    display: flex !important;
    flex-direction: column !important;
    gap: 3px !important;
}

.alert-content strong {
    margin: 0 !important;
}

.alert-content span {
    font-size: 11px !important;
}


/* ================= OVERALL ESG ================= */

.overall-esg-card {
    margin-top: 20px !important;
    padding: 24px !important;
    background: linear-gradient(
        135deg,
        #102522,
        #132e2a
    ) !important;
}

.overall-esg-content {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 25px !important;
}

.overall-label {
    color: #4fd18b !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    letter-spacing: 1.5px !important;
    margin-bottom: 6px !important;
}

.overall-esg-info h2 {
    margin: 0 !important;
    color: #ffffff !important;
    font-size: 21px !important;
}

.overall-esg-info p {
    margin: 6px 0 12px 0 !important;
    color: #8da9a3 !important;
    font-size: 12px !important;
}

.overall-status {
    display: inline-block !important;
    color: #4fd18b !important;
    background: #102c25 !important;
    border: 1px solid #285b4c !important;
    border-radius: 20px !important;
    padding: 5px 10px !important;
    font-size: 10px !important;
}

.overall-score-circle {
    width: 105px !important;
    height: 105px !important;
    min-width: 105px !important;
    border-radius: 50% !important;

    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;

    background: #10201e !important;
    border: 6px solid #36a269 !important;

    box-shadow:
        0 0 0 5px rgba(54,162,105,0.08),
        0 8px 25px rgba(0,0,0,0.25) !important;
}

.overall-score-value {
    color: #4fd18b !important;
    font-size: 23px !important;
    font-weight: 700 !important;
    line-height: 1 !important;
}

.overall-score-label {
    margin-top: 5px !important;
    color: #78958e !important;
    font-size: 8px !important;
    letter-spacing: 1px !important;
}


/* ================= RESPONSIVE ================= */

@media (max-width: 850px) {

    .overall-esg-content {
        flex-direction: column !important;
        align-items: flex-start !important;
    }

    .overall-score-circle {
        align-self: center !important;
    }

}
/* ================= ESG ANALYTICS CHART ================= */

.chart-panel {
    margin-top: 20px !important;
    padding: 22px !important;
}

.chart-container {
    position: relative !important;
    width: 100% !important;
    height: 280px !important;
    margin-top: 20px !important;
}

@media (max-width: 600px) {
    .chart-container {
        height: 230px !important;
    }
}
/* ================= QUICK ACTIONS ================= */

.quick-actions-panel {
    margin-top: 20px !important;
    padding: 22px !important;
}

.quick-status {
    color: #4fd18b !important;
    background: #102c25 !important;
    border: 1px solid #285b4c !important;
    padding: 5px 10px !important;
    border-radius: 20px !important;
    font-size: 10px !important;
}

.quick-actions-grid {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 12px !important;
}

.quick-action {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    gap: 11px !important;
    min-height: 72px !important;
    padding: 13px !important;

    box-sizing: border-box !important;

    background: #102522 !important;
    border: 1px solid #285148 !important;
    border-radius: 11px !important;

    text-decoration: none !important;

    transition: all 0.2s ease !important;
}

.quick-action:hover {
    transform: translateY(-3px) !important;
    border-color: #4a8878 !important;
    background: #142e2a !important;
}

.quick-action-icon {
    width: 38px !important;
    height: 38px !important;
    min-width: 38px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    background: #173c35 !important;
    border: 1px solid #326d5e !important;
    border-radius: 9px !important;

    font-size: 17px !important;
}

.quick-action-content {
    min-width: 0 !important;
}

.quick-action-content strong {
    display: block !important;
    color: #ffffff !important;
    font-size: 12px !important;
    font-weight: 600 !important;
}

.quick-action-content span {
    display: block !important;
    margin-top: 3px !important;
    color: #78958e !important;
    font-size: 9px !important;
    line-height: 1.3 !important;
}

.quick-arrow {
    margin-left: auto !important;
    color: #4fd18b !important;
    font-size: 18px !important;
}

@media (max-width: 1000px) {

    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }

}

@media (max-width: 600px) {

    .quick-actions-grid {
        grid-template-columns: 1fr !important;
    }

}
</style>
</head>

<body>

<div class="dashboard-container">

    <!-- ================= SIDEBAR ================= -->

    <?php include "sidebar.php"; ?>

    <!-- ================= MAIN CONTENT ================= -->

    <main class="main-content">


        <!-- HEADER -->

        <div class="top-header dashboard-header">

    <div class="dashboard-title">

        <div class="title-icon">
            ◈
        </div>

        <div>
            <h1>ESG Overview</h1>

            <p>
                Real-time Environmental, Social & Governance Performance
            </p>
        </div>

    </div>

    <div class="system-status">

        <span class="status-dot"></span>

        <div>
            <strong>System Online</strong>
            <small>ESG Monitoring Active</small>
        </div>

    </div>

</div>
        <!-- ================= KPI CARDS ================= -->

        <div class="kpi-grid">


            <!-- ENERGY -->

          <div class="kpi-card">

    <div class="kpi-top">

        <div class="kpi-icon">⚡</div>

        <span class="kpi-status">Live</span>

    </div>

    <h3>Energy Consumption</h3>

    <div class="kpi-value">
        <?php echo $data['energy']; ?>
    </div>

    <div class="kpi-unit">
        kWh
    </div>

</div>
            <!-- WATER -->

          <div class="kpi-card">

    
  <div class="kpi-top">

    <div class="kpi-icon">💧</div>

    <span class="kpi-status">Live</span>

</div>

<h3>Water Usage</h3>

<div class="kpi-value">
    <?php echo $data['water']; ?>
</div>

<div class="kpi-unit">
    Litres
</div>
</div>  

        


            <!-- WASTE -->

            <div class="kpi-card">
                <div class="kpi-top">
    <div class="kpi-icon">🗑️</div>
    <span class="kpi-status">Live</span>
</div>
                <h3>Waste Generated</h3>

                <div class="kpi-value">

                    <?php echo $data['waste']; ?>

                </div>

                <div class="kpi-unit">
                    kg
                </div>

            </div>


            <!-- PRODUCTION -->

            <div class="kpi-card">
                <div class="kpi-top">
    <div class="kpi-icon">🏭</div>
    <span class="kpi-status">Live</span>
</div>
                <h3>Production</h3>

                <div class="kpi-value">

                    <?php echo $data['production']; ?>

                </div>

                <div class="kpi-unit">
                    Units
                </div>

            </div>


            <!-- CARBON -->

            <div class="kpi-card">
                <div class="kpi-top">
    <div class="kpi-icon">🌱</div>
    <span class="kpi-status">Live</span>
</div>
                <h3>Carbon Footprint</h3>

                <div class="kpi-value">

                    <?php echo $data['carbon']; ?>

                </div>

                <div class="kpi-unit">
                    tCO₂e
                </div>

            </div>


        </div>


        
       <!-- ================= LOWER DASHBOARD ================= -->

<div class="dashboard-grid">

    <!-- ================= ESG PERFORMANCE ================= -->

    <div class="panel esg-performance-panel">

        <div class="section-heading">

            <div>
                <h2>ESG Performance</h2>
                <p>Current sustainability performance</p>
            </div>

            <span class="esg-live">
                ● Live
            </span>

        </div>

        <div class="esg-score-grid">

            <!-- ENVIRONMENTAL -->

            <div class="esg-score-card environmental">

                <div class="esg-score-icon">
                    🌿
                </div>

                <div class="esg-score-info">

                    <h3>Environmental</h3>

                    <div class="esg-grade">

                        <?php
                        echo ($data['environmental_score'] >= 90) ? 'A+' :
                             (($data['environmental_score'] >= 80) ? 'A' :
                             (($data['environmental_score'] >= 70) ? 'B+' : 'B'));
                        ?>

                    </div>

                    <span class="esg-score-percent">
                        <?php echo $data['environmental_score']; ?>%
                    </span>

                </div>

            </div>


            <!-- SOCIAL -->

            <div class="esg-score-card social">

                <div class="esg-score-icon">
                    👥
                </div>

                <div class="esg-score-info">

                    <h3>Social</h3>

                    <div class="esg-grade">

                        <?php
                        echo ($data['social_score'] >= 90) ? 'A+' :
                             (($data['social_score'] >= 80) ? 'A' :
                             (($data['social_score'] >= 70) ? 'B+' : 'B'));
                        ?>

                    </div>

                    <span class="esg-score-percent">
                        <?php echo $data['social_score']; ?>%
                    </span>

                </div>

            </div>


            <!-- GOVERNANCE -->

            <div class="esg-score-card governance">

                <div class="esg-score-icon">
                    🏛️
                </div>

                <div class="esg-score-info">

                    <h3>Governance</h3>

                    <div class="esg-grade">

                        <?php
                        echo ($data['governance_score'] >= 90) ? 'A+' :
                             (($data['governance_score'] >= 80) ? 'A' :
                             (($data['governance_score'] >= 70) ? 'B+' : 'B'));
                        ?>

                    </div>

                    <span class="esg-score-percent">
                        <?php echo $data['governance_score']; ?>%
                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= RECENT ALERTS ================= -->

    <div class="panel alerts-panel">

        <div class="section-heading">

            <div>
                <h2>Recent Alerts</h2>
                <p>Latest system notifications</p>
            </div>

            <span class="alert-count">
                <?php
                echo ($alert_result) ? $alert_result->num_rows : 0;
                ?>
            </span>

        </div>


        <div class="alerts-list">

            <?php

            if ($alert_result && $alert_result->num_rows > 0):

                while ($alert = $alert_result->fetch_assoc()):

            ?>

                <div class="alert
                    <?php
                    if (strtolower($alert['severity']) === 'critical') {
                        echo 'alert-critical';
                    } elseif (strtolower($alert['severity']) === 'warning') {
                        echo 'alert-warning';
                    } else {
                        echo 'alert-normal';
                    }
                    ?>
                ">

                    <div class="alert-indicator"></div>

                    <div class="alert-content">

                        <strong>
                            <?php echo htmlspecialchars($alert['severity']); ?>
                        </strong>

                        <span>
                            <?php echo htmlspecialchars($alert['message']); ?>
                        </span>

                    </div>

                </div>

            <?php

                endwhile;

            else:

            ?>

                <div class="alert alert-normal">

                    <div class="alert-indicator"></div>

                    <div class="alert-content">

                        <strong>Normal</strong>

                        <span>No active alerts.</span>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<!-- ================= OVERALL ESG SCORE ================= -->

<?php

$overall_score = round(
    (
        $data['environmental_score'] +
        $data['social_score'] +
        $data['governance_score']
    ) / 3,
    1
);

?>

<div class="panel overall-esg-card">

    <div class="overall-esg-content">

        <div class="overall-esg-info">

            <div class="overall-label">
                ESG PERFORMANCE
            </div>

            <h2>Overall ESG Score</h2>

            <p>
                Combined Environmental, Social & Governance performance
            </p>

            <div class="overall-status">
                ● Monitoring Active
            </div>

        </div>


        <div class="overall-score-circle">

            <div class="overall-score-value">
                <?php echo $overall_score; ?>%
            </div>

            <div class="overall-score-label">
                ESG SCORE
            </div>

        </div>

    </div>

</div>
<!-- ================= ESG ANALYTICS CHART ================= -->

<div class="panel chart-panel">

    <div class="section-heading">

        <div>
            <h2>ESG Score Analytics</h2>
            <p>Environmental, Social & Governance comparison</p>
        </div>

        <span class="esg-live">● Current Scores</span>

    </div>

    <div class="chart-container">
        <canvas id="esgScoreChart"></canvas>
    </div>

</div>
<!-- ================= QUICK ACTIONS ================= -->

<div class="panel quick-actions-panel">

    <div class="section-heading">

        <div>
            <h2>Quick Actions</h2>
            <p>Frequently used ESG management tools</p>
        </div>

        <span class="quick-status">
            ● Ready
        </span>

    </div>


    <div class="quick-actions-grid">


        <!-- REPORTS -->

        <a href="reports.php" class="quick-action">

            <div class="quick-action-icon">
                📄
            </div>

            <div class="quick-action-content">

                <strong>View Reports</strong>

                <span>Review ESG evidence and reports</span>

            </div>

            <div class="quick-arrow">
                →
            </div>

        </a>


        <!-- UPLOAD -->

        <a href="upload_report.php" class="quick-action">

            <div class="quick-action-icon">
                ⬆️
            </div>

            <div class="quick-action-content">

                <strong>Upload Report</strong>

                <span>Add a new ESG report</span>

            </div>

            <div class="quick-arrow">
                →
            </div>

        </a>


        <!-- ALERTS -->

        <a href="alerts.php" class="quick-action">

            <div class="quick-action-icon">
                🔔
            </div>

            <div class="quick-action-content">

                <strong>Manage Alerts</strong>

                <span>Check and manage alerts</span>

            </div>

            <div class="quick-arrow">
                →
            </div>

        </a>


        <!-- WHAT IF -->

        <a href="what_if.php" class="quick-action">

            <div class="quick-action-icon">
                📊
            </div>

            <div class="quick-action-content">

                <strong>What-if Simulation</strong>

                <span>Explore sustainability scenarios</span>

            </div>

            <div class="quick-arrow">
                →
            </div>

        </a>

    </div>

</div>
 <!-- ================= DIGITAL TWIN ================= -->

        <div class="panel">

            <h2>Digital Twin</h2>

            <div class="digital-twin">

                <p>
                    Digital Twin module will be integrated here.
                </p>

                <small>
                    This module is being developed separately by the team.
                </small>

            </div>

        </div>


    </main>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const chartCanvas = document.getElementById('esgScoreChart');

if (chartCanvas) {

    new Chart(chartCanvas, {
        type: 'bar',

        data: {
            labels: [
                'Environmental',
                'Social',
                'Governance'
            ],

            datasets: [{
                label: 'ESG Score (%)',

                data: [
                    <?php echo (float)$data['environmental_score']; ?>,
                    <?php echo (float)$data['social_score']; ?>,
                    <?php echo (float)$data['governance_score']; ?>
                ],

                backgroundColor: [
                    'rgba(79, 209, 139, 0.75)',
                    'rgba(85, 183, 239, 0.75)',
                    'rgba(224, 182, 94, 0.75)'
                ],

                borderColor: [
                    '#4fd18b',
                    '#55b7ef',
                    '#e0b65e'
                ],

                borderWidth: 1,
                borderRadius: 8,
                maxBarThickness: 65
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    labels: {
                        color: '#dce8e5'
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 100,

                    ticks: {
                        color: '#8da9a3'
                    },

                    grid: {
                        color: 'rgba(141, 169, 163, 0.12)'
                    }
                },

                x: {
                    ticks: {
                        color: '#dce8e5'
                    },

                    grid: {
                        display: false
                    }
                }
            }
        }
    });

}
</script>

</body>

</html>