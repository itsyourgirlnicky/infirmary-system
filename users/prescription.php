<?php
session_start();
include('config.php');

$patient_id = $_GET['patient_id'];
if ($patient_id) {
    $ret_patient = "SELECT name FROM patients WHERE patient_id = ?";
    $stmt_patient = $mysqli->prepare($ret_patient);
    $stmt_patient->bind_param('i', $patient_id);
    $stmt_patient->execute();
    $res_patient = $stmt_patient->get_result();
    $patient = $res_patient->fetch_object();

    $ret_consultation = "SELECT consultation_id FROM consultations WHERE patient_id = ? ORDER BY visit_date DESC LIMIT 1";
    $stmt_consultation = $mysqli->prepare($ret_consultation);
    $stmt_consultation->bind_param('i', $patient_id);
    $stmt_consultation->execute();
    $res_consultation = $stmt_consultation->get_result();
    $consultation = $res_consultation->fetch_object();

    $consultation_id = $consultation ? $consultation->consultation_id : '';
} else {
    echo "Patient ID is required.";
    exit();
}

if (isset($_POST['add_prescription'])) {
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $medications = $_POST['medication'];
    $dosages = $_POST['dosage'];
    $durations = $_POST['duration'];
    $created_at = date('Y-m-d H:i:s');

    foreach ($medications as $index => $medication) {
        $dosage = $dosages[$index];
        $duration = $durations[$index];

        $query = "INSERT INTO prescriptions (consultation_id, patient_id, medication, dosage, duration, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iissss', $consultation_id, $patient_id, $medication, $dosage, $duration, $created_at);

        if (!$stmt->execute()) {
            $err = "Error: " . $stmt->error;
            break;
        }
    }

    if (!isset($err)) {
        header("Location: consultation.php?patient_id=$patient_id");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Prescription</title>
    <link rel="stylesheet" href="managepatients.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .add-row {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            cursor: pointer;
            margin-bottom: 10px;
            display: inline-block;
        }
        .remove-row {
            color: red;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <?php if (isset($err)) { echo "<div style='color: red;'>$err</div>"; } ?>
        <form method="post" onsubmit="return validatePrescriptionForm()">
            <div>
                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($patient->name); ?></p>
                <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($patient_id); ?></p>
            </div>
            <input type="hidden" id="consultationID" name="consultation_id" value="<?php echo htmlspecialchars($consultation_id); ?>">
            <input type="hidden" id="patientID" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
            <table id="prescriptionTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medication Name</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><input type="text" name="medication[]" required></td>
                        <td><input type="text" name="dosage[]" required></td>
                        <td><input type="text" name="duration[]" required></td>
                        <td><span class="remove-row" onclick="removeRow(this)">Remove</span></td>
                    </tr>
                </tbody>
            </table>
            <div class="add-row" onclick="addRow()">Add Medication</div>
            <button type="submit" class="btn" name="add_prescription">Save Prescription</button>
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

    <script>
        function addRow() {
            const table = document.getElementById('prescriptionTable').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length;
            const row = table.insertRow(rowCount);

            row.innerHTML = `
                <td>${rowCount + 1}</td>
                <td><input type="text" name="medication[]" required></td>
                <td><input type="text" name="dosage[]" required></td>
                <td><input type="text" name="duration[]" required></td>
                <td><span class="remove-row" onclick="removeRow(this)">Remove</span></td>
            `;
        }

        function removeRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const table = document.getElementById('prescriptionTable').getElementsByTagName('tbody')[0];
            for (let i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[0].innerText = i + 1;
            }
        }

    </script>

</body>
</html>
