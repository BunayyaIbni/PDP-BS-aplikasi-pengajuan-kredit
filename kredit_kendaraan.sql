-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 05:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kredit_kendaraan`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `id_pengajuan` int(11) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_kredit`
--

CREATE TABLE `pengajuan_kredit` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `jenis_kendaraan` enum('motor','mobil') NOT NULL,
  `harga_kendaraan` decimal(15,2) NOT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `dokumen_ttd` varchar(255) DEFAULT NULL,
  `status` enum('Diproses','Ditolak','Disetujui','Selesai') DEFAULT 'Diproses',
  `status_approval` enum('Menunggu','Disetujui','Ditolak') DEFAULT 'Menunggu',
  `id_manager` int(11) DEFAULT NULL,
  `catatan_manager` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_kredit`
--

INSERT INTO `pengajuan_kredit` (`id`, `nama`, `email`, `telepon`, `jenis_kendaraan`, `harga_kendaraan`, `dokumen`, `dokumen_ttd`, `status`, `status_approval`, `id_manager`, `catatan_manager`, `created_at`, `updated_at`) VALUES
(1, 'Hasyim', 'Hasyim@gmail.com', '08123456789', 'motor', 25000000.00, NULL, NULL, 'Diproses', 'Disetujui', NULL, NULL, '2025-03-05 03:53:30', '2025-03-05 04:07:50'),
(2, 'Irwanto', 'wanto@yahoo.com', '08987654321', 'mobil', 350000000.00, NULL, NULL, 'Diproses', 'Menunggu', NULL, NULL, '2025-03-05 03:53:30', '2025-03-05 04:08:10'),
(3, 'ibni', 'm.ibni82@gmail.com', '085143278725', 'mobil', 1000.00, 'NOTA DINAS GLINTS-1.pdf', NULL, 'Diproses', 'Disetujui', NULL, NULL, '2025-03-05 04:01:02', '2025-03-05 04:01:17');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('pemohon','manager','admin') DEFAULT 'pemohon',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`, `email`, `nama_lengkap`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'admin@example.com', 'Administrator', 'admin', '2025-03-05 03:53:30'),
(2, 'manager', 'manager123', 'manager@example.com', 'Manager Kredit', 'manager', '2025-03-05 03:53:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pengajuan` (`id_pengajuan`),
  ADD KEY `idx_log_pengguna` (`id_pengguna`);

--
-- Indexes for table `pengajuan_kredit`
--
ALTER TABLE `pengajuan_kredit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_manager` (`id_manager`),
  ADD KEY `idx_pengajuan_status` (`status`),
  ADD KEY `idx_pengajuan_email` (`email`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuan_kredit`
--
ALTER TABLE `pengajuan_kredit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `log_aktivitas_ibfk_2` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_kredit` (`id`);

--
-- Constraints for table `pengajuan_kredit`
--
ALTER TABLE `pengajuan_kredit`
  ADD CONSTRAINT `pengajuan_kredit_ibfk_1` FOREIGN KEY (`id_manager`) REFERENCES `pengguna` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
