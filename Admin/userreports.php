<?php
session_start();
include('config.php');

// Fetch user records with filters
$roleFilter = '';
$dateFilter = '';

if (isset($_POST['filter'])) {
    if (!empty($_POST['role'])) {
        $roleFilter = $mysqli->real_escape_string($_POST['role']);
    }
    if (!empty($_POST['date'])) {
        $dateFilter = $mysqli->real_escape_string($_POST['date']);
    }
}

$query = "SELECT * FROM users WHERE 1=1";
if ($roleFilter) {
    $query .= " AND role LIKE '%$roleFilter%'";
}
if ($dateFilter) {
    $query .= " AND DATE(created_at) = '$dateFilter'";
}
$query .= " ORDER BY created_at DESC";
$result = $mysqli->query($query);
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Fetch data for reports
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM users";
$totalUsersResult = $mysqli->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'];

$rolesQuery = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
$rolesResult = $mysqli->query($rolesQuery);
$rolesData = [];
while ($row = $rolesResult->fetch_assoc()) {
    $rolesData[] = $row;
}

$usersByTimeQuery = "SELECT DATE(created_at) as date, COUNT(*) as count FROM users GROUP BY DATE(created_at) ORDER BY date DESC";
$usersByTimeResult = $mysqli->query($usersByTimeQuery);
$usersByTimeData = [];
while ($row = $usersByTimeResult->fetch_assoc()) {
    $usersByTimeData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reports</title>
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
                        <div class="breadcrumb-item active">User Reports</div>
                    </div>
                </div>

                <div class="card-box">
                    <h4 class="header-title">User Reports</h4>
                    <div class="report-summary mb-4">
                        <h5>Total Users: <?php echo $totalUsers; ?></h5>
                    </div>

                    <!-- Filter Form -->
                    <form method="POST" action="" class="form-inline mb-4">
                        <div class="form-group mr-2">
                            <label for="role" class="mr-2">Filter by Role:</label>
                            <input type="text" class="form-control" id="role" name="role" value="<?php echo htmlspecialchars($roleFilter); ?>">
                        </div>
                        <div class="form-group mr-2">
                            <label for="date" class="mr-2">Filter by Date:</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
                        </div>
                        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                    </form>
                    
                    <h5>Users by Role:</h5>
                    <?php foreach ($rolesData as $role) { ?>
                        <h6><?php echo htmlspecialchars($role['role']) . ": " . $role['count']; ?></h6>
                        <div class="table-container mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user) {
                                        if ($user['role'] == $role['role']) { ?>
                                            <tr>
                                                <td><?php echo $user['user_id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                            </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div> 
                    <?php } ?>

                    <h5>Users by Creation Date:</h5>
                    <?php foreach ($usersByTimeData as $data) { ?>
                        <h6><?php echo htmlspecialchars($data['date']) . ": " . $data['count']; ?></h6>
                        <div class="table-container mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user) {
                                        if (substr($user['created_at'], 0, 10) == $data['date']) { ?>
                                            <tr>
                                                <td><?php echo $user['user_id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div> 
                    <?php } ?>
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
