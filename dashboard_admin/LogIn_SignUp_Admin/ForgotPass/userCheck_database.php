<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vidoandmarymeatshop_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve values from the login form
$username = $_POST['username'];
$newPass = $_POST['newPass'];
$conPass = $_POST['conPass'];



// Query to check if the username and password match
$sql = "SELECT * FROM tbl_users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    if ($newPass == $conPass){
        $newPass1 = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
        $sqll = "UPDATE tbl_users SET password = '$newPass1' WHERE username = '$username'";
        $resultt = $conn->query($sqll);
            if ($resultt) {
                echo json_encode(['success' => true]);
            } 
            else {
                echo json_encode(['success' => false]);
            }
    }
    else{
        echo json_encode(['try' => false]);
    }
} 
else {
    echo json_encode(['check' => false]);
}

// Close the database connection
$conn->close();
?>
