<?php
session_start();
include('config.php');

// Check if patient_id is set
if (!isset($_GET['patient_id'])) {
    header('Location: dashboard.php');
    exit();
}

$patient_id = $_GET['patient_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Requests</title>
    <link rel="stylesheet" href="managepatients.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Header Styling and other styles */
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="breadcrumb">
                                <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></div>
                                <div class="breadcrumb-item active">Lab Requests</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Laboratory Requests for Patient ID: <?php echo htmlspecialchars($patient_id); ?></h4>
                            <div class="table-responsive">
                                <table class="table table-bordered toggle-circle mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Test Name</th>
                                            <th>Request Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Retrieve lab requests for the patient
                                        $query = "SELECT lab_request_id, test_name, created_at, status
                                                  FROM labrequests
                                                  WHERE patient_id = ?
                                                  ORDER BY created_at ASC";
                                        $stmt = $mysqli->prepare($query);
                                        $stmt->bind_param('s', $patient_id);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo htmlspecialchars($row->test_name); ?></td>
                                                <td><?php echo htmlspecialchars($row->created_at); ?></td>
                                                <td><?php echo htmlspecialchars($row->status); ?></td>
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
                            </div> <!-- end .table-responsive -->
                        </div> <!-- end .card-box -->
                    </div> <!-- end .col-12 -->
                </div> <!-- end .row -->
            </div> <!-- end .container-fluid -->
        </div> <!-- end .content -->
        
        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Catholic University of Eastern Africa</p>
            </div>
        </footer>
    </div> <!-- end .content-page -->
</body>
</html>
