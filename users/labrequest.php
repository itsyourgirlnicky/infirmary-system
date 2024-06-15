<?php
session_start();
include('config.php');

$patient_id = $_GET['patient_id'];
$err = '';

if ($patient_id) {
    $ret = "SELECT name FROM patients WHERE patient_id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $patient = $res->fetch_object();
}

// Fetch the latest consultation id for the patient
if ($patient_id) {
    $ret_consultation = "SELECT consultation_id FROM consultations WHERE patient_id = ? ORDER BY visit_date DESC LIMIT 1";
    $stmt_consultation = $mysqli->prepare($ret_consultation);
    $stmt_consultation->bind_param('i', $patient_id);
    $stmt_consultation->execute();
    $res_consultation = $stmt_consultation->get_result();
    $consultation = $res_consultation->fetch_object();
    $consultation_id = $consultation ? $consultation->consultation_id : '';
}

if (isset($_POST["add_lab_request"])) {
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $test_name = trim($_POST['test_name']);
    $result = trim($_POST['result']);
    $status = trim($_POST['status']);
    $created_at = date('Y-m-d H:i:s');

    if (empty($test_name) || empty($status)) {
        $err = "Fill all fields";
    } else {
        $query = "INSERT INTO labrequests (consultation_id, patient_id, test_name, result, status, created_at) VALUES (?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iissss', $consultation_id, $patient_id, $test_name, $result, $status, $created_at);
        
        if ($stmt->execute()) {
            header('location: consultation.php');
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
        <h2 class="header-title">Lab Request</h2>
        <?php if ($err) {
            echo "<div style='color: red;'>$err</div>";
        } ?>
        <form method="post" onsubmit="return validateLabRequestForm()">
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
                <input type="text" id="test_name" name="test_name" value="<?php echo htmlspecialchars($patient->test_name ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="result">Result</label>
                <textarea id="result" name="result" rows="4"><?php echo htmlspecialchars($patient->result ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="pending" <?php if (isset($patient->status) && $patient->status == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="completed" <?php if (isset($patient->status) && $patient->status == 'completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
            <button type="submit" name="add_lab_request" class="btn"> Lab Request</button>
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