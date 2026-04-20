<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Perpustakaan Digital';

$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$offset  = ($page - 1) * $perPage;
$search  = trim($_GET['q'] ?? '');
$kat     = trim($_GET['kat'] ?? '');

$where = '1=1'; $params = []; $types = '';
if ($search) { $where .= " AND (judul LIKE ? OR deskripsi LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; $types .= 'ss'; }
if ($kat)    { $where .= " AND kategori = ?"; $params[] = $kat; $types .= 's'; }

$stmtC = $conn->prepare("SELECT COUNT(*) FROM perpustakaan WHERE $where");
if ($params) $stmtC->bind_param($types, ...$params);
$stmtC->execute();
$total = $stmtC->get_result()->fetch_row()[0];

$stmtL = $conn->prepare("SELECT * FROM perpustakaan WHERE $where ORDER BY tgl_upload DESC LIMIT ? OFFSET ?");
$p2 = array_merge($params, [$perPage, $offset]);
$t2 = $types . 'ii';
$stmtL->bind_param($t2, ...$p2);
$stmtL->execute();
$list = $stmtL->get_result();

$kats = $conn->query("SELECT DISTINCT kategori FROM perpustakaan WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");

include 'include/header.php';
?>

<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right"></i> <span>Perpustakaan Digital</span>
        </div>
        <h1><i class="fas fa-book-open"></i> Perpustakaan Digital</h1>
        <p>Koleksi dokumen, arsip, peraturan, dan referensi digital Kelurahan Pulomerak yang dapat diunduh.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <form method="GET" action="perpustakaan.php">
            <?php if ($kat): ?><input type="hidden" name="kat" value="<?= e($kat) ?>"><?php endif; ?>
            <div class="search-bar">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari dokumen/arsip..." class="search-input">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>

        <!-- FILTER -->
        <div class="filter-bar">
            <a href="perpustakaan.php<?= $search ? '?q='.urlencode($search) : '' ?>"
               class="filter-btn <?= !$kat ? 'active' : '' ?>">Semua</a>
            <?php while ($k = $kats->fetch_assoc()): ?>
                <a href="perpustakaan.php?kat=<?= urlencode($k['kategori']) ?><?= $search ? '&q='.urlencode($search) : '' ?>"
                   class="filter-btn <?= $kat === $k['kategori'] ? 'active' : '' ?>">
                    <?= e($k['kategori']) ?>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- LIST DOKUMEN -->
        <?php if ($list->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h3>Dokumen Tidak Ditemukan</h3>
                <p>Coba kata kunci lain atau lihat semua dokumen.</p>
            </div>
        <?php else: ?>
            <div class="doc-list">
                <?php while ($p = $list->fetch_assoc()):
                    $ext = strtolower(pathinfo($p['file'] ?? '', PATHINFO_EXTENSION));
                    $iClass = $ext === 'pdf' ? 'pdf' : ($ext === 'xls' || $ext === 'xlsx' ? 'xls' : 'doc');
                    $icon   = $ext === 'pdf' ? 'fa-file-pdf' : ($ext === 'xls' || $ext === 'xlsx' ? 'fa-file-excel' : 'fa-file-word');
                ?>
                    <div class="doc-item reveal">
                        <div class="doc-icon <?= $iClass ?>">
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="doc-body">
                            <div class="doc-title"><?= e($p['judul']) ?></div>
                            <div class="doc-meta">
                                <span><i class="fas fa-calendar"></i> <?= formatTanggal($p['tgl_upload']) ?></span>
                                <?php if (!empty($p['kategori'])): ?>
                                    <span><i class="fas fa-tag"></i> <?= e($p['kategori']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($ext)): ?>
                                    <span><i class="fas fa-file"></i> <?= strtoupper($ext) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($p['deskripsi'])): ?>
                                <div class="doc-desc"><?= e(truncate($p['deskripsi'], 120)) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="doc-action">
                            <?php if (!empty($p['file'])): ?>
                                <a href="<?= SITE_URL ?>/uploads/perpustakaan/<?= e($p['file']) ?>"
                                   class="btn btn-secondary btn-sm" download>
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            <?php else: ?>
                                <span style="font-size:0.8rem;color:var(--gray)">Tidak ada file</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
            $bu = 'perpustakaan.php?';
            if ($kat)    $bu .= 'kat='.urlencode($kat).'&';
            if ($search) $bu .= 'q='.urlencode($search).'&';
            echo paginate($total, $page, $perPage, $bu);
            ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
