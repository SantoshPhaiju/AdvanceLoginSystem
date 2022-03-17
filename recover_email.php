<?php

session_start();
include '_dbconnect.php';
 include 'header.php';
include 'partials/_links.php';



if(isset($_POST['recover'])){
    $email = $_POST['email'];

    $sql = "SELECT * FROM usertable WHERE `email` = '$email'";
    $result = mysqli_query($conn, $sql);
    $numRows = mysqli_num_rows($result);
    if($numRows > 0){
        $row = mysqli_fetch_assoc($result);
        
        $token = $row['token'];
        $username = $row['username'];

            $to_email = $email;
            $subject = "Recover your account";
            $body = "Hi, $username. Please click the link below to recover your password
                        http://localhost/AdvanceLoginSystem/changePass.php?token=$token";
            $headers = "From: santoshphaiju@gmail.com";
            
            if(mail($to_email, $subject, $body, $headers)){
                $_SESSION['recover'] = "<p class='bg-success p-1 text-white'>Check your mail at $email to recover your password.</p>";
            }else{
                echo "Mail sending failed.";
            }
        }else{
        echo "<p class='p-1 text-dark' style='text-align: center;'>The mail You entered is not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Account</title>
    <style>
        body {
            background-color: #f0f2f5;
        }
        .container{
            margin-top: 80px;
        }
    </style>
</head>

<body>
  
    <div class="h-div">
        <div class="container">
            <div class="form-group">
            <h2>Recover Account</h2>
            <p>Fill the data correctly</p>
            <?php
            if (isset($_SESSION['recover'])) {
                echo $_SESSION['recover'];
            }
            session_unset();
            ?>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);  ?>" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i> </span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email ID" aria-label="phone" aria-describedby="basic-addon1" required>
                </div>
               
                <button name="recover" class="btn btn-primary my-2 w-100 btn-form"> Recover </button>

            </form>
            <p class="my-2"> Not have an account? <a href="signup.php">Singup here</a> </p>
        </div>

        </div>
    </div>
</body>

</html>






<?php
include 'footer.php';

?>