<?php
require_once 'include/config.php';
require_once 'include/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { redirect(SITE_URL . '/berita.php'); }

$stmt = $conn->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$berita = $stmt->get_result()->fetch_assoc();
if (!$berita) { redirect(SITE_URL . '/berita.php'); }

$pageTitle = $berita['judul'];

// Berita terkait (kategori sama, bukan id ini)
$stmtR = $conn->prepare("SELECT * FROM berita WHERE kategori = ? AND id != ? ORDER BY tgl_post DESC LIMIT 5");
$stmtR->bind_param('si', $berita['kategori'], $id);
$stmtR->execute();
$related = $stmtR->get_result();

// Berita terbaru (sidebar)
$stmtN = $conn->prepare("SELECT * FROM berita ORDER BY tgl_post DESC LIMIT 5");
$stmtN->execute();
$latest = $stmtN->get_result();

include 'include/header.php';
?>

<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right"></i>
            <a href="<?= SITE_URL ?>/berita.php">Berita</a> <i class="fas fa-chevron-right"></i>
            <span><?= truncate($berita['judul'], 40) ?></span>
        </div>
        <h1><i class="fas fa-newspaper"></i> Detail Berita</h1>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="detail-wrap">
            <!-- KONTEN UTAMA -->
            <div class="detail-main reveal">
                <?php if (!empty($berita['gambar'])): ?>
                    <img src="<?= getImg($berita['gambar'], 'berita') ?>" alt="<?= e($berita['judul']) ?>" class="detail-img">
                <?php endif; ?>

                <?php if (!empty($berita['kategori'])): ?>
                    <span class="tag tag-blue" style="margin-bottom:14px;display:inline-block;">
                        <?= e($berita['kategori']) ?>
                    </span>
                <?php endif; ?>

                <h1 class="detail-title"><?= e($berita['judul']) ?></h1>

                <div class="detail-meta">
                    <span><i class="fas fa-calendar"></i> <?= formatTanggal($berita['tgl_post']) ?></span>
                    <span><i class="fas fa-user"></i> Admin Kelurahan</span>
                    <?php if (!empty($berita['url_sumber'])): ?>
                        <span>
                            <i class="fas fa-link"></i>
                            <a href="<?= e($berita['url_sumber']) ?>" target="_blank" rel="noopener"
                               style="color:var(--primary);font-weight:600;">
                                Sumber Berita <i class="fas fa-external-link-alt" style="font-size:0.7rem"></i>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>



                <!-- Share -->
                <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--border);display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <span style="font-weight:600;font-size:0.875rem;color:var(--gray-dark)">Bagikan:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL.'/berita-detail.php?id='.$id) ?>"
                       target="_blank" rel="noopener"
                       style="background:#1877f2;color:#fff;padding:7px 16px;border-radius:8px;font-size:0.82rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($berita['judul']) ?>&url=<?= urlencode(SITE_URL.'/berita-detail.php?id='.$id) ?>"
                       target="_blank" rel="noopener"
                       style="background:#1da1f2;color:#fff;padding:7px 16px;border-radius:8px;font-size:0.82rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://wa.me/?text=<?= urlencode($berita['judul'].' - '.SITE_URL.'/berita-detail.php?id='.$id) ?>"
                       target="_blank" rel="noopener"
                       style="background:#25d366;color:#fff;padding:7px 16px;border-radius:8px;font-size:0.82rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="detail-sidebar">
                <div class="sidebar-widget">
                    <div class="sidebar-widget-header">
                        <i class="fas fa-clock"></i> Berita Terbaru
                    </div>
                    <div class="sidebar-widget-body">
                        <div class="berita-mini-list">
                            <?php while ($n = $latest->fetch_assoc()): ?>
                                <?php if ($n['id'] == $id) continue; ?>
                                <div class="berita-mini-item">
                                    <div class="berita-mini-img">
                                        <img src="<?= getImg($n['gambar'], 'berita') ?>" alt="<?= e($n['judul']) ?>" loading="lazy">
                                    </div>
                                    <div>
                                        <div class="berita-mini-title">
                                            <a href="berita-detail.php?id=<?= $n['id'] ?>"><?= e($n['judul']) ?></a>
                                        </div>
                                        <div class="berita-mini-date"><i class="fas fa-calendar"></i> <?= formatTanggal($n['tgl_post']) ?></div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <div class="sidebar-widget-header">
                        <i class="fas fa-tag"></i> Kategori
                    </div>
                    <div class="sidebar-widget-body">
                        <?php
                        $kats = $conn->query("SELECT kategori, COUNT(*) as total FROM berita WHERE kategori != '' GROUP BY kategori ORDER BY total DESC");
                        while ($k = $kats->fetch_assoc()):
                        ?>
                            <a href="berita.php?kat=<?= urlencode($k['kategori']) ?>"
                               style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--border);font-size:0.875rem;font-weight:600;color:var(--text);">
                                <span><?= e($k['kategori']) ?></span>
                                <span class="badge badge-info"><?= $k['total'] ?></span>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </aside>
        </div>

        <!-- BERITA TERKAIT -->
        <?php if ($related->num_rows > 0): ?>
            <div style="margin-top:56px;">
                <h2 style="font-size:1.3rem;font-weight:800;color:var(--dark);margin-bottom:24px;padding-bottom:12px;border-bottom:2px solid var(--primary);display:inline-block;">
                    Berita Terkait
                </h2>
                <div class="grid-3">
                    <?php while ($r = $related->fetch_assoc()): ?>
                        <div class="card">
                            <div class="card-img">
                                <img src="<?= getImg($r['gambar'], 'berita') ?>" alt="<?= e($r['judul']) ?>" loading="lazy">
                            </div>
                            <div class="card-body">
                                <div class="card-meta">
                                    <span><i class="fas fa-calendar"></i> <?= formatTanggal($r['tgl_post']) ?></span>
                                </div>
                                <h3 class="card-title"><a href="berita-detail.php?id=<?= $r['id'] ?>"><?= e($r['judul']) ?></a></h3>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'include/footer.php'; ?>
