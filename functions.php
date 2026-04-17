<?php 

include("config.php");



// get products 

function getProducts(){
    global $conn;

    // ✅ CHECK IF CATEGORY FILTER IS ACTIVE
    if (isset($_GET['category'])) {
        $category_id = $_GET['category'];
        $select_query = "SELECT * FROM products WHERE category_id='$category_id' ORDER BY RAND()";
    } else {
        $select_query = "SELECT * FROM products ORDER BY RAND()";
    }

    $result_query = mysqli_query($conn, $select_query);

    while($row = mysqli_fetch_assoc($result_query)){
        $product_name = $row['product_name'];
        $product_description = $row['product_description'];
        $product_img = $row['product_img1'];
        $product_price = $row['product_price'];
        $product_id = $row['product_id'];
        echo "
        <a href='product_details.php?product_id=$product_id'>
            <div class='productContainer'>
                <div class='mainCard'>
                    <img src='./admin/products/$product_img' alt='' height='auto' width='300'>
                    <div class='info'>
                        <h2>$product_name</h2>
                        <p>$product_description</p>
                        <h3>₹ $product_price</h3>
                        <p>Free delivery in <strong>4 DAYS</strong></p> <br>
                            <form method='POST'>
            <input type='hidden' name='product_id' value='$product_id'>
            <button class='buyBtn' type='submit' name='add_to_cart'>Add to Cart</button>
        </form>
                        
                    </div>
                </div>
            </div> 
            <hr class='productHr'>
        </a>
        ";
    }
}

// product details 
function productDetails(){
    global $conn;

    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
    } else {
        echo "No product selected";
        exit();
    }

    $select_query = "SELECT * FROM products WHERE product_id='$product_id'";
    $result_query = mysqli_query($conn, $select_query);
    $row = mysqli_fetch_assoc($result_query);

    $product_name = $row['product_name'];
    $product_description = $row['product_description'];
    $product_img1 = $row['product_img1'];
    $product_img2 = $row['product_img2'];
    $product_price = $row['product_price'];
    $product_id = $row['product_id'];
    echo "
    <div class='imgSec'>
        <div class='img-wrapper' style='overflow:hidden; width:500px;'>

            <img id='productImage'
                 src='./admin/products/$product_img1'
                 width='500px'
                 style='transition: transform 0.5s ease-in-out;'>
        </div>

        <br>

     
    </div>

    <div class='detSec'>
        <h1>$product_name</h1>
        <p>$product_description</p> <br>
<p class='deliveryDet'>Free Delivery in <strong>4 DAYS</strong></p>

    </div>

    <div class='buySec'>
        <h1>₹$product_price</h1>
        <p>In Stock</p>

        <form method='POST'>
            <input type='hidden' name='product_id' value='$product_id'>
            <button class='buyBtn' type='submit' name='add_to_cart'>Add to Cart</button>
        </form>

        <br>

        <button class='bargainBtn'>
            <a href='bargain.php?product_id=$product_id'>Bargain Price</a>
        </button>
    </div>

    <script>
        let images = [
            './admin/products/$product_img1',
            './admin/products/$product_img2'
        ].filter(img => img && img !== './admin/products/');

        let currentIndex = 0;
        const img = document.getElementById('productImage');

        function updateSlide(direction = 1) {
            if (!img || images.length === 0) return;

            img.style.transform = 'translateX(' + (direction * -100) + '%)';

            setTimeout(() => {
                img.src = images[currentIndex];

                img.style.transition = 'none';
                img.style.transform = 'translateX(' + (direction * 100) + '%)';

                img.offsetHeight; // force reflow

                img.style.transition = 'transform 0.5s ease-in-out';
                img.style.transform = 'translateX(0)';
            }, 100);
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            updateSlide(1);
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateSlide(-1);
        }

        let autoSlide = setInterval(nextImage, 1990);

        if (img) {
            img.addEventListener('mouseenter', function () {
                clearInterval(autoSlide);
            });

            img.addEventListener('mouseleave', function () {
                autoSlide = setInterval(nextImage, 3000);
            });
        }
    </script>
    ";
}
function getProductsGrid(){
    global $conn;

    $select_query = "SELECT * FROM products order by RAND() LIMIT 8";
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