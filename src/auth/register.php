<?php

require_once("../db/dbconnect.php");

if (!isset($_SESSION)){
    session_start();
}

$msg = "";
$created = false;

if ( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password-verify']) ){
    
    if (!$_POST['username']){
        $msg = "Username can not be left blank";
    }

    else {
        $db->autocommit(FALSE);

        // look into sql injection, this is BAD practice
        $sql = "SELECT username FROM users WHERE username ='" . $_POST['username'] . "'";

        if ($result = $db->query($sql)) {
            
            $exists = false;
                
                while ($row = $result->fetch_assoc()) {
                    if ($_POST['username'] == $row['username']){
                        $exists = true;
                        break;
                    }
                }

                if (!$exists){
                    
                    if ($_POST['password'] == $_POST['password-verify']){
                        $sql = sprintf("
                        INSERT INTO users (username, user_password)
                        VALUES ('%s', '%s');
                        ",
                        $db->real_escape_string($_POST['username']),
                        $db->real_escape_string(password_hash($_POST['password'], PASSWORD_DEFAULT))
                        );
                    
                        // ask miller about commit() and rollback()
                        if ($db->query($sql)) {
                            $db->commit();
                            $created = true;
                            header ("Location: ../index.php", true);
                        
                        } else { outputDBError($db); }

                    } else { $msg = "Passwords do not match"; }

                } else { $msg = "Username already exists"; }
                    
            } $db->close();
    }
}

?><!DOCTYPE html>
<?php include "authheader.php" ?>
                <h2>Register</h2>
                <form method="post">
                    <div>
                        <input type="text" value="" placeholder="Username" name="username" id="username" required>
                    </div>
                    <div>
                        <input type="password" value="" placeholder="Password" name="password" id="password" required>
                    </div>
                    <div>
                        <input type="password" value="" placeholder="Confirm"name="password-verify" id="password-verify" required>
                    </div>
                    <div>
                        <input type="submit" value="Create Account" id="create_btn">
                    </div>
                    <?php if ( $msg != "" ) { ?> <p><?= $msg?></p> <?php } ?>
                </form>
            </div>
        </div>
    </body>
</html>

