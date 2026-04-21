<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Dashboard';

// Stats
$stats = [
    'berita'       => $conn->query("SELECT COUNT(*) FROM berita")->fetch_row()[0],
    'kegiatan'     => $conn->query("SELECT COUNT(*) FROM kegiatan")->fetch_row()[0],
    'laporan'      => $conn->query("SELECT COUNT(*) FROM laporan")->fetch_row()[0],
    'kelurahan'    => $conn->query("SELECT COUNT(*) FROM kelurahan")->fetch_row()[0],
    'dinamika'     => $conn->query("SELECT COUNT(*) FROM dinamika")->fetch_row()[0],
    'perpustakaan' => $conn->query("SELECT COUNT(*) FROM perpustakaan")->fetch_row()[0],
];

// Berita terbaru
$latestBerita   = $conn->query("SELECT * FROM berita ORDER BY tgl_post DESC LIMIT 5");
// Kegiatan terbaru
$latestKegiatan = $conn->query("SELECT * FROM kegiatan ORDER BY tgl_kegiatan DESC LIMIT 5");

include 'header.php';
?>

<!-- STAT CARDS -->
<div class="page-title">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang di panel admin Portal Kelurahan Pulomerak</p>
    </div>
    <a href="<?= SITE_URL ?>/" target="_blank" class="btn-add">
        <i class="fas fa-external-link-alt"></i> Lihat Website
    </a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-newspaper"></i></div>
        <div class="stat-info">
            <div class="stat-num" data-stat="berita"><?= $stats['berita'] ?></div>
            <div class="stat-label">Total Berita</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-num" data-stat="kegiatan"><?= $stats['kegiatan'] ?></div>
            <div class="stat-label">Total Kegiatan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info">
            <div class="stat-num" data-stat="laporan"><?= $stats['laporan'] ?></div>
            <div class="stat-label">Total Laporan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-city"></i></div>
        <div class="stat-info">
            <div class="stat-num"><?= $stats['kelurahan'] ?></div>
            <div class="stat-label">Data Kelurahan</div>
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-num" data-stat="dinamika"><?= $stats['dinamika'] ?></div>
            <div class="stat-label">Artikel Dinamika</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-book-open"></i></div>
        <div class="stat-info">
            <div class="stat-num"><?= $stats['perpustakaan'] ?></div>
            <div class="stat-label">Dokumen Perpustakaan</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="location.href='berita-add.php'">
        <div class="stat-icon green"><i class="fas fa-plus-circle"></i></div>
        <div class="stat-info">
            <div class="stat-num" style="font-size:1rem;margin-top:4px">Tambah Berita</div>
            <div class="stat-label">Klik untuk tambah</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="location.href='kegiatan-add.php'">
        <div class="stat-icon orange"><i class="fas fa-plus-circle"></i></div>
        <div class="stat-info">
            <div class="stat-num" style="font-size:1rem;margin-top:4px">Tambah Kegiatan</div>
            <div class="stat-label">Klik untuk tambah</div>
        </div>
    </div>
</div>

<!-- TABLES -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;flex-wrap:wrap;">
    <!-- Berita Terbaru -->
    <div class="table-card">
        <div class="table-card-header">
            <h3><i class="fas fa-newspaper" style="color:var(--p)"></i> Berita Terbaru</h3>
            <a href="berita.php" class="btn-add" style="font-size:0.78rem;padding:7px 14px;">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($latestBerita->num_rows === 0): ?>
                        <tr><td colspan="3" style="text-align:center;color:var(--gray);padding:24px">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php while ($b = $latestBerita->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:var(--dark);font-size:0.855rem;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        <?= e($b['judul']) ?>
                                    </div>
                                    <div style="font-size:0.75rem;color:var(--gray)"><?= e($b['kategori'] ?? '-') ?></div>
                                </td>
                                <td style="font-size:0.8rem;white-space:nowrap"><?= formatTanggal($b['tgl_post']) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="berita-edit.php?id=<?= $b['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                        <a href="berita-delete.php?id=<?= $b['id'] ?>" class="btn-delete"
                                           data-confirm="Hapus berita ini?"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kegiatan Terbaru -->
    <div class="table-card">
        <div class="table-card-header">
            <h3><i class="fas fa-calendar-check" style="color:var(--p)"></i> Kegiatan Terbaru</h3>
            <a href="kegiatan.php" class="btn-add" style="font-size:0.78rem;padding:7px 14px;">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($latestKegiatan->num_rows === 0): ?>
                        <tr><td colspan="3" style="text-align:center;color:var(--gray);padding:24px">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php while ($k = $latestKegiatan->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:var(--dark);font-size:0.855rem;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        <?= e($k['judul']) ?>
                                    </div>
                                    <div style="font-size:0.75rem;color:var(--gray)"><?= e($k['lokasi'] ?? '-') ?></div>
                                </td>
                                <td style="font-size:0.8rem;white-space:nowrap"><?= formatTanggal($k['tgl_kegiatan']) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="kegiatan-edit.php?id=<?= $k['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                        <a href="kegiatan-delete.php?id=<?= $k['id'] ?>" class="btn-delete"
                                           data-confirm="Hapus kegiatan ini?"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Shortcut Grid -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:24px;">
    <?php
    $shortcuts = [
        ['berita-add.php',       'fa-plus',         'blue',   'Tambah Berita'],
        ['kegiatan-add.php',     'fa-calendar-plus','green',  'Tambah Kegiatan'],
        ['laporan-add.php',      'fa-file-upload',  'orange', 'Upload Laporan'],
        ['perpustakaan-add.php', 'fa-book-medical', 'purple', 'Tambah Dokumen'],
        ['kelurahan-add.php',    'fa-city',         'blue',   'Tambah Kelurahan'],
        ['dinamika-add.php',     'fa-users',        'red',    'Tambah Dinamika'],
        ['galeri-add.php',       'fa-images',       'green',  'Tambah Galeri'],
        ['logout.php',           'fa-sign-out-alt', 'red',    'Keluar'],
    ];
    foreach ($shortcuts as $s):
    ?>
        <a href="<?= $s[0] ?>" style="display:flex;align-items:center;gap:12px;background:var(--white);border:1px solid var(--border);border-radius:12px;padding:16px;transition:all 0.2s;"
           onmouseover="this.style.boxShadow='0 4px 16px rgba(26,79,160,0.12)';this.style.transform='translateY(-2px)';"
           onmouseout="this.style.boxShadow='';this.style.transform='';"
           <?= $s[0] === 'logout.php' ? 'onclick="return confirm(\'Yakin keluar?\')"' : '' ?>>
            <div class="stat-icon <?= $s[2] ?>" style="width:40px;height:40px;border-radius:10px;font-size:1rem;flex-shrink:0;">
                <i class="fas <?= $s[1] ?>"></i>
            </div>
            <span style="font-size:0.855rem;font-weight:700;color:var(--dark)"><?= $s[3] ?></span>
        </a>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>
