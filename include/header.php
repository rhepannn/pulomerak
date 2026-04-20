<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE_DESC ?>">
    <meta name="keywords" content="Kelurahan Pulomerak, Cilegon, informasi kelurahan, berita, kegiatan masyarakat">
    <meta name="author" content="Portal Kelurahan Pulomerak">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?><?= SITE_NAME ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/img/favicon.png">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- TOP BAR -->
<div class="topbar">
    <div class="container topbar-inner">
        <div class="topbar-left">
            <span><i class="fas fa-map-marker-alt"></i> Jl. Raya Merak, Kelurahan Pulomerak, Kota Cilegon, Banten</span>
            <span><i class="fas fa-clock"></i> Senin–Jumat: 08.00–16.00 WIB</span>
        </div>
        <div class="topbar-right">
            <a href="tel:+62-254-571234"><i class="fas fa-phone"></i> (0254) 571234</a>
            <a href="mailto:kel.pulomerak@cilegon.go.id"><i class="fas fa-envelope"></i> kel.pulomerak@cilegon.go.id</a>
        </div>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar" id="mainNav">
    <div class="container navbar-inner">
        <!-- LOGO -->
        <a href="<?= SITE_URL ?>/" class="navbar-brand">
            <div class="logo-icon" style="background: transparent; border-radius: 0;">
                <img src="<?= SITE_URL ?>/assets/img/pkk_logo.png" alt="Logo PKK" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" style="object-fit: contain;">
                <div class="logo-fallback" style="display:none"><i class="fas fa-users"></i></div>
            </div>
            <div class="logo-text">
                <span class="logo-title">Tim Penggerak PKK</span>
                <span class="logo-sub">Kelurahan Pulomerak</span>
            </div>
        </a>

        <!-- HAMBURGER (mobile) -->
        <button class="nav-toggle" id="navToggle" aria-label="Toggle Navigation">
            <span></span><span></span><span></span>
        </button>

        <!-- MENU -->
        <ul class="nav-menu" id="navMenu">
            <li class="nav-item <?= $currentPage === 'index' ? 'active' : '' ?>">
                <a href="<?= SITE_URL ?>/" class="nav-link"><i class="fas fa-home"></i> Beranda</a>
            </li>
            <li class="nav-item <?= $currentPage === 'profil' ? 'active' : '' ?>">
                <a href="<?= SITE_URL ?>/profil.php" class="nav-link"><i class="fas fa-landmark"></i> Profil</a>
            </li>
            <li class="nav-item has-dropdown <?= in_array($currentPage, ['kelurahan','kelurahan-detail']) ? 'active' : '' ?>">
                <a href="<?= SITE_URL ?>/kelurahan.php" class="nav-link"><i class="fas fa-city"></i> Kelurahan <i class="fas fa-chevron-down arrow"></i></a>
                <ul class="dropdown">
                    <li><a href="<?= SITE_URL ?>/kelurahan.php"><i class="fas fa-list"></i> Daftar RW/RT</a></li>
                    <li><a href="<?= SITE_URL ?>/kelurahan.php#inovasi"><i class="fas fa-lightbulb"></i> Inovasi</a></li>
                </ul>
            </li>
            <li class="nav-item <?= $currentPage === 'berita' || $currentPage === 'berita-detail' ? 'active' : '' ?>">
                <a href="<?= SITE_URL ?>/berita.php" class="nav-link"><i class="fas fa-newspaper"></i> Berita</a>
            </li>
            <li class="nav-item <?= $currentPage === 'kegiatan' ? 'active' : '' ?>">
                <a href="<?= SITE_URL ?>/kegiatan.php" class="nav-link"><i class="fas fa-calendar-check"></i> Kegiatan</a>
            </li>
            <li class="nav-item has-dropdown <?= in_array($currentPage, ['laporan','dinamika','perpustakaan']) ? 'active' : '' ?>">
                <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Layanan <i class="fas fa-chevron-down arrow"></i></a>
                <ul class="dropdown">
                    <li><a href="<?= SITE_URL ?>/laporan.php"><i class="fas fa-file-alt"></i> Laporan</a></li>
                    <li><a href="<?= SITE_URL ?>/dinamika.php"><i class="fas fa-users"></i> Dinamika Masyarakat</a></li>
                    <li><a href="<?= SITE_URL ?>/perpustakaan.php"><i class="fas fa-book-open"></i> Perpustakaan Digital</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- SPACER for sticky navbar -->
<div class="nav-spacer"></div>
