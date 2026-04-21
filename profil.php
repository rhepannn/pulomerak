<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Profil Kecamatan';

// Ambil semua settings
$S = getAllSettings($conn);

// Resolve uploaded images
function settingImg($S, $key, $default = '') {
    $f = $S[$key] ?? '';
    if ($f) {
        $p = __DIR__ . '/uploads/settings/' . $f;
        if (file_exists($p)) return SITE_URL . '/uploads/settings/' . $f;
    }
    return $default;
}

$profilHeroBg  = settingImg($S, 'profil_hero_image',    SITE_URL.'/assets/img/foto-profil.jpg');
$profilFoto    = settingImg($S, 'profil_tentang_image', SITE_URL.'/assets/img/foto-profil.jpg');

// Org photos (returns '' if no upload, template shows FA icon as fallback)
for ($i=1; $i<=4; $i++) $orgKasiFoto[$i] = settingImg($S, 'org_kasi_'.$i.'_foto', '');
$orgCamatFoto  = settingImg($S, 'org_camat_foto',  '');
$orgSekcamFoto = settingImg($S, 'org_sekcam_foto', '');

include 'include/header.php';
?>

<!-- ═══════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════ -->
<section class="hero" style="background-image: url('<?= $profilHeroBg ?>');">
    <div class="hero-overlay"></div>
    <div class="container hero-content animate-fade-up">
        <h1><?= $S['profil_hero_title'] ?? 'Mewujudkan Masyarakat<br><span>Maju & Sejahtera</span>' ?></h1>
        <p>
            <?= e($S['profil_hero_subtitle'] ?? '') ?>
        </p>
        <div class="hero-actions">
            <a href="#visi-misi" class="btn btn-primary">
                <i class="fas fa-bullseye"></i> Visi & Misi
            </a>
            <a href="#struktur" class="btn btn-outline">
                <i class="fas fa-sitemap"></i> Struktur Organisasi
            </a>
        </div>
    </div>
</section>

<!-- TENTANG PULOMERAK -->
<section class="section">
    <div class="container">
        <div class="profil-intro reveal">
            <div class="profil-img">
                <img src="<?= $profilFoto ?>"
                     alt="Kantor Kecamatan Pulomerak"
                     onerror="this.src='https://placehold.co/600x420/1a4fa0/ffffff?text=Kecamatan+Pulomerak'">
            </div>
            <div class="profil-content">
                <div class="section-label"><i class="fas fa-info-circle"></i> Tentang Kami</div>
                <h2>Kecamatan <span>Pulomerak</span></h2>
                <p>
                    <?= e($S['profil_tentang_1'] ?? '') ?>
                </p>
                <p>
                    <?= e($S['profil_tentang_2'] ?? '') ?>
                </p>
                <p>
                    <?= e($S['profil_tentang_3'] ?? '') ?>
                </p>
                <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:8px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:var(--gray);">
                        <i class="fas fa-map-marker-alt" style="color:var(--primary, #0054A6)"></i>
                        <?= e($S['profil_lokasi'] ?? 'Kec. Pulomerak, Kota Cilegon') ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:var(--gray);">
                        <i class="fas fa-expand-arrows-alt" style="color:var(--primary, #0054A6)"></i>
                        Luas <?= e($S['profil_luas'] ?? '±3,2 km²') ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:var(--gray);">
                        <i class="fas fa-users" style="color:var(--primary, #0054A6)"></i>
                        <?= e($S['profil_penduduk_info'] ?? '±12.450 Jiwa') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VISI & MISI -->
