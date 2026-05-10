<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.*, products.product_name
        FROM cart
        JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id='$user_id'";

$result = mysqli_query($conn, $sql);

$grand_total = 0;
$cart_items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $grand_total += ($row['price'] * $row['quantity']);
    $cart_items[] = $row;
}

if (isset($_POST['place_order'])) {
    $insert_order = "INSERT INTO orders (user_id, total_price, order_date, status)
                     VALUES ('$user_id', '$grand_total', NOW(), 'Pending')";
    mysqli_query($conn, $insert_order);

    $order_id = mysqli_insert_id($conn);

    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity   = $item['quantity'];
        $price      = $item['price'];
        $insert_items = "INSERT INTO order_items (order_id, product_id, quantity, price)
                         VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        mysqli_query($conn, $insert_items);
    }

    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
    header("Location: orders.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout – HaggleHub</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        :root {
            --blue: rgb(24, 57, 243);
            --blue-light: rgba(24, 57, 243, 0.07);
            --border: #e0e0e0;
            --radius: 12px;
            --muted: #777;
        }

        .checkout-page { max-width: 780px; margin: 40px auto; padding: 0 24px 100px; }
        .page-title { font-size: 32px; font-weight: 700; margin-bottom: 6px; }
        .page-sub   { font-size: 15px; color: var(--muted); margin-bottom: 28px; }
        .page-sub a { color: var(--blue); }

        /* ORDER REVIEW */
        .order-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius); overflow: hidden; margin-bottom: 18px;
            animation: fadeUp 0.3s ease both;
        }
        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        .order-card-head {
            padding: 14px 22px; background: whitesmoke;
            border-bottom: 1px solid var(--border);
            font-size: 13px; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
        }

        .order-item {
            display: flex; align-items: center; gap: 16px;
            padding: 14px 22px; border-bottom: 1px solid var(--border);
        }
        .order-item:last-child { border-bottom: none; }
        .oi-name  { flex: 1; font-size: 15px; font-weight: 600; }
        .oi-qty   { font-size: 13px; color: var(--muted); background: whitesmoke; padding: 3px 10px; border-radius: 6px; }
        .oi-unit  { font-size: 13px; color: var(--muted); min-width: 80px; text-align: right; }
        .oi-total { font-size: 15px; font-weight: 700; min-width: 70px; text-align: right; }

        /* SUMMARY */
        .summary-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius); padding: 24px 28px;
            animation: fadeUp 0.35s ease both; animation-delay: 0.08s;
        }
        .summary-title { font-size: 18px; font-weight: 700; margin-bottom: 16px; }
        .summary-row {
            display: flex; justify-content: space-between;
            font-size: 15px; padding: 10px 0; border-bottom: 1px solid var(--border);
        }
        .summary-row:last-of-type { border: none; }
        .summary-row.total { font-size: 20px; font-weight: 700; padding-top: 14px; }
        .free { color: #27ae60; font-weight: 600; }

        .place-btn {
            display: block; width: 100%; margin-top: 20px;
            padding: 15px; background: var(--blue); color: #fff;
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 17px; font-weight: 700; border: none;
            border-radius: var(--radius); cursor: pointer;
            transition: opacity 0.2s; text-align: center;
        }
        .place-btn:hover { opacity: 0.88; }

        .secure-note {
            text-align: center; margin-top: 12px;
            font-size: 13px; color: var(--muted);
        }

        /* EMPTY */
        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state .icon { font-size: 52px; margin-bottom: 16px; }
        .empty-state h2  { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
        .empty-state p   { color: var(--muted); margin-bottom: 24px; }
        .empty-state a   {
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

<div class="checkout-page">
    <div class="page-title">Checkout</div>
    <div class="page-sub">Review your order before placing it. <a href="cart.php">← Back to Cart</a></div>

    <?php if (count($cart_items) > 0): ?>

    <div class="order-card">
        <div class="order-card-head">Order Summary — <?php echo count($cart_items); ?> item<?php echo count($cart_items) > 1 ? 's' : ''; ?></div>
        <?php foreach ($cart_items as $item):
            $line = $item['price'] * $item['quantity'];
        ?>
        <div class="order-item">
            <div class="oi-name"><?php echo $item['product_name']; ?></div>
            <span class="oi-qty">×<?php echo $item['quantity']; ?></span>
            <div class="oi-unit">₹<?php echo $item['price']; ?> each</div>
            <div class="oi-total">₹<?php echo $line; ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="summary-card">
        <div class="summary-title">Payment Details</div>
        <div class="summary-row"><span>Subtotal</span><span>₹<?php echo $grand_total; ?></span></div>
        <div class="summary-row"><span>Shipping</span><span class="free">Free</span></div>
        <div class="summary-row total"><span>Total</span><span>₹<?php echo $grand_total; ?></span></div>

        <form method="POST">
            <button class="place-btn" type="submit" name="place_order">Place Order →</button>
        </form>
        <div class="secure-note">🔒 Your order is safe and secure</div>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="icon">🛒</div>
        <h2>Your cart is empty</h2>
        <p>Add some products before checking out.</p>
        <a href="products.php">Browse Products</a>
    </div>
    <?php endif; ?>
</div>

<div class="footer"><p>© 2025 HaggleHub. All rights reserved.</p></div>
</body>
</html>