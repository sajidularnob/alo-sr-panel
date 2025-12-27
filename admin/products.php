<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

$result = $conn->query("SELECT * FROM products ORDER BY name ASC");
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans min-h-screen" x-data="productEditor()">

    <!-- Navbar -->
    <nav class="bg-white shadow px-6 py-4 flex flex-wrap items-center justify-between">
        <h1 class="text-xl font-bold text-blue-900">Alo Industries Ltd.</h1>
        <div class="flex flex-wrap gap-4 mt-2 md:mt-0">
            <a href="dashboard.php" class="text-blue-600 hover:underline">Dashboard</a>
            <a href="orders.php" class="text-blue-600 hover:underline">Orders</a>
            <a href="users.php" class="text-blue-600 hover:underline">SR Users</a>
            <a href="../actions/logout.php" class="text-red-600 hover:underline">Logout</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-6">

        <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-6">Products</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center"><?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Add Product Form -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-2xl font-semibold mb-4">Add Product</h3>
            <form method="POST" action="../actions/add_product.php" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block font-medium mb-1">Product Name:</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-medium mb-1">Price:</label>
                    <input type="number" step="0.01" name="price" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-medium mb-1">Photo:</label>
                    <input type="file" name="photo" accept="image/*" class="w-full">
                </div>
                <button type="submit"
                    class="bg-green-500 text-white px-5 py-2 rounded hover:bg-green-600 transition">Add Product</button>
            </form>
        </div>

        <!-- Product List -->
        <div class="bg-white shadow rounded-lg p-6 overflow-x-auto">
            <h3 class="text-2xl font-semibold mb-4">Product List</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">SL</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Photo</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $sl = 1;
                    foreach ($products as $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?= $sl++ ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-4 py-2">৳<?= number_format($row['price'], 2) ?></td>
                            <td class="px-4 py-2">
                                <?php if ($row['photo']): ?>
                                    <img src="../assets/photos/products/<?= $row['photo'] ?>" alt="Product"
                                        class="w-16 h-16 object-contain rounded">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                <button @click="openModal(<?= htmlspecialchars(json_encode($row)) ?>)"
                                    class="text-blue-600 hover:underline">Edit</button>
                             

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Edit Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button @click="closeModal()"
                class="absolute top-3 right-3 text-gray-600 hover:text-gray-900">&times;</button>
            <h3 class="text-2xl font-semibold mb-4">Edit Product</h3>
            <form :action="'../actions/edit_product.php'" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" :value="selected.id">
                <div>
                    <label class="block font-medium mb-1">Product Name:</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-model="selected.name">
                </div>
                <div>
                    <label class="block font-medium mb-1">Price:</label>
                    <input type="number" step="0.01" name="price" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-model="selected.price">
                </div>
                <div>
                    <label class="block font-medium mb-1">Photo:</label>
                    <input type="file" name="photo" accept="image/*" class="w-full">
                    <template x-if="selected.photo">
                        <img :src="'../assets/photos/products/' + selected.photo" alt="Current Photo"
                            class="w-20 h-20 object-contain mt-2 rounded">
                    </template>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function productEditor() {
            return {
                showModal: false,
                selected: {},
                openModal(product) {
                    this.selected = { ...product };
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                    this.selected = {};
                }
            }
        }
    </script>

</body>

</html>