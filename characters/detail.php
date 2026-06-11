<?php
/* characters/detail.php — Detail karakter */

require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM characters WHERE id = ?");
$stmt->execute([$id]);
$character = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = $character ? htmlspecialchars($character['name']) . ' — TAWOG' : 'Not Found';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';

if (!$character): ?>
    <div class="page-wrapper">
        <div class="alert alert-danger">Karakter tidak ditemukan.</div>
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
        <h2 class="section-title">Detail Karakter</h2>
    </div>

    <div class="detail-card">

        <div class="detail-card__img">
            <?php if (!empty($character['image_url'])): ?>
                <img src="/<?= htmlspecialchars($character['image_url']) ?>"
                     alt="<?= htmlspecialchars($character['name']) ?>">
            <?php else: ?>
                <span class="detail-card__img-placeholder">
                    <i class="fa-solid fa-user" style="font-size:3rem; color:#a0b4c0;"></i>
                </span>
            <?php endif; ?>
        </div>

        <div class="detail-card__info">
            <h3 class="detail-name"><?= htmlspecialchars($character['name']) ?></h3>

            <table class="detail-info-table">
                <tr>
                    <td>Spesies</td>
                    <td><?= htmlspecialchars($character['species']) ?></td>
                </tr>
                <tr>
                    <td>First Appearance</td>
                    <td><?= htmlspecialchars($character['first_appearance'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Ditambahkan</td>
                    <td><?= date('d M Y', strtotime($character['created_at'])) ?></td>
                </tr>
            </table>

            <?php if (!empty($character['description'])): ?>
            <div class="detail-card__desc">
                <strong>Deskripsi</strong>
                <?= nl2br(htmlspecialchars($character['description'])) ?>
            </div>
            <?php endif; ?>

            <?php if (is_admin()): ?>
            <div class="detail-actions">
                <a href="edit.php?id=<?= $character['id'] ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <a href="delete.php?id=<?= $character['id'] ?>"
                   class="btn btn-danger"
                   onclick="return confirm('Hapus karakter ini?')">
                    <i class="fa-solid fa-trash"></i> Hapus
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
