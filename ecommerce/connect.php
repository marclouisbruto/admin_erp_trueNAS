<?php
    $conn = new mysqli("localhost","root","","db_meatshop");

    if(!$conn){
        die(mysqli_error($conn));
    }