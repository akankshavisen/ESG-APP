<?php

require_once "config/database.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["report"])) {

        $file = $_FILES["report"];

        $fileName = $file["name"];
        $fileTmp = $file["tmp_name"];
        $fileError = $file["error"];

        // Allowed file types
        $allowedExtensions = [
            "pdf",
            "xlsx",
            "xls",
            "csv"
        ];

        // Get file extension
        $fileExtension = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );


        // Check upload error
        if ($fileError !== 0) {

            die("File upload failed.");

        }


        // Check file type
        if (!in_array($fileExtension, $allowedExtensions)) {

            die(
                "Invalid file type. Only PDF, Excel and CSV files are allowed."
            );

        }


        // Create unique file name
        $newFileName =
            time() . "_" . basename($fileName);


        // File path
        $uploadPath =
            "uploads/" . $newFileName;


        // Upload file
        if (move_uploaded_file($fileTmp, $uploadPath)) {


            // Report type
            if ($fileExtension == "pdf") {

                $reportType = "PDF Report";

            } elseif (
                $fileExtension == "xlsx" ||
                $fileExtension == "xls"
            ) {

                $reportType = "Excel Report";

            } else {

                $reportType = "CSV Report";

            }


            // Default reporting period
            $reportingPeriod = "2026";


            // Status
            $status = "Pending";


            // Insert information into database
            $sql = "INSERT INTO reports
                    (
                        report_name,
                        file_name,
                        file_path,
                        report_type,
                        reporting_period,
                        status
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                    )";


            $stmt = $conn->prepare($sql);


            $stmt->bind_param(
                "ssssss",
                $fileName,
                $fileName,
                $uploadPath,
                $reportType,
                $reportingPeriod,
                $status
            );


            if ($stmt->execute()) {

                ?>

                <!DOCTYPE html>

                <html>

                <head>

                    <title>Upload Successful</title>

                    <style>

                        body {

                            font-family: Arial, sans-serif;

                            background: #f4f7f6;

                            text-align: center;

                            padding-top: 100px;

                        }


                        .box {

                            background: white;

                            width: 450px;

                            margin: auto;

                            padding: 35px;

                            border-radius: 12px;

                            box-shadow:
                                0 3px 15px
                                rgba(0,0,0,0.1);

                        }


                        h2 {

                            color: #102a27;

                        }


                        p {

                            color: #64748b;

                        }


                        a {

                            display: inline-block;

                            margin-top: 15px;

                            padding: 10px 18px;

                            background: #36a269;

                            color: white;

                            text-decoration: none;

                            border-radius: 8px;

                        }

                    </style>

                </head>


                <body>

                    <div class="box">

                        <h2>
                            ✓ Report Uploaded Successfully
                        </h2>


                        <p>

                            File:

                            <strong>

                                <?php
                                echo htmlspecialchars(
                                    $fileName
                                );
                                ?>

                            </strong>

                        </p>


                        <p>
                            Report saved in database.
                        </p>


                        <a href="reports.php">
                            Back to Reports
                        </a>

                    </div>

                </body>

                </html>

                <?php


            } else {

                echo "Database error: " .
                     $stmt->error;

            }


            $stmt->close();


        } else {

            die(
                "Unable to save the uploaded file."
            );

        }


    } else {

        die("No file selected.");

    }

}

?>