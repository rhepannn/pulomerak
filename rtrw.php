<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Daftar RW/RT';

$stmt = $conn->prepare("SELECT * FROM kelurahan ORDER BY nama ASC");
$stmt->execute();
$list = $stmt->get_result();

include 'include/header.php';
?>

<!-- PAGE HERO -->
<div class="page-hero compact" style="background-image: url('<?= SITE_URL ?>/assets/img/1.png') !important; background-size: cover; background-position: center; position: relative; min-height: 250px !important;">
    <div class="container" style="position:relative; z-index:10; height:100%; display:flex; align-items:center;">
        <h1 style="color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.5); font-size: 2rem;">Daftar RW/RT</h1>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-map"></i> Wilayah</div>
            <h2 class="section-title">Daftar <span>Kelurahan</span></h2>
            <p class="section-desc">Kecamatan Pulomerak terdiri dari berbagai kelurahan yang tersebar di wilayahnya.</p>
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
                                <div class="kel-stat"><i class="fas fa-map-marker-alt"></i> <?= (int)$k['jumlah_rw'] ?> RW / <?= (int)$k['jumlah_rt'] ?> RT</div>
                                <div class="kel-stat"><i class="fas fa-link"></i> <?= (int)$k['jumlah_link'] ?> Link / <?= (int)$k['dasa_wisma'] ?> DW</div>
                                <div class="kel-stat"><i class="fas fa-users"></i> <?= number_format($k['penduduk']) ?> Jiwa</div>
                            </div>
                            <a href="kelurahan-detail.php?id=<?= $k['id'] ?>" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;">
                                <i class="fas fa-info-circle"></i> Lihat Detail Statistik
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
            <p class="section-desc">Daftar Ketua TP PKK Tingkat Kecamatan dan Kelurahan se-Kecamatan Pulomerak.</p>
        </div>

        <!-- KECAMATAN -->
        <div class="pkk-leader-top">
            <div class="leader-card kec-card reveal">
                <div class="leader-avatar">
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

<?php include 'include/footer.php'; ?>
