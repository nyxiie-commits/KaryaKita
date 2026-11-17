
<?php
// =============================================
// HEADER KARYAKITA
// =============================================

require_once __DIR__ . '/functions.php';

// Jumlah produk dalam keranjang
$cartCount = array_sum($_SESSION['cart'] ?? []);

// Informasi pengguna
$currentUser = user();

// Judul halaman
$pageTitle = $pageTitle ?? 'KaryaKita';

// Notifikasi
$notice = flash();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($pageTitle) ?> | KaryaKita</title>

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="<?= url('assets/css/bootstrap-icons.min.css') ?>"
    >

    <!-- CSS Utama -->
    <link
        rel="stylesheet"
        href="<?= url('assets/css/style.css') ?>"
    >

    <!-- CSS khusus halaman profil pengguna -->
    <link
        rel="stylesheet"
        href="<?= url('assets/css/account.css') ?>?v=20261117-1"
    >
</head>

<body>

    <!-- HEADER -->
    <header class="topbar">
        <div class="container">

            <!-- LOGO -->
            <a href="<?= url('index.php') ?>" class="logo">
                <span class="logo-mark">K</span>

                <span class="logo-content">
                    <span class="logo-name">
                        Karya<span>Kita</span>
                    </span>

                    <small>
                        Belanja Lokal, Dukung Karya Indonesia
                    </small>
                </span>
            </a>

            <!-- PENCARIAN -->
            <form
                class="search"
                action="<?= url('index.php#produk') ?>"
                method="GET"
            >
                <input
                    type="search"
                    name="q"
                    placeholder="Cari produk handmade..."
                    value="<?= e($_GET['q'] ?? '') ?>"
                    aria-label="Cari produk handmade"
                >

                <button type="submit" aria-label="Cari">
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="20"
        height="20"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <circle cx="11" cy="11" r="7"></circle>
        <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
    </svg>
</button>
            </form>

            <!-- NAVIGASI -->
            <nav class="nav" aria-label="Navigasi utama">

                <a href="<?= url('index.php') ?>">
                    Beranda
                </a>

                <a href="<?= url('index.php#kategori') ?>">
                    Kategori
                </a>

                <a href="<?= url('cart.php') ?>">
                    Keranjang (<?= (int) $cartCount ?>)
                </a>

                <?php if ($currentUser): ?>

                    <a href="<?= url('account.php') ?>">
                        Akun
                    </a>

                    <a href="<?= url('logout.php') ?>">
                        Keluar
                    </a>

                <?php else: ?>

                    <a
                        href="<?= url('login.php') ?>"
                        class="nav-login"
                    >
                        Masuk
                    </a>

                <?php endif; ?>

            </nav>

        </div>
    </header>

    <!-- KONTEN HALAMAN -->
    <main class="container">

        <?php if ($notice): ?>
            <div class="notice">
                <?= e($notice) ?>
            </div>
        <?php endif; ?>
