<?php
session_start();
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Triage</title>
    <link rel="stylesheet" href="managepatients.css">
</head>
<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="page-title-box">
        <div class="breadcrumb">
            <div class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="javascript: void(0)">Triage</a></div>
            <div class="breadcrumb-item active">Manage Triage</div>
        </div>
    </div>

    <div class="card-box">
        <h4 class="header-title">Patient Records</h4>
        <div class="table-responsive">
            <table class="table table-bordered toggle-circle mb-0" data-page-size="7">
                <thead>
                    <tr>
                        <th>#</th>
                        <th data-toggle="true">Patient Name</th>
                        <th data-hide="phone">Patient ID</th>
                        <th data-hide="phone">Contact Number</th>
                        <th data-hide="phone">Address</th>
                        <th data-hide="phone">Age</th>
                        <th data-hide="phone">Gender</th>
                        <th data-hide="phone">Action</th>
                    </tr>
                </thead>
                <?php
                $ret = "SELECT * FROM patients ORDER BY created_at DESC";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute();
                $res = $stmt->get_result();
                $cnt = 1;
                while ($row = $res->fetch_object()) {
                ?>
                    <tbody>
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo $row->name; ?></td>
                            <td><?php echo $row->patient_id; ?></td>
                            <td><?php echo $row->contact_number; ?></td>
                            <td><?php echo $row->address; ?></td>
                            <td><?php echo $row->age; ?> Years</td>
                            <td><?php echo $row->gender; ?></td>
                            <td>
                                <a href="view_vitals.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-success"><i class="mdi mdi-eye"></i> View</a>
                                <a href="updatevitals.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-primary"><i class="mdi mdi-check-box-outline"></i> Update</a>
                            </td>
                        </tr>
                    </tbody>
                <?php $cnt++; } ?>
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

    <footer class="footer">
        <div class="container">
            <p style>&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