<section class="section section-alt" id="visi-misi">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Arah & Tujuan</div>
            <h2 class="section-title">Visi & <span>Misi</span></h2>
            <p class="section-desc">Panduan dan komitmen Kecamatan Pulomerak dalam melayani masyarakat.</p>
        </div>
        <div class="visi-misi reveal">
                <div class="vm-card">
                    <div class="vm-icon"><i class="fas fa-eye"></i></div>
                <h3>Visi</h3>
                <p>
                    <strong><?= e($S['profil_visi'] ?? '') ?></strong>
                </p>
            </div>
                <div class="vm-card">
                    <div class="vm-icon"><i class="fas fa-rocket"></i></div>
                <h3>Misi</h3>
                <ul>
                    <?php
                    $misiItems = array_filter(explode("\n", $S['profil_misi'] ?? ''));
                    foreach ($misiItems as $misi):
                        $misi = trim($misi);
                        if ($misi):
                    ?>
                        <li><?= e($misi) ?></li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- STRUKTUR ORGANISASI -->
<section class="section" id="struktur">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-sitemap"></i> Organisasi</div>
            <h2 class="section-title">Struktur <span>Organisasi</span></h2>
            <p class="section-desc">Susunan pejabat dan staf Kecamatan Pulomerak.</p>
        </div>

        <div class="org-chart reveal">
            <!-- Level 1: Camat -->
            <div class="org-level">
                <div class="org-card chief">
                    <div class="org-avatar">
                        <?php if ($orgCamatFoto): ?>
                            <img src="<?= $orgCamatFoto ?>" alt="<?= e($S['org_camat_nama'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        <?php else: ?>
                            <i class="fas fa-user-tie"></i>
                        <?php endif; ?>
                    </div>
                    <div class="org-name"><?= e($S['org_camat_nama'] ?? 'H. Ahmad Fauzi, S.IP') ?></div>
                    <div class="org-role"><?= e($S['org_camat_jabatan'] ?? 'Camat Pulomerak') ?></div>
                </div>
            </div>
            <div class="org-connector"></div>

            <!-- Level 2: Sekretaris Camat -->
            <div class="org-level">
                <div class="org-card" style="border-color:var(--primary-light, #2563EB);">
                    <div class="org-avatar">
                        <?php if ($orgSekcamFoto): ?>
                            <img src="<?= $orgSekcamFoto ?>" alt="<?= e($S['org_sekcam_nama'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="org-name"><?= e($S['org_sekcam_nama'] ?? 'Drs. Suharno') ?></div>
                    <div class="org-role"><?= e($S['org_sekcam_jabatan'] ?? 'Sekretaris Camat') ?></div>
                </div>
            </div>
            <div class="org-connector"></div>

            <!-- Level 3: Kasi-kasi -->
            <div class="org-level">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="org-card">
                    <div class="org-avatar">
                        <?php if (!empty($orgKasiFoto[$i])): ?>
                            <img src="<?= $orgKasiFoto[$i] ?>" alt="<?= e($S['org_kasi_'.$i.'_nama'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="org-name"><?= e($S["org_kasi_{$i}_nama"] ?? '') ?></div>
                    <div class="org-role"><?= e($S["org_kasi_{$i}_jabatan"] ?? '') ?></div>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- INFO WILAYAH -->
        <div class="grid-4" style="margin-top:52px;">
            <?php
            $batasArr = [
                ['label' => 'Batas Utara',   'key' => 'batas_utara'],
                ['label' => 'Batas Selatan',  'key' => 'batas_selatan'],
                ['label' => 'Batas Barat',   'key' => 'batas_barat'],
                ['label' => 'Batas Timur',   'key' => 'batas_timur'],
            ];
            $delays = ['', 'animate-delay-1', 'animate-delay-2', 'animate-delay-3'];
            foreach ($batasArr as $idx => $batas):
            ?>
            <div class="card reveal <?= $delays[$idx] ?>">
                <div class="card-body" style="text-align:center;">
                    <div class="shortcut-icon" style="margin:0 auto 14px;width:52px;height:52px;font-size:1.3rem">
                        <i class="fas fa-map"></i>
                    </div>
                    <h3 style="font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:6px"><?= $batas['label'] ?></h3>
                    <p style="font-size:0.875rem;color:var(--gray)"><?= e($S[$batas['key']] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'include/footer.php'; ?>
