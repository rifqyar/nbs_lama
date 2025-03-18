-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 02, 2012 at 03:38 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `filestorage`
--
CREATE DATABASE `filestorage` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `filestorage`;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `file_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `filepath` varchar(500) DEFAULT NULL,
  `counter` int(255) DEFAULT NULL,
  `owner_id` bigint(255) DEFAULT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`file_id`, `filename`, `filepath`, `counter`, `owner_id`) VALUES
(1, 'Win 2 Thropies in 6 Month', 'file/', 5, 1),
(4, 'frenda - 0 - Array', 'coverm.jpg', 36, 1),
(5, 'frenda - 1 - Array', 'cover.jpg', 14, 1),
(7, 'jorge - cherry_blossoms_and_mount_1024x768.jpg', 'cherry_blossoms_and_mount_1024x768.jpg', 12, 2),
(8, 'jorge - Gmail - Your Citilink Reservation.pdf', 'Gmail - Your Citilink Reservation.pdf', 9, 2),
(9, 'jorge - goodreads4.jpg', 'goodreads4.jpg', 5, 2),
(10, 'jorge - inssider_APDermaga08.png', 'inssider_APDermaga08.png', 5, 2),
(11, 'jorge - wisuda103.jpg', 'jorge_wisuda103.jpg', 13, 2),
(12, 'frenda - ping_APWorkshop.png', 'frenda_ping_APWorkshop.png', 12, 1),
(13, 'frenda - selecta_malang1.jpg', 'frenda_selecta_malang1.jpg', 7, 1),
(14, 'frenda - inssider_APCFS.png', 'frenda_inssider_APCFS.png', 8, 1),
(15, 'frenda - ping_APkananCFS.png', 'frenda_ping_APkananCFS.png', 10, 1),
(16, 'frenda - voting.txt', 'frenda_voting.txt', 5, 1),
(17, 'frenda - rabu.jpg', 'frenda_rabu.jpg', 5, 1),
(18, 'frenda - usecase.txt', 'frenda_usecase.txt', 9, 1),
(19, 'frenda - inssider_APDermaga08.png', 'frenda_inssider_APDermaga08.png', 5, 1),
(22, 'frenda - ping_APkanankali.png', 'frenda_ping_APkanankali.png', 1, 1),
(23, 'frenda - inssider_APkanankali.png', 'frenda_inssider_APkanankali.png', 1, 1),
(25, 'frenda - goodreads2.jpg', 'frenda_goodreads2.jpg', 2, 1),
(26, 'frenda - ping_APkanankali.png', 'frenda_ping_APkanankali.png', 1, 1),
(27, 'frenda - voting.txt', 'frenda_voting.txt', 4, 1),
(28, 'dani - CV_frenda.doc', 'dani_CV_frenda.doc', 29, 3),
(29, 'dani - Foto_Frenda.jpg', 'dani_Foto_Frenda.jpg', 17, 3),
(30, 'dani - suratsehat.docx', 'dani_suratsehat.docx', 9, 3),
(31, 'jorge - [www.indowebster.com]-Crystal_Kay_-_Koi_ni_Ochitara.mp3', 'jorge_[www.indowebster.com]-Crystal_Kay_-_Koi_ni_Ochitara.mp3', 18, 2),
(32, 'raden - share.gif', 'raden_share.gif', 4, 4),
(33, 'jorge - CERIA I DTJK.pdf', 'jorge_CERIA I DTJK.pdf', 8, 2),
(34, 'dani - ARUS BARANG MARET.xlsx', 'dani_ARUS BARANG MARET.xlsx', 2, 3),
(35, 'dani - yahoo-weather-api.jpg', 'dani_yahoo-weather-api.jpg', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `user_id` bigint(255) NOT NULL,
  `file_id` bigint(255) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `share`
--

INSERT INTO `share` (`user_id`, `file_id`) VALUES
(2, 5),
(1, 9),
(3, 4),
(4, 4),
(1, 9),
(4, 9),
(1, 28),
(2, 28),
(4, 28),
(3, 31),
(4, 31),
(1, 32),
(3, 32),
(3, 31),
(4, 31),
(2, 35),
(4, 35);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `username`) VALUES
(1, 'Frenda Rangga', 'frenda', 'frenda'),
(2, 'Jorge Lorenzo', 'jorge', 'jorge'),
(3, 'Dani Pedrosa', 'dani', 'dani'),
(4, 'Raden Patah', 'raden', 'raden');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `share`
--
ALTER TABLE `share`
  ADD CONSTRAINT `share_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `share_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `file` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
