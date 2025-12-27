<?php
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'){
    header("Location: dashboard.php");
    exit;
}
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Alo SR Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
     <style>
        .login-form {
            max-width: 350px;
            margin: 100px auto;
        }
        .login-form h2 {
            text-align: center;
        }
        .login-form input {
            width: 100%;
        }
        .login-form button {
            width: 100%;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="../actions/login_action.php">
        <label>Email:</label><br>
        <input type="text" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
