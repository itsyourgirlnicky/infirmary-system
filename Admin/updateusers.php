<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize user array
$user = [
    'username' => '',
    'name' => '',
    'email' => '',
    'role' => '',
    'password' => ''
];

// Fetch user details if editing
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        $err = "User not found.";
    }
}

// Handle update action
if (isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Server-side validation
    if (empty($username) || empty($name) || empty($email) || empty($role)) {
        $err = "All fields are required except the password fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Invalid email format.";
    } elseif (!empty($password) && $password !== $confirm_password) {
        $err = "Passwords do not match.";
    } else {
        // Use the current password if no new password is provided
        $hashed_password = !empty($password) ? sha1(md5($password)) : $user['password'];

        // Update the user details in the database
        $stmt = $mysqli->prepare("UPDATE users SET username = ?, name = ?, email = ?, role = ?, password = ? WHERE id = ?");
        $stmt->bind_param('sssssi', $username, $name, $email, $role, $hashed_password, $user_id);

        if ($stmt->execute()) {
            $success = "User updated successfully.";
            header("Location: userrecords.php");
            exit();
        } else {
            $err = "Please try again.";
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
    <title>Update User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="path/to/your/users.css">
</head>

<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center" style="text-align: center;">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h2>Update User</h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($err)) { echo "<div class='alert alert-danger'>$err</div>"; } ?>
                        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?php echo isset($user['id']) ? $user['id'] : ''; ?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input required type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input required type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input required type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select required name="role" id="role" class="form-control">
                                    <option value="doctor" <?php if ($user['role'] == 'doctor') echo 'selected'; ?>>Doctor</option>
                                    <option value="nurse" <?php if ($user['role'] == 'nurse') echo 'selected'; ?>>Nurse</option>
                                    <option value="laboratory_technician" <?php if ($user['role'] == 'laboratory_technician') echo 'selected'; ?>>Laboratory Technician</option>
                                    <option value="pharmacist" <?php if ($user['role'] == 'pharmacist') echo 'selected'; ?>>Pharmacist</option>
                                    <option value="receptionist" <?php if ($user['role'] == 'receptionist') echo 'selected'; ?>>Receptionist</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password (Leave blank to keep current password)</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                            </div>
                            <button name="update_user" type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div style="text-align: center;">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>

    <script src="path/to/your/validation.js"></script>
</body>

</html>
