-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 15, 2025 at 07:07 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simagang`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

DROP TABLE IF EXISTS `absensi`;
CREATE TABLE IF NOT EXISTS `absensi` (
  `id_absensi` int NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `kegiatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `bukti_kehadiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('hadir','izin','sakit') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'hadir',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_absensi`),
  KEY `id_mahasiswa` (`id_mahasiswa`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_mahasiswa`, `tanggal`, `jam_masuk`, `jam_pulang`, `kegiatan`, `bukti_kehadiran`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '2025-03-14', '09:52:15', '09:56:42', 'a', '1741945935_eeaf85de16be088cfc50.png', 'hadir', '2025-03-14 09:52:15', '2025-03-14 09:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `user_id` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `id_user`, `nama`, `foto`) VALUES
(1, 1, 'Admin Prodi 01', '1742065552_fb2ac0e43489a2466dfd.jpg'),
(2, 14, 'Admin Prodi 02', '1740781261_83a2a957dd5f1af55116.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_kelompok`
--

DROP TABLE IF EXISTS `anggota_kelompok`;
CREATE TABLE IF NOT EXISTS `anggota_kelompok` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pengajuan_id` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_anggota` (`pengajuan_id`,`mahasiswa_id`),
  KEY `mahasiswa_id` (`mahasiswa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota_kelompok`
--

INSERT INTO `anggota_kelompok` (`id`, `pengajuan_id`, `mahasiswa_id`, `created_at`) VALUES
(10, 5, 2, '2025-03-03 21:13:18'),
(9, 5, 1, '2025-03-03 21:13:18'),
(12, 8, 4, '2025-03-14 14:22:49'),
(13, 9, 7, '2025-03-14 19:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `bimbingan`
--

DROP TABLE IF EXISTS `bimbingan`;
CREATE TABLE IF NOT EXISTS `bimbingan` (
  `id_bimbingan` int NOT NULL AUTO_INCREMENT,
  `id_dosen` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text,
  `status` enum('pending','selesai') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bimbingan`),
  KEY `id_dosen` (`id_dosen`),
  KEY `id_mahasiswa` (`id_mahasiswa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

DROP TABLE IF EXISTS `dokumen`;
CREATE TABLE IF NOT EXISTS `dokumen` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) NOT NULL,
  `file_dokumen` varchar(255) NOT NULL,
  `keterangan` text,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Aktif',
  `tgl_upload` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `nama_dokumen`, `file_dokumen`, `keterangan`, `status`, `tgl_upload`) VALUES
(1, 'ss', '1742065359_ad2638ff5cabd9c2af85.pdf', 'asa', 'Aktif', '2025-03-15 19:02:39');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_pembimbing`
--

DROP TABLE IF EXISTS `dosen_pembimbing`;
CREATE TABLE IF NOT EXISTS `dosen_pembimbing` (
  `id_dosen` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nidn` int NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_dosen`),
  UNIQUE KEY `user_id` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dosen_pembimbing`
--

INSERT INTO `dosen_pembimbing` (`id_dosen`, `id_user`, `nama`, `nidn`, `foto`) VALUES
(1, 17, 'albet, S.Kom', 12414141, NULL),
(2, 3, 'dosen pembimbing', 1221112, '1741202380_f6fe32f605bab4e490a2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `instansi`
--

DROP TABLE IF EXISTS `instansi`;
CREATE TABLE IF NOT EXISTS `instansi` (
  `id_instansi` int NOT NULL AUTO_INCREMENT,
  `nama_instansi` varchar(100) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_instansi`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `instansi`
--

INSERT INTO `instansi` (`id_instansi`, `nama_instansi`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'PUPR Provinsi Jambi', ' Jl. Zainir Haviz No.4, Paal Lima, Kota Baru, Kota Jambi, Jambi 36129', '2025-03-01 04:43:21', '2025-03-15 14:30:41'),
(2, 'Dinas Komunikasi dan Digital/Diskominfo Provinsi Jambi', 'Jl A.Yani No.1, Telanaipura, Jambi, Kota Jambi, Jambi 36361', '2025-03-01 04:43:21', '2025-03-15 14:31:26'),
(3, 'KANTOR IMIGRASI Kelas 1 TPI JAMBI', 'Jl. Arif Rahman Hakim No. 63, Kota Jambi, Jambi.', '2025-03-01 04:43:21', '2025-03-15 14:32:13'),
(4, 'Dinas Kebudayaan dan Pariwisata Provinsi Jambi', 'JL. K.H. Agus Salim, Paal Lima, Kec. Kota Baru, Kota Jambi, Jambi 36129', '2025-03-01 04:43:21', '2025-03-15 14:32:55'),
(6, 'BAPEDA', 'JL. Jenderal Basuki Rahmat, No. 1, Kotabaru, Jambi, Paal Lima, Baru City, Jambi City, Jambi 36129', '2025-03-04 08:17:46', '2025-03-15 14:33:41'),
(7, 'RRI JAMBI (Radio Republik Indonesia)', 'Jl. Jend. Ahmad Yani No.5, Telanaipura, Kec. Telanaipura, Kota Jambi', '2025-03-15 14:34:31', NULL),
(8, 'PLN UP3 JAMBI', 'Jl. Urip Sumoharjo No. 02, Telanaipura, Kota Jambi, Jambi', '2025-03-15 14:35:04', NULL),
(9, 'Dinas Kependudukan dan Pencatatan Sipil jambi', 'Jl. H. Zainir Haviz, Paal Lima, Kec. Kota Baru, Kota Jambi, Jambi 36129', '2025-03-15 14:35:58', NULL),
(10, 'Taman Rimbo Zoo Jambi', 'Talang Bakung, Jambi Selatan, Jambi City, Jambi 36127', '2025-03-15 14:37:17', NULL),
(11, 'Sekteriat DPRD kota Jambi', 'Jl. Jend. Basuki Rahmat no.1 Kotabaru, Jambi ', '2025-03-15 14:39:16', NULL),
(12, 'Polda Jambi', ' Jl. Jend. Sudirman No. 45, Kota Jambi', '2025-03-15 14:40:02', NULL),
(13, 'Kantor Dinas pemuda dan olahraga', 'Jl. Slamet Riyadi, Solok Sipin, Kec. Telanaipura, Kota Jambi\r\n', '2025-03-15 14:40:52', NULL),
(14, 'BMKG STASIUN KLIMATOLOGI JAMBI', 'Jl. Jambi - Muara Bulian No.Km.18, Simpang Sungai Duren, Kec. Jambi Luar Kota, Kabupaten Muaro Jambi, Jambi 36361', '2025-03-15 14:41:22', NULL),
(15, 'Kabag Tata Usaha Biro Umum', 'Jl. Jend. Ahmad Yani No.1, Telanaipura, Kec. Telanaipura, Kota Jambi, Jambi 36128', '2025-03-15 14:42:16', NULL),
(16, 'balai penerpaan standar instrumen pertanian jambi', 'Jl. Samarinda No. 11 Paal Lima, Kota Baru, Jambi.', '2025-03-15 14:42:50', NULL),
(17, 'Perpustakaan umum kota Jambi', 'Jl. Profesor Dr Jl. Sumantri Brojonegoro, Sungai Putri, Kec. Telanaipura, Kota Jambi, Jambi 36124, Indonesia.', '2025-03-15 14:43:22', NULL),
(18, 'KPU Provinsi Jambi', 'Jl. Jend. A. Thalib No. 33, Telanaipura, Pematang Sulur, Telanaipura, Kota Jambi, Jambi 36122', '2025-03-15 14:43:55', NULL),
(19, 'Dinas Pekerjaan Umum dan Perumahan Rakyat', 'Jl. Zainir Haviz No.4, Paal Lima, Kota Baru, Kota Jambi, Jambi 36129', '2025-03-15 14:44:28', NULL),
(20, 'PT Taspen (Persero)', 'Lorong Cendana Broni, Solok Sipin, Kec. Telanaipura, Kota Jambi, Jambi 36124', '2025-03-15 14:45:11', NULL),
(21, 'Kantor DPR provinsi jambi', 'Jl. A Yani No.2, Telanaipura, Kota Jambi, Jambi 36361', '2025-03-15 14:45:48', NULL),
(22, 'Jambi TV', 'Jl. Kapten Pattimura No.35D, Kenali Besar, Kec. Kota Baru, Kota Jambi, Jambi 36361', '2025-03-15 14:46:25', NULL),
(23, 'PT TELKOM INDONESIA-WITEL JAMBI', 'Jalan Gajah Mada, Muarabulian, Kecamatan Muarabulian, Jambi', '2025-03-15 14:47:12', NULL),
(24, 'Kantor Bahasa Prov Jambi', 'Jalan Arif Rahman Hakim No. 101, Telanaipura, Jambi, Indonesia, 36124', '2025-03-15 14:47:45', NULL),
(25, 'Kantor Kementrian Agama Kota Jambi', 'Jl. Prof. DR. Hamka No.5, Beringin, Kec. Ps. Jambi, Kota Jambi, Jambi 36124', '2025-03-15 14:48:19', NULL),
(26, 'BADAN PUSAT STATISTIK PROVINSI JAMBI (BPS)', 'Jl. Jend. Ahmad Yani No.4, Telanaipura, Kota Jambi, Jambi 36122', '2025-03-15 14:48:50', NULL),
(27, 'PT Superintending Company of Indonesia (SUCOFINDO)', 'Jl. Fatmawati No. 25, Kota Jambi, 36141', '2025-03-15 14:50:04', NULL),
(28, 'PT Pertamina Hulu Rokan Zona 1 Jambi', 'Jl. Lirik No. 1, Komperta Kenali Asam, Kota Jambi, Jambi, 36128', '2025-03-15 14:50:52', NULL),
(29, 'Kantor Bahasa Provinsi Jambi', 'Jalan Arif Rahman Hakim No. 101, Telanaipura, Jambi, Indonesia, 36124', '2025-03-15 14:52:07', NULL),
(30, 'PT Angkasa Pura Indonesia Cabang Jambi', 'Bandar Udara Internasional Sultan Thaha. jalan jawa, bundaran bandara No.depan, Paal Merah, Jambi Sel., Kota Jambi, Jambi 36127', '2025-03-15 14:52:50', NULL),
(31, 'Badan Kepegawaian Daerah Provinsi Jambi', 'Jl. RM. Noer Atmadibrata No. 2 Kel.Telanaipura Kec. Telanaipura Kota Jambi, 36122', '2025-03-15 14:53:50', NULL),
(32, 'BIRO KESEJAHTERAAN RAKYAT', ' Jl. A. Yani No.1 Telanaipura Jambi.', '2025-03-15 14:55:13', NULL),
(33, 'Pemerintah Provinsi Jambi, SekDa Biro Administrasi Pimpinan', 'Jl. A. Yani No.1 Telanaipura Jambi', '2025-03-15 14:56:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loa_journal`
--

DROP TABLE IF EXISTS `loa_journal`;
CREATE TABLE IF NOT EXISTS `loa_journal` (
  `id_loa` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_loa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','disetujui','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_loa`),
  KEY `id_mahasiswa` (`id_mahasiswa`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loa_journal`
--

INSERT INTO `loa_journal` (`id_loa`, `id_mahasiswa`, `judul`, `deskripsi`, `file_loa`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 4, 'zz', 'zaz', '1742065427_f8c68c45ca56c41fc8e5.pdf', 'pending', NULL, '2025-03-15 19:03:47', '2025-03-15 19:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

DROP TABLE IF EXISTS `logbook`;
CREATE TABLE IF NOT EXISTS `logbook` (
  `id_logbook` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int UNSIGNED NOT NULL,
  `hari_ke` int NOT NULL,
  `tanggal` date NOT NULL,
  `uraian_kegiatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `paraf_pembimbing` enum('belum','disetujui','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'belum',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_logbook`),
  KEY `id_mahasiswa` (`id_mahasiswa`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook`
--

INSERT INTO `logbook` (`id_logbook`, `id_mahasiswa`, `hari_ke`, `tanggal`, `uraian_kegiatan`, `paraf_pembimbing`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '0025-01-12', 'membuat', 'belum', '2025-03-14 21:53:50', '2025-03-14 21:53:50'),
(2, 7, 1, '2025-03-15', 'nn', 'belum', '2025-03-15 06:26:35', '2025-03-15 06:26:35');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id_mahasiswa` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nim` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `angkatan` year DEFAULT NULL,
  `instansi` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `instansi_id` int DEFAULT NULL,
  PRIMARY KEY (`id_mahasiswa`),
  UNIQUE KEY `user_id` (`id_user`),
  UNIQUE KEY `nim` (`nim`),
  KEY `instansi_id` (`instansi_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nim`, `nama`, `angkatan`, `instansi`, `foto`, `instansi_id`) VALUES
(1, 19, '01020202', 'Andi Lubis', '2020', 'Bank 9 Jambi', '1740803406_e4f3fcff4573215862c2.jpg', NULL),
(2, 21, '00120001', 'Ayek Muhammadd', '2021', '-', '1740975744_ab08f5a0e39ffb4d159d.jpg', NULL),
(4, 23, '123123', 'jawo baru', '2022', 'Bank 9 Jambi', '1741078759_e292dae1cdd0379c0afd.jpeg', NULL),
(5, 22, '70121011', 'lubis batak', '2023', 'institut pertanian bogor', '1741079518_ceacaa4e153bc5d42a9d.png', NULL),
(7, 24, '70121012', 'Maydinda Amelia Putri', '2024', 'pln', '1741956800_cd9054cb1f9a35d1fdc1.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_magang`
--

DROP TABLE IF EXISTS `pengajuan_magang`;
CREATE TABLE IF NOT EXISTS `pengajuan_magang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kelompok` varchar(100) NOT NULL,
  `ketua_id` int NOT NULL,
  `instansi_id` int NOT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `surat_permohonan` varchar(255) DEFAULT NULL,
  `surat_pengantar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ketua_id` (`ketua_id`),
  KEY `instansi_id` (`instansi_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengajuan_magang`
--

INSERT INTO `pengajuan_magang` (`id`, `nama_kelompok`, `ketua_id`, `instansi_id`, `status`, `surat_permohonan`, `surat_pengantar`, `created_at`, `updated_at`) VALUES
(8, 'Kelompok 1', 4, 6, 'disetujui', '1741936969_bb3c4c9eebf2a76c915e.pdf', '1741937025_6782ffab2fe75b43c44c.pdf', '2025-03-14 07:22:49', '2025-03-14 14:23:45'),
(9, 'bank 9', 7, 6, 'disetujui', '1741956909_f4478a0dec99359d99db.pdf', '1741957215_0dc96ed4fdd90ba41ee8.pdf', '2025-03-14 12:55:09', '2025-03-14 20:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','dosen','mahasiswa') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$osatC57vXNvpvcpcL0Aa6.8mGFwLqwiTeL1RK8mUtZ7QpMEiy3Sza', 'admin@gmail.com', 'admin', '2025-02-24 04:44:15', '2025-02-28 06:48:44'),
(22, 'lubis', '$2y$10$RGy9DXHW6oUMUnHErJ.OauRnEkagJWkbMLtQoRg.yu5msm2oc2tmC', 'lubis@gmail.com', 'mahasiswa', '2025-03-04 01:17:13', '2025-03-04 01:17:13'),
(3, 'dosen', '$2y$10$BCFDyuXYPU3HQnNprJzCp.VSKX8Q/tjejlJyk2lfyUPwOtwLdv7d2', 'dosen@gmail.com', 'dosen', '2025-02-24 19:11:09', '2025-02-28 06:48:44'),
(13, 'iqbaalhaa', '$2y$10$LAuj85MLKGSn0N8wv1Nh4e0k2Yn3vNsOfWPeWo2eo6K/PqgARJ5Fm', 'iqbalhaanafi@gmail.com', 'mahasiswa', '2025-02-28 01:34:00', '2025-02-28 01:34:00'),
(14, 'admin02', '$2y$10$tfI.fuj3vr2QxFRAybQoiOwmDke291tHvPvoWR72AdDZ9qZO3YsCm', 'admin02@gmail.com', 'admin', '2025-02-28 22:21:01', '2025-02-28 22:21:01'),
(19, 'andi11', '$2y$10$FJMy7taidJ80DlXao5cLouLNGoCY4e4z3ymBYNq2vE8balZbpafN.', 'andi@gmail.com', 'mahasiswa', '2025-03-01 04:30:06', '2025-03-01 04:30:06'),
(17, 'albet12', '$2y$10$dPOfTow3NVSHd4u2Bwsh3.fZtiVRDU23DXtKKfRDwBS0cxpHSEdn.', 'albet@gmail.com', 'dosen', '2025-02-28 22:47:32', '2025-02-28 22:47:32'),
(21, 'ayek', '$2y$10$WUkbjiqffAcZhkkurDY9j.FrVgLsAqVGWn7zFZOlEJDnubCJCK04u', 'ayek@gmail.com', 'mahasiswa', '2025-03-02 11:08:22', '2025-03-02 11:08:22'),
(23, 'jawo', '$2y$10$r6knezem4UNfYMswPhi89Of0sfksAFHZhLtBXhQ05apVmFnoQ6Q/W', 'jawo@gmail.com', 'mahasiswa', '2025-03-04 01:24:41', '2025-03-04 01:24:41'),
(24, 'dinda', '$2y$10$3B1zuzo5avkxbOsCyASKne2zAxyJVEfLfojKGld6IUXkJuL20m58i', 'dinda@email.com', 'mahasiswa', '2025-03-14 05:48:59', '2025-03-14 05:48:59');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
