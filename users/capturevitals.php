<?php
session_start();
include('config.php');

if (isset($_POST['add_vitals'])) {
    $patient_id = $_POST['patient_id'];
    $user_id = $_SESSION['user_id']; 
    $visit_date = date('Y-m-d');
    $temperature = $_POST['temperature'];
    $blood_pressure = $_POST['blood_pressure'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    // SQL to insert captured values
    $query = "INSERT INTO vitals (patient_id, user_id, visit_date, temperature, blood_pressure, weight, height) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('iisssss', $patient_id, $user_id, $visit_date, $temperature, $blood_pressure, $weight, $height);
    $stmt->execute();

    // Declare a variable which will be passed to the alert function
    if ($stmt) {
        $success = "Patient Vitals Added";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>
<!--End Server Side-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture Vitals</title>
    <link rel="stylesheet" href="addpatient.css">
</head>
<body>
    <?php
    $patient_id = $_GET['patient_id'];
    $ret = "SELECT * FROM patients WHERE patient_id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_object()) {
    ?>
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
                                        <li class="breadcrumb-item"><a href="triage.php">Triage</a></li>
                                        <li class="breadcrumb-item active">Capture Vitals</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Capture <?php echo $row->name; ?>'s Vitals</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>
                                    <!--Add Patient Form-->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="patientName" class="col-form-label">Patient Name</label>
                                                <input type="text" required="required" readonly name="patient_name" value="<?php echo $row->name; ?>" class="form-control" id="patientName">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="patientAilment" class="col-form-label">Patient ID</label>
                                                <input type="text" required="required" readonly name="patient_id" value="<?php echo $row->patient_id; ?>" class="form-control" id="patientID">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="temperature" class="col-form-label">Temperature (°C)</label>
                                                <input type="text" required="required" name="temperature" class="form-control" id="temperature" placeholder="°C">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="blood_pressure" class="col-form-label">Blood Pressure (mmHg)</label>
                                                <input required="required" type="text" name="blood_pressure" class="form-control" id="blood_pressure" placeholder="mmHg">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="weight" class="col-form-label">Weight (kg)</label>
                                                <input required="required" type="text" name="weight" class="form-control" id="weight" placeholder="kg">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="height" class="col-form-label">Height (cm)</label>
                                                <input required="required" type="text" name="height" class="form-control" id="height" placeholder="cm">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_vitals" class="ladda-button btn btn-success" data-style="expand-right">Add Vitals</button>
                                    </form>
                                    <!--End Patient Form-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </div> <!-- container -->
            </div> <!-- content -->
        </div>
    <?php } ?>
    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
</body>

</html>
