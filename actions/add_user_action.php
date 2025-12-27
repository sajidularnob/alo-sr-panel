<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header("Location: ../admin/add_user.php?error=All fields required");
    exit;
}

// check duplicate
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: ../admin/add_user.php?error=User already exists");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (email, password, role, created_at)
     VALUES (?, ?, 'sr', NOW())"
);
$stmt->bind_param("ss", $email, $hash);

if ($stmt->execute()) {
    header("Location: ../admin/users.php");
} else {
    header("Location: ../admin/add_user.php?error=Failed to create user");
}
