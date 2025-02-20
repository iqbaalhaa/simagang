-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 16 Feb 2025 pada 10.30
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
-- Struktur dari tabel `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','mahasiswa','pembimbing') NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur dari tabel `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `semester` int(2) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  PRIMARY KEY (`id_mahasiswa`),
  FOREIGN KEY (`id_user`) REFERENCES `tbl_users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur dari tabel `tbl_pembimbing`
--

CREATE TABLE `tbl_pembimbing` (
  `id_pembimbing` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `bidang_keahlian` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_pembimbing`),
  FOREIGN KEY (`id_user`) REFERENCES `tbl_users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur dari tabel `tbl_instansi`
--

CREATE TABLE `tbl_instansi` (
  `id_instansi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_instansi` varchar(255) NOT NULL,
  `alamat_instansi` text NOT NULL,
  `telp_instansi` varchar(15) DEFAULT NULL,
  `email_instansi` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `bidang_usaha` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_instansi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur dari tabel `tbl_pengajuan`
--

CREATE TABLE `tbl_pengajuan` (
  `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int(11) NOT NULL,
  `id_instansi` int(11) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `status_pengajuan` enum('pending','diterima','ditolak') NOT NULL DEFAULT 'pending',
  `surat_pengajuan` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id_pengajuan`),
  FOREIGN KEY (`id_mahasiswa`) REFERENCES `tbl_mahasiswa` (`id_mahasiswa`),
  FOREIGN KEY (`id_instansi`) REFERENCES `tbl_instansi` (`id_instansi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur dari tabel `tbl_bimbingan`
--

CREATE TABLE `tbl_bimbingan` (
  `id_bimbingan` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int(11) NOT NULL,
  `id_pembimbing` int(11) NOT NULL,
  `tgl_bimbingan` datetime NOT NULL,
  `catatan` text DEFAULT NULL,
  `status_bimbingan` enum('pending','selesai') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id_bimbingan`),
  FOREIGN KEY (`id_pengajuan`) REFERENCES `tbl_pengajuan` (`id_pengajuan`),
  FOREIGN KEY (`id_pembimbing`) REFERENCES `tbl_pembimbing` (`id_pembimbing`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Insert data admin default
--

INSERT INTO `tbl_users` (`username`, `password`, `role`, `nama`, `email`, `created_at`, `updated_at`) 
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'admin',
    'Administrator',
    'admin@example.com',
    NOW(),
    NOW()
);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
