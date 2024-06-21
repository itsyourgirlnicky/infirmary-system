<?php
session_start();
include('config.php');

// Fetch patient records from the database
$query = "SELECT patient_id, name, gender, created_at, user_id FROM patients ORDER BY created_at DESC";
$result = $mysqli->query($query);


// Classify patients by gender and creation date
$patientsByGender = [];
$patientsByDate = [];
while ($row = $result->fetch_assoc()) {
    $patientsByGender[$row['gender']][] = $row;
    $date = substr($row['created_at'], 0, 10); // Extract the date part
    $patientsByDate[$date][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Records</title>
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
                        <div class="breadcrumb-item"><a href="registrationrecords.php">Registration Records</a></div>
                        <div class="breadcrumb-item active">Patient Details</div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Patient Records</h4>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="" class="form-inline mb-4">
                        <div class="form-group mr-2">
                            <label for="gender" class="mr-2">Filter by Gender:</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="">All Genders</option>
                                <option value="Male" <?php if (isset($_GET['gender']) && $_GET['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if (isset($_GET['gender']) && $_GET['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label for="date" class="mr-2">Filter by Date:</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
                        </div>
                        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                    </form>

                    <!-- Display Filtered Patients -->
                    <?php
                    $filteredPatients = $patientsByGender; // Start with all patients by gender

                    // Apply filters if set
                    if (isset($_GET['gender']) && !empty($_GET['gender'])) {
                        $filteredPatients = [$patientsByGender[$_GET['gender']]]; // Filter by gender
                    }

                    if (isset($_GET['date']) && !empty($_GET['date'])) {
                        $filteredPatients = [$patientsByDate[$_GET['date']]]; // Filter by date
                    }

                    // Display filtered results
                    foreach ($filteredPatients as $key => $patients) {
                        if ($key == 0) {
                            echo '<h5>Patients by Gender:</h5>';
                        } else {
                            echo '<h5>Patients by Creation Date:</h5>';
                        }
                        foreach ($patients as $patient) {
                            echo '
                                <h6>' . htmlspecialchars($key == 0 ? $patient['gender'] : substr($patient['created_at'], 0, 10)) . ':</h6>
                                <div class="table-container mt-4">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Patient ID</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Created At</th>
                                                <th>Registered By (UserID)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>' . $patient['patient_id'] . '</td>
                                                <td>' . htmlspecialchars($patient['name']) . '</td>
                                                <td>' . htmlspecialchars($patient['gender']) . '</td>
                                                <td>' . htmlspecialchars($patient['created_at']) . '</td>
                                                <td>' . htmlspecialchars($patient['user_id']) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>';
                        }
                    }
                    ?>
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
