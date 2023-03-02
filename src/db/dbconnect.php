<?php
//tweaked; also fixed typos; from Professor Miller:

$DB_USERNAME = "asegu51498"; //Your userid for tophat/database server, this is case-sensitive
$DB_DATABASE = $DB_USERNAME . "_webgroup1"; //Name of your database, the default is yourusername_default, this is case sensitive

$dbpwdPath = "/home/students/" . $DB_USERNAME . "/db.txt";
$db = null; //Mysqli Object

if (file_exists($dbpwdPath)) {
	 //DBPwd file exists
	 $DBPWD = trim(file_get_contents($dbpwdPath));

     //instead of db, use localhost for tophat
	 $db = new mysqli("webgroup1_db_1", $DB_USERNAME, $DBPWD, $DB_DATABASE);
	 if ($db->connect_errno) {
		  echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	 }

	 unset($DBPWD);
}
else {
	 trigger_error("Users db.txt file missing, unable to use DB, error=".$db->error, E_USER_ERROR);
}

function isLoggedIn()
{
//    if ( isset($_SESSION['usernum']) && isset($_SESSION['userid']) && isset($_SESSION['level']) && $_SESSION['level'] > 0 )
//        return true;
//    return false;
    if ( isset($_SESSION['username']) && isset($_SESSION['logged_in']))
        return true;
    return false;
}
function isAdmin() {
    if ( isset($_SESSION['username']) && isset($_SESSION['logged_in']) && $_SESSION['username'] === "admin")
        return true;
    return false;
}
