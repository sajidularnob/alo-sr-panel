<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

// fetch products
$products = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SR Panel - Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Company Title -->
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 tracking-wide inline-block border-b-2 border-blue-600 pb-2">
            Alo Industries Ltd.
        </h1>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h2 class="text-2xl font-medium text-gray-800">Available Products</h2>
        <div class="mt-3 md:mt-0 space-x-4">
            <a href="dashboard.php" class="text-blue-600 hover:underline">Dashboard</a>
            <a href="../actions/logout.php" class="text-red-600 hover:underline">Logout</a>
        </div>
    </div>

    <!-- Product Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Photo</th>
                    <th class="px-4 py-3 text-left">Product Name</th>
                    <th class="px-4 py-3 text-left">Price</th>
                </tr>
            </thead>
            <tbody class="divide-y">
            <?php while($row = $products->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <?php if($row['photo']): ?>
                            <img src="../assets/photos/products/<?php echo $row['photo']; ?>"
                                 class="w-16 h-16 object-contain rounded border bg-white">
                        <?php else: ?>
                            <span class="text-gray-400">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </td>
                    <td class="px-4 py-3">
                        ৳<?php echo number_format($row['price'], 2); ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
