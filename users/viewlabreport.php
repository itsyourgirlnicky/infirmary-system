<?php
session_start();
include('config.php'); 

// Define query to fetch lab reports from the view
$query = "SELECT * FROM labrequests";

// Execute the query
$result = $mysqli->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Lab Reports</title>
    <link rel="stylesheet" href="admin.css"> <!-- Adjust the path to your CSS file -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include any additional CSS files or stylesheets -->
</head>
<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <div class="content-page">
            <div class="content">
                <div class="page-title-box">
                    <div class="breadcrumb">
                        <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="manageconsultation.php">Manage Consultation</a></div>
                        <div class="breadcrumb-item active">Lab Reports</div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Lab Reports</h4>
                    </div>
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lab Request ID</th>
                                    <th>Patient ID</th>
                                    <th>Test Name</th>
                                    <th>Lab Created At</th>
                                    <th>Result</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch and display each row of the result
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['lab_request_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['patient_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['test_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['result']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="text-center">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

    <!-- Include any necessary JavaScript files or scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
