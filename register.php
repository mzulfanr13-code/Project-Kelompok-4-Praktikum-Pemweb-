<?php
/* register.php — Halaman registrasi */

require_once 'includes/session.php';
require_once 'config/db.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$input  = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['username'] = trim($_POST['username'] ?? '');
    $input['email']    = trim($_POST['email']    ?? '');
    $password          = trim($_POST['password'] ?? '');

    if ($input['username'] === '') $errors[] = 'Username tidak boleh kosong.';
    if ($input['email']    === '') $errors[] = 'Email tidak boleh kosong.';
    if ($password          === '') $errors[] = 'Password tidak boleh kosong.';
    if (mb_strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';

    if (empty($errors)) {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$input['email']]);
        if ($check->rowCount() > 0) {
            $errors[] = 'Email sudah digunakan.';
        }
    }

    if (empty($errors)) {
        $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")
            ->execute([$input['username'], $input['email'], password_hash($password, PASSWORD_BCRYPT)]);

        $_SESSION['flash'] = 'Akun berhasil dibuat! Silakan login.';
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — TAWOG Fan Site</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">

    <div class="login-card">

        <h1 class="login-card__title">Register</h1>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
            <p class="login-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($e) ?>
            </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST" action="register.php" novalidate>

            <input
                type="text"
                name="username"
                class="login-input"
                placeholder="Username"
                value="<?= htmlspecialchars($input['username']) ?>"
                autocomplete="username"
                required>

            <input
                type="email"
                name="email"
                class="login-input"
                placeholder="Email"
                value="<?= htmlspecialchars($input['email']) ?>"
                autocomplete="email"
                required>

            <input
                type="password"
                name="password"
                class="login-input"
                placeholder="Password (min. 6 karakter)"
                autocomplete="new-password"
                required>

            <button type="submit" class="btn-login">Daftar</button>

        </form>

        <p class="login-footer">
            Sudah punya akun?
            <a href="login.php">Login</a>
        </p>

    </div>

</body>
</html>
