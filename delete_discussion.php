<?php 

    require_once("inc/header.php");
    require_once("app/models/Discussion.php");

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $discussion = new Discussion();
        $discussion = $discussion->delete($_POST["discussion_id"]);

        header('Location: index.php');
        exit();
    }