<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$submitted = isset($_GET['submitted']);

$sql = "SELECT bargains.*, products.product_name
        FROM bargains
        JOIN products ON bargains.product_id = products.product_id
        WHERE bargains.user_id='$user_id'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bargains – HaggleHub</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        :root {
            --blue: rgb(24, 57, 243);
            --blue-light: rgba(24, 57, 243, 0.07);
            --border: #e0e0e0;
            --radius: 12px;
            --muted: #777;
        }

        .bargains-page { max-width: 960px; margin: 40px auto; padding: 0 24px 100px; }
        .page-title { font-size: 32px; font-weight: 700; margin-bottom: 6px; }
        .page-sub   { font-size: 15px; color: var(--muted); margin-bottom: 24px; }

        .alert-success {
            display: flex; align-items: center; gap: 10px;
            background: #edfbf2; border: 1px solid #a3d9b1; color: #1a6b38;
            padding: 13px 18px; border-radius: var(--radius);
            font-size: 14px; margin-bottom: 20px;
            animation: fadeUp 0.3s ease;
        }

        .bargain-list { display: flex; flex-direction: column; gap: 12px; }
        .bargain-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: var(--radius); padding: 18px 24px;
            display: flex; align-items: center; gap: 20px;
            flex-wrap: wrap; transition: box-shadow 0.2s;
            animation: fadeUp 0.35s ease both;
        }
        .bargain-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); }
        .bargain-card:nth-child(1){animation-delay:.04s}.bargain-card:nth-child(2){animation-delay:.08s}
        .bargain-card:nth-child(3){animation-delay:.12s}.bargain-card:nth-child(4){animation-delay:.16s}
        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        .bc-info { flex: 1; min-width: 140px; }
        .bc-info .name { font-size: 16px; font-weight: 700; margin-bottom: 3px; }
        .bc-info .id   { font-size: 12px; color: var(--muted); }

        .price-row { display: flex; gap: 28px; align-items: center; }
        .price-box { text-align: center; }
        .price-box .plabel { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 3px; }
        .price-box .pval   { font-size: 17px; font-weight: 700; }
        .price-box .pval.orig { text-decoration: line-through; color: var(--muted); font-weight: 400; font-size: 14px; }
        .price-box .pval.counter { color: var(--blue); }

        .divider { width: 1px; height: 36px; background: var(--border); }

        .badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .badge-pending   { background: #fff8e6; color: #9a6700; }
        .badge-accepted  { background: #edfbf2; color: #1a6b38; }
        .badge-rejected  { background: #fef0f0; color: #a32d2d; }
        .badge-countered { background: var(--blue-light); color: var(--blue); }

        .bc-action { margin-left: auto; }
        .accept-link {
            display: inline-block; padding: 8px 16px;
            background: var(--blue); color: #fff;
            border-radius: 8px; font-size: 13px; font-weight: 600;
            transition: opacity 0.2s;
        }
        .accept-link:hover { opacity: 0.85; color: #fff; }
        .waiting { font-size: 12px; color: var(--muted); }

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

<div class="bargains-page">
    <div class="page-title">My Bargains</div>
    <div class="page-sub">Track your price offers and responses from the seller.</div>

    <?php if ($submitted): ?>
    <div class="alert-success">✅ Your offer was submitted! The seller will review it soon.</div>
    <?php endif; ?>

    <?php if (count($rows) > 0): ?>
    <div class="bargain-list">
        <?php foreach ($rows as $row):
            $status = $row['status'];
            $badges = [
                'pending'   => ['cls' => 'badge-pending',   'label' => '🟡 Pending'],
                'accepted'  => ['cls' => 'badge-accepted',  'label' => '🟢 Accepted'],
                'rejected'  => ['cls' => 'badge-rejected',  'label' => '🔴 Rejected'],
                'countered' => ['cls' => 'badge-countered', 'label' => '🔵 Countered'],
            ];
            $b = $badges[$status] ?? ['cls' => '', 'label' => ucfirst($status)];
        ?>
        <div class="bargain-card">
            <div class="bc-info">
                <div class="name"><?php echo $row['product_name']; ?></div>
                <div class="id">Offer #<?php echo $row['id']; ?></div>
            </div>

            <div class="price-row">
                <div class="price-box">
                    <div class="plabel">Original</div>
                    <div class="pval orig">₹<?php echo $row['original_price']; ?></div>
                </div>
                <div class="price-box">
                    <div class="plabel">Your Offer</div>
                    <div class="pval">₹<?php echo $row['offered_price']; ?></div>
                </div>
                <?php if ($row['counter_price'] > 0): ?>
                <div class="price-box">
                    <div class="plabel">Counter</div>
                    <div class="pval counter">₹<?php echo $row['counter_price']; ?></div>
                </div>
                <?php endif; ?>
            </div>

            <div class="divider"></div>

            <span class="badge <?php echo $b['cls']; ?>"><?php echo $b['label']; ?></span>

            <div class="bc-action">
                <?php if ($status === 'countered'): ?>
                    <a class="accept-link" href="accept_counter.php?id=<?php echo $row['id']; ?>">Accept Counter ↗</a>
                <?php elseif ($status === 'pending'): ?>
                    <span class="waiting">Awaiting seller...</span>
                <?php else: ?>
                    <span class="waiting">—</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="icon">🤝</div>
        <h2>No bargains yet</h2>
        <p>Find a product you like and make an offer!</p>
        <a href="products.php">Browse Products</a>
    </div>
    <?php endif; ?>
</div>

<div class="footer"><p>© 2025 HaggleHub. All rights reserved.</p></div>
</body>
</html>