<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

// fetch orders
$sql = "
    SELECT o.id,  s.name AS shop_name, o.order_date
    FROM orders o
    JOIN users u ON u.id = o.sr_id
    JOIN shops s ON s.id = o.shop_id
    ORDER BY o.id DESC
";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Orders - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto px-6 py-8">
        <h1 class="text-3xl mb-6">All Orders</h1>
        <nav class="mb-6">
            <a href="dashboard.php" class="text-blue-600 hover:underline mr-4">Dashboard</a>
            <a href="products.php" class="text-blue-600 hover:underline mr-4">Products</a>
            <a href="users.php" class="text-blue-600 hover:underline mr-4">SR Users</a>
            <a href="../actions/logout.php" class="text-red-600 hover:underline">Logout</a>
        </nav>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-800 text-black">
                    <tr>
                        <th class="px-4 py-2 text-left">Order ID</th>

                        <th class="px-4 py-2 text-left">Shop Name</th>
                        <th class="px-4 py-2 text-left">Order Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?= $row['id'] ?></td>

                            <td class="px-4 py-2"><?= htmlspecialchars($row['shop_name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['order_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>