<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="width: 100%;">
    <div style="display: flex; flex-direction: row;">
        <div style="width: 17%;">
            <?php include "components/sidenav.php"; ?>
        </div>
        <div style="width: 83%;">
            <?php include "components/navbar.php" ?>

        </div>
    </div>

    <div style="position: fixed;">
    </div>
</body>

</html>