<?php
session_start();
include('config.php'); 
// Define initial query to fetch all consultations
$query = "SELECT consultation_id, patient_id, user_id, visit_date, consultation_type, notes, diagnosis, treatment_plan, created_at FROM consultations ORDER BY consultation_id DESC";

// Execute query
$result = $mysqli->query($query);

// Check if there are any consultations
if ($result->num_rows > 0) {
    // Initialize an array to store consultation data
    $consultations = [];

    // Fetch data and store in the consultations array
    while ($row = $result->fetch_assoc()) {
        $consultations[] = $row;
    }
} else {
    $error_message = "No consultations found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Records</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
        .header-title {
            margin-bottom: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <div class="content-page">
            <div class="content">
                <div class="page-title-box">
                    <div class="breadcrumb">
                        <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="consultationrecords.php">Registration Records</a></div>
                        <div class="breadcrumb-item active">Consultation Records</div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Consultation Records</h4>
                    </div>

                    <?php if (isset($error_message)) : ?>
                        <p class="text-danger"><?php echo $error_message; ?></p>
                    <?php else : ?>
                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Consultation ID</th>
                                        <th>Patient ID</th>
                                        <th>Registered By (User ID)</th>
                                        <th>Visit Date</th>
                                        <th>Consultation Type</th>
                                        <th>Notes</th>
                                        <th>Diagnosis</th>
                                        <th>Treatment Plan</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($consultations as $consultation) : ?>
                                        <tr>
                                            <td><?php echo $consultation['consultation_id']; ?></td>
                                            <td><?php echo $consultation['patient_id']; ?></td>
                                            <td><?php echo $consultation['user_id']; ?></td>
                                            <td><?php echo $consultation['visit_date']; ?></td>
                                            <td><?php echo $consultation['consultation_type']; ?></td>
                                            <td><?php echo htmlspecialchars($consultation['notes']); ?></td>
                                            <td><?php echo htmlspecialchars($consultation['diagnosis']); ?></td>
                                            <td><?php echo htmlspecialchars($consultation['treatment_plan']); ?></td>
                                            <td><?php echo $consultation['created_at']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="text-center">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
