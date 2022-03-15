<?php
session_start();
include "_dbconnect.php";
$showError = false;
$showAlert = false;

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {


    if (!isset($_FILES['user-image']['name'])) {
        $new_name = "";
    }else{
        
        $errors = array();

        $file_name = $_FILES['user-image']['name'];
        $file_size = $_FILES['user-image']['size'];
        $file_tmp = $_FILES['user-image']['tmp_name'];
        $file_type = $_FILES['user-image']['type'];
        $file_ext = explode('.', $file_name);

        $file_ext_check = strtolower(end($file_ext));
        $extensions = array('jpg', 'png', 'jpeg');

        if (in_array($file_ext_check, $extensions) === false) {
            $errors = "File extension is not allowed.";
        }
        if ($file_size > 2017592) {
            $errors = "File size must be less than or equal to 2MB.";
        }

        $new_name = time() . "-" . basename($file_name);
        $target = "upload/" . $new_name;

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, $target);
        } else {
            print_r($errors);
            exit;
        }
    }



    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpass']);

    $hash = password_hash($pass, PASSWORD_BCRYPT);

    $token = bin2hex(random_bytes(15));

    // To check whether the email exists or not
    $existSql = "SELECT * from usertable WHERE email = '$email'";
    $result1 = mysqli_query($conn, $existSql);
    $numRows = mysqli_num_rows($result1);
    if ($numRows > 0) {
        $showError = ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Email already exists.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> ';
    } else {
        if ($pass == $cpass) {
            $sql = "INSERT INTO usertable (`username`, `email`, `phone`, `password`, `userImg`, `token`, `status`) VALUES ('$username', '$email', '$phone', '$hash', '$new_name', '$token', 'inactive')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                

              $to_email = $email;
              $subject = "Email activation.";
              $body = "Hi $username. Click here to acitvate your account
                      http://localhost/AdvanceLoginSystem/activate.php?token=$token";
              $headers = "From: santoshphaiju@gmail.com";

              if(mail($to_email, $subject, $body, $headers)){
                $_SESSION['alert'] = ' <p class="p-4 bg-success text-white"><strong>Success!</strong> Your account has been created. Please check your email to activate your account '. $email .'</p>. 
                 ';
              header("location: login.php");
              }
              else{
                  echo "Mail sending failed.";
              }

            }
        } else {
            $showError = ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Passwords do not match.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div> ';
        }
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include 'partials/_links.php'; ?>
    <title>Signup Page goes here.</title>
    <style>
        body {
            background: #f0f2f5;
        }
        div .file{
            display: block;
            text-align: justify;
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

    <div class="h-div">

        <div class="form-group">
            <h2>Create account</h2>
            <p>Get started with your free account</p>
            <button class="my-2 form-btn" id="g-btn"><i class="fab fa-google me-2"></i> Login Via Gmail</button>
            <br>
            <button class="form-btn" id="f-btn"><i class="fab fa-facebook-f me-2"></i> Login Via Facebook</button>
            <p class="form-p"><span>OR</span></p>

            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);  ?>" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i> </span>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" aria-label="username" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i> </span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email address" aria-label="phone" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone-alt"></i> </span>
                    <input type="number" name="phone" id="phone" class="form-control" placeholder="Phone Number" aria-label="phone" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                    <input type="password" name="pass" id="pass" class="form-control" placeholder="Create Password" aria-label="phone" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                    <input type="password" name="cpass" id="cpass" class="form-control" placeholder="Confirm Password" aria-label="phone" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3 file" style="display: none;">
                    <label style="text-align: justify;" for="formFile" class="form-label">Choose Profile Image:</label>
                    <input style="width: 100%;" style="visibility: hidden;" class="user-image form-control" type="file" id="formFile" name="user-image">
                    
                </div>

                <button class="btn btn-primary my-2 w-100 btn-form">Create Account</button>
                <p class="my-2"> Already have an account? <a href="login.php">Login</a> </p>
            </form>
        </div>


    </div>






    <?php include 'footer.php'; ?>

</body>

</html>