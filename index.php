<?php

include '_dbconnect.php';
session_start();

$id = $_GET['userid'];

if ($_SESSION['id'] != $id) {
    header("location: index.php?userid={$_SESSION['id']}");
}

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] != true) {
    header("location: login.php");
}


if (isset($_POST['update'])) {

    if (empty($_FILES['profile-pic']['name'])) {
        $new_name = $_POST['old-image'];
    } else {

        $errors = array();

        $file_name = $_FILES['profile-pic']['name'];
        $file_size = $_FILES['profile-pic']['size'];
        $file_tmp = $_FILES['profile-pic']['tmp_name'];
        $file_type = $_FILES['profile-pic']['type'];
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

        $old_name = $_POST['old-image'];

        if (empty($errors) == true) {
            // unlink("upload/".$old_name);
            move_uploaded_file($file_tmp, $target);

        } else {
            print_r($errors);
            exit;
        }
    }

    $userid = $_GET['userid'];

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);


    $sql = "UPDATE usertable SET `username` = '$username', `email`= '$email', `phone` = '$phone', `userImg` = '$new_name' WHERE id = $userid";

    if (mysqli_query($conn, $sql)) {
        if(!empty($_FILES['profile-pic']['name'])){
            unlink("upload/" .$old_name);
        }
        setcookie('update', 'true', time() + 3, "/AdvanceLoginSystem/index.php");
        header("location: index.php?userid=$userid");
    } else {
        echo "Query Failed.";
    }
}






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo $_SESSION['username'] ?></title>

    <?php include 'partials/_links.php'; ?>

    <style>
        body {
            background: #f0f2f5;
        }

        .container {
            background-color: aliceblue;
            height: auto;
            width: 900px;
            margin: 0 auto;
            padding: 10px;
        }

        .user-img {
            height: 200px;
            width: 200px;
        }

        .user-detail {
            height: auto;
            /* border: 2px solid black; */
            padding: 7px;
            background: #fff;
        }

        .cover-img {
            width: 100%;
            margin: 0 auto;
            height: 200px;
        }

        .input-group {
            width: 60%;
            margin: 0 auto;
        }

        .wel-form {
            margin: 0 auto;
        }
    </style>

</head>

<body>

    <?php include 'header.php';




    ?>

    <?php
    if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == true) {
        echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You are loggedin with ' . $_SESSION['username'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> ';
    }
    if (isset($_COOKIE['update']) && $_COOKIE['update'] == true) {
        echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your data has been successfully updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> ';
    }
    ?>
    <div class="h-div">

        <div class="container my-5">
            <div class="row">
                <div class="col-md-3">
                    <div class="user-image">
                        <?php
                        $userid = $_GET['userid'];
                        $sql = "SELECT * FROM usertable WHERE id = $userid";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);

                        
                        if(empty($row['userImg'])){
                            echo '<img class="user-img" src="img/user-img.png">';
                        }else{
                            echo '<img class="user-img" src="upload/'. $row['userImg'] .' " alt="user-image">';
                        }
                        
                        ?>
                            
                    </div>
                    <h3 class="my-2">Profile Picture</h3>
                    <h4>Your Details:</h4>
                    <p>Username: <?php echo $row['username'] ?></p>
                    <p>Email: <?php echo $row['email'] ?></p>
                    <p>Phone Number: <?php echo $row['phone'] ?></p>
                    <a href="changepassword.php?userid=<?php echo $userid ?>" class="btn btn-primary my-2">Change Password</a>
                    <a href="logout.php" class="btn btn-primary my-2" onClick="javascript: return confirm('Are you sure want to Logout.');">Logout</a>

                </div>
                <div class="col-md-9">
                    <div class="user-detail">
                        <img class="cover-img" src="upload/download.jpg" alt="">

                        <div class="wel-form">
                            <h3 class="my-3" style="text-align: center;">Edit Your details</h3>
                            <form method="POST" action="index.php?userid=<?php echo $userid ?>" enctype="multipart/form-data">
                              <div class="input-group my-2">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i> </span>
                                    <input type="text" name="username" id="username" class="form-control " placeholder="Username" aria-label="username" value="<?php echo $row['username'] ?>" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group my-2">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i> </span>
                                    <input type="email" name="email" id="email" class="form-control " placeholder="Email" aria-label="username" value="<?php echo $row['email'] ?>" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group my-2">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone-alt"></i> </span>
                                    <input type="number" name="phone" id="phone" class="form-control " placeholder="Phone Number" aria-label="username" value="<?php echo $row['phone'] ?>" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group my-2">
                                    Change profile pic: <input type="file" name="profile-pic" id="profile-pic" class="my-2">
                                    <input type="hidden" id="old-image" name="old-image" value="<?php echo $row['userImg'] ?>">
                                    <button style="margin: 0 auto; border-radius: 5px;" class="btn btn-primary my-2 w-75" name="update">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>









    <?php include 'footer.php'; ?>

</body>

</html>