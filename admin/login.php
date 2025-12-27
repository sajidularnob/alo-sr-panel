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
<title>Login - Alo SR Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

<div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">

    <!-- Company Title -->
    <h1 class="text-2xl md:text-3xl font-bold text-center text-blue-900 mb-6 border-b-2 border-blue-600 pb-2">
        Alo Industries Ltd.
    </h1>

    <!-- Login Form -->
    <h2 class="text-xl font-semibold text-center mb-4">SR/Admin-Login</h2>

    <?php if($error): ?>
        <p class="text-red-600 text-center mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="../actions/login_action.php" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium mb-1">Email:</label>
            <input type="text" id="email" name="email" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1">Password:</label>
            <input type="password" id="password" name="password" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Login
        </button>
    </form>
</div>

</body>
</html>
