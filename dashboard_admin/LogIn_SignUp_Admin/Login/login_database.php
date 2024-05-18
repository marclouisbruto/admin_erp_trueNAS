<?php
// Replace these values with your actual database credentials
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
$password = $_POST['password'];

//Retrieve data para sa verification
$displayUser = "SELECT * FROM tbl_users WHERE username = '$username'";
$result = mysqli_query($conn, $displayUser);

// Query to check if the username and password match
if ($result) {
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        //Set ng session userID at username para magamit kahit saan
        $_SESSION['adminID'] = $user['userID'];
        echo json_encode(['success' => true]);
    } 
    else {
        echo json_encode(['success' => false]);
    }
}

// Close the database connection
$conn->close();
?>