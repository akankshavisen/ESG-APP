<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config/database.php";

/* GET LATEST ESG DATA */
$sql = "SELECT * FROM dashboard_metrics ORDER BY id DESC LIMIT 1";

$result = $conn->query($sql);

if (!$result) {
    die("Database Query Error: " . $conn->error);
}

if ($result->num_rows == 0) {
    die("No ESG data found.");
}

$data = $result->fetch_assoc();

/* CURRENT VALUES */

$energy = (float)$data['energy'];
$water  = (float)$data['water'];
$waste  = (float)$data['waste'];
$carbon = (float)$data['carbon'];

/* DEFAULT */

$change = 0;

$energy_result = $energy;
$water_result  = $water;
$waste_result  = $waste;
$carbon_result = $carbon;

/* SIMULATION */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $change = floatval($_POST["change"]);

    $factor = 1 + ($change / 100);

    $energy_result = $energy * $factor;
    $water_result  = $water * $factor;
    $waste_result  = $waste * $factor;
    $carbon_result = $carbon * $factor;
}

/* DIFFERENCE */

$energy_difference = $energy_result - $energy;
$water_difference  = $water_result - $water;
$waste_difference  = $waste_result - $waste;
$carbon_difference = $carbon_result - $carbon;

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>What-If Simulation | ESG Sentinel</title>

<link rel="stylesheet" href="assets/css/dashboard.css?v=1002">

<style>

/* ==============================
   PAGE HEADER
============================== */

.sim-page-header {
    margin-bottom: 25px;
}

.sim-page-header h1 {
    margin: 0 0 6px 0;
    color: #ffffff;
    font-size: 28px;
    font-weight: 700;
}

.sim-page-header p {
    margin: 0;
    color: #9ab7b1;
    font-size: 14px;
}


/* ==============================
   SIMULATION INPUT
============================== */

.simulation-box {
    background: #102d2a;
    border: 1px solid #28534c;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 25px;
}

.simulation-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.simulation-icon {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #17483f;
    color: #70e0a4;
    font-size: 19px;
}

.simulation-box h2 {
    margin: 0;
    color: #172f25;
    font-size: 19px;
}

.info-text {
    color: #91aaa5;
    font-size: 13px;
    line-height: 1.6;
}

.simulation-box .info-text {
    margin-bottom: 20px;
}

.simulation-box label {
    display: block;
    margin-bottom: 8px;
    color: #1f2524;
    font-size: 13px;
    font-weight: 600;
}

.input-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.simulation-input {
    width: 260px;
    padding: 12px 14px;
    box-sizing: border-box;
    border: 1px solid #42645e;
    border-radius: 8px;
    background: #0b2422;
    color: #ffffff;
    font-size: 15px;
    outline: none;
}

.simulation-input:focus {
    border-color: #4fd18b;
    box-shadow: 0 0 0 2px rgba(79, 209, 139, 0.12);
}

.simulation-input::placeholder {
    color: #6f8d88;
}

.simulate-btn {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    background: #36a269;
    color: #ffffff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.simulate-btn:hover {
    background: #2d8958;
    transform: translateY(-1px);
}


/* ==============================
   SCENARIO BADGE
============================== */

.scenario-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 20px;
    background: #163d36;
    border: 1px solid #2b5b51;
    color: #70e0a4;
    font-size: 12px;
    font-weight: 600;
}

.scenario-badge span {
    color: #282323;
}


/* ==============================
   RESULT PANEL
============================== */

.result-panel {
    background: #102d2a;
    border: 1px solid #28534c;
    border-radius: 14px;
    padding: 23px;
    margin-bottom: 25px;
}

.result-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.result-panel h2 {
    margin: 0;
    color: #1e1c1c;
    font-size: 19px;
}


/* ==============================
   RESULT CARDS
============================== */

.result-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 17px;
}

.result-card {
    position: relative;
    background: #153632;
    border: 1px solid #2a514b;
    border-radius: 11px;
    padding: 19px;
    overflow: hidden;
}

.result-card::after {
    content: "";
    position: absolute;
    width: 60px;
    height: 60px;
    right: -20px;
    bottom: -20px;
    border-radius: 50%;
    background: rgba(79, 209, 139, 0.07);
}

.result-card h3 {
    margin: 0 0 11px 0;
    color: #9bb5af;
    font-size: 13px;
    font-weight: 500;
}

.metric-icon {
    margin-bottom: 10px;
    font-size: 18px;
}

.result-value {
    color: #161515;
    font-size: 25px;
    font-weight: 700;
}

.result-unit {
    margin-top: 5px;
    color: #6f9990;
    font-size: 11px;
}

.change-value {
    margin-top: 9px;
    font-size: 11px;
    font-weight: 600;
}

.change-positive {
    color: #f0ad4e;
}

.change-negative {
    color: #4fd18b;
}

.change-neutral {
    color: #91aaa5;
}


/* ==============================
   BASELINE
============================== */

.baseline-panel {
    background: #102d2a;
    border: 1px solid #28534c;
    border-radius: 14px;
    padding: 23px;
}

.baseline-panel h2 {
    margin: 0 0 18px 0;
    color: #ffffff;
    font-size: 19px;
}

.baseline-table {
    width: 100%;
    border-collapse: collapse;
}

.baseline-table th {
    padding: 12px 14px;
    text-align: left;
    color: #7fa39c;
    background: #0d2825;
    border-bottom: 1px solid #2a4d48;
    font-size: 12px;
    font-weight: 600;
}

.baseline-table td {
    padding: 13px 14px;
    color: #d4e1de;
    border-bottom: 1px solid #23443f;
    font-size: 13px;
}

