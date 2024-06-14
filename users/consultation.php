<?php
session_start();
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation</title>
    <link rel="stylesheet" href="managepatients.css">
</head>

<body>
    <header class="navbar">
        <div class="container text-center">
            <h1 style="margin: 0; font-size: 24px; color: #ffc300;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Consultation</a></li>
                                    <li class="breadcrumb-item active">Records</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Patient Records</h4>
                            <div class="mb-2">
                                <div class="row">
                                    <div class="col-12 text-sm-center form-inline">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th data-toggle="true">Patient Name</th>
                                            <th data-hide="phone">Patient ID</th>
                                            <th data-hide="phone">Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $ret = "SELECT p.*
                                            FROM patients p
                                            INNER JOIN vitals v ON p.patient_id = v.patient_id
                                            WHERE NOT EXISTS (
                                                SELECT 1 FROM labrequests lr WHERE lr.patient_id = p.patient_id
                                            ) AND NOT EXISTS (
                                                SELECT 1 FROM prescriptions pr WHERE pr.patient_id = p.patient_id
                                            )
                                            ORDER BY p.created_at ASC";
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
                                                <td><a href="addconsultation.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i> Consultation Notes</a></td>
                                                <td><a href="labrequest.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-primary"><i class="mdi mdi-flask-outline"></i> Lab Request</a></td>
                                                <td><a href="prescription.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-primary"><i class="mdi mdi-flask-outline"></i> Prescription</a></td>
                                            </tr>
                                        </tbody>
                                    <?php $cnt = $cnt + 1; } ?>
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
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <footer class="footer">
            <div class="container">
                <p style="margin: 0;">&copy; 2024 Catholic University of Eastern Africa</p>
            </div>
        </footer>

    </div>
</body>
</html>
