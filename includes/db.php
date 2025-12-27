<?php
$host = "localhost";
$db   = "u400667647_alo";
$user = "u400667647_alo_panel";
$pass = "Yaharnob1234@";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: set charset (recommended)
$conn->set_charset("utf8mb4");
