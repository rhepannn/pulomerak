<?php
require 'include/config.php';

$sql = "
CREATE TABLE IF NOT EXISTS pengaturan (
  kunci VARCHAR(100) NOT NULL PRIMARY KEY,
  nilai TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
$conn->query($sql);

$defaults = [
    'site_name' => 'Portal Informasi Kecamatan Pulomerak',
    'site_desc' => 'Pusat Informasi Masyarakat Kecamatan Pulomerak, Kota Cilegon',
    'hero_badge' => 'Portal Resmi Tim Penggerak PKK Kecamatan Pulomerak',
    'hero_title' => 'Bersama Membangun Keluarga<br><span>Sejahtera & Mandiri</span>',
    'hero_desc' => 'Pusat informasi kegiatan, program inovasi, dan dinamika masyarakat yang mendukung pemberdayaan dan kesejahteraan keluarga di Kecamatan Pulomerak, Kota Cilegon.',
    'stat1_num' => '12450', 'stat1_label' => 'Jiwa Penduduk',
    'stat2_num' => '8', 'stat2_label' => 'Rukun Warga (RW)',
    'stat3_num' => '32', 'stat3_label' => 'Rukun Tetangga (RT)',
    'stat4_num' => '5', 'stat4_label' => 'Program Inovasi',
    'count1_num' => '3200', 'count1_label' => 'Kepala Keluarga',
    'count2_num' => '4', 'count2_label' => 'Sekolah Aktif',
    'count3_num' => '3', 'count3_label' => 'Fasilitas Kesehatan',
    'count4_num' => '12', 'count4_label' => 'Tempat Ibadah',
    'layanan1_title' => 'Administrasi Kependudukan', 'layanan1_desc' => 'Pelayanan surat keterangan domisili, pengantar KTP, KK, dan dokumen kependudukan lainnya secara cepat dan tertib.',
    'layanan2_title' => 'Program Inovasi Kelurahan', 'layanan2_desc' => 'Berbagai program inovasi untuk meningkatkan kualitas hidup dan pemberdayaan masyarakat Pulomerak yang berkelanjutan.',
    'layanan3_title' => 'Perpustakaan Digital', 'layanan3_desc' => 'Akses dokumen, arsip, dan referensi digital yang dapat diunduh oleh masyarakat secara gratis dan mudah.'
];

foreach ($defaults as $k => $v) {
    if ($stmt = $conn->prepare("INSERT IGNORE INTO pengaturan (kunci, nilai) VALUES (?, ?)")) {
        $stmt->bind_param("ss", $k, $v);
        $stmt->execute();
    }
}
echo "Tabel pengaturan berhasil dibuat dan diisi default.";
