<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Retrieve data para sa verification
    $displayUser = "SELECT * FROM tbl_users WHERE username = '$username'";
    $result = mysqli_query($conn, $displayUser);
    
    if ($result) {
        $user = mysqli_fetch_assoc($result);

        //Kapag may na-retrieve na data at match ang password sa password hash, pasok
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            //Set ng session userID at username para magamit kahit saan
            $_SESSION['user_id'] = $user['userID'];

            //Hindi pa ito nagagamit pero just in case
            $_SESSION['username'] = $user['username'];
            
            header("Location: buy.php");
            exit();
        } else {
            echo "Incorrect username or password.";
        }
    } else {
        echo "Failed to retrieve user data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
</head>
<body>
    <h2>User Login</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login" name="login">
    </form>
    <br><br><label>Don't have an account?<a href="register.php">Sign up here</a></label>
    

</body>
</html>
