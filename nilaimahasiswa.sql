-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 26, 2026 at 10:00 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db5a`
--

-- --------------------------------------------------------

--
-- Table structure for table `nilaimahasiswa`
--

CREATE TABLE `nilaimahasiswa` (
  `NIM` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `NamaMahasiswa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `NilaiAkhir` float NOT NULL,
  `NilaiHuruf` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Predikat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `avatar` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilaimahasiswa`
--

INSERT INTO `nilaimahasiswa` (`NIM`, `NamaMahasiswa`, `NilaiAkhir`, `NilaiHuruf`, `Predikat`, `created_date`, `modified_date`, `avatar`) VALUES
('3202416013', 'Fatimah Martin', 85, 'A', 'Sangat Memuaskan', '2026-10-26 15:58:54', '2026-10-26 15:58:54', '64c9522be552d6740502d74ff911aa0f.jpg'),
('3202416027', 'ika', 96, 'A', 'Sangat Memuaskan', '2026-10-26 16:05:13', '2026-10-26 16:05:13', '44c1fe57baa5d7ecb541f67c626fd19d.jpg'),
('3202416035', 'Nabil Fauzal Abrar', 78, 'B', 'Memuaskan', '2026-10-26 15:58:22', '2026-10-26 15:58:22', '048a229e674373e4896817fec12503fb.jpg'),
('3202416124', 'Monica Fatwa Mauliddina', 80, 'B', 'Memuaskan', '2026-10-26 15:44:03', '2026-10-26 15:44:03', 'a55186c867f19c2af2c8fc42f9a80d1d.jpg'),
('3202416125', 'Fajriansyah', 60, 'C', 'Cukup', '2026-10-26 15:56:43', '2026-10-26 15:56:43', 'd047323492b719a6709cfe77cd60e431.jpg'),
('3202416624', 'Aray Prasta Frediansyah', 98, 'A', 'Sangat Memuaskan', '2026-10-26 15:55:49', '2026-10-26 15:55:49', 'cf80e4a0c3b682ac038f97b01645ec71.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nilaimahasiswa`
--
ALTER TABLE `nilaimahasiswa`
  ADD PRIMARY KEY (`NIM`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
