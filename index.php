<?php
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
session_start();

$redirectUrl = "/sr/login.php"; // default

if (isset($_SESSION['user_id'], $_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        $redirectUrl = "/admin/dashboard.php";
    } elseif ($_SESSION['role'] === 'sr') {
        $redirectUrl = "/sr/dashboard.php";
    }
}

// If headers not sent yet, PHP redirect (fast)
if (!headers_sent()) {
    header("Location: $redirectUrl");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    
    <!-- PWA manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">

    <!-- Fallback redirect for browsers where headers already sent -->
    <meta http-equiv="refresh" content="0;url=<?= htmlspecialchars($redirectUrl) ?>">
</head>
<body>
    <p>Redirecting... <a href="<?= htmlspecialchars($redirectUrl) ?>">Click here if not redirected</a></p>
</body>
</html>
