<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Kelurahan';

$stmt = $conn->prepare("SELECT * FROM kelurahan ORDER BY nama ASC");
$stmt->execute();
$list = $stmt->get_result();

include 'include/header.php';
?>

<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right"></i> <span>Kelurahan</span>
        </div>
        <h1><i class="fas fa-city"></i> Wilayah Kelurahan Pulomerak</h1>
        <p>Informasi detail tentang wilayah, inovasi, dan kegiatan di setiap RW/lingkungan Kelurahan Pulomerak.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-map"></i> Wilayah</div>
            <h2 class="section-title">Daftar <span>Kelurahan / RW</span></h2>
            <p class="section-desc">Kelurahan Pulomerak terdiri dari 8 Rukun Warga (RW) yang tersebar di wilayahnya.</p>
        </div>

        <?php if ($list->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-city"></i>
                <h3>Data Belum Tersedia</h3>
                <p>Data kelurahan akan segera ditambahkan oleh admin.</p>
            </div>
        <?php else: ?>
            <div class="kel-grid">
                <?php while ($k = $list->fetch_assoc()): ?>
                    <div class="kel-card reveal">
                        <div class="kel-card-img">
                            <img src="<?= getImg($k['gambar'], 'kegiatan') ?>" alt="<?= e($k['nama']) ?>" loading="lazy">
                        </div>
                        <div class="kel-card-body">
                            <div class="kel-card-name"><?= e($k['nama']) ?></div>
                            <div class="kel-card-desc"><?= truncate($k['deskripsi'], 120) ?></div>
                            <div class="kel-stats">
                                <?php if (!empty($k['jumlah_rw'])): ?>
                                    <div class="kel-stat"><i class="fas fa-map-marker-alt"></i> <?= e($k['jumlah_rw']) ?> RW</div>
                                <?php endif; ?>
                                <?php if (!empty($k['jumlah_rt'])): ?>
                                    <div class="kel-stat"><i class="fas fa-home"></i> <?= e($k['jumlah_rt']) ?> RT</div>
                                <?php endif; ?>
                                <?php if (!empty($k['penduduk'])): ?>
                                    <div class="kel-stat"><i class="fas fa-users"></i> <?= number_format($k['penduduk']) ?> jiwa</div>
                                <?php endif; ?>
                            </div>
                            <a href="kelurahan-detail.php?id=<?= $k['id'] ?>" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;">
                                <i class="fas fa-info-circle"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     DAFTAR KETUA TP PKK
═══════════════════════════════════════════════════════ -->
<section class="section" style="background-color: var(--light-gray);">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-sitemap"></i> Struktur Organisasi</div>
            <h2 class="section-title">Ketua TP PKK <span>Wilayah</span></h2>
            <p class="section-desc">Daftar Ketua TP PKK Tingkat Kecamatan dan Kelurahan se-Pulomerak.</p>
        </div>

        <style>
            .pkk-leader-top { display: flex; justify-content: center; margin-bottom: 2rem; }
            .leader-card {
                background: white; border-radius: 16px; overflow: hidden;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                text-align: center; padding: 40px 20px;
                border: 1px solid rgba(0,0,0,0.03);
            }
            .leader-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
            .leader-card.kec-card { max-width: 450px; width: 100%; border-top: 5px solid var(--accent); }
            .kel-card-leader { border-top: 5px solid var(--primary); }
            .leader-avatar {
                width: 120px; height: 120px; border-radius: 50%;
                margin: 0 auto 25px; background: #f8f9fa;
                display: flex; align-items: center; justify-content: center;
                overflow: hidden; border: 4px solid white;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            }
            .leader-avatar img { width: 100%; height: 100%; object-fit: cover; }
            .avatar-placeholder { font-size: 3.5rem; color: #cbd5e1; }
            .leader-info .leader-title { font-size: 0.95rem; color: var(--text-light); margin-bottom: 8px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
            .leader-info .leader-name { font-size: 1.4rem; color: var(--primary); font-weight: 700; margin: 0; }
            .pkk-leaders-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 30px; }
        </style>

        <!-- KECAMATAN -->
        <div class="pkk-leader-top">
            <div class="leader-card kec-card reveal">
                <div class="leader-avatar">
                   <!-- <img src="<?= SITE_URL ?>/assets/img/placeholder.jpg" alt="Ny. Ma'nawiyah"> -->
                   <div class="avatar-placeholder"><i class="fas fa-user-tie"></i></div>
                </div>
                <div class="leader-info">
                    <div class="leader-title">Ketua TP PKK Kecamatan Pulomerak</div>
                    <h3 class="leader-name">Ny. Ma'nawiyah</h3>
                </div>
            </div>
        </div>

        <!-- KELURAHAN -->
        <div class="pkk-leaders-grid">
            <?php 
            if($list->num_rows > 0) {
                mysqli_data_seek($list, 0);
                $delay = 1;
                while ($k = $list->fetch_assoc()):
            ?>
            <div class="leader-card kel-card-leader reveal animate-delay-<?= $delay++ ?>">
                <div class="leader-avatar">
                   <?php if (!empty($k['gambar'])): ?>
                       <img src="<?= getImg($k['gambar'], 'kegiatan') ?>" alt="<?= e($k['nama']) ?>">
                   <?php else: ?>
                       <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
                   <?php endif; ?>
                </div>
                <div class="leader-info">
                    <div class="leader-title">Ketua TP PKK<br><?= e($k['nama']) ?></div>
                    <h3 class="leader-name" <?= empty($k['ketua_pkk']) ? 'style="color:#94a3b8; font-style:italic; font-size:1.1rem; margin-top:10px;"' : 'style="margin-top:10px;"' ?>>
                        <?= e($k['ketua_pkk'] ?: '[Belum Diatur]') ?>
                    </h3>
                </div>
            </div>
            <?php 
                endwhile;
            } 
            ?>
        </div>
    </div>
</section>

<!-- INOVASI SECTION ANCHOR -->
<section class="section section-alt" id="inovasi">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-lightbulb"></i> Program Unggulan</div>
            <h2 class="section-title">Inovasi <span>Kelurahan</span></h2>
            <p class="section-desc">Program-program inovatif yang dikembangkan oleh Kelurahan Pulomerak untuk meningkatkan kesejahteraan masyarakat.</p>
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
