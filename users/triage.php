<?php
session_start();
include('config.php');
// Handle Add action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_vital'])) {
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $visit_date = $_POST['visit_date'];
    $temperature = $_POST['temperature'];
    $blood_pressure = $_POST['blood_pressure'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $status = $_POST['status'];

    $query = "INSERT INTO vitals (patient_id, user_id, visit_date, temperature, blood_pressure, weight, height, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iissiiis', $patient_id, $user_id, $visit_date, $temperature, $blood_pressure, $weight, $height, $status);
    if ($stmt->execute()) {
        header("Location: triagerecords.php");
        exit();
    } else {
        $err_add = "Failed to add triage record. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triage</title>
    <link rel="stylesheet" href="managepatients.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="container text-center" style="text-align: center;">
        <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
    </div>
</header>

<div class="container">
    <div class="content-page">
        <div class="content">
            <div class="page-title-box">
                <div class="breadcrumb">
                    <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                    <div class="breadcrumb-item active">Triage</div>
                </div>
            </div>

            <div class="card-box">
                <h4 class="header-title">Patients Vitals</h4>
                <div class="d-flex justify-content-between align-items-center mb-3">
                        <button class="btn btn-success" data-toggle="modal" data-target="#addTriageModal">Add New Triage Record</button>
                    </div>
                <div class="table-responsive">
                    <table class="table table-bordered toggle-circle mb-0" data-page-size="7">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch patients who do not have a 'completed' status in vitals
                            $ret = "SELECT p.patient_id, p.name 
                                    FROM patients p 
                                    WHERE NOT EXISTS (
                                        SELECT 1 FROM vitals v 
                                        WHERE v.patient_id = p.patient_id 
                                        AND v.status = 'completed'
                                    )
                                    ORDER BY p.created_at ASC";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $cnt = 1;
                            while ($row = $res->fetch_object()) {
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlspecialchars($row->patient_id); ?></td>
                                    <td><?php echo htmlspecialchars($row->name); ?></td>
                                    <td><a href="capturevitals.php?patient_id=<?php echo htmlspecialchars($row->patient_id); ?>" class="badge badge-primary"><i class="mdi mdi-hospital-box"></i> Capture Vitals</a></td>
                                </tr>
                            <?php $cnt++; } ?>
                        </tbody>
                        <tfoot>
                            <tr class="active">
                                <td colspan="8">
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
<!-- Add Triage Modal -->
<div class="modal fade" id="addTriageModal" tabindex="-1" role="dialog" aria-labelledby="addTriageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="triagerecords.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTriageModalLabel">Add New Triage Record</h5>
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
                            <label for="visit_date">Visit Date</label>
                            <input type="date" class="form-control" id="visit_date" name="visit_date" required>
                        </div>
                        <div class="form-group">
                            <label for="temperature">Temperature</label>
                            <input type="number" step="0.1" class="form-control" id="temperature" name="temperature" required>
                        </div>
                        <div class="form-group">
                            <label for="blood_pressure">Blood Pressure</label>
                            <input type="number" class="form-control" id="blood_pressure" name="blood_pressure" required>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight</label>
                            <input type="number" class="form-control" id="weight" name="weight" required>
                        </div>
                        <div class="form-group">
                            <label for="height">Height</label>
                            <input type="number" class="form-control" id="height" name="height" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_vital">Add Triage Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>  //end
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
