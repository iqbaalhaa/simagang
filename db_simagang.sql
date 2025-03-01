-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 01 Mar 2025 pada 04.54
-- Versi server: 9.1.0
-- Versi PHP: 8.2.26

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
(1, 1, 'Admin Prodi 01', '1740771141_8b19b9867e7fc04f0ef3.webp'),
(2, 14, 'Admin Prodi 02', '1740781261_83a2a957dd5f1af55116.jpg');

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
  PRIMARY KEY (`id_dosen`),
  UNIQUE KEY `user_id` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `dosen_pembimbing`
--

INSERT INTO `dosen_pembimbing` (`id_dosen`, `id_user`, `nama`, `nidn`) VALUES
(1, 17, 'albet, S.Kom', 12414141);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `instansi`
--

INSERT INTO `instansi` (`id_instansi`, `nama_instansi`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Universitas Indonesia', 'Jl. Margonda Raya, Depok, Jawa Barat', '2025-03-01 04:43:21', NULL),
(2, 'Institut Teknologi Bandung', 'Jl. Ganesha No.10, Bandung, Jawa Barat', '2025-03-01 04:43:21', NULL),
(3, 'Universitas Gadjah Mada', 'Bulaksumur, Yogyakarta', '2025-03-01 04:43:21', NULL),
(4, 'Institut Pertanian Bogor', 'Jl. Raya Dramaga, Bogor, Jawa Barat', '2025-03-01 04:43:21', NULL),
(5, 'Universitas Diponegoro', 'Jl. Prof. Soedarto, Tembalang, Semarang', '2025-03-01 04:43:21', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id_mahasiswa` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `nim` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `angkatan` year DEFAULT NULL,
  `instansi` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `instansi_id` int DEFAULT NULL,
  PRIMARY KEY (`id_mahasiswa`),
  UNIQUE KEY `user_id` (`id_user`),
  UNIQUE KEY `nim` (`nim`),
  KEY `instansi_id` (`instansi_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nim`, `nama`, `angkatan`, `instansi`, `foto`, `instansi_id`) VALUES
(1, 19, '01020202', 'Andi Lubis', '2020', 'Bank 9 Jambi', '1740803406_e4f3fcff4573215862c2.jpg', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_magang`
--

DROP TABLE IF EXISTS `pengajuan_magang`;
CREATE TABLE IF NOT EXISTS `pengajuan_magang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` int NOT NULL,
  `instansi_id` int NOT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `tgl_pengajuan` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mahasiswa_id` (`mahasiswa_id`),
  KEY `instansi_id` (`instansi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$osatC57vXNvpvcpcL0Aa6.8mGFwLqwiTeL1RK8mUtZ7QpMEiy3Sza', 'admin@gmail.com', 'admin', '2025-02-24 04:44:15', '2025-02-28 06:48:44'),
(2, 'mahasiswa', '$2y$10$/.Avqs3kurPIjskUAbqKCuYTyePKyO83tBSB88tkBM2yvZjwGp4EO', 'mahasiswa@gmail.com', 'mahasiswa', '2025-02-24 18:57:36', '2025-02-28 06:48:44'),
(3, 'dosen', '$2y$10$BCFDyuXYPU3HQnNprJzCp.VSKX8Q/tjejlJyk2lfyUPwOtwLdv7d2', 'dosen@gmail.com', 'dosen', '2025-02-24 19:11:09', '2025-02-28 06:48:44'),
(13, 'iqbaalhaa', '$2y$10$LAuj85MLKGSn0N8wv1Nh4e0k2Yn3vNsOfWPeWo2eo6K/PqgARJ5Fm', 'iqbalhaanafi@gmail.com', 'mahasiswa', '2025-02-28 01:34:00', '2025-02-28 01:34:00'),
(14, 'admin02', '$2y$10$tfI.fuj3vr2QxFRAybQoiOwmDke291tHvPvoWR72AdDZ9qZO3YsCm', 'admin02@gmail.com', 'admin', '2025-02-28 22:21:01', '2025-02-28 22:21:01'),
(19, 'andi11', '$2y$10$FJMy7taidJ80DlXao5cLouLNGoCY4e4z3ymBYNq2vE8balZbpafN.', 'andi@gmail.com', 'mahasiswa', '2025-03-01 04:30:06', '2025-03-01 04:30:06'),
(17, 'albet12', '$2y$10$dPOfTow3NVSHd4u2Bwsh3.fZtiVRDU23DXtKKfRDwBS0cxpHSEdn.', 'albet@gmail.com', 'dosen', '2025-02-28 22:47:32', '2025-02-28 22:47:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
