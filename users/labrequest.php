<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $test_name = trim($_POST['test_name']);
    $result = trim($_POST['result']);
    $status = trim($_POST['status']);
    $created_at = date('Y-m-d H:i:s');

    // Server-side validation
    if (empty($test_name) || empty($result) || empty($status)) {
        $err = "All fields are required.";
    } else {
        $query = "INSERT INTO labrequests (consultation_id, patient_id, test_name, result, status, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iissss', $consultation_id, $patient_id, $test_name, $result, $status, $created_at);

        if ($stmt->execute()) {
            header("Location: managelabrequests.php");
            exit();
        } else {
            $err = "Error: " . $stmt->error;
        }
    }
}

// Get patient_id from URL
$patient_id = $_GET['patient_id'];

// Fetch patient name
$ret = "SELECT name FROM patients WHERE patient_id = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('i', $patient_id);
$stmt->execute();
$res = $stmt->get_result();
$patient = $res->fetch_object();

// Fetch consultations for the patient
$ret_consultations = "SELECT consultation_id FROM consultations WHERE patient_id = ?";
$stmt_consultations = $mysqli->prepare($ret_consultations);
$stmt_consultations->bind_param('i', $patient_id);
$stmt_consultations->execute();
$res_consultations = $stmt_consultations->get_result();

// Fetch latest vitals for the patient
$ret_vitals = "SELECT * FROM vitals WHERE patient_id = ? ORDER BY vital_id DESC LIMIT 1";
$stmt_vitals = $mysqli->prepare($ret_vitals);
$stmt_vitals->bind_param('i', $patient_id);
$stmt_vitals->execute();
$res_vitals = $stmt_vitals->get_result();
$vitals = $res_vitals->fetch_object();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lab Request</title>
    <link rel="stylesheet" href="consultation.css">
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <h2 class="header-title">Add Lab Request</h2>
        <?php if (isset($err)) { echo "<div style='color: red;'>$err</div>"; } ?>
        <form method="post" action="addlabrequest.php?patient_id=<?php echo $patient_id; ?>" onsubmit="return validateLabRequestForm()">
            <div class="form-group">
                <label for="patientName">Patient Name</label>
                <input type="text" id="patientName" name="patient_name" value="<?php echo htmlspecialchars($patient->name); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="patientID">Patient ID</label>
                <input type="text" id="patientID" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="consultationID">Consultation ID</label>
                <select id="consultationID" name="consultation_id" required>
                    <?php while ($consultation = $res_consultations->fetch_object()) { ?>
                        <option value="<?php echo $consultation->consultation_id; ?>"><?php echo $consultation->consultation_id; ?></option>
                    <?php } ?>
                </select>
            </div>
            <h3>Vitals Details</h3>
            <div class="form-group">
                <label for="temperature">Temperature</label>
                <input type="text" id="temperature" name="temperature" value="<?php echo htmlspecialchars($vitals->temperature); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="bloodPressure">Blood Pressure</label>
                <input type="text" id="bloodPressure" name="blood_pressure" value="<?php echo htmlspecialchars($vitals->blood_pressure); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="weight">Weight</label>
                <input type="text" id="weight" name="weight" value="<?php echo htmlspecialchars($vitals->weight); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="height">Height</label>
                <input type="text" id="height" name="height" value="<?php echo htmlspecialchars($vitals->height); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="testName">Test Name</label>
                <input type="text" id="testName" name="test_name" required>
            </div>
            <div class="form-group">
                <label for="result">Result</label>
                <textarea id="result" name="result" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="btn">Save Lab Request</button>
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
