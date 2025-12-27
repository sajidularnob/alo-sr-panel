<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

$order_id = (int)($_GET['id'] ?? 0);
$sr_id = $_SESSION['user_id'];

if ($order_id <= 0) {
    die("Invalid order");
}

/* Order + shop info */
$stmt = $conn->prepare("
    SELECT o.id, s.name AS shop_name, s.address, o.order_date
    FROM orders o
    JOIN shops s ON s.id = o.shop_id
    WHERE o.id = ? AND o.sr_id = ?
");
$stmt->bind_param("ii", $order_id, $sr_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found");
}

/* Order items */
$stmt = $conn->prepare("
    SELECT p.name, oi.quantity, oi.price,
           (oi.quantity * oi.price) AS subtotal
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details - SR Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-5xl mx-auto px-6 py-8">

    <!-- Company Name -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 tracking-wide inline-block border-b-2 border-blue-600 pb-2">
            Alo Industries Ltd.
        </h1>
    </div>

    <!-- Order Info -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-2xl font-medium mb-4">Order #<?= $order['id'] ?></h2>
        <p><strong>Shop Name:</strong> <?= htmlspecialchars($order['shop_name']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
        <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
    </div>

    <!-- Order Items Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <h2 class="text-xl font-medium p-4 border-b">Order Items</h2>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-4 py-2 text-left">Product</th>
                <th class="px-4 py-2 text-right">Quantity</th>
                <th class="px-4 py-2 text-right">Price</th>
                <th class="px-4 py-2 text-right">Subtotal</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            <?php
            $total = 0;
            while ($row = $items->fetch_assoc()):
                $total += $row['subtotal'];
            ?>
                <tr>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="px-4 py-2 text-right"><?= $row['quantity'] ?></td>
                    <td class="px-4 py-2 text-right">৳<?= number_format($row['price'],2) ?></td>
                    <td class="px-4 py-2 text-right">৳<?= number_format($row['subtotal'],2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="3" class="px-4 py-2 text-right">Total</td>
                    <td class="px-4 py-2 text-right">৳<?= number_format($total,2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-6">
        <a href="add-order.php" class="text-blue-600 hover:underline">&larr; Back to Orders</a>
    </div>

</div>

</body>
</html>
