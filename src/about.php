<?php
require_once("db/dbconnect.php");
session_start();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/about_styles.css">
	<link rel="stylesheet" href="styles/navstylesavery.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>about</title>
</head>
<body>
<?php include "nav.php"?>

<div class = "about_header" style="background-image: url(img/brimg.jpg)">
	<h2>About Us</h2>
	<p>Our mission is to rent cars to people at affortable prices.</p>
</div>

<div class="row">
  <div class="column">
    <div class="card">
      
      <div class="person">
        <h2>Avery Dunn</h2>
        <p class="title">CEO</p>
        <p> avery@rocketrentals.com</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>

  <div class="column">
    <div class="card">
      
      <div class="person">
        <h2>Drew Segura</h2>
        <p class="title">CEO</p>
        <p> drew@rocketrentals.com</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>
  
   <div class="column">
    <div class="card">
      
      <div class="person">
        <h2>Krishan Sapkota</h2>
        <p class="title">CEO</p>
        <p> krishan@rocketrentals.com</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>

  <div class="column">
    <div class="card">
      
      <div class="person">
        <h2>Nico Leone</h2>
        <p class="title">CEO</p>
        <p> nico@rocketrentals.comm</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>
</div>
<?php include_once "footer.php" ?>
</body>
</html>