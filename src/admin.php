<?php
session_start();
require_once "db/dbconnect.php";

if ($_GET["username"]) {
    $username = $_GET["username"];
    $db->query("UPDATE cars SET rented_by=NULL, return_date=NULL WHERE rented_by='$username'");
}

$table1 = "cars";
$table2 = "users";
$currDate = date('Y-m-d H:i:s');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/admin.css" />
    <link rel="stylesheet" href="styles/navstylesavery.css">
    <title>Admin</title>
</head>
<body>
    <?php include "nav.php"?>
    <main id="admin-wrapper">
        <div id="admin-wrapper-wrapper">
            <h2 id="title">Last Time</h2>
            <h2 id="subtitle"><?=$currDate?></h2>
            <div id="user-results">
                <table id="user-results">
                    <tr>
                        <th>User</th>
                        <th>Rental</th>
                        <th>Return Date</th>
                        <th></th>
                    </tr>
                <?php
            if ($result = $db->query("SELECT * FROM $table1, $table2 WHERE rented_by=username")) {
                while ($row = $result->fetch_assoc()) {
                    $userCheck = ($row["return_date"] < $currDate) ? "overdue-btn" : "return-btn";
            ?>
                <tr class="user-info">
                    <td><?=$row["username"]?></td>
                    <td class="car-info"><?=$row["year"]?> <?=$row["make"]?> <?=$row["model"]?></td>
                    <td><?=$row["return_date"]?></td>
                    <td><button class="<?=$userCheck?>" data-user="<?=$row["username"]?>" onclick="onReturn(event)">Return</button></td>
                </tr>
            <?php
                }
            }
            ?>
                </table>
            </div>
        </div>
    </main>
    <?php include_once "footer.php" ?>
    <script>
        const onReturn = (ev) => window.location.replace(`http://tophat.sunywcc.edu/~asegu51498/admin.php?username=${ev.target.getAttribute("data-user")}`);
    </script>
</body>
</html>
