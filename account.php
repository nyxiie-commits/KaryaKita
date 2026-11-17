<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
need_login();

$currentUser = user();
$userId = (int) $currentUser['id'];
$errors = [];
$success = '';

$stmt = $pdo->prepare('SELECT id, name, email, password, role, created_at FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$userId]);
$profile = $stmt->fetch();

if (!$profile) {
    $_SESSION = [];
    session_regenerate_id(true);
    go('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($name === '' || strlen($name) > 120) {
        $errors[] = 'Nama wajib diisi dan maksimal 120 karakter.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        $errors[] = 'Format email tidak valid.';
    } else {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1');
        $check->execute([$email, $userId]);
        if ($check->fetch()) $errors[] = 'Email tersebut sudah digunakan akun lain.';
    }

    $changingPassword = ($newPassword !== '' || $confirmPassword !== '');
    if ($changingPassword) {
        if (!password_verify($currentPassword, $profile['password'])) {
            $errors[] = 'Password saat ini tidak sesuai.';
        }
        if (strlen($newPassword) < 8) {
            $errors[] = 'Password baru minimal 8 karakter.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Konfirmasi password baru tidak sama.';
        }
    }

    if (!$errors) {
        if ($changingPassword) {
            $update = $pdo->prepare('UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?');
            $update->execute([$name, $email, password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
        } else {
            $update = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
            $update->execute([$name, $email, $userId]);
        }

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        session_regenerate_id(true);

        $stmt->execute([$userId]);
        $profile = $stmt->fetch();
        $success = 'Profil berhasil diperbarui.';
    }
}

$pageTitle = 'Profil Saya';
require __DIR__ . '/header.php';
?>

<section class="account-page">
    <div class="account-heading">
        <div>
            <span class="account-eyebrow">PENGATURAN AKUN</span>
            <h1>Profil Saya</h1>
            <p>Kelola informasi pribadi dan keamanan akun KaryaKita.</p>
        </div>
        <span class="account-role"><?= $profile['role'] === 'admin' ? 'Administrator' : 'Pembeli' ?></span>
    </div>

    <?php if ($success): ?>
        <div class="account-alert account-alert-success" role="status"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="account-alert account-alert-error" role="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="account-grid">
        <aside class="account-side-card">
            <div class="account-avatar"><?= e(strtoupper(substr($profile['name'], 0, 1))) ?></div>
            <h2><?= e($profile['name']) ?></h2>
            <p><?= e($profile['email']) ?></p>
            <span class="account-role"><?= $profile['role'] === 'admin' ? 'Admin KaryaKita' : 'Pelanggan KaryaKita' ?></span>

            <div class="account-side-links">
                <?php if ($profile['role'] === 'admin'): ?>
                    <a href="<?= url('admin/index.php') ?>"><span>Dashboard Admin</span><span>→</span></a>
                    <a href="<?= url('admin/produk.php') ?>"><span>Kelola Produk</span><span>→</span></a>
                    <a href="<?= url('admin/kategori.php') ?>"><span>Kelola Kategori</span><span>→</span></a>
                    <a href="<?= url('admin/pesanan.php') ?>"><span>Kelola Pesanan</span><span>→</span></a>
                <?php else: ?>
                    <a href="<?= url('orders.php') ?>"><span>Pesanan Saya</span><span>→</span></a>
                    <a href="<?= url('cart.php') ?>"><span>Keranjang Belanja</span><span>→</span></a>
                <?php endif; ?>
                <a href="<?= url('index.php') ?>"><span>Kembali ke Beranda</span><span>→</span></a>
                <a href="<?= url('logout.php') ?>" class="account-logout"><span>Keluar dari Akun</span><span>→</span></a>
            </div>
        </aside>

        <section class="account-form-card">
            <div class="account-card-heading">
                <h2>Informasi Pribadi</h2>
                <p>Perbarui nama dan email yang terhubung dengan akun.</p>
            </div>

            <form method="post" action="<?= e(url('account.php')) ?>" class="account-form">
                <?= csrf_field() ?>

                <label for="profile-name">Nama lengkap</label>
                <input id="profile-name" type="text" name="name" maxlength="120" required value="<?= e($_POST['name'] ?? $profile['name']) ?>">

                <label for="profile-email">Alamat email</label>
                <input id="profile-email" type="email" name="email" maxlength="190" required value="<?= e($_POST['email'] ?? $profile['email']) ?>">

                <div class="account-divider"></div>
                <div class="account-card-heading">
                    <h2>Ubah Password</h2>
                    <p>Kosongkan bagian password jika tidak ingin mengubahnya.</p>
                </div>

                <label for="current-password">Password saat ini</label>
                <input id="current-password" type="password" name="current_password" autocomplete="current-password">

                <label for="new-password">Password baru</label>
                <input id="new-password" type="password" name="new_password" minlength="8" autocomplete="new-password">

                <label for="confirm-password">Konfirmasi password baru</label>
                <input id="confirm-password" type="password" name="confirm_password" minlength="8" autocomplete="new-password">

                <button class="btn account-save" type="submit">Simpan Perubahan</button>
            </form>
        </section>
    </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>
