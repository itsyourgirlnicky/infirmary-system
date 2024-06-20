<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  //handle admin login this is not correct
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
$query = "SELECT patient_id, name FROM patients";
$result = $mysqli->query($query);

// Display error message if deletion fails
if (isset($err_delete)) {
    echo "<p class='text-danger'>$err_delete</p>";
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
                    <div class="table-container mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['patient_id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td>
                                        <a href="viewpatient.php?patient_id=<?php echo $row['patient_id']; ?>" class="badge badge-primary">View</a>
                                            <a href="updatepatient.php?patient_id=<?php echo $row['patient_id']; ?>" class="badge badge-primary">Update</a>
                                            <a href="registrationrecords.php?patient_id=<?php echo $row['patient_id']; ?>&delete_patient=1" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this patient?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> 
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
