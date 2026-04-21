<?php
require_once 'include/config.php';
require_once 'include/functions.php';
$pageTitle = 'Profil Kecamatan';
include 'include/header.php';
?>

<!-- PAGE HERO -->
<style>
    .page-hero.with-bg::before, .page-hero.with-bg::after { display: none !important; }
</style>
<div class="page-hero with-bg" style="background-image: url('<?= SITE_URL ?>/assets/img/foto-profil.jpg'); background-size: cover; background-position: center; position: relative; min-height: 400px;">
</div>

<!-- TENTANG PULOMERAK -->
<section class="section">
    <div class="container">
        <div class="profil-intro reveal">
            <div class="profil-img">
                <img src="<?= SITE_URL ?>/assets/img/foto-profil.jpg"
                     alt="Kantor Kecamatan Pulomerak"
                     onerror="this.src='https://placehold.co/600x420/1a4fa0/ffffff?text=Kecamatan+Pulomerak'">
            </div>
            <div class="profil-content">
                <div class="section-label"><i class="fas fa-info-circle"></i> Tentang Kami</div>
                <h2>Kecamatan <span>Pulomerak</span></h2>
                <p>
                    Kecamatan Pulomerak adalah salah satu kecamatan yang berada di wilayah Kota Cilegon, Provinsi Banten. Terletak di ujung barat Pulau Jawa, Pulomerak dikenal sebagai gerbang penyeberangan utama Jawa–Sumatera melalui Pelabuhan Merak.
                </p>
                <p>
                    Dengan luas wilayah yang strategis, kecamatan ini dihuni oleh ribuan jiwa yang terbagi dalam berbagai kelurahan dan lingkungan. Masyarakatnya yang heterogen menjadikan Pulomerak sebagai wilayah yang dinamis dan kaya akan keberagaman budaya.
                </p>
                <p>
                    Pemerintah Kecamatan Pulomerak berkomitmen untuk memberikan pelayanan terbaik kepada masyarakat melalui program-program inovatif dan transparansi informasi publik.
                </p>
                <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:8px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:var(--gray);">
                        <i class="fas fa-map-marker-alt" style="color:var(--primary)"></i>
                        Kec. Pulomerak, Kota Cilegon
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:var(--gray);">
                        <i class="fas fa-expand-arrows-alt" style="color:var(--primary)"></i>
                        Luas ±3,2 km²
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:var(--gray);">
                        <i class="fas fa-users" style="color:var(--primary)"></i>
                        ±12.450 Jiwa
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VISI & MISI -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-star"></i> Arah & Tujuan</div>
            <h2 class="section-title">Visi & <span>Misi</span></h2>
            <p class="section-desc">Panduan dan komitmen Kecamatan Pulomerak dalam melayani masyarakat.</p>
        </div>
        <div class="visi-misi reveal">
            <div class="vm-card">
                <div class="vm-icon blue"><i class="fas fa-eye"></i></div>
                <h3>Visi</h3>
                <p>
                    <strong>"Terwujudnya Kecamatan Pulomerak yang Maju, Bersih, dan Sejahtera
                    Melalui Pelayanan Prima Berbasis Teknologi dan Partisipasi Masyarakat."</strong>
                </p>
            </div>
            <div class="vm-card">
                <div class="vm-icon green"><i class="fas fa-rocket"></i></div>
                <h3>Misi</h3>
                <ul>
                    <li>Meningkatkan kualitas pelayanan administrasi yang cepat, tepat, and transparan.</li>
                    <li>Mendorong partisipasi aktif masyarakat dalam pembangunan kecamatan.</li>
                    <li>Mengembangkan potensi ekonomi lokal dan UMKM masyarakat Pulomerak.</li>
                    <li>Menjaga ketertiban, keamanan, dan kerukunan antar warga.</li>
                    <li>Meningkatkan kualitas lingkungan hidup yang bersih, sehat, dan nyaman.</li>
                    <li>Memanfaatkan teknologi informasi untuk transparansi pemerintahan.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- STRUKTUR ORGANISASI -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-label"><i class="fas fa-sitemap"></i> Organisasi</div>
            <h2 class="section-title">Struktur <span>Organisasi</span></h2>
            <p class="section-desc">Susunan pejabat dan staf Kecamatan Pulomerak.</p>
        </div>

        <div class="org-chart reveal">
            <!-- Level 1: Lurah -->
            <div class="org-level">
                <div class="org-card chief">
                    <div class="org-avatar"><i class="fas fa-user-tie"></i></div>
                    <div class="org-name">H. Ahmad Fauzi, S.IP</div>
                    <div class="org-role">Camat Pulomerak</div>
                </div>
            </div>
            <div class="org-connector"></div>

            <!-- Level 2: Sekretaris Camat -->
            <div class="org-level">
                <div class="org-card" style="border-color:var(--secondary);">
                    <div class="org-avatar"><i class="fas fa-user"></i></div>
                    <div class="org-name">Drs. Suharno</div>
                    <div class="org-role">Sekretaris Camat</div>
                </div>
            </div>
            <div class="org-connector"></div>

            <!-- Level 3: Kasi-kasi -->
            <div class="org-level">
                <div class="org-card">
                    <div class="org-avatar"><i class="fas fa-user"></i></div>
                    <div class="org-name">Siti Rahayu, S.E</div>
                    <div class="org-role">Kasi Pemerintahan</div>
                </div>
                <div class="org-card">
                    <div class="org-avatar"><i class="fas fa-user"></i></div>
                    <div class="org-name">Budi Santoso, S.H</div>
                    <div class="org-role">Kasi Pemberdayaan</div>
                </div>
                <div class="org-card">
                    <div class="org-avatar"><i class="fas fa-user"></i></div>
                    <div class="org-name">Rini Kusuma, A.Md</div>
                    <div class="org-role">Kasi Kesejahteraan</div>
                </div>
                <div class="org-card">
                    <div class="org-avatar"><i class="fas fa-user"></i></div>
                    <div class="org-name">Eko Prasetyo</div>
                    <div class="org-role">Kasi Ketentraman</div>
                </div>
            </div>
        </div>

        <!-- INFO WILAYAH -->
        <div class="grid-4" style="margin-top:52px;">
            <div class="card reveal">
                <div class="card-body" style="text-align:center;">
                    <div class="shortcut-icon blue" style="margin:0 auto 14px;width:52px;height:52px;font-size:1.3rem">
                        <i class="fas fa-map"></i>
                    </div>
                    <h3 style="font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:6px">Batas Utara</h3>
                    <p style="font-size:0.875rem;color:var(--gray)">Selat Sunda</p>
                </div>
            </div>
            <div class="card reveal animate-delay-1">
                <div class="card-body" style="text-align:center;">
                    <div class="shortcut-icon green" style="margin:0 auto 14px;width:52px;height:52px;font-size:1.3rem">
                        <i class="fas fa-map"></i>
                    </div>
                    <h3 style="font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:6px">Batas Selatan</h3>
                    <p style="font-size:0.875rem;color:var(--gray)">Kel. Suralaya</p>
                </div>
            </div>
            <div class="card reveal animate-delay-2">
                <div class="card-body" style="text-align:center;">
                    <div class="shortcut-icon orange" style="margin:0 auto 14px;width:52px;height:52px;font-size:1.3rem">
                        <i class="fas fa-map"></i>
                    </div>
                    <h3 style="font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:6px">Batas Barat</h3>
                    <p style="font-size:0.875rem;color:var(--gray)">Selat Sunda</p>
                </div>
            </div>
            <div class="card reveal animate-delay-3">
                <div class="card-body" style="text-align:center;">
                    <div class="shortcut-icon purple" style="margin:0 auto 14px;width:52px;height:52px;font-size:1.3rem">
                        <i class="fas fa-map"></i>
                    </div>
                    <h3 style="font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:6px">Batas Timur</h3>
                    <p style="font-size:0.875rem;color:var(--gray)">Kel. Lebak Gede</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'include/footer.php'; ?>
