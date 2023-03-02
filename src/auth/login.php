<?php

require_once("../db/dbconnect.php");

if (!isset($_SESSION)){
    session_start();
}

$msg = "";

if ( isset ($_POST['username']) && isset ($_POST['password'])) {
    
    if (!$_POST['username']) {
        $msg = "Username field must not be left blank";
    } else if (!$_POST['password']) {
        $msg = "Password field must not be left blank";
    } else {

        $sql = "SELECT username, password FROM users WHERE username='" . $_POST['username']  . "'";
        $row = $db->query($sql)->fetch_assoc();

        if (password_verify($_POST['password'], $row['password']) || $_POST['password'] == $row['password']) {
            // if passwords match, set session, login
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;
            
            header("Location: ../");
    
        } else {
            $msg = "Wrong username or password";
        }
    } 
}

?><!DOCTYPE html>
<?php include "authheader.php" ?>
            <?php if ( isLoggedIn() ) { ?>
                <p>You are already logged in as <b><?php echo $_SESSION['username']?></b></p>
                <p>Click <a style="text-decoration: none; color: black;" href="logout.php"><em>here</em></a> to logout</p>
            </div>
        </div>
    </body>
    </html>
    <?php } else { ?>
            <h2>Login</h2>
            <form method="post">
                <div>
                    <input type="text" value="" name="username" id="username" placeholder="Username" required>
                </div>
                <div>
                    <input type="password" value="" name="password" id="password" placeholder="Password" required>
                </div>
                <div>
                    <input type="submit" value="Login">
                </div>
                <?php 
                if ( $msg != "" ) {
                    ?>
                    <p><?= $msg?></p>
                    <?php      
                } else {
                    $msg = "";
                    ?>
                    <p><?= $msg?></p> 
                <?php
                }
                ?>
            </form>
        </div>
    </div>
    </body>
</html>
<?php  
}
?>
