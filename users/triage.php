<?php
session_start();
include('config.php');
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

<footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div style="text-align: center;">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>
</body>
</html>
