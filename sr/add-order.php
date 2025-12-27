<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

/* fetch products */
$products = $conn->query("SELECT id, name, price FROM products ORDER BY name ASC");

/* fetch SR shops */
$shopStmt = $conn->prepare("
    SELECT id, name, address 
    FROM shops 
    WHERE sr_id = ?
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
    <title>SR Panel - Add Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-5xl mx-auto px-6 py-8">

    <h1 class="text-3xl text-center mb-8 font-semibold border-b-2 border-blue-600 inline-block">
        Alo Industries Ltd.
    </h1>

    <?php if ($success): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded shadow mb-10"
         x-data="shopSelect(<?= htmlspecialchars(json_encode($shops)) ?>)">

        <form method="POST" action="../actions/add_order.php"
              class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- SHOP NAME -->
            <div class="relative">
                <label class="block text-sm font-medium mb-1">Shop Name</label>

                <input type="text"
                       x-model="search"
                       @focus="open=true"
                       @input="open=true"
                       class="w-full border rounded px-3 py-2"
                       placeholder="Type or select shop"
                       required>

                <input type="hidden" name="shop_name" :value="selectedName">
                <input type="hidden" name="shop_address" :value="selectedAddress">

                <div x-show="open"
                     @click.outside="open=false"
                     class="absolute bg-white border w-full max-h-48 overflow-y-auto rounded mt-1 z-10">

                    <template x-for="shop in filteredShops" :key="shop.id">
                        <div @click="selectShop(shop)"
                             class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                             x-text="shop.name"></div>
                    </template>

                    <div x-show="filteredShops.length === 0"
                         class="px-3 py-2 text-gray-400">
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
                       class="w-full border rounded px-3 py-2"
                       required>
            </div>

            <!-- PRODUCTS -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Products</label>

                <div id="items" class="space-y-2">
                    <div class="flex gap-2">
                        <select name="product_id[]" class="product w-1/2 border rounded px-3 py-2" required>
                            <option value="">Product</option>
                            <?php while ($p = $products->fetch_assoc()): ?>
                                <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
                                    <?= htmlspecialchars($p['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <input type="number" name="quantity[]" value="1" min="1"
                               class="w-1/4 border rounded px-3 py-2" required>

                        <input type="number" step="0.01" name="sell_price[]"
                               class="price w-1/4 border rounded px-3 py-2" required>
                    </div>
                </div>

                <button type="button" onclick="addRow()"
                        class="mt-3 text-blue-600 text-sm">+ Add another product</button>
            </div>

            <div class="md:col-span-2">
                <button class="bg-blue-600 text-white px-6 py-2 rounded">
                    Place Order
                </button>
            </div>

        </form>
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
