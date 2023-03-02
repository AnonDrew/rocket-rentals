<?php
session_start();
require_once("db/dbconnect.php");

// associate one key identifier to various distinct property values to simplify 
// the options available on the filter
$car_type_arr = array(
    'sedan' => array('midsize car', 'small station wagon', 'subcompact car', 'large car', 'compact car'),
    'coupe' => array('two seater', 'minicompact car'),
    'suv' => array('small pickup truck', 'standard pickup truck', 'sport utility vehicle','small sport utility vehicle', 'standard sport utility vehicle'),
    '4wd' => array('awd', '4wd')
);

$table = "cars";

// $sql_query_string: base for buildQuery(), concatenate on top of this to build query string
$sql_query_string = "SELECT * FROM $table WHERE ";

// buildQueryUtil: handle concatenating onto $sql_query_string(above) when a POST key's values are arrays
// for the POST properties that have arrays as values, they are processed through this helper
// function with the help of $car_type_arr for reference
function buildQueryUtil($car_value, $key) {
    global $car_type_arr;
    global $sql_query_string;
    foreach($car_type_arr[$car_value] as $car_type_value) {
        $sql_query_string .= $key . '="' . $car_type_value . '" OR ';
    }
};

// buildQuery: returns formatted mariadb style query string to be sent to db (filter selection dependent)
// will return "SELECT * FROM cars" if there is no filter criteria (display all cars)
function buildQuery(){
    global $table;
    global $sql_query_string;

    // iterate through all keys submitted through POST, and handle their values
    foreach ($_POST as $car_property) {
        $key = strval(key($_POST));

        // begin building query from user selections

        // specifically handle min / max price
        if ($key == 'car-price-min' && $car_property) {
            $sql_query_string .= "(price >= " . $car_property . ") AND ";
        }
        else if ($key == 'car-price-max' && $car_property) {
            $sql_query_string .= "(price <= " . $car_property . ") AND ";
        }
        
        // handle all other filter selections
        else if ($key != 'car-price-min' && $key != 'car-price-max') {
            $sql_query_string .= "(";

            // process all selections within filters subsection together
            foreach ($car_property as $car_value) {
                // specifically handle filter options which have a one-to-many relationship with values in the db
                // ex. 'suv' => ('sports utility vehicle', 'small sports utility vehicle', 'large sports utility vehicle')
                if ($key == 'class' || ($key == 'drive' && $car_value == '4wd')) {
                    buildQueryUtil($car_value, $key);  
                } else {
                    $sql_query_string .= $key . '="' . $car_value . '" OR ';
                }
            // remove any ORs before closing parenthesis of property conditional query
            }
            if (substr($sql_query_string, strlen($sql_query_string)-4, strlen($sql_query_string)) == " OR ") {
                $sql_query_string = substr($sql_query_string, 0, strlen($sql_query_string)-4);
            }
            $sql_query_string .= ") AND ";
        }
        next($_POST);
    }
    // remove any ANDs before returning query
    if (substr($sql_query_string, strlen($sql_query_string)-5, strlen($sql_query_string)) == " AND ") {
        $sql_query_string = substr($sql_query_string, 0, strlen($sql_query_string)-5); // trim the final "AND"
    }

    // if no filter criteria, return a valid query string that displays all cars
    if ($sql_query_string == "SELECT * FROM $table WHERE ") {
        return "SELECT * FROM $table WHERE rented_by IS NULL";
    }

    $sql_query_string .= " AND rented_by IS NULL";

    return $sql_query_string;
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge" />-->
    <link rel="stylesheet" href="styles/navstylesavery.css"/>
    <link rel="stylesheet" href="styles/catalog.css" />
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0" />-->
    <title>Catalog</title>
</head>
<body>
    <?php include "nav.php"?>
    <main id="catalog-wrapper">
        <div id="catalog">
            <div id="catalog-filter-wrapper">
                <form id="catalog-filter-form" method="POST">
            <!--uncomment the below line to see what the POST key / value pairs are-->
            <!--<form action="https://wp.zybooks.com/form-viewer.php" target="_blank" method="POST">-->
                    <div id="catalog-filters">
                        <div id="filter-and-reset">
                            <button id="filterbtn" type="submit" value="Filter">Filter</button>
                            <a href="catalog.php">reset</a>
                        </div>
                        <div class="filter-options" id="filter-car-type">
                            <h4>Category</h4>
                            <div id="filter-car-type-coupe">
                                <input id="car-type-coupe" type="checkbox" value="coupe" name="class[]">
                                <label for="car-type-coupe">Coupe</label>
                            </div>
                            <div id="filter-car-type-sedan">
                                <input id="car-type-sedan" type="checkbox" value="sedan" name="class[]">
                                <label for="car-type-sedan">Sedan</label>
                            </div>
                            <div id="filter-car-type-suv">
                                <input id="car-type-suv" type="checkbox" value="suv" name="class[]">
                                <label for="car-type-suv">SUV</label>
                            </div>
                        </div>
                        <div class="filter-options" id="filter-drive">
                            <h4>Drive</h4>
                            <div id="filter-car-type-awd">
                                <input id="car-type-awd" type="checkbox" value="4wd" name="drive[]">
                                <label for="car-type-awd">AWD</label>
                            </div>
                            <div id="filter-car-type-rwd">
                                <input id="car-type-rwd" type="checkbox" value="rwd" name="drive[]">
                                <label for="car-type-rwd">RWD</label>
                            </div>
                            <div id="filter-car-type-fwd">
                                <input id="car-type-fwd" type="checkbox" value="fwd" name="drive[]">
                                <label for="car-type-fwd">FWD</label>
                            </div>
                        </div>
                        <div class="filter-options" id="filter-fuel-type">
                            <h4>Fuel</h4>
                            <div id="filter-fuel-type-gas">
                                <input id="fuel-type-gas" type="checkbox" value="gas" name="fuel-type[]">
                                <label for="fuel-type-gas">Gas</label>
                            </div>
                            <div id="filter-fuel-type-diesel">
                                <input id="fuel-type-diesel" type="checkbox" value="diesel" name="fuel-type[]">
                                <label for="fuel-type-diesel">Diesel</label>
                            </div>
                            <div id="filter-fuel-type-electric">
                                <input id="fuel-type-electricity" type="checkbox" value="electricity" name="fuel-type[]">
                                <label for="fuel-type-electricity">Electric</label>
                            </div>
                        </div>
                        <div class="filter-options" id="filter-transmission-type">
                            <h4>Transmission</h4>
                            <div id="filter-transmission-type-manual">
                                <input id="fuel-type-manual" type="checkbox" value="m" name="transmission[]">
                                <label for="fuel-type-manual">Manual</label>
                            </div>
                            <div id="filter-transmission-type-automatic">
                                <input id="fuel-type-automatic" type="checkbox" value="a" name="transmission[]">
                                <label for="fuel-type-automatic">Automatic</label>
                            </div>
                        </div>
                        <div class="filter-options" id="filter-car-price">
                            <h4>Price</h4>
                            <div id="filter-car-price-min">
                                <input id="car-price-min" type="number" min="0" max="998" name="car-price-min">
                                <label for="car-price-min">Min</label>
                            </div>
                            <div id="filter-car-price-max">
                                <input id="car-type-max" type="number" min="1" max="999" name="car-price-max">
                                <label for="car-price-max">Max</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            // dynamically create all cards for cars that are returned from filter criteria
            if ($result = $db->query(buildQuery())) {
                $naming = "car-card";
                echo '<div id="catalog-car-results">';
                while ($row = $result->fetch_assoc()) {
            ?>
                <div class="<?=$naming?>">
                    <div class="<?=$naming?>-pic-wrapper" >
                        <img class="<?=$naming?>-pic" src=<?=$row['image_url']?> alt="car" />
                    </div>
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
                    <h4 class="<?=$naming?>-price">
                        $<?=$row['price']?> (per day)
                        <a class="<?=$naming?>-info-link" href="http://localhost:8000/car.php?cid=<?=$row['cid']?>" style="">&#9432;</a>
                    </h4>
                    <button class="<?=$naming?>-checkoutbtn" data-cid=<?=$row['cid']?> onclick="onCheckout(event)">checkout</button>
                </div>
            <?php }
                echo '</div>';
            }
            else {
            ?>
            <div id="catalog-no-results-wrapper">
                <div id="catalog-no-results">
                    <h3>No cars available</h3>
                    <p>You may need to tweak your filters or come back at a later time.</p>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </main>
    <?php include_once "footer.php" ?>
    <script>
        const onCheckout = (ev) => window.location.replace(`http://tophat.sunywcc.edu/~asegu51498/checkout.php?cid=${ev.target.getAttribute("data-cid")}`);
    </script>
</body>
</html>