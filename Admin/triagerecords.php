<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle Delete action
if (isset($_GET['vital_id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $vital_id = intval($_GET['vital_id']);
    $query = "DELETE FROM vitals WHERE vital_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $vital_id);
    if ($stmt->execute()) {
        header("Location: triagerecords.php");
        exit();
    } else {
        $err_delete = "Failed to delete triage record. Please try again.";
    }
    $stmt->close();
}

// Handle Add action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_vital'])) {
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $visit_date = $_POST['visit_date'];
    $temperature = $_POST['temperature'];
    $blood_pressure = $_POST['blood_pressure'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $status = $_POST['status'];

    $query = "INSERT INTO vitals (patient_id, user_id, visit_date, temperature, blood_pressure, weight, height, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iissiiis', $patient_id, $user_id, $visit_date, $temperature, $blood_pressure, $weight, $height, $status);
    if ($stmt->execute()) {
        header("Location: triagerecords.php");
        exit();
    } else {
        $err_add = "Failed to add triage record. Please try again.";
    }
    $stmt->close();
}

// Fetch triage records from the database
$query = "SELECT vital_id, patient_id, user_id, visit_date, temperature, blood_pressure, weight, height, created_at, status FROM vitals ORDER BY created_at DESC";
$result = $mysqli->query($query);

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
                        <button class="btn btn-success" data-toggle="modal" data-target="#addTriageModal">Add New Triage Record</button>
                    </div>

                    <?php if (isset($err_add)) { echo "<p class='text-danger'>$err_add</p>"; } ?>
                    <?php if (isset($err_delete)) { echo "<p class='text-danger'>$err_delete</p>"; } ?>

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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['vital_id']; ?></td>
                                        <td><?php echo $row['patient_id']; ?></td>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['temperature']); ?></td>
                                        <td><?php echo htmlspecialchars($row['blood_pressure']); ?></td>
                                        <td><?php echo htmlspecialchars($row['weight']); ?></td>
                                        <td><?php echo htmlspecialchars($row['height']); ?></td>
                                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td>
                                            <a href="updatevital.php?vital_id=<?php echo $row['vital_id']; ?>" class="badge badge-primary">Update</a>
                                            <a href="triagerecords.php?vital_id=<?php echo $row['vital_id']; ?>&action=delete" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>

    <!-- Add Triage Modal -->
    <div class="modal fade" id="addTriageModal" tabindex="-1" role="dialog" aria-labelledby="addTriageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="triagerecords.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTriageModalLabel">Add New Triage Record</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="patient_id">Patient ID</label>
                            <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                        </div>
                        <div class="form-group">
                            <label for="visit_date">Visit Date</label>
                            <input type="date" class="form-control" id="visit_date" name="visit_date" required>
                        </div>
                        <div class="form-group">
                            <label for="temperature">Temperature</label>
                            <input type="number" step="0.1" class="form-control" id="temperature" name="temperature" required>
                        </div>
                        <div class="form-group">
                            <label for="blood_pressure">Blood Pressure</label>
                            <input type="number" class="form-control" id="blood_pressure" name="blood_pressure" required>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight</label>
                            <input type="number" class="form-control" id="weight" name="weight" required>
                        </div>
                        <div class="form-group">
                            <label for="height">Height</label>
                            <input type="number" class="form-control" id="height" name="height" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_vital">Add Triage Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="text-center">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
