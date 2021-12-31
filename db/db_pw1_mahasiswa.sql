-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2021 at 12:58 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pw1_mahasiswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int(11) NOT NULL,
  `nama_dosen` varchar(255) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `nidn` varchar(50) NOT NULL,
  `jk` varchar(15) NOT NULL,
  `status` varchar(3) NOT NULL DEFAULT 'on'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `nama_dosen`, `nip`, `nidn`, `jk`, `status`) VALUES
(1, 'Ir. Ating Sudrajat, M.Sc.', '195607011987101001', '0001075603', 'Laki-laki', 'on'),
(2, 'Drs. Slamet Sutjipto, M.T.', '195805011987031001', '0001055805', 'Laki-laki', 'on'),
(3, 'Dr. Maria Fransisca Soetanto, Dipl.Ing., M.T.', '196102111992012001', '0011026104', 'Perempuan', 'on'),
(4, 'Dr. Carolus Bintoro, Dipl.Ing., M.T.', '196206021991021001', '0002066203', 'Laki-laki', 'on'),
(5, 'Ir. Ali Mahmudi, M.Eng.', '195806061990031001', '0006065805', 'Laki-laki', 'on'),
(6, 'Sckolastika Ninien Henny S.R.H, S.ST., M.Eng.', '196009271984032001', '0027096005', 'Perempuan', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL,
  `nrp` int(11) NOT NULL,
  `nama_mahasiswa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_prodi`, `id_dosen`, `nrp`, `nama_mahasiswa`) VALUES
(1, 3, 5, 201131001, 'Abdul Ruslan'),
(2, 1, 1, 201111001, 'Idris Iskandar'),
(3, 4, 5, 201221001, 'Cahaya Amir'),
(4, 4, 4, 201221002, 'Yusuf Ridwan'),
(5, 4, 2, 201221003, 'Akhmad Dwi');

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id_prodi` int(11) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `jenjang` varchar(5) NOT NULL,
  `status` varchar(3) NOT NULL DEFAULT 'on'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id_prodi`, `nama_prodi`, `jenjang`, `status`) VALUES
(1, 'Teknik Informatika', 'S1', 'on'),
(2, 'Desain Komunikasi Visual', 'S1', 'on'),
(3, 'Sistem Informasi', 'S1', 'on'),
(4, 'Manajemen Informatika', 'D3', 'on');

-- --------------------------------------------------------

--
-- Stand-in structure for view `tbl_mahasiswa`
-- (See below for the actual view)
--
CREATE TABLE `tbl_mahasiswa` (
`id_mahasiswa` int(11)
,`nrp` int(11)
,`nama_mahasiswa` varchar(255)
,`nama_prodi` varchar(255)
,`nama_dosen` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`) VALUES
(1, 'Admin', 'admin', 'admin'),
(2, 'M. Noval Hidayat', 'noval', '201111011');

-- --------------------------------------------------------

--
-- Structure for view `tbl_mahasiswa`
--
DROP TABLE IF EXISTS `tbl_mahasiswa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_mahasiswa`  AS SELECT `mahasiswa`.`id_mahasiswa` AS `id_mahasiswa`, `mahasiswa`.`nrp` AS `nrp`, `mahasiswa`.`nama_mahasiswa` AS `nama_mahasiswa`, `prodi`.`nama_prodi` AS `nama_prodi`, `dosen`.`nama_dosen` AS `nama_dosen` FROM ((`mahasiswa` join `prodi` on(`prodi`.`id_prodi` = `mahasiswa`.`id_prodi`)) join `dosen` on(`dosen`.`id_dosen` = `mahasiswa`.`id_dosen`)) ORDER BY `mahasiswa`.`id_mahasiswa` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD KEY `fk_prodi` (`id_prodi`),
  ADD KEY `fk_dosen` (`id_dosen`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id_prodi`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id_dosen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id_prodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `fk_dosen` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`),
  ADD CONSTRAINT `fk_prodi` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
