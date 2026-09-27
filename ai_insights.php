<?php

require_once "config/database.php";

/* GET LATEST ESG DATA */
$sql = "SELECT * FROM dashboard_metrics ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No ESG data available.");
}

/* OVERALL ESG SCORE */
$overall_score = round(
    (
        $data['environmental_score'] +
        $data['social_score'] +
        $data['governance_score']
    ) / 3,
    1
);

/* AI-STYLE INSIGHTS */
$insights = [];

/* ENVIRONMENTAL */
if ($data['environmental_score'] < 60) {

    $insights[] = [
        "type" => "warning",
        "icon" => "⚠️",
        "title" => "Environmental Performance Needs Attention",
        "message" => "Environmental score is relatively low. Energy, water and waste management should be reviewed."
    ];

} elseif ($data['environmental_score'] < 80) {

    $insights[] = [
        "type" => "info",
        "icon" => "💡",
        "title" => "Environmental Performance is Moderate",
        "message" => "Environmental performance is moderate. Further optimization of resource consumption can improve the score."
    ];

} else {

    $insights[] = [
        "type" => "success",
        "icon" => "✓",
        "title" => "Strong Environmental Performance",
        "message" => "Environmental performance is currently strong based on the available ESG data."
    ];
}

/* SOCIAL */
if ($data['social_score'] < 60) {

    $insights[] = [
        "type" => "warning",
        "icon" => "⚠️",
        "title" => "Social Performance Needs Attention",
        "message" => "Social indicators should be reviewed and improvement opportunities should be identified."
    ];

} elseif ($data['social_score'] < 80) {

    $insights[] = [
        "type" => "info",
        "icon" => "💡",
        "title" => "Social Performance is Moderate",
        "message" => "Social performance is moderate. Additional employee and community initiatives may improve the score."
    ];

} else {

    $insights[] = [
        "type" => "success",
        "icon" => "✓",
        "title" => "Strong Social Performance",
        "message" => "Social performance is currently strong based on the available data."
    ];
}

/* GOVERNANCE */
if ($data['governance_score'] < 60) {

    $insights[] = [
        "type" => "warning",
        "icon" => "⚠️",
        "title" => "Governance Requires Review",
        "message" => "Governance indicators should be reviewed for compliance, transparency and accountability."
    ];

} elseif ($data['governance_score'] < 80) {

    $insights[] = [
        "type" => "info",
        "icon" => "💡",
        "title" => "Governance Performance is Moderate",
        "message" => "Governance performance is moderate. Regular compliance and audit monitoring is recommended."
    ];

} else {

    $insights[] = [
        "type" => "success",
        "icon" => "✓",
        "title" => "Strong Governance Performance",
        "message" => "Governance performance is currently strong based on the available data."
    ];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AI Insights - ESG Sentinel</title>

<link rel="stylesheet" href="assets/css/dashboard.css?v=1002">

<style>

/* ==============================
   AI INSIGHTS PAGE
============================== */

.ai-page-header {
    margin-bottom: 26px;
}

.ai-page-header h1 {
    margin: 0 0 7px 0;
    color: #ffffff;
    font-size: 28px;
    font-weight: 700;
}

.ai-page-header p {
    margin: 0;
    color: #9bb7b2;
    font-size: 14px;
}

/* AI STATUS */

.ai-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 15px;
    padding: 8px 13px;
    border-radius: 20px;
    background: #123c35;
    border: 1px solid #245c51;
    color: #70e0a4;
    font-size: 12px;
    font-weight: 600;
}

.ai-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #36c978;
}

/* ==============================
   SUMMARY CARDS
============================== */

.insight-summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 26px;
}

.summary-card {
    position: relative;
    background: #12322f;
    border: 1px solid #28534c;
    border-radius: 13px;
    padding: 20px;
    overflow: hidden;
}

.summary-card::after {
    content: "";
    position: absolute;
    width: 70px;
    height: 70px;
    right: -25px;
    bottom: -25px;
    border-radius: 50%;
    background: rgba(79, 209, 139, 0.08);
}

.summary-card h3 {
    margin: 0 0 12px 0;
    color: #9ab5b0;
    font-size: 13px;
    font-weight: 500;
}

.summary-value {
    color: #ffffff !important;
    font-size: 29px;
    font-weight: 700;
}

.summary-label {
    display: block;
    margin-top: 5px;
    color: #6fa99d;
    font-size: 11px;
}

/* ==============================
   MAIN AI PANEL
============================== */

.ai-panel {
    background: #102d2a;
    border: 1px solid #28534c;
    border-radius: 14px;
    padding: 23px;
    margin-bottom: 24px;
}

.ai-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.ai-panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.ai-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #17483f;
    color: #70e0a4;
    font-size: 18px;
}

.ai-panel h2 {
    margin: 0;
    color: #ffffff;
    font-size: 19px;
}

.ai-panel-subtitle {
    color: #799c96;
    font-size: 12px;
}

/* ==============================
   INSIGHT CARDS
============================== */

