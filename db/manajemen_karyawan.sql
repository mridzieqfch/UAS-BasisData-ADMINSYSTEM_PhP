-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 25, 2024 at 03:16 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manajemen_karyawan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_divisi`
--

CREATE TABLE `tb_divisi` (
  `ID_Divisi` varchar(50) NOT NULL,
  `Nama_Divisi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_divisi`
--

INSERT INTO `tb_divisi` (`ID_Divisi`, `Nama_Divisi`) VALUES
('D001', 'Administrasi'),
('D002', 'SDM'),
('D003', 'Keuangan'),
('D004', 'K3'),
('D005', 'Teknologi Informasi');

-- --------------------------------------------------------

--
-- Table structure for table `tb_gaji`
--

CREATE TABLE `tb_gaji` (
  `ID_Gaji` varchar(50) NOT NULL,
  `ID_Karyawan` varchar(50) DEFAULT NULL,
  `Gaji_Pokok` decimal(15,2) NOT NULL,
  `Bonus` decimal(15,2) DEFAULT 0.00,
  `Potongan` decimal(15,2) DEFAULT 0.00,
  `Tanggal_Pembayaran` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_gaji`
--

INSERT INTO `tb_gaji` (`ID_Gaji`, `ID_Karyawan`, `Gaji_Pokok`, `Bonus`, `Potongan`, `Tanggal_Pembayaran`) VALUES
('G002', 'K001', 11000000.00, 500000.00, 150000.00, '2024-12-22');

-- --------------------------------------------------------

--
-- Table structure for table `tb_golongan`
--

CREATE TABLE `tb_golongan` (
  `ID_Golongan` varchar(50) NOT NULL,
  `Nama_Golongan` varchar(50) NOT NULL,
  `Tunjangan` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_golongan`
--

INSERT INTO `tb_golongan` (`ID_Golongan`, `Nama_Golongan`, `Tunjangan`) VALUES
('Gol1', 'Golongan I', 2000000.00),
('Gol2', 'Golongan II', 2500000.00),
('Gol3', 'Golongan III', 3000000.00),
('Gol4', 'Golongan IV', 3500000.00),
('Gol5', 'Golongan V', 4000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tb_jabatan`
--

CREATE TABLE `tb_jabatan` (
  `ID_Jabatan` varchar(50) NOT NULL,
  `Nama_Jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jabatan`
--

INSERT INTO `tb_jabatan` (`ID_Jabatan`, `Nama_Jabatan`) VALUES
('J001', 'Manager'),
('J002', 'Supervisor'),
('J003', 'Staff Senior'),
('J004', 'Staff Junior'),
('J005', 'Intern');

-- --------------------------------------------------------

--
-- Table structure for table `tb_karyawan`
--

CREATE TABLE `tb_karyawan` (
  `ID_Karyawan` varchar(50) NOT NULL,
  `Nama_Karyawan` varchar(100) NOT NULL,
  `Tanggal_Lahir` date NOT NULL,
  `Alamat` text DEFAULT NULL,
  `Telepon` varchar(15) DEFAULT NULL,
  `ID_Jabatan` varchar(50) NOT NULL,
  `ID_Divisi` varchar(50) NOT NULL,
  `ID_Golongan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_karyawan`
--

INSERT INTO `tb_karyawan` (`ID_Karyawan`, `Nama_Karyawan`, `Tanggal_Lahir`, `Alamat`, `Telepon`, `ID_Jabatan`, `ID_Divisi`, `ID_Golongan`) VALUES
('K001', 'Muhammaad Ridzieq Fachriansyah', '2004-05-24', 'Pontianak', '089694738979', 'J001', 'D005', 'Gol3');

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`username`, `password`) VALUES
('admin1', '$2y$10$eSg1wKCCg2nIiQk5omovyusJEGafBoR.xCwSW4PXJJLo/NFdT9b/y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_divisi`
--
ALTER TABLE `tb_divisi`
  ADD PRIMARY KEY (`ID_Divisi`);

--
-- Indexes for table `tb_gaji`
--
ALTER TABLE `tb_gaji`
  ADD PRIMARY KEY (`ID_Gaji`),
  ADD KEY `ID_Karyawan` (`ID_Karyawan`);

--
-- Indexes for table `tb_golongan`
--
ALTER TABLE `tb_golongan`
  ADD PRIMARY KEY (`ID_Golongan`);

--
-- Indexes for table `tb_jabatan`
--
ALTER TABLE `tb_jabatan`
  ADD PRIMARY KEY (`ID_Jabatan`);

--
-- Indexes for table `tb_karyawan`
--
ALTER TABLE `tb_karyawan`
  ADD PRIMARY KEY (`ID_Karyawan`),
  ADD KEY `ID_Jabatan` (`ID_Jabatan`),
  ADD KEY `ID_Divisi` (`ID_Divisi`),
  ADD KEY `ID_Golongan` (`ID_Golongan`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_gaji`
--
ALTER TABLE `tb_gaji`
  ADD CONSTRAINT `tb_gaji_ibfk_1` FOREIGN KEY (`ID_Karyawan`) REFERENCES `tb_karyawan` (`ID_Karyawan`);

--
-- Constraints for table `tb_karyawan`
--
ALTER TABLE `tb_karyawan`
  ADD CONSTRAINT `tb_karyawan_ibfk_1` FOREIGN KEY (`ID_Jabatan`) REFERENCES `tb_jabatan` (`ID_Jabatan`),
  ADD CONSTRAINT `tb_karyawan_ibfk_2` FOREIGN KEY (`ID_Divisi`) REFERENCES `tb_divisi` (`ID_Divisi`),
  ADD CONSTRAINT `tb_karyawan_ibfk_3` FOREIGN KEY (`ID_Golongan`) REFERENCES `tb_golongan` (`ID_Golongan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
