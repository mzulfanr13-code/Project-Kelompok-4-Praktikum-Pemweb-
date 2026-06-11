<?php
/* quotes/reject.php — Reject quote (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$filter = $_GET['filter'] ?? 'pending';

if ($id > 0) {
    $pdo->prepare("UPDATE quotes SET status = 'rejected' WHERE id = ?")->execute([$id]);
    $_SESSION['flash'] = 'Quote ditolak.';
}

header("Location: index.php?filter=$filter");
exit;
