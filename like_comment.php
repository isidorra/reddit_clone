<?php
require_once("inc/header.php");
require_once("app/models/Comment.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 

    $comm = new Comment();

   
    if (!$comm->is_liked($_POST["comment_id"], $_SESSION["user_id"])) {
        $comment = new Comment();
        $comment->like($_POST["comment_id"], $_SESSION["user_id"]);

        header('Location: discussion.php?discussion_id=' . $_GET["discussion_id"]);
        exit();
    } else {
        $comment = new Comment();
        $comment->remove_like($_POST["comment_id"], $_SESSION["user_id"]);

        header('Location: discussion.php?discussion_id=' . $_GET["discussion_id"]);
        exit();
    }
}
?>