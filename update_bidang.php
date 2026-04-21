<?php
require_once 'include/config.php';

$sql = "
DROP TABLE IF EXISTS `anggota_bidang`;
DROP TABLE IF EXISTS `bidang`;

CREATE TABLE `bidang` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(50)  NOT NULL UNIQUE,
  `deskripsi`  TEXT         DEFAULT NULL,
  `prestasi`   TEXT         DEFAULT NULL,
  `program_unggulan` TEXT      DEFAULT NULL,
  `gambar`     VARCHAR(255) DEFAULT NULL,
  `urutan`     INT(11)      DEFAULT 0,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bidang` (`nama`, `slug`, `deskripsi`, `prestasi`, `program_unggulan`, `urutan`) VALUES
('Sekretariat', 'sekretariat', 'Sekretariat bertugas mengoordinasikan seluruh administrasi, surat-menyurat, pendataan, dan pengelolaan informasi TP PKK Kecamatan Pulomerak. Sekretariat juga bertanggung jawab atas dokumentasi kegiatan dan penyusunan laporan berkala.', 'Pengelolaan administrasi terbaik tingkat Kecamatan Pulomerak tahun 2024.', '1. Penyusunan regulasi (Juknis/Panduan Kelembagaan PKK)\\n2. Sinergitas Sistem Informasi Gerakan PKK melalui E-PKK\\n3. Peningkatan kapasitas Kader PKK\\n4. Pemberian bantuan kepada masyarakat\\n5. Monitoring dan evaluasi', 1),
('POKJA I - Pembinaan Karakter Keluarga', 'pokja-1', 'POKJA I bertanggung jawab atas program Penghayatan dan Pengamalan Pancasila serta Gotong Royong. Fokus pada pembinaan karakter keluarga, kegiatan keagamaan, dan penguatan nilai-nilai moral masyarakat.', 'Juara 2 Lomba Posyandu Tingkat Kota Cilegon 2024.', '1. Program PAREDI (Pola Asuh Anak dan Remaja di Era Digital)\\n2. Pengamalan Butir-Butir Pancasila\\n3. Pembinaan Gotong Royong Masyarakat', 2),
('POKJA II - Pendidikan & Ekonomi Keluarga', 'pokja-2', 'POKJA II mengelola program Pendidikan dan Keterampilan serta Pengembangan Kehidupan Berkoperasi. Bertanggung jawab atas pelatihan keterampilan, pembinaan UMKM, dan pengembangan ekonomi kreatif keluarga.', 'Program UP2K berhasil membina 35 kelompok UMKM aktif.', '1. Pemberdayaan Ekonomi Keluarga (UP2K)\\n2. Pembentukan Toko PKK / Koperasi\\n3. Peningkatan Minat Baca melalui Taman Bacaan', 3),
('POKJA III - Ketahanan Keluarga', 'pokja-3', 'POKJA III menangani program Pangan, Sandang, Perumahan, dan Tata Laksana Rumah Tangga. Fokus pada ketahanan pangan keluarga, pengelolaan pekarangan, dan peningkatan gizi masyarakat.', 'Pemanfaatan pekarangan terbaik se-Kecamatan Pulomerak 2024.', '1. Gerakan HATINYA PKK (Halaman Asri Teratur Indah dan Nyaman)\\n2. Sosialisasi Pangan Beragam Bergizi Seimbang dan Aman (B2SA)\\n3. Pembinaan Pengelolaan Rumah Tangga Layak Huni', 4),
('POKJA IV - Kesehatan & Lingkungan', 'pokja-4', 'POKJA IV bertanggung jawab atas program Kesehatan, Kelestarian Lingkungan Hidup, dan Perencanaan SEHAT (Sinergi, Edukasi, Harmoni, Amanat, Terukur). Mengelola kegiatan posyandu, kesehatan ibu dan anak, serta program lingkungan bersih.', 'Posyandu Melati mendapat penghargaan Posyandu Aktif dari Kemenkes RI.', '1. Penurunan angka Stunting di wilayah Kelurahan\\n2. Optimalisasi Posyandu Terintegrasi\\n3. Pelestarian Lingkungan Hidup dan PHBS', 5);

CREATE TABLE `anggota_bidang` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `bidang_id`  INT(11)      NOT NULL,
  `nama`       VARCHAR(150) NOT NULL,
  `jabatan`    VARCHAR(100) DEFAULT NULL,
  `foto`       VARCHAR(255) DEFAULT NULL,
  `no_hp`      VARCHAR(20)  DEFAULT NULL,
  `urutan`     INT(11)      DEFAULT 0,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bidang` (`bidang_id`),
  CONSTRAINT `fk_anggota_bidang` FOREIGN KEY (`bidang_id`) REFERENCES `bidang`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `anggota_bidang` (`bidang_id`, `nama`, `jabatan`, `urutan`) VALUES
