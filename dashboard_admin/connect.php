<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpassword = "";
    $dbname = "db_meatshop";

    $conn = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

    if(!$conn){
        die("Connection failed: " . $conn->connect_error);;
    }
?>