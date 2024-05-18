<?php
include 'connect.php';
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy</title>
</head>
<style>
    th,
    td {
        padding: 5px;
    }
</style>

<body>

    <?php
    //Pag ini-click ang logout button, buburahin lahat ng session variables kasama ang userID na ginagamit para ma-access ang homepage
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    //Para hindi makabalik sa buy.php kapag naka-logout
    if (!isset($_SESSION['user_id'])) {
        // If not authenticated, redirect to login.php
        header('Location: login.php');
        exit;
    }
    ?>

    <!-- Gumamit ng form para makapagpasa ng value para magamit sa paglog-out (Ang function ng paglog-out ay nasa line 26-30) -->
    <form action="" method="post">
        <input type="submit" value="Log out" name="logout">
    </form>

    <!-- Pagdidisplay ng mga products -->
    <table border="1" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>Product Name</th>
                <th style="width: 400px;">Description</th>
                <th>Product Image</th>
                <th>Price</th>
                <th>Quantity Available</th>
                <th>Quantity</th>
                <th>Add to Cart</th>
            </tr>
        </thead>
        <style>
            td img {
                width: 160px;
            }
        </style>
        <tbody>
            <?php
            //Pagdidisplay ng products arranged in alphabetical order
            $display = "SELECT * FROM tbl_products ORDER BY productName ASC;";
            $result = mysqli_query($conn, $display);

            //Kapag may laman ang query, gagana ang if statement
            if ($result) {
                //Paggamit ng while para ma-isa isa ang laman ng tbl_products
                while ($row = mysqli_fetch_assoc($result)) {
                    //Pagstore sa variables ng mga values ng column names ng table
                    $productName = $row['productName'];
                    $description = $row['description'];
                    $productImage = $row['productImage'];
                    $price = $row['price'];
                    $quantity = $row['stockQuantity'];

                    //Iba't ibang forms per product para sa pag-insert ng individual products sa add to cart table sa database
                    //Kailangan magkaroon ng array ang products[] at quantity[] kasi pare-parehas lang sila ng input type name at add to cart button (submit button) na ang rule ni PHP, kuhanin ang last value ng $productName kaya kahit ang pinili ay Giniling tapos ang last display ay Pork Tapa, Pork Tapa ang ma-iinsert sa input type value.
                    echo "<form action='' method='post'>
                                <tr>
                                    <td>" . $productName . "</td>
                                    <td>" . $description . "</td>
                                    <td><img src='../admin_ecommerce/Inventory/$productImage' alt='$productName'></td>
                                    <td>Php " . $price . "</td>
                                    <td>" . $quantity . "</td>

                                    <td>
                                    <input type='number' step='0.25' name='quantities[$productName]' value='0.25' min='0.25' max='$quantity'>
                                    <input type='hidden' name='products[]' value='" . $productName . "'>
                                    </td>

                                    <td><input type='submit' value='Add to Cart' name='addtocart'></td>

                                </tr>
                                </form>
                            ";
                            
                    /* Halimbawa
                            quantities = [
                                'Tapa'      => 1
                                'Buto-buto' => 3
                            ];*/
                }
            }
            //Para sa pag-insert, nakainsert na rin yung userID or customerID
            $id = $_SESSION['user_id'];

            //Para madisplay yung name sa taas
            $query = "SELECT * FROM tbl_customer WHERE userID = '$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            if ($result) {
                echo $row['fullName'];
            }
            ?>
        </tbody>
    </table><br>




    <?php
    //Kapag naclick na ang add to cart button sa line 95
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addtocart'])) {

        //Kapag nakapag-login ang user, matic nakaset na ang session user_id galing sa login.php line 19
        if (isset($_SESSION['user_id'])) {

            //Ini-store ang value ng session user_id para magamit sa value na need ay customerID
            $customerID = $_SESSION['user_id'];
            //Pagkuha ng mga values ng products[] at quantities[]
            $selectedProducts = $_POST['products'];
            $quantities = $_POST['quantities'];
            
            //Iisa-isahin ang lahat ng laman ng $selectedProducts
            foreach ($selectedProducts as $productName) {

                //Pagretrieve ng lahat ng laman ng tbl_products na may ka-match na $productName galing sa products[]
                $getProductQuery = "SELECT * FROM tbl_products WHERE productName = '$productName'";
                $productResult = mysqli_query($conn, $getProductQuery);
                $product = mysqli_fetch_assoc($productResult);

                //Kapag may nareturn o may laman, papasok sa if statement
                if ($product) {
                    //Para makuha ang quantity na inilagay ng customer na may corresponding $productName para matukoy kung anong product yung may quantity na yun
                    $quantity = $quantities[$productName];
                    
                    //Pagkuha ng subtotal ng isang product base sa ilan ang quantity at price
                    $subtotal = $quantity * $product['price'];
                    
                    //Paginsert ng mga nakuhang values sa input isa-isa
                    $addtocart = "INSERT INTO tbl_addtocart (productName, quantity, subtotal, customerID) VALUES ('$productName', $quantity, $subtotal, $customerID)";
                    $result = mysqli_query($conn, $addtocart);
                }else{
                    //Kapag walang laman yung tbl_products
                    echo "No products are available.";
                }
                //Kapag ang $result ay gumana sa line 156, magdidisplay ng success
                if ($result) {
                    echo "<script>alert('Products added to cart successfully!');</script>";
                } else {
                    echo "Failed to add products to cart: " . mysqli_error($conn);
                }
            }
        }
    }
    ?>
<!-- Button para makapunta sa lahat ng na-add to cart na products -->
<a href="addtocart.php"><button>Added Products to Cart</button></a>
<a href="orderdetails.php"><button>Order Details</button></a>
</body>

</html>