<?php

include "_dbconnect.php";
$showError = false;
$showAlert = false;

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {


    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    //   Checking whether the email exists or not
    $existSql = "SELECT * from usertable WHERE `email` = '$email' AND `status` = 'active'";
    $result = mysqli_query($conn, $existSql);
    $numRows = mysqli_num_rows($result);
    if ($numRows) {
        $row = mysqli_fetch_assoc($result);
        $hashPass = $row['password'];

        if (password_verify($pass, $hashPass)) {
            session_start();
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            // $userid = $row['id'];
            setcookie('loggedin', 'true', time() + 5, '/AdvanceLoginSystem');
            if (isset($_POST['rememberme'])) {
                setcookie('emailCookie', $email, time() + 86400, "/AdvanceLoginSystem");
                setcookie('passwordCookie', $pass, time() + 86400, "/AdvanceLoginSystem");
                header("location: index.php?userid={$_SESSION['id']}");
            } else {
                header("location: index.php?userid={$_SESSION['id']}");
            }
            exit;
        } else {
            $showError = ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> Invalid Credentials!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> ';
        }
    } else {
        $showError = ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong> Email is not verified.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> ';
    }
}



?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Login </title>
    <?php include 'partials/_links.php'; ?>

    <style>
        body {
            background: #f0f2f5;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <?php

    if ($showError) {
        echo $showError;
    }
    if ($showAlert) {
        echo $showAlert;
    }
   


    ?>

    <div class="h-div" style="height: 98vh;">

        <div class="form-group">
            <h2>Login with your account</h2>
            <p>Get started with your free account</p>
            <button class="my-2 form-btn" id="g-btn"><i class="fab fa-google me-2"></i> Login Via Gmail</button>
            <br>
            <button class="form-btn" id="f-btn"><i class="fab fa-facebook-f me-2"></i> Login Via Facebook</button>
            <p class="form-p"><span>OR</span></p>
            <?Php
            session_start();
                 if(isset($_SESSION['success'])){
                    echo $_SESSION['success'];
                }
                if(isset($_SESSION['active'])){
                    echo $_SESSION['active'];
                }
                session_unset();
            ?>
           
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);  ?>" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i> </span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email ID" aria-label="phone" aria-describedby="basic-addon1" value="<?php if (isset($_COOKIE['emailCookie'])) { echo $_COOKIE['emailCookie'];} ?>" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                    <input type="password" name="pass" id="pass" class="form-control" placeholder="Create Password" aria-label="phone" aria-describedby="basic-addon1" value="<?php if(isset($_COOKIE['passwordCookie'])){ echo $_COOKIE['passwordCookie']; } ?>" required>
                </div>
                <input type="hidden" name="default-img" id="default-img" value="user-img.png">
                <div class="form-check" style="text-align: start;">
                    <input class="form-check-input" name="rememberme" type="checkbox" value="" id="flexCheckDefault">
                    <label style="text-align: start;" class="form-check-label" for="flexCheckDefault"> Remember Me
                    </label>
                </div>
                <button class="btn btn-primary my-2 w-100 btn-form"> Login Now </button>
                <p class="my-2"> Don't have an account? <a href="signup.php">Singup</a> </p>
                <p class="my-2"> Forget Password? <a href="recover_email.php">Click here</a> </p>
            </form>
        </div>

    </div>



    <?php include 'footer.php'; ?>
</body>

</html>