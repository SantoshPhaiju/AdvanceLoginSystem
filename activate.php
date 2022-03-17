<?php
session_start();

include '_dbconnect.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];

    $sql1 = "SELECT * FROM usertable WHERE `token` = '$token' AND `status` = 'active'";
    $result1 = mysqli_query($conn, $sql1);
    $numRows = mysqli_num_rows($result1);

    if($numRows > 0){
        $_SESSION['active'] =  "<p class='p-2 bg-success text-white'>Account already activated.</p>";
        header("location: login.php");
    }else{
        
        $sql = "UPDATE usertable SET `status` = 'active' WHERE `token` = '$token'";
        $result = mysqli_query($conn, $sql);

    if($result){
            $_SESSION['active'] = '<p class="p-2 bg-success text-white"><strong> Success!</strong> Your account has been activated. Now you can continue with Login.</p> ';
            
        header("location: login.php");
    }else{
        echo "Account already verified.";
        header("location: signup.php");
    }
  }
}
