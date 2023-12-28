<?php
require_once("inc/header.php");
require_once("app/models/Reply.php");

if(!$user->is_logged()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 

    $rep = new Reply();
    
   
    if (!$rep->is_liked($_POST["reply_id"], $_SESSION["user_id"])) {
        $reply = new Reply();
        $reply->like($_POST["reply_id"], $_SESSION["user_id"]);
        
        header('Location: discussion.php?discussion_id=' . $_GET["discussion_id"]);
        exit();
    } else {
        $reply = new Reply();
        $reply->remove_like($_POST["reply_id"], $_SESSION["user_id"]);

        header('Location: discussion.php?discussion_id=' . $_GET["discussion_id"]);
        exit();
    }
}
?>