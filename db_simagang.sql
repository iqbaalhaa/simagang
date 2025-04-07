-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 07, 2025 at 11:10 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_mahasiswa`, `tanggal`, `jam_masuk`, `jam_pulang`, `kegiatan`, `bukti_kehadiran`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '2025-03-14', '09:52:15', '09:56:42', 'a', '1741945935_eeaf85de16be088cfc50.png', 'hadir', '2025-03-14 09:52:15', '2025-03-14 09:56:42'),
(2, 7, '2025-03-18', '06:29:52', NULL, 'audit uang masuk', '', 'hadir', '2025-03-18 06:29:52', NULL),
(3, 15, '2025-03-20', '08:10:54', NULL, 'membuat izin cafe', '', 'hadir', '2025-03-20 08:10:54', NULL),
(4, 14, '2025-03-20', '08:12:15', NULL, 'membuat izin mendirikan taman kota', '', 'hadir', '2025-03-20 08:12:15', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota_kelompok`
--

INSERT INTO `anggota_kelompok` (`id`, `pengajuan_id`, `mahasiswa_id`, `created_at`) VALUES
(10, 5, 2, '2025-03-03 21:13:18'),
(9, 5, 1, '2025-03-03 21:13:18'),
(16, 11, 5, '2025-03-17 05:28:46'),
(17, 11, 7, '2025-03-17 05:28:46'),
(18, 12, 8, '2025-03-17 05:57:26'),
(21, 15, 9, '2025-03-19 20:41:16'),
(22, 15, 10, '2025-03-19 20:41:16'),
(23, 15, 11, '2025-03-19 20:41:16'),
(24, 17, 18, '2025-03-20 15:00:48'),
(25, 17, 16, '2025-03-20 15:00:48'),
(26, 17, 14, '2025-03-20 15:00:48'),
(27, 18, 19, '2025-03-21 10:52:32'),
(28, 18, 17, '2025-03-21 10:52:32'),
(29, 18, 21, '2025-03-21 10:52:32'),
(30, 19, 20, '2025-04-07 15:58:20'),
(31, 19, 23, '2025-04-07 15:58:20'),
(32, 19, 26, '2025-04-07 15:58:20');

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `nama_dokumen`, `file_dokumen`, `keterangan`, `status`, `tgl_upload`) VALUES
(3, 'permohonan Kerja Peraktek', '1742280855_1817372895dc0290d044.docx', '', 'Aktif', '2025-03-18 06:54:15'),
(4, 'Penunjukan pembimbing KP', '1742280926_557f94821893aa423e34.docx', '', 'Aktif', '2025-03-18 06:55:26'),
(5, 'Biodata Pembimbing Perusahaan', '1742280968_e15c078024d2f3293c2f.docx', '', 'Aktif', '2025-03-18 06:56:08'),
(6, 'Surat Keterangan Selesai KP', '1742280996_8cf11ffeabc35d32cec2.docx', '', 'Aktif', '2025-03-18 06:56:36'),
(7, 'Penilaian Pelaksanaan KP', '1742281029_e4d17716705842eeb6d3.docx', '', 'Aktif', '2025-03-18 06:57:09'),
(8, 'Kartu Bimbingan KP', '1742281057_65663136b9835c8b5f4e.docx', '', 'Aktif', '2025-03-18 06:57:37'),
(9, 'Kehadiran di Seminar KP Fakultas', '1742281178_9837766cbf214fdde445.docx', '', 'Aktif', '2025-03-18 06:59:38'),
(10, 'Undangan Seminar KP', '1742281253_c838e1677f27937a1855.docx', '', 'Aktif', '2025-03-18 07:00:53'),
(11, 'Daftar Hadir Seminar KP', '1742281282_5436917e56195d5e9666.docx', '', 'Aktif', '2025-03-18 07:01:22'),
(12, 'Penilaian Seminar KP', '1742281306_f4d71419e627b36884c1.docx', '', 'Aktif', '2025-03-18 07:01:46'),
(13, 'Berita Acara Seminar KP', '1742281327_a9389546e11243fec2f8.docx', '', 'Aktif', '2025-03-18 07:02:07'),
(14, 'Rekapitulasi Nilai Akhir KP', '1742281351_0fc9d268209c11a03094.docx', '', 'Aktif', '2025-03-18 07:02:31'),
(15, 'Bukti Penyerahan Laporan KP Kepada Prodi', '1742281381_7fc3c2f13946d8901cab.docx', '', 'Aktif', '2025-03-18 07:03:01'),
(16, 'Pedoman Kerja Praktek Final', '1742281406_5989ac75ce345ecad6c1.docx', '', 'Aktif', '2025-03-18 07:03:26'),
(17, 'Template Laporan KP', '1742281439_545b17bedceb0926ac1b.docx', '', 'Aktif', '2025-03-18 07:03:59'),
(18, 'Template Log Book KP', '1742281463_d62a3f35a78ca55fab40.docx', '', 'Aktif', '2025-03-18 07:04:23');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dosen_pembimbing`
--

INSERT INTO `dosen_pembimbing` (`id_dosen`, `id_user`, `nama`, `nidn`, `foto`) VALUES
(8, 41, 'Muttamasikin, M.Kom', 114, NULL),
(7, 35, 'Bastomi Baharsyah, M.Kom', 112, NULL),
(6, 34, 'Yerix Ramadhani, M.Kom', 1111, NULL),
(9, 42, 'Albert, M.Kom', 115, NULL),
(10, 43, 'Andreo yudertha, M.Kom', 116, NULL);

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
  `pengajuan_id` int UNSIGNED DEFAULT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_loa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','disetujui','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_loa`),
  KEY `id_mahasiswa` (`id_mahasiswa`),
  KEY `pengajuan_id` (`pengajuan_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loa_journal`
--

INSERT INTO `loa_journal` (`id_loa`, `id_mahasiswa`, `pengajuan_id`, `judul`, `deskripsi`, `file_loa`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 4, 11, 'zz', 'zaz', '1742065427_f8c68c45ca56c41fc8e5.pdf', 'disetujui', 'nn', '2025-03-15 19:03:47', '2025-03-17 16:47:44'),
(2, 26, NULL, 'w', 'w', '1744024060_b674d4c4079592cf3c9d.pdf', 'pending', NULL, '2025-04-07 11:07:40', '2025-04-07 11:07:40');

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
(2, 7, 1, '2025-03-15', 'nn', 'disetujui', '2025-03-15 06:26:35', '2025-03-17 08:21:10');

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
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nim`, `nama`, `angkatan`, `instansi`, `foto`, `instansi_id`) VALUES
(19, 44, '701210128', 'Rama Hikma Yudha', '2021', 'Disdukcapil', '1742458526_98ba2713a35ba64b737e.jpg', NULL),
(18, 40, '701210212', 'Heru Wahyu', '0000', 'Dinas Pariwisata', '1742457583_133aef929687def8662a.jpg', NULL),
(17, 39, '701210127', 'Reza Putri Puspita Dewi', '2021', 'Disdukcapil', '1742457220_d622ba04de9d25165214.png', NULL),
(7, 24, '70121012', 'Maydinda Amelia Putri', '2024', 'pln', '1741956800_cd9054cb1f9a35d1fdc1.jpg', NULL),
(16, 38, '701210210', 'Rivo Diandra', '2021', 'Dinas Pariwisata', '1742457047_74090170c16178689c08.png', NULL),
(20, 45, '7012129', 'Iza Diniati', '2021', '-', '1742523144_7f641f44f51f0289e6d4.jpg', NULL),
(10, 30, '701210041', 'Suci Aryeni', '2021', 'Disdukcapil', '1742358482_82ec0543f2519386282d.jpg', NULL),
(14, 36, '701210206', 'Audea Rizki Putri', '2021', 'Dinas Pariwisata', '1742456232_3a95b9bb4b3437415989.png', NULL),
(15, 37, '701210042', 'Khobari Akbar', '2021', 'Dinas Pariwisata', '1742456811_8fef3ec6925bf6ea0408.jpg', NULL),
(21, 28, '701210207', 'Rezki Kurnia Sholehati Nala Putri', '2021', '-', '1742523466_74032b0d22d0324c157f.png', NULL),
(22, 47, '701210114', 'Muhammad Askar', '2022', '-', '1742523825_4f99e1991b056ad81fc1.jpg', NULL),
(23, 26, '70120089', 'Budi Siregar', '2023', '-', '1744013747_f5d100fdb76d1b5e221c.png', NULL),
(24, 21, NULL, 'ayek', '2025', '', '', NULL),
(25, 32, NULL, 'iqbal', '2025', '', '', NULL),
(26, 13, '701200009', 'Iqbal Hanafi', '2021', '-', '1744014440_691966f46e8d65da416b.jpeg', NULL);

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
-- Table structure for table `nilai`
--

DROP TABLE IF EXISTS `nilai`;
CREATE TABLE IF NOT EXISTS `nilai` (
  `id_nilai` int NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int NOT NULL,
  `id_dosen` int NOT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_nilai`),
  KEY `id_mahasiswa` (`id_mahasiswa`),
  KEY `id_dosen` (`id_dosen`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_mahasiswa`, `id_dosen`, `nilai`, `keterangan`, `created_at`) VALUES
(3, 4, 25, 9.50, NULL, '2025-03-18 07:51:34'),
(4, 7, 25, 8.00, NULL, '2025-03-19 20:47:20');

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
  `id_dosen` int DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `surat_permohonan` varchar(255) DEFAULT NULL,
  `surat_pengantar` varchar(255) DEFAULT NULL,
  `surat_balasan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ketua_id` (`ketua_id`),
  KEY `instansi_id` (`instansi_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengajuan_magang`
--

INSERT INTO `pengajuan_magang` (`id`, `nama_kelompok`, `ketua_id`, `instansi_id`, `id_dosen`, `status`, `surat_permohonan`, `surat_pengantar`, `surat_balasan`, `created_at`, `updated_at`) VALUES
(11, 'jjj', 4, 3, 3, 'disetujui', '1742164126_c9eceb54c217d5e07dc6.pdf', '1742164533_b4e0c4573125ba31729c.pdf', '1742165365_95cfb78e958f63a68d5f.pdf', '2025-03-16 22:28:46', '2025-03-17 05:49:25'),
(12, 'gg', 8, 1, 1, 'disetujui', '1742165846_76201402b66dab2fe836.pdf', '1742166094_8ca1e98e6b4f08608de8.pdf', NULL, '2025-03-16 22:57:26', '2025-03-17 06:01:34'),
(17, 'Dinas Pariwisata', 15, 4, 8, 'disetujui', '1742457648_c815e7b68ff4eb83a1df.pdf', '1742458046_62d97dab39b15f5b6f05.pdf', NULL, '2025-03-20 08:00:48', '2025-03-20 15:07:26'),
(15, 'kelompok dukcapil', 12, 9, 5, 'disetujui', '1742391676_ba71ea665cf146b59099.pdf', '1742391748_5d6435d1dd2e572635d8.pdf', '1742401053_c7a22603c2c5d1871a3c.pdf', '2025-03-19 13:41:16', '2025-03-19 23:17:33'),
(16, 'pln', 7, 8, 6, 'disetujui', '1742455269_66d4f40440be340f1eff.pdf', '1742455929_de3614a40a3d39bc7072.pdf', NULL, '2025-03-20 07:21:09', '2025-03-20 14:32:09'),
(18, 'Polda', 21, 12, 7, 'disetujui', '1742529152_1f66ce45e700506ca702.pdf', '1742529226_265f3cad06367fb374f6.pdf', NULL, '2025-03-21 03:52:32', '2025-03-21 10:53:46'),
(19, 'Kelompok 111', 26, 21, 6, 'disetujui', '1744016300_ab87aa35f54cfe3905e9.pdf', '1744017577_3cb197ffb889b69df505.pdf', '1744017600_e7b945dcfddebfe3a632.pdf', '2025-04-07 08:58:20', '2025-04-07 16:20:00');

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
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$osatC57vXNvpvcpcL0Aa6.8mGFwLqwiTeL1RK8mUtZ7QpMEiy3Sza', 'admin@gmail.com', 'admin', '2025-02-24 04:44:15', '2025-02-28 06:48:44'),
(22, 'lubis', '$2y$10$RGy9DXHW6oUMUnHErJ.OauRnEkagJWkbMLtQoRg.yu5msm2oc2tmC', 'lubis@gmail.com', 'mahasiswa', '2025-03-04 01:17:13', '2025-03-04 01:17:13'),
(26, 'budi', '$2y$10$AkWK1ZSSAGin/KS6cNUcIeGojQClPxqqeG.lTK54qXiFmUjvhehXC', 'budi@gmail.com', 'mahasiswa', '2025-03-16 15:55:53', '2025-03-16 15:55:53'),
(13, 'iqbaalhaa', '$2y$10$LAuj85MLKGSn0N8wv1Nh4e0k2Yn3vNsOfWPeWo2eo6K/PqgARJ5Fm', 'iqbalhaanafi@gmail.com', 'mahasiswa', '2025-02-28 01:34:00', '2025-02-28 01:34:00'),
(14, 'admin02', '$2y$10$tfI.fuj3vr2QxFRAybQoiOwmDke291tHvPvoWR72AdDZ9qZO3YsCm', 'admin02@gmail.com', 'admin', '2025-02-28 22:21:01', '2025-02-28 22:21:01'),
(19, 'andi11', '$2y$10$FJMy7taidJ80DlXao5cLouLNGoCY4e4z3ymBYNq2vE8balZbpafN.', 'andi@gmail.com', 'mahasiswa', '2025-03-01 04:30:06', '2025-03-01 04:30:06'),
(36, 'audea12', '$2y$10$DFtHfY2cBR4oG13AdB6ZXezN3WF6JTKW2fTqqEP63Qv1K7R.OuFYa', 'dea@gmail.com', 'mahasiswa', '2025-03-20 00:35:18', '2025-03-20 00:35:18'),
(21, 'ayek', '$2y$10$WUkbjiqffAcZhkkurDY9j.FrVgLsAqVGWn7zFZOlEJDnubCJCK04u', 'ayek@gmail.com', 'mahasiswa', '2025-03-02 11:08:22', '2025-03-02 11:08:22'),
(23, 'jawo', '$2y$10$r6knezem4UNfYMswPhi89Of0sfksAFHZhLtBXhQ05apVmFnoQ6Q/W', 'jawo@gmail.com', 'mahasiswa', '2025-03-04 01:24:41', '2025-03-04 01:24:41'),
(24, 'dinda', '$2y$10$3B1zuzo5avkxbOsCyASKne2zAxyJVEfLfojKGld6IUXkJuL20m58i', 'dinda@email.com', 'mahasiswa', '2025-03-14 05:48:59', '2025-03-14 05:48:59'),
(35, 'tomi', '$2y$10$G75QdsCiAIlW9b.OsaMjqOUA/SBqWX4iXdASHipaSQSpKOBm8g8oO', 'tomi@gmail.com', 'dosen', '2025-03-20 07:25:46', '2025-03-20 07:25:46'),
(34, 'yerix', '$2y$10$52XbkCaH.q7l6qMaRyCUSO/L35bhVATzfPL.tzxXEzySQNgkcaa6K', 'yerix@gmail.com', 'dosen', '2025-03-20 07:22:34', '2025-03-20 07:22:34'),
(28, 'puput', '$2y$10$CRgoIWQJRuW534TxPJULcuWYfcRohq4e3zQxUgIDWWqltrfJuqgnu', 'puput@gmail.com', 'mahasiswa', '2025-03-18 00:07:03', '2025-03-18 00:07:03'),
(37, 'abay12345', '$2y$10$98vujU7US7R.B8lv.zl9suKCpVJixm4E9j5cNW.Qn.ykxHNvr6nf.', 'khobariabay@gmail.com', 'mahasiswa', '2025-03-20 00:45:12', '2025-03-20 00:45:12'),
(30, 'Suci', '$2y$10$e7oxoTZ7CTNUtbYn9yKz8u6nFmEKAUk8fj910Xzd7K/9RkjZXE6Lq', 'suci@gmail.com', 'mahasiswa', '2025-03-18 21:27:04', '2025-03-18 21:27:04'),
(31, 'melan', '$2y$10$37Pi94SIdhTQs62OwXtmUuhfRp923nrs4QcrZbAlVqEhFvbeXVjCS', 'melan@gmail.com', 'mahasiswa', '2025-03-18 21:34:31', '2025-03-18 21:34:31'),
(32, 'iqbal', '$2y$10$Gujl28EqsN/AE/eXNOWoY.2700JAvTT5FMJ2XJr8y/DXqK2.D1ELK', 'iqbal@gmail.com', 'mahasiswa', '2025-03-19 06:38:27', '2025-03-19 06:38:27'),
(33, 'joni', '$2y$10$4Eu.U/J64k5I9C5j6IkHxOGGMX4JC73gF6HV.sIlHOgFDavEIxAXa', 'joni@gmail.com', 'mahasiswa', '2025-03-19 08:53:45', '2025-03-19 08:53:45'),
(38, 'rivoy456', '$2y$10$sz3i.kLlmDcQ60eseX/LIufVY9on/hJsELDLv1q2ggQaXJKjSfx72', 'rivodiandra@gmail.com', 'mahasiswa', '2025-03-20 00:49:45', '2025-03-20 00:49:45'),
(39, 'rezaputri12', '$2y$10$1n5EbUkealwIsEoPW5Y7hOTDtFigVg7fOG29ELXWClpnJIvE6qEVu', 'reza@gmail.com', 'mahasiswa', '2025-03-20 00:52:28', '2025-03-20 00:52:28'),
(40, 'heruwahyu1', '$2y$10$6wCTuEBBLw1Acxyww94XYu13vSpteXYEtmIjKSwHp9U/Lk2T1hpF2', 'heru@gmail.com', 'mahasiswa', '2025-03-20 00:57:45', '2025-03-20 00:57:45'),
(41, 'ikin12', '$2y$10$RLjTO7wrH435jt5WloFRZuA17n6denpKJxD5OXKQ0.O1fzZGh6lEa', 'pakikin@gmail.com', 'dosen', '2025-03-20 08:04:08', '2025-03-20 08:04:08'),
(42, 'albert12', '$2y$10$HcuBSwQZFY90E1uaNn35YumWyCZ06jLziOpG7E1zUY8WlMe10HN9.', 'albert@gmail.com', 'dosen', '2025-03-20 08:04:54', '2025-03-20 08:04:54'),
(43, 'andreo12', '$2y$10$d44F1e1f/60ekM7WgLAPY.OmvdUPlTO52DixFx4nvy0jNJ1gok94q', 'andreo@gmail.com', 'dosen', '2025-03-20 08:06:20', '2025-03-20 08:06:20'),
(44, 'hikma', '$2y$10$Kxi5ekcug8PEnt50vcc6meI/Hrxlm26QvT/TkJEcEuspYLkBu0iaq', 'hima@gmail.com', 'mahasiswa', '2025-03-20 01:14:18', '2025-03-20 01:14:18'),
(45, 'iza12345', '$2y$10$UNwxlsI8PtFHFNIjxuB.6eEu6/t71dwhRA2DU1IApowZ5uwczVc46', 'iza@gmail.com', 'mahasiswa', '2025-03-20 19:10:55', '2025-03-20 19:10:55'),
(46, 'nurma456', '$2y$10$50vII1LG1XVrhAcy.3whjuhvtu747fkVVQzGUgIuQfsnssLsmo2LC', 'nurma@gmail.com', 'mahasiswa', '2025-03-20 19:14:33', '2025-03-20 19:14:33'),
(47, 'askar', '$2y$10$1EtbmimLYWuq2L236ww5yOTSvedOzr0xtb8vXF5F52WJMbfiwkta.', 'askar@gmail.com', 'mahasiswa', '2025-03-20 19:15:55', '2025-03-20 19:15:55');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
