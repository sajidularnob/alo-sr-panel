<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

/* fetch products */
$products = $conn->query("SELECT id, name, price FROM products ORDER BY name ASC");

/* fetch SR shops */
$shopStmt = $conn->prepare("
    SELECT id, name, address 
    FROM shops 
    WHERE id IN (SELECT shop_id FROM orders WHERE sr_id = ?)
    ORDER BY name ASC
");
$shopStmt->bind_param("i", $_SESSION['user_id']);
$shopStmt->execute();
$shops = $shopStmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SR Panel - Add Order</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<div class="max-w-5xl mx-auto px-4 py-8">

  <!-- Company Title & Navigation -->
<div class="text-center mb-8">
    <h1 class="text-3xl md:text-4xl font-semibold text-blue-900 inline-block border-b-2 border-blue-600 pb-2">
        Alo Industries Ltd.
    </h1>
    <!-- Navigation Links -->
    <div class="flex flex-wrap justify-center gap-4 mt-4">
        <a href="dashboard.php" class="px-4 py-2 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition">Dashboard</a>
        <a href="products.php" class="px-4 py-2 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition">Products</a>
        <a href="add-order.php" class="px-4 py-2 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition">Add Order</a>
        <a href="../actions/logout.php" class="px-4 py-2 bg-red-100 text-red-800 rounded hover:bg-red-200 transition">Logout</a>
    </div>
</div>

    <!-- Success / Error Messages -->
    <?php if ($success): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-center"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- ORDER FORM -->
    <div class="bg-white p-6 rounded shadow mb-10" x-data="shopSelect(<?= htmlspecialchars(json_encode($shops)) ?>)">

        <form method="POST" action="../actions/add_order.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- SHOP NAME -->
            <div class="relative">
                <label class="block text-sm font-medium mb-1">Shop Name</label>
                <input type="text"
                       x-model="search"
                       @focus="$nextTick(() => open = true)"
                       @click="$nextTick(() => open = true)"
                       @input="open = true"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="Type or select shop"
                       required>

                <input type="hidden" name="shop_name" :value="selectedName">
                <input type="hidden" name="shop_address" :value="selectedAddress">

                <div x-show="open" x-cloak @click.outside="open=false"
                     class="absolute bg-white border w-full max-h-48 overflow-y-auto rounded mt-1 z-10 shadow">

                    <template x-for="shop in filteredShops" :key="shop.id">
                        <div @click="selectShop(shop)"
                             class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                             x-text="shop.name"></div>
                    </template>

                    <div x-show="filteredShops.length === 0" class="px-3 py-2 text-gray-400">
                        New shop will be created
                    </div>
                </div>
            </div>

            <!-- SHOP ADDRESS -->
            <div>
                <label class="block text-sm font-medium mb-1">Shop Address</label>
                <input type="text"
                       name="shop_address"
                       x-model="selectedAddress"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
            </div>

            <!-- PRODUCTS -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Products</label>
                <div id="items" class="space-y-2">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <select name="product_id[]" class="product w-full sm:w-1/2 border rounded px-3 py-2" required>
                            <option value="">Product</option>
                            <?php $products->data_seek(0); while ($p = $products->fetch_assoc()): ?>
                                <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
                                    <?= htmlspecialchars($p['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <input type="number" name="quantity[]" value="1" min="1" class="w-full sm:w-1/4 border rounded px-3 py-2" required>
                        <input type="number" step="0.01" name="sell_price[]" class="price w-full sm:w-1/4 border rounded px-3 py-2" required>
                    </div>
                </div>
                <button type="button" onclick="addRow()" class="mt-3 text-blue-600 text-sm hover:underline">+ Add another product</button>
            </div>

            <!-- Submit -->
            <div class="md:col-span-2">
                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition w-full md:w-auto">
                    Place Order
                </button>
            </div>

        </form>
    </div>

    <!-- ORDERS TABLE -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <h2 class="text-xl font-medium p-4 border-b">My Orders</h2>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Shop</th>
                    <th class="px-4 py-3 text-left">Address</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php while ($row = $orderResult->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['address']) ?></td>
                        <td class="px-4 py-2 text-right font-medium">৳<?= number_format($row['total'],2) ?></td>
                        <td class="px-4 py-2 text-right">
                            <a href="order_details.php?id=<?= $row['id'] ?>" class="text-blue-600 mr-2 hover:underline">View</a>
                            <a href="edit-order.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function shopSelect(shops) {
    return {
        open: false,
        search: '',
        selectedName: '',
        selectedAddress: '',
        shops: shops,
        get filteredShops() {
            return this.shops.filter(s =>
                s.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        selectShop(shop) {
            this.search = shop.name;
            this.selectedName = shop.name;
            this.selectedAddress = shop.address;
            this.open = false;
        },
        init() {
            this.$watch('search', value => {
                this.selectedName = value;
                if (!this.filteredShops.length) {
                    this.selectedAddress = '';
                }
            });
        }
    }
}

function addRow() {
    const first = document.querySelector('#items > div');
    const row = first.cloneNode(true);
    row.querySelectorAll('input').forEach(i => i.value = '');
    row.querySelector('select').value = '';
    document.getElementById('items').appendChild(row);
}

document.addEventListener('change', e => {
    if (e.target.classList.contains('product')) {
        const price = e.target.selectedOptions[0].dataset.price;
        e.target.closest('div').querySelector('.price').value = price || '';
    }
});
</script>

</body>
</html>
