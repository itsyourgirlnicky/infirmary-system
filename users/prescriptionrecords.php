<?php
session_start();
include('config.php');

// Handle Add action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_prescription'])) {
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $medication = $_POST['medication'];
    $dosage = $_POST['dosage'];
    $duration = $_POST['duration'];
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO prescriptions (consultation_id, patient_id, user_id, medication, dosage, duration, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iisssss', $consultation_id, $patient_id, $user_id, $medication, $dosage, $duration, $created_at);
    if ($stmt->execute()) {
        header("Location: prescriptionrecords.php");
        exit();
    } else {
        $err_add = "Failed to add prescription. Please try again.";
    }
    $stmt->close();
}

// Handle Update action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_prescription'])) {
    $prescription_id = $_POST['prescription_id'];
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $medication = $_POST['medication'];
    $dosage = $_POST['dosage'];
    $duration = $_POST['duration'];

    $query = "UPDATE prescriptions SET consultation_id=?, patient_id=?, medication=?, dosage=?, duration=? WHERE prescription_id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iisssi', $consultation_id, $patient_id, $medication, $dosage, $duration, $prescription_id);
    if ($stmt->execute()) {
        header("Location: prescriptionrecords.php");
        exit();
    } else {
        $err_update = "Failed to update prescription. Please try again.";
    }
    $stmt->close();
}

// Fetch prescriptions from the database
$query = "SELECT prescription_id, consultation_id, patient_id, user_id, medication, dosage, duration, created_at FROM prescriptions ORDER BY created_at DESC";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescriptions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <div class="breadcrumb-item active">Prescriptions</div>
                </div>
            </div>

            <div class="card-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title">Prescriptions</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#addPrescriptionModal">Add New Prescription</button>
                </div>

                <?php if (isset($err_add)) { echo "<p class='text-danger'>$err_add</p>"; } ?>
                <?php if (isset($err_delete)) { echo "<p class='text-danger'>$err_delete</p>"; } ?>
                <?php if (isset($err_update)) { echo "<p class='text-danger'>$err_update</p>"; } ?>

                <div class="table-container mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Prescription ID</th>
                                <th>Patient ID</th>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Duration</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['prescription_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['medication']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dosage']); ?></td>
                                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm editBtn" data-id="<?php echo $row['prescription_id']; ?>" data-consultation_id="<?php echo $row['consultation_id']; ?>" data-patient_id="<?php echo $row['patient_id']; ?>" data-medication="<?php echo $row['medication']; ?>" data-dosage="<?php echo $row['dosage']; ?>" data-duration="<?php echo $row['duration']; ?>">Update</button>
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

<!-- Add Prescription Modal -->
<div class="modal fade" id="addPrescriptionModal" tabindex="-1" role="dialog" aria-labelledby="addPrescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="prescriptionrecords.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPrescriptionModalLabel">Add New Prescription</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label for="medication">Medication</label>
                        <input type="text" class="form-control" id="medication" name="medication" required>
                    </div>
                    <div class="form-group">
                        <label for="dosage">Dosage</label>
                        <input type="text" class="form-control" id="dosage" name="dosage" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" class="form-control" id="duration" name="duration" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add_prescription">Add Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Prescription Modal -->
<div class="modal fade" id="editPrescriptionModal" tabindex="-1" role="dialog" aria-labelledby="editPrescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="prescriptionrecords.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPrescriptionModalLabel">Edit Prescription</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_prescription_id" name="prescription_id">
                    <div class="form-group">
                        <label for="edit_patient_id">Patient ID</label>
                        <input type="number" class="form-control" id="edit_patient_id" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_medication">Medication</label>
                        <input type="text" class="form-control" id="edit_medication" name="medication" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_dosage">Dosage</label>
                        <input type="text" class="form-control" id="edit_dosage" name="dosage" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_duration">Duration</label>
                        <input type="text" class="form-control" id="edit_duration" name="duration" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="edit_prescription">Update Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer style="background-color: #800000; color: #ffc300; padding: 10px; text-align: center; position: fixed; bottom: 0; left: 0; width: 100%;">
    <div class="container">
        <p>&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('.editBtn').on('click', function() {
        $('#editPrescriptionModal').modal('show');
        
        $('#edit_prescription_id').val($(this).data('id'));
        $('#edit_consultation_id').val($(this).data('consultation_id'));
        $('#edit_patient_id').val($(this).data('patient_id'));
        $('#edit_medication').val($(this).data('medication'));
        $('#edit_dosage').val($(this).data('dosage'));
        $('#edit_duration').val($(this).data('duration'));
    });
});
</script>
</body>
</html>
