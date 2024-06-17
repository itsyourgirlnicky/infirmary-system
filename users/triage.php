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
                <h4 class="header-title">Patient Records</h4>
                <div class="table-container">
                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th data-toggle="true">Patient Name</th>
                                <th data-hide="phone">Patient ID</th>
                                <th data-hide="phone">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ret = "SELECT p.* 
                                    FROM patients p
                                    LEFT JOIN vitals v ON p.patient_id = v.patient_id
                                    WHERE v.patient_id IS NULL
                                    ORDER BY p.created_at ASC";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $cnt = 1;
                            while ($row = $res->fetch_object()) {
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlspecialchars($row->name); ?></td>
                                    <td><?php echo htmlspecialchars($row->patient_id); ?></td>
                                    <td>
                                        <a href="capturevitals.php?patient_id=<?php echo $row->patient_id; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i> Capture Vitals</a>
                                    </td>
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
                </div> 
            </div>
        </div>
    </div>
</div>

<footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div style="text-align: center;">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>
</body>
</html>
