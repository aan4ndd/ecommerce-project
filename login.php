<?php
$login = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'config.php';
    $username = $_POST["username"];
    $password = $_POST["password"];
    
   
    $sql =  "Select * from users where username= '$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result); 
    if($num == 1){
        $login = true;
        // $showAlert = true;
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;


        header("location: index.php");
    }
    else{
        $showError = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <div class="hero">
        <div class="signupForm">
            <div class="leftContainer">
            <div class="logo"><h3>HaggleHub</h3> </div>   
            <h2>Login to continue</h2>
            <form action="login.php" method="post">
                <div class="username">
                    <label for="username">Username</label><br>
                    <input type="text" id="username" name="username">
                </div>
                <div class="password">
                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="password">
                  
                </div>
                <button type="submit" class="mySubmit">Login
                </button>
                 <?php
                if($showAlert){

                echo'
                    <div class="success">
                        <p>logged in</p>
                    </div> ';
                }
                if($showError){ 
                    
                echo' <div class="fail">
                        <p>Account Does not exist or incorrect password</p>
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