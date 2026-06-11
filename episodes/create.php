<?php
/* episodes/create.php — Tambah episode baru (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$errors = [];
$input  = ['title' => '', 'season' => '', 'episode_number' => '', 'synopsis' => '', 'air_date' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['title']          = trim($_POST['title'] ?? '');
    $input['season']         = trim($_POST['season'] ?? '');
    $input['episode_number'] = trim($_POST['episode_number'] ?? '');
    $input['synopsis']       = trim($_POST['synopsis'] ?? '');
    $input['air_date']       = trim($_POST['air_date'] ?? '');

    if ($input['title'] === '')                                               $errors[] = 'Judul episode wajib diisi.';
    if ($input['season'] === '' || (int)$input['season'] < 1)                $errors[] = 'Season harus angka minimal 1.';
    if ($input['episode_number'] === '' || (int)$input['episode_number'] < 1) $errors[] = 'Nomor episode harus angka minimal 1.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO episodes (title, season, episode_number, synopsis, air_date)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $input['title'],
            (int)$input['season'],
            (int)$input['episode_number'],
            $input['synopsis'],
            $input['air_date'] ?: null,
        ]);

        $_SESSION['flash'] = 'Episode "' . htmlspecialchars($input['title']) . '" berhasil ditambahkan!';
        header('Location: index.php');
        exit;
    }
}

$pageTitle = 'Tambah Episode — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php" class="btn-back">← Kembali</a>
        <h2 class="section-title">Tambah Episode</h2>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
                <div>• <?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="create.php">

            <div class="form-group">
                <label class="form-label">Judul Episode <span class="req">*</span></label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($input['title']) ?>"
                       placeholder="cth. The DVD" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Season <span class="req">*</span></label>
                    <input type="number" name="season" class="form-control"
                           value="<?= htmlspecialchars($input['season']) ?>"
                           min="1" placeholder="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Episode # <span class="req">*</span></label>
                    <input type="number" name="episode_number" class="form-control"
                           value="<?= htmlspecialchars($input['episode_number']) ?>"
                           min="1" placeholder="1" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Tayang</label>
                <input type="date" name="air_date" class="form-control"
                       value="<?= htmlspecialchars($input['air_date']) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Sinopsis</label>
                <textarea name="synopsis" class="form-control" rows="4"
                          placeholder="Ceritakan isi episode ini..."><?= htmlspecialchars($input['synopsis']) ?></textarea>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Simpan Episode
                </button>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
