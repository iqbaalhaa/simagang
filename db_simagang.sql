-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 19 Mar 2025 pada 16.09
-- Versi server: 9.1.0
-- Versi PHP: 8.3.14

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
-- Struktur dari tabel `absensi`
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_mahasiswa`, `tanggal`, `jam_masuk`, `jam_pulang`, `kegiatan`, `bukti_kehadiran`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '2025-03-14', '09:52:15', '09:56:42', 'a', '1741945935_eeaf85de16be088cfc50.png', 'hadir', '2025-03-14 09:52:15', '2025-03-14 09:56:42'),
(2, 7, '2025-03-18', '06:29:52', NULL, 'audit uang masuk', '', 'hadir', '2025-03-18 06:29:52', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
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
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `id_user`, `nama`, `foto`) VALUES
(1, 1, 'Admin Prodi 01', '1742065552_fb2ac0e43489a2466dfd.jpg'),
(2, 14, 'Admin Prodi 02', '1740781261_83a2a957dd5f1af55116.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_kelompok`
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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `anggota_kelompok`
--

INSERT INTO `anggota_kelompok` (`id`, `pengajuan_id`, `mahasiswa_id`, `created_at`) VALUES
(10, 5, 2, '2025-03-03 21:13:18'),
(9, 5, 1, '2025-03-03 21:13:18'),
(16, 11, 5, '2025-03-17 05:28:46'),
(17, 11, 7, '2025-03-17 05:28:46'),
(18, 12, 8, '2025-03-17 05:57:26'),
(21, 15, 9, '2025-03-19 20:41:16'),
(22, 15, 10, '2025-03-19 20:41:16'),
(23, 15, 11, '2025-03-19 20:41:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen`
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
-- Dumping data untuk tabel `dokumen`
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
-- Struktur dari tabel `dosen_pembimbing`
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `dosen_pembimbing`
--

INSERT INTO `dosen_pembimbing` (`id_dosen`, `id_user`, `nama`, `nidn`, `foto`) VALUES
(1, 17, 'albet, S.Kom', 12414141, NULL),
(3, 25, 'M. Yusuf', 70120, '1742082800_be6430cf7c714a67ccba.jpeg'),
(4, 27, 'Yerix Ramadhani, M.Kom', 801210, NULL),
(5, 29, 'Hery Afriyadi. S.E., S.Kom., M.Si', 2015047103, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `instansi`
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
-- Dumping data untuk tabel `instansi`
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
-- Struktur dari tabel `loa_journal`
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `loa_journal`
--

INSERT INTO `loa_journal` (`id_loa`, `id_mahasiswa`, `pengajuan_id`, `judul`, `deskripsi`, `file_loa`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 4, 11, 'zz', 'zaz', '1742065427_f8c68c45ca56c41fc8e5.pdf', 'disetujui', 'nn', '2025-03-15 19:03:47', '2025-03-17 16:47:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `logbook`
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
-- Dumping data untuk tabel `logbook`
--

INSERT INTO `logbook` (`id_logbook`, `id_mahasiswa`, `hari_ke`, `tanggal`, `uraian_kegiatan`, `paraf_pembimbing`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '0025-01-12', 'membuat', 'belum', '2025-03-14 21:53:50', '2025-03-14 21:53:50'),
(2, 7, 1, '2025-03-15', 'nn', 'disetujui', '2025-03-15 06:26:35', '2025-03-17 08:21:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nim`, `nama`, `angkatan`, `instansi`, `foto`, `instansi_id`) VALUES
(1, 19, '01020202', 'Andi Lubis', '2020', 'Bank 9 Jambi', '1740803406_e4f3fcff4573215862c2.jpg', NULL),
(2, 21, '00120001', 'Ayek Muhammadd', '2021', '-', '1740975744_ab08f5a0e39ffb4d159d.jpg', NULL),
(4, 23, '123123', 'jawo baru', '2022', 'Bank 9 Jambi', '1741078759_e292dae1cdd0379c0afd.jpeg', NULL),
(5, 22, '70121011', 'lubis batak', '2023', 'institut pertanian bogor', '1741079518_ceacaa4e153bc5d42a9d.png', NULL),
(7, 24, '70121012', 'Maydinda Amelia Putri', '2024', 'pln', '1741956800_cd9054cb1f9a35d1fdc1.jpg', NULL),
(8, 26, '09090909', 'Budi Siregar', '2023', '-', '1742165790_6a8564cad2e66d0560ac.jpg', NULL),
(9, 28, '701210207', 'Rezki Kurnia Sholehati Nala Putri', '2021', 'Disdukcapil', '1742281714_9ab9d5eac03263f4f9f9.png', NULL),
(10, 30, '701210041', 'Suci Aryeni', '2021', 'Disdukcapil', '1742358482_82ec0543f2519386282d.jpg', NULL),
(11, 31, '701210126', 'Melan Sari', '2021', 'Disdukcapil', '1742358940_9ed5b91e0b0a55662bb3.jpg', NULL),
(12, 32, '701210009', 'Iqbal Hanafi', '2021', '-', '1742391564_d402d1a03556c303acc5.jpg', NULL),
(13, 33, NULL, 'joni', '2025', '', '', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
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
-- Struktur dari tabel `nilai`
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
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_mahasiswa`, `id_dosen`, `nilai`, `keterangan`, `created_at`) VALUES
(3, 4, 25, 9.50, NULL, '2025-03-18 07:51:34'),
(4, 7, 25, 8.00, NULL, '2025-03-19 20:47:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_magang`
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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengajuan_magang`
--

INSERT INTO `pengajuan_magang` (`id`, `nama_kelompok`, `ketua_id`, `instansi_id`, `id_dosen`, `status`, `surat_permohonan`, `surat_pengantar`, `surat_balasan`, `created_at`, `updated_at`) VALUES
(11, 'jjj', 4, 3, 3, 'disetujui', '1742164126_c9eceb54c217d5e07dc6.pdf', '1742164533_b4e0c4573125ba31729c.pdf', '1742165365_95cfb78e958f63a68d5f.pdf', '2025-03-16 22:28:46', '2025-03-17 05:49:25'),
(12, 'gg', 8, 1, 1, 'disetujui', '1742165846_76201402b66dab2fe836.pdf', '1742166094_8ca1e98e6b4f08608de8.pdf', NULL, '2025-03-16 22:57:26', '2025-03-17 06:01:34'),
(15, 'kelompok dukcapil', 12, 9, 5, 'disetujui', '1742391676_ba71ea665cf146b59099.pdf', '1742391748_5d6435d1dd2e572635d8.pdf', NULL, '2025-03-19 13:41:16', '2025-03-19 20:42:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
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
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$osatC57vXNvpvcpcL0Aa6.8mGFwLqwiTeL1RK8mUtZ7QpMEiy3Sza', 'admin@gmail.com', 'admin', '2025-02-24 04:44:15', '2025-02-28 06:48:44'),
(22, 'lubis', '$2y$10$RGy9DXHW6oUMUnHErJ.OauRnEkagJWkbMLtQoRg.yu5msm2oc2tmC', 'lubis@gmail.com', 'mahasiswa', '2025-03-04 01:17:13', '2025-03-04 01:17:13'),
(26, 'budi', '$2y$10$AkWK1ZSSAGin/KS6cNUcIeGojQClPxqqeG.lTK54qXiFmUjvhehXC', 'budi@gmail.com', 'mahasiswa', '2025-03-16 15:55:53', '2025-03-16 15:55:53'),
(13, 'iqbaalhaa', '$2y$10$LAuj85MLKGSn0N8wv1Nh4e0k2Yn3vNsOfWPeWo2eo6K/PqgARJ5Fm', 'iqbalhaanafi@gmail.com', 'mahasiswa', '2025-02-28 01:34:00', '2025-02-28 01:34:00'),
(14, 'admin02', '$2y$10$tfI.fuj3vr2QxFRAybQoiOwmDke291tHvPvoWR72AdDZ9qZO3YsCm', 'admin02@gmail.com', 'admin', '2025-02-28 22:21:01', '2025-02-28 22:21:01'),
(19, 'andi11', '$2y$10$FJMy7taidJ80DlXao5cLouLNGoCY4e4z3ymBYNq2vE8balZbpafN.', 'andi@gmail.com', 'mahasiswa', '2025-03-01 04:30:06', '2025-03-01 04:30:06'),
(17, 'albet12', '$2y$10$dPOfTow3NVSHd4u2Bwsh3.fZtiVRDU23DXtKKfRDwBS0cxpHSEdn.', 'albet@gmail.com', 'dosen', '2025-02-28 22:47:32', '2025-02-28 22:47:32'),
(21, 'ayek', '$2y$10$WUkbjiqffAcZhkkurDY9j.FrVgLsAqVGWn7zFZOlEJDnubCJCK04u', 'ayek@gmail.com', 'mahasiswa', '2025-03-02 11:08:22', '2025-03-02 11:08:22'),
(23, 'jawo', '$2y$10$r6knezem4UNfYMswPhi89Of0sfksAFHZhLtBXhQ05apVmFnoQ6Q/W', 'jawo@gmail.com', 'mahasiswa', '2025-03-04 01:24:41', '2025-03-04 01:24:41'),
(24, 'dinda', '$2y$10$3B1zuzo5avkxbOsCyASKne2zAxyJVEfLfojKGld6IUXkJuL20m58i', 'dinda@email.com', 'mahasiswa', '2025-03-14 05:48:59', '2025-03-14 05:48:59'),
(25, 'yusuf', '$2y$10$F7PxMcmqaeeCK88/hAXUsO65HP8fKyc2PRRkELobduqo2QFedvWTm', 'yusuf@gmail.com', 'dosen', '2025-03-15 23:45:17', '2025-03-15 23:45:17'),
(27, 'yerix', '$2y$10$v1TZCWvnPLWUrtgojhcMKejTL6aNQ2nWNFNoSYqqk2JFXO/RY/cue', 'yerix@gmail.com', 'dosen', '2025-03-18 06:32:19', '2025-03-18 06:32:19'),
(28, 'puput', '$2y$10$CRgoIWQJRuW534TxPJULcuWYfcRohq4e3zQxUgIDWWqltrfJuqgnu', 'puput@gmail.com', 'mahasiswa', '2025-03-18 00:07:03', '2025-03-18 00:07:03'),
(29, 'HeryAfriadi', '$2y$10$mB6t2Zs70g4etjSrMHdl8.5YbS47tkj8OqoGfMM6rJtl0X1dz6ptG', 'heryafriyadi@gmail.com', 'dosen', '2025-03-19 04:22:07', '2025-03-19 04:22:07'),
(30, 'Suci', '$2y$10$e7oxoTZ7CTNUtbYn9yKz8u6nFmEKAUk8fj910Xzd7K/9RkjZXE6Lq', 'suci@gmail.com', 'mahasiswa', '2025-03-18 21:27:04', '2025-03-18 21:27:04'),
(31, 'melan', '$2y$10$37Pi94SIdhTQs62OwXtmUuhfRp923nrs4QcrZbAlVqEhFvbeXVjCS', 'melan@gmail.com', 'mahasiswa', '2025-03-18 21:34:31', '2025-03-18 21:34:31'),
(32, 'iqbal', '$2y$10$Gujl28EqsN/AE/eXNOWoY.2700JAvTT5FMJ2XJr8y/DXqK2.D1ELK', 'iqbal@gmail.com', 'mahasiswa', '2025-03-19 06:38:27', '2025-03-19 06:38:27'),
(33, 'joni', '$2y$10$4Eu.U/J64k5I9C5j6IkHxOGGMX4JC73gF6HV.sIlHOgFDavEIxAXa', 'joni@gmail.com', 'mahasiswa', '2025-03-19 08:53:45', '2025-03-19 08:53:45');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
