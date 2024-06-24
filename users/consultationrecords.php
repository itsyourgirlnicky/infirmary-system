<?php
session_start();
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Records</title>
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

        .table td {
            vertical-align: middle;
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
                                    <li class="breadcrumb-item active">Consultation Records</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Consultation Records</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Patient Name</th>
                                            <th>Patient ID</th>
                                            <th>Visit Date</th>
                                            <th>Lab Reports</th>
                                            <th>Consultation Notes</th>
                                            <th>Prescriptions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $patient_id = $_GET['patient_id'];
                                        $ret = "SELECT c.*, p.name FROM consultations c 
                                        INNER JOIN patients p ON c.patient_id = p.patient_id 
                                        WHERE c.patient_id = $patient_id 
                                        ORDER BY c.visit_date ASC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {

                                            $labreport_url = "viewlabreport.php?patient_id=" . htmlspecialchars($row->patient_id);
                                            $notes_url = "viewconsultationnotes.php?patient_id=" . htmlspecialchars($row->patient_id);
                                            $prescription_url = "viewconsultationnotes.php?patient_id=" . htmlspecialchars($row->patient_id);
                                        ?>

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo htmlspecialchars($row->name); ?></td>
                                                <td><?php echo htmlspecialchars($row->patient_id); ?></td>
                                                <td><?php echo htmlspecialchars($row->visit_date); ?></td>
                                                <td><a href="<?php echo $labreport_url; ?>" class="btn btn-primary btn-sm">View</a></td>
                                                <td><a href="<?php echo $notes_url; ?>" class="btn btn-primary btn-sm">View</a></td>
                                                <td><a href="<?php echo $prescription_url; ?>" class="btn btn-primary btn-sm">View</a></td>
                                            </tr>
                                        <?php $cnt++;
                                        } ?>
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
        </div>

        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Catholic University of Eastern Africa</p>
            </div>
        </footer>
    </div>
</body>

</html>