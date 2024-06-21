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
                    <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
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
                                    <td><?php echo htmlspecialchars($row->visit_date); ?></td>
                                    <td><?php echo htmlspecialchars($row->consultation_type); ?></td>
                                    <td><?php echo htmlspecialchars($row->notes); ?></td>
                                    <td><?php echo htmlspecialchars($row->diagnosis); ?></td>
                                    <td><?php echo htmlspecialchars($row->treatment_plan); ?></td>
                                    <td>
                                        <a href="updateconsultation.php" class="badge badge-primary" data-toggle="modal" data-target="#editConsultationModal<?php echo $row->consultation_id; ?>">Update</a>
                                    </td>
                                </tr>

                                <!-- Edit Consultation Modal -->
                                <div class="modal fade" id="editConsultationModal<?php echo $row->consultation_id; ?>" tabindex="-1" role="dialog" aria-labelledby="editConsultationModalLabel" aria-hidden="true">
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
                                                    <input type="hidden" name="consultation_id" value="<?php echo htmlspecialchars($row->consultation_id); ?>">
                                                    <div class="form-group">
                                                        <label for="patient_id">Patient ID</label>
                                                        <input type="number" class="form-control" id="patient_id" name="patient_id" value="<?php echo htmlspecialchars($row->patient_id); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="visit_date">Visit Date</label>
                                                        <input type="datetime-local" class="form-control" id="visit_date" name="visit_date" value="<?php echo htmlspecialchars($row->visit_date); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="consultation_type">Consultation Type</label>
                                                        <input type="text" class="form-control" id="consultation_type" name="consultation_type" value="<?php echo htmlspecialchars($row->consultation_type); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="notes">Notes</label>
                                                        <textarea class="form-control" id="notes" name="notes" required><?php echo htmlspecialchars($row->notes); ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="diagnosis">Diagnosis</label>
                                                        <textarea class="form-control" id="diagnosis" name="diagnosis" required><?php echo htmlspecialchars($row->diagnosis); ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="treatment_plan">Treatment Plan</label>
                                                        <textarea class="form-control" id="treatment_plan" name="treatment_plan" required><?php echo htmlspecialchars($row->treatment_plan); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="edit_consultation">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $cnt++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

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
                                <button type="submit" class="btn btn-primary" name="add_consultation">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
