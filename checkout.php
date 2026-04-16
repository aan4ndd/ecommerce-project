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

$user_id = $_SESSION['user_id'];

// FETCH CART ITEMS WITH PRODUCT NAME
$sql = "SELECT cart.*, products.product_name 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id='$user_id'";

$result = mysqli_query($conn, $sql);

// CALCULATE TOTAL
$grand_total = 0;
$cart_items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $grand_total += ($row['price'] * $row['quantity']);
    $cart_items[] = $row;
}

// PLACE ORDER
if (isset($_POST['place_order'])) {

    // Insert into orders table
    $insert_order = "INSERT INTO orders (user_id, total_price, order_date, status)
                     VALUES ('$user_id', '$grand_total', NOW(), 'Pending')";
    mysqli_query($conn, $insert_order);

    $order_id = mysqli_insert_id($conn);

    // Insert order items
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $insert_items = "INSERT INTO order_items (order_id, product_id, quantity, price)
                         VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        mysqli_query($conn, $insert_items);
    }

    // Clear cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

    header("Location: orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body { font-family: Arial; text-align: center; }
        table { margin: auto; border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid black; padding: 10px; }
        button { padding: 10px 20px; font-size: 16px; }

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

<div class="checkout">
<h2>Checkout</h2>

<table>
<tr>
    <th>Product Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
</tr>

<?php foreach ($cart_items as $item) {
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
    <td colspan="3"><strong>Grand Total</strong></td>
    <td><strong>₹<?php echo $grand_total; ?></strong></td>
</tr>
</table>

<br>

<form method="POST">
    <button type="submit" name="place_order">Place Order</button>
</form>

</body>
</div>
</html>