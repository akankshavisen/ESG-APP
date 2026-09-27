<?php

require_once "auth.php";
require_once "config/database.php";

// Latest ESG data
$result = $conn->query(
    "SELECT * FROM dashboard_metrics
     ORDER BY id DESC
     LIMIT 1"
);

if (!$result || $result->num_rows === 0) {
    die("No ESG data available.");
}

$data = $result->fetch_assoc();

// ESG Scores
$environmental = (float) $data['environmental_score'];
$social = (float) $data['social_score'];
$governance = (float) $data['governance_score'];

$overall_score = round(
    ($environmental + $social + $governance) / 3,
    1
);

// Grade
if ($overall_score >= 90) {
    $grade = "A+";
} elseif ($overall_score >= 80) {
    $grade = "A";
} elseif ($overall_score >= 70) {
    $grade = "B+";
} else {
    $grade = "B";
}

// Company name
$company_name = "ESG Sentinel Company";

$settings_result = $conn->query(
    "SELECT company_name FROM settings
     ORDER BY id ASC
     LIMIT 1"
);

if ($settings_result && $settings_result->num_rows > 0) {
    $settings = $settings_result->fetch_assoc();

    if (!empty($settings['company_name'])) {
        $company_name = $settings['company_name'];
    }
}

$certificate_date = date("d F Y");

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>ESG Certificate</title>

<link rel="stylesheet"
      href="assets/css/dashboard.css?v=1005">

<style>

body {
    margin: 0;
    background: #071c1a;
    color: #ffffff;
    font-family: Arial, sans-serif;
}

.main-content {
    margin-left: 250px;
    padding: 35px;
    min-height: 100vh;
    box-sizing: border-box;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    color: #ffffff;
}

.page-header p {
    margin-top: 8px;
    color: #a9c1bd;
}

.download-btn {
    background: #4fd18b;
    color: #06201b;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    font-size: 14px;
}

.download-btn:hover {
    background: #68e5a0;
}


/* Certificate */

.certificate-wrapper {
    display: flex;
    justify-content: center;
    padding: 10px 0 40px;
}

.certificate {
    width: 850px;
    max-width: 100%;
    background: #0d302d;
    color: #ffffff;
    border: 1px solid #315b55;
    border-radius: 14px;
    padding: 12px;
    box-sizing: border-box;
}

.certificate-inner {
    border: 1px solid #4fd18b;
    border-radius: 10px;
    padding: 45px 35px;
    text-align: center;
    background: #102f2c;
}

.certificate-title {
    font-size: 38px;
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 10px;
    color: #ffffff;
}

.certificate-subtitle {
    font-size: 16px;
    color: #a9c1bd;
    margin-bottom: 35px;
}

.certificate-label {
    font-size: 14px;
    color: #a9c1bd;
    margin-bottom: 8px;
}

.company-name {
    font-size: 30px;
    font-weight: 800;
    color: #4fd18b;
    margin-bottom: 25px;
}

.certificate-text {
    font-size: 15px;
    line-height: 1.7;
    color: #c9d8d5;
    max-width: 650px;
    margin: 0 auto 30px;
}


/* Score Cards */

.score-section {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin: 25px auto;
    max-width: 650px;
}

.score-box {
    border: 1px solid #315b55;
    padding: 18px;
    border-radius: 10px;
    background: #0b2422;
}

.score-name {
    font-size: 13px;
    color: #a9c1bd;
    margin-bottom: 8px;
}

.score-value {
    font-size: 27px;
    font-weight: 800;
    color: #4fd18b;
}


/* Overall Score */

.overall-section {
    margin: 30px auto 0;
    padding: 22px;
    background: #0b2422;
    border: 1px solid #315b55;
    border-radius: 10px;
    max-width: 650px;
}

.overall-label {
    font-size: 14px;
    color: #a9c1bd;
}

.overall-score {
    font-size: 42px;
    font-weight: 800;
    color: #4fd18b;
    margin: 5px 0;
}

.grade {
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
}


/* Footer */

.certificate-footer {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #315b55;
    font-size: 13px;
    color: #a9c1bd;
}

.certificate-footer strong {
    color: #ffffff;
}


/* Mobile */

