<?php
    require_once("index.php");

    if(!$user->is_logged()) {
        header("Location: login.php");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];
        $subject = $_POST['subject'];
        $topic_id = $_POST['topic_id'];

        $discussion = new Discussion();
        $discussion = $discussion->create($user_id, $subject, $topic_id);
        
        header("Location: index.php");
        exit();
    }