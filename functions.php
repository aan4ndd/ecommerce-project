<?php 

include("config.php");



// get products 

function getProducts(){
     global $conn;
     $select_query = "select * from `products` order by rand()";
     $result_query = mysqli_query($conn, $select_query);


     while($row=mysqli_fetch_assoc($result_query)){
        $product_name=$row['product_name'];
        $product_description=$row['product_description'];
        $product_img=$row['product_img1'];
        $product_price=$row['product_price'];
        $product_id=$row['product_id'];

        echo "
        <a href='product_details.php?product_id=$product_id'>
            <div class='productContainer'>
                <div class='mainCard'>
                    <img src='./admin/products/$product_img' alt='' height='auto' width='300'>
                    <div class='info'>
                        <h2>$product_name</h2>
                        <p>$product_description</p>
                        <h3>₹   $product_price</h3>
                    </div>
                </div>
            </div> 
            <hr class=productHr>
            </a>
        ";
}
}

// product details 
function productDetails(){
    
    global $conn; if (isset($_GET['product_id'])) { $product_id = $_GET['product_id']; } else { echo "No product selected"; exit(); } $select_query = "select * from products where product_id='$product_id'"; $result_query = mysqli_query($conn, $select_query); $row = mysqli_fetch_assoc($result_query); $product_name=$row['product_name']; $product_description=$row['product_description']; $product_img=$row['product_img1']; $product_price=$row['product_price']; $product_id=$row['product_id']; echo" <div class='imgSec'> <img src='./admin/products/$product_img' alt='' height='auto' width='500px'> </div> <div class='detSec'> <h1> $product_name </h1> <p> $product_description </p> </div> <div class='buySec'> <h1>₹$product_price</h1> <p>in Stock</p> <form method='POST'> <input type='hidden' name='product_id' value='$product_id'> <button class='buyBtn' type='submit' name='add_to_cart'>Add to Cart</button> </form> <button class='bargainBtn'><a href='bargain.php?product_id=$product_id'> Bargain Price </a> </button> "; 
}
function getProductsGrid(){
    global $conn;

    $select_query = "SELECT * FROM products LIMIT 16";
    $result_query = mysqli_query($conn, $select_query);

    echo "<div class='productsGrid'>"; // grid wrapper

    while($row = mysqli_fetch_assoc($result_query)){
        $product_id = $row['product_id'];
        $product_name = htmlspecialchars($row['product_name']);
        $product_description = htmlspecialchars($row['product_description']);
        $product_img = $row['product_img1'];
        $product_price = $row['product_price'];

        $product_description = substr($product_description, 0, 60) . "...";

        echo "
        <a href='product_details.php?product_id=$product_id' class='productCard'>
            <div class='card'>
                <img src='./admin/products/$product_img' width='100%'>
                <div class='info'>
                    <h3>$product_name</h3>
                    <p>$product_description</p>
                    <h4>₹ $product_price</h4>
                </div>
            </div>
        </a>
        ";
    }

    echo "</div>"; // close grid
}
?>