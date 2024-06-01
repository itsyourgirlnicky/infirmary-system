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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient</title>
    <link rel="stylesheet" href="managepatients.css"> 
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
            <div class="breadcrumb-item active">View Patient</div>
        </div>
    </div>

    <!-- Patient Details -->
    <div class="card-box">
        <h4 class="header-title">Patient Details</h4>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($patient->name); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($patient->age); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient->gender); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($patient->contact_number); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Student/Employee Number:</strong> <?php echo htmlspecialchars($patient->student_employee_number); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($patient->address); ?></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p style="margin: 0;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
