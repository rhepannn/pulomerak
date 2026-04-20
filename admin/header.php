<?php
// Admin header - used by all admin pages
$adminPage = basename($_SERVER['PHP_SELF'], '.php');
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle).' — ' : '' ?>Admin Panel · Kelurahan Pulomerak</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon"><i class="fas fa-building-columns"></i></div>
        <div class="sidebar-brand-text">
            <h3>Admin Panel</h3>
            <p>Kel. Pulomerak</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-label">Navigasi Utama</div>
        <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-link <?= $adminPage === 'index' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <div class="sidebar-label" style="margin-top:12px;">Konten</div>
        <a href="<?= SITE_URL ?>/admin/berita.php" class="sidebar-link <?= str_starts_with($adminPage,'berita') ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i> Berita
        </a>
        <a href="<?= SITE_URL ?>/admin/kegiatan.php" class="sidebar-link <?= str_starts_with($adminPage,'kegiatan') ? 'active' : '' ?>">
            <i class="fas fa-calendar-check"></i> Kegiatan
        </a>
        <a href="<?= SITE_URL ?>/admin/laporan.php" class="sidebar-link <?= str_starts_with($adminPage,'laporan') ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i> Laporan
        </a>
        <a href="<?= SITE_URL ?>/admin/dinamika.php" class="sidebar-link <?= str_starts_with($adminPage,'dinamika') ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Dinamika
        </a>
        <a href="<?= SITE_URL ?>/admin/perpustakaan.php" class="sidebar-link <?= str_starts_with($adminPage,'perpustakaan') ? 'active' : '' ?>">
            <i class="fas fa-book-open"></i> Perpustakaan
        </a>

        <div class="sidebar-label" style="margin-top:12px;">Wilayah</div>
        <a href="<?= SITE_URL ?>/admin/kelurahan.php" class="sidebar-link <?= str_starts_with($adminPage,'kelurahan') ? 'active' : '' ?>">
            <i class="fas fa-city"></i> Kelurahan / RW
        </a>
        <a href="<?= SITE_URL ?>/admin/galeri.php" class="sidebar-link <?= str_starts_with($adminPage,'galeri') ? 'active' : '' ?>">
            <i class="fas fa-images"></i> Galeri
        </a>

        <div class="sidebar-label" style="margin-top:12px;">Pengaturan</div>
        <a href="<?= SITE_URL ?>/" target="_blank" class="sidebar-link">
            <i class="fas fa-external-link-alt"></i> Lihat Website
        </a>
        <a href="<?= SITE_URL ?>/admin/logout.php" class="sidebar-link" style="color:rgba(231,76,60,0.8);"
           onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><i class="fas fa-user"></i></div>
            <div class="sidebar-user-info">
                <h4><?= e($adminName) ?></h4>
                <p><?= e($_SESSION['admin_role'] ?? 'Administrator') ?></p>
            </div>
        </div>
    </div>
</aside>

<!-- MAIN WRAPPER -->
<div class="admin-wrapper">
    <!-- TOP BAR -->
    <header class="admin-topbar">
        <div style="display:flex;align-items:center;gap:16px;">
            <button id="sidebarToggle" style="display:none;background:none;border:none;font-size:1.2rem;color:var(--gray);cursor:pointer;">
                <i class="fas fa-bars"></i>
            </button>
            <span class="topbar-title"><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></span>
        </div>
        <div class="topbar-actions">
            <div class="topbar-admin">
                <i class="fas fa-user-circle"></i>
                <?= e($adminName) ?>
            </div>
        </div>
    </header>

    <div class="admin-content">
        <?= showFlash() ?>
