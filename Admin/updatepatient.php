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
        header("Location: registrationrecords.php");
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
    <!-- Update Patient Form -->
    <div class="container">
        <div class="card-box">
            <h4 class="header-title">Update Patient Details</h4>
            <?php if (isset($err)) { echo "<p class='text-danger'>$err</p>"; } ?>
            <?php if (isset($success)) { echo "<p class='text-success'>$success</p>"; } ?>
            <form method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" required="required" name="name" value="<?php echo htmlspecialchars($patient->name); ?>" class="form-control" id="name" placeholder="Patient's Name">
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" required="required" name="age" value="<?php echo htmlspecialchars($patient->age); ?>" class="form-control" id="age" placeholder="Patient's Age">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" class="form-control" id="gender" required="required">
                        <option value="male" <?php if ($patient->gender == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if ($patient->gender == 'female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" required="required" name="contact_number" value="<?php echo htmlspecialchars($patient->contact_number); ?>" class="form-control" id="contact_number" placeholder="Contact Number">
                </div>
                <div class="form-group">
                    <label for="student_employee_number">Student/Employee Number</label>
                    <input type="text" required="required" name="student_employee_number" value="<?php echo htmlspecialchars($patient->student_employee_number); ?>" class="form-control" id="student_employee_number" placeholder="Student/Employee Number">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" required="required" name="address" value="<?php echo htmlspecialchars($patient->address); ?>" class="form-control" id="address" placeholder="Address">
                </div>
                <button type="submit" name="update_patient" class="btn btn-success">Update Patient</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
