<?php
session_start();
include('config.php'); 

// Fetch existing billing records
$query = "SELECT * FROM billing ORDER BY billing_date DESC";
$result = $mysqli->query($query);

// Initialize variables for modal form
$user_id = '';
$patient_id = '';
$billing_date = date('Y-m-d'); // Default to current date
$billing_type = '';
$amount = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $user_id = htmlspecialchars($_POST['user_id']);
    $patient_id = htmlspecialchars($_POST['patient_id']);
    $billing_date = htmlspecialchars($_POST['billing_date']);
    $billing_type = htmlspecialchars($_POST['billing_type']);
    $amount = htmlspecialchars($_POST['amount']);

    // Insert into database
    $insert_query = "INSERT INTO billing (user_id, patient_id, billing_date, billing_type, amount) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insert_query);
    $stmt->bind_param('siisd', $user_id, $patient_id, $billing_date, $billing_type, $amount);

    if ($stmt->execute()) {
        // Success message
        $success_message = "Billing record added successfully.";
        // Clear form fields after successful submission
        $user_id = '';
        $patient_id = '';
        $billing_date = date('Y-m-d');
        $billing_type = '';
        $amount = '';
        // Refresh page to update billing records
        header("Refresh:0");
    } else {
        // Error message
        $error_message = "Failed to add billing record. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Dashboard</title>
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
        <h2 class="mb-4">Billing Dashboard</h2>
        
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addBillingModal">
            Add Billing
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addBillingModal" tabindex="-1" role="dialog" aria-labelledby="addBillingModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBillingModalLabel">Add Billing</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="modal-body">
                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($success_message)): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $success_message; ?>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="patient_id">Patient ID:</label>
                                <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?php echo $patient_id; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="billing_date">Billing Date:</label>
                                <input type="date" class="form-control" id="billing_date" name="billing_date" value="<?php echo $billing_date; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="billing_type">Billing Type:</label>
                                <select class="form-control" id="billing_type" name="billing_type" required>
                                    <option value="">Select Billing Type</option>
                                    <option value="Dental" <?php if ($billing_type === 'Dental') echo 'selected'; ?>>Dental</option>
                                    <option value="Medication" <?php if ($billing_type === 'Medication') echo 'selected'; ?>>Medication</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Display billing records -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Patient ID</th>
                        <th>Billing Date</th>
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
                        <td><?php echo htmlspecialchars($row['billing_date']); ?></td>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
  <div style="text-align: center;">
      <p style="font-size: 14px;">&copy; 2024 Catholic University of Eastern Africa</p>
  </div>
</footer>


<script src=".\validation.js"></script>
</body>
</html>
