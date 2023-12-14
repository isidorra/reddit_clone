<?php

    session_start();

    $server_name = "localhost";
    $db_username = "Isidora";
    $db_password = "123456";
    $db_name = "reddit_clone";

    $conn = mysqli_connect($server_name, $db_username, $db_password, $db_name);

    if(!$conn)
        die("Database connection failed.");


