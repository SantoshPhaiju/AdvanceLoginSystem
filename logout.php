<?php

setcookie('loggedin', 'true', time() - 3600, '/AdvanceLoginSystem');
session_start();

session_unset();
session_destroy();

header("location: login.php");




?>