<?php 
  session_start();
  if (!isset($_SESSION['adminID'])) {
    // If not authenticated, redirect to login.php
    header('Location: ../../LogIn_SignUp_Admin/Login/login.php');
    exit;
}          
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../boardd.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        function confirmLogout(adminID) {
        Swal.fire({
            title: 'Are you sure you want to log out?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../logout.php?adminID=' + adminID;
            }
        });
    }

    function reloadPage() {
        window.location.reload(true); // Pass true to force a reload from the server and not from the cache
    }
    </script>
    
    <title>Dashboard</title>
</head>
<body>
    <section id="leftbar">
        <a href="#" class="logo"><img src="../../LogIn_SignUp_Admin/Images/mira.png" alt="">Vido and Mira Meatshop</a>
        <ul class="side-menu">
            <li class=""><a href="../Dashboard/dashboard.php" ><i class='bx bxs-dashboard icon'></i>Dashboard</i></a></li>       
            <li class="each">
                <a href="finance.php"><i class='bx bxs-wallet-alt icon'></i>Finance<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up' ></i></a>
                <ul class="dropdown">
                    <li><a href="../Finance/finance-income.php">Income Statement</a></li>
                    <!-- <li><a href="../Finance/finance-withdraw.php">Withdrawal</a></li> -->
                </ul>
            </li>
            <li class="each">
                <a href="shipment.php" class='current'><i class='bx bxs-package icon'></i>Shipment<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up' ></i></a>
                <ul class="dropdown">
                    <li><a href="../Supply_Chain/shipment-toship.php">To Ship</a></li>
                    <li><a href="../Supply_Chain/shipment-shipping.php">Shipping</a></li>
                    <li><a href="../Supply_Chain/shipment-delivered.php">Delivered</a></li>
                </ul>
            </li>
            <li class="each">
                <a href="order.php"><i class='bx bxs-cart-download icon'></i>Order<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up' ></i></a>
                <ul class="dropdown">
                    <li><a href="../Order/order-unpaid.php">Unpaid</a></li>
                    <li><a href="../Order/order-complete.php">Completed</a></li>
                    <!-- <li><a href="../Order/order-canceled.php">Canceled</a></li> -->
                </ul>
            </li>
            <li class="each">
                <a href="inventory.php"><i class='bx bxs-bar-chart-alt-2 icon'></i>Inventory<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up' ></i></a>
                <ul class="dropdown">
                    <li><a href="../Inventory/inventory-live.php">Live</a></li>
                    <li><a href="../Inventory/inventory-soldout.php">Sold Out</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <?php 
                include '../../connect.php';
                
                $adminID = $_SESSION['adminID'];
            $admin = "SELECT fullName FROM tbl_customer WHERE userID = $adminID";
            $result = mysqli_query($conn,$admin);
            $row = mysqli_fetch_assoc($result);
        ?>
        <div id="admin">
            <div class="admin-details">
                <div id="online"></div>
                <p id="admin-name">
                    <?php echo $row['fullName']; ?>
                </p>
            </div>
            <div class="logout">
                <button type="submit" onclick="confirmLogout(<?php echo $adminID; ?>)">Log Out</button>
            </div>
        </div>


    <div id="title-live">
        <h2>TO SHIP ORDERS</h2>
    </div>


    <div class="order-content">
        <table>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $adminID = $_SESSION['adminID'];

            if (isset($adminID)) {
                $query = "SELECT
                tbl_order.*,
                tbl_products.productName,
                tbl_orderitems.*
                FROM
                    tbl_products
                JOIN tbl_orderitems ON tbl_products.productID = tbl_orderitems.productID
                JOIN tbl_order ON tbl_orderitems.orderID = tbl_order.orderID
                WHERE tbl_order.status = 'To Ship'
                ORDER BY
                    tbl_order.orderID ASC
                    ";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $currentOrderID = null;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $orderID = $row['orderID'];
                        $productName = $row['productName'];
                        $quantity = $row['quantity'];
                        $subtotal = $row['subTotal'];
                        $status = $row['status'];

                        $formattedDatetime = date('m-d-Y H:i:s', strtotime($row['orderDate']));
                        // Display order information only if it's a new order
                        if ($currentOrderID !== $orderID) {
                            echo "
                                <tr><td colspan='5' id='order-details'>Order ID: $orderID &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; Order Date: " . $formattedDatetime . " &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; Status: $status  
                            ";
                            $currentOrderID = $orderID; // Update the current order ID
                                echo "<form action='' method='post'>
                                    <input type='submit' value='Ship Order' name='shiporder_$orderID' id='input-generate''>
                                </form>";
                            echo "</td></tr>";
                        }
                        if (isset($_POST["shiporder_$orderID"])) {
                            $updateToShipping = "UPDATE tbl_order SET status = 'Shipping' WHERE tbl_order.orderID = $currentOrderID;";
                            mysqli_query($conn, $updateToShipping);
                            echo '<script>reloadPage();</script>';
                        }
                        echo "
                        <tr>
                            <td>" . $productName . "</td>
                            <td>" . $quantity . "kg</td>
                            <td>Php " . number_format($subtotal,2) . "</td>
                        </tr>
                        ";
                    }
                }
            }

            ?>

        </table>
    </div>
    
    <div class="download-button" style="margin-left: 100px;">
            <button onclick="window.location.href='generate-report-ship.php'">Download PDF Report</button>
        </div>

            
    <script src="../boardd.js"></script>

</body>
</html>