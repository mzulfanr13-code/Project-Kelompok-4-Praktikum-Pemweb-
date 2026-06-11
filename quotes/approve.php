<?php
/* quotes/approve.php — Approve quote (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$filter = $_GET['filter'] ?? 'pending';

if ($id > 0) {
    $pdo->prepare("UPDATE quotes SET status = 'approved' WHERE id = ?")->execute([$id]);
    $_SESSION['flash'] = 'Quote berhasil di-approve!';
}

header("Location: index.php?filter=$filter");
exit;
