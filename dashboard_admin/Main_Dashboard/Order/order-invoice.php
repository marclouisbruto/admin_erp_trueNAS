<?php
session_start();
include '../../connect.php';
$adminID = $_SESSION['adminID'];
$orderID = $_GET['orderID'];
if(isset($orderID)){
    $query = "SELECT
                tbl_order.*,
                tbl_customer.fullName,
                tbl_customer.address,
                tbl_orderitems.quantity,
                tbl_orderitems.subTotal,
                tbl_products.productName,
                tbl_products.price
            FROM
                tbl_products
            JOIN tbl_orderitems ON tbl_orderitems.productID = tbl_products.productID
            JOIN tbl_order ON tbl_orderitems.orderID = tbl_order.orderID
            JOIN tbl_customer ON tbl_order.customerID = tbl_customer.customerID
            WHERE
                tbl_order.orderID = $orderID
            ORDER BY tbl_products.productName ASC;";
    $result = mysqli_query($conn,$query);
    if($result){
        $headerRow = mysqli_fetch_assoc($result);
        $totalAmount = $headerRow['totalAmount'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../invoice.css">
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
    <title>Order Receipt</title>
</head>
<body>
    <div class="back"><a href="order-complete.php"><button>Back</button></a></div>
    <div class="print"><button onclick="printInvoice()">Print</button></div>
    <div class="wrapper">
        <div class="header">
            <div id="logo"><img src="../../LogIn_SignUp_Admin/Images/mira.png" alt=""></div>
            <div class="title">
                <h3>Vido And Mira Meatshop</h3>
                <p>Purok 6, Brgy. Sta. Maria, San Pablo City</p>
            </div>
        </div>
        <div class="orderID">
            Order ID: <?php echo $orderID ?>
        </div>
        <div class="contact">
            Customer ID: <?php echo $headerRow['customerID'] ?>  <br>
            Name: <?php echo $headerRow['fullName'] ?> <br>
            <div id="address-date">
                <div class="address">
                Address: <?php echo $headerRow['address'] ?></div>
                <div class="date">Date: <?php $formattedDatetime = date('F d, Y ', strtotime($headerRow['orderDate']));
                echo $formattedDatetime;
                ?></div>
            </div>
        </div>

        <div class="table-receipt">
            <h4>Order Invoice</h4>
        
            <table border="1">
                <tr>
                    <th>Qty.</th>
                    <th>Product name</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
                
                <?php 
                $query1 = "SELECT
                            tbl_order.*,
                            tbl_customer.fullName,
                            tbl_customer.address,
                            tbl_orderitems.quantity,
                            tbl_orderitems.subTotal,
                            tbl_products.productName,
                            tbl_products.price
                        FROM
                            tbl_products
                        JOIN tbl_orderitems ON tbl_orderitems.productID = tbl_products.productID
                        JOIN tbl_order ON tbl_orderitems.orderID = tbl_order.orderID
                        JOIN tbl_customer ON tbl_order.customerID = tbl_customer.customerID
                        WHERE
                            tbl_order.orderID = $orderID
                        ORDER BY tbl_products.productName ASC;";
                $result1 = mysqli_query($conn,$query1);
                if($result1){
                    while($row = mysqli_fetch_assoc($result1)){
                        $quantity = $row['quantity'];
                        $productName = $row['productName'];
                        $price = $row['price'];
                        $subTotal = $row['subTotal'];

                        echo "
                        <tr>
                            <td>".$quantity."kg</td>
                            <td>".$productName."</td>
                            <td>".$price."</td>
                            <td>".$subTotal."</td>
                        </tr>
                        ";
                    }
                }
                ?>
                <tr>
                    <td colspan="3" id="amount-total">TOTAL Php</td>
                    <td><?php echo $totalAmount; ?></td>
                </tr>
            </table>
            <div class="footer">
                Prepared By: <?php $admin = "SELECT fullName FROM tbl_customer WHERE userID = $adminID";
                $result = mysqli_query($conn, $admin);
                $row = mysqli_fetch_assoc($result);

                echo $row['fullName'];
                ?> 
            </div>
        </div>
    </div>
</body>
</html>







<?php 
    
} 
?>