<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

/*
Expected POST:
order_id
shop_name
shop_address
order_item_id[] (existing items, empty for new)
product_id[]
quantity[]
price[]
*/

$order_id    = (int)($_POST['order_id'] ?? 0);
$shop_name   = trim($_POST['shop_name'] ?? '');
$shop_address= trim($_POST['shop_address'] ?? '');
$order_item_ids = $_POST['order_item_id'] ?? [];
$product_ids = $_POST['product_id'] ?? [];
$quantities  = $_POST['quantity'] ?? [];
$prices      = $_POST['price'] ?? [];

$sr_id = $_SESSION['user_id'];

/* basic validation */
if ($order_id <= 0 || !$shop_name || !$shop_address || !count($product_ids)) {
    header("Location: ../sr/edit_order.php?id=$order_id&error=Invalid input");
    exit;
}

/* =====================
   Update Shop info
===================== */
$stmt = $conn->prepare("SELECT id FROM shops WHERE id = (SELECT shop_id FROM orders WHERE id=?) LIMIT 1");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$shop_id = $stmt->get_result()->fetch_assoc()['id'];

$stmt = $conn->prepare("UPDATE shops SET name=?, address=? WHERE id=?");
$stmt->bind_param("ssi", $shop_name, $shop_address, $shop_id);
$stmt->execute();

/* =====================
   Process Order Items
===================== */
foreach ($product_ids as $index => $product_id) {
    $product_id = (int)$product_id;
    $qty = (int)$quantities[$index];
    $price = (float)$prices[$index];
    $order_item_id = isset($order_item_ids[$index]) ? (int)$order_item_ids[$index] : 0;

    if ($product_id <=0 || $qty <=0 || $price <=0) continue;

    if ($order_item_id > 0) {
        // Update existing item
        $stmt = $conn->prepare("UPDATE order_items SET product_id=?, quantity=?, price=? WHERE id=? AND order_id=?");
        $stmt->bind_param("iiiii", $product_id, $qty, $price, $order_item_id, $order_id);
        $stmt->execute();
    } else {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);
        $stmt->execute();
    }
}

/* =====================
   Redirect back
===================== */
header("Location: ../sr/dashboard.php");
exit;
