<?php
require_once 'include/config.php';
require_once 'include/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(SITE_URL.'/dinamika.php');

$stmt = $conn->prepare("SELECT * FROM dinamika WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$d = $stmt->get_result()->fetch_assoc();
if (!$d) redirect(SITE_URL.'/dinamika.php');

$pageTitle = $d['judul'];
include 'include/header.php';
?>

<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right"></i>
            <a href="dinamika.php">Dinamika</a> <i class="fas fa-chevron-right"></i>
            <span><?= truncate($d['judul'], 40) ?></span>
        </div>
        <h1><i class="fas fa-users"></i> Dinamika Masyarakat</h1>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:800px">
        <div class="detail-main reveal">
            <?php if (!empty($d['gambar'])): ?>
                <img src="<?= getImg($d['gambar'], 'kegiatan') ?>" alt="<?= e($d['judul']) ?>" class="detail-img">
            <?php endif; ?>
            <?php if (!empty($d['kategori'])): ?>
                <span class="tag tag-green" style="display:inline-block;margin-bottom:14px"><?= e($d['kategori']) ?></span>
            <?php endif; ?>
            <h1 class="detail-title"><?= e($d['judul']) ?></h1>
            <div class="detail-meta">
                <span><i class="fas fa-calendar"></i> <?= formatTanggal($d['tgl_post']) ?></span>
                <?php if (!empty($d['penulis'])): ?>
                    <span><i class="fas fa-user"></i> <?= e($d['penulis']) ?></span>
                <?php endif; ?>
            </div>
            <div class="detail-body"><?= nl2br(e($d['isi'])) ?></div>
            <div style="margin-top:28px;">
                <a href="dinamika.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dinamika
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'include/footer.php'; ?>
