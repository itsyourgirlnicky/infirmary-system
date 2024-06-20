<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Handle admin login
    exit();
}

// Handle Delete action
if (isset($_GET['patient_id'])) {
    $patient_id = intval($_GET['patient_id']);
    $query = "DELETE FROM patients WHERE patient_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $patient_id);
    if ($stmt->execute()) {
        // Deletion successful, redirect to refresh the page
        header("Location: registrationrecords.php");
        exit();
    } else {
        $err_delete = "Failed to delete patient. Please try again.";
    }
    $stmt->close();
}

// Fetch patient records from the database
$query = "SELECT patient_id, name, gender, created_at, user_id FROM patients ORDER BY created_at DESC";
$result = $mysqli->query($query);

// Display error message if deletion fails
if (isset($err_delete)) {
    echo "<p class='text-danger'>$err_delete</p>";
}

// Classify patients by gender and creation date
$patientsByGender = [];
$patientsByDate = [];
while ($row = $result->fetch_assoc()) {
    $patientsByGender[$row['gender']][] = $row;
    $date = substr($row['created_at'], 0, 10); // Extract the date part
    $patientsByDate[$date][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Records</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                        <div class="breadcrumb-item"><a href="admindashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="registrationrecords.php">Registration Records</a></div>
                        <div class="breadcrumb-item active">Patient Details</div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Patient Records</h4>
                        <a href="add_patient.php" class="btn btn-success">Add New Patient</a>
                    </div>

                    <h5>Patients by Gender:</h5>
                    <?php foreach ($patientsByGender as $gender => $patients) { ?>
                        <h6><?php echo htmlspecialchars($gender); ?>:</h6>
                        <div class="table-container mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Patient ID</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Created At</th>
                                        <th>Registered By (UserID)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($patients as $patient) { ?>
                                        <tr>
                                            <td><?php echo $patient['patient_id']; ?></td>
                                            <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['created_at']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['user_id']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                    <h5>Patients by Creation Date:</h5>
                    <?php foreach ($patientsByDate as $date => $patients) { ?>
                        <h6><?php echo htmlspecialchars($date); ?>:</h6>
                        <div class="table-container mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Patient ID</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Created At</th>
                                        <th>Registered By (UserID)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($patients as $patient) { ?>
                                        <tr>
                                            <td><?php echo $patient['patient_id']; ?></td>
                                            <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['created_at']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['user_id']); ?></td>
                                            
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="text-center">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
