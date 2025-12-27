<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

// Total SR Users
$result = $conn->query("SELECT COUNT(*) AS total_sr FROM users WHERE role='sr'");
$total_sr = $result ? $result->fetch_assoc()['total_sr'] : 0;

// Total Shops
$result = $conn->query("SELECT COUNT(*) AS total_shops FROM shops");
$total_shops = $result ? $result->fetch_assoc()['total_shops'] : 0;

// Total Orders
$result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = $result ? $result->fetch_assoc()['total_orders'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Alo SR Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<!-- Navbar -->
<nav class="bg-white shadow px-6 py-4 flex flex-wrap items-center justify-between">
    <h1 class="text-xl font-bold text-blue-900">Alo Industries Ltd.</h1>
    <div class="flex flex-wrap gap-4 mt-2 md:mt-0">
        <a href="dashboard.php" class="text-blue-600 hover:underline">Dashboard</a>
        <a href="products.php" class="text-blue-600 hover:underline">Products</a>
        <a href="orders.php" class="text-blue-600 hover:underline">Orders</a>
        <a href="users.php" class="text-blue-600 hover:underline">SR Users</a>
        <a href="../actions/logout.php" class="text-red-600 hover:underline">Logout</a>
    </div>
</nav>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 py-8">
    
    <h2 class="text-3xl md:text-4xl font-semibold text-center text-gray-800 mb-8">Welcome, Admin!</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-4xl font-bold text-gray-800"><?= $total_sr ?></h3>
            <p class="text-gray-600 mt-2">Total SR Users</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-4xl font-bold text-gray-800"><?= $total_shops ?></h3>
            <p class="text-gray-600 mt-2">Total Shops</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-4xl font-bold text-gray-800"><?= $total_orders ?></h3>
            <p class="text-gray-600 mt-2">Total Orders</p>
        </div>
    </div>

</div>

</body>
</html>
