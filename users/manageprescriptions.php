<?php
session_start();
include('config.php');

// Handle Delete action
if (isset($_GET['prescription_id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $prescription_id = intval($_GET['prescription_id']);
    $query = "DELETE FROM prescriptions WHERE prescription_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $prescription_id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard_prescriptions.php");
        exit();
    } else {
        $err_delete = "Failed to delete prescription. Please try again.";
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
