<?php
/* characters/create.php — Tambah karakter baru (admin only) */

require_once '../includes/session.php';
require_admin();
require_once '../config/db.php';

$errors = [];
$input  = ['name' => '', 'species' => '', 'first_appearance' => '', 'description' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['name']             = trim($_POST['name'] ?? '');
    $input['species']          = trim($_POST['species'] ?? '');
    $input['first_appearance'] = trim($_POST['first_appearance'] ?? '');
    $input['description']      = trim($_POST['description'] ?? '');

    if ($input['name'] === '')    $errors[] = 'Nama karakter wajib diisi.';
    if ($input['species'] === '') $errors[] = 'Spesies wajib diisi.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO characters (name, species, first_appearance, description)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $input['name'],
            $input['species'],
            $input['first_appearance'],
            $input['description'],
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
        <form method="POST" action="create.php">

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
