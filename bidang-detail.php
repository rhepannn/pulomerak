<?php
require_once 'include/config.php';
require_once 'include/functions.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) redirect(SITE_URL . '/index.php'); // Redirect ke beranda jika tidak ada slug

$stmt = $conn->prepare("SELECT * FROM bidang WHERE slug = ?");
$stmt->bind_param('s', $slug);
$stmt->execute();
$bidang = $stmt->get_result()->fetch_assoc();

if (!$bidang) redirect(SITE_URL . '/index.php');

$pageTitle = $bidang['nama'];

// Ambil pengurus/anggota
$stmt = $conn->prepare("SELECT * FROM anggota_bidang WHERE bidang_id = ? ORDER BY urutan ASC");
$stmt->bind_param('i', $bidang['id']);
$stmt->execute();
$anggota = $stmt->get_result();

$currentPage = 'bidang-detail';
include 'include/header.php';
?>

<div class="page-hero" style="padding: 60px 0; min-height: auto;">
    <div class="container">
        <div class="breadcrumb" style="margin-bottom: 15px; opacity: 0.8; font-size: 0.85rem;">
            <a href="<?= SITE_URL ?>/">Beranda</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin: 0 5px;"></i> 
            <span><?= e($bidang['nama']) ?></span>
        </div>
        <h1 style="font-size: 2.2rem; margin-bottom: 10px;"><?= e($bidang['nama']) ?></h1>
        <p style="max-width: 700px; opacity: 0.85; font-size: 1rem;">Mengenal pengurus dan program kerja unggulan bidang kami.</p>
    </div>
</div>

<section class="section" style="padding: 60px 0;">
    <div class="container">
        <div style="display: none;">
            <!-- HEADER BIDANG REMOVED FOR SIMPLICITY -->
        </div>

        <!-- STRUKTUR PENGURUS (HUMAN-CENTRIC DIRECTORY) -->
        <div style="margin-top: 20px;">
            <div class="section-header left" style="margin-bottom: 40px;">
                <div class="section-label" style="background: var(--primary-glow); color: var(--primary);"><i class="fas fa-users"></i> Mari Mengenal Kami</div>
                <h2 style="font-size: 1.8rem; color: var(--primary); margin-top: 10px;">Struktur <span>Kepengurusan</span></h2>
            </div>

            <?php if ($anggota->num_rows === 0): ?>
                <div style="padding: 40px; text-align: center; color: var(--text-muted); background: var(--gray-50); border-radius: 12px; border: 1px dashed var(--border);">
                    <p>Data struktur pengurus belum tersedia.</p>
                </div>
            <?php else: ?>
                <div class="grid-2" style="gap: 20px;">
                    <?php while ($a = $anggota->fetch_assoc()): ?>
                    <div class="person-card reveal" style="background: var(--white); border-radius: 16px; border: 1px solid var(--border); padding: 15px; display: flex; align-items: center; gap: 20px; transition: var(--transition);">
                        <div style="width: 65px; height: 65px; border-radius: 50%; overflow: hidden; background: var(--gray-100); flex-shrink: 0; border: 2px solid var(--white); box-shadow: var(--shadow-sm);">
                            <?php if ($a['foto']): ?>
                                <img src="<?= getImg($a['foto'], 'bidang') ?>" alt="<?= e($a['nama']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--gray-400); font-size: 1.2rem;"><i class="fas fa-user"></i></div>
                            <?php endif; ?>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 2px; font-weight: 700;"><?= e($a['nama']) ?></h4>
                            <div style="color: var(--accent); font-weight: 600; font-size: 0.75rem; text-transform: uppercase;">
                                <?= e($a['jabatan']) ?>
                            </div>
                        </div>
                        <?php if ($a['no_hp']): ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $a['no_hp']) ?>" target="_blank" style="width: 35px; height: 35px; border-radius: 50%; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;" title="Hubungi">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- PROGRAM UNGGULAN (CLEANER CARDS) -->
        <?php if ($bidang['program_unggulan']): ?>
        <div style="margin-top: 80px; padding-top: 60px; border-top: 1px solid var(--border);">
            <div class="section-header left" style="margin-bottom: 30px;">
                <div class="section-label" style="background: var(--gold-soft); color: var(--accent);"><i class="fas fa-star"></i> Pengabdian Kami</div>
                <h2 style="font-size: 1.6rem; color: var(--primary); margin-top: 10px;">Program <span>Unggulan</span></h2>
            </div>

            <div class="grid-3" style="gap: 15px;">
                <?php 
                $programs = explode("\n", str_replace("\r", "", $bidang['program_unggulan']));
                foreach ($programs as $index => $prog): 
                    if (empty(trim($prog))) continue;
                    $cleanProg = preg_replace('/^\d+[\.\)]\s+/', '', trim($prog));
                ?>
                <div class="program-card-human reveal" style="background: var(--gray-50); padding: 25px; border-radius: 12px; border: 1px solid var(--border); transition: var(--transition);">
                    <div style="display: flex; gap: 15px; align-items: flex-start;">
                        <span style="font-weight: 800; color: var(--primary); opacity: 0.2; font-size: 1.2rem;"><?= sprintf('%02d', $index + 1) ?></span>
                        <p style="font-size: 0.95rem; color: var(--gray-800); font-weight: 500; line-height: 1.5; margin: 0;"><?= e($cleanProg) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.person-card:hover { border-color: var(--primary); transform: translateY(-3px); box-shadow: var(--shadow); }
.program-card-human:hover { background: var(--white); border-color: var(--accent); transform: translateY(-2px); }

@media (max-width: 992px) {
    .grid-2 { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .grid-3 { grid-template-columns: 1fr; }
    .page-hero { text-align: center; }
}
</style>

<?php include 'include/footer.php'; ?>
