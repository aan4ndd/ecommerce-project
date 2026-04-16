<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config.php');
include('functions.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}


 if(isset($_POST['add_to_cart'])){
    $user_id= $_SESSION['user_id'];
    $post_product_id= $_POST['product_id'];

    $sql="Select product_price from `products` WHERE product_id='$post_product_id'";
    $result=mysqli_query($conn, $sql);
    $row= mysqli_fetch_assoc($result);
    $product_price=$row['product_price'];

    // duplicate check 
    $sql_check=    "SELECT * FROM `cart`
                    WHERE user_id='$user_id' AND product_id='$post_product_id'";
    $result_check= mysqli_query($conn, $sql_check);

    if(mysqli_num_rows($result_check)>0){
        $update= "UPDATE `cart`
                  SET quantity = quantity +1 
                  WHERE user_id = '$user_id' AND product_id = '$post_product_id'";
                  mysqli_query($conn, $update);
    }else{
        $insert= "INSERT into `cart` (user_id, product_id, quantity, price)
                  VALUES ('$user_id', '$post_product_id', 1, '$product_price')";
                  mysqli_query($conn, $insert);

    }
    
    echo "<script>alert('Added To Cart')</script>";
    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="nav">
        <a href="index.php"><div class="logo"><h3>HaggleHub</h3> </div></a>
        <div class="search">
            <input type="text">
            
        </div>
        <div class="navButtons">
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
</div>

<div class="prodDetails">
 <?php 
 productDetails();
?>
 </div>

