<?php
session_start();
include('config.php');

if (isset($_POST['add_patient'])) {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $contact_number = trim($_POST['contact_number']);
    $student_employee_number = trim($_POST['student_employee_number']);
    $address = trim($_POST['address']);
    
    // Get user ID from the session
    if (!isset($_SESSION['user_id'])) {
        $err = "User ID not found. Please log in again.";
    } else {
        $user_id = $_SESSION['user_id'];

        // Server-side validation
        if (empty($name) || empty($age) || empty($gender) || empty($contact_number) || empty($student_employee_number) || empty($address)) {
            $err = "All fields are required.";
        } elseif (!is_numeric($age) || $age <= 0) {
            $err = "Invalid age.";
        } elseif (!is_numeric($contact_number)) {
            $err = "Invalid contact number.";
        } else {
            // Insert the new patient into the database
            $stmt = $mysqli->prepare("INSERT INTO patients (user_id, name, age, gender, contact_number, student_employee_number, address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param('ssissss', $user_id, $name, $age, $gender, $contact_number, $student_employee_number, $address);
            if ($stmt->execute()) {
                $success = "Patient added successfully";
                header("Location: dashboard.php");
                exit();
            } else {
                $err = "Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="C:\xampp\htdocs\Final Year Project\users\addpatient.css">
</head>

<body>
<header style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="container text-center" style="text-align: center;">
        <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
    </div>
</header>
<div class="container">
    <!-- Start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patient Registration</a></li>
                        <li class="breadcrumb-item active">Add Patient</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Patient Details</h4>
            </div>
        </div>
    </div>
    <!-- End page title -->

    <!-- Form row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Fill all fields</h4>
                    <!-- Add Patient Form -->
                    <form method="post" onsubmit="return validateForm()">
                        <?php if (isset($err)) { echo "<div style='color: red;'>$err</div>"; } ?>
                        <?php if (isset($success)) { echo "<div style='color: green;'>$success</div>"; } ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">Name</label>
                                <input type="text" required="required" name="name" class="form-control" id="name" placeholder="Patient's Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="age" class="col-form-label">Age</label>
                                <input required="required" type="number" name="age" class="form-control" id="age" placeholder="Patient's Age">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="gender" class="col-form-label">Gender</label>
                                <select id="gender" required="required" name="gender" class="form-control">
                                    <option value="">Choose</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact_number" class="col-form-label">Mobile Number</label>
                                <input required="required" type="text" name="contact_number" class="form-control" id="contact_number">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="student_employee_number" class="col-form-label">Student/Employee Number</label>
                                <input required="required" type="text" name="student_employee_number" class="form-control" id="student_employee_number">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address" class="col-form-label">Address</label>
                                <input required="required" type="text" class="form-control" name="address" id="address" placeholder="Patient's Address">
                            </div>
                        </div>

                        <button type="submit" name="add_patient" class="btn btn-primary">Add Patient</button>
                    </form>
                    <!-- End Patient Form -->
                </div> 
            </div> 
        </div> 
    </div>
    <!-- end row -->
</div> <!-- container -->

<footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div style="text-align: center;">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>

<script src=".\validation.js"></script>

</body>
</html>
