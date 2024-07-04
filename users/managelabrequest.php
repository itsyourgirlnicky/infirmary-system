<?php
session_start();
include('config.php');

// Handle Add action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_lab_request'])) {
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id'];
    $test_name = $_POST['test_name'];
    $created_at = date('Y-m-d H:i:s');
    $result = $_POST['result'];
    $status = $_POST['status'];

    // Fetch the latest consultation_id for the given patient_id
    $consultation_query = "SELECT consultation_id FROM consultations WHERE patient_id = ? ORDER BY visit_date DESC LIMIT 1";
    $stmt = $mysqli->prepare($consultation_query);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $stmt->bind_result($consultation_id);
    $stmt->fetch();
    $stmt->close();

    if ($consultation_id) {
        $query = "INSERT INTO labrequests (consultation_id, patient_id, user_id, test_name, created_at, result, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iisssss', $consultation_id, $patient_id, $user_id, $test_name, $created_at, $result, $status);
        if ($stmt->execute()) {
            header("Location: managelabrequest.php");
            exit();
        } else {
            $err_add = "Failed to add lab request. Please try again.";
        }
        $stmt->close();
    } else {
        $err_add = "No consultation found for the given patient ID.";
    }
}

// Fetch lab requests from the database
$query = "SELECT lab_request_id, consultation_id, patient_id, user_id, test_name, created_at, result, status FROM labrequests ORDER BY created_at DESC";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Requests</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Header Styling */
        .navbar {
            background-color: #800000;
            color: #ffc300;
            padding: 10px 0;
        }

        .navbar .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
            color: #ffc300;
            text-align: center;
        }
        .footer {
            background-color: #800000;
            color: #ffc300;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
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

            <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></li>
                                    <li class="breadcrumb-item active">Lab Requests</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Patients with Lab Requests</h4>
                            <div class="mb-2">
                                <div class="row">
                                    <div class="col-12 text-sm-center form-inline">
                                        <button class="btn btn-success" data-toggle="modal" data-target="#addLabRequestModal">Add New Lab Request</button>
                                    </div>
                                </div>
                            </div>

                            <?php if (isset($err_add)) { echo "<p class='text-danger'>$err_add</p>"; } ?>
                            <?php if (isset($err_delete)) { echo "<p class='text-danger'>$err_delete</p>"; } ?>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th data-hide="phone">Patient ID</th>
                                            <th data-toggle="true">Patient Name</th>
                                            <th data-hide="phone">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Retrieve patients with lab requests
                                        $query = "SELECT DISTINCT p.patient_id, p.name
                                                  FROM patients p
                                                  INNER JOIN consultations c ON p.patient_id = c.patient_id
                                                  INNER JOIN labrequests l ON p.patient_id = l.patient_id
                                                  ORDER BY p.name ASC";
                                        $stmt = $mysqli->prepare($query);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo htmlspecialchars($row->patient_id); ?></td>
                                                <td><?php echo htmlspecialchars($row->name); ?></td>
                                                <td><a href="viewlabrequests.php?patient_id=<?php echo htmlspecialchars($row->patient_id); ?>" class="badge badge-primary"><i class="mdi mdi-flask-outline"></i> View Lab Requests</a></td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="active">
                                            <td colspan="4">
                                                <div class="text-right">
                                                    <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div> <!-- end .card-box -->
                    </div> <!-- end .col -->
                </div>
                <!-- end .row -->

            </div> <!-- container -->

        </div> <!-- content -->
    </div>

    <!-- Add Lab Request Modal -->
    <div class="modal fade" id="addLabRequestModal" tabindex="-1" role="dialog" aria-labelledby="addLabRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="managelabrequest.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLabRequestModalLabel">Add New Lab Request</h5>
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
                            <label for="test_name">Test Name</label>
                            <input type="text" class="form-control" id="test_name" name="test_name" required>
                        </div>
                        <div class="form-group">
                            <label for="result">Result</label>
                            <input type="text" class="form-control" id="result" name="result" required>
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
                        <button type="submit" class="btn btn-primary" name="add_lab_request">Add Lab Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
