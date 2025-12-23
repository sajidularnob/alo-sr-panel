<?php
$conn = new mysqli("localhost", "root", "", "alo_sr_panel");
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}
