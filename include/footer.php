<?php
// Ambil settings footer
$footerS = getAllSettings($conn, 'footer');

// Resolve logo
$footerLogo = SITE_URL . '/assets/img/logo.png';
if (!empty($footerS['site_logo'])) {
    $lp = __DIR__ . '/../uploads/settings/' . $footerS['site_logo'];
    if (file_exists($lp)) $footerLogo = SITE_URL . '/uploads/settings/' . $footerS['site_logo'];
}
?>
<!-- FOOTER -->
<footer class="footer">
    <div class="footer-wave">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none"><path d="M0,40 C300,80 900,0 1200,40 L1200,80 L0,80 Z" fill="var(--gray-900)"/></svg>
    </div>
    <div class="footer-main">
        <div class="container footer-grid">
            <!-- Kolom 1: Tentang -->
            <div class="footer-col">
                <div class="footer-brand">
                    <div class="footer-logo-wrap">
                        <img src="<?= $footerLogo ?>" alt="Logo" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <h3>Kecamatan Pulomerak</h3>
                        <p class="footer-tagline">Kota Cilegon · Banten</p>
                    </div>
                </div>
                <p class="footer-desc">
                    <?= e($footerS['footer_deskripsi'] ?? 'Portal resmi Kecamatan Pulomerak sebagai pusat informasi masyarakat, transparansi pemerintahan, dan pelayanan publik berbasis digital.') ?>
                </p>
                <div class="footer-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="Twitter/X"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- Kolom 2: Kontak -->

            <div class="footer-col">

                <h4 class="footer-heading">Kontak Kami</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= e($footerS['footer_alamat'] ?? 'Jl. Raya Merak, Kecamatan Pulomerak, Kota Cilegon, Banten 42438') ?></span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span><?= e($footerS['footer_telepon'] ?? '(0254) 571234') ?></span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span><?= e($footerS['footer_email'] ?? 'kec.pulomerak@cilegon.go.id') ?></span>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?= date('Y') ?> <strong>Kecamatan Pulomerak</strong> — Kota Cilegon, Banten. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" title="Kembali ke Atas">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Main JS -->
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
