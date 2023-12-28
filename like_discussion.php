<?php
require_once("inc/header.php");
require_once("app/models/Discussion.php");

if(!$user->is_logged()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 

    $disc = new Discussion();

   
    if (!$disc->is_liked($_POST["discussion_id"], $_SESSION["user_id"])) {
        $discussion = new Discussion();
        $discussion->like($_POST["discussion_id"], $_SESSION["user_id"]);

        header("Location: index.php");
        exit();
    } else {
        $discussion = new Discussion();
        $discussion->remove_like($_POST["discussion_id"], $_SESSION["user_id"]);

        header("Location: index.php");
        exit();
    }
}
?>