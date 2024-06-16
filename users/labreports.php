<?php
session_start();
include('config.php');

$err = ''; // Initialize error variable

// Get patient_id and lab_request_id from URL if set
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : null;
$lab_request_id = isset($_GET['lab_request_id']) ? $_GET['lab_request_id'] : null;

if ($lab_request_id) {
    // Fetch existing lab request details for update
    $query = "SELECT * FROM labrequests WHERE lab_request_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $lab_request_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $lab_request = $res->fetch_object();
}

// Fetch patient name
$patient = null;
if ($patient_id) {
    $ret = "SELECT name FROM patients WHERE patient_id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $patient = $res->fetch_object();
}

// Fetch the latest consultation id for the patient
$consultation_id = null;
if ($patient_id) {
    $ret_consultation = "SELECT consultation_id FROM consultations WHERE patient_id = ? ORDER BY visit_date DESC LIMIT 1";
    $stmt_consultation = $mysqli->prepare($ret_consultation);
    $stmt_consultation->bind_param('i', $patient_id);
    $stmt_consultation->execute();
    $res_consultation = $stmt_consultation->get_result();
    $consultation = $res_consultation->fetch_object();
    $consultation_id = $consultation ? $consultation->consultation_id : '';
}

if (isset($_POST['add_lab_request'])) {
    $lab_request_id = $_POST['lab_request_id'];
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $test_name = trim($_POST['test_name']);
    $result = trim($_POST['result']);
    $status = trim($_POST['status']);
    $created_at = date('Y-m-d H:i:s');

    // Server-side validation
    if (empty($test_name) || empty($status)) {
        $err = "Test name and status are required.";
    } elseif ($lab_request_id && empty($result)) {
        $err = "Result is required for updating.";
    } else {
        if ($lab_request_id) {
            // Update existing lab request
            $query = "UPDATE labrequests SET test_name = ?, result = ?, status = ? WHERE lab_request_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssi', $test_name, $result, $status, $lab_request_id);
        } else {
            // Insert new lab request
            $query = "INSERT INTO labrequests (consultation_id, patient_id, test_name, result, status, created_at) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('iissss', $consultation_id, $patient_id, $test_name, $result, $status, $created_at);
        }

        if ($stmt->execute()) {
            header("Location: labrequest.php?patient_id=$patient_id");
            exit();
        } else {
            $err = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lab_request_id ? 'Update' : 'Add'; ?> Lab Request</title>
    <link rel="stylesheet" href="consultation.css">
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <h2 class="header-title"><?php echo $lab_request_id ? 'Update' : 'Add'; ?> Lab Request</h2>
        <?php if ($err) { echo "<div style='color: red;'>$err</div>"; } ?>
        <form id="labRequestForm" method="post" action="labrequest.php?patient_id=<?php echo $patient_id; ?><?php if ($lab_request_id) echo '&lab_request_id=' . $lab_request_id; ?>" onsubmit="return validateLabRequestForm()">
            <input type="hidden" name="lab_request_id" value="<?php echo htmlspecialchars($lab_request_id); ?>">
            <div class="form-group">
                <label for="patientName">Patient Name</label>
                <input type="text" id="patientName" name="patient_name" value="<?php echo htmlspecialchars($patient->name ?? ''); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="patientID">Patient ID</label>
                <input type="text" id="patientID" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
            </div>
            <input type="hidden" id="consultationID" name="consultation_id" value="<?php echo htmlspecialchars($consultation_id); ?>">
            <div class="form-group">
                <label for="testName">Test Name</label>
                <input type="text" id="testName" name="test_name" value="<?php echo htmlspecialchars($lab_request->test_name ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="result">Result</label>
                <textarea id="result" name="result" rows="4"><?php echo htmlspecialchars($lab_request->result ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="pending" <?php if (isset($lab_request->status) && $lab_request->status == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="completed" <?php if (isset($lab_request->status) && $lab_request->status == 'completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
            <button type="submit" name="add_lab_request" class="btn"><?php echo $lab_request_id ? 'Update' : 'Save'; ?> Lab Request</button>
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

    <script src="validation.js"></script>
</body>
</html>
