-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 14, 2025 at 07:25 AM
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
(1, 1, 'Admin Prodi 01', '1740983491_610b3d357cc341640423.jpg'),
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota_kelompok`
--

INSERT INTO `anggota_kelompok` (`id`, `pengajuan_id`, `mahasiswa_id`, `created_at`) VALUES
(10, 5, 2, '2025-03-03 21:13:18'),
(9, 5, 1, '2025-03-03 21:13:18'),
(12, 8, 4, '2025-03-14 14:22:49');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `instansi`
--

INSERT INTO `instansi` (`id_instansi`, `nama_instansi`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Universitas Indonesia', 'Jl. Margonda Raya, Depok, Jawa Barat', '2025-03-01 04:43:21', NULL),
(2, 'Institut Teknologi Bandung', 'Jl. Ganesha No.10, Bandung, Jawa Barat', '2025-03-01 04:43:21', NULL),
(3, 'Universitas Gadjah Mada', 'Bulaksumur, Yogyakarta', '2025-03-01 04:43:21', NULL),
(4, 'Institut Pertanian Bogor', 'Jl. Raya Dramaga, Bogor, Jawa Barat', '2025-03-01 04:43:21', NULL),
(6, 'Bank 9 Jambi', 'Telanaipura, Kota Jambi', '2025-03-04 08:17:46', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nim`, `nama`, `angkatan`, `instansi`, `foto`, `instansi_id`) VALUES
(1, 19, '01020202', 'Andi Lubis', '2020', 'Bank 9 Jambi', '1740803406_e4f3fcff4573215862c2.jpg', NULL),
(2, 21, '00120001', 'Ayek Muhammadd', '2021', '-', '1740975744_ab08f5a0e39ffb4d159d.jpg', NULL),
(4, 23, '123123', 'jawo baru', '2022', 'Bank 9 Jambi', '1741078759_e292dae1cdd0379c0afd.jpeg', NULL),
(5, 22, '70121011', 'lubis batak', '2023', 'institut pertanian bogor', '1741079518_ceacaa4e153bc5d42a9d.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengajuan_magang`
--

INSERT INTO `pengajuan_magang` (`id`, `nama_kelompok`, `ketua_id`, `instansi_id`, `status`, `surat_permohonan`, `surat_pengantar`, `created_at`, `updated_at`) VALUES
(8, 'Kelompok 1', 4, 6, 'disetujui', '1741936969_bb3c4c9eebf2a76c915e.pdf', '1741937025_6782ffab2fe75b43c44c.pdf', '2025-03-14 07:22:49', '2025-03-14 14:23:45');

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(23, 'jawo', '$2y$10$r6knezem4UNfYMswPhi89Of0sfksAFHZhLtBXhQ05apVmFnoQ6Q/W', 'jawo@gmail.com', 'mahasiswa', '2025-03-04 01:24:41', '2025-03-04 01:24:41');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
