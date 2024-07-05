<?php
session_start();
include('config.php');

// Fetch from the database
$query_total = "SELECT COUNT(*) as total FROM labrequests";
$result_total = $mysqli->query($query_total);
$total_requests = $result_total->fetch_assoc()['total'];

$query_pending = "SELECT COUNT(*) as pending FROM labrequests WHERE status='pending'";
$result_pending = $mysqli->query($query_pending);
$pending_requests = $result_pending->fetch_assoc()['pending'];

$query_completed = "SELECT COUNT(*) as completed FROM labrequests WHERE status='completed'";
$result_completed = $mysqli->query($query_completed);
$completed_requests = $result_completed->fetch_assoc()['completed'];

// Fetch recent lab requests for display
$query_recent = "SELECT lab_request_id, patient_id, test_name, created_at, result, status FROM labrequests ORDER BY created_at DESC LIMIT 10";
$result_recent = $mysqli->query($query_recent);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reports Summary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar {
            background-color: #800000;
            color: #ffc300;
            padding: 10px 0;
        }

        .navbar .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
            color: #ffc300;
            text-align: center;
        }

        .footer {
            background-color: #800000;
            color: #ffc300;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1>CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container mt-5">
    <div class="breadcrumb">
            <div class="breadcrumb-item"><a href="admindashboard.php">Dashboard</a></div>
            <div class="breadcrumb-item active">Lab Report</div>
        </div>
        <h2 class="text-center">Lab Reports Summary</h2>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Lab Requests</h5>
                        <p class="card-text"><?php echo $total_requests; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pending Requests</h5>
                        <p class="card-text"><?php echo $pending_requests; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Completed Requests</h5>
                        <p class="card-text"><?php echo $completed_requests; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-5">Recent Lab Requests</h3>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Lab Request ID</th>
                        <th>Patient ID</th>
                        <th>Test Name</th>
                        <th>Created At</th>
                        <th>Result</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_recent->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['lab_request_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['test_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($row['result']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
