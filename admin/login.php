<?php
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'){
    header("Location: dashboard.php");
    exit;
}
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - Alo SR Panel</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f4f8;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .login-container {
        background: #fff;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        width: 90%;
        max-width: 400px;
    }

    .login-container h2 {
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: #333;
    }

    .login-container label {
        display: block;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .login-container input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1rem;
    }

    .login-container button {
        width: 100%;
        padding: 0.6rem;
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }

    .login-container button:hover {
        background: #2563eb;
    }

    .error {
        color: red;
        text-align: center;
        margin-bottom: 1rem;
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 1.5rem;
        }
        .login-container h2 {
            font-size: 1.25rem;
        }
    }
</style>
</head>
<body>
<div class="login-container">
    <h2>Admin Login</h2>
    <?php if($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="../actions/login_action.php">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
