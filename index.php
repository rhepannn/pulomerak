<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Beranda';

// Ambil berita terbaru
$stmtB = $conn->prepare("SELECT * FROM berita ORDER BY tgl_post DESC LIMIT 5");
$stmtB->execute();
$beritaList = $stmtB->get_result();

// Ambil kegiatan terbaru
$stmtK = $conn->prepare("SELECT * FROM kegiatan ORDER BY tgl_kegiatan DESC LIMIT 6");
$stmtK->execute();
$kegiatanList = $stmtK->get_result();

include 'include/header.php';
?>

<!-- ═══════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════ -->
<section class="hero" style="background-image: url('<?= SITE_URL ?>/assets/img/pkk_group.jpg');">
    <div class="hero-overlay"></div>
    <div class="container hero-content animate-fade-up">
        <div class="hero-badge">
            <i class="fas fa-star"></i>
            Portal Resmi Tim Penggerak PKK Kelurahan Pulomerak
        </div>
        <h1>Bersama Membangun Keluarga<br><span>Sejahtera & Mandiri</span></h1>
        <p>
            Pusat informasi kegiatan, program inovasi, dan dinamika masyarakat
            yang mendukung pemberdayaan dan kesejahteraan keluarga di Kelurahan Pulomerak, Kota Cilegon.
        </p>
        <div class="hero-actions">
            <a href="profil.php" class="btn btn-primary">
                <i class="fas fa-landmark"></i> Profil Organisasi
            </a>
            <a href="kegiatan.php" class="btn btn-outline">
                <i class="fas fa-camera"></i> Galeri Kegiatan
            </a>
        </div>
    </div>
</section>

<!-- STATISTIK STRIP (Moved under hero to be flat) -->
<div class="hero-stats-strip">
    <div class="container">
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="12450">0</span>
                <span class="hero-stat-label">Jiwa Penduduk</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="8">0</span>
                <span class="hero-stat-label">Rukun Warga (RW)</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="32">0</span>
                <span class="hero-stat-label">Rukun Tetangga (RT)</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="5">0</span>
                <span class="hero-stat-label">Program Inovasi</span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     SHORTCUT MENU
═══════════════════════════════════════════════════════ -->
<div class="shortcuts">
    <div class="container">
        <div class="shortcuts-grid">
            <a href="profil.php" class="shortcut-card">
                <div class="shortcut-icon blue"><i class="fas fa-landmark"></i></div>
                <span class="shortcut-label">Profil</span>
            </a>
            <a href="berita.php" class="shortcut-card">
                <div class="shortcut-icon green"><i class="fas fa-newspaper"></i></div>
                <span class="shortcut-label">Berita</span>
            </a>
            <a href="kegiatan.php" class="shortcut-card">
                <div class="shortcut-icon orange"><i class="fas fa-calendar-check"></i></div>
                <span class="shortcut-label">Kegiatan</span>
            </a>
            <a href="laporan.php" class="shortcut-card">
                <div class="shortcut-icon red"><i class="fas fa-file-alt"></i></div>
                <span class="shortcut-label">Laporan</span>
            </a>
            <a href="dinamika.php" class="shortcut-card">
                <div class="shortcut-icon purple"><i class="fas fa-users"></i></div>
                <span class="shortcut-label">Dinamika</span>
            </a>
            <a href="perpustakaan.php" class="shortcut-card">
                <div class="shortcut-icon teal"><i class="fas fa-book-open"></i></div>
                <span class="shortcut-label">Perpustakaan</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     BERITA TERBARU
