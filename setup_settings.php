<?php
/**
 * Setup Script: Membuat tabel site_settings dan mengisi data default
 * Jalankan sekali via browser: http://localhost/web/tppkkkecamatanpulaumerak/setup_settings.php
 */
require_once 'include/config.php';

// Buat tabel site_settings
$conn->query("
    CREATE TABLE IF NOT EXISTS `site_settings` (
        `setting_key`   VARCHAR(100) NOT NULL,
        `setting_value` TEXT DEFAULT NULL,
        `setting_group` VARCHAR(50) DEFAULT 'general',
        `label`         VARCHAR(200) DEFAULT NULL,
        `field_type`    VARCHAR(20) DEFAULT 'text',
        `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`setting_key`),
        KEY `idx_group` (`setting_group`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

// Helper: insert jika belum ada
function seedSetting($conn, $key, $value, $group, $label, $type = 'text') {
    $stmt = $conn->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_group, label, field_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $key, $value, $group, $label, $type);
    $stmt->execute();
    $stmt->close();
}

// ══════════════════════════════════════════════
// BERANDA - Hero Section
// ══════════════════════════════════════════════
seedSetting($conn, 'beranda_hero_image', '', 'beranda_hero', 'Gambar Background Hero', 'image');
seedSetting($conn, 'beranda_hero_title', 'Bersama Membangun Keluarga<br><span>Sejahtera & Mandiri</span>', 'beranda_hero', 'Judul Hero', 'textarea');
seedSetting($conn, 'beranda_hero_subtitle', 'Pusat informasi kegiatan, program inovasi, dan dinamika masyarakat yang mendukung pemberdayaan dan kesejahteraan keluarga di Kecamatan Pulomerak, Kota Cilegon.', 'beranda_hero', 'Deskripsi Hero', 'textarea');

// BERANDA - Statistik Strip
seedSetting($conn, 'stat_penduduk', '12450', 'beranda_stats', 'Jumlah Penduduk', 'number');
seedSetting($conn, 'stat_rw', '8', 'beranda_stats', 'Jumlah RW', 'number');
seedSetting($conn, 'stat_rt', '32', 'beranda_stats', 'Jumlah RT', 'number');
seedSetting($conn, 'stat_inovasi', '5', 'beranda_stats', 'Program Inovasi', 'number');

// BERANDA - Pengertian & Tujuan PKK
seedSetting($conn, 'pkk_pengertian', 'Gerakan Nasional dalam pembangunan masyarakat yang tumbuh dari bawah, yang pengelolaannya dari, oleh, dan untuk masyarakat menuju terwujudnya keluarga yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia dan berbudi luhur, sehat sejahtera, maju dan mandiri, kesetaraan dan keadilan gender serta kesadaran hukum dan lingkungan.', 'beranda_pkk', 'Pengertian Gerakan PKK', 'textarea');
seedSetting($conn, 'pkk_tujuan', 'Memberdayakan keluarga untuk meningkatkan kesejahteraannya menuju terwujudnya keluarga yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia dan berbudi luhur, sehat sejahtera, maju dan mandiri, kesetaraan dan keadilan gender serta kesadaran hukum dan lingkungan.', 'beranda_pkk', 'Tujuan Gerakan PKK', 'textarea');
seedSetting($conn, 'pkk_sasaran', 'Keluarga di pedesaan maupun perkotaan yang perlu ditingkatkan dan dikembangkan kemampuan serta kepribadiannya dalam bidang:', 'beranda_pkk', 'Sasaran PKK', 'textarea');
seedSetting($conn, 'pkk_sasaran_mental', 'Sikap dan perilaku sebagai insan hamba Tuhan dan warga negara yang dinamis.', 'beranda_pkk', 'Sasaran: Mental Spiritual', 'textarea');
seedSetting($conn, 'pkk_sasaran_fisik', 'Pangan, sandang, papan, kesehatan, dan lingkungan hidup yang sehat.', 'beranda_pkk', 'Sasaran: Fisik Material', 'textarea');
seedSetting($conn, 'pkk_tugas', 'Tanggung jawab utama meliputi koordinasi TP PKK Desa/Kelurahan, penyuluhan kepada keluarga, pembinaan program kerja, serta pelaporan hasil kegiatan secara berkala kepada tingkat Kota.', 'beranda_pkk', 'Tugas TP PKK Kecamatan', 'textarea');

// BERANDA - Counter Section
seedSetting($conn, 'counter_kk', '3200', 'beranda_counter', 'Kepala Keluarga', 'number');
seedSetting($conn, 'counter_sekolah', '4', 'beranda_counter', 'Sekolah Aktif', 'number');
seedSetting($conn, 'counter_kesehatan', '3', 'beranda_counter', 'Fasilitas Kesehatan', 'number');
seedSetting($conn, 'counter_ibadah', '12', 'beranda_counter', 'Tempat Ibadah', 'number');

// BERANDA - Layanan Unggulan
seedSetting($conn, 'layanan_1_title', 'Administrasi Kependudukan', 'beranda_layanan', 'Layanan 1: Judul', 'text');
seedSetting($conn, 'layanan_1_desc', 'Pelayanan surat keterangan domisili, pengantar KTP, KK, dan dokumen kependudukan lainnya secara cepat dan tertib.', 'beranda_layanan', 'Layanan 1: Deskripsi', 'textarea');
seedSetting($conn, 'layanan_1_icon', 'fas fa-file-alt', 'beranda_layanan', 'Layanan 1: Icon (class FA)', 'text');
seedSetting($conn, 'layanan_1_image', '', 'beranda_layanan', 'Layanan 1: Gambar Icon (opsional)', 'image');
seedSetting($conn, 'layanan_2_title', 'Program Inovasi', 'beranda_layanan', 'Layanan 2: Judul', 'text');
seedSetting($conn, 'layanan_2_desc', 'Berbagai program inovasi untuk meningkatkan kualitas hidup dan pemberdayaan masyarakat Pulomerak yang berkelanjutan.', 'beranda_layanan', 'Layanan 2: Deskripsi', 'textarea');
seedSetting($conn, 'layanan_2_icon', 'fas fa-lightbulb', 'beranda_layanan', 'Layanan 2: Icon (class FA)', 'text');
seedSetting($conn, 'layanan_2_image', '', 'beranda_layanan', 'Layanan 2: Gambar Icon (opsional)', 'image');
seedSetting($conn, 'layanan_3_title', 'Perpustakaan Digital', 'beranda_layanan', 'Layanan 3: Judul', 'text');
seedSetting($conn, 'layanan_3_desc', 'Akses dokumen, arsip, dan referensi digital yang dapat diunduh oleh masyarakat secara gratis dan mudah.', 'beranda_layanan', 'Layanan 3: Deskripsi', 'textarea');
seedSetting($conn, 'layanan_3_icon', 'fas fa-book-open', 'beranda_layanan', 'Layanan 3: Icon (class FA)', 'text');
seedSetting($conn, 'layanan_3_image', '', 'beranda_layanan', 'Layanan 3: Gambar Icon (opsional)', 'image');

// ══════════════════════════════════════════════
// PROFIL
// ══════════════════════════════════════════════
seedSetting($conn, 'profil_hero_image', '', 'profil_hero', 'Gambar Background Hero Profil', 'image');
seedSetting($conn, 'profil_hero_title', 'Mewujudkan Masyarakat<br><span>Maju & Sejahtera</span>', 'profil_hero', 'Judul Hero Profil', 'textarea');
seedSetting($conn, 'profil_hero_subtitle', 'Mengenal lebih dekat visi, misi, dan struktur organisasi Pemerintah Kecamatan Pulomerak dalam melayani masyarakat Kota Cilegon.', 'profil_hero', 'Deskripsi Hero Profil', 'textarea');

seedSetting($conn, 'profil_tentang_image', '', 'profil_tentang', 'Foto Kecamatan (Tentang)', 'image');
seedSetting($conn, 'profil_tentang_1', 'Kecamatan Pulomerak adalah salah satu kecamatan yang berada di wilayah Kota Cilegon, Provinsi Banten. Terletak di ujung barat Pulau Jawa, Pulomerak dikenal sebagai gerbang penyeberangan utama Jawa–Sumatera melalui Pelabuhan Merak.', 'profil_tentang', 'Tentang: Paragraf 1', 'textarea');
seedSetting($conn, 'profil_tentang_2', 'Dengan luas wilayah yang strategis, kecamatan ini dihuni oleh ribuan jiwa yang terbagi dalam berbagai kelurahan dan lingkungan. Masyarakatnya yang heterogen menjadikan Pulomerak sebagai wilayah yang dinamis dan kaya akan keberagaman budaya.', 'profil_tentang', 'Tentang: Paragraf 2', 'textarea');
seedSetting($conn, 'profil_tentang_3', 'Pemerintah Kecamatan Pulomerak berkomitmen untuk memberikan pelayanan terbaik kepada masyarakat melalui program-program inovatif dan transparansi informasi publik.', 'profil_tentang', 'Tentang: Paragraf 3', 'textarea');
seedSetting($conn, 'profil_lokasi', 'Kec. Pulomerak, Kota Cilegon', 'profil_tentang', 'Lokasi', 'text');
seedSetting($conn, 'profil_luas', '±3,2 km²', 'profil_tentang', 'Luas Wilayah', 'text');
seedSetting($conn, 'profil_penduduk_info', '±12.450 Jiwa', 'profil_tentang', 'Jumlah Penduduk', 'text');

seedSetting($conn, 'profil_visi', '"Terwujudnya Kecamatan Pulomerak yang Maju, Bersih, dan Sejahtera Melalui Pelayanan Prima Berbasis Teknologi dan Partisipasi Masyarakat."', 'profil_visimisi', 'Visi', 'textarea');
seedSetting($conn, 'profil_misi', "Meningkatkan kualitas pelayanan administrasi yang cepat, tepat, and transparan.\nMendorong partisipasi aktif masyarakat dalam pembangunan kecamatan.\nMengembangkan potensi ekonomi lokal dan UMKM masyarakat Pulomerak.\nMenjaga ketertiban, keamanan, dan kerukunan antar warga.\nMeningkatkan kualitas lingkungan hidup yang bersih, sehat, dan nyaman.\nMemanfaatkan teknologi informasi untuk transparansi pemerintahan.", 'profil_visimisi', 'Misi (satu per baris)', 'textarea');

// Struktur Organisasi
seedSetting($conn, 'org_camat_foto', '', 'profil_struktur', 'Foto Camat', 'image');
seedSetting($conn, 'org_camat_nama', 'H. Ahmad Fauzi, S.IP', 'profil_struktur', 'Nama Camat', 'text');
seedSetting($conn, 'org_camat_jabatan', 'Camat Pulomerak', 'profil_struktur', 'Jabatan Camat', 'text');
seedSetting($conn, 'org_sekcam_foto', '', 'profil_struktur', 'Foto Sekretaris Camat', 'image');
seedSetting($conn, 'org_sekcam_nama', 'Drs. Suharno', 'profil_struktur', 'Nama Sekretaris Camat', 'text');
seedSetting($conn, 'org_sekcam_jabatan', 'Sekretaris Camat', 'profil_struktur', 'Jabatan Sekcam', 'text');
seedSetting($conn, 'org_kasi_1_foto', '', 'profil_struktur', 'Kasi 1: Foto', 'image');
seedSetting($conn, 'org_kasi_1_nama', 'Siti Rahayu, S.E', 'profil_struktur', 'Kasi 1: Nama', 'text');
seedSetting($conn, 'org_kasi_1_jabatan', 'Kasi Pemerintahan', 'profil_struktur', 'Kasi 1: Jabatan', 'text');
seedSetting($conn, 'org_kasi_2_foto', '', 'profil_struktur', 'Kasi 2: Foto', 'image');
seedSetting($conn, 'org_kasi_2_nama', 'Budi Santoso, S.H', 'profil_struktur', 'Kasi 2: Nama', 'text');
seedSetting($conn, 'org_kasi_2_jabatan', 'Kasi Pemberdayaan', 'profil_struktur', 'Kasi 2: Jabatan', 'text');
seedSetting($conn, 'org_kasi_3_foto', '', 'profil_struktur', 'Kasi 3: Foto', 'image');
seedSetting($conn, 'org_kasi_3_nama', 'Rini Kusuma, A.Md', 'profil_struktur', 'Kasi 3: Nama', 'text');
seedSetting($conn, 'org_kasi_3_jabatan', 'Kasi Kesejahteraan', 'profil_struktur', 'Kasi 3: Jabatan', 'text');
seedSetting($conn, 'org_kasi_4_foto', '', 'profil_struktur', 'Kasi 4: Foto', 'image');
seedSetting($conn, 'org_kasi_4_nama', 'Eko Prasetyo', 'profil_struktur', 'Kasi 4: Nama', 'text');
seedSetting($conn, 'org_kasi_4_jabatan', 'Kasi Ketentraman', 'profil_struktur', 'Kasi 4: Jabatan', 'text');

// Batas Wilayah
seedSetting($conn, 'batas_utara', 'Selat Sunda', 'profil_batas', 'Batas Utara', 'text');
seedSetting($conn, 'batas_selatan', 'Kel. Suralaya', 'profil_batas', 'Batas Selatan', 'text');
seedSetting($conn, 'batas_barat', 'Selat Sunda', 'profil_batas', 'Batas Barat', 'text');
seedSetting($conn, 'batas_timur', 'Kel. Lebak Gede', 'profil_batas', 'Batas Timur', 'text');

// Footer
seedSetting($conn, 'footer_alamat', 'Jl. Raya Merak, Kecamatan Pulomerak, Kota Cilegon, Banten 42438', 'footer', 'Alamat Footer', 'text');
seedSetting($conn, 'footer_telepon', '(0254) 571234', 'footer', 'Telepon Footer', 'text');
seedSetting($conn, 'footer_email', 'kec.pulomerak@cilegon.go.id', 'footer', 'Email Footer', 'text');
seedSetting($conn, 'footer_deskripsi', 'Portal resmi Kecamatan Pulomerak sebagai pusat informasi masyarakat, transparansi pemerintahan, dan pelayanan publik berbasis digital.', 'footer', 'Deskripsi Footer', 'textarea');
seedSetting($conn, 'site_logo', '', 'footer', 'Logo Website', 'image');

echo '<div style="font-family:sans-serif;padding:40px;text-align:center;">
    <h2 style="color:#059669;">✅ Setup Berhasil!</h2>
    <p>Tabel <code>site_settings</code> telah dibuat dan diisi dengan data default.</p>
    <p>Total setting: <strong>' . $conn->query("SELECT COUNT(*) FROM site_settings")->fetch_row()[0] . '</strong></p>
    <br>
    <a href="admin/konten.php" style="background:#0054A6;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:700;">
        Buka Halaman Editor Konten →
    </a>
</div>';
?>
