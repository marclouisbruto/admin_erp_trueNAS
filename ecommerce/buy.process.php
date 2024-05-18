<?php
include 'connect.php';
session_start();

//Kapag naka-set na yung Add Selected to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    
    //If naka-set na yung user_id na nasa login.php line 19
    if (isset($_SESSION['user_id'])) {

        //Value ng user_id para sa pag-iinsert ng customerID sa ibang table
        $customerID = $_SESSION['user_id'];

        //Pagkuha ng values sa products[] at quantities[productName]
        $selectedProducts = $_POST['products'];
        $quantities = $_POST['quantities'];

        //Para sa pagkuha ng totalAmount na inserted sa tbl_order
        $totalAmount = 0;

        //Array para ma-store ang mga in-order para ma-display pagkatapos bumili
        $orderedProducts = array();

        //Iisa-isahin yung nasa loob ng products[] checkbox input
        
            foreach ($selectedProducts as $productName) {

            //I-reretrieve yung mga products na selected sa checkbox
                $getProductQuery = "SELECT * FROM tbl_products WHERE productName = '$productName'";
                $productResult = mysqli_query($conn, $getProductQuery);
                $product = mysqli_fetch_assoc($productResult);

                if ($product) {
                    //Kukuhanin ang quantity per product na binili
                    $quantity = $quantities[$productName];
                    /* Halimbawa
                                quantities = [
                                    'Tapa'      => 1
                                    'Buto-buto' => 3
                                ];
                    */

                    //Pagkuha ng price ng isang product base sa quantity
                    $subtotal = $quantity * $product['price'];

                    //Pagbawas ng quantity ng products sa tbl_products
                    $newStockQuantity = $product['stockQuantity'] - $quantity;
                    $updateStockQuery = "UPDATE tbl_products SET stockQuantity = $newStockQuantity WHERE productID = {$product['productID']}";
                    mysqli_query($conn, $updateStockQuery);

                    //Nasa loob ng foreach ang totalAmount para ma-store ang bawat subtotal para sa totalAmount
                    $totalAmount += $subtotal;

                    //Pagset ng key para sa productName, quantity, at subtotal para sa display
                    $orderedProducts[] = array(
                        'productName' => $product['productName'],
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    );
                } else {
                    echo "Product $productName not found.<br>";
                }
            }
        

        //Nasa labas ng foreach para maging order summary ng bawat customer
        $insertOrderQuery = "INSERT INTO tbl_order (customerID, orderDate, totalAmount) VALUES ($customerID, NOW(), $totalAmount)";
        mysqli_query($conn, $insertOrderQuery);

        //Kukuhanin ang orderID ng huling query na insert para magamit sa tbl_orderitems
        $orderID = mysqli_insert_id($conn);


        foreach ($selectedProducts as $productName) {
                //I-reretrieve yung mga products na selected sa checkbox
                //Inulit kasi hindi na scope ng foreach na nauna
                $getProductQuery = "SELECT * FROM tbl_products WHERE productName = '$productName'";
                $productResult = mysqli_query($conn, $getProductQuery);
                $product = mysqli_fetch_assoc($productResult);

                $quantity = $quantities[$productName];
                $subtotal = $quantity * $product['price'];

                //Pagiinsert ng individual product sa tbl_orderitems
                $insertOrderItemQuery = "INSERT INTO tbl_orderitems (orderID, productID, quantity, subTotal) VALUES ($orderID, {$product['productID']}, $quantity, $subtotal)";
                mysqli_query($conn, $insertOrderItemQuery);
        }

        //Pagdidisplay ng mga na-order kasama ang subtotal at totalAmount
        echo "Order created successfully!<br>";
        echo "Ordered Products:<br>";
        foreach ($orderedProducts as $orderedProduct) {
            echo "Product: " . $orderedProduct['productName'] . ", Quantity: " . $orderedProduct['quantity'] . ", Subtotal: Php " . $orderedProduct['subtotal'] . "<br>";
            
        }
        echo "Total Amount: Php ". $totalAmount;
    } else {
        echo "Customer not authenticated.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="buy.php"><button>Back</button></a>
</body>
</html>