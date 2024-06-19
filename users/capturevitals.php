<?php
session_start();
include('config.php');

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Capture vitals from form submission
        $temperature = $_POST['temperature'];
        $blood_pressure = $_POST['blood_pressure'];
        $weight = $_POST['weight'];
        $height = $_POST['height'];
        
        // Insert the captured vitals into the vitals table
        $query = "INSERT INTO vitals (patient_id, user_id, visit_date, temperature, blood_pressure, weight, height, created_at, status) 
                  VALUES (?, ?, NOW(), ?, ?, ?, ?, NOW(), 'completed')";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iisdds', $patient_id, $_SESSION['user_id'], $temperature, $blood_pressure, $weight, $height);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "Vitals captured successfully.";
            // Redirect back to triage page
            header("Location: triage.php");
            exit();
        } else {
            echo "Failed to capture vitals.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture Vitals</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="C:\xampp\htdocs\Final Year Project\users\addpatient.css">
</head>
<body>
<header style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="container text-center" style="text-align: center;">
        <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
    </div>
</header>

<div class="container">
    <h2>Capture Vitals</h2>
    <form method="POST">
        <div class="form-group">
            <label for="temperature">Temperature</label>
            <input type="text" class="form-control" id="temperature" name="temperature" required>
        </div>
        <div class="form-group">
            <label for="blood_pressure">Blood Pressure</label>
            <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" required>
        </div>
        <div class="form-group">
            <label for="weight">Weight</label>
            <input type="text" class="form-control" id="weight" name="weight" required>
        </div>
        <div class="form-group">
            <label for="height">Height</label>
            <input type="text" class="form-control" id="height" name="height" required>
        </div>
        <button type="submit" class="btn btn-primary">Capture Vitals</button>
    </form>
</div>

<footer style="background-color: #800000; color: #ffc300; padding: 10px; position: absolute; bottom: 0; width: 100%;">
    <div style="text-align: center;">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>

</body>
</html>
