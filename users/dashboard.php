<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body style="width: 100%;" class="body_dashboard">
    <div style="display: flex; flex-direction: row; width: 100%;">
        <div>
            <?php include "components/sidenav.php"; ?>
        </div>
        <div style="width: 100%; z-index: 1;">
            <?php include "components/navbar.php" ?>

        </div>
    </div>

    <div style="position: fixed;">
    </div>
</body>

</html>