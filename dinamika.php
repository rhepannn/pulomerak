<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Dinamika Masyarakat';

$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 9;
$offset  = ($page - 1) * $perPage;
$search  = trim($_GET['q'] ?? '');

$where = '1=1'; $params = []; $types = '';
if ($search) { $where .= " AND (judul LIKE ? OR isi LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; $types .= 'ss'; }

$stmtC = $conn->prepare("SELECT COUNT(*) FROM dinamika WHERE $where");
if ($params) $stmtC->bind_param($types, ...$params);
$stmtC->execute();
$total = $stmtC->get_result()->fetch_row()[0];

$stmtL = $conn->prepare("SELECT * FROM dinamika WHERE $where ORDER BY tgl_post DESC LIMIT ? OFFSET ?");
$p2 = array_merge($params, [$perPage, $offset]);
$t2 = $types . 'ii';
$stmtL->bind_param($t2, ...$p2);
$stmtL->execute();
$list = $stmtL->get_result();

include 'include/header.php';
?>

<!-- PAGE HERO -->
<style>
    .page-hero.with-bg::before, .page-hero.with-bg::after { display: none !important; }
</style>
<div class="page-hero with-bg" style="background-image: url('<?= SITE_URL ?>/assets/img/10.png'); background-size: cover; background-position: center; position: relative; min-height: 400px;">
</div>

<section class="section">
    <div class="container">
        <form method="GET" action="dinamika.php">
            <div class="search-bar">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari artikel dinamika..." class="search-input">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>

        <?php if ($list->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>Belum Ada Konten</h3>
                <p>Artikel dinamika masyarakat akan segera ditambahkan.</p>
            </div>
        <?php else: ?>
            <div class="artikel-grid">
                <?php while ($d = $list->fetch_assoc()): ?>
                    <div class="card reveal">
                        <?php if (!empty($d['gambar'])): ?>
                            <div class="card-img">
                                <img src="<?= getImg($d['gambar'], 'kegiatan') ?>" alt="<?= e($d['judul']) ?>" loading="lazy">
                                <?php if (!empty($d['kategori'])): ?>
                                    <span class="card-badge"><?= e($d['kategori']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="card-meta">
                                <span><i class="fas fa-calendar"></i> <?= formatTanggal($d['tgl_post']) ?></span>
                                <?php if (!empty($d['penulis'])): ?>
                                    <span><i class="fas fa-user"></i> <?= e($d['penulis']) ?></span>
                                <?php endif; ?>
                            </div>
                            <h3 class="card-title"><?= e($d['judul']) ?></h3>
                            <p class="card-text"><?= truncate($d['isi'], 150) ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="dinamika-detail.php?id=<?= $d['id'] ?>" class="card-link">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
            $bu = 'dinamika.php?';
            if ($search) $bu .= 'q='.urlencode($search).'&';
            echo paginate($total, $page, $perPage, $bu);
            ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
