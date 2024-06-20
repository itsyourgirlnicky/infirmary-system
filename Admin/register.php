<?php
session_start();
include('config.php');

if (isset($_POST['user_register'])) {
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Server-side validation
    if (empty($username) || empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $err = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $err = "Passwords do not match.";
    } else {
        // Hash the password using double encryption
        $hashed_password = sha1(md5($password));

        // Check if the username or email already exists
        $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $err = "Username or email already exists.";
        } else {
            // Generate user ID based on role
            if ($role == 'admin') {
                $user_id = 'Admin_' . $username;
            } else {
                $user_id = ucfirst($role) . '_' . $username;
            }

            // Insert the new user into the database
            $stmt = $mysqli->prepare("INSERT INTO users (user_id, username, password, role, name, email) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $user_id, $username, $hashed_password, $role, $name, $email);
            if ($stmt->execute()) {
                $success = "User registered successfully";
                header("location:login.php");
                exit();
            } else {
                $err = "Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
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

    <div class="register_container_wrapper">
        <!-- Register form  -->
        <div class="register_form_container">
            <h2>Register</h2>
            <form method="post" onsubmit="return registerValidation()">
                <label for="username">Username</label>
                <input required type="text" name="username" id="username">
                <label for="name">Full Name</label>
                <input required type="text" name="name" id="name">
                <label for="email">Email</label>
                <input required type="email" name="email" id="email">
                <label for="password">Password</label>
                <input required type="password" name="password" id="password">
                <label for="confirm_password">Confirm Password</label>
                <input required type="password" name="confirm_password" id="confirm_password">
                <label for="role">Role</label>
                <select required name="role" id="role">
                    <option value="doctor">Doctor</option>
                    <option value="nurse">Nurse</option>
                    <option value="laboratory_technician">Laboratory Technician</option>
                    <option value="pharmacist">Pharmacist</option>
                    <option value="receptionist">Receptionist</option>
                    <option value="admin">Admin</option>
                </select>
                <button name="user_register" type="submit">Create</button>
            </form>
            <?php if (isset($err)): ?>
                <div class="alert alert-danger"><?php echo $err; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div style="text-align: center;">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

    <script src="../js/validation.js"></script>
</body>
</html>
