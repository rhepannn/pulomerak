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
if ($params) bindParamsSafe($stmtC, $types, $params);
$stmtC->execute();
$total = $stmtC->get_result()->fetch_row()[0];

$stmtL = $conn->prepare("SELECT * FROM perpustakaan WHERE $where ORDER BY tgl_upload DESC LIMIT ? OFFSET ?");
$p2 = array_merge($params, [$perPage, $offset]);
$t2 = $types . 'ii';
bindParamsSafe($stmtL, $t2, $p2);
$stmtL->execute();
$list = $stmtL->get_result();

$kats = $conn->query("SELECT DISTINCT kategori FROM perpustakaan WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");

include 'include/header.php';
?>

<div class="page-hero" style="background: linear-gradient(135deg, #001a33 0%, #004080 100%); padding: 100px 0; min-height: auto; position: relative; overflow: hidden; color: white; text-align: center;">
    <div style="position: absolute; inset: 0; opacity: 0.1; background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="breadcrumb" style="justify-content: center; margin-bottom: 25px; opacity: 0.7; font-size: 0.85rem;">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin: 0 10px;"></i> <span>Perpustakaan Digital</span>
        </div>
        <h1 style="font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 800; margin-bottom: 15px; letter-spacing: -1px;">Perpustakaan <span style="color: var(--accent);">Pulomerak</span></h1>
        <p style="max-width: 600px; margin: 0 auto 40px; opacity: 0.9; font-size: 1.1rem; line-height: 1.6;">Akses koleksi dokumen, arsip berita, dan referensi literasi digital terlengkap di satu tempat.</p>
        
        <form method="GET" action="perpustakaan.php" style="max-width: 700px; margin: 0 auto;">
            <?php if ($kat): ?><input type="hidden" name="kat" value="<?= e($kat) ?>"><?php endif; ?>
            <div style="background: white; padding: 6px; border-radius: 100px; display: flex; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Apa yang ingin Anda baca hari ini?" style="flex: 1; border: none; padding: 15px 30px; font-size: 1rem; color: var(--primary); outline: none; background: transparent; border-radius: 100px;">
                <button type="submit" style="background: var(--accent); color: white; border: none; padding: 0 35px; border-radius: 100px; font-weight: 700; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-search"></i> <span>Cari</span>
                </button>
            </div>
        </form>
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

        <!-- FILTER CHIPS -->
        <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-bottom: 50px;">
            <a href="perpustakaan.php<?= $search ? '?q='.urlencode($search) : '' ?>"
               style="padding: 10px 24px; border-radius: 100px; font-size: 0.85rem; font-weight: 700; border: 1px solid var(--border); background: <?= !$kat ? 'var(--primary)' : 'white' ?>; color: <?= !$kat ? 'white' : 'var(--text-secondary)' ?>; transition: 0.3s;">Semua Koleksi</a>
            <?php while ($k = $kats->fetch_assoc()): ?>
                <a href="perpustakaan.php?kat=<?= urlencode($k['kategori']) ?><?= $search ? '&q='.urlencode($search) : '' ?>"
                   style="padding: 10px 24px; border-radius: 100px; font-size: 0.85rem; font-weight: 700; border: 1px solid var(--border); background: <?= $kat === $k['kategori'] ? 'var(--primary)' : 'white' ?>; color: <?= $kat === $k['kategori'] ? 'white' : 'var(--text-secondary)' ?>; transition: 0.3s;">
                    <?= e($k['kategori']) ?>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- LIST DOKUMEN -->
        <?php if ($list->num_rows === 0): ?>
            <div class="empty-state" style="text-align: center; padding: 50px 0;">
                <i class="fas fa-book-open" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                <h3>Dokumen Tidak Ditemukan</h3>
                <p>Coba kata kunci lain atau lihat semua dokumen.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 40px;">
                <?php while ($p = $list->fetch_assoc()):
                    $ext = '';
                    if (!empty($p['file'])) {
                        $ext = strtolower(pathinfo($p['file'], PATHINFO_EXTENSION));
                    }
                    $icon   = $ext === 'pdf' ? 'fa-file-pdf' : ($ext === 'xls' || $ext === 'xlsx' ? 'fa-file-excel' : 'fa-file-word');
                    $color  = $ext === 'pdf' ? '#e74c3c' : ($ext === 'xls' || $ext === 'xlsx' ? '#27ae60' : '#2980b9');
                ?>
                    <div class="book-card reveal" style="display: flex; flex-direction: column; gap: 15px;">
                        <div class="book-cover" style="position: relative; aspect-ratio: 3/4; border-radius: 12px; overflow: hidden; background: #eee; box-shadow: 0 15px 30px rgba(0,0,0,0.1); transition: 0.4s;">
                            <?php if (!empty($p['gambar'])): ?>
                                <img src="<?= SITE_URL ?>/uploads/perpustakaan/<?= e($p['gambar']) ?>" alt="<?= e($p['judul']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 15px; background: linear-gradient(135deg, var(--primary) 0%, #001a33 100%); color: rgba(255,255,255,0.2); padding: 20px; text-align: center;">
                                    <i class="fas fa-book" style="font-size: 3rem;"></i>
                                    <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.6);">PKK Digital Archive</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($ext): ?>
                                <div style="position: absolute; top: 15px; left: 15px; background: <?= $color ?>; color: white; padding: 5px 12px; border-radius: 4px; font-size: 0.7rem; font-weight: 800; font-family: sans-serif;"><?= strtoupper($ext) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <div style="color: var(--accent); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 5px;"><?= !empty($p['kategori']) ? e($p['kategori']) : 'Dokumen' ?></div>
                            <h4 style="font-size: 1rem; color: var(--primary); line-height: 1.4; font-weight: 700; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8em;" title="<?= e($p['judul']) ?>"><?= e($p['judul']) ?></h4>
                            <div style="display: flex; gap: 8px;">
                                <?php if (!empty($p['file'])): ?>
                                    <a href="<?= SITE_URL ?>/uploads/perpustakaan/<?= e($p['file']) ?>" target="_blank" style="flex: 1.5; background: var(--primary); color: white; text-align: center; padding: 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <i class="fas fa-eye"></i> Baca
                                    </a>
                                    <a href="<?= SITE_URL ?>/uploads/perpustakaan/<?= e($p['file']) ?>" download style="flex: 1; background: var(--gray-100); color: var(--text-secondary); text-align: center; padding: 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; transition: 0.3s;" title="Unduh">
                                        <i class="fas fa-download"></i>
                                    </a>
                                <?php else: ?>
                                    <div style="flex: 1; background: var(--gray-100); color: var(--text-secondary); text-align: center; padding: 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 700;">File Tidak Tersedia</div>
                                <?php endif; ?>
                            </div>
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
<style>
.book-cover:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.15); }
.book-card:hover h4 { color: var(--accent); }
@media (max-width: 576px) {
    .page-hero { padding: 60px 0; }
}
</style>

<?php include 'include/footer.php'; ?>
