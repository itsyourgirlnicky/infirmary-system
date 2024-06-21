<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the consultation record for editing
if (isset($_GET['consultation_id'])) {
    $consultation_id = intval($_GET['consultation_id']);
    $query = "SELECT * FROM consultations WHERE consultation_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $consultation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $consultation = $result->fetch_assoc();
    $stmt->close();

    if (!$consultation) {
        echo "Consultation record not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission to update consultation record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_consultation'])) {
    $consultation_id = intval($_POST['consultation_id']);
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];  // Getting user_id from session
    $visit_date = $_POST['visit_date'];
    $consultation_type = $_POST['consultation_type'];
    $notes = $_POST['notes'];
    $diagnosis = $_POST['diagnosis'];
    $treatment_plan = $_POST['treatment_plan'];

    $query = "UPDATE consultations SET patient_id = ?, user_id = ?, visit_date = ?, consultation_type = ?, notes = ?, diagnosis = ?, treatment_plan = ? WHERE consultation_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('issssssi', $patient_id, $user_id, $visit_date, $consultation_type, $notes, $diagnosis, $treatment_plan, $consultation_id);
    
    if ($stmt->execute()) {
        header("Location: manageconsultation.php");
        exit();
    } else {
        $err_update = "Failed to update consultation record. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Consultation</title>
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
                        <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="manageconsultation.php">Manage Consultation</a></div>
                        <div class="breadcrumb-item active">Update Consultation</div>
                    </div>
                </div>

                <div class="card-box">
                    <h4 class="header-title">Update Consultation Record</h4>
                    <?php if (isset($err_update)) { echo "<p class='text-danger'>$err_update</p>"; } ?>
                    <form action="updateconsultation.php?consultation_id=<?php echo $consultation['consultation_id']; ?>" method="POST">
                        <input type="hidden" name="consultation_id" value="<?php echo $consultation['consultation_id']; ?>">
                        <div class="form-group">
                            <label for="patient_id">Patient ID</label>
                            <input type="number" class="form-control" id="patient_id" name="patient_id" value="<?php echo $consultation['patient_id']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="visit_date">Visit Date</label>
                            <input type="date" class="form-control" id="visit_date" name="visit_date" value="<?php echo $consultation['visit_date']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="consultation_type">Consultation Type</label>
                            <input type="text" class="form-control" id="consultation_type" name="consultation_type" value="<?php echo $consultation['consultation_type']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" required><?php echo $consultation['notes']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="diagnosis">Diagnosis</label>
                            <textarea class="form-control" id="diagnosis" name="diagnosis" required><?php echo $consultation['diagnosis']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="treatment_plan">Treatment Plan</label>
                            <textarea class="form-control" id="treatment_plan" name="treatment_plan" required><?php echo $consultation['treatment_plan']; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="update_consultation">Update Consultation</button>
                    </form>
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
