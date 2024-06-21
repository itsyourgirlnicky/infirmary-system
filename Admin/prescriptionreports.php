<?php
session_start();
include('config.php');

// Fetch prescription data with optional medication filter
$medication = '';
$query = "SELECT prescription_id, consultation_id, patient_id, user_id, medication, dosage, duration, created_at FROM prescriptions";

if (isset($_POST['filter_medication']) && !empty($_POST['medication'])) {
    $medication = $mysqli->real_escape_string($_POST['medication']);
    $query .= " WHERE medication LIKE '%$medication%'";
}

$query .= " ORDER BY created_at DESC";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Reports - Prescriptions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<header style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="container text-center">
        <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
    </div>
</header>

<div class="container mt-4">
    <div class="content-page">
        <div class="content">
            <div class="page-title-box">
                <div class="breadcrumb">
                    <div class="breadcrumb-item"><a href="admindashboard.php">Dashboard</a></div>
                    <div class="breadcrumb-item active">System Reports - Prescriptions</div>
                </div>
            </div>

            <div class="card-box">
                <h4 class="header-title">Prescriptions Report</h4>
                
                <!-- Filter Form -->
                <form method="POST" action="" class="form-inline mb-4">
                    <div class="form-group mr-2">
                        <label for="medication" class="mr-2">Filter by Medication:</label>
                        <input type="text" class="form-control" id="medication" name="medication" value="<?php echo htmlspecialchars($medication); ?>">
                    </div>
                    <button type="submit" name="filter_medication" class="btn btn-primary">Filter</button>
                </form>
                
                <div class="table-container mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Prescription ID</th>
                                <th>Consultation ID</th>
                                <th>Patient ID</th>
                                <th>User ID</th>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Duration</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['prescription_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['consultation_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['medication']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dosage']); ?></td>
                                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</div>

<footer style="background-color: #800000; color: #ffc300; padding: 10px; text-align: center; position: fixed; bottom: 0; left: 0; width: 100%;">
    <div class="container">
        <p>&copy; 2024 Catholic University of Eastern Africa</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
