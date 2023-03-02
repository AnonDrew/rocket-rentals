<?php
require_once("db/dbconnect.php");
session_start();

global $cars_table;
global $users_table;
global $username;
$cars_table = 'cars';
$users_table = 'users';
if (isLoggedIn()) { $username = $_SESSION['username']; }

global $car_id_query;
 $car_id_query  = "SELECT * FROM cars WHERE rented_by='$username' ";

?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head> <h></h>
    <meta charset="utf-8">
    <title> Rocket Rentals </title>
    <link rel="stylesheet" href="styles/garage_style.css">
    <link rel="stylesheet" href="styles/navstylesavery.css"/>

    <script src="car_page.php" charset="utf-8"></script>
</head>
<?php
if (!isset($home_url)){
    $home_url = "index.php";
}

if (!isset($logo_url)){
    $logo_url = "rocketrentalslogo.svg";
}

?>
<?php include "nav.php"?>


<body>

    <div class="product-car">
        <h1>
            <img class ="garage_icon" src="https://www.pngall.com/wp-content/uploads/12/Garage-Warehouse-PNG-Photos.png" alt = "garage">

            Your Garage: </h1>
        <br>
    </div>
        <?php
        if (isLoggedIn()) {
            /*
            //first delete all expired entries from rentals table
            date_default_timezone_set('America/New_York');
            $date = date('Y-m-d H:i:s', time());
            if ($rented_cid = $db->query($car_id_query) ) {
                if (!empty($rented_cid) && $rented_cid->num_rows > 0) {
                    //mark all expired cars' "is rented" to NULL
                    while ($row0 = $rented_cid->fetch_assoc() ) {
                        if ($row0['return_car_time'] <= $date) {
                            $expired_car_id = $row0['rented_car_id'];
                            $db->query("UPDATE cars SET is_rented = NULL WHERE cid='$expired_car_id'");
                        }
                    }
                    $db->query("DELETE FROM rentals WHERE return_car_time<= '$date'");
                }
            }
            */
            //now display remaining rented cars
            if ($rented_cid = $db->query($car_id_query) ) {
                if (!empty($rented_cid) && $rented_cid->num_rows > 0) {
                    echo '<div class="garage_results">';
                    $rented_class = "rented_car";
                    while ($row = $rented_cid->fetch_assoc() ) {   //Creates a loop to loop through results
                        global $cid;
                        global $rented_car_info_query;
                        global $expiration;
                        $cid = $row['cid'];
                        $rented_car_info_query = "SELECT * FROM cars WHERE cid= '$cid' ";
                        $expiration = $row['return_date'];
                        if ( $rented_car_info = $db->query($rented_car_info_query ) ) {
                            if (!empty($rented_car_info) && $rented_car_info->num_rows > 0) {
                                while ($row2 = $rented_car_info->fetch_assoc() ) {
                                    global $carName;
                                    global $carImg;
                                    global $expiration;
                                    global $MPG;
                                    global $drive;
                                    global $cyl;

                                    $carName = $row2['year'] . " " . $row2['make'] ." " . $row2['model'];
                                    $carImg = $row2['image_url'];
                                    $MPG = $row2['combination_mpg'];
                                    $drive = $row2['drive'];
                                    $cyl = $row2['cylinders'];


                                ?>
        <div class="<?= $rented_class?>">
            <h2 id="product-name"><?=  $carName . "<br>";?></h2>
            <img class="rented_car-pic" src=<?=$carImg?> alt="Car">
            <br>
        <p id="info1">Rental Expires: <?=$expiration?> </p>
            <p id="info2">MPG: <?=$MPG?> </p>
            <p id="info3">Drive: <?=$drive?> </p>
            <p id="info4">Cylinders: <?=$cyl?> </p>



        </div>


    <?php } } } } }
    else {
        $db->close();

    ?>

            <div id="no-results">
                <br>
                <h3>No cars currently being rented</h3>
                <p>Visit our catalog to find your next ride!</p>
                <br>
            </div>
            <?php
        } } }
        else {
    ?>
    <div id="no-results">
        <br>
        <h3>You are not logged in</h3>
        <p>Please log in to view your garage</p>
        <br>
    </div>
        <?php
        } ?>

<script>

</script>
</body>
<footer style="background: rgb(224, 233, 255);">
    <p class="copyright">© 2022 Rocket Rentals Inc</p>
</footer>


</html>