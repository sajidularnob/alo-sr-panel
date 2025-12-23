<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sr') {
    header("Location: /sr/login.php");
    exit;
}
