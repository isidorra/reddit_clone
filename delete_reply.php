<?php 

    require_once("inc/header.php");
    require_once("app/models/Reply.php");

    if(!$user->is_logged()) {
        header("Location: login.php");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $reply = new Reply();
        $reply = $reply->delete($_POST["reply_id"]);

        header('Location: discussion.php?discussion_id=' . $_GET["discussion_id"]);
        exit();
    }