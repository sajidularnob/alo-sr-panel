<?php
require_once "../includes/auth_admin.php";
require_once "../includes/db.php";

// Total SR Users (users table থেকে যেখানে role='sr')
$result = $conn->query("SELECT COUNT(*) AS total_sr FROM users WHERE role='sr'");
if (!$result) {
    die("Query failed: " . $conn->error);
}
$total_sr = $result->fetch_assoc()['total_sr'];

// Total Shops
$result = $conn->query("SELECT COUNT(*) AS total_shops FROM shops");
if (!$result) {
    die("Query failed: " . $conn->error);
}
$total_shops = $result->fetch_assoc()['total_shops'];

// Total Orders
$result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
if (!$result) {
    die("Query failed: " . $conn->error);
}
$total_orders = $result->fetch_assoc()['total_orders'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Alo SR Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f9f9f9; margin:0; padding:0; }
        nav { background:#fff; padding:15px 30px; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
        nav a { margin-right:20px; text-decoration:none; color:#007bff; font-weight:500; }
        .container { max-width:1200px; margin:30px auto; padding:0 20px; }
        .cards { display:flex; gap:20px; flex-wrap:wrap; }
        .card { flex:1; min-width:200px; background:#fff; border-radius:6px; padding:30px; box-shadow:0 2px 6px rgba(0,0,0,0.1); text-align:center; }
        .card h2 { font-size:2.5em; margin:0 0 10px; color:#333; }
        .card p { font-size:1em; color:#555; margin:0; }
    </style>
</head>
<body>

<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="products.php">Products</a>
    <a href="orders.php">Orders</a>
    <a href="users.php">SR Users</a>
    <a href="../actions/logout.php" style="color:red;">Logout</a>
</nav>

<div class="container">
    <h1>Welcome, Admin!</h1>
    <div class="cards">
        <div class="card">
            <h2><?= $total_sr ?></h2>
            <p>Total SR Users</p>
        </div>
        <div class="card">
            <h2><?= $total_shops ?></h2>
            <p>Total Shops</p>
        </div>
        <div class="card">
            <h2><?= $total_orders ?></h2>
            <p>Total Orders</p>
        </div>
    </div>
</div>

</body>
</html>
