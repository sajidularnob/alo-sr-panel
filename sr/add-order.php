<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

/* fetch products */
$products = $conn->query("SELECT id, name, price FROM products ORDER BY name ASC");

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

/* fetch SR orders */
$orders = $conn->prepare("
    SELECT o.id, s.name, s.address,
           SUM(oi.quantity * oi.price) AS total
    FROM orders o
    JOIN shops s ON s.id = o.shop_id
    JOIN order_items oi ON oi.order_id = o.id
    WHERE o.sr_id = ?
    GROUP BY o.id
    ORDER BY o.id DESC
");
$orders->bind_param("i", $_SESSION['user_id']);
$orders->execute();
$orderResult = $orders->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SR Panel - Add Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto px-6 py-8">

        <!-- Company Name -->
        <div class="text-center mb-8">
            <h1
                class="text-3xl md:text-4xl font-semibold text-gray-800 tracking-wide inline-block border-b-2 border-blue-600 pb-2">
                Alo Industries Ltd.
            </h1>
        </div>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-2xl font-medium text-gray-800">Create New Order</h2>
            <div class="mt-3 md:mt-0 space-x-4">
                <a href="dashboard.php" class="text-blue-600 hover:underline">Dashboard</a>
                <a href="products.php" class="text-blue-600 hover:underline">Products</a>
                <a href="../actions/logout.php" class="text-red-600 hover:underline">Logout</a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if ($success): ?>
            <div class="mb-4 p-4 rounded bg-green-100 text-green-800">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-4 p-4 rounded bg-red-100 text-red-800">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Order Form -->
        <div class="bg-white rounded shadow p-6 mb-10">
            <form method="POST" action="../actions/add_order.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Shop Name</label>
                    <input type="text" name="shop_name" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Shop Address</label>
                    <input type="text" name="shop_address" required class="w-full border rounded px-3 py-2">
                </div>

                <!-- Products -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Products</label>

                    <div id="items" class="space-y-2">
                        <div class="flex gap-2">
                            <select name="product_id[]" class="product w-1/2 border rounded px-3 py-2" required>
                                <option value="">Product</option>
                                <?php
                                $products->data_seek(0);
                                while ($p = $products->fetch_assoc()):
                                    ?>
                                    <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
                                        <?= htmlspecialchars($p['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            <input type="number" name="quantity[]" min="1" value="1"
                                class="qty w-1/4 border rounded px-3 py-2" required>

                            <input type="number" step="0.01" name="sell_price[]"
                                class="price w-1/4 border rounded px-3 py-2" required>
                        </div>
                    </div>

                    <button type="button" onclick="addRow()" class="mt-3 text-sm text-blue-600 hover:underline">
                        + Add another product
                    </button>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        Place Order
                    </button>
                </div>

            </form>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded shadow overflow-x-auto">
            <h2 class="text-xl font-medium p-4 border-b">My Orders</h2>

            <table class="min-w-full text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Shop</th>
                        <th class="px-4 py-2 text-left">Address</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php while ($row = $orderResult->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['address']) ?></td>
                            <td class="px-4 py-2 text-right font-medium">
                                ৳<?= number_format($row['total'], 2) ?>
                            </td>
                            <td>
                                <a href="order_details.php?id=<?= $row['id'] ?>" class="text-blue-600">
                                    View
                                </a>
                                <a href="edit-order.php?id=<?= $row['id'] ?>" class="text-blue-600">
                                    Edit
                                </a>
                            </td>


                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function addRow() {
            const firstRow = document.querySelector('#items > div');
            const row = firstRow.cloneNode(true);
            row.querySelectorAll('input').forEach(i => i.value = '');
            row.querySelector('select').value = '';
            document.getElementById('items').appendChild(row);
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product')) {
                const price = e.target.selectedOptions[0].dataset.price;
                e.target.closest('div').querySelector('.price').value = price || '';
            }
        });
    </script>

</body>

</html>