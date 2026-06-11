<?php
/* characters/index.php — Character Encyclopedia */

require_once '../config/db.php';

$pageTitle = 'Characters — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';

$stmt = $pdo->query("
    SELECT *
    FROM characters
    ORDER BY name ASC
");
$characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-wrapper">

    <div class="section-header">
        <h2 class="section-title">Character Encyclopedia</h2>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']) ?></div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="char-grid">

        <?php foreach ($characters as $char): ?>

        <a href="detail.php?id=<?= $char['id'] ?>" class="char-card">

            <div class="char-card__img">
                <?php if (!empty($char['image_url'])): ?>
                    <img src="/<?= htmlspecialchars($char['image_url']) ?>"
                         alt="<?= htmlspecialchars($char['name']) ?>">
                <?php endif; ?>
            </div>

            <p class="char-card__name"><?= htmlspecialchars($char['name']) ?></p>

        </a>

        <?php endforeach; ?>

        <?php if (is_admin()): ?>
        <a href="create.php" class="char-card char-card--add" title="Tambah Karakter">
            <span>+</span>
        </a>
        <?php endif; ?>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
