<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Inovasi Kecamatan';

include 'include/header.php';
?>

<!-- PAGE HERO -->
<div class="page-hero compact" style="background-image: url('<?= SITE_URL ?>/assets/img/1.png') !important; background-size: cover; background-position: center; position: relative; min-height: 250px !important;">
    <div class="container" style="position:relative; z-index:10; height:100%; display:flex; align-items:center;">
        <h1 style="color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.5); font-size: 2rem;">Inovasi Kecamatan</h1>
    </div>
</div>

<section class="section section-alt" id="inovasi">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-lightbulb"></i> Program Unggulan</div>
            <h2 class="section-title">Inovasi <span>Kecamatan</span></h2>
            <p class="section-desc">Program-program inovatif yang dikembangkan oleh Kecamatan Pulomerak untuk meningkatkan kesejahteraan masyarakat.</p>
        </div>
        <div class="grid-3">
            <div class="card reveal">
                <div class="card-body">
                    <div class="shortcut-icon green" style="margin-bottom:16px;width:56px;height:56px">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="card-title">Urban Farming Pulomerak</h3>
                    <p class="card-text">Program pertanian perkotaan yang memanfaatkan lahan kosong untuk budidaya sayuran dan tanaman pangan guna ketahanan pangan warga.</p>
                </div>
            </div>
            <div class="card reveal animate-delay-1">
                <div class="card-body">
                    <div class="shortcut-icon blue" style="margin-bottom:16px;width:56px;height:56px">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h3 class="card-title">Bank Sampah Digital</h3>
                    <p class="card-text">Sistem pengelolaan sampah berbasis digital yang memungkinkan warga menukar sampah dengan poin yang bisa digunakan untuk membayar tagihan.</p>
                </div>
            </div>
            <div class="card reveal animate-delay-2">
                <div class="card-body">
                    <div class="shortcut-icon orange" style="margin-bottom:16px;width:56px;height:56px">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3 class="card-title">Wifi Publik Gratis</h3>
                    <p class="card-text">Fasilitas internet gratis di titik-titik strategis kelurahan untuk mendukung pendidikan dan produktivitas warga di era digital.</p>
                </div>
            </div>
            <div class="card reveal">
                <div class="card-body">
                    <div class="shortcut-icon purple" style="margin-bottom:16px;width:56px;height:56px">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3 class="card-title">Posyandu Terintegrasi</h3>
                    <p class="card-text">Sistem posyandu terintegrasi digital untuk pemantauan kesehatan ibu dan anak secara real-time dengan notifikasi otomatis.</p>
                </div>
            </div>
            <div class="card reveal animate-delay-1">
                <div class="card-body">
                    <div class="shortcut-icon teal" style="margin-bottom:16px;width:56px;height:56px">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3 class="card-title">Pasar UMKM Digital</h3>
                    <p class="card-text">Platform digital untuk memasarkan produk UMKM lokal Pulomerak ke pasar yang lebih luas melalui media sosial dan e-commerce.</p>
                </div>
            </div>
            <div class="card reveal animate-delay-2">
                <div class="card-body">
                    <div class="shortcut-icon red" style="margin-bottom:16px;width:56px;height:56px">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="card-title">Kampung Aman CCTV</h3>
                    <p class="card-text">Program pemasangan CCTV di titik rawan untuk meningkatkan keamanan lingkungan dan mengurangi angka kriminalitas.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'include/footer.php'; ?>
