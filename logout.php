<?php

setcookie('loggedin', 'true', time() - 3600, '/AdvanceLoginSystem');
setcookie('emailCookie', $email, time() - 3600, '/AdvanceLoginSystem');
setcookie('passwordCookie', $pass, time() - 3600, '/AdvanceLoginSystem');



session_unset();
session_destroy();

header("location: login.php");




?>