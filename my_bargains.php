<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bargains
$sql = "SELECT bargains.*, products.product_name
        FROM bargains
        JOIN products ON bargains.product_id = products.product_id
        WHERE bargains.user_id='$user_id'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/main.css">
    <title>My Bargains</title>
    <style>
        body { font-family: Arial; text-align: center; }
        table { margin: auto; border-collapse: collapse; width: 90%; }
        th, td { border-radius:8px; border: 1px solid black; padding: 10px; }
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
<div class="bargain">
<h2>My Bargains</h2>

<table>
<tr>
    <th>Product</th>
    <th>Original</th>
    <th>Offered</th>
    <th>Counter</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $row['product_name']; ?></td>
    <td>₹<?php echo $row['original_price']; ?></td>
    <td>₹<?php echo $row['offered_price']; ?></td>
    <td>₹<?php echo $row['counter_price']; ?></td>
    <td><?php echo $row['status']; ?></td>

    <td>
        <?php if ($row['status'] == "countered") { ?>
            <a href="accept_counter.php?id=<?php echo $row['id']; ?>">
                Accept Counter
            </a>
        <?php } ?>
    </td>
</tr>

<?php } ?>

</table>
</div>
</body>
</html>