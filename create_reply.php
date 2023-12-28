<?php
require_once("inc/header.php");
require_once("app/models/Reply.php");

if(!$user->is_logged()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_id = $_GET["comment_id"];
    $user_id = $_SESSION["user_id"];
    $content = $_POST["content"];

    $reply = new Reply();
    $reply->create($comment_id, $user_id, $content);

    header("Location: discussion.php?discussion_id=" . $_GET["discussion_id"]);
    exit();
}
?>