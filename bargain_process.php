<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$original  = $_POST['original_price'];
$offer     = $_POST['offer_price'];

$sql = "INSERT INTO bargains 
        (user_id, product_id, original_price, offered_price, counter_price, final_price, status)
        VALUES ('$user_id', '$product_id', '$original', '$offer', 0, 0, 'pending')";

mysqli_query($conn, $sql);

header("location: my_bargains.php?submitted=1");
exit();
?>