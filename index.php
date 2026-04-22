<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Beranda';

// Ambil semua settings sekaligus
$S = getAllSettings($conn);

// Hero background image
$heroImg = SITE_URL . '/assets/img/foto-beranda.jpg';
if (!empty($S['beranda_hero_image'])) {
    $heroPath = __DIR__ . '/uploads/settings/' . $S['beranda_hero_image'];
    if (file_exists($heroPath)) $heroImg = SITE_URL . '/uploads/settings/' . $S['beranda_hero_image'];
}

// Ambil berita terbaru
$stmtB = $conn->prepare("SELECT * FROM berita ORDER BY tgl_post DESC LIMIT 5");
$stmtB->execute();
$beritaList = $stmtB->get_result();

// Ambil kegiatan terbaru
$stmtK = $conn->prepare("SELECT * FROM kegiatan ORDER BY tgl_kegiatan DESC LIMIT 6");
$stmtK->execute();
$kegiatanList = $stmtK->get_result();

// ID terbaru untuk realtime tracking
$rtBerita   = $conn->query("SELECT MAX(id) FROM berita")->fetch_row()[0] ?? 0;
$rtKegiatan = $conn->query("SELECT MAX(id) FROM kegiatan")->fetch_row()[0] ?? 0;

// Ambil statistik real dari tabel kelurahan
$statQ = $conn->query("SELECT SUM(penduduk) as sum_penduduk, SUM(penduduk_l) as sum_l, SUM(penduduk_p) as sum_p, SUM(jumlah_rw) as sum_rw, SUM(jumlah_rt) as sum_rt, SUM(IF(inovasi IS NOT NULL AND inovasi != '', 1, 0)) as sum_inovasi FROM kelurahan");
$statD = $statQ->fetch_assoc();
$stat_penduduk = $statD['sum_penduduk'] ?: 0;
$stat_l = $statD['sum_l'] ?: 0;
$stat_p = $statD['sum_p'] ?: 0;
$stat_rw = $statD['sum_rw'] ?: 0;
$stat_rt = $statD['sum_rt'] ?: 0;
$stat_inovasi = $statD['sum_inovasi'] ?: 0;

include 'include/header.php'; ?>
<script>
window.SITE_URL   = '<?= SITE_URL ?>';
window.RT_BERITA  = <?= (int)$rtBerita ?>;
window.RT_KEGIATAN = <?= (int)$rtKegiatan ?>;
window.RT_PAGE    = 'beranda';
</script>

<!-- ═══════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════ -->
<section class="hero" style="background-image: url('<?= $heroImg ?>');">
    <div class="hero-overlay"></div>
    <div class="container hero-content animate-fade-up">

        <h1><?= $S['beranda_hero_title'] ?? 'Bersama Membangun Keluarga<br><span>Sejahtera & Mandiri</span>' ?></h1>
        <p>
            <?= e($S['beranda_hero_subtitle'] ?? '') ?>
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

<!-- STATISTIK STRIP -->
<div class="hero-stats-final reveal">
    <div class="container">
        <div class="stats-final-flex">
            <!-- Group 1: Exact layout from image but smaller -->
            <div class="stats-final-box">
                <div class="box-title">JIWA PENDUDUK</div>
                <div class="box-grid">
                    <div class="box-item">
                        <div class="box-label">LAKI-LAKI</div>
                        <div class="box-num" data-count="<?= $stat_l ?>">0</div>
                    </div>
                    <div class="box-item">
                        <div class="box-label">PEREMPUAN</div>
                        <div class="box-num" data-count="<?= $stat_p ?>">0</div>
                    </div>
                </div>
                <div class="box-total">
                    TOTAL: <span data-count="<?= $stat_penduduk ?>">0</span>
                </div>
            </div>

            <!-- Group 2: Admin & Inovasi (Side) -->
            <div class="stats-final-side">
                <div class="side-item">
                    <span class="side-num" data-count="<?= $stat_rw ?>">0</span>
                    <span class="side-label">RW</span>
                </div>
                <div class="side-item">
                    <span class="side-num" data-count="<?= $stat_rt ?>">0</span>
                    <span class="side-label">RT</span>
                </div>
                <div class="side-item">
                    <span class="side-num" data-count="<?= $stat_inovasi ?>">0</span>
                    <span class="side-label">Inovasi</span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     TENTANG GERAKAN PKK
