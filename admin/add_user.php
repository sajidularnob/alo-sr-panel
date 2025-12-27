<?php
require_once "../includes/auth_admin.php";
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add SR User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<form method="POST" action="../actions/add_user_action.php"
      class="bg-white p-6 rounded shadow w-96">

    <h2 class="text-2xl mb-4">Add SR User</h2>

    <?php if($error): ?>
        <p class="text-red-600 mb-3"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <label class="block mb-1">Email / Username</label>
    <input type="text" name="email" required
           class="w-full border px-3 py-2 rounded mb-4">

    <label class="block mb-1">Password</label>
    <input type="password" name="password" required
           class="w-full border px-3 py-2 rounded mb-4">

    <div class="flex justify-between">
        <a href="users.php" class="text-blue-600">← Back</a>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Create SR
        </button>
    </div>
</form>

</body>
</html>
