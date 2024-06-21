<?php
session_start();
include('config.php');

// Handle Delete action if needed
if (isset($_GET['consultation_id'])) {
    $consultation_id = intval($_GET['consultation_id']);
    $query = "DELETE FROM consultations WHERE consultation_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $consultation_id);
    if ($stmt->execute()) {
        // Deletion successful, redirect to refresh the page
        header("Location: consultationreports.php");
        exit();
    } else {
        $err_delete = "Failed to delete consultation. Please try again.";
    }
    $stmt->close();
}

// Define initial query to fetch all consultations
$query = "SELECT consultation_id, patient_id, user_id, visit_date, created_at, consultation_type, notes, diagnosis, treatment_plan FROM consultations";

// Initialize filter variables
$filterUserId = '';
$filterConsultationType = '';

// Check if filters are applied via GET parameters
if (isset($_GET['filter'])) {
    $filterUserId = $_GET['user_id'];
    $filterConsultationType = $_GET['consultation_type'];

    // Prepare WHERE clause based on filter selections
    $whereClause = [];
    $params = [];
    $paramTypes = '';

    if (!empty($filterUserId)) {
        $whereClause[] = "user_id = ?";
        $params[] = $filterUserId;
        $paramTypes .= 's'; // user_id is assumed to be a string
    }
    if (!empty($filterConsultationType)) {
        $whereClause[] = "consultation_type = ?";
        $params[] = $filterConsultationType;
        $paramTypes .= 's'; // consultation_type is assumed to be a string
    }

    // Combine WHERE clauses if filters are applied
    if (!empty($whereClause)) {
        $query .= " WHERE " . implode(" AND ", $whereClause);
    }

    // Prepare statement and execute filtered query
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        // Bind parameters if there are any
        if (!empty($params)) {
            $stmt->bind_param($paramTypes, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $err_filter = "Failed to execute filtered query.";
    }
} else {
    // Execute the initial query without filters
    $result = $mysqli->query($query);
}

// Display error message if deletion or filter fails
if (isset($err_delete)) {
    echo "<p class='text-danger'>$err_delete</p>";
}
if (isset($err_filter)) {
    echo "<p class='text-danger'>$err_filter</p>";
}

// Initialize arrays to store consultations grouped by user_id and created_at date
$consultationsByUser = [];

// Process fetched data and group by user_id and created_at
while ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $date = substr($row['created_at'], 0, 10); // Extract the date part
    $consultationsByUser[$user_id][$date][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Records</title>
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
                        <div class="breadcrumb-item active">Consultation Details</div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Consultation Records</h4>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="" class="form-inline mb-4">
                        <div class="form-group mr-2">
                            <label for="user_id" class="mr-2">Filter by User ID:</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo htmlspecialchars($filterUserId); ?>">
                        </div>
                        <div class="form-group mr-2">
                            <label for="consultation_type" class="mr-2">Filter by Consultation Type:</label>
                            <select class="form-control" id="consultation_type" name="consultation_type">
                                <option value="">All</option>
                                <option value="General" <?php if ($filterConsultationType === 'General') echo 'selected'; ?>>General</option>
                                <option value="VCT" <?php if ($filterConsultationType === 'VCT') echo 'selected'; ?>>VCT</option>
                                <option value="Dental" <?php if ($filterConsultationType === 'Dental') echo 'selected'; ?>>Dental</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <button type="submit" name="filter" value="true" class="btn btn-primary">Filter</button>
                    </form>

                    <!-- Display Records -->
                    <?php foreach ($consultationsByUser as $user_id => $userConsultations) { ?>
                        <h5>Consultations for User ID: <?php echo $user_id; ?></h5>
                        <?php foreach ($userConsultations as $date => $consultations) { ?>
                            <h6>Date: <?php echo htmlspecialchars($date); ?></h6>
                            <div class="table-container mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Consultation ID</th>
                                            <th>Patient ID</th>
                                            <th>Registered By (User ID)</th>
                                            <th>Visit Date</th>
                                            <th>Created At</th>
                                            <th>Consultation Type</th>
                                            <th>Notes</th>
                                            <th>Diagnosis</th>
                                            <th>Treatment Plan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($consultations as $consultation) { ?>
                                            <tr>
                                                <td><?php echo $consultation['consultation_id']; ?></td>
                                                <td><?php echo $consultation['patient_id']; ?></td>
                                                <td><?php echo $consultation['user_id']; ?></td>
                                                <td><?php echo $consultation['visit_date']; ?></td>
                                                <td><?php echo $consultation['created_at']; ?></td>
                                                <td><?php echo $consultation['consultation_type']; ?></td>
                                                <td><?php echo htmlspecialchars($consultation['notes']); ?></td>
                                                <td><?php echo htmlspecialchars($consultation['diagnosis']); ?></td>
                                                <td><?php echo htmlspecialchars($consultation['treatment_plan']); ?></td>
                                                <td><a href="?consultation_id=<?php echo $consultation['consultation_id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
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
