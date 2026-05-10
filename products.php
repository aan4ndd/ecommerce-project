<?php
include("config.php");
include("functions.php");
session_start();

if (isset($_POST['add_to_cart'])) {
    $user_id         = $_SESSION['user_id'];
    $post_product_id = $_POST['product_id'];

    $sql    = "Select product_price from `products` WHERE product_id='$post_product_id'";
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    $product_price = $row['product_price'];

    $sql_check    = "SELECT * FROM `cart` WHERE user_id='$user_id' AND product_id='$post_product_id'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $update = "UPDATE `cart` SET quantity = quantity + 1 WHERE user_id='$user_id' AND product_id='$post_product_id'";
        mysqli_query($conn, $update);
    } else {
        $insert = "INSERT into `cart` (user_id, product_id, quantity, price) VALUES ('$user_id', '$post_product_id', 1, '$product_price')";
        mysqli_query($conn, $insert);
    }

    $added_to_cart = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products – HaggleHub</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        :root {
            --blue: rgb(24, 57, 243);
            --blue-light: rgba(24, 57, 243, 0.06);
            --border: #e0e0e0;
            --radius: 10px;
            --muted: #777;
        }

        .toast {
            background: #edfbf2;
            border: 1px solid #a3d9b1;
            color: #1a6b38;
            padding: 10px 16px;
            font-size: 14px;
            text-align: center;
        }
        .toast a { color: #1a6b38; font-weight: 600; margin-left: 6px; }

        .productHero {
            display: flex;
            min-height: calc(100vh - 100px);
            align-items: flex-start;
        }

        .filter {
            width: 180px;
            flex-shrink: 0;
            padding: 24px 16px;
            border-right: 1px solid var(--border);
            min-height: calc(100vh - 100px);
            background: #fff;
        }
        .filter-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
            margin-bottom: 12px;
            padding-left: 4px;
        }
        .filter li {
            list-style: none;
            margin: 0;
        }
        .filter li a {
            display: block;
            padding: 7px 10px;
            font-size: 14px;
            color: #333;
            border-radius: 6px;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }
        .filter li a:hover { background: whitesmoke; color: #111; }
        .filter li a.active {
            background: var(--blue-light);
            color: var(--blue);
            font-weight: 600;
        }

        .productDisplay {
            flex: 1;
            padding: 24px 28px;
            background: whitesmoke;
        }

        .productContainer { margin: 0; }

        .mainCard {
            background: #fff;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            padding: 16px 20px !important;
            gap: 20px !important;
            margin-bottom: 12px;
            transition: box-shadow 0.2s !important;
        }
        .mainCard:hover {
            box-shadow: 0 4px 14px rgba(0,0,0,0.07) !important;
            transform: none !important;
        }

        .mainCard img {
            width: 160px !important;
            height: 160px !important;
            object-fit: cover;
            border-radius: 8px !important;
            flex-shrink: 0;
        }

        .mainCard .info h2 {
            font-size: 17px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }
        .mainCard .info p {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 6px;
        }
        .mainCard .info h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--blue);
            margin-bottom: 8px;
        }

        .mainCard .buyBtn {
            padding: 8px 18px !important;
            background: var(--blue) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 6px !important;
            font-family: "Barlow Semi Condensed", sans-serif !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            cursor: pointer;
            transition: opacity 0.15s;
            width: auto !important;
            margin: 0 !important;
        }
        .mainCard .buyBtn:hover { opacity: 0.88; }

        .productHr { display: none; }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php"><div class="logo"><h3>HaggleHub</h3></div></a>
    <div class="search"><input type="text" placeholder="Search"></div>
    <div class="navButtons">
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="my_bargains.php">Bargains</a>
        <a href="orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<?php if (isset($added_to_cart)): ?>
<div class="toast">Added to cart. <a href="cart.php">View Cart</a></div>
<?php endif; ?>

<div class="productHero">

    <div class="filter">
        <div class="filter-label">Categories</div>
        <li><a href="products.php" <?php echo !isset($_GET['category']) ? 'class="active"' : ''; ?>>All Products</a></li>

        <?php
        $select_categories = "SELECT * FROM categories";
        $result_categories = mysqli_query($conn, $select_categories);
        $current_category  = $_GET['category'] ?? null;

        while ($row_data = mysqli_fetch_assoc($result_categories)) {
            $category_id    = $row_data['category_id'];
            $category_title = $row_data['category_title'];
            $active = ($current_category == $category_id) ? 'class="active"' : '';
            echo "<li><a href='products.php?category=$category_id' $active>$category_title</a></li>";
        }
        ?>
    </div>

    <div class="productDisplay">
        <?php getProducts(); ?>
    </div>

</div>

<div class="footer"><p>© 2025 HaggleHub. All rights reserved.</p></div>

</body>
</html>