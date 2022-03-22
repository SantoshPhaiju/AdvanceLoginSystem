<?php

include 'header.php';
session_start();
$id = $_GET['userid'];
include '_dbconnect.php';
include 'partials/_links.php';

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] != true) {
    header("location: login.php");
}

if ($_SESSION['id'] != $id) {
    header("location: changepassword.php?userid={$_SESSION['id']}");
}

if (isset($_POST['recover'])) {
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    $oldpass = $_POST['oldpass'];

    $userid = $_GET['userid'];
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    
    $sql = "SELECT * FROM usertable WHERE `id` = '$userid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $hashPass = $row['password'];

    if ($result) {
        if(password_verify($oldpass, $hashPass)){
            if ($pass == $cpass) {
                $updateSql = "UPDATE usertable SET `password` = '$hash'";
                $result1 = mysqli_query($conn, $updateSql);
                
                if ($result1) {
                    $_SESSION['success'] = "<p class='bg-success text-white p-1'> Your password has been changed. Now you can continue with Login. </p>";
                    header("location: login.php");
                }
            } else {
                $_SESSION['error'] = "<p class='bg-warning text-white p-1'> Passwords you enterd do no match </p>";
            }
        }else {
            $_SESSION['error'] = "<p class='bg-warning text-white p-1'> Old password not matched. </p>";
        }
    }
}
?>
 


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <style>
        body {
            background-color: #f0f2f5;
        }
        
        .container {
            margin-top: 80px;
        }
    </style>
</head>

<body>

    <div class="h-div">
        <div class="container">
            <div class="form-group">
                <h2>Change Password</h2>
                <p>Fill the data correctly</p>
                <?php
                if(isset($_SESSION['error'])){
                    echo $_SESSION['error'];
                }
                $userid = $_GET['userid'];
                ?>

                <form action="changepassword.php?userid=<?php echo $userid ?>" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                        <input type="password" name="oldpass" id="oldpass" class="form-control" placeholder="Old Password" aria-label="phone" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                        <input type="password" name="pass" id="pass" class="form-control" placeholder="New Password" aria-label="phone" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                        <input type="password" name="cpass" id="cpass" class="form-control" placeholder="Confirm New Password" aria-label="phone" aria-describedby="basic-addon1" required>
                    </div>
                    <button name="recover" class="btn btn-primary my-2 w-100 btn-form"> Change Password </button>
                </form>
                <p class="my-2"> Have an account? <a href="login.php">Login here</a> </p>
            </div>

        </div>
    </div>

    <?php
include 'footer.php';

?>
</body>

</html>

