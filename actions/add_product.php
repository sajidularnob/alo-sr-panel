<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');

// Handle image upload
$photoFileName = null;
if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photoFileName = uniqid('prod_') . '.' . $ext;

    $uploadDir = "../assets/photos/products/";
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fullPath = $uploadDir . $photoFileName;
    move_uploaded_file($_FILES['photo']['tmp_name'], $fullPath);
}

if(empty($name) || empty($price)){
    header("Location: ../admin/products.php?error=Please fill all fields");
    exit;
}

$stmt = $conn->prepare("INSERT INTO products (name, price, photo) VALUES (?, ?, ?)");
$stmt->bind_param("sds", $name, $price, $photoFileName);

if($stmt->execute()){
    header("Location: ../admin/products.php?success=Product added successfully");
    exit;
}else{
    header("Location: ../admin/products.php?error=Failed to add product");
    exit;
}
