<?php
session_start();
include "config.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// FETCH ORDERS
$sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/main.css">
    <style>
    <title>My Orders</title>
    <style>
        body { font-family: Arial; text-align: center; }
        table { margin: auto; border-collapse: collapse; width: 90%; }
        th, td { border: 1px solid black; padding: 10px; }
        h2 { margin-top: 20px; }
    </style>
</head>
<body>
      <div class="nav">
        <a href="index.php"><div class="logo"><h3>HaggleHub</h3> </div></a>
        <div class="navButtons">
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>


    <div class="orders">
<h2>My Orders</h2>

<?php
if (mysqli_num_rows($result) > 0) {

    while ($order = mysqli_fetch_assoc($result)) {
        $order_id = $order['order_id'];
?>

<h3>Order ID: <?php echo $order_id; ?></h3>
<p>Status: <?php echo $order['status']; ?></p>
<p>Date: <?php echo $order['order_date']; ?></p>

<table>
<tr>
    <th>Product Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
</tr>

<?php
// FETCH ORDER ITEMS WITH PRODUCT NAMES
$sql_items = "SELECT order_items.*, products.product_name
              FROM order_items
              JOIN products ON order_items.product_id = products.product_id
              WHERE order_items.order_id='$order_id'";

$result_items = mysqli_query($conn, $sql_items);

while ($item = mysqli_fetch_assoc($result_items)) {
    $total = $item['price'] * $item['quantity'];
?>

<tr>
    <td><?php echo $item['product_name']; ?></td>
    <td>₹<?php echo $item['price']; ?></td>
    <td><?php echo $item['quantity']; ?></td>
    <td>₹<?php echo $total; ?></td>
</tr>

<?php } ?>

<tr>
    <td colspan="3"><strong>Order Total</strong></td>
    <td><strong>₹<?php echo $order['total_price']; ?></strong></td>
</tr>

</table>

<hr>

<?php
    }

} else {
    echo "<p>No orders found</p>";
}
?>
</div>


</body>
</html>