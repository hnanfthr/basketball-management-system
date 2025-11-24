-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql212.infinityfree.com
-- Generation Time: Nov 24, 2025 at 07:35 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40429991_basket48`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `tanggal_latihan` date NOT NULL,
  `status_kehadiran` enum('Hadir','Izin','Sakit','Alfa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_anggota`, `tanggal_latihan`, `status_kehadiran`) VALUES
(1, 3, '2025-11-12', 'Hadir'),
(2, 6, '2025-11-12', 'Izin'),
(3, 7, '2025-11-12', 'Hadir'),
(4, 8, '2025-11-12', 'Hadir'),
(5, 9, '2025-11-12', 'Izin'),
(6, 5, '2025-11-12', 'Hadir'),
(7, 1, '2025-11-12', 'Izin'),
(8, 25, '2025-11-12', 'Hadir'),
(9, 2, '2025-11-12', 'Izin'),
(10, 4, '2025-11-12', 'Hadir'),
(11, 10, '2025-11-12', 'Izin'),
(12, 11, '2025-11-12', 'Hadir'),
(13, 12, '2025-11-12', 'Izin'),
(14, 16, '2025-11-12', 'Hadir'),
(15, 14, '2025-11-12', 'Izin'),
(16, 13, '2025-11-12', 'Hadir'),
(17, 15, '2025-11-12', 'Izin'),
(18, 17, '2025-11-12', 'Hadir'),
(19, 18, '2025-11-12', 'Izin'),
(20, 19, '2025-11-12', 'Hadir'),
(21, 20, '2025-11-12', 'Izin'),
(22, 21, '2025-11-12', 'Hadir'),
(23, 23, '2025-11-12', 'Izin'),
(24, 24, '2025-11-12', 'Hadir'),
(25, 26, '2025-11-12', 'Izin'),
(26, 29, '2025-11-12', 'Hadir'),
(27, 28, '2025-11-12', 'Izin'),
(28, 27, '2025-11-12', 'Hadir'),
(29, 22, '2025-11-12', 'Izin'),
(30, 3, '2025-11-19', 'Alfa'),
(31, 6, '2025-11-19', 'Hadir'),
(32, 7, '2025-11-19', 'Alfa'),
(33, 8, '2025-11-19', 'Hadir'),
(34, 9, '2025-11-19', 'Alfa'),
(35, 5, '2025-11-19', 'Hadir'),
(36, 1, '2025-11-19', 'Alfa'),
(37, 25, '2025-11-19', 'Alfa'),
(38, 2, '2025-11-19', 'Hadir'),
(39, 4, '2025-11-19', 'Alfa'),
(40, 10, '2025-11-19', 'Hadir'),
(41, 11, '2025-11-19', 'Hadir'),
(42, 12, '2025-11-19', 'Hadir'),
(43, 16, '2025-11-19', 'Hadir'),
(44, 14, '2025-11-19', 'Hadir'),
(45, 13, '2025-11-19', 'Alfa'),
(46, 15, '2025-11-19', 'Hadir'),
(47, 17, '2025-11-19', 'Hadir'),
(48, 18, '2025-11-19', 'Hadir'),
(49, 19, '2025-11-19', 'Alfa'),
(50, 20, '2025-11-19', 'Alfa'),
(51, 21, '2025-11-19', 'Alfa'),
(52, 23, '2025-11-19', 'Alfa'),
(53, 24, '2025-11-19', 'Alfa'),
(54, 26, '2025-11-19', 'Alfa'),
(55, 29, '2025-11-19', 'Alfa'),
(56, 28, '2025-11-19', 'Alfa'),
(57, 27, '2025-11-19', 'Alfa'),
(58, 22, '2025-11-19', 'Alfa'),
(130, 31, '2025-11-19', 'Hadir'),
(147, 3, '2025-11-22', 'Alfa'),
(148, 6, '2025-11-22', 'Hadir'),
(149, 7, '2025-11-22', 'Alfa'),
(150, 8, '2025-11-22', 'Izin'),
(151, 9, '2025-11-22', 'Alfa'),
(152, 5, '2025-11-22', 'Alfa'),
(153, 1, '2025-11-22', 'Hadir'),
(154, 25, '2025-11-22', 'Alfa'),
(155, 2, '2025-11-22', 'Alfa'),
(156, 4, '2025-11-22', 'Alfa'),
(157, 10, '2025-11-22', 'Hadir'),
(158, 11, '2025-11-22', 'Izin'),
(159, 12, '2025-11-22', 'Hadir'),
(160, 31, '2025-11-22', 'Alfa'),
(161, 16, '2025-11-22', 'Hadir'),
(162, 14, '2025-11-22', 'Hadir'),
(163, 13, '2025-11-22', 'Hadir'),
(164, 15, '2025-11-22', 'Hadir'),
(165, 17, '2025-11-22', 'Hadir'),
(166, 18, '2025-11-22', 'Hadir'),
(167, 19, '2025-11-22', 'Alfa'),
(168, 20, '2025-11-22', 'Alfa'),
(169, 21, '2025-11-22', 'Alfa'),
(170, 23, '2025-11-22', 'Alfa'),
(171, 24, '2025-11-22', 'Alfa'),
(172, 26, '2025-11-22', 'Alfa'),
(173, 29, '2025-11-22', 'Alfa'),
(174, 28, '2025-11-22', 'Alfa'),
(175, 27, '2025-11-22', 'Alfa'),
(176, 22, '2025-11-22', 'Alfa');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password_hash`, `nama_lengkap`) VALUES
(2, 'admin', '$2y$10$dHdVzMTh6zQGdjBMM0ZfFuGbNHKpekQi7p1.Bet3F4t9tJRZp9VNW', 'Admin Utama'),
(3, 'admin2', '$2y$10$rH9JE75B8QfJZ64bZUTNOOVM1sNEp0zpgTnqDrcdZ3edljDn2tJLq', 'admin2');

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `posisi_main` enum('PG','SG','SF','PF','C') DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama_lengkap`, `kelas`, `posisi_main`, `foto_profil`, `email`, `password_hash`) VALUES
(1, 'Adelia kusuma wardhani', '10 DKV 1', NULL, NULL, 'adelia.kusuma.wardhani@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(2, 'Zahra Alifna Endrieka', '10 DKV 1', NULL, NULL, 'zahra.alifna.endrieka@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(3, 'Arika Latifa Azzahra', '10 AKL 1', NULL, NULL, 'arika.latifa.azzahra@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(4, 'Annisa Salwabillah', '10 PF', NULL, 'profil_4_1763529058.jpeg', 'annisa.salwabillah@basket48.local', '$2y$10$pWyFW4h8PtqMOOtbAG4.H.xcArc5CPvlMtQif2Aaf3QbtJPeWPOpi'),
(5, 'Princella Khairan Fikri', '10 BD', NULL, NULL, 'princella.khairan.fikri@basket48.local', '$2y$10$bd2YwvDoha/RvzDa873khuYKmUbmcLZsBxPWxyO7joX8BobP2c/Te'),
(6, 'Akbar Firmansyah', '10 BD', NULL, NULL, 'akbar.firmansyah@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(7, 'Gabriel Fadhil.S', '10 BD', NULL, NULL, 'gabriel.fadhils@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(8, 'George Darrel Arjuna', '10 BD', 'PG', NULL, 'george.darrel.arjuna@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(9, 'Katrina Jelitany Nadeak', '10 BD', NULL, NULL, 'katrina.jelitany.nadeak@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(10, 'Celsy Ayu Riandini', '11 AK 1', NULL, NULL, 'celsy.ayu.riandin@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(11, 'Friska Nainggolan', '11 AK 1', NULL, NULL, 'friska.nainggolan@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(12, 'Raisya Aulia Avriani', '11 AK 1', NULL, NULL, 'raisya.aulia.avriani@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(13, 'Dira Rusdyana', '11 MP', NULL, NULL, 'dira.rusdyana@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(14, 'Debora Angelita', '11 MP', NULL, NULL, 'debora.angelita@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(15, 'Yunika Fakis', '11 MP', NULL, NULL, 'yunika.fakis@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(16, 'Izzy Bayu Langit Merah Salya', '11 DKV 1', NULL, NULL, 'izzy.bayu.langit.merah.salya@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(17, 'Radhitya Galih Widcaksono', '11 PF', NULL, NULL, 'radhitya.galih.widcaksono@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(18, 'Yasmin Kahla', '11 PF', NULL, NULL, 'yasmin.kahla@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(19, 'Nadya Shafwah Ramadhani', '12 AK 2', NULL, NULL, 'nadya.shafwah.ramadhani@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(20, 'Novita Indah Pramudita', '12 AK 2', NULL, NULL, 'novita.indah.pramudita@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(21, 'Elang Bimo', '12 BR', NULL, NULL, 'elang.bimo@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(22, 'M. Bintang Atalansyah P. S', '12 MP', NULL, NULL, 'm.bintang.atalansyah.p.s@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(23, 'Abyan Syarif', '12 DKV 1', NULL, NULL, 'abyan.syarif@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(24, 'Alfito Kafkha Bagaskara', '12 DKV 1', NULL, NULL, 'alfito.kafkha.bagaskara@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(25, 'Fahri Muhammad Akbar', '10 DKV 1', NULL, NULL, 'fahri.muhammad.akbar@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(26, 'Fasya Rayhan', '12 DKV 1', NULL, NULL, 'fasya.rayhan@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(27, 'Rajwa Mirachel', '12 DKV 2', NULL, NULL, 'rajwa.mirachel@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(28, 'Octa Liga Herliana', '12 DKV 2', NULL, NULL, 'octa.liga.herliana@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(29, 'Megajati Anggraini', '12 DKV 2', NULL, NULL, 'megajati.anggraini@basket48.local', '$2a$12$XYKtJ6JOzVpl7zXo9Qt5wuqvV7Q2vSWJy2H5lNMrg5IPz7ZMLvos6'),
(31, 'Rara Suryaningsih', '11 AKL 1', NULL, NULL, 'r4r4suryaningsih@gmail.com', '$2y$10$BZPopZBYOZpPNSE2qkM8UuGMrt4vKquW0PY1dKxchOk4SQG71XOMy'),
(32, 'demo account', 'demo account', NULL, NULL, 'tamu@demo.com', '$2y$10$N7E/JiNgFEK.NcY1fPFJw.2bsp0vEBPJ8A6Fq2dwsRyoAfvVJZmpK');

-- --------------------------------------------------------

--
-- Table structure for table `denda`
--

CREATE TABLE `denda` (
  `id_denda` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `status` enum('Belum Lunas','Lunas') DEFAULT 'Belum Lunas',
  `tanggal_dibuat` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id_foto` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_admin_uploader` int(11) NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id_foto`, `nama_file`, `judul`, `keterangan`, `id_admin_uploader`, `tanggal_upload`) VALUES
(1, 'foto_691c96806f9eb1763481216.jpeg', 'PALAPA ROSTER', '', 3, '2025-11-18 15:53:36'),
(3, 'foto_691c980919f611763481609.jpeg', 'PALAPA GIRL\'S', '', 3, '2025-11-18 16:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_latihan`
--

CREATE TABLE `jadwal_latihan` (
  `id_jadwal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `waktu_selesai` time NOT NULL DEFAULT '00:00:00',
  `lokasi` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_latihan`
--

INSERT INTO `jadwal_latihan` (`id_jadwal`, `tanggal`, `waktu`, `waktu_selesai`, `lokasi`, `keterangan`) VALUES
(2, '2025-11-19', '15:30:00', '00:00:00', 'Latihan (Lapangan SMK 48)', 'latihan'),
(3, '2025-11-22', '14:00:00', '00:00:00', 'Latihan (GOR Pondok Bambu)', 'Latihan'),
(4, '2025-11-26', '15:30:00', '17:00:00', 'Latihan (Lapangan SMK 48)', 'Latihan'),
(5, '2025-11-29', '06:00:00', '08:00:00', 'Sparing (Matraman)', 'Sparing vs GRBC'),
(6, '2025-12-03', '15:30:00', '17:00:00', 'Latihan (Lapangan SMK 48)', 'Latihan (Coach Fadel)');

-- --------------------------------------------------------

--
-- Table structure for table `kas_latihan`
--

CREATE TABLE `kas_latihan` (
  `id_kas` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `status` enum('Lunas','Belum') DEFAULT 'Belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kas_latihan`
--

INSERT INTO `kas_latihan` (`id_kas`, `id_anggota`, `id_jadwal`, `status`) VALUES
(1, 5, 2, 'Lunas'),
(2, 15, 2, 'Lunas'),
(3, 2, 2, 'Lunas'),
(4, 6, 2, 'Lunas'),
(5, 14, 2, 'Lunas'),
(6, 18, 2, 'Lunas'),
(7, 16, 2, 'Lunas'),
(8, 10, 2, 'Lunas'),
(9, 11, 2, 'Lunas'),
(10, 12, 2, 'Lunas'),
(11, 1, 3, 'Lunas'),
(12, 10, 3, 'Lunas'),
(13, 12, 3, 'Lunas'),
(14, 16, 3, 'Lunas'),
(15, 14, 3, 'Lunas'),
(16, 13, 3, 'Lunas'),
(17, 17, 3, 'Lunas'),
(18, 18, 3, 'Lunas'),
(19, 15, 3, 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_pending`
--

CREATE TABLE `pendaftaran_pending` (
  `id_pendaftaran` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id_pengurus` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `urutan` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `struktur_organisasi`
--

INSERT INTO `struktur_organisasi` (`id_pengurus`, `nama`, `jabatan`, `foto`, `urutan`) VALUES
(1, 'Sujud Widhiatmoko', 'Coach', 'struktur_69196af7485631763273463.jpeg', 1),
(2, 'Fadhel Ahmad Rantisi', 'Assistant Coach', 'struktur_69196b7d41cda1763273597.jpeg', 2),
(4, 'Hannan Fathur Hendrawan', 'Assistant Coach', 'struktur_69196e67a3d881763274343.jpeg', 3),
(5, 'Izzy Bayu Langit Merah Salya', 'Ketua', 'struktur_692072519ccff1763734097.jpeg', 4),
(8, 'Dira Rusdyana', 'Wakil', 'struktur_6920730d37fac1763734285.jpeg', 5),
(10, 'Celsy Ayu Riandini', 'Sekretaris ', 'struktur_692073f1e3ba21763734513.jpeg', 6),
(11, 'Raisya Aulia Avriani', 'Bendahara ', 'struktur_6920742c9424d1763734572.jpeg', 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD UNIQUE KEY `absensi_unik` (`id_anggota`,`tanggal_latihan`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id_denda`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_admin_uploader` (`id_admin_uploader`);

--
-- Indexes for table `jadwal_latihan`
--
ALTER TABLE `jadwal_latihan`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `kas_latihan`
--
ALTER TABLE `kas_latihan`
  ADD PRIMARY KEY (`id_kas`),
  ADD UNIQUE KEY `pembayaran_sesi_unik` (`id_anggota`,`id_jadwal`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `pendaftaran_pending`
--
ALTER TABLE `pendaftaran_pending`
  ADD PRIMARY KEY (`id_pendaftaran`);

--
-- Indexes for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD PRIMARY KEY (`id_pengurus`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `denda`
--
ALTER TABLE `denda`
  MODIFY `id_denda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jadwal_latihan`
--
ALTER TABLE `jadwal_latihan`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kas_latihan`
--
ALTER TABLE `kas_latihan`
  MODIFY `id_kas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pendaftaran_pending`
--
ALTER TABLE `pendaftaran_pending`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id_pengurus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `galeri`
--
ALTER TABLE `galeri`
  ADD CONSTRAINT `galeri_ibfk_1` FOREIGN KEY (`id_admin_uploader`) REFERENCES `admin` (`id_admin`);

--
-- Constraints for table `kas_latihan`
--
ALTER TABLE `kas_latihan`
  ADD CONSTRAINT `kas_latihan_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE,
  ADD CONSTRAINT `kas_latihan_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_latihan` (`id_jadwal`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
