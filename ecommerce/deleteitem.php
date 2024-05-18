<?php
    include 'connect.php';

    $cartID = $_GET['cartID'];
    $deleteItem = "DELETE FROM tbl_addtocart WHERE cartID = $cartID";
    $result = mysqli_query($conn,$deleteItem);

    if($result){
        header('location: addtocart.php');
        exit;
    }else{
        echo "Failed to delete.";
    }