.insight {
    display: flex;
    gap: 15px;
    padding: 17px;
    margin-bottom: 12px;
    border-radius: 11px;
    background: #163632;
    border: 1px solid #294f49;
    border-left: 4px solid;
}

.insight:last-child {
    margin-bottom: 0;
}

.insight-icon {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(255,255,255,0.06);
    font-size: 16px;
}

.insight-content {
    flex: 1;
}

.insight h3 {
    margin: 0 0 6px 0;
    color: #ffffff;
    font-size: 14px;
}

.insight p {
    margin: 0;
    color: #9ab3ae;
    line-height: 1.6;
    font-size: 13px;
}

.insight.warning {
    border-left-color: #f0ad4e;
}

.insight.info {
    border-left-color: #5ba9e6;
}

.insight.success {
    border-left-color: #4fd18b;
}

/* ==============================
   RECOMMENDATIONS
============================== */

.recommendation {
    background: #102d2a;
    border: 1px solid #28534c;
    border-radius: 14px;
    padding: 23px;
}

.recommendation-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}

.recommendation-header h2 {
    margin: 0;
    color: #ffffff;
    font-size: 19px;
}

.recommendation-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #17483f;
    color: #70e0a4;
}

.recommendation ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.recommendation li {
    position: relative;
    padding: 12px 12px 12px 28px;
    margin-bottom: 8px;
    border-radius: 8px;
    background: #153632;
    color: #a9c0bb;
    font-size: 13px;
    line-height: 1.5;
}

.recommendation li::before {
    content: "✓";
    position: absolute;
    left: 10px;
    color: #4fd18b;
    font-weight: bold;
}

/* ==============================
   RESPONSIVE
============================== */

@media (max-width: 1100px) {

    .insight-summary {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 650px) {

    .insight-summary {
        grid-template-columns: 1fr;
    }

    .ai-panel,
    .recommendation {
        padding: 16px;
    }

    .ai-panel-header {
        align-items: flex-start;
        gap: 10px;
        flex-direction: column;
    }

    .summary-value {
        font-size: 25px;
    }

}

</style>

</head>

<body>

<div class="dashboard-container">

<?php include "sidebar.php"; ?>

<main class="main-content">

    <!-- HEADER -->

    <div class="ai-page-header">

        <h1>AI Insights</h1>

        <p>
            Intelligent analysis of current ESG performance
        </p>

        <div class="ai-status">
            <span class="ai-status-dot"></span>
            AI Analysis Active
        </div>

    </div>


    <!-- SUMMARY -->

    <div class="insight-summary">

        <div class="summary-card">

            <h3>Overall ESG Score</h3>

            <div class="summary-value">
                <?php echo $overall_score; ?>%
            </div>

            <span class="summary-label">
                Combined ESG performance
            </span>

        </div>


        <div class="summary-card">

            <h3>Environmental</h3>

            <div class="summary-value">
                <?php echo $data['environmental_score']; ?>%
            </div>

            <span class="summary-label">
                Environmental performance
            </span>

        </div>


        <div class="summary-card">

            <h3>Social</h3>

            <div class="summary-value">
                <?php echo $data['social_score']; ?>%
            </div>

            <span class="summary-label">
                Social performance
            </span>

        </div>


        <div class="summary-card">

            <h3>Governance</h3>

            <div class="summary-value">
                <?php echo $data['governance_score']; ?>%
            </div>

            <span class="summary-label">
                Governance performance
            </span>

        </div>

    </div>


    <!-- GENERATED INSIGHTS -->

    <div class="ai-panel">

        <div class="ai-panel-header">

            <div class="ai-panel-title">

                <div class="ai-icon">🤖</div>

                <div>

                    <h2>Generated Insights</h2>

                </div>

            </div>

            <span class="ai-panel-subtitle">
                Based on latest ESG data
            </span>

        </div>


        <?php foreach ($insights as $insight): ?>

            <div class="insight <?php echo $insight['type']; ?>">

                <div class="insight-icon">
                    <?php echo $insight['icon']; ?>
                </div>

                <div class="insight-content">

                    <h3>
                        <?php echo $insight['title']; ?>
                    </h3>

                    <p>
                        <?php echo $insight['message']; ?>
                    </p>

                </div>

            </div>

        <?php endforeach; ?>

    </div>


    <!-- RECOMMENDATIONS -->

    <div class="recommendation">

        <div class="recommendation-header">

            <div class="recommendation-icon">
                ✓
            </div>

            <h2>Recommended Actions</h2>

        </div>


        <ul>

            <li>
                Continue monitoring energy consumption
                and identify unnecessary usage.
            </li>

            <li>
                Monitor water usage regularly and
                investigate unusual consumption.
            </li>

            <li>
                Track waste generation and identify
                opportunities for waste reduction.
            </li>

            <li>
                Maintain regular governance,
                compliance and audit reviews.
            </li>

            <li>
                Continue collecting reliable ESG
                evidence for future reporting.
            </li>

        </ul>

    </div>

</main>

</div>

</body>

</html>