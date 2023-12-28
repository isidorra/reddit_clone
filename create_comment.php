<?php
require_once("inc/header.php");
require_once("app/models/Comment.php");

if(!$user->is_logged()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $discussion_id = $_GET["discussion_id"];
    $user_id = $_SESSION["user_id"];
    $content = $_POST["content"];

    $comment = new Comment();
    $comment->create($discussion_id, $user_id, $content);

    header("Location: discussion.php?discussion_id=" . $discussion_id);
    exit();
}
?>




