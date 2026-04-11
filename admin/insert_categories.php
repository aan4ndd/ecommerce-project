<?php
    include("../config.php");

    if(isset($_POST['insert_cat'])){ 
    $category_title = $_POST['cat_title'];

    $checkCat = "Select * from `categories` where category_title = '$category_title'";
    $resultSelect = mysqli_query($conn, $checkCat);
    $numRows = mysqli_num_rows($resultSelect);
    if($numRows > 0){
        echo "<script>alert('Category already exists')</script>";
    }
    else{
        $insert_query="insert into `categories` (category_title) values ('$category_title')";
        $result= mysqli_query($conn, $insert_query);
        if($result){
            echo "<script>alert('Category added successfully')</script>";
        }
}
    }

?>


<form action="" method="post">
    <div>
        <label for="category_title">Category</label><br>
        <input type="text" name="cat_title">
    </div>
    <div>
        <input type="submit" name="insert_cat">
    </div>
</form> 
<!-- <style>
    form{
        font-size: 30px;
        margin-top: 50px;
        display:flex;
        justify-content: center;
        align-items: center;
    }
    input{
        font-size: 30px;
    }
</style> -->