@media(max-width: 900px) {

    .main-content {
        margin-left: 0;
        padding: 20px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .certificate {
        padding: 8px;
    }

    .certificate-inner {
        padding: 30px 20px;
    }

    .score-section {
        grid-template-columns: 1fr;
    }

    .certificate-title {
        font-size: 30px;
    }

    .company-name {
        font-size: 25px;
    }

}


/* PDF / Print */
@media print {

    @page {
        size: A4 landscape;
        margin: 7mm;
    }

    html,
    body {
        width: 100%;
        height: 100%;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        color: #1f2937 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .sidebar,
    .page-header,
    .no-print {
        display: none !important;
    }

    .main-content {
        margin: 0 !important;
        padding: 0 !important;
        min-height: auto !important;
    }

    .certificate-wrapper {
        display: block !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .certificate {
        width: 100% !important;
        max-width: none !important;
        height: auto !important;
        min-height: 0 !important;

        background: #ffffff !important;
        color: #1f2937 !important;

        border: 4px solid #174d43 !important;
        border-radius: 0 !important;

        padding: 7px !important;
        margin: 0 !important;

        box-sizing: border-box !important;
        box-shadow: none !important;

        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    .certificate-inner {
        background: #ffffff !important;

        border: 2px solid #4fd18b !important;
        border-radius: 0 !important;

        padding: 22px 30px !important;
        margin: 0 !important;

        box-sizing: border-box !important;

        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    .certificate-title {
        color: #174d43 !important;
        font-size: 30px !important;
        line-height: 1.1 !important;

        margin: 0 0 5px 0 !important;
    }

    .certificate-subtitle {
        color: #536a65 !important;
        font-size: 13px !important;

        margin: 0 0 18px 0 !important;
    }

    .certificate-label {
        color: #536a65 !important;
        font-size: 12px !important;

        margin-bottom: 4px !important;
    }

    .company-name {
        color: #174d43 !important;
        font-size: 26px !important;
        line-height: 1.1 !important;

        margin-bottom: 15px !important;
    }

    .certificate-text {
        color: #374a46 !important;
        font-size: 12px !important;
        line-height: 1.45 !important;

        max-width: 650px !important;

        margin: 0 auto 16px !important;
    }

    .score-section {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;

        gap: 10px !important;

        margin: 15px auto !important;
        max-width: 650px !important;
    }

    .score-box {
        background: #f4faf7 !important;

        border: 1px solid #b8d8ce !important;
        border-radius: 5px !important;

        padding: 10px !important;
    }

    .score-name {
        color: #536a65 !important;
        font-size: 11px !important;

        margin-bottom: 4px !important;
    }

    .score-value {
        color: #174d43 !important;
        font-size: 22px !important;
        line-height: 1 !important;
    }

    .overall-section {
        max-width: 650px !important;

        margin: 15px auto 0 !important;
        padding: 12px !important;

        background: #eef9f3 !important;

        border: 1px solid #a9d9c5 !important;
        border-radius: 5px !important;
    }

    .overall-label {
        color: #536a65 !important;
        font-size: 11px !important;
    }

    .overall-score {
        color: #168253 !important;

        font-size: 30px !important;
        line-height: 1 !important;

        margin: 3px 0 !important;
    }

    .grade {
        color: #174d43 !important;
        font-size: 13px !important;
    }

    .certificate-footer {
        margin-top: 18px !important;
        padding-top: 10px !important;

        border-top: 1px solid #c7d8d3 !important;

        color: #536a65 !important;
        font-size: 10px !important;
    }

    .certificate-footer strong {
        color: #174d43 !important;
    }
}

</style>

</head>

<body>

<?php include "sidebar.php"; ?>

<main class="main-content">

    <div class="page-header no-print">

        <div>
            <h1>ESG Certificate</h1>

            <p>
                Official ESG performance certificate
            </p>
        </div>

        <button
            class="download-btn"
            onclick="window.print()">

            Download / Save PDF

        </button>

    </div>


    <div class="certificate-wrapper">

        <div class="certificate"
             id="certificate">

            <div class="certificate-inner">

                <div class="certificate-title">
                    ESG CERTIFICATE
                </div>

                <div class="certificate-subtitle">
                    Environmental, Social & Governance Performance
                </div>


                <div class="certificate-label">
                    This certificate is presented to
                </div>

                <div class="company-name">
                    <?php echo htmlspecialchars($company_name); ?>
                </div>


                <div class="certificate-text">

                    This certificate recognizes the recorded
                    Environmental, Social and Governance performance
                    based on the latest ESG monitoring data available
                    in the ESG Sentinel system.

                </div>


                <div class="score-section">

                    <div class="score-box">

                        <div class="score-name">
                            Environmental
                        </div>

                        <div class="score-value">
                            <?php echo $environmental; ?>%
                        </div>

                    </div>


                    <div class="score-box">

                        <div class="score-name">
                            Social
                        </div>

                        <div class="score-value">
                            <?php echo $social; ?>%
                        </div>

                    </div>


                    <div class="score-box">

                        <div class="score-name">
                            Governance
                        </div>

                        <div class="score-value">
                            <?php echo $governance; ?>%
                        </div>

                    </div>

                </div>


                <div class="overall-section">

                    <div class="overall-label">
                        Overall ESG Score
                    </div>

                    <div class="overall-score">
                        <?php echo $overall_score; ?>%
                    </div>

                    <div class="grade">
                        Grade: <?php echo $grade; ?>
                    </div>

                </div>


                <div class="certificate-footer">

                    <div>
                        Issued by<br>
                        <strong>ESG Sentinel</strong>
                    </div>

                    <div>
                        Certificate Date<br>
                        <strong>
                            <?php echo $certificate_date; ?>
                        </strong>
                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

</body>

</html>