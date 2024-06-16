<?php
session_start();
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Consultation</title>
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
                                    <li class="breadcrumb-item"><a href="consultation.php">Consultation</a></li>
                                    <li class="breadcrumb-item active">Manage Consultation</li>
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
                                                <td><a href=".php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i> Consultation Updates</a></td>
                                                <td><a href="labreport.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i>lab Report</a></td>
                                                <td><a href="prescription.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i>Prescription</a></td>
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

    </div
