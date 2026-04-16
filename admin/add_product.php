<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
    include("../config.php");
    if(isset($_POST['insert_product'])){
        $product_name = $_POST['product_name'];
        $product_description = $_POST['product_description'];
        $product_category = $_POST['product_category'];
        $product_price = $_POST['product_price'];
        $product_status = true;
        // img 
        $product_img1 = $_FILES['product_img1']['name'];
        $product_img2 = $_FILES['product_img2']['name'];

        // img tempname
        $temp_img1 = $_FILES['product_img1']['tmp_name'];
        $temp_img2 = $_FILES['product_img2']['tmp_name'];

        if($product_name=='' or $product_description=='' or $product_category=='' or $product_img1=='' or $product_img2==''
            or $product_price==''){
                echo  "<script>alert('Fill all the available fields')</script>";
                exit();
            }
            else{
                move_uploaded_file($temp_img1,"./product_images/$product_img1");
                move_uploaded_file($temp_img2,"./product_images/$product_img2");

                // insert 
                $product_insert="insert into `products` (product_name, product_description, category_id, product_img1, product_img2, product_price, date, status) values
                                 ('$product_name', '$product_description', '$product_category', '$product_img1', '$product_img2', $product_price, NOW(), '$product_status')";
                  
                $result = mysqli_query($conn, $product_insert);
                if($result){
                echo  "<script>alert('Product added successfully!')</script>";
                }
            }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> 
     <div class="nav">
        <div class="logo"><h3>HaggleHub</h3>
        </div>
        <div class="buttons">
           <button><a href="add_product.php">Add Products</a></button>
           <button><a href="index.php?insert_categories">Insert Category</a></button>
           <button><a href="">Manage Products</a></button>
           <button><a href="">Edit Products</a></button>
           <button><a href="">Orders</a></button>
           <button><a href="">Users</a></button>
           <button><a href="">Bargains</a></button>
        </div>
    </div>
    <h1>Insert Products</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <!-- title -->
    <div>
        <label for="product_name">Product Name</label><br>
        <input type="text" name="product_name" id="product_name" required="required">
    </div>
        <!-- description -->
        <div>
        <label for="product_description">Description</label> <br>
        <input type="text" name="product_description" id="product_description" required="required">
    </div>
        <!-- categories -->
    <div>
        <select name="product_category" id="product_category">
            <?php 
                $select_query = "Select * from `categories`";
                $result_query = mysqli_query($conn, $select_query);
                while($row = mysqli_fetch_assoc($result_query)){
                    $category_title=$row['category_title'];
                    $category_id=$row['category_id'];
                    echo "<option value='$category_id'>$category_title</option>";
                }
            // <option value="">Select a Category</option>
            // <option value="">Category1</option>
            // <option value="">Category2</option>
            // <option value="">Category3</option>
            ?>  
        </select>
    </div>
        <!-- images -->
        <div>
            <label for="product_img1">Product Image 1</label><br>
            <input type="file" name="product_img1" id="product_img1">
        </div>
        <!-- image2 -->
          <div>
            <label for="product_img2">Product Image 2</label><br>
            <input type="file" name="product_img2" id="product_img2">
        </div>
        <!-- price  -->
          <div>
            <label for="product_price">Price</label><br>
            <input type="text" name="product_price" id="product_price">
        </div>
        <!-- submit  -->
         <input type="submit" name="insert_product" value="Insert Products">

    </form>
    
</body>
</html>