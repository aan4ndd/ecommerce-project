<?php
include("config.php");
include("functions.php");
session_start();
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
    <title>Products</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

<!-- NAV -->
<div class="nav">
    <a href="index.php"><div class="logo"><h3>HaggleHub</h3></div></a>

    <div class="search">
        <input type="text" placeholder="Search">
    </div>

    <div class="navButtons">
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="my_bargains.php">Bargains</a>
        <a href="orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- MAIN -->
<div class="productHero">

    <!-- FILTER -->
    <div class="filter">

        <!-- ALL PRODUCTS -->
        <li>
            <a href="products.php">All Products</a>
        </li>

        <?php 
        $select_categories = "SELECT * FROM categories";
        $result_categories = mysqli_query($conn, $select_categories);

        $current_category = $_GET['category'] ?? null;

        while($row_data = mysqli_fetch_assoc($result_categories)){
            $category_id = $row_data['category_id'];
            $category_title = $row_data['category_title'];

            $active = ($current_category == $category_id) ? "active" : "";

            echo "
            <li>
                <a class='$active' href='products.php?category=$category_id'>
                    $category_title
                </a>
            </li>
            ";    
        }
        ?>

    </div>

    <!-- PRODUCTS -->
    <div class="productDisplay">
        <?php 
        getProducts();        
        ?>
    </div>

</div>
<div class="footer">
    <p>HaggleHub 2026</p>
</div>
</body>
</html>