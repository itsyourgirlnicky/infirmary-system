<?php
session_start();
include('config.php');

// Get the user ID from session
$user_id = $_SESSION['user_id'];

// Handle Add Consultation action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_consultation'])) {
    $patient_id = $_POST['patient_id'];
    $visit_date = $_POST['visit_date'];
    $consultation_type = $_POST['consultation_type'];
    $notes = $_POST['notes'];
    $diagnosis = $_POST['diagnosis'];
    $treatment_plan = $_POST['treatment_plan'];

    $query = "INSERT INTO consultations (patient_id, user_id, visit_date, consultation_type, notes, diagnosis, treatment_plan) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('issssss', $patient_id, $user_id, $visit_date, $consultation_type, $notes, $diagnosis, $treatment_plan);
    if ($stmt->execute()) {
        header("Location: consultations.php");
        exit();
    } else {
        $err_add = "Failed to add consultation record. Please try again.";
    }
    $stmt->close();
}

// Handle Edit Consultation action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_consultation'])) {
    $consultation_id = $_POST['consultation_id'];
    $patient_id = $_POST['patient_id'];
    $visit_date = $_POST['visit_date'];
    $consultation_type = $_POST['consultation_type'];
    $notes = $_POST['notes'];
    $diagnosis = $_POST['diagnosis'];
    $treatment_plan = $_POST['treatment_plan'];

    $query = "UPDATE consultations SET patient_id = ?, visit_date = ?, consultation_type = ?, notes = ?, diagnosis = ?, treatment_plan = ? WHERE consultation_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('isssssi', $patient_id, $visit_date, $consultation_type, $notes, $diagnosis, $treatment_plan, $consultation_id);
    if ($stmt->execute()) {
        header("Location: consultations.php");
        exit();
    } else {
        $err_edit = "Failed to update consultation record. Please try again.";
    }
    $stmt->close();
}

// Handle Delete Consultation action
if (isset($_GET['delete']) && isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];

    $query = "DELETE FROM consultations WHERE consultation_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $consultation_id);
    if ($stmt->execute()) {
        header("Location: consultations.php");
        exit();
    } else {
        $err_delete = "Failed to delete consultation record. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultations</title>
    <link rel="stylesheet" href="managepatients.css">
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
                    <div class="breadcrumb-item active">Consultations</div>
                </div>
            </div>

            <div class="card-box">
                <h4 class="header-title">Consultations</h4>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addConsultationModal">Add New Consultation</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered toggle-circle mb-0" data-page-size="7">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient ID</th>
                                <th>User ID</th>
                                <th>Visit Date</th>
                                <th>Consultation Type</th>
                                <th>Notes</th>
                                <th>Diagnosis</th>
                                <th>Treatment Plan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM consultations ORDER BY created_at ASC";
                            $stmt = $mysqli->prepare($query);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $cnt = 1;
                            while ($row = $res->fetch_object()) {
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlspecialchars($row->patient_id); ?></td>
                                    <td><?php echo htmlspecialchars($row->user_id); ?></td>
                                    <td><?php echo htmlspecialchars($row->visit_date); ?></td>
                                    <td><?php echo htmlspecialchars($row->consultation_type); ?></td>
                                    <td><?php echo htmlspecialchars($row->notes); ?></td>
                                    <td><?php echo htmlspecialchars($row->diagnosis); ?></td>
                                    <td><?php echo htmlspecialchars($row->treatment_plan); ?></td>
                                    <td>
                                        <a href="updateconsultation.php?edit=1&consultation_id=<?php echo htmlspecialchars($row->consultation_id); ?>" class="badge badge-primary">Update</a>
                                        <a href="consultations.php?delete=1&consultation_id=<?php echo htmlspecialchars($row->consultation_id); ?>" class="badge badge-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php $cnt++; } ?>
                        </tbody>
                        <tfoot>
                            <tr class="active">
                                <td colspan="9">
                                    <div class="text-right">
                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- end .table-responsive-->
            </div> <!-- end card-box -->
        </div> <!-- content -->
    </div> <!-- content-page -->
</div> <!-- container -->

<!-- Add Consultation Modal -->
<div class="modal fade" id="addConsultationModal" tabindex="-1" role="dialog" aria-labelledby="addConsultationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="consultations.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addConsultationModalLabel">Add New Consultation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (isset($err_add)) { echo "<p class='text-danger'>$err_add</p>"; } ?>
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label for="visit_date">Visit Date</label>
                        <input type="datetime-local" class="form-control" id="visit_date" name="visit_date" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation_type">Consultation Type</label>
                        <input type="text" class="form-control" id="consultation_type" name="consultation_type" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="diagnosis">Diagnosis</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="treatment_plan">Treatment Plan</label>
                        <textarea class="form-control" id="treatment_plan" name="treatment_plan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add_consultation">Add Consultation</button>
                </div>
            </form>
        </div>
    </div>
</div>  

<!-- Edit Consultation Modal -->
<?php if (isset($_GET['edit']) && isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
    $query = "SELECT * FROM consultations WHERE consultation_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $consultation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $consultation = $result->fetch_object();
    $stmt->close();
?>
<div class="modal fade" id="editConsultationModal" tabindex="-1" role="dialog" aria-labelledby="editConsultationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="consultations.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editConsultationModalLabel">Edit Consultation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (isset($err_edit)) { echo "<p class='text-danger'>$err_edit</p>"; } ?>
                    <input type="hidden" name="consultation_id" value="<?php echo htmlspecialchars($consultation->consultation_id); ?>">
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="number" class="form-control" id="patient_id" name="patient_id" value="<?php echo htmlspecialchars($consultation->patient_id); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="visit_date">Visit Date</label>
                        <input type="datetime-local" class="form-control" id="visit_date" name="visit_date" value="<?php echo htmlspecialchars($consultation->visit_date); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation_type">Consultation Type</label>
                        <input type="text" class="form-control" id="consultation_type" name="consultation_type" value="<?php echo htmlspecialchars($consultation->consultation_type); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" required><?php echo htmlspecialchars($consultation->notes); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="diagnosis">Diagnosis</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" required><?php echo htmlspecialchars($consultation->diagnosis); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="treatment_plan">Treatment Plan</label>
                        <textarea class="form-control" id="treatment_plan" name="treatment_plan" required><?php echo htmlspecialchars($consultation->treatment_plan); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="edit_consultation">Update Consultation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editConsultationModal').modal('show');
    });
</script>
<?php } ?>

<footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div style="text-align: center;">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
