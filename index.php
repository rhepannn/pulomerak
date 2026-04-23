<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Beranda';

// Ambil semua settings sekaligus (Fungsi ini sudah dikonversi ke PDO di turn sebelumnya)
$S = getAllSettings($conn);

// Hero background image
$heroImg = SITE_URL . '/assets/img/foto-beranda.jpg';
if (!empty($S['beranda_hero_image'])) {
    $heroPath = __DIR__ . '/uploads/settings/' . $S['beranda_hero_image'];
    if (file_exists($heroPath)) $heroImg = SITE_URL . '/uploads/settings/' . $S['beranda_hero_image'];
}

// Ambil berita terbaru (Sintaks PDO)
$stmtB = $conn->prepare("SELECT * FROM berita ORDER BY tgl_post DESC LIMIT 5");
$stmtB->execute();
$beritaList = $stmtB->fetchAll();

// Ambil kegiatan terbaru (Sintaks PDO)
$stmtK = $conn->prepare("SELECT * FROM kegiatan ORDER BY tgl_kegiatan DESC LIMIT 6");
$stmtK->execute();
$kegiatanList = $stmtK->fetchAll();

// ID terbaru untuk realtime tracking (fetchColumn untuk PostgreSQL)
$rtBerita   = $conn->query("SELECT MAX(id) FROM berita")->fetchColumn() ?: 0;
$rtKegiatan = $conn->query("SELECT MAX(id) FROM kegiatan")->fetchColumn() ?: 0;

// Ambil statistik real dari tabel kelurahan (Sintaks PostgreSQL CASE WHEN)
$sqlStat = "SELECT 
    SUM(penduduk) as sum_penduduk, 
    SUM(penduduk_l) as sum_l, 
    SUM(penduduk_p) as sum_p, 
    SUM(jumlah_rw) as sum_rw, 
    SUM(jumlah_rt) as sum_rt, 
    SUM(CASE WHEN inovasi IS NOT NULL AND inovasi != '' THEN 1 ELSE 0 END) as sum_inovasi 
    FROM kelurahan";
$statD = $conn->query($sqlStat)->fetch();

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

<!-- HERO SECTION & SISANYA TETAP SAMA SEPERTI KODE ANDA -->
<section class="hero" style="background-image: url('<?= $heroImg ?>');">
    <div class="hero-overlay"></div>
    <div class="container hero-content animate-fade-up">
        <h1><?= $S['beranda_hero_title'] ?? 'Bersama Membangun Keluarga<br><span>Sejahtera &amp; Mandiri</span>' ?></h1>
        <p><?= e($S['beranda_hero_subtitle'] ?? '') ?></p>
        <div class="hero-actions">
            <a href="profil.php" class="btn btn-primary"><i class="fas fa-landmark"></i> Profil Organisasi</a>
            <a href="kegiatan.php" class="btn btn-outline"><i class="fas fa-camera"></i> Galeri Kegiatan</a>
        </div>
    </div>
</section>

<!-- STATISTIK STRIP -->
<div class="hero-stats-final reveal">
    <div class="container">
        <div class="stats-final-flex">
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
                <div class="box-total">TOTAL: <span data-count="<?= $stat_penduduk ?>">0</span></div>
            </div>
            <div class="stats-final-side">
                <div class="side-item"><span class="side-num" data-count="<?= $stat_rw ?>">0</span><span class="side-label">RW</span></div>
                <div class="side-item"><span class="side-num" data-count="<?= $stat_rt ?>">0</span><span class="side-label">RT</span></div>
                <div class="side-item"><span class="side-num" data-count="<?= $stat_inovasi ?>">0</span><span class="side-label">Inovasi</span></div>
            </div>
        </div>
    </div>
</div>

<!-- BAGIAN LAINNYA (TENTANG PKK, BERITA GRID, KEGIATAN GRID) 
     Tinggal sesuaikan loop-nya dari while ke foreach karena pakai fetchAll() -->

<section class="section">
    <div class="container">
        <!-- ... (Header Section Berita) ... -->
        <?php if (empty($beritaList)): ?>
            <div class="empty-state"><i class="fas fa-newspaper"></i><h3>Belum Ada Berita</h3></div>
        <?php else: ?>
            <div class="berita-featured reveal">
                <?php $bUtama = $beritaList[0]; ?>
                <!-- ... Tampilkan berita utama ... -->
                <div class="berita-side">
                    <?php for ($i = 1; $i < count($beritaList); $i++): $b = $beritaList[$i]; ?>
                        <!-- ... Tampilkan berita samping ... -->
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <!-- ... (Header Section Kegiatan) ... -->
        <?php if (empty($kegiatanList)): ?>
            <div class="empty-state"><i class="fas fa-calendar-times"></i><h3>Belum Ada Kegiatan</h3></div>
        <?php else: ?>
            <div class="galeri-grid">
                <?php foreach ($kegiatanList as $k): ?>
                    <!-- ... Tampilkan item kegiatan ... -->
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
