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

        /* General Reset and Styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .page-title-box {
            margin-bottom: 20px;
        }

        .breadcrumb {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            padding: 0 8px;
            color: #6c757d;
        }

        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        /* Card Box */
        .card-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header-title {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .table-container {
            max-height: 500px;
            overflow-y: auto;
        }

        .table th, .table td {
            white-space: nowrap;
            text-align: center;
        }

        .content-page {
            flex: 1;
            margin: 20px;
            padding-bottom: 60px;
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
                            <h4 class="header-title">Laboratory Requests for Patient ID: <?php echo htmlspecialchars($patient_id); ?></h4>
                            <div class="mb-2">
                                <div class="row">
                                    <div class="col-12 text-sm-center form-inline">
                                    </div>
                                </div>
                            </div>

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
                                    $ret = "SELECT l.*
                                            FROM labrequests l
                                            WHERE l.patient_id = ?
                                            ORDER BY l.request_date ASC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('s', $patient_id);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo htmlspecialchars($row->test_name); ?></td>
                                                <td><?php echo htmlspecialchars($row->request_date); ?></td>
                                                <td><?php echo htmlspecialchars($row->status); ?></td>
                                            </tr>
                                    <?php $cnt = $cnt + 1; } ?>
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
                        </div> <!-- end card-box -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Catholic University of Eastern Africa</p>
            </div>
        </footer>

    </div>
</body>
</html>
