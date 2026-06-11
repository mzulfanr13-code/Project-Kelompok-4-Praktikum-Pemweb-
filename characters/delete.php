<?php
/* characters/delete.php — Hapus karakter (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT name, image_url FROM characters WHERE id = ?");
    $stmt->execute([$id]);
    $char = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($char) {
        /* Hapus file gambar jika ada */
        if (!empty($char['image_url'])) {
            $file = __DIR__ . '/../' . $char['image_url'];
            if (file_exists($file)) unlink($file);
        }

        $pdo->prepare("DELETE FROM characters WHERE id = ?")->execute([$id]);
        $_SESSION['flash'] = 'Karakter "' . htmlspecialchars($char['name']) . '" berhasil dihapus.';
    }
}

header('Location: index.php');
exit;