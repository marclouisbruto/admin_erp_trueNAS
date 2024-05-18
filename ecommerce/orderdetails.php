<?php
    include 'connect.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<body>
    <a href="buy.php"><button>Back</button></a>
    <table border="1" style="text-align: center; border-collapse:collapse; ">
        <style>
            th,
            td {
                padding: 5px;
            }
        </style>
        <tr>
            
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            
        </tr>
        <?php
            $customerID = $_SESSION['user_id'];

            

            if(isset($customerID)){
                $query = "SELECT tbl_order.*, tbl_products.productName, tbl_orderitems.* FROM tbl_products JOIN tbl_orderitems ON tbl_products.productID = tbl_orderitems.productID JOIN tbl_order ON tbl_orderitems.orderID = tbl_order.orderID WHERE tbl_order.customerID = $customerID  ORDER BY tbl_order.orderID ASC";
                $result = mysqli_query($conn,$query);

                if($result){
                    $currentOrderID = null;
                    
                    while($row = mysqli_fetch_assoc($result)){
                        $orderID = $row['orderID'];
                        $productName = $row['productName'];
                        $quantity = $row['quantity'];
                        $subtotal = $row['subTotal'];
                        $totalAmount = $row['totalAmount'];
                        $status = $row['status'];

                        $formattedDatetime = date('m-d-Y H:i:s', strtotime($row['orderDate']));
                        // Display order information only if it's a new order
                        if ($currentOrderID !== $orderID) {
                            echo "
                                <tr>
                                    <td colspan='3'>Order ID: $orderID | Order Date: " . $formattedDatetime . " | Total Amount: Php $totalAmount | Status: $status |
                                
                            ";
                            if($status === "Shipping"){
                                echo "<form action='' method='post'>
                                        <input type='submit' value='Order Received' name='shiporder_$orderID'>
                                      </form>";
                            }
                            echo "</td></tr>";
                            $currentOrderID = $orderID; // Update the current order ID
                        }
                        if(isset($_POST["shiporder_$orderID"])){
                            $updateToShipping = "UPDATE tbl_order SET status = 'Delivered' WHERE tbl_order.orderID = $currentOrderID;";
                            mysqli_query($conn,$updateToShipping);

                            header("Location: ".$_SERVER['PHP_SELF']);
                            exit();
                        }
                        echo "
                        <tr>
                            
                            <td>".$productName."</td>
                            <td>".$quantity."kg</td>
                            <td>Php ".$subtotal."</td>
                            
                            
                        </tr>
                        ";
                        
                    }
                }
            }
        ?>
        
    </table>
</body>
</html>