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

/* --- Upload helper --- */
function handle_image_upload(): string|false
{
    if (empty($_FILES['image']['name'])) return false;

    $file    = $_FILES['image'];
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 2 * 1024 * 1024;

    if ($file['error'] !== UPLOAD_ERR_OK)  return false;
    if (!in_array($file['type'], $allowed)) return false;
    if ($file['size'] > $maxSize)           return false;

    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('char_', true) . '.' . strtolower($ext);
    $dest     = __DIR__ . '/../assets/images/characters/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) return false;

    return 'assets/images/characters/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name             = trim($_POST['name'] ?? '');
    $species          = trim($_POST['species'] ?? '');
    $first_appearance = trim($_POST['first_appearance'] ?? '');
    $description      = trim($_POST['description'] ?? '');

    if ($name === '')    $errors[] = 'Nama karakter wajib diisi.';
    if ($species === '') $errors[] = 'Spesies wajib diisi.';

    /* Validate image if provided */
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload gambar gagal.';
        } elseif (!in_array($_FILES['image']['type'], $allowed)) {
            $errors[] = 'Format gambar harus JPG, PNG, GIF, atau WEBP.';
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran gambar maksimal 2 MB.';
        }
    }

    if (empty($errors)) {
        $newImagePath = handle_image_upload();

        /* Determine final image_url */
        if ($newImagePath) {
            /* Delete old image file if it exists */
            if (!empty($character['image_url'])) {
                $oldFile = __DIR__ . '/../' . $character['image_url'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
            $imagePath = $newImagePath;
        } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] === '1') {
            /* Admin ticked "Hapus gambar" */
            if (!empty($character['image_url'])) {
                $oldFile = __DIR__ . '/../' . $character['image_url'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
            $imagePath = null;
        } else {
            /* Keep existing image */
            $imagePath = $character['image_url'] ?: null;
        }

        $stmt = $pdo->prepare("
            UPDATE characters
            SET name = ?, species = ?, first_appearance = ?, description = ?, image_url = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $species, $first_appearance, $description, $imagePath, $id]);

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
        <form method="POST" action="edit.php?id=<?= $id ?>" enctype="multipart/form-data">

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

            <!-- Gambar saat ini -->
            <div class="form-group">
                <label class="form-label">Gambar Karakter</label>

                <?php if (!empty($character['image_url'])): ?>
                <div class="img-preview">
                    <img src="/<?= htmlspecialchars($character['image_url']) ?>"
                         alt="<?= htmlspecialchars($character['name']) ?>">
                    <label class="img-remove-label">
                        <input type="checkbox" name="remove_image" value="1">
                        Hapus gambar ini
                    </label>
                </div>
                <?php endif; ?>

                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp"
                       style="margin-top:<?= !empty($character['image_url']) ? '.65rem' : '0' ?>">
                <p class="form-hint">
                    <?= !empty($character['image_url']) ? 'Upload gambar baru untuk mengganti yang lama. ' : '' ?>
                    JPG, PNG, GIF, atau WEBP · Maks. 2 MB
                </p>
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