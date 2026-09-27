<?php

require_once "config/database.php";


/* =========================
   FETCH REPORTS
========================= */

$sql = "SELECT * FROM reports ORDER BY id DESC";

$result = $conn->query($sql);

$reports = [];

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $reports[] = $row;

    }

}


/* =========================
   REPORT COUNTS
========================= */

$total_reports = count($reports);

$verified_reports = 0;
$pending_reports = 0;

foreach ($reports as $report) {

    if ($report['status'] === 'Verified') {

        $verified_reports++;

    } else {

        $pending_reports++;

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Evidence & Reports - ESG Sentinel</title>

    <link rel="stylesheet" href="assets/css/dashboard.css?v=1002">


    <style>

        /* =========================
           HEADER
        ========================= */

        .reports-header {

            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 25px;

            gap: 20px;

        }


        .reports-title h1 {

            margin: 0;

            color: #ffffff;

            font-size: 30px;

        }


        .reports-title p {

            margin-top: 7px;

            color: #9fb8b3;

            font-size: 14px;

        }


        .reports-status {

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
           SUMMARY CARDS
        ========================= */

        .report-summary {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 18px;

            margin-bottom: 25px;

        }


        .report-summary-card {

            background: #12322f;

            border: 1px solid #234d47;

            border-radius: 14px;

            padding: 20px;

            min-height: 145px;

            box-sizing: border-box;

            transition: 0.2s ease;

        }


        .report-summary-card:hover {

            transform: translateY(-3px);

            border-color: #3c866f;

        }


        .summary-top {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 15px;

        }


        .summary-icon {

            width: 40px;
            height: 40px;

            display: flex;

            justify-content: center;
            align-items: center;

            background: #1d4942;

            border-radius: 10px;

            font-size: 18px;

        }


        .summary-label {

            color: #a9c1bd;

            font-size: 12px;

        }


        .summary-value {

            color: #ffffff;

            font-size: 30px;

            font-weight: 700;

        }


        .summary-description {

            color: #78948e;

            font-size: 12px;

            margin-top: 5px;

        }


        /* =========================
           PANEL
        ========================= */

        .reports-panel {

            background: #102b28;

            border: 1px solid #234d47;

            border-radius: 14px;

            padding: 24px;

            margin-bottom: 25px;

        }


        .panel-heading {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;

            gap: 15px;

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


        .report-count {

            background: #173f38;

            border: 1px solid #28624f;

            color: #69e39d;

            padding: 6px 11px;

            border-radius: 15px;

            font-size: 11px;

            white-space: nowrap;

        }


        /* =========================
           TABLE
        ========================= */

        .reports-table-wrapper {

            overflow-x: auto;

        }


        .reports-table {

            width: 100%;

            border-collapse: collapse;

            min-width: 700px;

        }


        .reports-table th {

            padding: 14px 12px;

            text-align: left;

            color: #8fa9a4;

            font-size: 12px;

            font-weight: 600;

            border-bottom: 1px solid #294b46;

        }


        .reports-table td {

            padding: 16px 12px;

            color: #e8f0ee;

            font-size: 13px;

            border-bottom: 1px solid #203f3b;

        }


        .reports-table tr:last-child td {

            border-bottom: none;

        }


        .reports-table tr:hover {

            background: #14332f;

        }


        .report-name {

            display: flex;

            align-items: center;

            gap: 10px;

            color: #ffffff;

            font-weight: 600;

        }


        .report-file-icon {

            width: 32px;
            height: 32px;

            display: flex;

            justify-content: center;
            align-items: center;

            background: #1d4942;

            border-radius: 8px;

        }


        /* =========================
           STATUS
        ========================= */

        .report-status {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 5px 10px;

            border-radius: 15px;

            font-size: 11px;

            font-weight: 600;

        }


        .status-verified {

            background: #173f38;

            color: #69e39d;

            border: 1px solid #28624f;

        }


        .status-pending {

            background: #44381b;

            color: #f1c76a;

            border: 1px solid #66552a;

        }


        /* =========================
           VIEW BUTTON
        ========================= */

        .view-report {

            display: inline-block;

            color: #69d69a;

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;

            padding: 6px 10px;

            border: 1px solid #28624f;

            border-radius: 7px;

        }


        .view-report:hover {

            background: #173f38;

        }


        /* =========================
           EMPTY STATE
        ========================= */

        .empty-state {

            text-align: center;

            padding: 45px 20px;

            color: #819c96;

        }


        .empty-icon {

            font-size: 35px;

            margin-bottom: 10px;

        }


        /* =========================
           UPLOAD SECTION
        ========================= */

        .upload-box {

            border: 1px dashed #3a625a;

            background: #0c2422;

            border-radius: 12px;

            padding: 35px 20px;

            text-align: center;

        }


        .upload-icon {

            font-size: 35px;

            margin-bottom: 10px;

        }


        .upload-box h3 {

            color: #ffffff;

            margin-bottom: 7px;

        }


        .upload-box p {

            color: #8fa9a4;

            font-size: 13px;

            margin-bottom: 8px;

        }


        .supported-formats {

            color: #66827c !important;

            font-size: 11px !important;

            margin-bottom: 18px !important;

        }


        .file-input {

            color: #cddbd8;

            margin: 10px 0 18px;

        }


        .upload-btn {

            background: #36a269;

            color: #ffffff;

            border: none;

            padding: 11px 22px;

            border-radius: 8px;

            cursor: pointer;

            font-size: 13px;

            font-weight: 600;

        }


        .upload-btn:hover {

            background: #2d8959;

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .report-summary {

                grid-template-columns: 1fr;

            }

        }


        @media (max-width: 650px) {

            .reports-header {

                flex-direction: column;

                align-items: flex-start;

            }


            .panel-heading {

                align-items: flex-start;

                flex-direction: column;

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

        <div class="reports-header">


            <div class="reports-title">

                <h1>
                    Evidence & Reports
                </h1>

                <p>
                    Manage ESG evidence, reports and verification records
                </p>

            </div>


            <div class="reports-status">

                <span class="status-dot"></span>

                Reports System Active

            </div>


        </div>



        <!-- =========================
             SUMMARY
        ========================= -->

        <div class="report-summary">


            <!-- TOTAL -->

            <div class="report-summary-card">

                <div class="summary-top">

                    <div class="summary-icon">
                        📄
                    </div>

                    <span class="summary-label">
                        TOTAL REPORTS
                    </span>

                </div>


                <div class="summary-value">
                    <?php echo $total_reports; ?>
                </div>


                <div class="summary-description">
                    Reports available in system
                </div>

            </div>



            <!-- VERIFIED -->

            <div class="report-summary-card">

                <div class="summary-top">

                    <div class="summary-icon">
                        ✓
                    </div>

                    <span class="summary-label">
                        VERIFIED
                    </span>

                </div>


                <div class="summary-value">
                    <?php echo $verified_reports; ?>
                </div>


                <div class="summary-description">
                    Successfully verified reports
                </div>

            </div>



            <!-- PENDING -->

            <div class="report-summary-card">

                <div class="summary-top">

                    <div class="summary-icon">
                        ⏳
                    </div>

                    <span class="summary-label">
                        PENDING
                    </span>

                </div>


                <div class="summary-value">
                    <?php echo $pending_reports; ?>
                </div>


                <div class="summary-description">
                    Reports awaiting verification
                </div>

            </div>


        </div>



        <!-- =========================
             REPORT TABLE
        ========================= -->

        <div class="reports-panel">


            <div class="panel-heading">

                <div>

                    <h2>
                        ESG Reports
                    </h2>

                    <p>
                        Uploaded reports and supporting evidence
                    </p>

                </div>


                <span class="report-count">

                    <?php echo $total_reports; ?>

                    Reports

                </span>

            </div>


            <div class="reports-table-wrapper">


                <table class="reports-table">


                    <thead>

                        <tr>

                            <th>
                                Report
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Reporting Period
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if ($total_reports > 0): ?>


                        <?php foreach ($reports as $report): ?>


                            <tr>


                                <td>

                                    <div class="report-name">

                                        <span class="report-file-icon">
                                            📄
                                        </span>

                                        <?php

                                        echo htmlspecialchars(
                                            $report['report_name']
                                        );

                                        ?>

                                    </div>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $report['report_type']
                                    );

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $report['reporting_period']
                                    );

                                    ?>

                                </td>


                                <td>


                                    <?php if ($report['status'] === 'Verified'): ?>


                                        <span class="report-status status-verified">

                                            ● Verified

                                        </span>


                                    <?php else: ?>


                                        <span class="report-status status-pending">

                                            ● Pending

                                        </span>


                                    <?php endif; ?>


                                </td>


                                <td>


                                    <a
                                        href="<?php echo htmlspecialchars($report['file_path']); ?>"
                                        target="_blank"
                                        class="view-report">

                                        View

                                    </a>


                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td colspan="5">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        📂
                                    </div>

                                    No reports uploaded yet.

                                </div>

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </div>



        <!-- =========================
             UPLOAD
        ========================= -->

        <div class="reports-panel">


            <div class="panel-heading">

                <div>

                    <h2>
                        Upload ESG Evidence
                    </h2>

                    <p>
                        Add reports and supporting documents to the ESG system
                    </p>

                </div>

            </div>


            <div class="upload-box">


                <div class="upload-icon">
                    📤
                </div>


                <h3>
                    Upload ESG Report
                </h3>


                <p>
                    Select an ESG report or supporting evidence file
                </p>


                <p class="supported-formats">
                    Supported formats: PDF, Excel, CSV
                </p>


                <form
                    action="/esg-sentinel/upload_report.php"
                    method="POST"
                    enctype="multipart/form-data">


                    <input
                        type="file"
                        name="report"
                        class="file-input"
                        accept=".pdf,.xlsx,.xls,.csv"
                        required>


                    <br>


                    <button
                        type="submit"
                        class="upload-btn">

                        Upload Report

                    </button>


                </form>


            </div>


        </div>


    </main>


</div>


</body>

</html>