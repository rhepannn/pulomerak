<?php
// ============================================================
// KONFIGURASI - Portal Informasi Kecamatan Pulomerak
// ============================================================

// ── DATABASE ─────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pulomerak');

// ── SITE URL ─────────────────────────────────────────────────
$__p = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$__h = $_SERVER['HTTP_HOST'] ?? 'localhost';
$__r = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', str_replace('\\', '/', __DIR__));
$__r = rtrim(dirname($__r), '/');
define('SITE_URL', $__p . '://' . $__h . ($__r ? $__r : ''));
define('SITE_NAME', 'Portal Informasi Kecamatan Pulomerak');
define('SITE_DESC', 'Pusat Informasi Masyarakat Kecamatan Pulomerak, Kota Cilegon');
unset($__p);


// ── KONEKSI DATABASE ─────────────────────────────────────────
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;padding:40px;text-align:center;">
        <h2 style="color:#c0392b;">Koneksi Database Gagal</h2>
        <p>' . htmlspecialchars($conn->connect_error) . '</p>
    </div>');
}
$conn->set_charset('utf8mb4');
date_default_timezone_set('Asia/Jakarta');

// ── SESSION ──────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// AUTO-SETUP: Pastikan tabel & kolom selalu ada (silent)
// Tidak perlu jalankan script setup terpisah.
// ============================================================
_ensureSchema($conn);

