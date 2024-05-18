<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_meatshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve values from the login form
$username = $_POST['username'];
//Password hash para sa encryption tapos ang PASSWORD_DEFAULT ay algorithm sa encryption
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$fullName = $_POST['fullName'];
$mobileNum = $_POST['mobileNum'];
$address = $_POST['address'];


// Protect against SQL injection
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

$checkUsername = "SELECT * FROM tbl_users WHERE username = '$username'";
$check = $conn-> query($checkUsername);

    if ($check->num_rows == 0) {

        $insertUserQuery = "INSERT INTO tbl_users (username, password) VALUES ('$username', '$password')";
        $result = $conn->query($insertUserQuery);

        //Nirereturn yung ID na auto_increment ng last query
        $userID = mysqli_insert_id($conn);

        //insert ng values para sa customer data 
        $insertCustomerQuery = "INSERT INTO tbl_customer (userID, fullName, mobileNum, address) VALUES ($userID, '$fullName', $mobileNum, '$address')";
        mysqli_query($conn, $insertCustomerQuery);
        


            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
    }
    else{
        echo json_encode(['check' => true]);
    }


// Close the database connection
$conn->close();
?>
