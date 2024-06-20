<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Handle admin login
    exit();
}

// Handle Delete action
if (isset($_GET['vital_id'])) {
    $vital_id = intval($_GET['vital_id']);
    $query = "DELETE FROM vitals WHERE vital_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $vital_id);
    if ($stmt->execute()) {
        // Deletion successful, redirect to refresh the page
        header("Location: triagerecords.php");
        exit();
    } else {
        $err_delete = "Failed to delete triage record. Please try again.";
    }
    $stmt->close();
}

// Fetch triage records from the database
$query = "SELECT vital_id, patient_id, user_id, visit_date, temperature, blood_pressure, weight, height, created_at, status FROM vitals ORDER BY created_at DESC";
$result = $mysqli->query($query);

// Display error message if deletion fails
if (isset($err_delete)) {
    echo "<p class='text-danger'>$err_delete</p>";
}

// Classify records by status
$pendingRecords = [];
$completedRecords = [];
while ($row = $result->fetch_assoc()) {
    if ($row['status'] === 'pending') {
        $pendingRecords[] = $row;
    } else {
        $completedRecords[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triage Records</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                        <div class="breadcrumb-item"><a href="admindashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item active">Triage Records</div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Triage Records</h4>
                    </div>

                    <h5>Pending Records:</h5>
                    <div class="table-container mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vital ID</th>
                                    <th>Patient ID</th>
                                    <th>User ID</th>
                                    <th>Visit Date</th>
                                    <th>Temperature</th>
                                    <th>Blood Pressure</th>
                                    <th>Weight</th>
                                    <th>Height</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingRecords as $record) { ?>
                                    <tr>
                                        <td><?php echo $record['vital_id']; ?></td>
                                        <td><?php echo $record['patient_id']; ?></td>
                                        <td><?php echo $record['user_id']; ?></td>
                                        <td><?php echo htmlspecialchars($record['visit_date']); ?></td>
                                        <td><?php echo htmlspecialchars($record['temperature']); ?></td>
                                        <td><?php echo htmlspecialchars($record['blood_pressure']); ?></td>
                                        <td><?php echo htmlspecialchars($record['weight']); ?></td>
                                        <td><?php echo htmlspecialchars($record['height']); ?></td>
                                        <td><?php echo htmlspecialchars($record['created_at']); ?></td>
                                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <h5>Completed Records:</h5>
                    <div class="table-container mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vital ID</th>
                                    <th>Patient ID</th>
                                    <th>User ID</th>
                                    <th>Visit Date</th>
                                    <th>Temperature</th>
                                    <th>Blood Pressure</th>
                                    <th>Weight</th>
                                    <th>Height</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($completedRecords as $record) { ?>
                                    <tr>
                                        <td><?php echo $record['vital_id']; ?></td>
                                        <td><?php echo $record['patient_id']; ?></td>
                                        <td><?php echo $record['user_id']; ?></td>
                                        <td><?php echo htmlspecialchars($record['visit_date']); ?></td>
                                        <td><?php echo htmlspecialchars($record['temperature']); ?></td>
                                        <td><?php echo htmlspecialchars($record['blood_pressure']); ?></td>
                                        <td><?php echo htmlspecialchars($record['weight']); ?></td>
                                        <td><?php echo htmlspecialchars($record['height']); ?></td>
                                        <td><?php echo htmlspecialchars($record['created_at']); ?></td>
                                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                        
                                    </tr>
                                <?php } ?>
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
</body>
</html>
