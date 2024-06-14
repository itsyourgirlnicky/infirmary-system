<?php
session_start();
include('config.php');

$err = ''; // Initialize error variable

// Get patient_id and prescription_id from URL if set
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : null;
$prescription_id = isset($_GET['prescription_id']) ? $_GET['prescription_id'] : null;

if ($prescription_id) {
    // Fetch existing prescription details for update
    $query = "SELECT * FROM prescriptions WHERE prescription_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $prescription_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $prescription = $res->fetch_object();
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prescription_id = isset($_POST['prescription_id']) ? $_POST['prescription_id'] : null;
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $medication = trim($_POST['medication']);
    $dosage = trim($_POST['dosage']);
    $duration = trim($_POST['duration']);
    $created_at = date('Y-m-d H:i:s');

    // Server-side validation
    if (empty($medication) || empty($dosage) || empty($duration)) {
        $err = "All fields are required.";
    } else {
        if ($prescription_id) {
            // Update existing prescription
            $query = "UPDATE prescriptions SET medication = ?, dosage = ?, duration = ? WHERE prescription_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssi', $medication, $dosage, $duration, $prescription_id);
        } else {
            // Insert new prescription
            $query = "INSERT INTO prescriptions (consultation_id, patient_id, medication, dosage, duration, created_at) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('iissss', $consultation_id, $patient_id, $medication, $dosage, $duration, $created_at);
        }

        if ($stmt->execute()) {
            header("Location: manageprescriptions.php");
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
    <title><?php echo $prescription_id ? 'Update' : 'Add'; ?> Prescription</title>
    <link rel="stylesheet" href="consultation.css">
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <h2 class="header-title"><?php echo $prescription_id ? 'Update' : 'Add'; ?> Prescription</h2>
        <?php if ($err) { echo "<div style='color: red;'>$err</div>"; } ?>
        <form id="prescriptionForm" method="post" action="addprescription.php?patient_id=<?php echo $patient_id; ?><?php if ($prescription_id) echo '&prescription_id=' . $prescription_id; ?>" onsubmit="return validatePrescriptionForm()">
            <input type="hidden" name="prescription_id" value="<?php echo htmlspecialchars($prescription_id); ?>">
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
                <label for="medication">Medication</label>
                <textarea id="medication" name="medication" rows="4" required><?php echo htmlspecialchars($prescription->medication ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="dosage">Dosage</label>
                <textarea id="dosage" name="dosage" rows="4" required><?php echo htmlspecialchars($prescription->dosage ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="duration">Duration</label>
                <textarea id="duration" name="duration" rows="4" required><?php echo htmlspecialchars($prescription->duration ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn"><?php echo $prescription_id ? 'Update' : 'Save'; ?> Prescription</button>
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
