<?php
/* episodes/index.php — Episode Guide */

require_once '../config/db.php';

$pageTitle = 'Episodes — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';

$stmt = $pdo->query("
    SELECT * FROM episodes
    ORDER BY season ASC, episode_number ASC
");
$episodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$byseason = [];
foreach ($episodes as $ep) {
    $byseason[$ep['season']][] = $ep;
}
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <h2 class="section-title">Episode Guide</h2>
        <?php if (is_admin()): ?>
        <a href="create.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Episode
        </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']) ?></div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (empty($episodes)): ?>
        <div class="ep-empty">
            <i class="fa-solid fa-film"></i>
            <p>Belum ada episode yang ditambahkan.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($byseason as $season => $eps): ?>
    <div class="season-block">
        <div class="season-label">Season <?= $season ?></div>

        <div class="ep-grid">
            <?php foreach ($eps as $ep): ?>
            <div class="ep-card">
                <div class="ep-card__badge">
                    S<?= str_pad($ep['season'], 2, '0', STR_PAD_LEFT) ?>E<?= str_pad($ep['episode_number'], 2, '0', STR_PAD_LEFT) ?>
                </div>
                <div class="ep-card__body">
                    <h4 class="ep-card__title"><?= htmlspecialchars($ep['title']) ?></h4>
                    <?php if (!empty($ep['air_date'])): ?>
                    <p class="ep-card__date">
                        <i class="fa-regular fa-calendar"></i>
                        <?= date('d M Y', strtotime($ep['air_date'])) ?>
                    </p>
                    <?php endif; ?>
                    <?php if (!empty($ep['synopsis'])): ?>
                    <p class="ep-card__synopsis">
                        <?= htmlspecialchars(mb_strimwidth($ep['synopsis'], 0, 100, '...')) ?>
                    </p>
                    <?php endif; ?>
                </div>
                <div class="ep-card__actions">
                    <a href="detail.php?id=<?= $ep['id'] ?>" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-eye"></i> Detail
                    </a>
                    <?php if (is_admin()): ?>
                    <a href="edit.php?id=<?= $ep['id'] ?>" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="delete.php?id=<?= $ep['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus episode ini?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>

<?php require_once '../includes/footer.php'; ?>
