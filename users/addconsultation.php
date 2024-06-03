<?php
session_start();
include('config.php');

if (isset($_POST['add_consultation'])) {
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $visit_date = date('Y-m-d');
    $consultation_type = $_POST['consultation_type'];
    $notes = $_POST['notes'];
    $diagnosis = $_POST['diagnosis'];
    $treatment_plan = $_POST['treatment_plan'];

    $query = "INSERT INTO consultations (patient_id, user_id, visit_date, consultation_type, notes, diagnosis, treatment_plan) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iisssss', $patient_id, $user_id, $visit_date, $consultation_type, $notes, $diagnosis, $treatment_plan);
    if ($stmt->execute()) {
        $success = "Consultation added successfully";
    } else {
        $err = "Please try again later";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Consultation</title>
    <link rel="stylesheet" href="addpatient.css">
</head>
<body>
    <!-- Header -->
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <!-- Breadcrumb and Page Title -->
                <div class="page-title-box">
                    <div class="breadcrumb">
                        <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="manage_consultations.php">Manage Consultations</a></div>
                        <div class="breadcrumb-item active">Add Consultation</div>
                    </div>
                </div>

                <!-- Add Consultation Form -->
                <div class="card-box">
                    <h4 class="header-title">Add Consultation</h4>
                    <?php if (isset($err)) { echo "<p style='color: red;'>$err</p>"; } ?>
                    <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="patient_id" class="col-form-label">Patient ID</label>
                                <input type="text" required="required" name="patient_id" class="form-control" id="patient_id">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="consultation_type" class="col-form-label">Consultation Type</label>
                                <select name="consultation_type" class="form-control" id="consultation_type" required="required">
                                    <option value="general">General</option>
                                    <option value="dental">Dental</option>
                                    <option value="vct">VCT</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="notes" class="col-form-label">Notes</label>
                                <textarea required="required" name="notes" class="form-control" id="notes"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="diagnosis" class="col-form-label">Diagnosis</label>
                                <input type="text" required="required" name="diagnosis" class="form-control" id="diagnosis">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="treatment_plan" class="col-form-label">Treatment Plan</label>
                            <textarea required="required" name="treatment_plan" class="form-control" id="treatment_plan"></textarea>
                        </div>
                        <button type="submit" name="add_consultation" class="btn btn-success">Add Consultation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
