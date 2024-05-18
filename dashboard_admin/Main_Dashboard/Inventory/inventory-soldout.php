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
    <title>Sold Out Products</title>
</head>
<body>
    <section id="leftbar">
        <a href="#" class="logo"><img src="../../LogIn_SignUp_Admin/Images/mira.png">Vido and Mira Meatshop</a>
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
                <a href="shipment.php"><i class='bx bxs-package icon'></i>Shipment<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up' ></i></a>
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
                <a href="inventory.php" class='current'><i class='bx bxs-bar-chart-alt-2 icon'></i>Inventory<i class='bx bx-chevron-down down'></i><i class='bx bx-chevron-up up' ></i></a>
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

    


    <div id="overlay" class="overlay">
        <div class="overlay-content">
            <!-- This space intentionally left blank -->
        </div>
    </div>



    <div id="title-live">
        <h2>SOLD OUT PRODUCTS</h2>
    </div>
    <!-- <div id="add-product-button">
        <a href="addproduct.php"><button>Add New Product</button></a>
    </div> -->
    <div class="inventory-content">
        
        <table border="1" style="text-align: center;">
            <tr>
                
                <th >Product Name</th>
                <th style="width: 300px;">Description</th>
                <th>Product Image</th>
                <th>Price with Markup Percentage (+30%)</th>
                <th >Stock Quantity</th>
                <th>Operations</th>
            </tr>
            <?php
                $query = "SELECT * FROM tbl_products WHERE stockQuantity = 0.0";
                $result = mysqli_query($conn,$query);
                $productNumber = 1;
                if($result){
                    while($row = mysqli_fetch_assoc($result)){
                        $productID = $row['productID'];
                        $productName = $row['productName'];
                        $description = $row['description'];
                        $productImage = $row['productImage'];
                        $price = $row['price'];
                        $stock = $row['stockQuantity'];

                        echo "
                        <tr>
                            <td>$productName</td>
                            <td id='desc'>$description</td>
                            <td><img src='$productImage'></td>
                            <td ><span >Php</span> $price</td>
                            <td>$stock kg</td>
                            <td>
                            <div id='inventory-soldout'>
                                <button onclick='openPopup()' data-productid=".$productID.">Add Stock</button>
                            </div>
                            </td>
                        </tr>
                        ";
                        
                    }
                }
            ?>
            <!-- <a href='addquantity.php?productID=".$productID."'><button type='submit' name='addstock'>Add Stock</button></a> -->
            
        </table>
    </div>

    
    <div id="updateProductpopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Update Quantity</h2>
            <form method="POST" action="">
                <input type="hidden" name="productID" value='<?php echo $productID; ?>'>
                <label for="stockQuantity">Stock Quantity (kg):</label>
                <input type="number" id="stockQuantity" name="stockQuantity" required>
                <button type="submit" name="updateQuantity">Update Quantity</button>
            </form>
            
        </div>
    </div>
    <?php include 'addquantity.php'; ?>


    
    <script src="../boardd.js"></script>
    <script>
        function openPopup() {
        document.getElementById('updateProductpopup').classList.add('show');
        document.getElementById('overlay').classList.add('show');
        }
        
        function closePopup() {
            document.getElementById('updateProductpopup').classList.remove('show');
            document.getElementById('overlay').classList.remove('show');
        }
    </script>
</body>
</html>