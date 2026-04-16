<?php
$server = "localhost";
$db_username = "root";
$password = "";
$database = "mystore";

$conn = mysqli_connect($server, $db_username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>