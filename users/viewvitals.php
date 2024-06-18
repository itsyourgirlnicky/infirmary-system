<?php
session_start();
include('config.php');

// Check if the vital_id is set in the GET request
if (isset($_GET['vital_id'])) {
    $vital_id = intval($_GET['vital_id']);
    
    // Fetch vital details using vital_id
    $query = "SELECT vitals.*, patients.name AS patient_name 
              FROM vitals 
              JOIN patients ON vitals.patient_id = patients.patient_id 
              WHERE vitals.vital_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $vital_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vitals = $result->fetch_object();

    if (!$vitals) {
        // Handle the case where the vital record is not found
        echo "Vital record not found for Vital ID: " . htmlspecialchars($vital_id);
        exit();
    }
} else {
    // Handle the case where vital_id is not set
    echo "Vital ID not set.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Vitals</title>
    <link rel="stylesheet" href="managepatients.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="container text-center" style="text-align: center;">
        <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
    </div>
</header>

<div class="container">
    <div class="content-page">
        <div class="content">
            <div class="page-title-box">
                <div class="breadcrumb">
                    <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="managetriage.php">Manage Triage</a></div>
                    <div class="breadcrumb-item active">Patient Vitals</div>
                </div>
            </div>

            <div class="card-box">
                <h4 class="header-title">Patient Vitals</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Visit Date:</strong> <?php echo htmlspecialchars($vitals->visit_date); ?></p>
                        <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($vitals->patient_name); ?></p>
                        <p><strong>Temperature (°C):</strong> <?php echo htmlspecialchars($vitals->temperature); ?></p>
                        <p><strong>Blood Pressure (mmHg):</strong> <?php echo htmlspecialchars($vitals->blood_pressure); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Weight (kg):</strong> <?php echo htmlspecialchars($vitals->weight); ?></p>
                        <p><strong>Height (cm):</strong> <?php echo htmlspecialchars($vitals->height); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div style="text-align: center;">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>
</body>
</html>
