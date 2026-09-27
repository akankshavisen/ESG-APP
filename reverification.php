<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config/database.php";

/* HANDLE VERIFICATION */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = "ESG Data Re-Verification";
    $module = "Re-Verification";
    $status = "Completed";
    $performed_by = "Admin";
    $details = "ESG data verification completed successfully.";

    $sql = "INSERT INTO audit_trail
            (action, module, status, performed_by, details)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database Error: " . $conn->error);
    }

    $stmt->bind_param(
        "sssss",
        $action,
        $module,
        $status,
        $performed_by,
        $details
    );

    if ($stmt->execute()) {
        $message = "Verification completed successfully.";
        $message_type = "success";
    } else {
        $message = "Verification failed: " . $stmt->error;
        $message_type = "error";
    }

    $stmt->close();
}


/* GET AUDIT RECORDS */

$sql = "SELECT * FROM audit_trail ORDER BY id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Query Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Re-Verification | ESG Sentinel</title>

<link rel="stylesheet" href="assets/css/dashboard.css?v=1002">

<style>

/* ==============================
   PAGE HEADER
============================== */

.verify-page-header {
    margin-bottom: 25px;
}

.verify-page-header h1 {
    margin: 0 0 6px 0;
    color: #ffffff;
    font-size: 28px;
    font-weight: 700;
}

.verify-page-header p {
    margin: 0;
    color: #9ab7b1;
    font-size: 14px;
}


/* ==============================
   MESSAGE
============================== */

.message {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    border-radius: 9px;
    margin-bottom: 20px;
    font-size: 13px;
    font-weight: 600;
}

.message.success {
    background: #123c32;
    border: 1px solid #287355;
    color: #70e0a4;
}

.message.error {
    background: #402322;
    border: 1px solid #7d3b38;
    color: #ff9d97;
}


/* ==============================
   VERIFICATION CARD
============================== */

.verify-box {
    background: #102d2a;
    border: 1px solid #28534c;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 25px;
}

.verify-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.verify-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #17483f;
    color: #70e0a4;
    font-size: 19px;
}

.verify-box h2 {
    margin: 0;
    color: #ffffff;
    font-size: 19px;
}

.verify-description {
    margin: 0 0 20px 0;
    color: #91aaa5;
    font-size: 13px;
    line-height: 1.6;
    max-width: 700px;
}

.verify-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
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

.verify-btn:hover {
    background: #2d8958;
    transform: translateY(-1px);
}


/* ==============================
   AUDIT TRAIL
============================== */

.audit-panel {
    background: #102d2a;
    border: 1px solid #28534c;
    border-radius: 14px;
    padding: 23px;
}

.audit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.audit-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.audit-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #17483f;
    color: #70e0a4;
}

.audit-panel h2 {
    margin: 0;
    color: #ffffff;
    font-size: 19px;
}

.audit-count {
    padding: 6px 11px;
    border-radius: 15px;
    background: #163d36;
    border: 1px solid #2b5b51;
    color: #70e0a4;
    font-size: 11px;
    font-weight: 600;
}


/* ==============================
   TABLE
============================== */

.table-container {
    overflow-x: auto;
}

.audit-table {
    width: 100%;
    min-width: 750px;
    border-collapse: collapse;
}

.audit-table th {
    padding: 13px 12px;
    text-align: left;
    background: #0d2825;
    color: #7fa39c;
    border-bottom: 1px solid #2a4d48;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.audit-table td {
    padding: 14px 12px;
    color: #d2dfdc;
    border-bottom: 1px solid #23443f;
    font-size: 13px;
}

.audit-table tbody tr:hover {
    background: #143430;
}

.audit-table tbody tr:last-child td {
    border-bottom: none;
}

.id-cell {
    color: #789a93 !important;
    font-weight: 600;
}

.action-cell {
    color: #ffffff !important;
    font-weight: 600;
}

.date-cell {
    color: #9ab5af !important;
    white-space: nowrap;
}


/* ==============================
   STATUS
============================== */

.status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status.completed {
    background: #123c32;
    color: #70e0a4;
    border: 1px solid #276b51;
}

.status.completed .status-dot {
    background: #4fd18b;
}


/* ==============================
   EMPTY STATE
============================== */

.empty-state {
    padding: 35px 15px !important;
    text-align: center !important;
    color: #789a93 !important;
}


/* ==============================
   RESPONSIVE
============================== */

@media (max-width: 700px) {

    .verify-box,
    .audit-panel {
        padding: 17px;
    }

    .verify-page-header h1 {
        font-size: 24px;
    }

    .audit-header {
        align-items: flex-start;
        gap: 12px;
        flex-direction: column;
    }

}

</style>

</head>


<body>

<div class="dashboard-container">

<?php include "sidebar.php"; ?>


<main class="main-content">


    <!-- PAGE HEADER -->

    <div class="verify-page-header">

        <h1>
            Re-Verification & Audit Trail
        </h1>

        <p>
            Verify ESG data and maintain an activity record
        </p>

    </div>


    <!-- MESSAGE -->

    <?php if (isset($message)): ?>

        <div class="message <?php echo $message_type; ?>">

            <?php if ($message_type === "success"): ?>
                ✓
            <?php else: ?>
                ⚠
            <?php endif; ?>

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <!-- VERIFICATION -->

    <div class="verify-box">

        <div class="verify-header">

            <div class="verify-icon">
                ✓
            </div>

            <h2>
                ESG Data Verification
            </h2>

        </div>


        <p class="verify-description">

            Run verification to create a new audit record
            for the current ESG data. Every verification
            activity is stored in the audit trail below.

        </p>


        <form method="POST">

            <button
                type="submit"
                class="verify-btn">

                ✓ Run Verification

            </button>

        </form>

    </div>


    <!-- AUDIT TRAIL -->

    <div class="audit-panel">

        <div class="audit-header">

            <div class="audit-title">

                <div class="audit-icon">
                    📋
                </div>

                <h2>
                    Audit Trail
                </h2>

            </div>


            <div class="audit-count">

                <?php echo $result->num_rows; ?> Records

            </div>

        </div>


        <div class="table-container">

            <table class="audit-table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Action</th>

                        <th>Module</th>

                        <th>Status</th>

                        <th>Performed By</th>

                        <th>Date & Time</th>

                    </tr>

                </thead>


                <tbody>

                <?php if ($result->num_rows > 0): ?>

                    <?php while ($row = $result->fetch_assoc()): ?>

                        <tr>

                            <td class="id-cell">
                                #<?php echo $row['id']; ?>
                            </td>


                            <td class="action-cell">

                                <?php
                                echo htmlspecialchars(
                                    $row['action']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row['module']
                                );
                                ?>

                            </td>


                            <td>

                                <span class="status completed">

                                    <span class="status-dot"></span>

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
                                    $row['performed_by']
                                );
                                ?>

                            </td>


                            <td class="date-cell">

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

                        <td
                            colspan="6"
                            class="empty-state">

                            No audit records found.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


</main>

</div>

</body>

</html>