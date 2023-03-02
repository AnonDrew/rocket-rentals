<?php

include("../db/dbconnect.php");

session_start();

if ( !isLoggedIn() ) {
    include "authheader.php" ?>
                <p>You are currently not logged in</p>
                <p>Click <a style="text-decoration: none; color: black;" href="login.php"><em>here</em></a> to login</p>
            </div>
        </div>    
    </body>
</html>
<?php
} else {

    $_SESSION['logged_in'] = false;
    unset($_SESSION['username']);

    header("Location: ../", true);

}
?>