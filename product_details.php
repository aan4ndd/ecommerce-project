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
    <title>Product Details – HaggleHub</title>
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
            max-width: 1040px;
            margin: 16px auto 0;
            padding: 0 24px;
        }
        .toast-inner {
            background: #edfbf2;
            border: 1px solid #a3d9b1;
            color: #1a6b38;
            padding: 11px 16px;
            border-radius: var(--radius);
            font-size: 14px;
        }
        .toast-inner a { color: #1a6b38; font-weight: 600; margin-left: 8px; }

        .prodDetails {
            max-width: 1040px;
            margin: 32px auto;
            padding: 0 24px 80px;
            display: flex;
            align-items: flex-start;
            gap: 48px;
            flex-wrap: wrap;
        }

        .imgSec {
            flex: 1;
            min-width: 280px;
            max-width: 500px;
            margin-top: 0 !important;
        }
        .img-wrapper {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            overflow: hidden;
            background: whitesmoke;
            width: 100% !important;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .img-wrapper img {
            width: 100% !important;
            height: 100%;
            object-fit: cover;
            border-radius: 0 !important;
        }

        .detSec {
            flex: 1.2;
            min-width: 240px;
            width: auto !important;
            margin-top: 0 !important;
        }
        .detSec h1 {
            font-size: 26px;
            font-weight: 700;
            color: #111;
            margin-bottom: 10px;
        }
        .detSec p {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
        }
        .deliveryDet {
            font-size: 13px !important;
            color: var(--muted) !important;
            font-weight: 400 !important;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }
        .deliveryDet strong { color: #333; }

        .buySec {
            width: 200px !important;
            height: auto !important;
            background: whitesmoke !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            padding: 20px !important;
            text-align: left !important;
            margin-top: 0 !important;
            flex-shrink: 0;
        }
        .buySec h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--blue);
            margin-bottom: 4px;
        }
        .buySec p {
            font-size: 13px;
            color: #2e7d32;
            margin-bottom: 16px !important;
        }

        .buyBtn {
            display: block;
            width: 100%;
            padding: 10px;
            background: var(--blue) !important;
            color: #fff !important;
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 15px;
            font-weight: 600;
            border: none !important;
            border-radius: var(--radius) !important;
            cursor: pointer;
            transition: opacity 0.15s;
            margin-bottom: 8px;
        }
        .buyBtn:hover { opacity: 0.88; }

        .bargainBtn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #fff !important;
            border: 1px solid var(--blue) !important;
            border-radius: var(--radius) !important;
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: background 0.15s;
            height: auto !important;
        }
        .bargainBtn:hover { background: var(--blue-light) !important; }
        .bargainBtn a { color: var(--blue) !important; display: block; }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php"><div class="logo"><h3>HaggleHub</h3></div></a>
    <div class="search"><input type="text"></div>
    <div class="navButtons">
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="my_bargains.php">Bargains</a>
        <a href="orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<?php if (isset($added_to_cart)): ?>
<div class="toast">
    <div class="toast-inner">Added to cart. <a href="cart.php">View Cart</a></div>
</div>
<?php endif; ?>
<br><br><br>
<div class="prodDetails">
    <?php productDetails(); ?>
</div>

<div class="footer"><p>© 2025 HaggleHub. All rights reserved.</p></div>

</body>
</html>