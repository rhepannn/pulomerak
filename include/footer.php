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
                        <img src="<?= SITE_URL ?>/assets/img/logo.png" alt="Logo" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <h3>Kelurahan Pulomerak</h3>
                        <p class="footer-tagline">Kota Cilegon · Banten</p>
                    </div>
                </div>
                <p class="footer-desc">
                    Portal resmi Kelurahan Pulomerak sebagai pusat informasi masyarakat,
                    transparansi pemerintahan, dan pelayanan publik berbasis digital.
                </p>
                <div class="footer-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="Twitter/X"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- Kolom 2: Menu Cepat -->
            <div class="footer-col">
                <h4 class="footer-heading">Menu Cepat</h4>
                <ul class="footer-links">
                    <li><a href="<?= SITE_URL ?>/"><i class="fas fa-angle-right"></i> Beranda</a></li>
                    <li><a href="<?= SITE_URL ?>/profil.php"><i class="fas fa-angle-right"></i> Profil Kelurahan</a></li>
                    <li><a href="<?= SITE_URL ?>/berita.php"><i class="fas fa-angle-right"></i> Berita Terbaru</a></li>
                    <li><a href="<?= SITE_URL ?>/kegiatan.php"><i class="fas fa-angle-right"></i> Kegiatan</a></li>
                    <li><a href="<?= SITE_URL ?>/laporan.php"><i class="fas fa-angle-right"></i> Laporan</a></li>
                    <li><a href="<?= SITE_URL ?>/dinamika.php"><i class="fas fa-angle-right"></i> Dinamika Masyarakat</a></li>
                    <li><a href="<?= SITE_URL ?>/perpustakaan.php"><i class="fas fa-angle-right"></i> Perpustakaan Digital</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Layanan -->
            <div class="footer-col">
                <h4 class="footer-heading">Layanan Masyarakat</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-angle-right"></i> Pelayanan Administrasi</a></li>
                    <li><a href="#"><i class="fas fa-angle-right"></i> Surat Keterangan Domisili</a></li>
                    <li><a href="#"><i class="fas fa-angle-right"></i> Surat Pengantar RT/RW</a></li>
                    <li><a href="#"><i class="fas fa-angle-right"></i> Pengajuan Bantuan</a></li>
                    <li><a href="<?= SITE_URL ?>/perpustakaan.php"><i class="fas fa-angle-right"></i> Dokumen & Arsip</a></li>
                    <li><a href="<?= SITE_URL ?>/admin/login.php"><i class="fas fa-lock"></i> Admin Panel</a></li>
                </ul>
            </div>

            <!-- Kolom 4: Kontak -->
            <div class="footer-col">
                <h4 class="footer-heading">Kontak Kami</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Jl. Raya Merak, Kelurahan Pulomerak, Kec. Pulomerak, Kota Cilegon, Banten 42438</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>(0254) 571234</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>kel.pulomerak@cilegon.go.id</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Senin–Jumat: 08.00–16.00 WIB</span>
                    </li>
                </ul>
                <div class="footer-badge">
                    <i class="fas fa-shield-halved"></i>
                    <span>Website Resmi Pemerintah</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?= date('Y') ?> <strong>Kelurahan Pulomerak</strong> — Kota Cilegon, Banten. Hak Cipta Dilindungi.</p>
            <p>Dikembangkan oleh Tim IT Kelurahan Pulomerak</p>
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
