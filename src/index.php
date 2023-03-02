<?php
require_once("db/dbconnect.php");

session_start();

if (isLoggedIn()){
    header ("Location: catalog.php");
} else {

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/splashstyles.css">
    <title>Document</title>
</head>
<body>
    <div id="splash-wrapper">
        <img id="logo" src="img/rocketrentalslogo.svg" alt="rocket rentals logo" width="650px">
        <div id="splash-wrapper-links">
            <a href="catalog.php">Catalog</a>
            <a href="auth/login.php">Login</a>
            <a href="auth/register.php">Register</a>
        </div>
        <p>Welcome to the world's most diverse and open marketplace for automobile rentals!</p>
        <?php include_once "footer.php" ?>
    </div> 
</body>
</html>

<?php } ?>