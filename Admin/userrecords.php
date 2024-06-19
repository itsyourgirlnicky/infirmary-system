<?php
session_start();
include('config.php');

// Handle Create and Update actions
if (isset($_POST['save_user'])) {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $name = $_POST['name'];

    if ($user_id) {
        // Update user record
        $query = "UPDATE users SET username = ?, email = ?, password = ?, role = ?, name = ? WHERE user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssi', $username, $email, $password, $role, $name, $user_id);
    } else {
        // Create new user record
        $query = "INSERT INTO users (username, email, password, role, name) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss', $username, $email, $password, $role, $name);
    }

    if ($stmt->execute()) {
        header("Location: userrecords.php");
        exit();
    } else {
        $err = "Please try again later";
    }
    $stmt->close();
}

// Handle Delete action
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: userrecords.php");
    exit();
}

// Fetch user records
$query = "SELECT * FROM users";
$result = $mysqli->query($query);

// If editing a user
if (isset($_GET['edit_user'])) {
    $edit_user_id = intval($_GET['edit_user']);
    $edit_query = "SELECT * FROM users WHERE user_id = ?";
    $edit_stmt = $mysqli->prepare($edit_query);
    $edit_stmt->bind_param('i', $edit_user_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    $edit_user = $edit_result->fetch_assoc();
    $edit_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Records</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>

    <div class="container">
        <div class="content-page">
            <div class="content">
                <div class="page-title-box">
                    <div class="breadcrumb">
                        <div class="breadcrumb-item"><a href="admindashboard.php">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="userrecords.php">User Details</a></div>
                        <div class="breadcrumb-item active">System Users</div>
                    </div>
                </div>

                <div class="card-box">
                    <h4 class="header-title">User Records</h4>
                    <a href="adduser.php" class="btn btn-success mb-3">Add New User</a>
                    <div class="table-container mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td>
                                            <a href="updateusers.php?update_user=<?php echo $row['user_id']; ?>" class="badge badge-primary">Update</a>
                                            <a href="userrecords.php?delete_user=<?php echo $row['user_id']; ?>" class="badge badge-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="text-center">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>
</html>
