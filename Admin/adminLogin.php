<?php
session_start();
include('config.php');

if (isset($_POST['admin_login'])) {
    $username = trim($_POST['username']);
    $password = sha1(md5(trim($_POST['password']))); // Double encryption

    // Server-side validation
    if (empty($username) || empty($password)) {
        $err = "All fields are required.";
    } else {
        // SQL to validate user credentials and check role
        $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username=? AND password=? AND role = ?");
        if ($stmt === false) {
            $err = "Failed to prepare the SQL statement.";
        } else {
            $stmt->bind_param('sss', $username, $password,$role);
            $stmt->execute();
            $stmt->bind_result($user_id);
            $rs = $stmt->fetch();

            if ($rs) {
                if ($role === 'admin') {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['role'] = $role;

                    header("location:admindashboard.php");
                } else {
                    $err = "You do not have the required permissions to access this page.";
                }
            } else {
                $err = "Invalid username or password.";
            }
            $stmt->close();
        }
    }
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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./users.css">
</head>

<body class="login_body">
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA This is the admin login</h1>
        </div>
    </header>

    <!-- Login form content -->
    <div class="login_form_wrapper">
        <form id="loginForm" method="post" onsubmit="return loginValidation()">
            <?php 
            if (isset($err)) {
                echo "<div style='color: red;'>$err</div>";
            } 
            ?>
            <label for="username">Username</label><br>
            <input required type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"><br>
            <label for="role">Role</label>
            <select required name="role" id="role">
            <option value="admin">Admin</option>
            <label for="password">Password</label><br>
            <input required type="password" id="password" name="password"><br>
            <button type="submit" name="admin_login">Login</button>
        </form>
    </div>

    <script src="../js/validation.js"></script>
</body>
<footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="text-center">
        <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>

</html>
