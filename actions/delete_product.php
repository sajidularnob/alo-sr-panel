<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../admin/products.php?error=Invalid product ID");
    exit;
}

// Fetch product to delete photo
$stmt = $conn->prepare("SELECT photo FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: ../admin/products.php?error=Product not found");
    exit;
}

// Delete photo if exists
if ($product['photo'] && file_exists("../assets/photos/products/" . $product['photo'])) {
    unlink("../assets/photos/products/" . $product['photo']);
}

// Delete product
$delStmt = $conn->prepare("DELETE FROM products WHERE id=?");
$delStmt->bind_param("i", $id);

if ($delStmt->execute()) {
    header("Location: ../admin/products.php?success=Product deleted successfully");
    exit;
} else {
    header("Location: ../admin/products.php?error=Failed to delete product");
    exit;
}
