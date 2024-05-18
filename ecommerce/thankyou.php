<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
</head>
<body>
    <?php
    if (isset($_GET['name']) && isset($_GET['totalAmount'])) {
        $name = htmlspecialchars($_GET['name']);
        $totalAmount = htmlspecialchars($_GET['totalAmount']);
        echo "<h1>Thank you for your purchase, $name!</h1>";
        echo "<p>The total amount is Php $totalAmount.</p>";
    } else {
        echo "<h1>Thank you for your purchase!</h1>";
    }
    ?>
    <a href="buy.php"><button>Back to Shopping</button></a>
</body>
</html>
