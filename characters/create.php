<?php
/* characters/create.php — Tambah karakter baru (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$errors = [];
$input  = ['name' => '', 'species' => '', 'first_appearance' => '', 'description' => ''];

/* --- Upload helper --- */
function handle_image_upload(): string|false
{
    if (empty($_FILES['image']['name'])) return false;

    $file     = $_FILES['image'];
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize  = 2 * 1024 * 1024; // 2 MB

    if ($file['error'] !== UPLOAD_ERR_OK)       return false;
    if (!in_array($file['type'], $allowed))      return false;
    if ($file['size'] > $maxSize)                return false;

    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('char_', true) . '.' . strtolower($ext);
    $dest     = __DIR__ . '/../assets/images/characters/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) return false;

    return 'assets/images/characters/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['name']             = trim($_POST['name'] ?? '');
    $input['species']          = trim($_POST['species'] ?? '');
    $input['first_appearance'] = trim($_POST['first_appearance'] ?? '');
    $input['description']      = trim($_POST['description'] ?? '');

    if ($input['name'] === '')    $errors[] = 'Nama karakter wajib diisi.';
    if ($input['species'] === '') $errors[] = 'Spesies wajib diisi.';

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
        $imagePath = handle_image_upload(); // false jika tidak ada file

        $stmt = $pdo->prepare("
            INSERT INTO characters (name, species, first_appearance, description, image_url)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $input['name'],
            $input['species'],
            $input['first_appearance'],
            $input['description'],
            $imagePath ?: null,
        ]);

        $_SESSION['flash'] = 'Karakter "' . htmlspecialchars($input['name']) . '" berhasil ditambahkan!';
        header('Location: index.php');
        exit;
    }
}

$pageTitle = 'Tambah Karakter — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php" class="btn-back">← Kembali</a>
        <h2 class="section-title">Tambah Karakter</h2>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
                <div>• <?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="create.php" enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Nama Karakter <span class="req">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($input['name']) ?>"
                       placeholder="cth. Gumball Watterson" required>
            </div>

            <div class="form-group">
                <label class="form-label">Spesies <span class="req">*</span></label>
                <input type="text" name="species" class="form-control"
                       value="<?= htmlspecialchars($input['species']) ?>"
                       placeholder="cth. Blue Cat" required>
            </div>

            <div class="form-group">
                <label class="form-label">First Appearance</label>
                <input type="text" name="first_appearance" class="form-control"
                       value="<?= htmlspecialchars($input['first_appearance']) ?>"
                       placeholder="cth. The DVD">
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4"
                          placeholder="Deskripsi singkat karakter..."><?= htmlspecialchars($input['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Karakter</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <p class="form-hint">JPG, PNG, GIF, atau WEBP · Maks. 2 MB</p>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Simpan Karakter
                </button>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>