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
