<?php
session_start();
include('assets/inc/config.php');

$aid = $_SESSION['ad_id'];

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM patients WHERE patient_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close(); 

    if ($stmt) {
        $success = "Patient's record deleted successfully.";
    } else {
        $err = "Try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">Manage Patients</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Patient Details</h4>
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
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Consultation</option>
                                                    <option value="OutPatients">VCT</option>
                                                    <option value="InPatients">Dental</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th data-toggle="true">Patient Name</th>
                                                <th data-hide="phone">Contact Number</th>
                                                <th data-hide="phone">Student/Employee Number</th>
                                                <th data-hide="phone">Address</th>
                                                <th data-hide="phone">Actions</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $ret = "SELECT * FROM patients ORDER BY RAND()";
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
                                                    <td><?php echo $row->contact_number; ?></td>
                                                    <td><?php echo $row->student_employee_number; ?></td>
                                                    <td><?php echo $row->address; ?></td>
                                                    <td>
                                                        <a href="his_admin_manage_patient.php?delete=<?php echo $row->patient_id; ?>" class="badge badge-danger"><i class="mdi mdi-trash-can-outline"></i> Delete</a>
                                                        <a href="his_admin_view_single_patient.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-success"><i class="mdi mdi-eye"></i> View</a>
                                                        <a href="his_admin_update_single_patient.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-primary"><i class="mdi mdi-check-box-outline"></i> Update</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        <?php $cnt = $cnt + 1; } ?>
                                        <tfoot>
                                            <tr class="active">
                                                <td colspan="6">
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

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Footable js -->
    <script src="assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <script src="assets/js/pages/foo-tables.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>
