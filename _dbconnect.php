<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "loginsys";

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    echo "Connection Failed due to this error-->" . mysqli_connect_error();
}





?>