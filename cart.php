<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    $delete = "DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    mysqli_query($conn, $delete);
    header("Location: cart.php");
    exit();
}

$sql = "SELECT cart.*, products.product_name, products.product_img1
        FROM cart
        JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id='$user_id'";

$result = mysqli_query($conn, $sql);
$rows = [];
$grand_total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $row['line_total'] = $row['price'] * $row['quantity'];
    $grand_total += $row['line_total'];
    $rows[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart – HaggleHub</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        :root {
            --blue: rgb(24, 57, 243);
            --blue-light: rgba(24, 57, 243, 0.07);
            --border: #e0e0e0;
            --radius: 12px;
            --text: #1a1a1a;
            --muted: #777;
        }

        .cart-page { max-width: 860px; margin: 40px auto; padding: 0 24px 100px; }

        .cart-title { font-size: 32px; font-weight: 700; margin-bottom: 6px; }
        .cart-subtitle { color: var(--muted); font-size: 15px; margin-bottom: 28px; }
        .cart-subtitle a { color: var(--blue); }

        /* ITEMS */
        .cart-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
        .cart-item {
            display: flex; align-items: center; gap: 18px;
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius); padding: 16px 20px;
            transition: box-shadow 0.2s;
            animation: fadeUp 0.3s ease both;
        }
        .cart-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); }
        .cart-item:nth-child(1){animation-delay:.04s}.cart-item:nth-child(2){animation-delay:.08s}
        .cart-item:nth-child(3){animation-delay:.12s}.cart-item:nth-child(4){animation-delay:.16s}
        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        .cart-item img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
        .item-placeholder { width: 70px; height: 70px; border-radius: 8px; background: whitesmoke; display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0; }

        .item-info { flex: 1; }
        .item-name { font-size: 16px; font-weight: 600; margin-bottom: 4px; }
        .item-qty { font-size: 13px; color: var(--muted); }

        .item-price { font-size: 16px; font-weight: 700; min-width: 80px; text-align: right; }
        .item-unit  { font-size: 12px; color: var(--muted); text-align: right; }

        .remove-btn {
            font-size: 12px; color: var(--muted);
            border: 1px solid var(--border); border-radius: 6px;
            padding: 5px 12px; transition: all 0.2s; white-space: nowrap;
        }
        .remove-btn:hover { color: #c0392b; border-color: #c0392b; background: #fff5f5; }

        /* SUMMARY */
        .summary {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius); padding: 24px 28px;
        }
        .summary-title { font-size: 18px; font-weight: 700; margin-bottom: 16px; }
        .summary-row { display: flex; justify-content: space-between; font-size: 15px; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .summary-row:last-of-type { border: none; }
        .summary-row.total { font-size: 19px; font-weight: 700; padding-top: 14px; }
        .free { color: #27ae60; font-weight: 600; }

        .checkout-btn {
            display: block; width: 100%; margin-top: 18px;
            padding: 14px; background: var(--blue); color: #fff;
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 16px; font-weight: 600; border: none;
            border-radius: var(--radius); cursor: pointer;
            text-align: center; transition: opacity 0.2s;
        }
        .checkout-btn:hover { opacity: 0.88; color: #fff; }

        /* EMPTY */
        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state .icon { font-size: 52px; margin-bottom: 16px; }
        .empty-state h2 { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
        .empty-state p { color: var(--muted); margin-bottom: 24px; }
        .empty-state a {
            display: inline-block; padding: 12px 30px;
            background: var(--blue); color: #fff;
            border-radius: var(--radius); font-weight: 600; font-size: 15px;
        }
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

<div class="cart-page">
    <div class="cart-title">Your Cart</div>
    <div class="cart-subtitle">
        <?php if (count($rows) > 0): ?>
            <?php echo count($rows); ?> item<?php echo count($rows) > 1 ? 's' : ''; ?> &nbsp;·&nbsp;
        <?php endif; ?>
        <a href="products.php">← Continue Shopping</a>
    </div>

    <?php if (count($rows) > 0): ?>
    <div class="cart-list">
        <?php foreach ($rows as $row): ?>
        <div class="cart-item">
            <?php if (!empty($row['product_img1'])): ?>
                <img src="./admin/products/<?php echo $row['product_img1']; ?>" alt="">
            <?php else: ?>
                <div class="item-placeholder">🛍️</div>
            <?php endif; ?>
            <div class="item-info">
                <div class="item-name"><?php echo $row['product_name']; ?></div>
                <div class="item-qty">Qty: <?php echo $row['quantity']; ?> &nbsp;·&nbsp; ₹<?php echo $row['price']; ?> each</div>
            </div>
            <div>
                <div class="item-unit">Subtotal</div>
                <div class="item-price">₹<?php echo $row['line_total']; ?></div>
            </div>
            <a class="remove-btn" href="cart.php?remove=<?php echo $row['product_id']; ?>">Remove</a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="summary">
        <div class="summary-title">Order Summary</div>
        <div class="summary-row"><span>Subtotal</span><span>₹<?php echo $grand_total; ?></span></div>
        <div class="summary-row"><span>Shipping</span><span class="free">Free</span></div>
        <div class="summary-row total"><span>Total</span><span>₹<?php echo $grand_total; ?></span></div>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout →</a>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="icon">🛒</div>
        <h2>Your cart is empty</h2>
        <p>Browse products and add something you like!</p>
        <a href="products.php">Browse Products</a>
    </div>
    <?php endif; ?>
</div>

<div class="footer"><p>© 2025 HaggleHub. All rights reserved.</p></div>
</body>
</html>