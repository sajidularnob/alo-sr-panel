<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Products</h1>

    <nav class="mb-6">
        <a href="dashboard.php" class="text-blue-600 font-semibold hover:underline mr-4">Dashboard</a>
        <a href="orders.php" class="text-blue-600 font-semibold hover:underline mr-4">Orders</a>
        <a href="users.php" class="text-blue-600 font-semibold hover:underline mr-4">SR Users</a>
        <a href="../actions/logout.php" class="text-red-600 font-semibold hover:underline">Logout</a>
    </nav>

    <?php if($error): ?>
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Add Product</h2>
        <form method="POST" action="../actions/add_product.php" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-medium mb-1">Product Name:</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block font-medium mb-1">Price:</label>
                <input type="number" step="0.01" name="price" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block font-medium mb-1">Photo:</label>
                <input type="file" name="photo" accept="image/*" class="w-full">
            </div>

            <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded hover:bg-green-600">Add Product</button>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Product List</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Photo</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo $row['id']; ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="px-4 py-2"><?php echo number_format($row['price'], 2); ?></td>
                            <td class="px-4 py-2">
                                <?php if($row['photo']): ?>
                                    <img src="../assets/photos/products/<?php echo $row['photo']; ?>" alt="Product" class="w-16 h-16 object-contain rounded">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
