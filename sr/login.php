<?php
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'sr'){
    header("Location: dashboard.php");
    exit;
}
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SR Login - Alo SR Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>SR Login</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="../actions/login_action.php">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
