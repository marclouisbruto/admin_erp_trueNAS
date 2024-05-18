<?php

include '../../connect.php';


if(isset($_POST['updateQuantity'])){
    $productID = $_POST['productID'];
    $adminID = $_SESSION['adminID'];
    
    if(isset($productID)){
        $stockQuantity = $_POST['stockQuantity'];

        $displayProducts = "SELECT * FROM tbl_expenses WHERE productID = $productID";
        $display = mysqli_query($conn,$displayProducts);
        $row = mysqli_fetch_assoc($display);
        $price = $row['unitPrice'];

        $totalAmount = $price * $stockQuantity;

        $updateExpense = "INSERT INTO tbl_expenses (adminID, expenseDate, productID, quantity, unitPrice, totalExpense) VALUES ($adminID, NOW(), $productID, $stockQuantity, $price, $totalAmount)";
        $updateee = mysqli_query($conn,$updateExpense);
            if (!$updateee) {
                die('Error: ' . mysqli_error($conn));
            }

        $updateQuantity = "UPDATE tbl_products SET stockQuantity = $stockQuantity WHERE productID = $productID";
        $result = mysqli_query($conn,$updateQuantity);

        if($result){
            showSuccess("Successfully update quantity!");
            echo "<script>setTimeout(function() {
                window.location.href = window.location.href;
                }, 2000); // 1000 milliseconds (1 second) delay</script>";
        }else{
            showError("Failed to update quantity!");
            
        }
    }
}   


    function showSuccess($message) {
        echo "<script>
        
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '$message',
                    showConfirmButton: false,
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
                    showConfirmButton: false,
                });
                </script>";
    }

?>