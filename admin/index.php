<?php
include('../config.php');

// Handle category insert
$toast = null;
if (isset($_POST['insert_cat'])) {
    $category_title = $_POST['cat_title'];
    $checkCat       = "SELECT * FROM `categories` WHERE category_title='$category_title'";
    $resultSelect   = mysqli_query($conn, $checkCat);

    if (mysqli_num_rows($resultSelect) > 0) {
        $toast = ['type' => 'error', 'msg' => 'Category already exists.'];
    } else {
        $insert_query = "INSERT INTO `categories` (category_title) VALUES ('$category_title')";
        $result       = mysqli_query($conn, $insert_query);
        if ($result) {
            $toast = ['type' => 'success', 'msg' => 'Category added successfully.'];
        }
    }
}

$show_categories = isset($_GET['insert_categories']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – HaggleHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="nav">
    <div class="logo"><h3>HaggleHub</h3></div>
    <div class="buttons">
        <button><a href="add_product.php">Add Product</a></button>
        <button class="<?php echo $show_categories ? 'active-btn' : ''; ?>">
            <a href="index.php?insert_categories">Categories</a>
        </button>
        <button><a href="bargains.php">Bargains</a></button>
        <button><a href="../logout.php">Logout</a></button>
    </div>
</div>

<div class="admin-page">

    <?php if ($show_categories): ?>

    <div class="admin-card">
        <div class="admin-card-title">Insert Category</div>
        <div class="admin-card-sub">Add a new product category to the store.</div>

        <?php if ($toast): ?>
        <div class="toast toast-<?php echo $toast['type']; ?>"><?php echo $toast['msg']; ?></div>
        <?php endif; ?>

        <form action="index.php?insert_categories" method="POST">
            <div class="field">
                <label for="cat_title">Category Name</label>
                <input type="text" id="cat_title" name="cat_title" placeholder="e.g. Electronics" required>
            </div>
            <button class="submit-btn" type="submit" name="insert_cat">Add Category</button>
        </form>

        <?php
        // Show existing categories
        $existing = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_id DESC");
        if (mysqli_num_rows($existing) > 0):
        ?>
        <div class="cat-list">
            <div class="cat-list-title">Existing Categories</div>
            <?php while ($cat = mysqli_fetch_assoc($existing)): ?>
            <div class="cat-item">
                <span><?php echo $cat['category_title']; ?></span>
                <span class="cat-badge">ID <?php echo $cat['category_id']; ?></span>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php else: ?>

    <!-- DASHBOARD DEFAULT -->
    <div class="admin-card" style="text-align:center; padding: 48px 32px;">
        <div class="admin-card-title" style="font-size:26px; margin-bottom:10px;">Admin Dashboard</div>
        <div class="admin-card-sub" style="border:none; padding:0; margin-bottom: 28px;">Manage your HaggleHub store.</div>
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
            <a href="add_product.php" style="padding:10px 22px; background:var(--blue); color:#fff; border-radius:var(--radius); font-size:14px; font-weight:600;">Add Product</a>
            <a href="index.php?insert_categories" style="padding:10px 22px; background:#fff; border:1px solid var(--border); color:#333; border-radius:var(--radius); font-size:14px; font-weight:600;">Manage Categories</a>
            <a href="bargains.php" style="padding:10px 22px; background:#fff; border:1px solid var(--border); color:#333; border-radius:var(--radius); font-size:14px; font-weight:600;">View Bargains</a>
        </div>
    </div>

    <?php endif; ?>

</div>

</body>
</html>