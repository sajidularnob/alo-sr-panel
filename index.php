<?php
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
session_start();

if (isset($_SESSION['user_id'], $_SESSION['role'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: /alo-sr-panel/admin/dashboard.php");
        exit;
    }

    if ($_SESSION['role'] === 'sr') {
        header("Location: /alo-sr-panel/sr/dashboard.php");
        exit;
    }
}

header("Location: /alo-sr-panel/sr/login.php");
exit;
