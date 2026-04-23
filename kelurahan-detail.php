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

<div class="page-hero compact" style="position: relative !important; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important; color: white !important; padding: 10px 0 !important; min-height: auto !important; overflow: hidden;">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.7); font-size: 0.8rem;">
            <a href="<?= SITE_URL ?>/" style="color: white;">Beranda</a> <i class="fas fa-chevron-right" style="font-size: 0.6rem; margin: 0 5px;"></i>
            <a href="rtrw.php" style="color: white;">Kelurahan</a> <i class="fas fa-chevron-right" style="font-size: 0.6rem; margin: 0 5px;"></i>
            <span><?= e($kel['nama']) ?></span>
        </div>
        <h1 style="color: white; margin-top: 5px; font-size: 1.5rem;"><i class="fas fa-city"></i> <?= e($kel['nama']) ?></h1>
    </div>
</div>

<section class="section" style="background: white;">
    <div class="container">
        <div class="detail-wrap">
            <!-- MAIN -->
            <div class="detail-main reveal">
                <?php if (!empty($kel['gambar'])): ?>
                    <img src="<?= getImg($kel['gambar'], 'kegiatan') ?>" alt="<?= e($kel['nama']) ?>" class="detail-img">
                <?php endif; ?>

                <div class="detail-body" style="padding-bottom: 40px; border-bottom: 1px solid var(--border);">
                    <?= nl2br(e($kel['deskripsi'])) ?>
                </div>

                <!-- STATISTIK REKAPITULASI 2025 -->
                <div style="margin-top:50px; padding-top:20px;">
                    <h2 style="font-size:1.3rem;font-weight:800;color:var(--dark);margin-bottom:24px;display:flex;align-items:center;gap:12px;">
                        <i class="fas fa-chart-pie" style="color:var(--primary)"></i> Rekapitulasi Data Kesehatan & Wilayah (2025)
                    </h2>
                    
                <!-- STATISTIK REKAPITULASI DATA -->
                <div style="margin-top:50px; padding-top:20px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:15px;">
                        <h2 style="font-size:1.3rem;font-weight:800;color:var(--dark);margin:0;display:flex;align-items:center;gap:12px;">
                            <i class="fas fa-chart-line" style="color:var(--primary)"></i> Rekapitulasi Data Wilayah (2025)
                        </h2>
                        <div style="font-size:0.8rem; color:var(--gray-500); background:var(--gray-100); padding:5px 12px; border-radius:var(--radius-full); font-weight:600;">
                            Update Terakhir: <?= formatTanggal($kel['created_at']) ?>
                        </div>
                    </div>
                    
                    <div class="stat-table-wrap" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border);">
                        <table class="stat-table">
                            <thead>
                                <tr>
                                    <th style="width:30%">Kategori Data</th>
                                    <th>Detail Statistik / Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- WILAYAH -->
                                <tr><td colspan="2" style="background:var(--primary); color:white; font-weight:800; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; text-align:center;">Wilayah Administrasi</td></tr>
                                <tr><td><i class="fas fa-layer-group" style="color:var(--primary); width:20px;"></i> Jumlah RW</td><td><strong><?= (int)$kel['jumlah_rw'] ?></strong> RW</td></tr>
                                <tr><td><i class="fas fa-home" style="color:var(--primary); width:20px;"></i> Jumlah RT</td><td><strong><?= (int)$kel['jumlah_rt'] ?></strong> RT</td></tr>
                                <tr><td><i class="fas fa-link" style="color:var(--primary); width:20px;"></i> Jumlah Link</td><td><strong><?= (int)$kel['jumlah_link'] ?></strong> Link</td></tr>
                                <tr><td><i class="fas fa-users-cog" style="color:var(--primary); width:20px;"></i> Kelompok Dasa Wisma</td><td><strong><?= (int)$kel['dasa_wisma'] ?></strong> DW</td></tr>

                                <!-- KEPENDUDUKAN -->
                                <tr><td colspan="2" style="background:var(--primary); color:white; font-weight:800; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; text-align:center;">Kependudukan</td></tr>
                                <tr><td>Total Penduduk</td><td><strong><?= number_format($kel['penduduk']) ?></strong> Jiwa</td></tr>
                                <tr><td>Penduduk Laki-laki</td><td><strong><?= number_format($kel['penduduk_l']) ?></strong> Jiwa</td></tr>
                                <tr><td>Penduduk Perempuan</td><td><strong><?= number_format($kel['penduduk_p']) ?></strong> Jiwa</td></tr>

                                <!-- KESEHATAN IBU -->
                                <tr><td colspan="2" style="background:var(--primary); color:white; font-weight:800; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; text-align:center;">Data Ibu</td></tr>
                                <tr><td>Ibu Hamil</td><td><strong><?= (int)$kel['ibu_hamil'] ?></strong> Orang</td></tr>
                                <tr><td>Ibu Melahirkan</td><td><strong><?= (int)$kel['ibu_melahirkan'] ?></strong> Orang</td></tr>
                                <tr><td>Ibu Nifas</td><td><strong><?= (int)$kel['ibu_nifas'] ?></strong> Orang</td></tr>
                                <tr><td style="color:#dc2626;">Ibu Meninggal</td><td style="color:#dc2626;"><strong><?= (int)$kel['ibu_meninggal'] ?></strong> Orang</td></tr>

                                <!-- KELAHIRAN -->
                                <tr><td colspan="2" style="background:var(--primary); color:white; font-weight:800; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; text-align:center;">Data Bayi & Akte</td></tr>
                                <tr><td>Bayi Lahir (Laki-laki)</td><td><strong><?= (int)$kel['bayi_lahir_l'] ?></strong> Bayi</td></tr>
                                <tr><td>Bayi Lahir (Perempuan)</td><td><strong><?= (int)$kel['bayi_lahir_p'] ?></strong> Bayi</td></tr>
                                <tr><td style="font-weight:700; color:var(--primary);">Total Kelahiran</td><td><strong><?= (int)$kel['bayi_lahir_l'] + (int)$kel['bayi_lahir_p'] ?></strong> Bayi</td></tr>
                                <tr><td>Memiliki Akte Kelahiran</td><td style="color:#059669;"><strong><?= (int)$kel['akte_ada'] ?></strong> Jiwa</td></tr>
                                <tr><td>Belum Memiliki Akte Kelahiran</td><td style="color:#dc2626;"><strong><?= (int)$kel['akte_tidak'] ?></strong> Jiwa</td></tr>

                                <!-- KEMATIAN -->
                                <tr><td colspan="2" style="background:var(--primary); color:white; font-weight:800; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; text-align:center;">Data Kematian</td></tr>
                                <tr><td>Kematian Bayi (L/P)</td><td>L: <?= (int)$kel['bayi_meninggal_l'] ?> | P: <?= (int)$kel['bayi_meninggal_p'] ?> (Total: <strong><?= (int)$kel['bayi_meninggal_l'] + (int)$kel['bayi_meninggal_p'] ?></strong>)</td></tr>
                                <tr><td>Kematian Balita (L/P)</td><td>L: <?= (int)$kel['balita_meninggal_l'] ?> | P: <?= (int)$kel['balita_meninggal_p'] ?> (Total: <strong><?= (int)$kel['balita_meninggal_l'] + (int)$kel['balita_meninggal_p'] ?></strong>)</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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

                <!-- STRUKTUR PENGURUS TP PKK KELURAHAN -->
                <div class="org-structure-section">
                    <div class="section-header" style="text-align:left; margin-bottom:40px;">
                        <div class="section-label"><i class="fas fa-sitemap"></i> Struktur Kepengurusan</div>
                        <h2 class="section-title">Struktur <span>TP PKK <?= e($kel['nama']) ?></span></h2>
                    </div>

                    <div class="org-tree">
                        <!-- KETUA -->
                        <div class="org-node node-primary">
                            <div class="node-card">
                                <div class="node-avatar"><i class="fas fa-user-tie"></i></div>
                                <div class="node-info">
                                    <div class="node-role">Ketua TP PKK Kelurahan</div>
                                    <div class="node-name"><?= e($kel['ketua_pkk'] ?: 'Ny. [Nama Ketua]') ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="org-connector"></div>

                        <!-- SEKRETARIS & BENDAHARA -->
                        <div class="org-row">
                            <div class="org-node node-secondary">
                                <div class="node-card">
                                    <div class="node-avatar"><i class="fas fa-user"></i></div>
                                    <div class="node-info">
                                        <div class="node-role">Sekretaris</div>
                                        <div class="node-name"><?= e($kel['sekretaris_pkk'] ?: 'Ny. [Nama Sekretaris]') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="org-node node-secondary">
                                <div class="node-card">
                                    <div class="node-avatar"><i class="fas fa-user"></i></div>
                                    <div class="node-info">
                                        <div class="node-role">Bendahara</div>
                                        <div class="node-name"><?= e($kel['bendahara_pkk'] ?: 'Ny. [Nama Bendahara]') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="org-connector"></div>

                        <!-- POKJA GRID -->
                        <div class="org-grid-pokja">
                            <div class="org-node node-pokja">
                                <div class="node-card">
                                    <div class="node-role">Pokja I</div>
                                    <div class="node-name"><?= e($kel['pokja1_pkk'] ?: 'Ny. [Nama Pengurus]') ?></div>
                                </div>
                            </div>
                            <div class="org-node node-pokja">
                                <div class="node-card">
                                    <div class="node-role">Pokja II</div>
                                    <div class="node-name"><?= e($kel['pokja2_pkk'] ?: 'Ny. [Nama Pengurus]') ?></div>
                                </div>
                            </div>
                            <div class="org-node node-pokja">
                                <div class="node-card">
                                    <div class="node-role">Pokja III</div>
                                    <div class="node-name"><?= e($kel['pokja3_pkk'] ?: 'Ny. [Nama Pengurus]') ?></div>
                                </div>
                            </div>
                            <div class="org-node node-pokja">
                                <div class="node-card">
                                    <div class="node-role">Pokja IV</div>
                                    <div class="node-name"><?= e($kel['pokja4_pkk'] ?: 'Ny. [Nama Pengurus]') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
