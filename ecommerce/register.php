<?php
    include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    <form method="post" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Full Name:</label>
        <input type="text" name="fullName" required><br><br>

        <label>Mobile Number:</label>
        <input type="text" name="mobileNum" required><br><br>

        <label>Address:</label>
        <input type="text" name="address" required><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        //Password hash para sa encryption tapos ang PASSWORD_DEFAULT ay algorithm sa encryption
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $fullName = $_POST['fullName'];
        $mobileNum = $_POST['mobileNum'];
        $address = $_POST['address'];

        //Retrieve username para sa checking kung may ka-match
        $query = "SELECT * FROM tbl_users WHERE username = '$username'";
        $result = mysqli_query($conn,$query);
        
        //Kapag may na-fetch, hindi maglolog-in
        if(mysqli_num_rows($result) > 0){
            echo "Username ".$username." is taken.";
        } else{
            //Insert ng values para sa input sa log in
            $insertUserQuery = "INSERT INTO tbl_users (username, password) VALUES ('$username', '$password')";
            mysqli_query($conn, $insertUserQuery);

            //Nirereturn yung ID na auto_increment ng last query
            $userID = mysqli_insert_id($conn);

            //insert ng values para sa customer data 
            $insertCustomerQuery = "INSERT INTO tbl_customer (userID, fullName, mobileNum, address) VALUES ($userID, '$fullName', $mobileNum, '$address')";
            mysqli_query($conn, $insertCustomerQuery);
        

            if($insertCustomerQuery){
                header("Location: login.php");
                exit();
            }else{
                echo "Failed to insert.";
            }
        }
    }
?>

