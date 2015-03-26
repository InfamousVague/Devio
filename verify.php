<?php
    require 'init.php';
    if( isset($_GET['username']) && isset($_GET['veri_key']) ){
        $username = $_GET['username'];
        $key = $_GET['veri_key'];
        
        if ($stmt = $mysqli->prepare("SELECT `id`, `rank` FROM `" . TABLE_USERS . "` WHERE `username` = ? AND `verification_key` = ? ")){
            $stmt->bind_param("ss",$username,$key);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$rank);
            $stmt->fetch();
            if ($stmt->num_rows === 1) {
                if( $rank > 1 ){
                    header("Location: /index?error=alreadyverified");
                    exit;
                }
                if ($stmt2 = $mysqli->prepare("UPDATE `" . TABLE_USERS . "` SET `rank` = 2 WHERE `id` = ?")) {
                    $stmt2->bind_param("i",$id);
                    $stmt2->execute();
                    header("Location: /index");
                } 
            } else {
                header("Location: /index?error=verificationfailed");
            }
        }
    }
?>