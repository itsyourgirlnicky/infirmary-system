<?php
session_start();
include('config.php');

// Initialize variables
$lab_request = null;
$err_save = '';

// Fetch lab request record for editing if lab_request_id is provided
if (isset($_GET['lab_request_id'])) {
    $lab_request_id = intval($_GET['lab_request_id']);
    $query = "SELECT * FROM labrequests WHERE lab_request_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $lab_request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lab_request = $result->fetch_assoc();
    $stmt->close();

    if (!$lab_request) {
        echo "Lab request record not found.";
        exit();
    }
}

// Handle form submission to create or update lab request record
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lab_request_id = isset($_POST['lab_request_id']) ? intval($_POST['lab_request_id']) : null;
    $consultation_id = intval($_POST['consultation_id']);
    $patient_id = intval($_POST['patient_id']);
    $user_id = $_SESSION['user_id'];
    $test_name = $_POST['test_name'];
    $result = $_POST['result'];
    $status = $_POST['status'];

    if ($lab_request_id) {
        // Update existing lab request
        $query = "UPDATE labrequests SET consultation_id = ?, patient_id = ?, user_id = ?, test_name = ?, result = ?, status = ? WHERE lab_request_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iissssi', $consultation_id, $patient_id, $user_id, $test_name, $result, $status, $lab_request_id);
    } else {
        // Create new lab request
        $query = "INSERT INTO labrequests (consultation_id, patient_id, user_id, test_name, result, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iissss', $consultation_id, $patient_id, $user_id, $test_name, $result, $status);
    }

    if ($stmt->execute()) {
        header("Location:labreports.php");
        exit();
    } else {
        $err_save = "Failed to save lab request. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($lab_request) ? 'Update' : 'Create'; ?> Lab Request</title>
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
                        <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="managelabrequests.php">Manage Lab Requests</a></div>
                        <div class="breadcrumb-item active"><?php echo isset($lab_request) ? 'Update' : 'Create'; ?> Lab Request</div>
                    </div>
                </div>

                <div class="card-box">
                    <h4 class="header-title"><?php echo isset($lab_request) ? 'Update' : 'Create'; ?> Lab Request</h4>
                    <?php if (!empty($err_save)) { echo "<p class='text-danger'>$err_save</p>"; } ?>
                    <form action="managelabreports.php<?php echo isset($lab_request) ? '?lab_request_id=' . $lab_request['lab_request_id'] : ''; ?>" method="POST">
                        <?php if (isset($lab_request)) { ?>
                            <input type="hidden" name="lab_request_id" value="<?php echo $lab_request['lab_request_id']; ?>">
                        <?php } ?>
                        <div class="form-group">
                            <label for="consultation_id">Consultation ID</label>
                            <input type="number" class="form-control" id="consultation_id" name="consultation_id" value="<?php echo isset($lab_request) ? $lab_request['consultation_id'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="patient_id">Patient ID</label>
                            <input type="number" class="form-control" id="patient_id" name="patient_id" value="<?php echo isset($lab_request) ? $lab_request['patient_id'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="test_name">Test Name</label>
                            <input type="text" class="form-control" id="test_name" name="test_name" value="<?php echo isset($lab_request) ? $lab_request['test_name'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="result">Result</label>
                            <textarea class="form-control" id="result" name="result" required><?php echo isset($lab_request) ? $lab_request['result'] : ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" <?php if (isset($lab_request) && $lab_request['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="completed" <?php if (isset($lab_request) && $lab_request['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="save_lab_request"><?php echo isset($lab_request) ? 'Update' : 'Create'; ?> Lab Request</button>
                    </form>
                </div>
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
