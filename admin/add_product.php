<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config.php");

$toast = null;

if (isset($_POST['insert_product'])) {
    $product_name        = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_category    = $_POST['product_category'];
    $product_price       = $_POST['product_price'];
    $product_status      = true;

    $product_img1 = $_FILES['product_img1']['name'];
    $product_img2 = $_FILES['product_img2']['name'];
    $temp_img1    = $_FILES['product_img1']['tmp_name'];
    $temp_img2    = $_FILES['product_img2']['tmp_name'];

    if ($product_name == '' || $product_description == '' || $product_category == '' || $product_img1 == '' || $product_img2 == '' || $product_price == '') {
        $toast = ['type' => 'error', 'msg' => 'Please fill in all fields before submitting.'];
    } else {
        move_uploaded_file($temp_img1, "./product_images/$product_img1");
        move_uploaded_file($temp_img2, "./product_images/$product_img2");

        $product_insert = "INSERT INTO `products` (product_name, product_description, category_id, product_img1, product_img2, product_price, date, status)
                           VALUES ('$product_name', '$product_description', '$product_category', '$product_img1', '$product_img2', $product_price, NOW(), '$product_status')";

        $result = mysqli_query($conn, $product_insert);
        if ($result) {
            $toast = ['type' => 'success', 'msg' => 'Product added successfully.'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product – HaggleHub Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="nav">
    <div class="logo"><h3>HaggleHub</h3></div>
    <div class="buttons">
        <button class="active-btn"><a href="add_product.php">Add Product</a></button>
        <button><a href="index.php?insert_categories">Categories</a></button>
        <button><a href="bargains.php">Bargains</a></button>
        <button><a href="../logout.php">Logout</a></button>
    </div>
</div>

<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-title">Add Product</div>
        <div class="admin-card-sub">Fill in the details below to add a new product to the store.</div>

        <?php if ($toast): ?>
        <div class="toast toast-<?php echo $toast['type']; ?>"><?php echo $toast['msg']; ?></div>
        <?php endif; ?>

        <form action="add_product.php" method="POST" enctype="multipart/form-data">

            <div class="field">
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" name="product_name" placeholder="e.g. Wireless Headphones" required>
            </div>

            <div class="field">
                <label for="product_description">Description</label>
                <textarea id="product_description" name="product_description" placeholder="Brief description of the product..." required></textarea>
            </div>

            <div class="field-grid">
                <div class="field">
                    <label for="product_category">Category</label>
                    <select id="product_category" name="product_category" required>
                        <option value="" disabled selected>Select a category</option>
                        <?php
                        $select_query = "SELECT * FROM `categories`";
                        $result_query = mysqli_query($conn, $select_query);
                        while ($row = mysqli_fetch_assoc($result_query)) {
                            echo "<option value='{$row['category_id']}'>{$row['category_title']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="field">
                    <label for="product_price">Price (₹)</label>
                    <input type="number" id="product_price" name="product_price" placeholder="0" min="1" required>
                </div>
            </div>

            <div class="field-grid">
                <div class="field">
                    <label for="product_img1">Image 1</label>
                    <input type="file" id="product_img1" name="product_img1" accept="image/*" required>
                </div>
                <div class="field">
                    <label for="product_img2">Image 2</label>
                    <input type="file" id="product_img2" name="product_img2" accept="image/*" required>
                </div>
            </div>

            <button class="submit-btn" type="submit" name="insert_product">Add Product</button>
        </form>
    </div>
</div>

</body>
</html>