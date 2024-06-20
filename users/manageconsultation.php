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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #800000;
            padding: 10px;
        }
        .navbar h1 {
            margin: 0;
            font-size: 24px;
            color: #ffc300;
        }
     
        .footer {
            background-color: #800000;
            color: #ffc300;
            padding: 10px;
            text-align: center;
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
            <div class="container-fluid">
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

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Patient Records</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Patient Name</th>
                                            <th>Patient ID</th>
                                            <th>Consultation Updates</th>
                                            <th>Lab Report</th>
                                            <th>Prescription</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Select patients who have consultation records
                                    $query = "SELECT p.patient_id, p.name, c.consultation_id 
                                              FROM patients p 
                                              INNER JOIN consultations c ON p.patient_id = c.patient_id 
                                              ORDER BY p.created_at ASC";
                                    $stmt = $mysqli->prepare($query);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                        $consultation_url = "updateconsultation.php?consultation_id=" . htmlspecialchars($row->consultation_id);
                                        $labreport_url = "viewlabreport.php?patient_id=" . htmlspecialchars($row->patient_id);
                                        $prescription_url = "prescription.php?patient_id=" . htmlspecialchars($row->patient_id);
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo htmlspecialchars($row->name); ?></td>
                                            <td><?php echo htmlspecialchars($row->patient_id); ?></td>
                                            <td><a href="<?php echo $consultation_url; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i> Consultation Updates</a></td>
                                            <td><a href="<?php echo $labreport_url; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i> Lab Report</a></td>
                                            <td><a href="<?php echo $prescription_url; ?>" class="badge badge-success"><i class="mdi mdi-beaker"></i> Prescription</a></td>
                                        </tr>
                                    <?php $cnt++; } ?>
                                    </tbody>
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
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Catholic University of Eastern Africa</p>
            </div>
        </footer>
    </div>
</body>
</html>