═══════════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-info-circle"></i> Tentang PKK</div>
            <h2 class="section-title">Informasi <span>Gerakan PKK</span></h2>
            <p class="section-desc">Mengenal lebih dekat tentang Gerakan Pemberdayaan dan Kesejahteraan Keluarga.</p>
        </div>
        
        <!-- ═══════════════════════════════════════════════════════
             PENGERTIAN & TUJUAN
        ═══════════════════════════════════════════════════════ -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-bottom: 5rem; align-items: start;">
            <div class="reveal">
                <h3 style="color:var(--primary); font-size:1.6rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
                    <i class="fas fa-info-circle"></i> Pengertian Gerakan PKK
                </h3>
                <p style="color:var(--text-secondary); line-height:1.9; font-size:1.1rem; text-align:justify;">
                    <?= e($S['pkk_pengertian'] ?? '') ?>
                </p>
            </div>
            <div class="reveal animate-delay-1">
                <h3 style="color:var(--primary); font-size:1.6rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
                    <i class="fas fa-bullseye"></i> Tujuan Gerakan PKK
                </h3>
                <p style="color:var(--text-secondary); line-height:1.9; font-size:1.1rem; text-align:justify;">
                    <?= e($S['pkk_tujuan'] ?? '') ?>
                </p>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             10 PROGRAM POKOK PKK
        ═══════════════════════════════════════════════════════ -->
        <div class="section-header" style="margin-bottom: 40px;">
            <div class="section-label"><i class="fas fa-star"></i> Program Utama</div>
            <h2 class="section-title">10 Program <span>Pokok PKK</span></h2>
            <p class="section-desc">Pilar utama pemberdayaan keluarga yang diimplementasikan secara komprehensif.</p>
        </div>

        <div class="program-grid reveal">
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-heart"></i></div>
                <div class="program-title">Penghayatan dan Pengamalan Pancasila</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-hands-holding-circle"></i></div>
                <div class="program-title">Gotong Royong</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-utensils"></i></div>
                <div class="program-title">Pangan</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-shirt"></i></div>
                <div class="program-title">Sandang</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-house-chimney"></i></div>
                <div class="program-title">Perumahan dan Tata Laksana Rumah Tangga</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="program-title">Pendidikan dan Keterampilan</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-heart-pulse"></i></div>
                <div class="program-title">Kesehatan</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-shop"></i></div>
                <div class="program-title">Pengembangan Kehidupan Berkoperasi</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-leaf"></i></div>
                <div class="program-title">Kelestarian Lingkungan Hidup</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="program-title">Perencanaan Sehat</div>
            </div>
        </div>

        <div style="margin-top: 5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem;" class="reveal">
            <!-- Sasaran -->
            <div style="background:var(--gray-50); padding:2rem; border-radius:16px; border-left:4px solid var(--primary);">
                <h3 style="color:var(--primary); font-size:1.3rem; margin-bottom:1rem;"><i class="fas fa-users-viewfinder"></i> Sasaran Gerakan PKK</h3>
                <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.7;">
                    <?= e($S['pkk_sasaran'] ?? '') ?>
                </p>
                <ul style="margin-top:10px; font-size:0.9rem; color:var(--text-secondary); display:flex; flex-direction:column; gap:8px;">
                    <li><i class="fas fa-check-circle" style="color:var(--primary); font-size:0.8rem;"></i> <strong>Mental Spiritual:</strong> <?= e($S['pkk_sasaran_mental'] ?? '') ?></li>
                    <li><i class="fas fa-check-circle" style="color:var(--primary); font-size:0.8rem;"></i> <strong>Fisik Material:</strong> <?= e($S['pkk_sasaran_fisik'] ?? '') ?></li>
                </ul>
            </div>
            <!-- Tugas & Fungsi -->
            <div style="background:var(--gray-50); padding:2rem; border-radius:16px; border-left:4px solid var(--accent-light);">
                <h3 style="color:var(--primary); font-size:1.3rem; margin-bottom:1rem;"><i class="fas fa-list-check"></i> Tugas TP PKK Kecamatan</h3>
                <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.7;">
                    <?= e($S['pkk_tugas'] ?? '') ?>
                </p>
                <div style="margin-top:15px;">
                    <a href="profil.php" class="btn btn-outline btn-sm" style="background:#fff;">Lihat Detail Fungsi <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     BERITA TERBARU
═══════════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-newspaper"></i> Informasi Terkini</div>
            <h2 class="section-title">Berita <span>Terbaru</span></h2>
            <p class="section-desc">Informasi dan perkembangan terbaru seputar Kecamatan Pulomerak dan masyarakatnya.</p>
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
     KEGIATAN TERBARU
═══════════════════════════════════════════════════════ -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-calendar-check"></i> Aktivitas</div>
            <h2 class="section-title">Kegiatan <span>Terbaru</span></h2>
            <p class="section-desc">Dokumentasi kegiatan dan program yang telah dilaksanakan oleh Kecamatan Pulomerak.</p>
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


<?php include 'include/footer.php'; ?>
