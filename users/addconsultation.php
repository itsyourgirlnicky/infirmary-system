<?php
session_start();
include('config.php');

$patient_id = $_GET['patient_id'];
$ret = "SELECT patients.name, patients.age, patients.gender, vitals.temperature, vitals.blood_pressure, vitals.weight, vitals.height FROM patients JOIN vitals ON patients.patient_id = vitals.patient_id WHERE patients.patient_id = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('i', $patient_id);
$stmt->execute();
$res = $stmt->get_result();
$patient = $res->fetch_object();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $visit_date = date('Y-m-d');
    $consultation_type = trim($_POST['consultation_type']);
    $notes = trim($_POST['notes']);
    $diagnosis = trim($_POST['diagnosis']);
    $treatment_plan = trim($_POST['treatment_plan']);

    // Server-side validation
    if (empty($consultation_type) || empty($notes) || empty($diagnosis) || empty($treatment_plan)) {
        $err = "All fields are required.";
    } else {
        $query = "INSERT INTO consultations (patient_id, user_id, visit_date, consultation_type, notes, diagnosis, treatment_plan) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iisssss', $patient_id, $user_id, $visit_date, $consultation_type, $notes, $diagnosis, $treatment_plan);

        if ($stmt->execute()) {
            header("Location: manageconsultations.php");
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
    <title>Consultation Details</title>
    <link rel="stylesheet" href="consultation.css"> 
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <h2 class="header-title">Consultation Details</h2>
        <?php if (isset($err)) { echo "<div style='color: red;'>$err</div>"; } ?>
        <form method="post" action="consultation.php" onsubmit="return validateConsultationForm()">
            <div class="form-group">
                <label for="patientName">Patient Name</label>
                <input type="text" id="patientName" name="patient_name" value="<?php echo htmlspecialchars($patient->name); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="patientID">Patient ID</label>
                <input type="text" id="patientID" name="patient_id" value="<?php echo $patient_id; ?>" readonly>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($patient->age); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($patient->gender); ?>" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="temperature">Temperature (°C)</label>
                    <input type="text" id="temperature" name="temperature" value="<?php echo htmlspecialchars($patient->temperature); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="blood_pressure">Blood Pressure (mmHg)</label>
                    <input type="text" id="blood_pressure" name="blood_pressure" value="<?php echo htmlspecialchars($patient->blood_pressure); ?>" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="text" id="weight" name="weight" value="<?php echo htmlspecialchars($patient->weight); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="text" id="height" name="height" value="<?php echo htmlspecialchars($patient->height); ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="consultation_type">Consultation Type</label>
                <select id="consultation_type" name="consultation_type">
                    <option value="general">General</option>
                    <option value="dental">Dental</option>
                    <option value="vct">VCT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="diagnosis">Diagnosis</label>
                <textarea id="diagnosis" name="diagnosis" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="treatment_plan">Treatment Plan</label>
                <textarea id="treatment_plan" name="treatment_plan" rows="4"></textarea>
            </div>
            <button type="submit" class="btn">Save Consultation</button>
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
    <script src=".\validation.js"></script>

</body>
</html>
