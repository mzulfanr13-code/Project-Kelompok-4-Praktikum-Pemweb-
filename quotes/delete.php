<?php
/* quotes/delete.php — Hapus quote (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$filter = $_GET['filter'] ?? 'approved';

if ($id > 0) {
    $pdo->prepare("DELETE FROM quotes WHERE id = ?")->execute([$id]);
    $_SESSION['flash'] = 'Quote berhasil dihapus.';
}

header("Location: index.php?filter=$filter");
exit;
