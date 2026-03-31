<?php
$showAlert = false;
$showError = false;
$empty = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
        include 'config.php';
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $exists = false;
   
    if(empty($username) || empty($password) || empty($cpassword)){
        $empty = true;
    }else if
    (($password == $cpassword) && $exists == false){
      $sql = "INSERT INTO users (username, password, dt) 
        VALUES ('$username', '$password', current_timestamp())";
        
$result = mysqli_query($conn, $sql);
        }     
if($result){
    $showAlert = true;
} else {
     // die("Error: " . mysqli_error($conn));
}
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
 
    <div class="hero">
        <div class="signupForm">
            <div class="leftContainer">
            <div class="logo">Placeholder</div>
            <h2>Sign up to continue</h2>
            <form action="register.php" method="post">
                <div class="username">
                    <label for="username">Username</label><br>
                    <input type="text" id="username" name="username">
                </div>
                <div class="password">
                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="password">
                    <br>
                    <label for="cpassword">Confirm Password</label><br>
                    <input type="password" id="cpassword" name="cpassword">
                    
                </div>
                <button type="submit" class="mySubmit">Submit
                </button>
            <?php
                if($showAlert){

                echo'
                    <div class="success">
                        <p>Success! Your account has been registered</p>
                    </div> ';
                }
                if($showError){ 
                    
                echo' <div class="fail">
                        <p>Error!</p> '.$showError.'
                    </div>';
                }
                if($empty){ 
                    
                echo' <div class="empty">
                        <p>Error! invalid username or password</p>
                    </div>';
                }
                ?>
                
            </form>
            </div>
        </div>
        <div class="graphics">
            <img src="img/login.jpg">
        </div>
    </div>
    
</body>
</html>