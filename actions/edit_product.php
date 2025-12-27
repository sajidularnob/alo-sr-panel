<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

// Get ID from POST
$id = $_POST['id'] ?? null;

if(!$id){
    header("Location: ../admin/products.php?error=Invalid product ID");
    exit;
}

// Fetch existing product
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if(!$product){
    header("Location: ../admin/products.php?error=Product not found");
    exit;
}

// Get POST data
$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');

if(empty($name) || empty($price)){
    header("Location: ../admin/products.php?error=Please fill all fields");
    exit;
}

// Handle new photo upload
$photoFileName = $product['photo']; // keep existing photo by default
if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photoFileName = uniqid('prod_') . '.' . $ext;

    $uploadDir = "../assets/photos/products/";
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fullPath = $uploadDir . $photoFileName;
    move_uploaded_file($_FILES['photo']['tmp_name'], $fullPath);

    // Optional: delete old photo
    if($product['photo'] && file_exists($uploadDir . $product['photo'])){
        unlink($uploadDir . $product['photo']);
    }
}

// Update product
$updateStmt = $conn->prepare("UPDATE products SET name=?, price=?, photo=? WHERE id=?");
$updateStmt->bind_param("sdsi", $name, $price, $photoFileName, $id);

if($updateStmt->execute()){
    header("Location: ../admin/products.php?success=Product updated successfully");
    exit;
}else{
    header("Location: ../admin/products.php?error=Failed to update product");
    exit;
}
