<?php
include 'connect.php';
session_start();
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
        //Pagkuha ng user_id ng current logged in customer para sa pagdidisplay ng mga na-add to cart ng isang customer base sa kanyang customerID
        $id = $_SESSION['user_id'];

        //Ireretrieve lahat ng products na na-add to cart na may same customerID
        $query = "SELECT * FROM tbl_addtocart WHERE customerID = $id";
        $result = mysqli_query($conn, $query);

        //Para lang to sa pagdidisplay ng bilang ng products pero di nakaka-apekto ito sa database
        $productNum = 1;
        //Pag-iistore ng total amount
        $totalAmount = 0;

        //Pagdeclare ng array para makuha lahat ng nasa tbl_addtocart tapos ichecheck out at maiinsert sa tbl_order at tbl_orderitems
        $addedToCartItems = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $productName = $row['productName'];
                $quantity = $row['quantity'];
                $subtotal = $row['subTotal'];
                $cartID = $row['cartID'];

                //Pagset ng associative array na magagamit sa pag-iinsert ng order pag nagcheck-out
                $addedToCartItems[] = array(
                    'productName' => $productName,
                    'quantity' => $quantity
                );

                //Kukuhanin lahat ng subtotal at i-aadd lahat papunta sa totalAmount
                $totalAmount += $subtotal;

                //Pagdisplay ng lahat ng products na na-add to cart ng user
                //Yung cancel order, nakadelete query lang yun base dun sa cartID ng add to cart product
                echo '
                    <tr>
                        <td>' . $productNum . '</td>
                        <td>' . $productName . '</td>
                        <td>' . $quantity . ' kg</td>
                        <td>Php ' . $subtotal . '</td>
                        <td><a href="deleteitem.php?cartID='.$cartID.'"><button>Cancel Order</button></a></td>
                        </tr>
                    ';
                //Para lang to sa pagdidisplay ng number dun sa tabi, di to nakakaapekto sa database. Eto yung 'No.' na column sa table
                $productNum++;
            }
        }
        ?>
        <tr>
            <!-- Pagdidisplay ng total amount na nakuha -->
            <td colspan="5">Total Amount: Php <?php echo $totalAmount; ?></td>
        </tr>
    </table>
    <!-- Para makabalik sa buy kapag may gusto pang bilihin -->
    <a href="buy.php"><button>Back</button></a>
    
    <!-- Gumawa ng form para makapag-set ng button para sa pagche-check out -->
    <form action="" method="post">
        <input type="submit" value="Check Out" name="checkout">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
        if (isset($_SESSION['user_id'])) {
            $customerID = $_SESSION['user_id'];

            //Kapag may laman yung array na nilagyan doon sa line 48-51, gagana yung if statement
            if (!empty($addedToCartItems)) {

                //Iisa-isahin yung laman ng $addToCartItems para isa-isang ma-iinsert sa mga tables pagka-checkout
                foreach ($addedToCartItems as $item) {
                    //Pagset ng variables base dun sa associative array na nasa line 48-51
                    $productName = $item['productName'];
                    $quantities = $item['quantity'];

                    //Ireretrieve yung product na may kamatch sa productName tapos gagamitin para makapag-update ng quantity at babawasan base dun sa quantity ng customer
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

                //Pagiinsert sa tbl_order ng summary ng lahat ng nabili ng customer
                $insertOrderQuery = "INSERT INTO tbl_order (customerID, orderDate, totalAmount) VALUES ($customerID, NOW(), $totalAmount)";
                mysqli_query($conn, $insertOrderQuery);

                //Kukuhanin yung id ng last na insert query which ngayon ay yung orderID para magamit sa paglagay ng value sa tbl_orderitems
                $orderID = mysqli_insert_id($conn);

                //Iisa-isahin yung laman ng $addToCartItems para isa-isang ma-iinsert sa mga tables pagka-checkout
                foreach ($addedToCartItems as $item) {
                    $productName = $item['productName'];
                    $quantities = $item['quantity'];

                    $getProductQuery = "SELECT * FROM tbl_products WHERE productName = '$productName'";
                    $productResult = mysqli_query($conn, $getProductQuery);
                    $product = mysqli_fetch_assoc($productResult);


                    $subtotal = $quantities * $product['price'];

                    //Pagiinsert ng individual product sa tbl_orderitems
                    $insertOrderItemQuery = "INSERT INTO tbl_orderitems (orderID, productID, quantity, subTotal) VALUES ($orderID, {$product['productID']}, $quantities, $subtotal)";
                    $result = mysqli_query($conn, $insertOrderItemQuery);
                }
                if ($result) {
                    //Para lang to madisplay yung pangalan sa thank you for ordering 'name'
                    //Di to masyadong required
                    $sql = "SELECT * FROM tbl_customer WHERE customerID = $customerID";
                    $retrieve = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($retrieve);
                    if ($row) {
                        echo "<script>alert('Check Out Successful! Thank you for purchasing, " . $row['fullName'] . ".  The total amount is Php " . $totalAmount . "');
                        window.location.reload();</script>";

                        //Query para mabura lahat ng nasa tbl_addtocart pagkatapos icheck-out
                        $deleteCart = "TRUNCATE TABLE `vidoandmarymeatshop_db`.`tbl_addtocart`;";
                        mysqli_query($conn, $deleteCart);
                    }
                } else {
                    echo "Failed to Insert";
                }
            }else{
                echo "Cart is empty.";
            }
        }
    }
    ?>
</body>

</html>