<?php
session_start();
include('config.php');

// Check if patient_id is set in the URL
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Fetch patient name
    $stmt = $mysqli->prepare("SELECT name FROM patients WHERE patient_id = ?");
    $stmt->bind_param('s', $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_object();
    
    if (!$patient) {
        echo "Patient not found.";
        exit;
    }

    // Fetch consultation notes
    $stmt = $mysqli->prepare("SELECT * FROM consultationnotes WHERE patient_id = ? ORDER BY date ASC");
    $stmt->bind_param('s', $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Patient ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Notes</title>
    <link rel="stylesheet" href="managepatients.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #800000;
            padding: 10px;
        }
        .navbar h1 {
            margin: 0;
            font-size: 24px;
            color: #ffc300;
        }
        .content-page {
            padding: 20px;
        }
        .card-box {
            border: 1px solid #ddd;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
        }
        .header-title {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .footer {
            background-color: #800000;
            color: #ffc300;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1>CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="consultationrecords.php">Consultation Records</a></li>
                                    <li class="breadcrumb-item active">Consultation Notes</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Consultation Notes for <?php echo htmlspecialchars($patient->name); ?></h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Notes</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while ($row = $result->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row->date); ?></td>
                                            <td><?php echo htmlspecialchars($row->notes); ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Catholic University of Eastern Africa</p>
            </div>
        </footer>
    </div>
</body>
</html>
