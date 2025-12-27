<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

$sql = "SELECT id, email, created_at FROM users WHERE role='sr' ORDER BY id DESC";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SR Users - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl">SR Users</h1>
        <a href="add_user.php"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add SR
        </a>
    </div>

    <nav class="mb-6">
        <a href="dashboard.php" class="text-blue-600 mr-4">Dashboard</a>
        <a href="orders.php" class="text-blue-600 mr-4">Orders</a>
        <a href="../actions/logout.php" class="text-red-600">Logout</a>
    </nav>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-black">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Email / Username</th>
                    <th class="px-4 py-2 text-left">Created At</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-4 py-2"><?= $row['id'] ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="px-4 py-2"><?= $row['created_at'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
