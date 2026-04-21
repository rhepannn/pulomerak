<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Beranda';

// Ambil berita terbaru
$stmtB = $conn->prepare("SELECT * FROM berita ORDER BY tgl_post DESC LIMIT 5");
$stmtB->execute();
$beritaList = $stmtB->get_result();

// Ambil kegiatan terbaru
$stmtK = $conn->prepare("SELECT * FROM kegiatan ORDER BY tgl_kegiatan DESC LIMIT 6");
$stmtK->execute();
$kegiatanList = $stmtK->get_result();

include 'include/header.php';
?>

<!-- ═══════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════ -->
<section class="hero" style="background-image: url('<?= SITE_URL ?>/assets/img/foto-beranda.jpg');">
    <div class="hero-overlay"></div>
    <div class="container hero-content animate-fade-up">

        <h1>Bersama Membangun Keluarga<br><span>Sejahtera & Mandiri</span></h1>
        <p>
            Pusat informasi kegiatan, program inovasi, dan dinamika masyarakat
            yang mendukung pemberdayaan dan kesejahteraan keluarga di Kecamatan Pulomerak, Kota Cilegon.
        </p>
        <div class="hero-actions">
            <a href="profil.php" class="btn btn-primary">
                <i class="fas fa-landmark"></i> Profil Organisasi
            </a>
            <a href="kegiatan.php" class="btn btn-outline">
                <i class="fas fa-camera"></i> Galeri Kegiatan
            </a>
        </div>
    </div>
</section>

<!-- STATISTIK STRIP (Moved under hero to be flat) -->
<div class="hero-stats-strip">
    <div class="container">
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="12450">0</span>
                <span class="hero-stat-label">Jiwa Penduduk</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="8">0</span>
                <span class="hero-stat-label">Rukun Warga (RW)</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="32">0</span>
                <span class="hero-stat-label">Rukun Tetangga (RT)</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-num" data-count="5">0</span>
                <span class="hero-stat-label">Program Inovasi</span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     SHORTCUT MENU
═══════════════════════════════════════════════════════ -->
<div class="shortcuts">
    <div class="container">
        <div class="shortcuts-grid">
            <a href="profil.php" class="shortcut-card">
                <div class="shortcut-icon blue"><i class="fas fa-landmark"></i></div>
                <span class="shortcut-label">Profil</span>
            </a>
            <a href="berita.php" class="shortcut-card">
                <div class="shortcut-icon green"><i class="fas fa-newspaper"></i></div>
                <span class="shortcut-label">Berita</span>
            </a>
            <a href="kegiatan.php" class="shortcut-card">
                <div class="shortcut-icon orange"><i class="fas fa-calendar-check"></i></div>
                <span class="shortcut-label">Kegiatan</span>
            </a>
            <a href="laporan.php" class="shortcut-card">
                <div class="shortcut-icon red"><i class="fas fa-file-alt"></i></div>
                <span class="shortcut-label">Laporan</span>
            </a>
            <a href="dinamika.php" class="shortcut-card">
                <div class="shortcut-icon purple"><i class="fas fa-users"></i></div>
                <span class="shortcut-label">Dinamika</span>
            </a>
            <a href="perpustakaan.php" class="shortcut-card">
                <div class="shortcut-icon teal"><i class="fas fa-book-open"></i></div>
                <span class="shortcut-label">Perpustakaan</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     TENTANG GERAKAN PKK
