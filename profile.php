<?php
/* profile.php — Halaman profil pengguna */

require_once 'includes/session.php';
require_login();

require_once 'config/db.php';

$stmtUser = $pdo->prepare("
    SELECT id, username, email, role, created_at
    FROM users
    WHERE id = :id
");
$stmtUser->execute([':id' => $_SESSION['user_id']]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$stmtStats = $pdo->prepare("
    SELECT
        COUNT(*)                 AS total,
        SUM(status = 'approved') AS approved,
        SUM(status = 'pending')  AS pending,
        SUM(status = 'rejected') AS rejected
    FROM quotes
    WHERE submitted_by = :id
");
$stmtStats->execute([':id' => $_SESSION['user_id']]);
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

$stmtRecent = $pdo->prepare("
    SELECT q.id, q.content, q.status, q.created_at,
           c.name  AS character_name,
           e.title AS episode_title
    FROM   quotes q
    JOIN   characters c ON q.character_id = c.id
    JOIN   episodes   e ON q.episode_id   = e.id
    WHERE  q.submitted_by = :id
    ORDER BY q.created_at DESC
    LIMIT 5
");
$stmtRecent->execute([':id' => $_SESSION['user_id']]);
$recentQuotes = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Profile — TAWOG Fan Site';
$bodyClass = 'bg-tawog';
require_once 'includes/header.php';
?>

<div class="page-wrapper">

    <div class="section-header">
        <h2 class="section-title">
            <i class="fa-solid fa-id-card" style="margin-right:.5rem; opacity:.85;"></i>My Profile
        </h2>
    </div>

    <div class="profile-layout">

        <!-- SIDEBAR -->
        <aside class="profile-sidebar">

            <div class="profile-avatar">
                <?= strtoupper(mb_substr(htmlspecialchars($user['username']), 0, 1)) ?>
            </div>

            <div class="profile-username">
                <?= htmlspecialchars($user['username']) ?>
            </div>

            <?php $roleClass = $user['role'] === 'admin' ? 'profile-role--admin' : 'profile-role--user'; ?>
            <span class="profile-role <?= $roleClass ?>">
                <i class="fa-solid <?= $user['role'] === 'admin' ? 'fa-shield-halved' : 'fa-user' ?>"></i>
                <?= ucfirst(htmlspecialchars($user['role'])) ?>
            </span>

            <div class="profile-stats">
                <div class="profile-stat">
                    <span class="profile-stat__num"><?= (int)($stats['total'] ?? 0) ?></span>
                    <span class="profile-stat__label">Total<br>Quotes</span>
                </div>
                <div class="profile-stat">
                    <span class="profile-stat__num" style="color:#155724;"><?= (int)($stats['approved'] ?? 0) ?></span>
                    <span class="profile-stat__label">Approved</span>
                </div>
                <div class="profile-stat">
                    <span class="profile-stat__num" style="color:#856404;"><?= (int)($stats['pending'] ?? 0) ?></span>
                    <span class="profile-stat__label">Pending</span>
                </div>
            </div>

            <a href="/quotes/create.php" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:.25rem;">
                <i class="fa-solid fa-quote-left"></i> Submit Quote
            </a>

            <a href="/quotes/index.php" class="btn btn-outline" style="width:100%; justify-content:center;">
                <i class="fa-solid fa-list"></i> All Quotes
            </a>

        </aside>
        <!-- /SIDEBAR -->

        <!-- MAIN CONTENT -->
        <div class="profile-main">

            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['flash']) ?>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <!-- Account Info -->
            <div class="profile-card">
                <div class="profile-card__title">
                    <i class="fa-solid fa-circle-info" style="color:var(--color-primary);"></i>
                    Account Info
                </div>

                <table class="profile-info-table">
                    <tr>
                        <td>User ID</td>
                        <td><?= (int)$user['id'] ?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><strong style="font-weight:700;"><?= htmlspecialchars($user['username']) ?></strong></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>
                            <span class="profile-role <?= $roleClass ?>" style="font-size:.78rem; padding:.2rem .65rem;">
                                <i class="fa-solid <?= $user['role'] === 'admin' ? 'fa-shield-halved' : 'fa-user' ?>"></i>
                                <?= ucfirst(htmlspecialchars($user['role'])) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Member since</td>
                        <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                    </tr>
                </table>
            </div>

            <!-- Recent Quotes -->
            <div class="profile-card">
                <div class="profile-card__title">
                    <i class="fa-solid fa-quote-left" style="color:var(--color-primary);"></i>
                    My Recent Quotes
                    <?php if ((int)($stats['total'] ?? 0) > 0): ?>
                        <span class="text-muted" style="font-family:var(--font-body); font-size:.8rem; font-weight:400; margin-left:.5rem;">
                            <?= (int)$stats['total'] ?> total
                        </span>
                    <?php endif; ?>
                </div>

                <?php if (empty($recentQuotes)): ?>
                    <div style="text-align:center; padding:2rem 1rem;">
                        <i class="fa-regular fa-comment-dots" style="font-size:2.8rem; color:#ccd6dd; display:block; margin-bottom:.85rem;"></i>
                        <p style="color:#aaa; font-size:.9rem; margin-bottom:1rem;">
                            Kamu belum submit quote apapun.
                        </p>
                        <a href="/quotes/create.php" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus"></i> Submit Quote Pertama
                        </a>
                    </div>

                <?php else: ?>
                    <div style="display:flex; flex-direction:column; gap:.85rem;">
                        <?php foreach ($recentQuotes as $q):
                            $sc = match($q['status']) {
                                'approved' => 'quote-status--approved',
                                'rejected' => 'quote-status--rejected',
                                default    => 'quote-status--pending',
                            };
                        ?>
                        <div class="quote-card" style="box-shadow:none; border:1px solid #eef1f4; background:#f8fafc; padding:.9rem 1.1rem;">
                            <span class="quote-status <?= $sc ?>">
                                <?= ucfirst($q['status']) ?>
                            </span>

                            <p class="quote-card__content" style="font-size:.9rem; padding-right:5.5rem;">
                                <i class="fa-solid fa-quote-left quote-icon"></i><?= htmlspecialchars($q['content']) ?>
                            </p>

                            <div class="quote-card__meta">
                                <span class="quote-character">
                                    <i class="fa-solid fa-person"></i>
                                    <?= htmlspecialchars($q['character_name']) ?>
                                </span>
                                <span class="quote-episode">
                                    <i class="fa-solid fa-tv"></i>
                                    <?= htmlspecialchars($q['episode_title']) ?>
                                </span>
                                <span class="quote-submitter">
                                    <i class="fa-regular fa-clock"></i>
                                    <?= date('d M Y', strtotime($q['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ((int)($stats['total'] ?? 0) > 5): ?>
                    <div style="text-align:center; margin-top:1.1rem;">
                        <a href="/quotes/index.php" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-arrow-right"></i>
                            Lihat semua <?= (int)$stats['total'] ?> quote
                        </a>
                    </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>
        <!-- /MAIN CONTENT -->

    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
