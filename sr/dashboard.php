<?php
require_once "../includes/auth_sr.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SR Dashboard - Alo SR Panel</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: #f0f4f8;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 1rem;
    }

    h1 {
        text-align: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        color: #1e40af;
    }

    nav {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    nav a {
        text-decoration: none;
        color: #1d4ed8;
        padding: 0.5rem 1rem;
        background: #e0e7ff;
        border-radius: 6px;
        transition: background 0.2s;
    }

    nav a:hover {
        background: #c7d2fe;
    }

    p {
        text-align: center;
        font-size: 1rem;
        color: #333;
    }

    @media (max-width: 480px) {
        h1 {
            font-size: 1.5rem;
        }

        nav a {
            width: 100%;
            text-align: center;
            padding: 0.75rem;
            font-size: 1rem;
        }
    }
</style>
</head>
<body>
<div class="container">
     <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 inline-block border-b-2 border-blue-600 pb-2">
            Alo Industries Ltd.
        </h1>
    </div>
    <h1>Welcome, SR!</h1>
    <nav>
        <a href="products.php">Products</a>
        <a href="add-order.php">Add Order</a>
        <a href="../actions/logout.php">Logout</a>
    </nav>
    <p>Dashboard content goes here...</p>
</div>
</body>
</html>
