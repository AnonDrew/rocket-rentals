<?php

$home_url = "catalog.php";
$logo_url = "img/rocketrentalslogo.svg";
$about_url = "about.php";
$login_url = "login.php";
$logout_url = "logout.php";
$register_url = "register.php";
$garage_url = "garage.php";
$admin_url = "admin.php";

// could not use str_contains()
if (strpos($_SERVER['REQUEST_URI'], 'auth')){
    $home_url = "../" . $home_url;
    $logo_url = "../" . $logo_url;
    $about_url = "../" . $about_url;
    $garage_url = "../" . $garage_url;
} else {
    $login_url = "auth/" . $login_url;
    $register_url = "auth/" . $register_url;
    $logout_url = "auth/" . $logout_url;
}

?>
<nav id="nav-wrapper">
    <a class="nav-item" id="logo" href="<?=$home_url?>"><img src="<?=$logo_url?>" alt="rocket rentals logo" width="150px"></a>
    <div id="internal-nav-wrapper">
        <a class="nav-item internal-nav-item" href=<?=$about_url?>>About Us</a>
        <?php if (!isLoggedIn()) { ?>
        <a class="nav-item internal-nav-item" href=<?=$login_url?>>Login</a>
        <a class="nav-item internal-nav-item" href=<?=$register_url?>>Register</a>
        <?php } else { ?>
        <?php if ($_SESSION['username'] == "admin") { ?>
        <a class="nav-item internal-nav-item" href=<?=$admin_url?>>Admin</a>
        <?php } ?>
        <a class="nav-item internal-nav-item" href=<?=$garage_url?>>My Garage</a>
        <a class="nav-item internal-nav-item" href=<?=$logout_url?>>Logout</a>
        <?php } ?>
    </div>
</nav>