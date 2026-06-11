<?php
/* quotes/edit.php — Edit quote (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = ?");
$stmt->execute([$id]);
$quote = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quote) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');

    if ($content === '')              $errors[] = 'Isi quote tidak boleh kosong.';
    if (mb_strlen($content) > 500)   $errors[] = 'Quote maksimal 500 karakter.';

    if (empty($errors)) {
        $pdo->prepare("UPDATE quotes SET content = ? WHERE id = ?")
            ->execute([$content, $id]);

        $_SESSION['flash'] = 'Quote berhasil diperbarui.';
        $filter = $_GET['filter'] ?? 'approved';
        header("Location: index.php?filter=$filter");
        exit;
    }

    $quote['content'] = $content;
}

$filter    = $_GET['filter'] ?? 'approved';
$pageTitle = 'Edit Quote — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php?filter=<?= htmlspecialchars($filter) ?>" class="btn-back">← Kembali</a>
        <h2 class="section-title">Edit Quote</h2>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?><div>• <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="edit.php?id=<?= $id ?>&filter=<?= htmlspecialchars($filter) ?>">

            <div class="form-group">
                <label class="form-label">
                    Isi Quote <span class="req">*</span>
                    <span class="char-counter" id="counter">0 / 500</span>
                </label>
                <textarea name="content" id="content" class="form-control" rows="4"
                          maxlength="500"><?= htmlspecialchars($quote['content']) ?></textarea>
            </div>

            <div class="form-actions">
                <a href="index.php?filter=<?= htmlspecialchars($filter) ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>

<script>
const ta = document.getElementById('content'), counter = document.getElementById('counter');
function upd() { counter.textContent = ta.value.length + ' / 500'; counter.style.color = ta.value.length > 450 ? '#E74C3C' : ''; }
ta.addEventListener('input', upd); upd();
</script>

<?php require_once '../includes/footer.php'; ?>
