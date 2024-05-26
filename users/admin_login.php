<?php
session_start();
include('config.php');
if (isset($_POST['user_login'])) {
    $username = $_POST['username'];
    $password = sha1(md5($_POST['password'])); //double encryption

    // SQL to insert captured values
    $stmt = $mysqli->prepare("SELECT username, password, user_id FROM users WHERE username=? AND password=?");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->bind_result($username, $password, $user_id);
    $rs = $stmt->fetch();

    if ($rs) {
        $success = "Successful!";
        header("location:admin_dashboard.php");
        exit();
    } else {
        $err = "Please try again";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS-->
    <link rel="stylesheet" href="./users.css">
</head>

<body class="login_body">
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <!-- Login form content here -->
    <div class="login_form_wrapper">
        <form id="loginForm" method="post" onsubmit="return loginValidation()">
            <label for="Username">Username</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br>
            <button type="submit" name="user_login">Login</button>
        </form>
        <div class="options">
            <p><a href="register.php">Register</a> | <a href="forgot_password.php">Forgot your password?</a></p>
        </div>
    </div>

    </div>
    <script src="../js/validation.js"></script>
</body>
<footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="text-center">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>

</html>