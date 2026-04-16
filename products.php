<?php
include("config.php");
include("functions.php");
session_start();

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

    <!-- main  -->

    <div class="productHero">
       <div class="filter">
         <?php 
//          if(isset($_GET['category'])){
//          $category_id = $_GET['category'];
//         $select_query = "SELECT * FROM products WHERE category_id = $category_id";
// }
        
            $select_categories="Select * from `categories`";
            $result_categories=mysqli_query($conn,$select_categories);
            while($row_data = mysqli_fetch_assoc($result_categories)){
                $category_id=$row_data['category_id'];
                $category_title=$row_data['category_title'];
                echo"
              <li> <a href='products.php?category=$category_id'>$category_title</a>
            </li>
            ";    
            }
            ?>
        </div>
    <div class="productDisplay">
     <?php 
     getProducts();        
?>
    </div>
    </div>