.baseline-table tr:last-child td {
    border-bottom: none;
}

.baseline-table td:last-child {
    color: #ffffff;
    font-weight: 600;
}


/* ==============================
   RESPONSIVE
============================== */

@media (max-width: 1050px) {

    .result-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 650px) {

    .result-grid {
        grid-template-columns: 1fr;
    }

    .input-row {
        align-items: stretch;
        flex-direction: column;
    }

    .simulation-input {
        width: 100%;
    }

    .simulate-btn {
        width: 100%;
    }

    .result-panel-header {
        align-items: flex-start;
        gap: 12px;
        flex-direction: column;
    }

    .baseline-table {
        font-size: 12px;
    }

}

</style>

</head>


<body>

<div class="dashboard-container">

<?php include "sidebar.php"; ?>


<main class="main-content">


    <!-- HEADER -->

    <div class="sim-page-header">

        <h1>What-If Simulation</h1>

        <p>
            Simulate changes in ESG operational parameters
        </p>

    </div>


    <!-- SIMULATION INPUT -->

    <div class="simulation-box">

        <div class="simulation-header">

            <div class="simulation-icon">
                ⚙️
            </div>

            <h2>
                Scenario Simulation
            </h2>

        </div>


        <p class="info-text">

            Enter a percentage change to simulate
            its effect on current ESG metrics.

        </p>


        <form method="POST">

            <label for="change">
                Percentage Change (%)
            </label>


            <div class="input-row">

                <input
                    type="number"
                    step="0.1"
                    name="change"
                    id="change"
                    class="simulation-input"
                    value="<?php echo htmlspecialchars($change); ?>"
                    placeholder="Example: -10"
                    required
                >


                <button
                    type="submit"
                    class="simulate-btn"
                >
                    Run Simulation
                </button>

            </div>

        </form>

    </div>


    <!-- RESULTS -->

    <div class="result-panel">

        <div class="result-panel-header">

            <h2>
                Simulation Results
            </h2>

            <div class="scenario-badge">
                Scenario:
                <span><?php echo $change; ?>%</span>
            </div>

        </div>


        <div class="result-grid">


            <!-- ENERGY -->

            <div class="result-card">

                <div class="metric-icon">
                    ⚡
                </div>

                <h3>
                    Energy
                </h3>

                <div class="result-value">
                    <?php echo number_format($energy_result, 2); ?>
                </div>

                <div class="result-unit">
                    kWh
                </div>

                <?php if ($change != 0): ?>

                    <div class="change-value <?php echo ($energy_difference > 0) ? 'change-positive' : 'change-negative'; ?>">

                        <?php echo ($energy_difference > 0 ? '+' : '') . number_format($energy_difference, 2); ?>

                        from baseline

                    </div>

                <?php endif; ?>

            </div>


            <!-- WATER -->

            <div class="result-card">

                <div class="metric-icon">
                    💧
                </div>

                <h3>
                    Water
                </h3>

                <div class="result-value">
                    <?php echo number_format($water_result, 2); ?>
                </div>

                <div class="result-unit">
                    Litres
                </div>

                <?php if ($change != 0): ?>

                    <div class="change-value <?php echo ($water_difference > 0) ? 'change-positive' : 'change-negative'; ?>">

                        <?php echo ($water_difference > 0 ? '+' : '') . number_format($water_difference, 2); ?>

                        from baseline

                    </div>

                <?php endif; ?>

            </div>


            <!-- WASTE -->

            <div class="result-card">

                <div class="metric-icon">
                    🗑️
                </div>

                <h3>
                    Waste
                </h3>

                <div class="result-value">
                    <?php echo number_format($waste_result, 2); ?>
                </div>

                <div class="result-unit">
                    kg
                </div>

                <?php if ($change != 0): ?>

                    <div class="change-value <?php echo ($waste_difference > 0) ? 'change-positive' : 'change-negative'; ?>">

                        <?php echo ($waste_difference > 0 ? '+' : '') . number_format($waste_difference, 2); ?>

                        from baseline

                    </div>

                <?php endif; ?>

            </div>


            <!-- CARBON -->

            <div class="result-card">

                <div class="metric-icon">
                    🌱
                </div>

                <h3>
                    Carbon Emission
                </h3>

                <div class="result-value">
                    <?php echo number_format($carbon_result, 2); ?>
                </div>

                <div class="result-unit">
                    tCO₂e
                </div>

                <?php if ($change != 0): ?>

                    <div class="change-value <?php echo ($carbon_difference > 0) ? 'change-positive' : 'change-negative'; ?>">

                        <?php echo ($carbon_difference > 0 ? '+' : '') . number_format($carbon_difference, 2); ?>

                        from baseline

                    </div>

                <?php endif; ?>

            </div>


        </div>

    </div>


    <!-- BASELINE -->

    <div class="baseline-panel">

        <h2>
            Current Baseline
        </h2>


        <table class="baseline-table">

            <thead>

                <tr>

                    <th>Metric</th>

                    <th>Current Value</th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>Energy</td>

                    <td>
                        <?php echo number_format($energy, 2); ?> kWh
                    </td>

                </tr>


                <tr>

                    <td>Water</td>

                    <td>
                        <?php echo number_format($water, 2); ?> Litres
                    </td>

                </tr>


                <tr>

                    <td>Waste</td>

                    <td>
                        <?php echo number_format($waste, 2); ?> kg
                    </td>

                </tr>


                <tr>

                    <td>Carbon Emission</td>

                    <td>
                        <?php echo number_format($carbon, 2); ?> tCO₂e
                    </td>

                </tr>

            </tbody>

        </table>

    </div>


</main>

</div>

</body>

</html>