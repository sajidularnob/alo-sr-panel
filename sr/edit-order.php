<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

$order_id = (int)($_GET['id'] ?? 0);
$sr_id = $_SESSION['user_id'];

if ($order_id <= 0) die("Invalid order");

/* Fetch order + shop */
$stmt = $conn->prepare("
    SELECT o.id, s.name AS shop_name, s.address
    FROM orders o
    JOIN shops s ON s.id = o.shop_id
    WHERE o.id=? AND o.sr_id=?
");
$stmt->bind_param("ii", $order_id, $sr_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) die("Order not found");

/* Fetch order items */
$stmt = $conn->prepare("
    SELECT oi.id AS order_item_id, p.id AS product_id, p.name, oi.quantity, oi.price
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();

/* Fetch products */
$productsRes = $conn->query("SELECT id, name, price FROM products ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Order #<?= $order_id ?></title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-5xl mx-auto px-6 py-8">

    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 border-b-2 border-blue-600 inline-block pb-2">
            Alo Industries Ltd.
        </h1>
    </div>

    <h2 class="text-2xl font-medium mb-4">Edit Order #<?= $order_id ?></h2>

    <form method="POST" action="../actions/update_order.php" id="orderForm" class="bg-white rounded shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">

        <div>
            <label class="block text-sm font-medium mb-1">Shop Name</label>
            <input type="text" name="shop_name" required value="<?= htmlspecialchars($order['shop_name']) ?>"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Shop Address</label>
            <input type="text" name="shop_address" required value="<?= htmlspecialchars($order['address']) ?>"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Products</label>
            <div id="items" class="space-y-2">
                <?php while($row = $items->fetch_assoc()): ?>
                <div class="flex gap-2 item-row">
                    <input type="hidden" name="order_item_id[]" value="<?= $row['order_item_id'] ?>">
                    <select name="product_id[]" class="product w-1/2 border rounded px-3 py-2" required>
                        <option value="">Select Product</option>
                        <?php
                        $productsRes->data_seek(0);
                        while($p = $productsRes->fetch_assoc()):
                        ?>
                            <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>" <?= $p['id']==$row['product_id']?'selected':'' ?>>
                                <?= htmlspecialchars($p['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="quantity[]" min="1" value="<?= $row['quantity'] ?>"
                           class="qty w-1/6 border rounded px-3 py-2" required>
                    <input type="number" step="0.01" name="price[]" value="<?= $row['price'] ?>"
                           class="price w-1/6 border rounded px-3 py-2" required>
                    <span class="subtotal w-1/6 py-2 text-right font-medium">৳<?= number_format($row['quantity']*$row['price'],2) ?></span>
                    <button type="button" class="delete-btn text-red-600 hover:underline">Delete</button>
                </div>
                <?php endwhile; ?>
            </div>

            <button type="button" onclick="addRow()" class="mt-3 text-sm text-blue-600 hover:underline">+ Add another product</button>
        </div>

        <div class="md:col-span-2 text-right">
            <strong>Total: ৳<span id="total">0.00</span></strong>
        </div>

        <div class="md:col-span-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Update Order</button>
        </div>
    </form>
</div>

<script>
function addRow() {
    const firstRow = document.querySelector('#items > .item-row');
    const row = firstRow.cloneNode(true);
    row.querySelectorAll('input').forEach(i=>{
        if(i.type==='hidden') i.remove();
        else i.value='';
    });
    row.querySelector('select').value='';
    row.querySelector('.subtotal').textContent='৳0.00';
    document.getElementById('items').appendChild(row);
    attachDelete(row);
    updateTotal();
}

function attachDelete(row){
    const btn = row.querySelector('.delete-btn');
    btn.addEventListener('click', function(){
        row.remove();
        updateTotal();
    });
}

function updateTotal(){
    let total=0;
    document.querySelectorAll('#items .item-row').forEach(row=>{
        const qty=parseFloat(row.querySelector('.qty').value)||0;
        const price=parseFloat(row.querySelector('.price').value)||0;
        const subtotal=qty*price;
        row.querySelector('.subtotal').textContent='৳'+subtotal.toFixed(2);
        total+=subtotal;
    });
    document.getElementById('total').textContent=total.toFixed(2);
}

/* attach delete events */
document.querySelectorAll('#items .item-row').forEach(attachDelete);

/* auto update subtotal & total */
document.getElementById('items').addEventListener('input', updateTotal);

/* auto fill price when product changes */
document.getElementById('items').addEventListener('change', function(e){
    if(e.target.classList.contains('product')){
        const price = parseFloat(e.target.selectedOptions[0].dataset.price)||0;
        e.target.closest('.item-row').querySelector('.price').value = price;
        updateTotal();
    }
});

/* initialize total */
updateTotal();
</script>
</body>
</html>
