<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get ID from POST
$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: ../admin/products.php?error=Invalid product ID");
    exit;
}

// Fetch product
$stmt = $conn->prepare("SELECT photo FROM products WHERE id=?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: ../admin/products.php?error=Product not found");
    exit;
}

// Delete photo if exists
if ($product['photo']) {
    $photoPath = "../assets/photos/products/" . $product['photo'];
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }
}

// Delete product
$delStmt = $conn->prepare("DELETE FROM products WHERE id=?");
if (!$delStmt) {
    die("Prepare failed: " . $conn->error);
}
$delStmt->bind_param("i", $id);

if ($delStmt->execute()) {
    header("Location: ../admin/products.php?success=Product deleted successfully");
    exit;
} else {
    header("Location: ../admin/products.php?error=Failed to delete product");
    exit;
}
