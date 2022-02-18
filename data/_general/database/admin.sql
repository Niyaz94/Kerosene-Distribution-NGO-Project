-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 13, 2018 at 07:29 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kt_kerosene`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ADMID` int(11) NOT NULL,
  `ADMUsername` varchar(30) NOT NULL,
  `ADMPassword` varchar(512) NOT NULL,
  `ADMFullname` varchar(150) NOT NULL,
  `ADMEmail` varchar(256) NOT NULL,
  `ADMPhoneNumber` varchar(120) NOT NULL,
  `ADMProfileImg` varchar(256) NOT NULL,
  `ADMCoverImg` varchar(256) NOT NULL,
  `ADMLoginCount` int(11) NOT NULL DEFAULT '0',
  `ADMProfileType` int(1) NOT NULL DEFAULT '0' COMMENT '0 ==> permission , 1==> admin , 2==> developer',
  `ADMProfilePermission` text NOT NULL,
  `ADMLANFORID` int(11) NOT NULL DEFAULT '2',
  `ADMCampID` varchar(255) NOT NULL,
  `ADMRegisterDate` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'active,',
  `ADMDeleted` int(1) NOT NULL DEFAULT '0' COMMENT 'active,'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='active,';

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ADMID`, `ADMUsername`, `ADMPassword`, `ADMFullname`, `ADMEmail`, `ADMPhoneNumber`, `ADMProfileImg`, `ADMCoverImg`, `ADMLoginCount`, `ADMProfileType`, `ADMProfilePermission`, `ADMLANFORID`, `ADMCampID`, `ADMRegisterDate`, `ADMDeleted`) VALUES
(3, '@@niyaz_@_', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'Niyaz', '', '07504569812', '', '', 41, 2, '', 2, '', '2018-11-04 04:45:18', 0),
(7, 'admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'test3', 'nnn@gmail.com', '07686954123', '', '', 6, 1, '', 2, '2,3,4', '2018-11-13 16:31:40', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ADMID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ADMID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
