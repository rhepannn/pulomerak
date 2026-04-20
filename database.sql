-- ============================================================
-- DATABASE: Portal Informasi Kelurahan Pulomerak
-- Versi: 1.1 (Compatible MySQL 5.7+ / MariaDB 10+)
-- Charset: utf8mb4
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";
SET NAMES utf8mb4;

-- ============================================================
-- TABEL: users (Admin)
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100) NOT NULL,
  `username`   VARCHAR(60)  NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       VARCHAR(50)  NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Akun admin dibuat via setup_admin.php (username: masbro | password: 12)

-- ============================================================
-- TABEL: berita
-- ============================================================
DROP TABLE IF EXISTS `berita`;
CREATE TABLE `berita` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `judul`      VARCHAR(255) NOT NULL,
  `isi`        TEXT         NOT NULL,
  `kategori`   VARCHAR(100) DEFAULT NULL,
  `gambar`     VARCHAR(255) DEFAULT NULL,
  `url_sumber` VARCHAR(500) DEFAULT NULL,
  `tgl_post`   DATE         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tgl_post` (`tgl_post`),
  KEY `idx_kategori` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `berita` (`judul`, `isi`, `kategori`, `gambar`, `tgl_post`) VALUES
('Musyawarah Perencanaan Pembangunan Kelurahan Pulomerak 2025',
'Kelurahan Pulomerak menyelenggarakan Musrenbang (Musyawarah Perencanaan Pembangunan) tingkat kelurahan untuk tahun anggaran 2025. Kegiatan ini dihadiri oleh perwakilan RW, RT, tokoh masyarakat, dan perangkat kelurahan.\r\n\r\nDalam musyawarah tersebut, warga menyampaikan berbagai usulan pembangunan infrastruktur dan program sosial yang diharapkan dapat meningkatkan kualitas hidup masyarakat Pulomerak.\r\n\r\nBeberapa usulan prioritas yang disepakati antara lain: perbaikan jalan lingkungan, pembangunan taman bermain, dan peningkatan fasilitas posyandu.',
'Pemerintahan', NULL, '2025-03-15'),

('Pelatihan UMKM Digital untuk Warga Pulomerak',
'Dinas Koperasi dan UMKM Kota Cilegon bersama Kelurahan Pulomerak menggelar pelatihan digitalisasi UMKM bagi pelaku usaha kecil di wilayah Pulomerak. Pelatihan ini bertujuan membantu warga memasarkan produk mereka secara online.\r\n\r\nSebanyak 45 pelaku UMKM mengikuti pelatihan ini dan mendapatkan materi tentang pembuatan toko online, fotografi produk, dan strategi pemasaran digital.',
'Ekonomi', NULL, '2025-03-10'),

('Kampanye Kebersihan Lingkungan Pulomerak Bersih',
'Dalam rangka memperingati Hari Peduli Sampah Nasional, Kelurahan Pulomerak menggelar kampanye kebersihan lingkungan bertajuk "Pulomerak Bersih, Pulomerak Sehat". Kegiatan ini melibatkan seluruh warga dari 8 RW yang ada.\r\n\r\nRatusan warga bergotong royong membersihkan saluran air, jalan lingkungan, dan tempat-tempat umum. Program bank sampah juga diperkenalkan kepada warga.',
'Lingkungan Hidup', NULL, '2025-03-05'),

('Peluncuran Layanan Administrasi Online Kelurahan',
'Kelurahan Pulomerak resmi meluncurkan sistem layanan administrasi online yang memungkinkan warga mengajukan berbagai dokumen kependudukan secara digital. Inovasi ini merupakan bagian dari program Smart City Kota Cilegon.\r\n\r\nMelalui sistem ini, warga dapat mengajukan surat keterangan domisili, surat pengantar, dan berbagai layanan administratif lainnya tanpa harus datang langsung ke kantor kelurahan.',
'Pengumuman', NULL, '2025-02-28'),

('Posyandu Terintegrasi Digital Resmi Beroperasi',
'Program inovasi Posyandu Terintegrasi Digital di Kelurahan Pulomerak resmi beroperasi. Program ini menggunakan aplikasi khusus untuk memantau perkembangan kesehatan ibu dan anak secara real-time.\r\n\r\nKader posyandu dari 8 RW telah mendapatkan pelatihan penggunaan aplikasi sehingga dapat memberikan pelayanan yang lebih efektif dan terdata dengan baik.',
'Kesehatan', NULL, '2025-02-20');

-- ============================================================
-- TABEL: kegiatan
-- ============================================================
DROP TABLE IF EXISTS `kegiatan`;
CREATE TABLE `kegiatan` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `judul`        VARCHAR(255) NOT NULL,
  `deskripsi`    TEXT         DEFAULT NULL,
  `kategori`     VARCHAR(100) DEFAULT NULL,
  `gambar`       VARCHAR(255) DEFAULT NULL,
  `tgl_kegiatan` DATE         NOT NULL,
  `lokasi`       VARCHAR(255) DEFAULT NULL,
  `kelurahan_id` INT(11)      DEFAULT 0,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tgl` (`tgl_kegiatan`),
  KEY `idx_kel` (`kelurahan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kegiatan` (`judul`, `deskripsi`, `kategori`, `tgl_kegiatan`, `lokasi`) VALUES
('Gotong Royong Bersih Lingkungan', 'Kegiatan gotong royong rutin membersihkan lingkungan sekitar kantor kelurahan dan jalan utama.', 'Lingkungan Hidup', '2025-03-20', 'Jl. Raya Merak'),
('Posyandu Balita Maret 2025', 'Kegiatan posyandu rutin untuk pemantauan tumbuh kembang balita warga Pulomerak.', 'Kesehatan', '2025-03-18', 'Balai RW 03'),
('Musrenbang Kelurahan 2025', 'Musyawarah perencanaan pembangunan untuk menentukan prioritas program tahun 2025.', 'Pemerintahan', '2025-03-15', 'Aula Kelurahan'),
('Pelatihan UMKM Digital', 'Pelatihan digitalisasi usaha bagi pelaku UMKM Pulomerak bekerja sama dengan Dinas Koperasi.', 'Ekonomi', '2025-03-10', 'Balai Kelurahan'),
('Pengajian Rutin Warga', 'Pengajian bulanan yang dihadiri oleh warga dari berbagai RW sebagai kegiatan keagamaan.', 'Keagamaan', '2025-03-08', 'Masjid Al-Ikhlas'),
('Pertandingan Olahraga HUT RT', 'Turnamen olahraga dalam rangka HUT RT yang diikuti oleh warga berbagai kalangan.', 'Olahraga & Seni', '2025-03-01', 'Lapangan RW 05');

-- ============================================================
-- TABEL: laporan
-- ============================================================
DROP TABLE IF EXISTS `laporan`;
CREATE TABLE `laporan` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `judul`      VARCHAR(255) NOT NULL,
  `deskripsi`  TEXT         DEFAULT NULL,
  `file`       VARCHAR(255) DEFAULT NULL,
  `tgl_upload` DATE         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tgl` (`tgl_upload`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `laporan` (`judul`, `deskripsi`, `tgl_upload`) VALUES
('Laporan Bulanan Februari 2025', 'Laporan kegiatan dan keuangan kelurahan bulan Februari 2025.', '2025-03-01'),
('Laporan Realisasi APBKel 2024', 'Laporan realisasi anggaran pendapatan dan belanja kelurahan tahun 2024.', '2025-01-15'),
('Laporan Musrenbang 2024', 'Dokumentasi hasil musyawarah perencanaan pembangunan tahun 2024.', '2024-02-28');

-- ============================================================
-- TABEL: kelurahan (RW/lingkungan)
-- ============================================================
DROP TABLE IF EXISTS `kelurahan`;
CREATE TABLE `kelurahan` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(150) NOT NULL,
  `deskripsi`  TEXT         DEFAULT NULL,
  `inovasi`    TEXT         DEFAULT NULL,
  `gambar`     VARCHAR(255) DEFAULT NULL,
  `jumlah_rw`  INT(11)      DEFAULT 0,
  `jumlah_rt`  INT(11)      DEFAULT 0,
  `penduduk`   INT(11)      DEFAULT 0,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kelurahan` (`nama`, `deskripsi`, `jumlah_rw`, `jumlah_rt`, `penduduk`) VALUES
('RW 01 - Kampung Merak', 'Wilayah RW 01 yang terletak di area pesisir Selat Sunda, dikenal dengan komunitas nelayan yang aktif dan kegiatan sosial kemasyarakatannya yang erat.', 1, 4, 1580),
('RW 02 - Kampung Jawa', 'Wilayah RW 02 dengan komunitas yang beragam. Terdapat beberapa usaha kecil dan menengah yang berkembang pesat di sini.', 1, 4, 1420),
('RW 03 - Perumahan Merak Indah', 'Kawasan perumahan modern dengan fasilitas lengkap dan tingkat kepedulian sosial warga yang tinggi.', 1, 5, 1890),
('RW 04 - Kampung Bogel', 'Kawasan dengan sejarah panjang dan nilai budaya tinggi. Terkenal dengan kesenian tradisional dan adat istiadatnya.', 1, 4, 1230),
('RW 05 - Area Pelabuhan', 'Wilayah terdekat dengan Pelabuhan Merak dengan dinamika sosial yang tinggi akibat mobilitas masyarakat yang besar.', 1, 4, 1560),
('RW 06 - Kampung Warung', 'Wilayah perdagangan dengan pusat-pusat ekonomi rakyat dan pasar tradisional yang ramai setiap hari.', 1, 4, 1340),
('RW 07 - Perumahan Griya', 'Kawasan perumahan dengan lingkungan hijau dan program bank sampah yang aktif dan berhasil.', 1, 4, 1780),
('RW 08 - Kampung Nelayan', 'Wilayah pesisir dengan komunitas nelayan tradisional yang kuat dan program pemberdayaan hasil laut.', 1, 3, 1650);

-- ============================================================
-- TABEL: galeri
-- ============================================================
DROP TABLE IF EXISTS `galeri`;
CREATE TABLE `galeri` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `judul`      VARCHAR(255) DEFAULT NULL,
  `keterangan` TEXT         DEFAULT NULL,
  `gambar`     VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: dinamika
-- ============================================================
DROP TABLE IF EXISTS `dinamika`;
CREATE TABLE `dinamika` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `judul`      VARCHAR(255) NOT NULL,
  `isi`        TEXT         NOT NULL,
  `kategori`   VARCHAR(100) DEFAULT NULL,
  `penulis`    VARCHAR(100) DEFAULT 'Admin Kelurahan',
  `gambar`     VARCHAR(255) DEFAULT NULL,
  `tgl_post`   DATE         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tgl` (`tgl_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `dinamika` (`judul`, `isi`, `kategori`, `penulis`, `tgl_post`) VALUES
('Komunitas Nelayan Pulomerak Kembangkan Budidaya Kerang',
'Kelompok nelayan Pulomerak yang tergabung dalam Kelompok Usaha Bersama (KUB) Bahari Sejahtera mulai mengembangkan budidaya kerang hijau di perairan Selat Sunda. Program ini mendapat dukungan penuh dari Dinas Kelautan dan Perikanan Kota Cilegon.\r\n\r\nDengan modal awal yang bersumber dari Dana Desa dan bantuan pemerintah, para nelayan berhasil membangun 20 unit keramba terapung.',
'Ekonomi', 'Tim Redaksi', '2025-03-12'),

('Ibu-Ibu PKK Pulomerak Berinovasi dengan Olahan Sampah Plastik',
'Kelompok PKK Kelurahan Pulomerak berhasil mengembangkan usaha kreatif pengolahan sampah plastik menjadi berbagai produk bernilai ekonomis seperti tas, dompet, dan aksesori rumah tangga.\r\n\r\nInovasi ini tidak hanya membantu mengurangi volume sampah plastik di lingkungan, tetapi juga memberikan tambahan penghasilan bagi ibu rumah tangga.',
'Sosial', 'Tim Redaksi', '2025-03-05'),

('Pemuda Pulomerak Aktif dalam Program Literasi Digital',
'Karang Taruna Kelurahan Pulomerak menginisiasi program literasi digital bagi anak-anak dan remaja setempat. Program ini bertujuan untuk mempersiapkan generasi muda menghadapi tantangan era digital.\r\n\r\nMelalui kelas coding gratis dan pelatihan desain grafis, puluhan anak muda Pulomerak kini mulai terampil di bidang teknologi.',
'Pendidikan', 'Tim Redaksi', '2025-02-25');

-- ============================================================
-- TABEL: perpustakaan
-- ============================================================
DROP TABLE IF EXISTS `perpustakaan`;
CREATE TABLE `perpustakaan` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `judul`      VARCHAR(255) NOT NULL,
  `deskripsi`  TEXT         DEFAULT NULL,
  `kategori`   VARCHAR(100) DEFAULT NULL,
  `file`       VARCHAR(255) DEFAULT NULL,
  `tgl_upload` DATE         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_kat` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `perpustakaan` (`judul`, `deskripsi`, `kategori`, `tgl_upload`) VALUES
('Peraturan Daerah Kota Cilegon No. 5 Tahun 2022 tentang Ketertiban Umum', 'Peraturan daerah yang mengatur ketertiban dan ketentraman umum di Kota Cilegon.', 'Peraturan Daerah', '2024-01-10'),
('Panduan Layanan Administrasi Kelurahan', 'Panduan lengkap prosedur dan persyaratan untuk berbagai layanan administrasi di kelurahan.', 'Panduan Layanan', '2024-06-01'),
('Profil Kelurahan Pulomerak 2024', 'Dokumen profil lengkap Kelurahan Pulomerak termasuk data kependudukan, potensi, dan program kerja.', 'Arsip Kelurahan', '2024-03-15'),
('Laporan Tahunan Kelurahan 2023', 'Laporan lengkap kegiatan dan capaian program Kelurahan Pulomerak sepanjang tahun 2023.', 'Laporan Tahunan', '2024-01-31');

-- ============================================================
-- SELESAI - Import sukses!
-- Langkah selanjutnya: buka setup_admin.php untuk buat akun admin
-- ============================================================
