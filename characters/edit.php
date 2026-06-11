<?php
/* characters/edit.php — Edit karakter (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM characters WHERE id = ?");
$stmt->execute([$id]);
$character = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$character) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name             = trim($_POST['name'] ?? '');
    $species          = trim($_POST['species'] ?? '');
    $first_appearance = trim($_POST['first_appearance'] ?? '');
    $description      = trim($_POST['description'] ?? '');

    if ($name === '')    $errors[] = 'Nama karakter wajib diisi.';
    if ($species === '') $errors[] = 'Spesies wajib diisi.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE characters
            SET name = ?, species = ?, first_appearance = ?, description = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $species, $first_appearance, $description, $id]);

        $_SESSION['flash'] = 'Karakter "' . htmlspecialchars($name) . '" berhasil diperbarui!';
        header('Location: index.php');
        exit;
    }

    $character['name']             = $name;
    $character['species']          = $species;
    $character['first_appearance'] = $first_appearance;
    $character['description']      = $description;
}

$pageTitle = 'Edit ' . htmlspecialchars($character['name']) . ' — TAWOG';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php" class="btn-back">← Kembali</a>
        <h2 class="section-title">Edit Karakter</h2>
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
                <label class="form-label">Nama Karakter <span class="req">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($character['name']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Spesies <span class="req">*</span></label>
                <input type="text" name="species" class="form-control"
                       value="<?= htmlspecialchars($character['species']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">First Appearance</label>
                <input type="text" name="first_appearance" class="form-control"
                       value="<?= htmlspecialchars($character['first_appearance'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($character['description'] ?? '') ?></textarea>
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
