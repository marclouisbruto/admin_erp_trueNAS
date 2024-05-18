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
    </script>
    <title>Dashboard</title>
</head>

<body>
    <section id="leftbar">
        <a href="#" class="logo"><img src="../../LogIn_SignUp_Admin/Images/mira.png">Vido and Mira Meatshop</a>
        <ul class="side-menu">
            <li class=""><a href="../Dashboard/dashboard.php"><i class='bx bxs-dashboard icon'></i>Dashboard</i></a></li>
            <li class="each">
                <a href="finance.php" class='current'><i class='bx bxs-wallet-alt icon'></i>Finance<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up'></i></a>
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
    <div id="title-live">
        <h2>INCOME STATEMENT</h2>
    </div>



    <div class="income-wrapper">

        <div id="total-sales">
            Total Sales:
            <div id="value-sales"> <?php echo number_format($row1['totalSales'], 2); ?></div>
        </div><br>


        <div id="total-expense">
            Total Expense: <div id="minus"></div>
            <div id="value-expense">
                <?php echo number_format($row2['totalExpense'], 2); ?>
            </div>
        </div><br><br>


        <div id="net-income">
            <h3>
                Total Net Income:
                <div id="value-income">Php <?php echo number_format($row1['totalSales'] - $row2['totalExpense'], 2); ?></div>
            </h3>
        </div>
        <div id="total"></div>

    </div>

    <div id="title-live">
        <h2 style="margin-top: 30px;">Total Sales, Total Expense, and Net Income per Product</h2>
    </div>

    <div class="financetable">
        <table border="1">
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Total Sales</th>
                <th>Total Expense</th>
                <th>Net Income per Product</th>
            </tr>
            <?php
            $totalSalesperProduct = "SELECT
            tbl_products.productID,
            tbl_products.productName,
            COALESCE(SUM(tbl_orderitems.subTotal), 0) AS totalSubTotal
            FROM
                tbl_orderitems
                JOIN tbl_products ON tbl_products.productID = tbl_orderitems.productID
            GROUP BY
                tbl_products.productID, tbl_products.productName
            ORDER BY
                tbl_products.productID";
            $result1 = mysqli_query($conn, $totalSalesperProduct);


            $totalExpensePerProduct = "SELECT
            tbl_products.productID,
            tbl_products.productName,
            COALESCE(SUM(tbl_expenses.totalExpense), 0) AS totalExpense
            FROM
                tbl_expenses
                JOIN tbl_products ON tbl_products.productID = tbl_expenses.productID
            GROUP BY
                tbl_products.productID, tbl_products.productName
            ORDER BY
                tbl_products.productID";
            $result2 = mysqli_query($conn, $totalExpensePerProduct);



            if ($result1 && $result2) {
                while ($row1 = mysqli_fetch_assoc($result1)) {
                    $row2 = mysqli_fetch_assoc($result2);

                    $productID = $row1['productID'];
                    $productName = $row1['productName'];
                    $totalExpense = $row2['totalExpense'];
                    $totalSubTotal = $row1['totalSubTotal'];

                    echo "
                    <tr>
                        <td>" . $productID . "</td>
                        <td>" . $productName . "</td>
                        <td>Php " . number_format($totalSubTotal, 2) . "</td>
                        <td>Php " . number_format($totalExpense, 2) . "</td>
                        <td>Php " . number_format($totalSubTotal - $totalExpense, 2) . "</td>
                    </tr>
                    ";
                }
            }
            ?>
        </table>
    </div>


    
    <script src="../boardd.js"></script>


</body>

</html>