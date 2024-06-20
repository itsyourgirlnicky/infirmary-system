<?php
session_start();
include('config.php');
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
                                    </div>
                                </div>
                            </div>

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
    <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Catholic University of Eastern Africa</p>
            </div>
        </footer>
</body>
</html>