═══════════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-info-circle"></i> Tentang PKK</div>
            <h2 class="section-title">Informasi <span>Gerakan PKK</span></h2>
            <p class="section-desc">Mengenal lebih dekat tentang Gerakan Pemberdayaan dan Kesejahteraan Keluarga.</p>
        </div>
        
        <!-- ═══════════════════════════════════════════════════════
             PENGERTIAN & TUJUAN
        ═══════════════════════════════════════════════════════ -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-bottom: 5rem; align-items: start;">
            <div class="reveal">
                <h3 style="color:var(--primary); font-size:1.6rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
                    <i class="fas fa-info-circle"></i> Pengertian Gerakan PKK
                </h3>
                <p style="color:var(--text-secondary); line-height:1.9; font-size:1.1rem; text-align:justify;">
                    Gerakan Nasional dalam pembangunan masyarakat yang tumbuh dari bawah, yang pengelolaannya dari, oleh, dan untuk masyarakat menuju terwujudnya keluarga yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia dan berbudi luhur, sehat sejahtera, maju dan mandiri, kesetaraan dan keadilan gender serta kesadaran hukum dan lingkungan.
                </p>
            </div>
            <div class="reveal animate-delay-1">
                <h3 style="color:var(--primary); font-size:1.6rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
                    <i class="fas fa-bullseye"></i> Tujuan Gerakan PKK
                </h3>
                <p style="color:var(--text-secondary); line-height:1.9; font-size:1.1rem; text-align:justify;">
                    Memberdayakan keluarga untuk meningkatkan kesejahteraannya menuju terwujudnya keluarga yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia dan berbudi luhur, sehat sejahtera, maju dan mandiri, kesetaraan dan keadilan gender serta kesadaran hukum dan lingkungan.
                </p>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             10 PROGRAM POKOK PKK
        ═══════════════════════════════════════════════════════ -->
        <div class="section-header" style="margin-bottom: 40px;">
            <div class="section-label"><i class="fas fa-star"></i> Program Utama</div>
            <h2 class="section-title">10 Program <span>Pokok PKK</span></h2>
            <p class="section-desc">Pilar utama pemberdayaan keluarga yang diimplementasikan secara komprehensif.</p>
        </div>

        <div class="program-grid reveal">
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-heart"></i></div>
                <div class="program-title">Penghayatan dan Pengamalan Pancasila</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-hands-holding-circle"></i></div>
                <div class="program-title">Gotong Royong</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-utensils"></i></div>
                <div class="program-title">Pangan</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-shirt"></i></div>
                <div class="program-title">Sandang</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-house-chimney"></i></div>
                <div class="program-title">Perumahan dan Tata Laksana Rumah Tangga</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="program-title">Pendidikan dan Keterampilan</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-heart-pulse"></i></div>
                <div class="program-title">Kesehatan</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-shop"></i></div>
                <div class="program-title">Pengembangan Kehidupan Berkoperasi</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-leaf"></i></div>
                <div class="program-title">Kelestarian Lingkungan Hidup</div>
            </div>
            <div class="program-item">
                <div class="program-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="program-title">Perencanaan Sehat</div>
            </div>
        </div>

        <div style="margin-top: 5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem;" class="reveal">
            <!-- Sasaran -->
            <div style="background:var(--gray-50); padding:2rem; border-radius:16px; border-left:4px solid var(--primary);">
                <h3 style="color:var(--primary); font-size:1.3rem; margin-bottom:1rem;"><i class="fas fa-users-viewfinder"></i> Sasaran Gerakan PKK</h3>
                <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.7;">
                    Keluarga di pedesaan maupun perkotaan yang perlu ditingkatkan dan dikembangkan kemampuan serta kepribadiannya dalam bidang:
                </p>
                <ul style="margin-top:10px; font-size:0.9rem; color:var(--text-secondary); display:flex; flex-direction:column; gap:8px;">
                    <li><i class="fas fa-check-circle" style="color:var(--primary); font-size:0.8rem;"></i> <strong>Mental Spiritual:</strong> Sikap dan perilaku sebagai insan hamba Tuhan dan warga negara yang dinamis.</li>
                    <li><i class="fas fa-check-circle" style="color:var(--primary); font-size:0.8rem;"></i> <strong>Fisik Material:</strong> Pangan, sandang, papan, kesehatan, dan lingkungan hidup yang sehat.</li>
                </ul>
            </div>
            <!-- Tugas & Fungsi -->
            <div style="background:var(--gray-50); padding:2rem; border-radius:16px; border-left:4px solid var(--accent-light);">
                <h3 style="color:var(--primary); font-size:1.3rem; margin-bottom:1rem;"><i class="fas fa-list-check"></i> Tugas TP PKK Kecamatan</h3>
                <p style="font-size:0.95rem; color:var(--text-secondary); line-height:1.7;">
                    Tanggung jawab utama meliputi koordinasi TP PKK Desa/Kelurahan, penyuluhan kepada keluarga, pembinaan program kerja, serta pelaporan hasil kegiatan secara berkala kepada tingkat Kota.
                </p>
                <div style="margin-top:15px;">
                    <a href="profil.php" class="btn btn-outline btn-sm" style="background:#fff;">Lihat Detail Fungsi <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     BERITA TERBARU
