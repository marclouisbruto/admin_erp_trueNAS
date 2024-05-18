
<?php

include '../../connect.php';

$adminID = $_SESSION['adminID'];

    if (isset($_POST['add'])) {
        
        $productName = $_POST['productName'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stockQuantity'];
         
        
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //Kukuhanin ang markup percentage (30%)
        //New Price = Cost + (Cost / Markup percentage)
        $newPrice = $price + ($price * 0.30);


        $insertQuery = $conn->prepare("INSERT INTO tbl_products (productName, description, productImage, price, stockQuantity) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->bind_param("sssdd", $productName, $description, $targetFile, $newPrice, $stock);

        // Check if the image file is a valid image
        $check = getimagesize($_FILES["productImage"]["tmp_name"]);
        if ($check === false) {
            showError("File is not an image.");
            $uploadOk = 0;
        }

        // Check if the file already exists
        if (file_exists($targetFile)) {
            showError("Sorry, file already exists.");
            $uploadOk = 0;
        }

        // Check file size (adjust as needed)
        if ($_FILES["productImage"]["size"] > 5000000) {
            showError("Sorry, your file is too large.");
            $uploadOk = 0;
        }

        // Allow only certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats)) {
            showError("Sorry, only JPG, JPEG, PNG, and GIF files are allowed.");
            $uploadOk = 0;
        }



        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            showError("Sorry, your file was not uploaded.");
        } else {
            // If everything is ok, upload file and insert data into the database
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                if ($insertQuery->execute()) {
                    // Get the last inserted product ID
                $lastProductID = $conn->insert_id;

                // Expense tracking: Record the expense in tbl_expenses
                // Make sure to adjust this based on your session variable
                $expenseDate = date('Y-m-d H:i:s');
                $totalAmount = $price * $stock;

                $insertExpenseQuery = $conn->prepare("INSERT INTO tbl_expenses (adminID, expenseDate, productID, quantity, unitPrice, totalExpense) VALUES (?, ?, ?, ?, ?, ?)");
                $insertExpenseQuery->bind_param("isiddd", $adminID, $expenseDate, $lastProductID, $stock, $price, $totalAmount);

                if ($insertExpenseQuery->execute()) {
                    showSuccess("The new product " . $productName . " was successfully inserted into the database.");
                } else {
                    showError("Error: " . $insertExpenseQuery->error);
                }
    
                } else {
                    showError("Error: " . $insertQuery . "<br>" . $conn->error);
                }
            } else {
                showError("Sorry, there was an error uploading your file.");
            }
        }
    }
function showSuccess($message) {
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '$message',
            });
            </script>";
}
    
// Function to show error message
function showError($message) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '$message',
            });
            </script>";
}
?>