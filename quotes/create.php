<?php
/* quotes/create.php — Submit quote baru */

require_once '../includes/session.php';
require_login();
require_once '../config/db.php';

$errors = [];
$input  = ['character_id' => '', 'episode_id' => '', 'content' => ''];

$characters = $pdo->query("SELECT id, name FROM characters ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$episodes   = $pdo->query("SELECT id, title, season, episode_number FROM episodes ORDER BY season, episode_number")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['character_id'] = trim($_POST['character_id'] ?? '');
    $input['episode_id']   = trim($_POST['episode_id']   ?? '');
    $input['content']      = trim($_POST['content']      ?? '');

    if ($input['character_id'] === '')      $errors[] = 'Pilih karakter.';
    if ($input['episode_id']   === '')      $errors[] = 'Pilih episode.';
    if ($input['content']      === '')      $errors[] = 'Isi quote tidak boleh kosong.';
    if (mb_strlen($input['content']) > 500) $errors[] = 'Quote maksimal 500 karakter.';

    if (empty($errors)) {
        $status = is_admin() ? 'approved' : 'pending';
        $stmt   = $pdo->prepare("
            INSERT INTO quotes (character_id, episode_id, content, submitted_by, status)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            (int)$input['character_id'],
            (int)$input['episode_id'],
            $input['content'],
            $_SESSION['user_id'],
            $status,
        ]);

        $_SESSION['flash'] = $status === 'approved'
            ? 'Quote berhasil ditambahkan!'
            : 'Quote kamu berhasil dikirim dan menunggu persetujuan admin.';
        header('Location: index.php');
        exit;
    }
}

$pageTitle = 'Submit Quote — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once '../includes/header.php';
?>

<div class="page-wrapper">

    <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php" class="btn-back">← Kembali</a>
        <h2 class="section-title">Submit Quote</h2>
    </div>

    <?php if (!is_admin()): ?>
    <div class="alert alert-info" style="max-width:620px; margin-bottom:1rem;">
        <i class="fa-solid fa-circle-info"></i>
        Quote yang kamu kirim akan ditampilkan setelah disetujui oleh admin.
    </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?><div>• <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="create.php">

            <div class="form-group">
                <label class="form-label">Karakter <span class="req">*</span></label>
                <select name="character_id" class="form-control" required>
                    <option value="">— Pilih Karakter —</option>
                    <?php foreach ($characters as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $input['character_id'] == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Episode <span class="req">*</span></label>
                <select name="episode_id" class="form-control" required>
                    <option value="">— Pilih Episode —</option>
                    <?php foreach ($episodes as $e): ?>
                    <option value="<?= $e['id'] ?>" <?= $input['episode_id'] == $e['id'] ? 'selected' : '' ?>>
                        S<?= str_pad($e['season'], 2, '0', STR_PAD_LEFT) ?>E<?= str_pad($e['episode_number'], 2, '0', STR_PAD_LEFT) ?> — <?= htmlspecialchars($e['title']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Isi Quote <span class="req">*</span>
                    <span class="char-counter" id="counter">0 / 500</span>
                </label>
                <textarea name="content" id="content" class="form-control" rows="4"
                          maxlength="500" placeholder="Tulis quote di sini..."><?= htmlspecialchars($input['content']) ?></textarea>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Quote
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
