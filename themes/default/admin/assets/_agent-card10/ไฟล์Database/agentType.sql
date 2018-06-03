-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2018 at 03:44 PM
-- Server version: 5.6.38
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cp782165_apps`
--

-- --------------------------------------------------------

--
-- Table structure for table `agentType`
--

CREATE TABLE `agentType` (
  `typeID` int(11) NOT NULL,
  `nameType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'name',
  `imgTypeUrl` varchar(450) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createDate` date DEFAULT NULL,
  `createTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `agentType`
--

INSERT INTO `agentType` (`typeID`, `nameType`, `imgTypeUrl`, `createDate`, `createTime`) VALUES
(1, 'ตัวแทนย่อย', 'assets/images/type/agent_logo.png', NULL, NULL),
(2, 'ตัวแทนหลัก', 'assets/images/type/member_logo.png', NULL, NULL),
(3, 'VIP', 'assets/images/type/VIPv3_logo.png', NULL, NULL),
(4, 'Super Platinum', 'assets/images/type/super_platinum_logo.png', NULL, NULL),
(5, 'VVIP', 'assets/images/type/VVIP_logo.png', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agentType`
--
ALTER TABLE `agentType`
  ADD PRIMARY KEY (`typeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agentType`
--
ALTER TABLE `agentType`
  MODIFY `typeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
