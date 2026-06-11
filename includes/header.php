<?php
/* includes/header.php — DOCTYPE → html → head → body → nav */

require_once __DIR__ . '/session.php';

$pageTitle = $pageTitle ?? 'TAWOG Fan Site';
$bodyClass = $bodyClass ?? '';

$uri = $_SERVER['REQUEST_URI'];
function nav_active(string $segment, string $uri): string {
    return strpos($uri, $segment) !== false ? ' class="active"' : '';
}
$homeActive = (
    $uri === '/' ||
    preg_match('#^/index\.php#', $uri) ||
    preg_match('#^/$#', $uri)
) ? ' class="active"' : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:ital,wght@0,400;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">

<!-- NAVBAR -->
<nav class="navbar">

    <a href="/index.php" class="navbar-brand">
        <span class="navbar-brand-text">TAWOG</span>
    </a>

    <ul class="navbar-links">
        <li><a href="/index.php"<?= $homeActive ?>>HOME</a></li>
        <li><a href="/characters/index.php"<?= nav_active('characters', $uri) ?>>CHARACTER</a></li>
        <li><a href="/episodes/index.php"<?= nav_active('episodes', $uri) ?>>EPISODE</a></li>
        <li><a href="/quotes/index.php"<?= nav_active('quotes', $uri) ?>>QUOTES</a></li>
    </ul>

    <div class="navbar-right">
        <?php if (is_logged_in()): ?>

            <a href="/profile.php"<?= nav_active('profile', $uri) ?>>PROFILE</a>

            <?php if (is_admin()): ?>
                <a href="/admin/dashboard.php"<?= nav_active('admin', $uri) ?>>ADMIN</a>
            <?php endif; ?>

            <a href="/logout.php" class="nav-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>

        <?php else: ?>

            <a href="/login.php"<?= nav_active('login', $uri) ?>>LOGIN</a>

        <?php endif; ?>
    </div>

</nav>
<!-- /NAVBAR -->