═══════════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-newspaper"></i> Informasi Terkini</div>
            <h2 class="section-title">Berita <span>Terbaru</span></h2>
            <p class="section-desc">Informasi dan perkembangan terbaru seputar Kecamatan Pulomerak dan masyarakatnya.</p>
        </div>

        <?php
        $beritaArr = [];
        while ($b = $beritaList->fetch_assoc()) $beritaArr[] = $b;
        ?>

        <?php if (empty($beritaArr)): ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h3>Belum Ada Berita</h3>
                <p>Berita akan segera ditambahkan oleh admin.</p>
            </div>
        <?php else: ?>
            <div class="berita-featured reveal">
                <!-- Berita Utama -->
                <?php $bUtama = $beritaArr[0]; ?>
                <div class="berita-main-card">
                    <img src="<?= getImg($bUtama['gambar'], 'berita') ?>" alt="<?= e($bUtama['judul']) ?>">
                    <div class="berita-main-overlay">
                        <span class="berita-main-cat"><?= e($bUtama['kategori'] ?? 'Berita') ?></span>
                        <h2 class="berita-main-title">
                            <a href="berita-detail.php?id=<?= $bUtama['id'] ?>"><?= e($bUtama['judul']) ?></a>
                        </h2>
                        <div class="berita-main-meta">
                            <span><i class="fas fa-calendar"></i> <?= formatTanggal($bUtama['tgl_post']) ?></span>
                            <span><i class="fas fa-user"></i> Admin</span>
                        </div>
                    </div>
                </div>

                <!-- Berita Samping -->
                <div class="berita-side">
                    <?php for ($i = 1; $i <= min(4, count($beritaArr) - 1); $i++): ?>
                        <?php $b = $beritaArr[$i]; ?>
                        <div class="berita-side-item">
                            <div class="berita-side-img">
                                <img src="<?= getImg($b['gambar'], 'berita') ?>" alt="<?= e($b['judul']) ?>">
                            </div>
                            <div class="berita-side-body">
                                <div class="berita-side-cat"><?= e($b['kategori'] ?? 'Berita') ?></div>
                                <div class="berita-side-title">
                                    <a href="berita-detail.php?id=<?= $b['id'] ?>"><?= e($b['judul']) ?></a>
                                </div>
                                <div class="berita-side-date"><i class="fas fa-clock"></i> <?= formatTanggal($b['tgl_post']) ?></div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="section-footer">
                <a href="berita.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> Lihat Semua Berita
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     STATISTIK COUNTER
═══════════════════════════════════════════════════════ -->
<div class="counter-section">
    <div class="container">
        <div class="counter-grid">
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-home"></i></div>
                <div class="counter-num"><span data-count="3200">0</span><span class="suffix">+</span></div>
                <div class="counter-label">Kepala Keluarga</div>
            </div>
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="counter-num"><span data-count="4">0</span></div>
                <div class="counter-label">Sekolah Aktif</div>
            </div>
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-hospital"></i></div>
                <div class="counter-num"><span data-count="3">0</span></div>
                <div class="counter-label">Fasilitas Kesehatan</div>
            </div>
            <div class="counter-item">
                <div class="counter-icon"><i class="fas fa-mosque"></i></div>
                <div class="counter-num"><span data-count="12">0</span></div>
                <div class="counter-label">Tempat Ibadah</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     KEGIATAN TERBARU
