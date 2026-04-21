<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Berita';

$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 9;
$offset  = ($page - 1) * $perPage;
$search  = trim($_GET['q'] ?? '');
$kat     = trim($_GET['kat'] ?? '');

// Count
$whereClause = '1=1';
$params = [];
$types  = '';
if ($search) { $whereClause .= " AND judul LIKE ?"; $params[] = "%$search%"; $types .= 's'; }
if ($kat)    { $whereClause .= " AND kategori = ?"; $params[] = $kat; $types .= 's'; }

$stmtC = $conn->prepare("SELECT COUNT(*) FROM berita WHERE $whereClause");
if ($params) $stmtC->bind_param($types, ...$params);
$stmtC->execute();
$total = $stmtC->get_result()->fetch_row()[0];

// List
$stmtL = $conn->prepare("SELECT * FROM berita WHERE $whereClause ORDER BY tgl_post DESC LIMIT ? OFFSET ?");
$params2 = array_merge($params, [$perPage, $offset]);
$types2  = $types . 'ii';
$stmtL->bind_param($types2, ...$params2);
$stmtL->execute();
$beritaList = $stmtL->get_result();

// Kategori untuk filter
$kats = $conn->query("SELECT DISTINCT kategori FROM berita WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");

include 'include/header.php';
?>

<!-- PAGE HERO -->
<style>
    .page-hero.with-bg::before, .page-hero.with-bg::after { display: none !important; }
</style>
<div class="page-hero with-bg" style="background-image: url('<?= SITE_URL ?>/assets/img/7.png'); background-size: cover; background-position: center; position: relative; min-height: 400px;">
</div>

<section class="section">
    <div class="container">
        <!-- SEARCH + FILTER -->
        <form method="GET" action="berita.php">
            <div class="search-bar">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari berita..." class="search-input" id="searchBerita">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>

        <div class="filter-bar">
            <a href="berita.php" class="filter-btn <?= !$kat ? 'active' : '' ?>">Semua</a>
            <?php while ($k = $kats->fetch_assoc()): ?>
                <a href="berita.php?kat=<?= urlencode($k['kategori']) ?><?= $search ? '&q='.urlencode($search) : '' ?>"
                   class="filter-btn <?= $kat === $k['kategori'] ? 'active' : '' ?>">
                    <?= e($k['kategori']) ?>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- BERITA GRID -->
        <?php if ($beritaList->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Berita Tidak Ditemukan</h3>
                <p>Coba kata kunci lain atau lihat semua berita.</p>
            </div>
        <?php else: ?>
            <div class="grid-3">
                <?php while ($b = $beritaList->fetch_assoc()): ?>
                    <div class="card reveal">
                        <div class="card-img">
                            <img src="<?= getImg($b['gambar'], 'berita') ?>" alt="<?= e($b['judul']) ?>" loading="lazy">
                            <?php if (!empty($b['kategori'])): ?>
                                <span class="card-badge"><?= e($b['kategori']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span><i class="fas fa-calendar"></i> <?= formatTanggal($b['tgl_post']) ?></span>
                                <span><i class="fas fa-user"></i> Admin</span>
                            </div>
                            <h3 class="card-title">
                                <a href="berita-detail.php?id=<?= $b['id'] ?>"><?= e($b['judul']) ?></a>
                            </h3>

                        </div>
                        <div class="card-footer">
                            <a href="berita-detail.php?id=<?= $b['id'] ?>" class="card-link">
                                Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                            <?php if (!empty($b['url_sumber'])): ?>
                                <a href="<?= e($b['url_sumber']) ?>" target="_blank" rel="noopener"
                                   class="card-link" style="color:var(--secondary);font-size:0.8rem;">
                                    <i class="fas fa-external-link-alt"></i> Sumber
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- PAGINATION -->
            <?php
            $baseUrl = 'berita.php?';
            if ($search) $baseUrl .= 'q=' . urlencode($search) . '&';
            if ($kat)    $baseUrl .= 'kat=' . urlencode($kat) . '&';
            echo paginate($total, $page, $perPage, $baseUrl);
            ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
