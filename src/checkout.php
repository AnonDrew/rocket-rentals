<?php

require_once("db/dbconnect.php");

session_start();

function performCheckout($row){
    
    $db->autocommit(FALSE);

    $sql = sprintf("UPDATE users SET is_rented='%s' WHERE cid='%d'", 
    $db->real_escape_string($_SESSION['username']),$db->real_escape_string($_GET['cid']));

    if ($db->query($sql)){
        $db->commit();
        header ("Location: index.php", true);
    }
    
    $db->close();

}

if (isset($_GET['cid'])){

    $car_id = $_GET['cid'];

    $sql = sprintf("SELECT * FROM cars WHERE cid='%s'", $db->real_escape_string($car_id));

    if ($result = $db->query($sql)){
        $row = $result->fetch_assoc();
    }

    $db->close();

}
$naming = "car-card";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/navstylesavery.css">
    <link rel="stylesheet" href="styles/checkoutstyles.css">
    <title>Document</title>
</head>
<body>
<?php include "nav.php"?>
    <div class="checkout-wrapper">
        <div class="internal-checkout-wrapper">
            <div class="<?=$naming?>">
                <img data-cid=<?=$row['cid']?> class="<?=$naming?>-pic" src=<?=$row['image_url']?> alt=<?=$row['year'] . " " . $row['make'] . " " . $row['model']?> />
                <h4 class="<?=$naming?>-make-model"><?=$row['year'] . " " . $row['make'] . '<br>' . $row['model']?></h4>
                <dl class="<?=$naming?>-info">
                    <img id="test" src="https://www.svgrepo.com/show/64833/black-car-side-view.svg" height="20" width="20">
                    <dt class="<?=$naming?>-class"><?=$row['class']?></dt>

                    <img src="http://cdn.onlinewebfonts.com/svg/img_307276.png" height="15" width="15">
                    <dt class="<?=$naming?>-drive"><?=$row['drive']?></dt>

                    <img src="http://cdn.onlinewebfonts.com/svg/img_537129.png" height="15" width="15">
                    <dt class="<?=$naming?>-transmission"><?=$row['transmission'] == "a" ? "automatic" : "manual"?></dt>

                    <img src="http://cdn.onlinewebfonts.com/svg/img_432549.png" height="15" width="15">
                    <dt class="<?=$naming?>-mpg"><?=$row['combination_mpg'] . " mpg"?></dt>

                    <img src="https://freesvg.org/img/fuel-15.png" height="15" width="15">
                    <dt class="<?=$naming?>-fuel"><?=$row['fuel_type']?></dt>
                </dl>
            </div>
            <form id="checkout-form" method="post" action="placeholder">
                <div class="checkout-options">
                    <div class="checkout-options-duration">
                        <input type="radio" class="rental-duration" id="duration1" name="duration" value="1" checked>
                        <label for="duration1">
                            <div class="duration1">
                                <h1>1 Day</h1>
                            </div>
                            <div class="price">
                                <h1>$<?=$row["price"]?></h1>
                                <p>price perday ($)</p>
                            </div>
                        </label>
                        <input type="radio" class="rental-duration" id="duration2" name="duration" value="3">
                        <label for="duration2">
                            <div class="duration2">
                                <h1>3 Days</h1>
                            </div>
                            <div class="price">
                                <h1>$<?=$row["price"] * 3?></h1>
                                <p>calculated</p>
                            </div>
                        </label>
                        <input type="radio" class="rental-duration" id="duration3" name="duration" value="7">
                        <label for="duration3">
                            <div class="duration2">
                                <h1>7 Days</h1>
                            </div>
                            <div class="price">
                                <h1>$<?=$row["price"] * 7?></h1>
                                <p>calculated</p>
                            </div>
                        </label>
                    </div>
                    <div class="checkout-options-cc">
                        <table>
                            <tr>
                                <td>
                                    <label for="cc-number">Credit Card Number</label>
                                </td>
                                <td>
                                    <input type="tel" maxlength="16" class="cc-number" name="cc-number" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="cc-cvc">CVC</label>
                                </td>
                                <td>
                                    <input type="tel" maxlength="3" class="cc-cvc" name="cc-cvc" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="cc-expiration">Expiration Date</p>
                                </td>
                                <td>
                                    <input type="tel" maxlength="2" class="cc-mmyy" name="cc-mm" placeholder="MM" required>
                                    /
                                    <input type="tel" maxlength="2" class="cc-mmyy" name="cc-yy" placeholder="YY" required>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" class="checkout-btn" value="Checkout">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include_once "footer.php" ?>
    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(ev) {
        ev.preventDefault();
        let duration = document.querySelector('input[type="radio"]:checked').getAttribute('value');
        this.action = `checkoutredir.php?cid=${<?=$row['cid']?>}&duration=${duration}`;
        this.submit();
        });
    </script>
</body>
</html>