function _ensureSchema($conn) {
    // 1. Buat tabel site_settings jika belum ada
    $conn->query("
        CREATE TABLE IF NOT EXISTS `site_settings` (
            `setting_key`   VARCHAR(100) NOT NULL,
            `setting_value` TEXT DEFAULT NULL,
            `setting_group` VARCHAR(50)  DEFAULT 'general',
            `label`         VARCHAR(200) DEFAULT NULL,
            `field_type`    VARCHAR(20)  DEFAULT 'text',
            `updated_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`setting_key`),
            KEY `idx_group` (`setting_group`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // 2. Migrasi kolom jika tabel sudah ada versi lama
    $res  = $conn->query("SHOW COLUMNS FROM `site_settings`");
    $cols = [];
    while ($r = $res->fetch_assoc()) $cols[] = $r['Field'];

    if (!in_array('label',      $cols)) $conn->query("ALTER TABLE `site_settings` ADD `label`      VARCHAR(200) DEFAULT NULL  AFTER `setting_group`");
    if (!in_array('field_type', $cols)) $conn->query("ALTER TABLE `site_settings` ADD `field_type` VARCHAR(20)  DEFAULT 'text' AFTER `label`");
    if (!in_array('updated_at', $cols)) $conn->query("ALTER TABLE `site_settings` ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `field_type`");

    // 3. Kolom tambahan di tabel kelurahan
    $tables = $conn->query("SHOW TABLES LIKE 'kelurahan'");
    if ($tables && $tables->num_rows > 0) {
        $res = $conn->query("SHOW COLUMNS FROM `kelurahan` ");
        $cols = [];
        while ($r = $res->fetch_assoc()) $cols[] = $r['Field'];

        if (!in_array('ketua_pkk',      $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `ketua_pkk`      VARCHAR(255) DEFAULT NULL AFTER `nama` ");
        if (!in_array('penduduk_l',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `penduduk_l`     INT(11)      DEFAULT 0    AFTER `penduduk` ");
        if (!in_array('penduduk_p',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `penduduk_p`     INT(11)      DEFAULT 0    AFTER `penduduk_l` ");
        if (!in_array('jumlah_link',    $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `jumlah_link`    INT(11)      DEFAULT 0    AFTER `jumlah_rt` ");
        if (!in_array('dasa_wisma',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `dasa_wisma`     INT(11)      DEFAULT 0    AFTER `jumlah_link` ");
        if (!in_array('ibu_hamil',      $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `ibu_hamil`      INT(11)      DEFAULT 0    AFTER `dasa_wisma` ");
        if (!in_array('ibu_melahirkan', $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `ibu_melahirkan` INT(11)      DEFAULT 0    AFTER `ibu_hamil` ");
        if (!in_array('ibu_nifas',      $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `ibu_nifas`      INT(11)      DEFAULT 0    AFTER `ibu_melahirkan` ");
        if (!in_array('ibu_meninggal',  $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `ibu_meninggal`  INT(11)      DEFAULT 0    AFTER `ibu_nifas` ");
        if (!in_array('bayi_lahir_l',   $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `bayi_lahir_l`   INT(11)      DEFAULT 0    AFTER `ibu_meninggal` ");
        if (!in_array('bayi_lahir_p',   $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `bayi_lahir_p`   INT(11)      DEFAULT 0    AFTER `bayi_lahir_l` ");
        if (!in_array('akte_ada',       $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `akte_ada`       INT(11)      DEFAULT 0    AFTER `bayi_lahir_p` ");
        if (!in_array('akte_tidak',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `akte_tidak`     INT(11)      DEFAULT 0    AFTER `akte_ada` ");
        if (!in_array('bayi_meninggal_l',   $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `bayi_meninggal_l`   INT(11)      DEFAULT 0    AFTER `akte_tidak` ");
        if (!in_array('bayi_meninggal_p',   $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `bayi_meninggal_p`   INT(11)      DEFAULT 0    AFTER `bayi_meninggal_l` ");
        if (!in_array('balita_meninggal_l', $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `balita_meninggal_l` INT(11)      DEFAULT 0    AFTER `bayi_meninggal_p` ");
        if (!in_array('balita_meninggal_p', $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `balita_meninggal_p` INT(11)      DEFAULT 0    AFTER `balita_meninggal_l` ");
        
        if (!in_array('sekretaris_pkk', $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `sekretaris_pkk` VARCHAR(255) DEFAULT NULL AFTER `ketua_pkk` ");
        if (!in_array('bendahara_pkk',  $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `bendahara_pkk`  VARCHAR(255) DEFAULT NULL AFTER `sekretaris_pkk` ");
        if (!in_array('pokja1_pkk',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `pokja1_pkk`     VARCHAR(255) DEFAULT NULL AFTER `bendahara_pkk` ");
        if (!in_array('pokja2_pkk',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `pokja2_pkk`     VARCHAR(255) DEFAULT NULL AFTER `pokja1_pkk` ");
        if (!in_array('pokja3_pkk',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `pokja3_pkk`     VARCHAR(255) DEFAULT NULL AFTER `pokja2_pkk` ");
        if (!in_array('pokja4_pkk',     $cols)) $conn->query("ALTER TABLE `kelurahan` ADD `pokja4_pkk`     VARCHAR(255) DEFAULT NULL AFTER `pokja3_pkk` ");
    }

    // 5. Update tabel users untuk kelurahan_id
    $res  = $conn->query("SHOW COLUMNS FROM `users` ");
    $cols = [];
    while ($r = $res->fetch_assoc()) $cols[] = $r['Field'];
    if (!in_array('kelurahan_id', $cols)) $conn->query("ALTER TABLE `users` ADD `kelurahan_id` INT(11) DEFAULT NULL AFTER `role` ");

    // 6. Update tabel berita & kegiatan untuk kelurahan_id
    $tables_check = ['berita', 'kegiatan'];
    foreach($tables_check as $tbl) {
        $res = $conn->query("SHOW COLUMNS FROM `$tbl` ");
        $cols = [];
        while ($r = $res->fetch_assoc()) $cols[] = $r['Field'];
        if (!in_array('kelurahan_id', $cols)) {
            $conn->query("ALTER TABLE `$tbl` ADD `kelurahan_id` INT(11) DEFAULT 0 AFTER `id` ");
            $conn->query("ALTER TABLE `$tbl` ADD INDEX (`kelurahan_id`) ");
        }
    }

    // 4. Seed default settings (INSERT IGNORE = skip jika sudah ada)
    _seedSettings($conn);
}

function _seed($conn, $key, $value, $group, $label, $type = 'text') {
    $stmt = $conn->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_group, label, field_type) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) return;
    $stmt->bind_param("sssss", $key, $value, $group, $label, $type);
    $stmt->execute();
    $stmt->close();
}

function _seedSettings($conn) {
    // Beranda — Hero
    _seed($conn, 'beranda_hero_image',    '',   'beranda_hero', 'Gambar Background Hero', 'image');
    _seed($conn, 'beranda_hero_title',    'Bersama Membangun Keluarga<br><span>Sejahtera &amp; Mandiri</span>', 'beranda_hero', 'Judul Hero', 'textarea');
    _seed($conn, 'beranda_hero_subtitle', 'Pusat informasi kegiatan, program inovasi, dan dinamika masyarakat yang mendukung pemberdayaan dan kesejahteraan keluarga di Kecamatan Pulomerak, Kota Cilegon.', 'beranda_hero', 'Deskripsi Hero', 'textarea');

    // Beranda — Statistik
    _seed($conn, 'stat_penduduk', '12450', 'beranda_stats', 'Jumlah Penduduk', 'number');
    _seed($conn, 'stat_rw',       '8',     'beranda_stats', 'Jumlah RW',       'number');
    _seed($conn, 'stat_rt',       '32',    'beranda_stats', 'Jumlah RT',       'number');
    _seed($conn, 'stat_inovasi',  '5',     'beranda_stats', 'Program Inovasi', 'number');

    // Beranda — PKK
    _seed($conn, 'pkk_pengertian',      'Gerakan Nasional dalam pembangunan masyarakat yang tumbuh dari bawah, yang pengelolaannya dari, oleh, dan untuk masyarakat menuju terwujudnya keluarga yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia dan berbudi luhur, sehat sejahtera, maju dan mandiri, kesetaraan dan keadilan gender serta kesadaran hukum dan lingkungan.',   'beranda_pkk', 'Pengertian Gerakan PKK',  'textarea');
    _seed($conn, 'pkk_tujuan',          'Memberdayakan keluarga untuk meningkatkan kesejahteraannya menuju terwujudnya keluarga yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia dan berbudi luhur, sehat sejahtera, maju dan mandiri, kesetaraan dan keadilan gender serta kesadaran hukum dan lingkungan.', 'beranda_pkk', 'Tujuan Gerakan PKK',       'textarea');
    _seed($conn, 'pkk_sasaran',         'Keluarga di pedesaan maupun perkotaan yang perlu ditingkatkan dan dikembangkan kemampuan serta kepribadiannya dalam bidang:',                                                                                                                                                              'beranda_pkk', 'Sasaran PKK',              'textarea');
    _seed($conn, 'pkk_sasaran_mental',  'Sikap dan perilaku sebagai insan hamba Tuhan dan warga negara yang dinamis.',                                                                                                                                                                                                           'beranda_pkk', 'Sasaran: Mental Spiritual', 'textarea');
    _seed($conn, 'pkk_sasaran_fisik',   'Pangan, sandang, papan, kesehatan, dan lingkungan hidup yang sehat.',                                                                                                                                                                                                                   'beranda_pkk', 'Sasaran: Fisik Material',  'textarea');
    _seed($conn, 'pkk_tugas',           'Tanggung jawab utama meliputi koordinasi TP PKK Desa/Kelurahan, penyuluhan kepada keluarga, pembinaan program kerja, serta pelaporan hasil kegiatan secara berkala kepada tingkat Kota.',                                                                                               'beranda_pkk', 'Tugas TP PKK Kecamatan',   'textarea');

    // Beranda — Counter
    _seed($conn, 'counter_kk',         '3200', 'beranda_counter', 'Kepala Keluarga',       'number');
    _seed($conn, 'counter_sekolah',    '4',    'beranda_counter', 'Sekolah Aktif',         'number');
    _seed($conn, 'counter_kesehatan',  '3',    'beranda_counter', 'Fasilitas Kesehatan',   'number');
    _seed($conn, 'counter_ibadah',     '12',   'beranda_counter', 'Tempat Ibadah',         'number');

    // Beranda — Layanan
    _seed($conn, 'layanan_1_title', 'Administrasi Kependudukan',    'beranda_layanan', 'Layanan 1: Judul',    'text');
    _seed($conn, 'layanan_1_desc',  'Pelayanan surat keterangan domisili, pengantar KTP, KK, dan dokumen kependudukan lainnya secara cepat dan tertib.', 'beranda_layanan', 'Layanan 1: Deskripsi', 'textarea');
    _seed($conn, 'layanan_1_icon',  'fas fa-file-alt',              'beranda_layanan', 'Layanan 1: Icon',     'text');
    _seed($conn, 'layanan_1_image', '',                             'beranda_layanan', 'Layanan 1: Gambar',   'image');
    _seed($conn, 'layanan_2_title', 'Program Inovasi',              'beranda_layanan', 'Layanan 2: Judul',    'text');
    _seed($conn, 'layanan_2_desc',  'Berbagai program inovasi untuk meningkatkan kualitas hidup dan pemberdayaan masyarakat Pulomerak yang berkelanjutan.', 'beranda_layanan', 'Layanan 2: Deskripsi', 'textarea');
    _seed($conn, 'layanan_2_icon',  'fas fa-lightbulb',             'beranda_layanan', 'Layanan 2: Icon',     'text');
    _seed($conn, 'layanan_2_image', '',                             'beranda_layanan', 'Layanan 2: Gambar',   'image');
    _seed($conn, 'layanan_3_title', 'Perpustakaan Digital',         'beranda_layanan', 'Layanan 3: Judul',    'text');
    _seed($conn, 'layanan_3_desc',  'Akses dokumen, arsip, dan referensi digital yang dapat diunduh oleh masyarakat secara gratis dan mudah.', 'beranda_layanan', 'Layanan 3: Deskripsi', 'textarea');
    _seed($conn, 'layanan_3_icon',  'fas fa-book-open',             'beranda_layanan', 'Layanan 3: Icon',     'text');
    _seed($conn, 'layanan_3_image', '',                             'beranda_layanan', 'Layanan 3: Gambar',   'image');

    // Profil — Hero
    _seed($conn, 'profil_hero_image',    '', 'profil_hero', 'Gambar Background Hero Profil', 'image');
    _seed($conn, 'profil_hero_title',    'Mewujudkan Masyarakat<br><span>Maju &amp; Sejahtera</span>', 'profil_hero', 'Judul Hero Profil',   'textarea');
    _seed($conn, 'profil_hero_subtitle', 'Mengenal lebih dekat visi, misi, dan struktur organisasi Pemerintah Kecamatan Pulomerak dalam melayani masyarakat Kota Cilegon.', 'profil_hero', 'Deskripsi Hero Profil', 'textarea');

    // Profil — Tentang
    _seed($conn, 'profil_tentang_image', '',                         'profil_tentang', 'Foto Kecamatan',       'image');
    _seed($conn, 'profil_tentang_1',     'Kecamatan Pulomerak adalah salah satu kecamatan yang berada di wilayah Kota Cilegon, Provinsi Banten. Terletak di ujung barat Pulau Jawa, Pulomerak dikenal sebagai gerbang penyeberangan utama Jawa–Sumatera melalui Pelabuhan Merak.', 'profil_tentang', 'Tentang: Paragraf 1', 'textarea');
    _seed($conn, 'profil_tentang_2',     'Dengan luas wilayah yang strategis, kecamatan ini dihuni oleh ribuan jiwa yang terbagi dalam berbagai kelurahan dan lingkungan. Masyarakatnya yang heterogen menjadikan Pulomerak sebagai wilayah yang dinamis dan kaya akan keberagaman budaya.', 'profil_tentang', 'Tentang: Paragraf 2', 'textarea');
    _seed($conn, 'profil_tentang_3',     'Pemerintah Kecamatan Pulomerak berkomitmen untuk memberikan pelayanan terbaik kepada masyarakat melalui program-program inovatif dan transparansi informasi publik.', 'profil_tentang', 'Tentang: Paragraf 3', 'textarea');
    _seed($conn, 'profil_lokasi',        'Kec. Pulomerak, Kota Cilegon', 'profil_tentang', 'Lokasi',         'text');
    _seed($conn, 'profil_luas',          '±3,2 km²',                     'profil_tentang', 'Luas Wilayah',   'text');
    _seed($conn, 'profil_penduduk_info', '±12.450 Jiwa',                 'profil_tentang', 'Jumlah Penduduk','text');

    // Profil — Visi Misi
    _seed($conn, 'profil_visi', '"Terwujudnya Kecamatan Pulomerak yang Maju, Bersih, dan Sejahtera Melalui Pelayanan Prima Berbasis Teknologi dan Partisipasi Masyarakat."', 'profil_visimisi', 'Visi', 'textarea');
    _seed($conn, 'profil_misi', "Meningkatkan kualitas pelayanan administrasi yang cepat, tepat, dan transparan.\nMendorong partisipasi aktif masyarakat dalam pembangunan kecamatan.\nMengembangkan potensi ekonomi lokal dan UMKM masyarakat Pulomerak.\nMenjaga ketertiban, keamanan, dan kerukunan antar warga.\nMeningkatkan kualitas lingkungan hidup yang bersih, sehat, dan nyaman.\nMemanfaatkan teknologi informasi untuk transparansi pemerintahan.", 'profil_visimisi', 'Misi (satu per baris)', 'textarea');

    // Profil — Struktur Organisasi
    _seed($conn, 'org_camat_foto',     '', 'profil_struktur', 'Foto Camat',            'image');
    _seed($conn, 'org_camat_nama',     'H. Ahmad Fauzi, S.IP', 'profil_struktur', 'Nama Camat',    'text');
    _seed($conn, 'org_camat_jabatan',  'Camat Pulomerak',      'profil_struktur', 'Jabatan Camat', 'text');
    _seed($conn, 'org_sekcam_foto',    '', 'profil_struktur', 'Foto Sekretaris Camat',       'image');
    _seed($conn, 'org_sekcam_nama',    'Drs. Suharno',        'profil_struktur', 'Nama Sekcam',    'text');
    _seed($conn, 'org_sekcam_jabatan', 'Sekretaris Camat',    'profil_struktur', 'Jabatan Sekcam', 'text');
    for ($i = 1; $i <= 4; $i++) {
        $defaults = [
            1 => ['Siti Rahayu, S.E',  'Kasi Pemerintahan'],
            2 => ['Budi Santoso, S.H', 'Kasi Pemberdayaan'],
            3 => ['Rini Kusuma, A.Md', 'Kasi Kesejahteraan'],
            4 => ['Eko Prasetyo',      'Kasi Ketentraman'],
        ];
        _seed($conn, "org_kasi_{$i}_foto",    '',                    'profil_struktur', "Kasi {$i}: Foto",    'image');
        _seed($conn, "org_kasi_{$i}_nama",    $defaults[$i][0],      'profil_struktur', "Kasi {$i}: Nama",    'text');
        _seed($conn, "org_kasi_{$i}_jabatan", $defaults[$i][1],      'profil_struktur', "Kasi {$i}: Jabatan", 'text');
    }

    // Profil — Batas Wilayah
    _seed($conn, 'batas_utara',   'Selat Sunda',   'profil_batas', 'Batas Utara',   'text');
    _seed($conn, 'batas_selatan', 'Kel. Suralaya', 'profil_batas', 'Batas Selatan', 'text');
    _seed($conn, 'batas_barat',   'Selat Sunda',   'profil_batas', 'Batas Barat',   'text');
    _seed($conn, 'batas_timur',   'Kel. Lebak Gede','profil_batas','Batas Timur',   'text');

    // Footer
    _seed($conn, 'footer_alamat',     'Jl. Raya Merak, Kecamatan Pulomerak, Kota Cilegon, Banten 42438', 'footer', 'Alamat Footer',    'text');
    _seed($conn, 'footer_telepon',    '(0254) 571234',                'footer', 'Telepon Footer',   'text');
    _seed($conn, 'footer_email',      'kec.pulomerak@cilegon.go.id',  'footer', 'Email Footer',     'text');
    _seed($conn, 'footer_deskripsi',  'Portal resmi Kecamatan Pulomerak sebagai pusat informasi masyarakat, transparansi pemerintahan, dan pelayanan publik berbasis digital.', 'footer', 'Deskripsi Footer', 'textarea');
    _seed($conn, 'site_logo',         '', 'footer', 'Logo Website', 'image');
}
