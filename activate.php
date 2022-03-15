<?php
session_start();

include '_dbconnect.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];

    $sql1 = "SELECT * FROM usertable WHERE `token` = '$token' AND `status` = 'active'";
    $result1 = mysqli_query($conn, $sql1);

    if($result1){
        $_SESSION['alert'] =  "<p class='p-2 bg-success text-white'>Account already activated.</p>";
        header("location: login.php");
    }



    $sql = "UPDATE usertable SET `status` = 'active' WHERE `token` = '$token'";
    $result = mysqli_query($conn, $sql);


    if($result){
        if(isset($_SESSION['alert'])){
            $_SESSION['alert'] = ' <p class="p-2 bg-success text-white"><strong> Success!</strong> Your account has been activated. Now you can login.</p> ';
            header("location: login.php");
        }
    }else{
        echo "Account already verified.";
        header("location: signup.php");
    }
}


?>