<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

if (!isset($_GET['product_id'])) {
    echo "No product selected";
    exit();
}

$product_id = $_GET['product_id'];

$sql    = "SELECT * FROM products WHERE product_id='$product_id'";
$result = mysqli_query($conn, $sql);
$row    = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Product not found";
    exit();
}

$product_name  = $row['product_name'];
$product_price = $row['product_price'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bargain – <?php echo $product_name; ?></title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        :root {
            --blue: rgb(24, 57, 243);
            --blue-light: rgba(24, 57, 243, 0.06);
            --border: #e0e0e0;
            --radius: 10px;
            --muted: #777;
        }

        .bargain-page {
            min-height: calc(100vh - 160px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px 80px;
        }

        .box {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px 36px;
            width: 100%;
            max-width: 420px;
        }

        .box-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 20px;
        }

        .product-name {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }

        .original-price {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        .original-price strong {
            color: #333;
        }

        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
        .field input[type="number"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 15px;
            outline: none;
            transition: border-color 0.15s;
            background: #fafafa;
            box-sizing: border-box;
            margin: 0;
        }
        .field input[type="number"]:focus {
            border-color: var(--blue);
            background: #fff;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 5px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 11px;
            background: var(--blue);
            color: #fff;
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: opacity 0.15s;
            margin-top: 4px;
        }
        .submit-btn:hover { opacity: 0.88; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: var(--muted);
        }
        .back-link a { color: var(--blue); }
    </style>
</head>
<body>

<div class="nav">
    <a href="index.php"><div class="logo"><h3>HaggleHub</h3></div></a>
    <div class="navButtons">
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="my_bargains.php">Bargains</a>
        <a href="orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="bargain-page">
    <div class="box">
        <div class="box-label">Make an Offer</div>

        <div class="product-name"><?php echo $product_name; ?></div>
        <div class="original-price">Original price: <strong>₹<?php echo $product_price; ?></strong></div>

        <form method="POST" action="bargain_process.php">
            <input type="hidden" name="product_id"     value="<?php echo $product_id; ?>">
            <input type="hidden" name="original_price" value="<?php echo $product_price; ?>">

            <div class="field">
                <label for="offer_price">Your Offer Price</label>
                <input type="number" id="offer_price" name="offer_price"
                       placeholder="₹ Enter amount" min="1" max="<?php echo $product_price; ?>" required>
                <div class="hint">Enter a price lower than ₹<?php echo $product_price; ?>. The seller will review your offer.</div>
            </div>

            <button class="submit-btn" type="submit">Submit Offer</button>
        </form>

        <div class="back-link"><a href="product_details.php?product_id=<?php echo $product_id; ?>">← Back to Product</a></div>
    </div>
</div>

<div class="footer"><p>© 2025 HaggleHub. All rights reserved.</p></div>

</body>
</html>