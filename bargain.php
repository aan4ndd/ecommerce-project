<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "config.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

// Check product_id from URL
if (!isset($_GET['product_id'])) {
    echo "No product selected";
    exit();
}

$product_id = $_GET['product_id'];

// Fetch product details
$sql = "SELECT * FROM products WHERE product_id='$product_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Check if product exists
if (!$row) {
    echo "Product not found";
    exit();
}

$product_name = $row['product_name'];
$product_price = $row['product_price'];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/main.css">
    <title>Bargain Product</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
        }
        .box {
            border-radius: 8px;
            width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid black;
        }
        input, button {
            padding: 10px;
            margin: 10px;
            width: 80%;
        }
    </style>
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
<div class="box">
    <h2>Bargain for Product</h2>

    <h3><?php echo $product_name; ?></h3>
    <p>Original Price: ₹<?php echo $product_price; ?></p>

    <form method="POST" action="bargain_process.php">
        
        <!-- Hidden data -->
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input type="hidden" name="original_price" value="<?php echo $product_price; ?>">

        <!-- User input -->
        <input type="number" name="offer_price" placeholder="Enter your offer price" required>

        <button type="submit">Submit Offer</button>
    </form>

    <br>
    <a href="product_details.php?product_id=<?php echo $product_id; ?>">← Back to Product</a>
</div>

</body>
</html>