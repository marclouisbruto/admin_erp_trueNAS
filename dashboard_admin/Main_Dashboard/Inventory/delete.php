<?php
    include '../../connect.php';

    $productID = $_GET['productID'];
    
    $query = "DELETE FROM tbl_products WHERE productID = $productID";
            $result = mysqli_query($conn,$query);
            if($result){
                header('location: inventory-live.php');
                exit();
    }else{
        echo "Can't delete product " . $row['productName'];
    }
?>