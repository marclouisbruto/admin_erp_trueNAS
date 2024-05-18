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
        
        function redirectToPage(url) {
            var redirectTo = url; 
            window.location.href = redirectTo;
        }
                   
    </script>


    <title>Dashboard</title>
</head>

<body>
    <section id="leftbar">
        <a href="#" class="logo"><img src="../../LogIn_SignUp_Admin/Images/mira.png">Vido and Mira Meatshop</a>
        <ul class="side-menu">
            <li class=""><a href="dashboard.php" class='current'><i class='bx bxs-dashboard icon'></i>Dashboard</i></a></li>
            <li class="each">
                <a href="finance.php"><i class='bx bxs-wallet-alt icon'></i>Finance<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up'></i></a>
                <ul class="dropdown">
                    <li><a href="../Finance/finance-income.php">Income Statement</a></li>
                    <!-- <li><a href="../Finance/finance-withdraw.php">Withdrawal</a></li> -->
                </ul>
            </li>
            <li class="each">
                <a href="shipment.php"><i class='bx bxs-package icon'></i>Shipment<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up'></i></a>
                <ul class="dropdown">
                    <li><a href="../Supply_Chain/shipment-toship.php">To Ship</a></li>
                    <li><a href="../Supply_Chain/shipment-shipping.php">Shipping</a></li>
                    <li><a href="../Supply_Chain/shipment-delivered.php">Delivered</a></li>
                </ul>
            </li>
            <li class="each">
                <a href="order.php"><i class='bx bxs-cart-download icon'></i>Order<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up'></i></a>
                <ul class="dropdown">
                    <li><a href="../Order/order-unpaid.php">Unpaid</a></li>
                    <li><a href="../Order/order-complete.php">Completed</a></li>
                    <!-- <li><a href="../Order/order-canceled.php">Canceled</a></li> -->
                </ul>
            </li>
            <li class="each">
                <a href="inventory.php"><i class='bx bxs-bar-chart-alt-2 icon'></i>Inventory<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up'></i></a>
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
    $result = mysqli_query($conn, $admin);
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

     
        <?php
            include '../../connect.php';
        
            $displayTotalSales = "SELECT SUM(totalAmount) AS totalSales FROM tbl_order";
            $result1 = mysqli_query($conn, $displayTotalSales);
            $row1 = mysqli_fetch_assoc($result1);
        
            $displayTotalExpense = "SELECT SUM(totalExpense) AS totalExpense FROM tbl_expenses";
            $result2 = mysqli_query($conn, $displayTotalExpense);
            $row2 = mysqli_fetch_assoc($result2);
        ?>

    <main class="rightCont">
        <div id="title-overview">
            <h2>OVERVIEW</h2>
        </div>
        <div class="summary">
            <div class="order" onclick="redirectToPage('../Finance/finance-income.php')">
                <div class="title">
                    <h2>Total Income</h2><br>
                    <h1>Php <?php echo number_format($row1['totalSales'] - $row2['totalExpense'], 2); ?></h1>
                </div>
            </div>

            <div class="order" onclick="redirectToPage('../Supply_Chain/shipment-toship.php')">
                <div class="title">
                    <?php 
                        $displayTotalToShip = "SELECT COALESCE(COUNT(status), 0) AS totalToShip FROM tbl_order WHERE status = 'To Ship'";
                        $result2 = mysqli_query($conn, $displayTotalToShip);
                        $row2 = mysqli_fetch_assoc($result2);
                    ?>
                    <h2>To Ship</h2><br>
                    <h1><?php echo $row2['totalToShip']; ?> order/s</h1>
                </div>
            </div>
        </div>

        <div class="summary">
        <div class="inventory" onclick="redirectToPage('../Order/order-unpaid.php')">
                <div class="title">
                    <?php 
                        $displayUnpaidOrders = "SELECT COALESCE(COUNT(status), 0) AS totalToShip FROM tbl_order WHERE status = 'To Ship' OR status = 'Shipping'";
                        $result3 = mysqli_query($conn, $displayUnpaidOrders);
                        $row3 = mysqli_fetch_assoc($result3);
                    ?>
                    <h2>Unpaid Orders</h2><br>
                    <h1><?php echo $row3['totalToShip']; ?> order/s</h1>
                </div>
            </div>
            <div class="finance" onclick="redirectToPage('../Inventory/inventory-soldout.php')">
                <div class="title">
                    <?php
                        $displaySoldOut = "SELECT COALESCE(COUNT(productName), 0) AS totalSoldOut FROM tbl_products WHERE stockQuantity = 0.0";
                        $result4 = mysqli_query($conn,$displaySoldOut);
                        $row4 = mysqli_fetch_assoc($result4);
                    ?>
                    <h2>Sold Out Products</h2><br>
                    <h1><?php echo $row4['totalSoldOut']; ?> product/s</h1>
                </div>
            </div>
        </div>
    </main>




    <script src="../boardd.js"></script>

</body>

</html>