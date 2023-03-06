<?php
require_once("db/dbconnect.php");

session_start();

if (!isLoggedIn()){
    header ("Location auth/login.php", true);
}

date_default_timezone_set('EST');

$return_date = new DateTime("+" . $_GET['duration'] . " days");
$formatted_return_date = $return_date->format('Y-m-d H:i:s');

$db->autocommit(FALSE);

$sql = sprintf(
    "UPDATE cars SET rented_by='%s', return_date='%s' WHERE cid='%d'", 
    $db->real_escape_string($_SESSION['username']),
    $db->real_escape_string($formatted_return_date),
    $db->real_escape_string($_GET['cid'])
);

//Causes “Cannot Modify Header Information – Headers Already Sent By” Error??
//echo $formatted_return_date;

if ($db->query($sql)){
    $db->commit();
    header ("Location: index.php", true);
}
else {
    echo "failed";
}

$db->close();



