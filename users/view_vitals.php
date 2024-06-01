<?php
session_start();
include('config.php');

$patient_id = $_GET['patient_id'];
$ret = "SELECT * FROM vitals WHERE patient_id = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('i', $patient_id);
$stmt->execute();
$res = $stmt->get_result();
$vitals = $res->fetch_object();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vitals</title>
    <link rel="stylesheet" href="managepatients.css">
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="page-title-box">
        <div class="breadcrumb">
            <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="managetriage.php">Manage Triage</a></div>
            <div class="breadcrumb-item active">View Vitals</div>
        </div>
    </div>

    <div class="card-box">
        <h4 class="header-title">Patient Vitals</h4>
        <div class="table-responsive">
            <?php if ($vitals) { ?>
                <table class="table table-bordered toggle-circle mb-0" data-page-size="7">
                    <thead>
                        <tr>
                            <th>Temperature (°C)</th>
                            <th>Blood Pressure (mmHg)</th>
                            <th>Weight (kg)</th>
                            <th>Height (cm)</th>
                            <th>Visit Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $vitals->temperature; ?></td>
                            <td><?php echo $vitals->blood_pressure; ?></td>
                            <td><?php echo $vitals->weight; ?></td>
                            <td><?php echo $vitals->height; ?></td>
                            <td><?php echo $vitals->visit_date; ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No vitals found for this patient.</p>
            <?php } ?>
        </div> <!-- end .table-responsive-->
    </div> <!-- end card-box -->

    <footer class="footer">
        <div class="container">
            <p style>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
