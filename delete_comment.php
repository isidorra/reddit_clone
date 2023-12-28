<?php 

    require_once("inc/header.php");
    require_once("app/models/Comment.php");

    if(!$user->is_logged()) {
        header("Location: login.php");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $comment = new Comment();
        $comment = $comment->delete($_POST["comment_id"]);

        header('Location: discussion.php?discussion_id=' . $_GET["discussion_id"]);
        exit();
    }