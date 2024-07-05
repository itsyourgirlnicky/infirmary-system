<?php
session_start();
include('config.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body id="content_wrapper">
    <div style="position: fixed; z-index: 1; display: flex; flex-direction: row; width: 100%; justify-content: space-between;">
        <div style="height: 100vh;">
            <?php include "components/menu.php"; ?>
        </div>
        <div>
            <?php include "components/navbar.php"; ?>
        </div>
    </div>

    <div style="position: fixed; bottom: 5rem; left: 30rem; right: 0;">
        <div class="dashboard_container">
            <div class="dashboard_image_wrapper">
                <img src="../images/cuea.jpg" alt="Catholic University of Eastern Africa">
                <p>Catholic University of Eastern Africa</p>
                <p><span>Consecrate them in the truth</span></p>
            </div>
        </div>
    </div>
</body>

</html>
