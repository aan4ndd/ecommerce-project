<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);
$orders = [];
while ($o = mysqli_fetch_assoc($result)) {
    $items_sql = "SELECT order_items.*, products.product_name
                  FROM order_items
                  JOIN products ON order_items.product_id = products.product_id
                  WHERE order_items.order_id='{$o['order_id']}'";
    $o['items'] = [];
    $ir = mysqli_query($conn, $items_sql);
    while ($item = mysqli_fetch_assoc($ir)) $o['items'][] = $item;
    $orders[] = $o;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders – HaggleHub</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        :root {
            --blue: rgb(24, 57, 243);
            --blue-light: rgba(24, 57, 243, 0.07);
            --border: #e0e0e0;
            --radius: 12px;
            --muted: #777;
        }

        .orders-page { max-width: 860px; margin: 40px auto; padding: 0 24px 100px; }
        .page-title { font-size: 32px; font-weight: 700; margin-bottom: 6px; }
        .page-sub   { font-size: 15px; color: var(--muted); margin-bottom: 28px; }

        .order-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius); margin-bottom: 18px; overflow: hidden;
            animation: fadeUp 0.35s ease both;
        }
        .order-card:nth-child(1){animation-delay:.04s}.order-card:nth-child(2){animation-delay:.08s}
        .order-card:nth-child(3){animation-delay:.12s}
        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        .order-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 22px; background: whitesmoke;
            border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px;
        }
        .order-head-left .oid  { font-size: 14px; font-weight: 700; }
        .order-head-left .odate{ font-size: 12px; color: var(--muted); margin-top: 2px; }
        .order-head-right { display: flex; align-items: center; gap: 14px; }
        .order-total { font-size: 17px; font-weight: 700; }

        .badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .badge-pending    { background: #fff8e6; color: #9a6700; }
        .badge-processing { background: var(--blue-light); color: var(--blue); }
        .badge-delivered  { background: #edfbf2; color: #1a6b38; }
        .badge-cancelled  { background: #fef0f0; color: #a32d2d; }

        .order-item {
            display: flex; align-items: center; gap: 16px;
            padding: 13px 22px; border-bottom: 1px solid var(--border);
        }
        .order-item:last-child { border-bottom: none; }
        .oi-name { flex: 1; font-size: 14px; font-weight: 600; }
        .oi-qty  { font-size: 13px; color: var(--muted); background: whitesmoke; padding: 3px 10px; border-radius: 6px; }
        .oi-unit { font-size: 13px; color: var(--muted); }
        .oi-total{ font-size: 15px; font-weight: 700; min-width: 70px; text-align: right; }

        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state .icon { font-size: 52px; margin-bottom: 16px; }
        .empty-state h2  { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
        .empty-state p   { color: var(--muted); margin-bottom: 24px; }
        .empty-state a   { display: inline-block; padding: 12px 30px; background: var(--blue); color: #fff; border-radius: var(--radius); font-weight: 600; font-size: 15px; }
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

<div class="orders-page">
    <div class="page-title">My Orders</div>
    <div class="page-sub"><?php echo count($orders); ?> order<?php echo count($orders) != 1 ? 's' : ''; ?> placed</div>

    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order):
            $status = strtolower($order['status'] ?? 'pending');
            $badge_map = [
                'pending'    => 'badge-pending',
                'processing' => 'badge-processing',
                'delivered'  => 'badge-delivered',
                'cancelled'  => 'badge-cancelled',
            ];
            $badge_cls = $badge_map[$status] ?? 'badge-pending';
            $date = date('d M Y', strtotime($order['order_date']));
        ?>
        <div class="order-card">
            <div class="order-head">
                <div class="order-head-left">
                    <div class="oid">Order #<?php echo $order['order_id']; ?></div>
                    <div class="odate">📅 <?php echo $date; ?></div>
                </div>
                <div class="order-head-right">
                    <span class="badge <?php echo $badge_cls; ?>"><?php echo ucfirst($status); ?></span>
                    <span class="order-total">₹<?php echo $order['total_price']; ?></span>
                </div>
            </div>

            <?php foreach ($order['items'] as $item):
                $line = $item['price'] * $item['quantity'];
            ?>
            <div class="order-item">
                <div class="oi-name"><?php echo $item['product_name']; ?></div>
                <span class="oi-qty">×<?php echo $item['quantity']; ?></span>
                <span class="oi-unit">₹<?php echo $item['price']; ?> each</span>
                <div class="oi-total">₹<?php echo $line; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
    <div class="empty-state">
        <div class="icon">📦</div>
        <h2>No orders yet</h2>
        <p>Once you place an order, it'll show up here.</p>
        <a href="products.php">Start Shopping</a>
    </div>
    <?php endif; ?>
</div>

<div class="footer"><p>© 2025 HaggleHub. All rights reserved.</p></div>
</body>
</html>