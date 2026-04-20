<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Kegiatan';

// Ambil semua kategori
$kats = $conn->query("SELECT DISTINCT kategori FROM kegiatan WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");

$kat    = trim($_GET['kat'] ?? '');
$search = trim($_GET['q'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$offset  = ($page - 1) * $perPage;

$where  = '1=1';
$params = [];
$types  = '';
if ($kat)    { $where .= " AND kategori = ?"; $params[] = $kat; $types .= 's'; }
if ($search) { $where .= " AND judul LIKE ?"; $params[] = "%$search%"; $types .= 's'; }

$stmtC = $conn->prepare("SELECT COUNT(*) FROM kegiatan WHERE $where");
if ($params) $stmtC->bind_param($types, ...$params);
$stmtC->execute();
$total = $stmtC->get_result()->fetch_row()[0];

$stmtL = $conn->prepare("SELECT * FROM kegiatan WHERE $where ORDER BY tgl_kegiatan DESC LIMIT ? OFFSET ?");
$p2 = array_merge($params, [$perPage, $offset]);
$t2 = $types . 'ii';
$stmtL->bind_param($t2, ...$p2);
$stmtL->execute();
$list = $stmtL->get_result();

include 'include/header.php';
?>

<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right"></i> <span>Kegiatan</span>
        </div>
        <h1><i class="fas fa-calendar-check"></i> Galeri Kegiatan</h1>
        <p>Dokumentasi kegiatan dan program yang telah dilaksanakan Kelurahan Pulomerak.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- SEARCH -->
        <form method="GET" action="kegiatan.php">
            <?php if ($kat): ?><input type="hidden" name="kat" value="<?= e($kat) ?>"><?php endif; ?>
            <div class="search-bar">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari kegiatan..." class="search-input">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>

        <!-- FILTER KATEGORI -->
        <div class="filter-bar">
            <a href="kegiatan.php<?= $search ? '?q='.urlencode($search) : '' ?>"
               class="filter-btn <?= !$kat ? 'active' : '' ?>" data-filter="all">Semua</a>
            <?php
            $kats->data_seek(0);
            while ($k = $kats->fetch_assoc()):
            ?>
                <a href="kegiatan.php?kat=<?= urlencode($k['kategori']) ?><?= $search ? '&q='.urlencode($search) : '' ?>"
                   class="filter-btn <?= $kat === $k['kategori'] ? 'active' : '' ?>">
                    <?= e($k['kategori']) ?>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- GALERI GRID -->
        <?php if ($list->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Belum Ada Kegiatan</h3>
                <p>Data kegiatan akan segera ditambahkan.</p>
            </div>
        <?php else: ?>
            <div class="galeri-grid">
                <?php while ($k = $list->fetch_assoc()): ?>
                    <div class="galeri-item reveal"
                         data-src="<?= getImg($k['gambar'], 'kegiatan') ?>"
                         data-caption="<?= e($k['judul']) ?> — <?= formatTanggal($k['tgl_kegiatan']) ?>"
                         data-category="<?= e($k['kategori'] ?? '') ?>">
                        <img src="<?= getImg($k['gambar'], 'kegiatan') ?>" alt="<?= e($k['judul']) ?>" loading="lazy">
                        <div class="galeri-overlay">
                            <?php if (!empty($k['kategori'])): ?>
                                <span class="tag tag-green" style="margin-bottom:8px;display:inline-block;font-size:0.7rem"><?= e($k['kategori']) ?></span>
                            <?php endif; ?>
                            <h4><?= e($k['judul']) ?></h4>
                            <p><i class="fas fa-calendar"></i> <?= formatTanggal($k['tgl_kegiatan']) ?></p>
                            <?php if (!empty($k['lokasi'])): ?>
                                <p><i class="fas fa-map-marker-alt"></i> <?= e($k['lokasi']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- PAGINATION -->
            <?php
            $bu = 'kegiatan.php?';
            if ($kat)    $bu .= 'kat='.urlencode($kat).'&';
            if ($search) $bu .= 'q='.urlencode($search).'&';
            echo paginate($total, $page, $perPage, $bu);
            ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