═══════════════════════════════════════════════════════ -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-calendar-check"></i> Aktivitas</div>
            <h2 class="section-title">Kegiatan <span>Terbaru</span></h2>
            <p class="section-desc">Dokumentasi kegiatan dan program yang telah dilaksanakan oleh Kecamatan Pulomerak.</p>
        </div>

        <?php if ($kegiatanList->num_rows === 0): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Belum Ada Kegiatan</h3>
                <p>Data kegiatan akan segera ditambahkan.</p>
            </div>
        <?php else: ?>
            <div class="galeri-grid">
                <?php while ($k = $kegiatanList->fetch_assoc()): ?>
                    <div class="galeri-item reveal"
                         data-src="<?= getImg($k['gambar'], 'kegiatan') ?>"
                         data-caption="<?= e($k['judul']) ?>">
                        <img src="<?= getImg($k['gambar'], 'kegiatan') ?>" alt="<?= e($k['judul']) ?>" loading="lazy">
                        <div class="galeri-overlay">
                            <h4><?= e($k['judul']) ?></h4>
                            <p><i class="fas fa-calendar"></i> <?= formatTanggal($k['tgl_kegiatan']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="section-footer">
                <a href="kegiatan.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> Lihat Semua Kegiatan
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     LAYANAN UNGGULAN
═══════════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-cog"></i> Layanan</div>
            <h2 class="section-title">Layanan <span>Unggulan</span></h2>
            <p class="section-desc">Berbagai layanan dan program unggulan Kecamatan Pulomerak untuk masyarakat.</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem;" class="reveal">
            <!-- Layanan 1 -->
            <div style="background:#fff; padding:3rem 2rem; border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.04); border:1px solid var(--border); text-align:center; transition:var(--transition); position:relative; overflow:hidden;" class="service-card">
                <div style="width:60px; height:60px; background:var(--primary-glow); color:var(--primary); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 1.5rem;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 style="color:var(--text-dark); font-size:1.3rem; margin-bottom:1rem;">Administrasi Kependudukan</h3>
                <p style="color:var(--text-secondary); line-height:1.7; font-size:0.95rem; margin-bottom:2rem;">
                    Pelayanan surat keterangan domisili, pengantar KTP, KK, dan dokumen kependudukan lainnya secara cepat dan tertib.
                </p>
                <a href="laporan.php" class="btn btn-ghost btn-sm" style="width:100%; justify-content:center;">Selengkapnya <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- Layanan 2 -->
            <div style="background:#fff; padding:3rem 2rem; border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.04); border:1px solid var(--border); text-align:center; transition:var(--transition);" class="service-card">
                <div style="width:60px; height:60px; background:var(--primary-glow); color:var(--primary); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 1.5rem;">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3 style="color:var(--text-dark); font-size:1.3rem; margin-bottom:1rem;">Program Inovasi</h3>
                <p style="color:var(--text-secondary); line-height:1.7; font-size:0.95rem; margin-bottom:2rem;">
                    Berbagai program inovasi untuk meningkatkan kualitas hidup dan pemberdayaan masyarakat Pulomerak yang berkelanjutan.
                </p>
                <a href="kelurahan.php#inovasi" class="btn btn-ghost btn-sm" style="width:100%; justify-content:center;">Selengkapnya <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- Layanan 3 -->
            <div style="background:#fff; padding:3rem 2rem; border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.04); border:1px solid var(--border); text-align:center; transition:var(--transition);" class="service-card">
                <div style="width:60px; height:60px; background:var(--primary-glow); color:var(--primary); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 1.5rem;">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 style="color:var(--text-dark); font-size:1.3rem; margin-bottom:1rem;">Perpustakaan Digital</h3>
                <p style="color:var(--text-secondary); line-height:1.7; font-size:0.95rem; margin-bottom:2rem;">
                    Akses dokumen, arsip, dan referensi digital yang dapat diunduh oleh masyarakat secara gratis dan mudah.
                </p>
                <a href="perpustakaan.php" class="btn btn-ghost btn-sm" style="width:100%; justify-content:center;">Selengkapnya <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

    </div>
</section>

<?php include 'include/footer.php'; ?>
