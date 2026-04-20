<?php
require_once 'include/config.php';
require_once 'include/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(SITE_URL.'/kelurahan.php');

$stmt = $conn->prepare("SELECT * FROM kelurahan WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$kel = $stmt->get_result()->fetch_assoc();
if (!$kel) redirect(SITE_URL.'/kelurahan.php');

$pageTitle = $kel['nama'];

// Kegiatan terkait kelurahan ini
$stmtK = $conn->prepare("SELECT * FROM kegiatan WHERE kelurahan_id = ? ORDER BY tgl_kegiatan DESC LIMIT 6");
$stmtK->bind_param('i', $id);
$stmtK->execute();
$kegiatan = $stmtK->get_result();

include 'include/header.php';
?>

<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right"></i>
            <a href="kelurahan.php">Kelurahan</a> <i class="fas fa-chevron-right"></i>
            <span><?= e($kel['nama']) ?></span>
        </div>
        <h1><i class="fas fa-city"></i> <?= e($kel['nama']) ?></h1>
        <p>Detail informasi, inovasi, dan kegiatan <?= e($kel['nama']) ?>.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="detail-wrap">
            <!-- MAIN -->
            <div class="detail-main reveal">
                <?php if (!empty($kel['gambar'])): ?>
                    <img src="<?= getImg($kel['gambar'], 'kegiatan') ?>" alt="<?= e($kel['nama']) ?>" class="detail-img">
                <?php endif; ?>

                <h1 class="detail-title"><?= e($kel['nama']) ?></h1>

                <div class="detail-meta">
                    <?php if (!empty($kel['jumlah_rw'])): ?>
                        <span><i class="fas fa-map-marker-alt"></i> <?= e($kel['jumlah_rw']) ?> RW</span>
                    <?php endif; ?>
                    <?php if (!empty($kel['jumlah_rt'])): ?>
                        <span><i class="fas fa-home"></i> <?= e($kel['jumlah_rt']) ?> RT</span>
                    <?php endif; ?>
                    <?php if (!empty($kel['penduduk'])): ?>
                        <span><i class="fas fa-users"></i> <?= number_format($kel['penduduk']) ?> Jiwa</span>
                    <?php endif; ?>
                </div>

                <div class="detail-body">
                    <?= nl2br(e($kel['deskripsi'])) ?>
                </div>

                <?php if (!empty($kel['inovasi'])): ?>
                    <div style="margin-top:32px;padding:24px;background:var(--light);border-radius:var(--radius);border-left:4px solid var(--secondary);">
                        <h3 style="font-size:1.1rem;font-weight:800;color:var(--dark);margin-bottom:14px;display:flex;align-items:center;gap:10px;">
                            <i class="fas fa-lightbulb" style="color:var(--accent)"></i> Inovasi Unggulan
                        </h3>
                        <div style="color:var(--text);font-size:0.95rem;line-height:1.8;">
                            <?= nl2br(e($kel['inovasi'])) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div style="margin-top:28px;">
                    <a href="kelurahan.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="detail-sidebar">
                <div class="sidebar-widget">
                    <div class="sidebar-widget-header">
                        <i class="fas fa-info-circle"></i> Info Wilayah
                    </div>
                    <div class="sidebar-widget-body">
                        <?php
                        $infos = [
                            ['icon'=>'fa-map-marker-alt','label'=>'Wilayah',  'val'=>$kel['nama']??'-'],
                            ['icon'=>'fa-layer-group',   'label'=>'Jumlah RW','val'=>($kel['jumlah_rw']??'-').' RW'],
                            ['icon'=>'fa-home',          'label'=>'Jumlah RT','val'=>($kel['jumlah_rt']??'-').' RT'],
                            ['icon'=>'fa-users',         'label'=>'Penduduk', 'val'=>(!empty($kel['penduduk']) ? number_format($kel['penduduk']).' Jiwa' : '-')],
                        ];
                        foreach ($infos as $inf):
                        ?>
                            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);">
                                <i class="fas <?= $inf['icon'] ?>" style="color:var(--primary);width:16px;text-align:center;"></i>
                                <div>
                                    <div style="font-size:0.72rem;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px;"><?= $inf['label'] ?></div>
                                    <div style="font-size:0.9rem;font-weight:700;color:var(--dark);"><?= e($inf['val']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <div class="sidebar-widget-header">
                        <i class="fas fa-city"></i> Kelurahan Lainnya
                    </div>
                    <div class="sidebar-widget-body">
                        <?php
                        $others = $conn->prepare("SELECT id, nama FROM kelurahan WHERE id != ? ORDER BY nama LIMIT 8");
                        $others->bind_param('i', $id);
                        $others->execute();
                        $othersRes = $others->get_result();
                        while ($o = $othersRes->fetch_assoc()):
                        ?>
                            <a href="kelurahan-detail.php?id=<?= $o['id'] ?>"
                               style="display:flex;align-items:center;gap:8px;padding:9px 0;border-bottom:1px solid var(--border);font-size:0.875rem;font-weight:600;color:var(--text);">
                                <i class="fas fa-angle-right" style="color:var(--secondary);font-size:0.75rem;"></i>
                                <?= e($o['nama']) ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </aside>
        </div>

        <!-- KEGIATAN TERKAIT -->
        <?php if ($kegiatan->num_rows > 0): ?>
            <div style="margin-top:56px;">
                <h2 style="font-size:1.3rem;font-weight:800;color:var(--dark);margin-bottom:24px;padding-bottom:12px;border-bottom:2px solid var(--primary);display:inline-block;">
                    <i class="fas fa-calendar-check" style="color:var(--primary)"></i> Kegiatan Terbaru
                </h2>
                <div class="galeri-grid">
                    <?php while ($k = $kegiatan->fetch_assoc()): ?>
                        <div class="galeri-item"
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
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
