<?php
require_once "../includes/auth_sr.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SR Dashboard - Alo SR Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <!-- Company Title -->
    <div class="text-center mb-10 px-4">
        <h1 class="text-3xl md:text-4xl font-semibold text-blue-900 inline-block border-b-2 border-blue-600 pb-2">
            Alo Industries Ltd.
        </h1>
    </div>

    <div class="max-w-3xl mx-auto px-4">

        <!-- Welcome -->
        <h1 class="text-2xl md:text-3xl text-center text-blue-900 font-semibold mb-6">
            Welcome, SR!
        </h1>

        <!-- Navigation -->
        <nav class="flex flex-wrap justify-center gap-4 mb-8">
            <a href="products.php" class="px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition w-full sm:w-auto text-center">Products</a>
            <a href="add-order.php" class="px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition w-full sm:w-auto text-center">Add Order</a>
            <a href="../actions/logout.php" class="px-4 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 transition w-full sm:w-auto text-center">Logout</a>
        </nav>

        <!-- Content -->
        <p class="text-center text-gray-700 text-base md:text-lg">
            Dashboard content goes here...
        </p>

    </div>

</body>
</html>
