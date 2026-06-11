<?php
/* episodes/delete.php — Hapus episode (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT title FROM episodes WHERE id = ?");
    $stmt->execute([$id]);
    $ep = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ep) {
        $pdo->prepare("DELETE FROM episodes WHERE id = ?")->execute([$id]);
        $_SESSION['flash'] = 'Episode "' . htmlspecialchars($ep['title']) . '" berhasil dihapus.';
    }
}

header('Location: index.php');
exit;
