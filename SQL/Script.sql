-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 30, 2022 at 08:51 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mc_rest`
--

-- --------------------------------------------------------

--
-- Table structure for table `comptes`
--

CREATE TABLE `comptes` (
  `uuid` varchar(36) NOT NULL,
  `login` varchar(255) NOT NULL DEFAULT 'root',
  `password` varchar(255) NOT NULL DEFAULT 'root',
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecritures`
--

CREATE TABLE `ecritures` (
  `uuid` varchar(36) NOT NULL,
  `compte_uuid` varchar(36) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `type` enum('C','D') NOT NULL,
  `amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comptes`
--
ALTER TABLE `comptes`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexes for table `ecritures`
--
ALTER TABLE `ecritures`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `fk compte_uuid` (`compte_uuid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ecritures`
--
ALTER TABLE `ecritures`
  ADD CONSTRAINT `fk compte_uuid` FOREIGN KEY (`compte_uuid`) REFERENCES `comptes` (`uuid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