═══════════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-newspaper"></i> Informasi Terkini</div>
            <h2 class="section-title">Berita <span>Terbaru</span></h2>
            <p class="section-desc">Informasi dan perkembangan terbaru seputar Kelurahan Pulomerak dan masyarakatnya.</p>
        </div>

        <?php
        $beritaArr = [];
        while ($b = $beritaList->fetch_assoc()) $beritaArr[] = $b;
        ?>

        <?php if (empty($beritaArr)): ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h3>Belum Ada Berita</h3>
                <p>Berita akan segera ditambahkan oleh admin.</p>
            </div>
        <?php else: ?>
            <div class="berita-featured reveal">
                <!-- Berita Utama -->
                <?php $bUtama = $beritaArr[0]; ?>
                <div class="berita-main-card">
                    <img src="<?= getImg($bUtama['gambar'], 'berita') ?>" alt="<?= e($bUtama['judul']) ?>">
                    <div class="berita-main-overlay">
                        <span class="berita-main-cat"><?= e($bUtama['kategori'] ?? 'Berita') ?></span>
                        <h2 class="berita-main-title">
                            <a href="berita-detail.php?id=<?= $bUtama['id'] ?>"><?= e($bUtama['judul']) ?></a>
                        </h2>
                        <div class="berita-main-meta">
                            <span><i class="fas fa-calendar"></i> <?= formatTanggal($bUtama['tgl_post']) ?></span>
                            <span><i class="fas fa-user"></i> Admin</span>
                        </div>
                    </div>
                </div>

                <!-- Berita Samping -->
                <div class="berita-side">
                    <?php for ($i = 1; $i <= min(4, count($beritaArr) - 1); $i++): ?>
                        <?php $b = $beritaArr[$i]; ?>
                        <div class="berita-side-item">
                            <div class="berita-side-img">
                                <img src="<?= getImg($b['gambar'], 'berita') ?>" alt="<?= e($b['judul']) ?>">
                            </div>
                            <div class="berita-side-body">
                                <div class="berita-side-cat"><?= e($b['kategori'] ?? 'Berita') ?></div>
                                <div class="berita-side-title">
                                    <a href="berita-detail.php?id=<?= $b['id'] ?>"><?= e($b['judul']) ?></a>
                                </div>
                                <div class="berita-side-date"><i class="fas fa-clock"></i> <?= formatTanggal($b['tgl_post']) ?></div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="section-footer">
                <a href="berita.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> Lihat Semua Berita
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     STATISTIK COUNTER
═══════════════════════════════════════════════════════ -->
<div class="counter-section">
    <div class="container">
        <div class="counter-grid">
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-home"></i></div>
                <div class="counter-num"><span data-count="3200">0</span><span class="suffix">+</span></div>
                <div class="counter-label">Kepala Keluarga</div>
            </div>
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="counter-num"><span data-count="4">0</span></div>
                <div class="counter-label">Sekolah Aktif</div>
            </div>
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-hospital"></i></div>
                <div class="counter-num"><span data-count="3">0</span></div>
                <div class="counter-label">Fasilitas Kesehatan</div>
            </div>
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-mosque"></i></div>
                <div class="counter-num"><span data-count="12">0</span></div>
                <div class="counter-label">Tempat Ibadah</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     KEGIATAN TERBARU
═══════════════════════════════════════════════════════ -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-calendar-check"></i> Aktivitas</div>
            <h2 class="section-title">Kegiatan <span>Terbaru</span></h2>
            <p class="section-desc">Dokumentasi kegiatan dan program yang telah dilaksanakan oleh Kelurahan Pulomerak.</p>
        </div>

        <?php if ($kegiatanList->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Belum Ada Kegiatan</h3>
                <p>Data kegiatan akan segera ditambahkan.</p>
            </div>
        <?php else: ?>
            <div class="galeri-grid">
                <?php while ($k = $kegiatanList->fetch_assoc()): ?>
                    <div class="galeri-item reveal"
                         data-src="<?= getImg($k['gambar'], 'kegiatan') ?>"
                         data-caption="<?= e($k['judul']) ?>">
                        <img src="<?= getImg($k['gambar'], 'kegiatan') ?>" alt="<?= e($k['judul']) ?>" loading="lazy">
                        <div class="galeri-overlay">
                            <h4><?= e($k['judul']) ?></h4>
                            <p><i class="fas fa-calendar"></i> <?= formatTanggal($k['tgl_kegiatan']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="section-footer">
                <a href="kegiatan.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> Lihat Semua Kegiatan
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     LAYANAN UNGGULAN
═══════════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-cog"></i> Layanan</div>
            <h2 class="section-title">Layanan <span>Unggulan</span></h2>
            <p class="section-desc">Berbagai layanan dan program unggulan Kelurahan Pulomerak untuk masyarakat.</p>
        </div>
        <div class="layanan-list">
            <div class="layanan-item reveal">
                <div class="layanan-icon"><i class="fas fa-file-alt"></i></div>
                <div class="layanan-content">
                    <h3>Administrasi Kependudukan</h3>
                    <p>Pelayanan surat keterangan domisili, pengantar KTP, KK, dan dokumen kependudukan lainnya secara cepat dan tertib.</p>
                    <a href="laporan.php" class="layanan-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="layanan-item reveal animate-delay-1">
                <div class="layanan-icon"><i class="fas fa-lightbulb"></i></div>
                <div class="layanan-content">
                    <h3>Program Inovasi Kelurahan</h3>
                    <p>Berbagai program inovasi untuk meningkatkan kualitas hidup dan pemberdayaan masyarakat Pulomerak yang berkelanjutan.</p>
                    <a href="kelurahan.php#inovasi" class="layanan-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="layanan-item reveal animate-delay-2">
                <div class="layanan-icon"><i class="fas fa-book-open"></i></div>
                <div class="layanan-content">
                    <h3>Perpustakaan Digital</h3>
                    <p>Akses dokumen, arsip, dan referensi digital yang dapat diunduh oleh masyarakat secara gratis dan mudah.</p>
                    <a href="perpustakaan.php" class="layanan-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'include/footer.php'; ?>
