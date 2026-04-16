<?php
session_start();
include "config.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// REMOVE ITEM
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];

    $delete = "DELETE FROM cart 
               WHERE user_id='$user_id' AND product_id='$product_id'";
    mysqli_query($conn, $delete);

    header("Location: cart.php");
    exit();
}

// FETCH CART ITEMS
$sql = "SELECT cart.*, products.product_name, products.product_img1 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id='$user_id'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body { font-family: Arial; }
        table { width: 80%; margin: auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        img { width: 80px; }
        .top { text-align: center; margin: 20px; }
        /* a { text-decoration: none; color: blue; } */
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

<div class="top">
    <h2>Your Cart</h2>
    <a href="products.php">← Continue Shopping</a>
</div>

<table>
<tr>
    <th>Product</th>
    <th>Image</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Action</th>
</tr>

<?php
$grand_total = 0;

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $total = $row['price'] * $row['quantity'];
        $grand_total += $total;
?>

<tr>
    <td><?php echo $row['product_name']; ?></td>

    <td>
        <img src="./admin/products/<?php echo $row['product_img1']; ?>">
    </td>

    <td>₹<?php echo $row['price']; ?></td>

    <td><?php echo $row['quantity']; ?></td>

    <td>₹<?php echo $total; ?></td>

    <td>
        <a href="cart.php?remove=<?php echo $row['product_id']; ?>">
            Remove
        </a>
    </td>
</tr>

<?php
    }
} else {
    echo "<tr><td colspan='6'>Cart is empty</td></tr>";
}
?>

<tr>
    <td colspan="4"><strong>Grand Total</strong></td>
    <td colspan="2">₹<?php echo $grand_total; ?></td>
</tr>

</table>

<div class="top">
    <br>
    <a href="checkout.php">Proceed to Checkout →</a>
</div>

</body>
</html>