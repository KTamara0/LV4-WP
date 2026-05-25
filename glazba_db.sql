-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2026 at 02:27 PM
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
-- Database: `glazba_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL,
  `korisnicko_ime` varchar(50) NOT NULL,
  `lozinka` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id`, `korisnicko_ime`, `lozinka`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'Tamara', '$2y$10$zERIVZEz.v/G6llE9qfpSOntjh0AKafS5S5oCAXOVIrVadeGO7EQK'),
(3, 'Ivica', '$2y$10$vQ1STUyuRxRmpKgCKwrpoeJtfU5/8omOeJBuQZJshQLZCCRayiFlu'),
(4, 'Petra', '$2y$10$UxHLSx0/AHx0/Q9jQrpToOzPzmQzXGZZFxN9OvIbfvVfUi4RiNzMO');

-- --------------------------------------------------------

--
-- Table structure for table `ocjene`
--

CREATE TABLE `ocjene` (
  `id_ocjene` int(11) NOT NULL,
  `id_korisnik` int(11) NOT NULL,
  `id_slika` varchar(50) NOT NULL,
  `ocjena` int(11) DEFAULT NULL CHECK (`ocjena` between 1 and 5),
  `vrijeme_ocjene` timestamp NOT NULL DEFAULT current_timestamp(),
  `komentar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ocjene`
--

INSERT INTO `ocjene` (`id_ocjene`, `id_korisnik`, `id_slika`, `ocjena`, `vrijeme_ocjene`, `komentar`) VALUES
(3, 3, '1', 3, '2026-05-11 21:21:19', NULL),
(5, 3, '3', 4, '2026-05-11 21:34:21', NULL),
(6, 2, '8', 3, '2026-05-22 09:42:03', NULL),
(8, 2, '17', 5, '2026-05-22 09:42:24', NULL),
(9, 2, '1', 4, '2026-05-22 10:17:50', NULL),
(11, 2, '3', 2, '2026-05-22 10:26:03', NULL),
(12, 2, '6', 4, '2026-05-22 10:26:20', NULL),
(14, 4, '6', 3, '2026-05-22 12:26:36', NULL),
(15, 3, '6', 3, '2026-05-22 12:27:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pjesme`
--

CREATE TABLE `pjesme` (
  `id` int(11) NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `izvodjac` varchar(255) NOT NULL,
  `zanr` varchar(100) DEFAULT NULL,
  `bpm` int(11) DEFAULT NULL,
  `godina` int(11) DEFAULT NULL,
  `popularnost` double(3,1) DEFAULT NULL,
  `raspolozenje` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pjesme`
--

INSERT INTO `pjesme` (`id`, `naslov`, `izvodjac`, `zanr`, `bpm`, `godina`, `popularnost`, `raspolozenje`) VALUES
(3, 'Bad Guy', 'Billie Eilish', 'Electropop', 135, 2019, 4.3, 'Dark'),
(4, 'Wake Me Up', 'Avicii', 'EDM', 124, 2013, 4.5, 'Uplifting'),
(5, 'Watermelon Sugar', 'Harry Styles', 'Pop', 95, 2019, 4.2, 'Summer Vibes'),
(6, 'Shape of You', 'Ed Sheeran', 'Pop', 96, 2017, 4.2, 'Happy');

-- --------------------------------------------------------

--
-- Table structure for table `planirani_izleti`
--

CREATE TABLE `planirani_izleti` (
  `id` int(11) NOT NULL,
  `id_korisnik` int(11) NOT NULL,
  `id_pjesma` int(11) NOT NULL,
  `datum_dodavanja` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planirani_izleti`
--

INSERT INTO `planirani_izleti` (`id`, `id_korisnik`, `id_pjesma`, `datum_dodavanja`) VALUES
(7, 3, 3, '2026-05-11 20:41:52'),
(8, 3, 4, '2026-05-11 20:43:53');

-- --------------------------------------------------------

--
-- Table structure for table `slike`
--

CREATE TABLE `slike` (
  `id` int(11) NOT NULL,
  `naslov` varchar(255) DEFAULT NULL,
  `putanja_slike` varchar(255) DEFAULT NULL,
  `tip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slike`
--

INSERT INTO `slike` (`id`, `naslov`, `putanja_slike`, `tip`) VALUES
(1, 'Planinski vrh', 'https://picsum.photos/id/10/300/200', 'url'),
(2, 'Zlatna obala', 'https://picsum.photos/id/100/300/200', 'url'),
(3, 'Stari grad', 'https://picsum.photos/id/1015/300/200', 'url'),
(4, 'Šumski put', 'https://picsum.photos/id/1018/300/200', 'url'),
(5, 'Jezero u jesen', 'https://picsum.photos/id/1019/300/200', 'url'),
(6, 'Polje pšenice', 'https://picsum.photos/id/1027/300/200', 'url'),
(7, 'Plava laguna', 'https://picsum.photos/id/1029/300/200', 'url'),
(8, 'Snježna šuma', 'https://picsum.photos/id/1036/300/200', 'url'),
(9, 'Zelena livada', 'https://picsum.photos/id/1038/300/200', 'url'),
(10, 'Slapovi', 'https://picsum.photos/id/1043/300/200', 'url'),
(11, 'Pustinjski pijesak', 'https://picsum.photos/id/1045/300/200', 'url'),
(12, 'Zvijezdano nebo', 'https://picsum.photos/id/1053/300/200', 'url'),
(13, 'Maglovito jutro', 'https://picsum.photos/id/1059/300/200', 'url'),
(14, 'Gradska svjetla', 'https://picsum.photos/id/1081/300/200', 'url'),
(15, 'Cvjetni vrt', 'https://picsum.photos/id/1082/300/200', 'url'),
(16, 'Crveni most', 'https://picsum.photos/id/1084/300/200', 'url'),
(17, 'Stablo', 'slike/6a024aba89ee0.jpg', 'lokalno');

-- --------------------------------------------------------

--
-- Table structure for table `spremljene_playliste`
--

CREATE TABLE `spremljene_playliste` (
  `id` int(11) NOT NULL,
  `id_korisnik` int(11) NOT NULL,
  `naziv_playliste` varchar(100) NOT NULL,
  `datum_kreiranja` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spremljene_playliste`
--

INSERT INTO `spremljene_playliste` (`id`, `id_korisnik`, `naziv_playliste`, `datum_kreiranja`) VALUES
(3, 2, 'PopList', '2026-05-22 10:34:51');

-- --------------------------------------------------------

--
-- Table structure for table `stavke_playliste`
--

CREATE TABLE `stavke_playliste` (
  `id` int(11) NOT NULL,
  `id_playliste` int(11) NOT NULL,
  `id_pjesme` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stavke_playliste`
--

INSERT INTO `stavke_playliste` (`id`, `id_playliste`, `id_pjesme`) VALUES
(3, 3, 5),
(4, 3, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `korisnicko_ime` (`korisnicko_ime`);

--
-- Indexes for table `ocjene`
--
ALTER TABLE `ocjene`
  ADD PRIMARY KEY (`id_ocjene`),
  ADD UNIQUE KEY `jedinstvena_ocjena` (`id_korisnik`,`id_slika`),
  ADD KEY `id_slika` (`id_slika`);

--
-- Indexes for table `pjesme`
--
ALTER TABLE `pjesme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planirani_izleti`
--
ALTER TABLE `planirani_izleti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_korisnik` (`id_korisnik`),
  ADD KEY `id_pjesma` (`id_pjesma`);

--
-- Indexes for table `slike`
--
ALTER TABLE `slike`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spremljene_playliste`
--
ALTER TABLE `spremljene_playliste`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_korisnik` (`id_korisnik`);

--
-- Indexes for table `stavke_playliste`
--
ALTER TABLE `stavke_playliste`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_playliste` (`id_playliste`),
  ADD KEY `id_pjesme` (`id_pjesme`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ocjene`
--
ALTER TABLE `ocjene`
  MODIFY `id_ocjene` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pjesme`
--
ALTER TABLE `pjesme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `planirani_izleti`
--
ALTER TABLE `planirani_izleti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `slike`
--
ALTER TABLE `slike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `spremljene_playliste`
--
ALTER TABLE `spremljene_playliste`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stavke_playliste`
--
ALTER TABLE `stavke_playliste`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `planirani_izleti`
--
ALTER TABLE `planirani_izleti`
  ADD CONSTRAINT `planirani_izleti_ibfk_1` FOREIGN KEY (`id_korisnik`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `planirani_izleti_ibfk_2` FOREIGN KEY (`id_pjesma`) REFERENCES `pjesme` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spremljene_playliste`
--
ALTER TABLE `spremljene_playliste`
  ADD CONSTRAINT `spremljene_playliste_ibfk_1` FOREIGN KEY (`id_korisnik`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stavke_playliste`
--
ALTER TABLE `stavke_playliste`
  ADD CONSTRAINT `stavke_playliste_ibfk_1` FOREIGN KEY (`id_playliste`) REFERENCES `spremljene_playliste` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stavke_playliste_ibfk_2` FOREIGN KEY (`id_pjesme`) REFERENCES `pjesme` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
