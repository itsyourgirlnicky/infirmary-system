<?php
session_start();
include('config.php');



// Handle Delete action
if (isset($_GET['lab_request_id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $lab_request_id = intval($_GET['lab_request_id']);
    $query = "DELETE FROM labrequests WHERE lab_request_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $lab_request_id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $err_delete = "Failed to delete lab request. Please try again.";
    }
    $stmt->close();
}

// Handle Add action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_lab_request'])) {
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $test_name = $_POST['test_name'];
    $created_at = date('Y-m-d H:i:s');
    $result = $_POST['result'];
    $status = $_POST['status'];

    $query = "INSERT INTO labrequests (consultation_id, patient_id, user_id, test_name, created_at, result, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iiissss', $consultation_id, $patient_id, $user_id, $test_name, $created_at, $result, $status);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $err_add = "Failed to add lab request. Please try again.";
    }
    $stmt->close();
}

// Fetch lab requests from the database
$query = "SELECT lab_request_id, consultation_id, patient_id, user_id, test_name, created_at, result, status FROM labrequests ORDER BY created_at DESC";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                    <div class="breadcrumb-item active">Lab Requests</div>
                </div>
            </div>

            <div class="card-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title">Lab Requests</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#addLabRequestModal">Add New Lab Request</button>
                </div>

                <?php if (isset($err_add)) { echo "<p class='text-danger'>$err_add</p>"; } ?>
                <?php if (isset($err_delete)) { echo "<p class='text-danger'>$err_delete</p>"; } ?>

                <div class="table-container mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Lab Request ID</th>
                                <th>Patient ID</th>
                                <th>Test Name</th>
                                <th>Created At</th>
                                <th>Result</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['lab_request_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['test_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td><?php echo htmlspecialchars($row['result']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td>
                                        <a href="labreports.php?lab_request_id=<?php echo $row['lab_request_id']; ?>" class="badge badge-primary">Update</a>
                                        <a href="admindashboard.php?lab_request_id=<?php echo $row['lab_request_id']; ?>&action=delete" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
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

<!-- Add Lab Request Modal -->
<div class="modal fade" id="addLabRequestModal" tabindex="-1" role="dialog" aria-labelledby="addLabRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="admin_dashboard.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLabRequestModalLabel">Add New Lab Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="consultation_id">Consultation ID</label>
                        <input type="number" class="form-control" id="consultation_id" name="consultation_id" required>
                    </div>
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label for="test_name">Test Name</label>
                        <input type="text" class="form-control" id="test_name" name="test_name" required>
                    </div>
                    <div class="form-group">
                        <label for="result">Result</label>
                        <input type="text" class="form-control" id="result" name="result" required>
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
                    <button type="submit" class="btn btn-primary" name="add_lab_request">Add Lab Request</button>
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
