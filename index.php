<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login.php");
    exit();
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
         <div class="hero">
            <div class="left">
                
                <h1>Welcome to <br>HaggleHub, <?php echo $_SESSION['username'] ?></h1>
                <h3>Discover amazing products and decide your prices on latest
                    trends in electronics, fashion, and more!
                </h3>
                <button>Shop Now →</button>
            </div>
            <div class="right">
                <img src="img/heroimg.cms" alt="" height="350px" width="auto">
            </div>
        </div>   
        <div class="featuredProducts"></div>
</body>
</html>