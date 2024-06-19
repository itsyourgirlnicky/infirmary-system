<?php
session_start();
include('config.php');

// Check if the patient_id is set in the GET request
if (isset($_GET['patient_id'])) {
    $patient_id = intval($_GET['patient_id']);
    
    // Fetch patient details
    $query = "SELECT * FROM patients WHERE patient_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_object();
}

// Update patient details
if (isset($_POST['update_patient'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $student_employee_number = $_POST['student_employee_number'];
    $address = $_POST['address'];

    // SQL to update patient details
    $query = "UPDATE patients SET name = ?, age = ?, gender = ?, contact_number = ?, student_employee_number = ?, address = ? WHERE patient_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sissssi', $name, $age, $gender, $contact_number, $student_employee_number, $address, $patient_id);
    $stmt->execute();

    if ($stmt) {
        $success = "Patient details updated successfully";
        header("Location: manage_patients.php");
        exit();
    } else {
        $err = "Please try again later";
    }
}

// Send patient record to triage
if (isset($_POST['send_to_triage'])) {
    $patient_id = $_POST['patient_id'];
    
    // Insert into a vitals or triage table
    $query = "INSERT INTO vitals (patient_id, status) VALUES (?, 'pending')";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    
    if ($stmt) {
        $success = "Patient record sent to triage successfully";
        header("Location: manage_patients.php");
        exit();
    } else {
        $err = "Please try again later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient</title>
    <link rel="stylesheet" href="updatepatient.css">
</head>
<body>
    <!-- Header -->
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <!-- Breadcrumb and Page Title -->
    <div class="page-title-box">
        <div class="breadcrumb">
            <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="manage_patients.php">Manage Patients</a></div>
            <div class="breadcrumb-item active">Update Patient</div>
        </div>
    </div>

    <!-- Update Patient Form -->
    <div class="card-box">
        <h4 class="header-title">Update Patient Details</h4>
        <?php if (isset($err)) { echo "<p style='color: red;'>$err</p>"; } ?>
        <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name" class="col-form-label">Name</label>
                    <input type="text" required="required" name="name" value="<?php echo htmlspecialchars($patient->name); ?>" class="form-control" id="name" placeholder="Patient's Name">
                </div>
                <div class="form-group col-md-6">
                    <label for="age" class="col-form-label">Age</label>
                    <input type="number" required="required" name="age" value="<?php echo htmlspecialchars($patient->age); ?>" class="form-control" id="age" placeholder="Patient's Age">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="gender" class="col-form-label">Gender</label>
                    <select name="gender" class="form-control" id="gender" required="required">
                        <option value="male" <?php if ($patient->gender == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if ($patient->gender == 'female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="contact_number" class="col-form-label">Contact Number</label>
                    <input type="text" required="required" name="contact_number" value="<?php echo htmlspecialchars($patient->contact_number); ?>" class="form-control" id="contact_number" placeholder="Contact Number">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="student_employee_number" class="col-form-label">Student/Employee Number</label>
                    <input type="text" required="required" name="student_employee_number" value="<?php echo htmlspecialchars($patient->student_employee_number); ?>" class="form-control" id="student_employee_number" placeholder="Student/Employee Number">
                </div>
                <div class="form-group col-md-6">
                    <label for="address" class="col-form-label">Address</label>
                    <input type="text" required="required" name="address" value="<?php echo htmlspecialchars($patient->address); ?>" class="form-control" id="address" placeholder="Address">
                </div>
            </div>

            <button type="submit" name="update_patient" class="btn btn-success">Update Patient</button>
        </form>

        <!-- Form for sending to triage -->
        <form method="post" action="update_patient.php">
            <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
            <button type="submit" name="send_to_triage" class="btn btn-primary">Send to Triage</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p style="margin: 0;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