(1, 'Yane Ardian, SE., M.Si', 'Staf Ahli Bidang Penguatan Ketahanan Pangan Keluarga', 1),
(1, 'Irjen Pol (Purn) Dra. Sri Handayani, M.H', 'Staf Ahli Bidang Pendidikan Karakter', 2),
(1, 'Dr. Yulia Zubir, M.Epid', 'Staf Ahli Bidang Kesehatan', 3),
(1, 'Niken Tomsi, SH., MH', 'Staf Ahli Bidang Ketahanan Ekonomi Keluarga', 4),
(1, 'Dra. Lusje Anncke Tabalujuan, M.Pd', 'Sekretaris Umum TP PKK', 5),
(1, 'Rahmania Lufitasari, S.S, M.Si', 'Sekretaris I', 6),
(1, 'Meydy DS. Malonda, S.STP', 'Sekretaris II', 7),
(1, 'Mey Rany Wahida Utami, S.STP, M.M', 'Sekretaris III', 8),
(1, 'drg. Linda Sutarjo', 'Sekretaris IV', 9),
(1, 'Agnetha Rosari, S.Psi, M.Si', 'Bendahara I', 10),
(2, 'Hj. Ratna Dewi', 'Ketua POKJA I', 1),
(2, 'Sri Wahyuni', 'Wakil Ketua', 2),
(2, 'Aisyah Putri', 'Sekretaris', 3),
(2, 'Fatimah Zahra', 'Anggota', 4),
(2, 'Nurul Hidayah', 'Anggota', 5),
(3, 'Hj. Ening Suhaeni', 'Ketua POKJA II', 1),
(3, 'Tuti Alawiyah', 'Wakil Ketua', 2),
(3, 'Lilis Suryani', 'Sekretaris', 3),
(3, 'Mira Agustina', 'Anggota', 4),
(3, 'Sari Indah', 'Anggota', 5),
(4, 'Hj. Yayah Rokayah', 'Ketua POKJA III', 1),
(4, 'Wati Hernawati', 'Wakil Ketua', 2),
(4, 'Euis Komariah', 'Sekretaris', 3),
(4, 'Imas Masitoh', 'Anggota', 4),
(5, 'Hj. Nia Kurniasih', 'Ketua POKJA IV', 1),
(5, 'Dr. Santi Rahayu', 'Wakil Ketua', 2),
(5, 'Ida Farida', 'Sekretaris', 3),
(5, 'Ani Sumarni', 'Anggota', 4),
(5, 'Rini Handayani', 'Anggota', 5);
";

if ($conn->multi_query($sql)) {
    // Flush the multi_query to avoid errors with subsequent queries
    do {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
    echo "<div style='font-family:sans-serif; text-align:center; padding: 50px;'>";
    echo "<h2 style='color:green;'>✅ Update Database Berhasil!</h2>";
    echo "<p>Tabel <b>bidang</b> dan <b>anggota_bidang</b> telah berhasil ditambahkan.</p>";
    echo "<a href='index.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#1a4fa0; color:white; text-decoration:none; border-radius:5px;'>Kembali ke Beranda</a>";
    echo "</div>";
} else {
    echo "Error: " . $conn->error;
}
?>
