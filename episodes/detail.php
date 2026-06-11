<?php
/* episodes/detail.php — Detail episode */

require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM episodes WHERE id = ?");
$stmt->execute([$id]);
$episode = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = $episode ? htmlspecialchars($episode['title']) . ' — TAWOG' : 'Not Found';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';

if (!$episode): ?>
    <div class="page-wrapper">
        <div class="alert alert-danger">Episode tidak ditemukan.</div>
        <a href="index.php" class="btn btn-outline">← Kembali</a>
    </div>
<?php
    require_once '../includes/footer.php';
    exit;
endif;
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php" class="btn-back">← Kembali</a>
        <h2 class="section-title">Detail Episode</h2>
    </div>

    <div class="detail-card ep-detail-card">

        <div class="ep-detail__badge">
            S<?= str_pad($episode['season'], 2, '0', STR_PAD_LEFT) ?><br>
            E<?= str_pad($episode['episode_number'], 2, '0', STR_PAD_LEFT) ?>
        </div>

        <div class="ep-detail__info">
            <h3 class="detail-name"><?= htmlspecialchars($episode['title']) ?></h3>

            <table class="detail-info-table">
                <tr>
                    <td>Season</td>
                    <td><?= $episode['season'] ?></td>
                </tr>
                <tr>
                    <td>Episode</td>
                    <td><?= $episode['episode_number'] ?></td>
                </tr>
                <tr>
                    <td>Tanggal Tayang</td>
                    <td><?= !empty($episode['air_date']) ? date('d M Y', strtotime($episode['air_date'])) : '-' ?></td>
                </tr>
                <tr>
                    <td>Ditambahkan</td>
                    <td><?= date('d M Y', strtotime($episode['created_at'])) ?></td>
                </tr>
            </table>

            <?php if (!empty($episode['synopsis'])): ?>
            <div class="detail-card__desc">
                <strong>Sinopsis</strong>
                <?= nl2br(htmlspecialchars($episode['synopsis'])) ?>
            </div>
            <?php endif; ?>

            <?php if (is_admin()): ?>
            <div class="detail-actions">
                <a href="edit.php?id=<?= $episode['id'] ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <a href="delete.php?id=<?= $episode['id'] ?>"
                   class="btn btn-danger"
                   onclick="return confirm('Hapus episode ini?')">
                    <i class="fa-solid fa-trash"></i> Hapus
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
