<?php
// শুরু session
session_start();

// যদি user already login করে থাকে, redirect according to role
if(isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if($_SESSION['role'] === 'admin'){
        header("Location: admin/dashboard.php");
        exit;
    } elseif($_SESSION['role'] === 'sr') {
        header("Location: sr/dashboard.php");
        exit;
    }
}

// অন্যথায় login page এ redirect
header("Location: sr/login.php");
exit;
