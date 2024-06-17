<?php
session_start();
include('config.php');

// Check if the patient_id is set in the GET request
if (isset($_GET['patient_id'])) {
    $patient_id = intval($_GET['patient_id']);
    
    // Fetch the latest vital details for the patient
    $query = "SELECT * FROM vitals WHERE patient_id = ? ORDER BY visit_date DESC LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vitals = $result->fetch_object();

    if (!$vitals) {
        // Handle the case where no vital record is found for the patient
        echo "No vital records found for Patient ID: " . htmlspecialchars($patient_id);
        exit();
    }
} else {
    // Handle the case where patient_id is not set
    echo "Patient ID not set.";
    header("Location: triage.php");
    exit();
}

// Update vital details
if (isset($_POST['update_vitals'])) {
    $temperature = $_POST['temperature'];
    $blood_pressure = $_POST['blood_pressure'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    // SQL to update vital details
    $query = "UPDATE vitals SET temperature = ?, blood_pressure = ?, weight = ?, height = ? WHERE vital_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('dssdi', $temperature, $blood_pressure, $weight, $height, $vitals->vital_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $success = "Patient vitals updated successfully";
        header("Location: managevitals.php");
        exit();
    } else {
        $err = "No changes were made to the patient vitals.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Vitals</title>
    <link rel="stylesheet" href="updatepatient.css"> 
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        .content-page {
            flex: 1;
        }
        .footer {
            background-color: #800000;
            color: #ffc300;
            text-align: center;
            padding: 10px 0;
            position: relative;
            width: 100%;
            margin-top: auto;
        }
    </style>
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
            <div class="breadcrumb-item"><a href="managetriage.php">Manage Triage</a></div>
            <div class="breadcrumb-item active">Update Vitals</div>
        </div>
    </div>

    <!-- Update Vitals Form -->
    <div class="card-box">
        <h4 class="header-title">Update Patient Vitals</h4>
        <?php if (isset($err)) { echo "<p style='color: red;'>$err</p>"; } ?>
        <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="temperature" class="col-form-label">Temperature (°C)</label>
                    <input type="text" required="required" name="temperature" value="<?php echo htmlspecialchars($vitals->temperature); ?>" class="form-control" id="temperature" placeholder="Temperature">
                </div>
                <div class="form-group col-md-3">
                    <label for="blood_pressure" class="col-form-label">Blood Pressure (mmHg)</label>
                    <input type="text" required="required" name="blood_pressure" value="<?php echo htmlspecialchars($vitals->blood_pressure); ?>" class="form-control" id="blood_pressure" placeholder="Blood Pressure">
                </div>
                <div class="form-group col-md-3">
                    <label for="weight" class="col-form-label">Weight (kg)</label>
                    <input type="text" required="required" name="weight" value="<?php echo htmlspecialchars($vitals->weight); ?>" class="form-control" id="weight" placeholder="Weight">
                </div>
                <div class="form-group col-md-3">
                    <label for="height" class="col-form-label">Height (cm)</label>
                    <input type="text" required="required" name="height" value="<?php echo htmlspecialchars($vitals->height); ?>" class="form-control" id="height" placeholder="Height">
                </div>
            </div>
            <button type="submit" name="update_vitals" class="btn btn-success">Update Vitals</button>
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
