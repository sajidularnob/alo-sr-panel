<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

$shop_name    = trim($_POST['shop_name'] ?? '');
$shop_address = trim($_POST['shop_address'] ?? '');

$product_ids = $_POST['product_id'] ?? [];
$quantities  = $_POST['quantity'] ?? [];
$prices      = $_POST['sell_price'] ?? [];

$sr_id = $_SESSION['user_id'];

/* basic validation */
if (!$shop_name || !$shop_address || empty($product_ids)) {
    header("Location: ../sr/add-order.php?error=Invalid input");
    exit;
}

/* =====================
   SHOP CHECK / INSERT
===================== */
$stmt = $conn->prepare("SELECT id FROM shops WHERE name = ? LIMIT 1");
$stmt->bind_param("s", $shop_name);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows) {
    $shop_id = $res->fetch_assoc()['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO shops (name, address) VALUES (?, ?)");
    $stmt->bind_param("ss", $shop_name, $shop_address);
    $stmt->execute();
    $shop_id = $stmt->insert_id;
}

/* =====================
   ORDER INSERT
===================== */
$stmt = $conn->prepare("INSERT INTO orders (sr_id, shop_id) VALUES (?, ?)");
$stmt->bind_param("ii", $sr_id, $shop_id);
$stmt->execute();

$order_id = $stmt->insert_id;

/* =====================
   ORDER ITEMS INSERT
===================== */
$stmt = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price)
    VALUES (?, ?, ?, ?)
");

foreach ($product_ids as $i => $pid) {

    $pid   = (int)$pid;
    $qty   = (int)($quantities[$i] ?? 0);
    $price = (float)($prices[$i] ?? 0);

    if ($pid <= 0 || $qty <= 0 || $price <= 0) {
        continue; // skip broken row
    }

    $stmt->bind_param("iiid", $order_id, $pid, $qty, $price);
    $stmt->execute();
}

/* =====================
   REDIRECT
===================== */
header("Location: ../sr/add-order.php?success=Order placed successfully");
exit;
