<?php
session_start();
include "config.php";

$user_id = $_SESSION['user_id'];

$product_id = $_POST['product_id'];
$original = $_POST['original_price'];
$offer = $_POST['offer_price'];

$status = "";
$counter = 0;
$final_price = 0;

// LOGIC
if ($offer >= $original * 0.9) {
    $status = "accepted";
    $final_price = $offer;
}
elseif ($offer >= $original * 0.7) {
    $status = "countered";
    $counter = $original * 0.9;
}
else {
    $status = "rejected";
}

// SAVE TO DATABASE
$sql = "INSERT INTO bargains 
(user_id, product_id, original_price, offered_price, counter_price, final_price, status)
VALUES ('$user_id', '$product_id', '$original', '$offer', '$counter', '$final_price', '$status')";

mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
      <div class="nav">
        <a href="index.php"><div class="logo"><h3>HaggleHub</h3> </div></a>
      <div class="navButtons">
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="my_bargains.php">Bargains</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div> 
<div class="bargainProcess">
    <?php
    echo "<h2>Result</h2>";

if ($status == "accepted") {
    echo "Offer Accepted at ₹$offer";
}
elseif ($status == "countered") {
    echo "Counter Offer: ₹$counter";
}
else {
    echo "Offer Rejected";
}
?>
</div>
</body>
</html>

