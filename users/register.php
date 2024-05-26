<?php
session_start();
include('config.php');
if (isset($_POST['user_register'])) {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_number = $_POST['phone_number'];
    $password = sha1(md5($_POST['password']));
    $role = $_POST['role'];

    // SQL to insert captured values
    $query = "INSERT INTO users (username, password, role, name, contact_number, email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssss', $username, $password, $role, $name, $contact_number, $email);

    if ($stmt->execute()) {
        $success = "User registered successfully";
        header("location:login.php");
        exit();
    } else {
        $err = "Please try again";
    }
    $stmt->close();
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./users.css">
</head>

<body class="login_body">
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center" style="text-align: center;">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>


    <body class="register_container_wrapper">
        <!-- Register form  -->
        <div class="register_form_container">
            <h2>Register</h2>
            <form method="post" onsubmit="return registerValidation()">
                <label for="username">Username</label>
                <input required type="text" name="username" id="username">
                <label for="name">Full Name</label>
                <input required type="text" name="name" id="name">
                <label for="email">Email</label>
                <input required type="text" name="email" id="email">
                <label for="phone_number">Mobile</label>
                <input requiredtype="text" name="phone_number" id="phone_number">
                <label for="password">Password</label>
                <input required type="password" name="password" id="password">
                <label for="role">Role</label>
                <select required name="role" id="role">
                    <option value="doctor">Doctor</option>
                    <option value="nurse">Nurse</option>
                    <option value="laboratory_technician">Laboratory Technician</option>
                    <option value="pharmacist">Pharmacist</option>
                    <option value="receptionist">Receptionist</option>
                </select>
                <button name="user_register" type="submit">Create</button>
            </form>
        </div>
        <script src="../js/validation.js"></script>
    </body>
    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div style="text-align: center;">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

</html>