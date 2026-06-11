<?php
/* episodes/edit.php — Edit episode (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM episodes WHERE id = ?");
$stmt->execute([$id]);
$episode = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$episode) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title          = trim($_POST['title'] ?? '');
    $season         = trim($_POST['season'] ?? '');
    $episode_number = trim($_POST['episode_number'] ?? '');
    $synopsis       = trim($_POST['synopsis'] ?? '');
    $air_date       = trim($_POST['air_date'] ?? '');

    if ($title === '')                                       $errors[] = 'Judul episode wajib diisi.';
    if ($season === '' || (int)$season < 1)                 $errors[] = 'Season harus angka minimal 1.';
    if ($episode_number === '' || (int)$episode_number < 1) $errors[] = 'Nomor episode harus angka minimal 1.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE episodes
            SET title = ?, season = ?, episode_number = ?, synopsis = ?, air_date = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $title, (int)$season, (int)$episode_number,
            $synopsis, $air_date ?: null, $id,
        ]);

        $_SESSION['flash'] = 'Episode "' . htmlspecialchars($title) . '" berhasil diperbarui!';
        header('Location: index.php');
        exit;
    }

    $episode['title']          = $title;
    $episode['season']         = $season;
    $episode['episode_number'] = $episode_number;
    $episode['synopsis']       = $synopsis;
    $episode['air_date']       = $air_date;
}

$pageTitle = 'Edit Episode — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php" class="btn-back">← Kembali</a>
        <h2 class="section-title">Edit Episode</h2>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
                <div>• <?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="edit.php?id=<?= $id ?>">

            <div class="form-group">
                <label class="form-label">Judul Episode <span class="req">*</span></label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($episode['title']) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Season <span class="req">*</span></label>
                    <input type="number" name="season" class="form-control"
                           value="<?= htmlspecialchars($episode['season']) ?>" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Episode # <span class="req">*</span></label>
                    <input type="number" name="episode_number" class="form-control"
                           value="<?= htmlspecialchars($episode['episode_number']) ?>" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Tayang</label>
                <input type="date" name="air_date" class="form-control"
                       value="<?= htmlspecialchars($episode['air_date'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Sinopsis</label>
                <textarea name="synopsis" class="form-control" rows="4"><?= htmlspecialchars($episode['synopsis'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <a href="detail.php?id=<?= $id ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
