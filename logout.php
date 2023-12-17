<?php 
    require_once("inc/header.php");

    $user->logout();
    header("Location: login.php");
    exit();

?>