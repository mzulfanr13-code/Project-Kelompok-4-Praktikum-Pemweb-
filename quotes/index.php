<?php
/* quotes/index.php — Quotes Wall */

require_once '../config/db.php';

$pageTitle = 'Quotes Wall — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';

$filter  = $_GET['filter'] ?? 'approved';
$allowed = ['approved', 'pending', 'rejected', 'all'];
if (!in_array($filter, $allowed)) $filter = 'approved';

$params = [];
if (is_admin() && $filter !== 'approved') {
    if ($filter === 'all') {
        $where = '';
    } else {
        $where    = 'WHERE q.status = ?';
        $params[] = $filter;
    }
} else {
    $where  = "WHERE q.status = 'approved'";
    $filter = 'approved';
}

$stmt = $pdo->prepare("
    SELECT q.*, c.name AS character_name, e.title AS episode_title,
           u.username AS submitted_by_name
    FROM quotes q
    JOIN characters c ON q.character_id = c.id
    JOIN episodes   e ON q.episode_id   = e.id
    JOIN users      u ON q.submitted_by = u.id
    $where
    ORDER BY q.created_at DESC
");
$stmt->execute($params);
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <h2 class="section-title">Quotes Wall</h2>
        <?php if (is_logged_in()): ?>
        <a href="create.php" class="btn btn-primary">
            <i class="fa-solid fa-quote-left"></i> Submit Quote
        </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']) ?></div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (is_admin()): ?>
    <div class="quote-tabs">
        <?php foreach (['approved' => 'Approved', 'pending' => 'Pending', 'rejected' => 'Rejected', 'all' => 'Semua'] as $val => $label): ?>
        <a href="?filter=<?= $val ?>" class="quote-tab <?= $filter === $val ? 'active' : '' ?>">
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($quotes)): ?>
    <div class="ep-empty">
        <i class="fa-solid fa-quote-left"></i>
        <p>Belum ada quote.</p>
    </div>
    <?php endif; ?>

    <div class="quote-grid">
        <?php foreach ($quotes as $q): ?>
        <div class="quote-card">

            <?php if (is_admin()): ?>
            <span class="quote-status quote-status--<?= $q['status'] ?>">
                <?= ucfirst($q['status']) ?>
            </span>
            <?php endif; ?>

            <div class="quote-card__content">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                <?= htmlspecialchars($q['content']) ?>
            </div>

            <div class="quote-card__meta">
                <span class="quote-character"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($q['character_name']) ?></span>
                <span class="quote-episode"><i class="fa-solid fa-film"></i> <?= htmlspecialchars($q['episode_title']) ?></span>
                <span class="quote-submitter"><i class="fa-solid fa-pen-nib"></i> <?= htmlspecialchars($q['submitted_by_name']) ?></span>
            </div>

            <?php if (is_admin()): ?>
            <div class="quote-card__actions">
                <?php if ($q['status'] !== 'approved'): ?>
                <a href="approve.php?id=<?= $q['id'] ?>&filter=<?= $filter ?>" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-check"></i> Approve
                </a>
                <?php endif; ?>
                <?php if ($q['status'] !== 'rejected'): ?>
                <a href="reject.php?id=<?= $q['id'] ?>&filter=<?= $filter ?>" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-xmark"></i> Reject
                </a>
                <?php endif; ?>
                <a href="edit.php?id=<?= $q['id'] ?>&filter=<?= $filter ?>" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-pen"></i>
                </a>
                <a href="delete.php?id=<?= $q['id'] ?>&filter=<?= $filter ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Hapus quote ini?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
