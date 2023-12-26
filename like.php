<?php
require_once("inc/header.php");
require_once("app/models/Discussion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 

    $disc = new Discussion();

    echo "Discussion ID: " . $_POST["discussion_id"] . "<br>";
    echo "User ID: " . $_SESSION["user_id"] . "<br>";
   
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