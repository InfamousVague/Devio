<?php
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    require '../init.php';
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    
    if (!isset($_POST['checkfor'])) {
        ?>
        { "datasuccess" : "0" , "success" : "0" }
        <?php
        exit;
    } else {
        switch($_POST['checkfor']){
            case 'display_name' : 
                if( !isset($_POST['value'])){
                    ?>
                     { "datasuccess" : "0" , "success" : "0" }
                    <?php
                    break;
                } else {
                    $display_name = strtolower($_POST['value']);
                    if( $stmt = $mysqli->prepare("SELECT `id` FROM `" . TABLE_USERS . "` WHERE LOWER(`username`) = ?") ){
                        $stmt->bind_param('s',$display_name);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->fetch();
                        if ($stmt->num_rows === 0) {
                            ?>
                            { "datasuccess" : "1" , "success" : "1" }
                            <?php
                        } else {
                            ?>
                            { "datasuccess" : "1" , "success" : "0" }
                            <?php
                        }
                    }
                }
            break;
            case 'email' : 
                if( !isset($_POST['value'])){
                    ?>
                     { "datasuccess" : "0" , "success" : "0" }
                    <?php
                    break;
                } else {
                    $email = strtolower($_POST['value']);
                    if( $stmt = $mysqli->prepare("SELECT `id` FROM `" . TABLE_USERS . "` WHERE LOWER(`email`) = ?") ){
                        $stmt->bind_param('s',$email);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->fetch();
                        if ($stmt->num_rows === 0) {
                            ?>
                            { "datasuccess" : "1" , "success" : "1" }
                            <?php
                        } else {
                            ?>
                            { "datasuccess" : "1" , "success" : "0" }
                            <?php
                        }
                    }
                }
            break;
            default : 
                ?>
                { "datasuccess" : "0" , "success" : "0" }
                <?php
            break;
        }
    }
?>