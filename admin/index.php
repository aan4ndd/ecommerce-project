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
    
    <div class="container">
        <?php 
            if(isset($_GET['insert_categories'])){
                include('insert_categories.php');
            }
        ?>

    </div>
</body>
</html>