<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Laporan';

$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset  = ($page - 1) * $perPage;
$search  = trim($_GET['q'] ?? '');

$where = '1=1'; $params = []; $types = '';
if ($search) { $where .= " AND (judul LIKE ? OR deskripsi LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; $types .= 'ss'; }

$stmtC = $conn->prepare("SELECT COUNT(*) FROM laporan WHERE $where");
if ($params) bindParamsSafe($stmtC, $types, $params);
$stmtC->execute();
$total = $stmtC->get_result()->fetch_row()[0];

$stmtL = $conn->prepare("SELECT * FROM laporan WHERE $where ORDER BY tgl_upload DESC LIMIT ? OFFSET ?");
$p2 = array_merge($params, [$perPage, $offset]);
$t2 = $types . 'ii';
bindParamsSafe($stmtL, $t2, $p2);
$stmtL->execute();
$list = $stmtL->get_result();

include 'include/header.php';
?>

<!-- PAGE HERO -->
<style>
    .page-hero.with-bg::before, .page-hero.with-bg::after { display: none !important; }
</style>
<div class="page-hero with-bg" style="background-image: url('<?= SITE_URL ?>/assets/img/9.png'); background-size: cover; background-position: center; position: relative; min-height: 400px;">
</div>

<section class="section">
    <div class="container">
        <form method="GET" action="laporan.php">
            <div class="search-bar">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari laporan..." class="search-input">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>

        <?php if ($list->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>Belum Ada Laporan</h3>
                <p>Laporan akan segera ditambahkan oleh admin.</p>
            </div>
        <?php else: ?>
            <div class="doc-list">
                <?php while ($l = $list->fetch_assoc()):
                    $ext = strtolower(pathinfo($l['file'] ?? '', PATHINFO_EXTENSION));
                    $iconClass = in_array($ext, ['pdf']) ? 'pdf' : (in_array($ext, ['doc','docx']) ? 'doc' : 'xls');
                    $icon = $ext === 'pdf' ? 'fa-file-pdf' : ($ext === 'xls' || $ext === 'xlsx' ? 'fa-file-excel' : 'fa-file-word');
                ?>
                    <div class="doc-item reveal">
                        <div class="doc-icon <?= $iconClass ?>">
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="doc-body">
                            <div class="doc-title"><?= e($l['judul']) ?></div>
                            <div class="doc-meta">
                                <span><i class="fas fa-calendar"></i> <?= formatTanggal($l['tgl_upload']) ?></span>
                                <?php if (!empty($ext)): ?>
                                    <span><i class="fas fa-file"></i> <?= strtoupper($ext) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($l['deskripsi'])): ?>
                                <div class="doc-desc"><?= e(truncate($l['deskripsi'], 120)) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="doc-action">
                            <?php if (!empty($l['file'])): ?>
                                <a href="<?= SITE_URL ?>/uploads/laporan/<?= e($l['file']) ?>"
                                   class="btn btn-secondary btn-sm" download>
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            <?php else: ?>
                                <span style="font-size:0.8rem;color:var(--gray)">File tidak tersedia</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
            $bu = 'laporan.php?';
            if ($search) $bu .= 'q='.urlencode($search).'&';
            echo paginate($total, $page, $perPage, $bu);
            ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
