<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$bargain_id = $_GET['id'];

// Get bargain
$sql = "SELECT * FROM bargains WHERE id='$bargain_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$product_id = $row['product_id'];
$counter_price = $row['counter_price'];

// Update bargain
mysqli_query($conn, "UPDATE bargains 
                     SET status='accepted', final_price='$counter_price'
                     WHERE id='$bargain_id'");

// Add to cart
$check = "SELECT * FROM cart 
          WHERE user_id='$user_id' AND product_id='$product_id'";
$res = mysqli_query($conn, $check);

if (mysqli_num_rows($res) > 0) {
    mysqli_query($conn, "UPDATE cart 
                         SET quantity = quantity + 1
                         WHERE user_id='$user_id' AND product_id='$product_id'");
} else {
    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity, price)
                         VALUES ('$user_id', '$product_id', 1, '$counter_price')");
}

header("Location: my_bargains.php");
exit();