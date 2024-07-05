<?php
session_start();
include('config.php');

// Initialize variables
$filter_billing_type = '';

// Check for filters and build the query
$query = "SELECT * FROM billing WHERE 1=1";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['filter_billing_type'])) {
        $filter_billing_type = htmlspecialchars($_POST['filter_billing_type']);
        $query .= " AND billing_type = '$filter_billing_type'";
    }

}

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Report</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center" style="text-align: center;">
            <h1 style="font-size: 24px;">CATHOLIC UNIVERSITY OF EASTERN AFRICA</h1>
        </div>
    </header>
    <div class="container mt-4">
        <div class="breadcrumb">
            <div class="breadcrumb-item"><a href="admindashboard.php">Dashboard</a></div>
            <div class="breadcrumb-item active">Billing Report</div>
        </div>
        <h2 class="mb-4">Generate Billing Report</h2>

        <!-- Filter Form -->
        <form method="POST" action="" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="filter_billing_type">Billing Type:</label>
                    <select class="form-control" id="filter_billing_type" name="filter_billing_type">
                        <option value="">Select Billing Type</option>
                        <option value="Dental" <?php if ($filter_billing_type == 'Dental') echo 'selected'; ?>>Dental</option>
                        <option value="Medication" <?php if ($filter_billing_type == 'Medication') echo 'selected'; ?>>Medication</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Display billing records -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Patient ID</th>
                        <th>Billing Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cnt = 1;
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['billing_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                        </tr>
                    <?php
                        $cnt++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div style="text-align: center;">
            <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
        </div>
    </footer>
</body>

</html>
