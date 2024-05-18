<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (isset($_SESSION['user_id'])) {
        $customerID = $_SESSION['user_id'];
        $totalAmount = 0;

        // Retrieve cart items
        $query = "SELECT * FROM tbl_addtocart WHERE customerID = $customerID";
        $result = mysqli_query($conn, $query);

        $addedToCartItems = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $productName = $row['productName'];
            $quantity = $row['quantity'];
            $subtotal = $row['subTotal'];
            $cartID = $row['cartID'];

            $addedToCartItems[] = array(
                'productName' => $productName,
                'quantity' => $quantity
            );

            $totalAmount += $subtotal;
        }

        if (!empty($addedToCartItems)) {
            foreach ($addedToCartItems as $item) {
                $productName = $item['productName'];
                $quantities = $item['quantity'];

                $getProductQuery = "SELECT * FROM tbl_products WHERE productName = '$productName'";
                $productResult = mysqli_query($conn, $getProductQuery);
                $product = mysqli_fetch_assoc($productResult);

                if ($product) {
                    $subtotal = $quantities * $product['price'];
                    $newStockQuantity = $product['stockQuantity'] - $quantities;
                    $updateStockQuery = "UPDATE tbl_products SET stockQuantity = $newStockQuantity WHERE productID = {$product['productID']}";
                    mysqli_query($conn, $updateStockQuery);
                } else {
                    echo "Product $productName not found.<br>";
                }
            }

            $insertOrderQuery = "INSERT INTO tbl_order (customerID, orderDate, totalAmount) VALUES ($customerID, NOW(), $totalAmount)";
            mysqli_query($conn, $insertOrderQuery);
            $orderID = mysqli_insert_id($conn);

            foreach ($addedToCartItems as $item) {
                $productName = $item['productName'];
                $quantities = $item['quantity'];

                $getProductQuery = "SELECT * FROM tbl_products WHERE productName = '$productName'";
                $productResult = mysqli_query($conn, $getProductQuery);
                $product = mysqli_fetch_assoc($productResult);

                $subtotal = $quantities * $product['price'];
                $insertOrderItemQuery = "INSERT INTO tbl_orderitems (orderID, productID, quantity, subTotal) VALUES ($orderID, {$product['productID']}, $quantities, $subtotal)";
                mysqli_query($conn, $insertOrderItemQuery);
            }

            $sql = "SELECT * FROM tbl_customer WHERE customerID = $customerID";
            $retrieve = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($retrieve);

            if ($row) {
                // Clear the cart
                $deleteCart = "DELETE FROM tbl_addtocart WHERE customerID = $customerID";
                mysqli_query($conn, $deleteCart);

                // Redirect to a thank you page to prevent form resubmission
                header("Location: thankyou.php?name=" . urlencode($row['fullName']) . "&totalAmount=" . urlencode($totalAmount));
                exit;
            }
        } else {
            echo "Cart is empty.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Added Products</title>
</head>
<body>
    <table border="1" style="text-align: center; border-collapse:collapse;">
        <tr>
            <th>No.</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Cancel Order</th>
        </tr>
        <?php
        $id = $_SESSION['user_id'];
        $query = "SELECT * FROM tbl_addtocart WHERE customerID = $id";
        $result = mysqli_query($conn, $query);
        $productNum = 1;
        $totalAmount = 0;

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $productName = $row['productName'];
                $quantity = $row['quantity'];
                $subtotal = $row['subTotal'];
                $cartID = $row['cartID'];

                echo '
                    <tr>
                        <td>' . $productNum . '</td>
                        <td>' . $productName . '</td>
                        <td>' . $quantity . ' kg</td>
                        <td>Php ' . $subtotal . '</td>
                        <td><a href="deleteitem.php?cartID=' . $cartID . '"><button>Cancel Order</button></a></td>
                    </tr>
                ';
                $totalAmount += $subtotal;
                $productNum++;
            }
        }
        ?>
        <tr>
            <td colspan="5">Total Amount: Php <?php echo $totalAmount; ?></td>
        </tr>
    </table>
    <a href="buy.php"><button>Back</button></a>
    <form action="" method="post">
        <input type="submit" value="Check Out" name="checkout">
    </form>
</body>
</html>
