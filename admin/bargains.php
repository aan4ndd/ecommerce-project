<?php
session_start();
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bargain_id = $_POST['bargain_id'];
    $action     = $_POST['action'];

    if ($action == 'accept') {
        $offered = $_POST['offered_price'];
        mysqli_query($conn, "UPDATE bargains SET status='accepted', final_price='$offered' WHERE id='$bargain_id'");

        $b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id, product_id FROM bargains WHERE id='$bargain_id'"));
        $b_user_id    = $b['user_id'];
        $b_product_id = $b['product_id'];

        $cart_check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$b_user_id' AND product_id='$b_product_id'");
        if (mysqli_num_rows($cart_check) > 0) {
            mysqli_query($conn, "UPDATE cart SET price='$offered' WHERE user_id='$b_user_id' AND product_id='$b_product_id'");
        } else {
            mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity, price) VALUES ('$b_user_id', '$b_product_id', 1, '$offered')");
        }

    } elseif ($action == 'reject') {
        mysqli_query($conn, "UPDATE bargains SET status='rejected' WHERE id='$bargain_id'");

    } elseif ($action == 'counter') {
        $counter_price = $_POST['counter_price'];
        mysqli_query($conn, "UPDATE bargains SET status='countered', counter_price='$counter_price' WHERE id='$bargain_id'");
    }

    header("location: bargains.php");
    exit();
}

$sql = "SELECT bargains.*, products.product_name, users.username
        FROM bargains
        JOIN products ON bargains.product_id = products.product_id
        JOIN users ON bargains.user_id = users.user_id
        ORDER BY bargains.id DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bargains</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .container { padding: 30px; }
        h2 { margin-bottom: 20px; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; }
        th { background: #f5f5f5; padding: 12px 14px; text-align: left; font-size: 13px; color: #555; border-bottom: 1px solid #ddd; }
        td { padding: 12px 14px; font-size: 14px; border-bottom: 1px solid #eee; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-pending  { background: #fff3cd; color: #856404; }
        .badge-accepted { background: #d1e7dd; color: #0f5132; }
        .badge-rejected { background: #f8d7da; color: #842029; }
        .badge-countered { background: #cfe2ff; color: #084298; }

        .action-form { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
        .btn { padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold; }
        .btn-accept  { background: #198754; color: white; }
        .btn-reject  { background: #dc3545; color: white; }
        .btn-counter { background: #0d6efd; color: white; }
        .btn:hover { opacity: 0.85; }

        input[type=number] { padding: 6px 8px; border: 1px solid #ccc; border-radius: 6px; width: 100px; font-size: 13px; }

        .filter-tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .filter-tabs a { padding: 7px 16px; border-radius: 20px; text-decoration: none; font-size: 13px; background: #eee; color: #333; }
        .filter-tabs a.active { background: #333; color: white; }

        .no-bargains { text-align: center; padding: 40px; color: #888; }
    </style>
</head>
<body>

<div class="nav">
    <div class="logo"><h3>HaggleHub</h3></div>
    <div class="buttons">
        <button><a href="add_product.php">Add Products</a></button>
        <button><a href="index.php?insert_categories">Insert Category</a></button>
        <button><a href="bargains.php">Bargains</a></button>
        <button><a href="../logout.php">Logout</a></button>
    </div>
</div>

<div class="container">
    <h2>Manage Bargains</h2>

    <?php
    $filter = $_GET['filter'] ?? 'all';
    $filters = ['all', 'pending', 'accepted', 'rejected', 'countered'];
    echo '<div class="filter-tabs">';
    foreach ($filters as $f) {
        $active = ($filter == $f) ? 'active' : '';
        echo "<a href='bargains.php?filter=$f' class='$active'>" . ucfirst($f) . "</a>";
    }
    echo '</div>';
    ?>

    <table>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Product</th>
            <th>Original Price</th>
            <th>Offered Price</th>
            <th>Counter Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        $count = 0;
        mysqli_data_seek($result, 0);
        while ($row = mysqli_fetch_assoc($result)) {
            if ($filter != 'all' && $row['status'] != $filter) continue;
            $count++;

            $badge_class = 'badge-' . $row['status'];
            $counter_val = $row['counter_price'] > 0 ? '₹' . $row['counter_price'] : '—';
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td>₹<?php echo $row['original_price']; ?></td>
            <td>₹<?php echo $row['offered_price']; ?></td>
            <td><?php echo $counter_val; ?></td>
            <td><span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($row['status']); ?></span></td>
            <td>
                <?php if ($row['status'] == 'pending') { ?>
                <div class="action-form">

                    <form method="POST">
                        <input type="hidden" name="bargain_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="accept">
                        <input type="hidden" name="offered_price" value="<?php echo $row['offered_price']; ?>">
                        <button class="btn btn-accept" type="submit">Accept</button>
                    </form>

                    <form method="POST">
                        <input type="hidden" name="bargain_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="reject">
                        <button class="btn btn-reject" type="submit">Reject</button>
                    </form>

                    <form method="POST" style="display:flex; gap:4px; align-items:center;">
                        <input type="hidden" name="bargain_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="counter">
                        <input type="number" name="counter_price" placeholder="₹ Price" required min="1">
                        <button class="btn btn-counter" type="submit">Counter</button>
                    </form>

                </div>
                <?php } else { ?>
                    <span style="color:#aaa; font-size:13px;">Done</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>

        <?php if ($count == 0) { ?>
        <tr><td colspan="8" class="no-bargains">No <?php echo $filter; ?> bargains found.</td></tr>
        <?php } ?>
    </table>
</div>

</body>
</html>