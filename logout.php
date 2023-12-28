<?php 
    require_once("inc/header.php");

    if(!$user->is_logged()) {
        header("Location: login.php");
        exit();
    }

    $user->logout();
    header("Location: login.php");
    exit();

?>