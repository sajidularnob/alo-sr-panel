<?php
session_start();
require_once __DIR__ . "/../includes/db.php";

// POST values
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// simple check
if(empty($email) || empty($password)){
    header("Location: ../admin/login.php?error=Please fill all fields");
    exit;
}

// prepare statement
$stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result && $result->num_rows === 1){
    $row = $result->fetch_assoc();

    if(password_verify($password, $row['password'])){
        // login success
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];

        // redirect according to role
        if($row['role'] === 'admin'){
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../sr/dashboard.php");
        }
        exit;
    } 
}

// login failed
header("Location: " . (isset($row['role']) && $row['role'] === 'sr' ? "../sr/login.php" : "../admin/login.php") . "?error=Invalid credentials");
exit;
