-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2025 at 09:22 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `planify`
--

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `prioritas` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `deadline` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `username`, `judul`, `kategori`, `prioritas`, `status`, `deadline`, `created_at`) VALUES
(3, 'null', 'web prak', 'Kuliah', 'Sedang', 'Selesai', '2025-06-12', '2025-06-09 14:53:50'),
(8, 'null', 'makan pagi dan malam', 'Pribadi', 'Tinggi', 'Selesai', '2025-06-11', '2025-06-10 06:17:44'),
(10, 'banyu', 'makan sore,malam dan siang', 'Pribadi', 'Rendah', 'Selesai', '2025-06-20', '2025-06-10 06:26:46'),
(13, 'husnul', 'badmintonan', 'Pribadi', 'Sedang', 'Selesai', '2025-06-13', '2025-06-10 08:13:57'),
(15, 'null', 'mengerjakan db', 'Kerja', 'Tinggi', 'Selesai', '2025-06-20', '2025-06-11 04:05:12'),
(16, 'null', 'mengerjakan uap ml', 'Kuliah', 'Tinggi', 'Selesai', '2025-06-14', '2025-06-11 06:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@planify.com', '$2y$10$O4tpgG2xsH0yigv5E/eYvORAcfUjpFWRVE/cpdl2lnocWGIMN8UOi', 'admin'),
(6, 'husnul', 'langsek@213', '$2y$10$1j3o16H162tQy8ieFL7DJe/kJPNN5jydgrwLf7IB5g61KKQQTxAP2', 'user'),
(7, 'null', 'null@2012', '$2y$10$o.G6eKAWm1f.69IsACmHiOoahOg0vvD9jEMxiXfOZCCrZ9ywD/oa2', 'user'),
(9, 'banyu', 'nadhif@2020', '$2y$10$LPzemaYytJPZbVkSRnzjN.tfygTd2TpRd.d7KQOLLb2XTu13cPo5q', 'user'),
(14, 'tami', 'tami@21', '$2y$10$5J5gMPc7buoQcz4P8ah6KuJ/jmhFrocBSDBilCPaMTbAnq0CAweIq', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
