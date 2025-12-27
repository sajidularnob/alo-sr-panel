<?php
require_once "../includes/auth_sr.php";
require_once "../includes/db.php";

$order_id = (int)($_POST['order_id'] ?? 0);
$shop_name = trim($_POST['shop_name'] ?? '');
$shop_address = trim($_POST['shop_address'] ?? '');

$product_ids = $_POST['product_id'] ?? [];
$quantities  = $_POST['quantity'] ?? [];
$prices      = $_POST['price'] ?? [];
$order_item_ids = $_POST['order_item_id'] ?? [];

$sr_id = $_SESSION['user_id'];

if ($order_id <= 0 || !$shop_name || !$shop_address) {
    header("Location: ../sr/dashboard.php");
    exit;
}

/* GET SHOP ID (SR SAFE) */
$stmt = $conn->prepare("
    SELECT s.id 
    FROM orders o
    JOIN shops s ON s.id = o.shop_id
    WHERE o.id = ? AND o.sr_id = ?
");
$stmt->bind_param("ii", $order_id, $sr_id);
$stmt->execute();
$shop = $stmt->get_result()->fetch_assoc();

if (!$shop) exit("Invalid access");

$shop_id = $shop['id'];

/* UPDATE SHOP */
$stmt = $conn->prepare("
    UPDATE shops SET name=?, address=? 
    WHERE id=? AND sr_id=?
");
$stmt->bind_param("ssii", $shop_name, $shop_address, $shop_id, $sr_id);
$stmt->execute();

/* UPDATE ITEMS */
foreach ($product_ids as $i => $pid) {
    $pid = (int)$pid;
    $qty = (int)$quantities[$i];
    $price = (float)$prices[$i];
    $item_id = (int)($order_item_ids[$i] ?? 0);

    if ($pid <= 0 || $qty <= 0 || $price <= 0) continue;

    if ($item_id) {
        $stmt = $conn->prepare("
            UPDATE order_items 
            SET product_id=?, quantity=?, price=?
            WHERE id=? AND order_id=?
        ");
        $stmt->bind_param("iiiii", $pid, $qty, $price, $item_id, $order_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiid", $order_id, $pid, $qty, $price);
        $stmt->execute();
    }
}

header("Location: ../sr/dashboard.php");
exit;
