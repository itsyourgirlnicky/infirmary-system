<?php
session_start();
include('config.php');

// Fetch triage records from the database
$query = "SELECT vital_id, patient_id, user_id, visit_date, temperature, blood_pressure, weight, height, created_at, status FROM vitals ORDER BY created_at DESC";
$result = $mysqli->query($query);

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

// Apply Filters
$filterByStatus = '';
if (isset($_GET['status'])) {
    $filterByStatus = $_GET['status'];
    if ($filterByStatus === 'pending') {
        $completedRecords = []; // Reset completed records if filtered by pending
    } elseif ($filterByStatus === 'completed') {
        $pendingRecords = []; // Reset pending records if filtered by completed
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

                    <!-- Filter Form -->
                    <form method="GET" action="" class="form-inline mb-4">
                        <div class="form-group mr-2">
                            <label for="status" class="mr-2">Filter by Status:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All</option>
                                <option value="pending" <?php if ($filterByStatus === 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="completed" <?php if ($filterByStatus === 'completed') echo 'selected'; ?>>Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <!-- Display Records -->
                    <?php if (!empty($pendingRecords)) { ?>
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
                                            <td><a href="?vital_id=<?php echo $record['vital_id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                    <?php if (!empty($completedRecords)) { ?>
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
                    <?php } ?>
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
