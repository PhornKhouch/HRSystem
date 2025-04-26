-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 26, 2025 at 03:04 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvesalary`
--

DROP TABLE IF EXISTS `approvesalary`;
CREATE TABLE IF NOT EXISTS `approvesalary` (
  `ID` int NOT NULL,
  `Inmonth` int DEFAULT NULL,
  `Inyear` int DEFAULT NULL,
  `SumStaff` int DEFAULT NULL,
  `SumAllowance` decimal(10,2) DEFAULT NULL,
  `SumBonus` decimal(10,2) DEFAULT NULL,
  `SumDedction` decimal(10,2) DEFAULT NULL,
  `SumNetpay` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `careerhistory`
--

DROP TABLE IF EXISTS `careerhistory`;
CREATE TABLE IF NOT EXISTS `careerhistory` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CareerHistoryType` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `EmployeeID` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Company` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Division` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PositionTitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date DEFAULT NULL,
  `Remark` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Increase` decimal(18,2) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `EmployeeID` (`EmployeeID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `careerhistory`
--

INSERT INTO `careerhistory` (`ID`, `CareerHistoryType`, `EmployeeID`, `Company`, `Division`, `PositionTitle`, `Department`, `StartDate`, `EndDate`, `Remark`, `Increase`, `CreatedAt`, `UpdatedAt`) VALUES
(7, 'NEW', 'A02', '', '', 'P01', 'HR', '2025-04-11', NULL, NULL, NULL, '2025-04-26 16:54:06', '2025-04-26 16:54:06'),
(10, 'INCREASE', 'A02', '', '', 'P01', 'HR', '2025-05-01', NULL, NULL, 20.00, '2025-04-26 21:18:40', '2025-04-26 21:18:40'),
(12, 'NEW', 'A01', '', '', 'P01', 'HR', '2025-04-01', NULL, NULL, NULL, '2025-04-26 21:22:04', '2025-04-26 21:22:04'),
(13, 'INCREASE', 'A01', '', '', 'Full Stack Developer', 'Human resource', '2025-05-15', NULL, NULL, 23.00, '2025-04-26 21:24:04', '2025-04-26 21:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `hisgensalary`
--

DROP TABLE IF EXISTS `hisgensalary`;
CREATE TABLE IF NOT EXISTS `hisgensalary` (
  `ID` int NOT NULL,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InMonth` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Inyear` int DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `Allowance` decimal(10,2) DEFAULT NULL,
  `Bonus` decimal(10,2) DEFAULT NULL,
  `Dedction` decimal(10,2) DEFAULT NULL,
  `LeavedTax` decimal(10,2) DEFAULT NULL,
  `Amtobetax` decimal(10,2) DEFAULT NULL,
  `Grosspay` decimal(10,2) DEFAULT NULL,
  `Family` decimal(10,2) DEFAULT NULL,
  `UntaxAm` decimal(10,2) DEFAULT NULL,
  `NSSF` decimal(10,2) DEFAULT NULL,
  `NetSalary` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrcompany`
--

DROP TABLE IF EXISTS `hrcompany`;
CREATE TABLE IF NOT EXISTS `hrcompany` (
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrcompany`
--

INSERT INTO `hrcompany` (`Code`, `Description`, `Status`) VALUES
('CB', 'CLUBCODE', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `hrdepartment`
--

DROP TABLE IF EXISTS `hrdepartment`;
CREATE TABLE IF NOT EXISTS `hrdepartment` (
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrdepartment`
--

INSERT INTO `hrdepartment` (`Code`, `Description`, `Status`) VALUES
('HR', 'Human resource', 'Active'),
('IT', 'information technoloy', 'Active'),
('SALE', 'Maketing', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `hrdivision`
--

DROP TABLE IF EXISTS `hrdivision`;
CREATE TABLE IF NOT EXISTS `hrdivision` (
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrdivision`
--

INSERT INTO `hrdivision` (`Code`, `Description`, `Status`) VALUES
('D01', 'Sale ', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `hreducation`
--

DROP TABLE IF EXISTS `hreducation`;
CREATE TABLE IF NOT EXISTS `hreducation` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `EmpCode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Institution` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Degree` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `FieldOfStudy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `EmpCode` (`EmpCode`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hreducation`
--

INSERT INTO `hreducation` (`Id`, `EmpCode`, `Institution`, `Degree`, `FieldOfStudy`, `StartDate`, `EndDate`) VALUES
(3, 'A01', 'test', 'High School', 'test', '0000-00-00', '2025-04-10');

-- --------------------------------------------------------

--
-- Table structure for table `hrfamily`
--

DROP TABLE IF EXISTS `hrfamily`;
CREATE TABLE IF NOT EXISTS `hrfamily` (
  `EmpCode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `RelationName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `RelationType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Gender` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IsTax` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`EmpCode`,`RelationName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrlevel`
--

DROP TABLE IF EXISTS `hrlevel`;
CREATE TABLE IF NOT EXISTS `hrlevel` (
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrlevel`
--

INSERT INTO `hrlevel` (`Code`, `Description`, `Status`) VALUES
('L1', 'CEO', 'Active'),
('L2', 'CFO', 'Active'),
('L3', 'Manager', 'Active'),
('L4', 'Senior', 'Active'),
('L5', 'Junoir', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `hrposition`
--

DROP TABLE IF EXISTS `hrposition`;
CREATE TABLE IF NOT EXISTS `hrposition` (
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrposition`
--

INSERT INTO `hrposition` (`Code`, `Description`, `Status`) VALUES
('P01', 'Full Stack Developer', 'Active'),
('P02', 'Sale Manager', 'Active'),
('P03', 'Senior Developer', 'Active'),
('P05', 'HR assistant', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `hrstaffdocument`
--

DROP TABLE IF EXISTS `hrstaffdocument`;
CREATE TABLE IF NOT EXISTS `hrstaffdocument` (
  `EmpCode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `DocType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`EmpCode`,`DocType`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrstaffprofile`
--

DROP TABLE IF EXISTS `hrstaffprofile`;
CREATE TABLE IF NOT EXISTS `hrstaffprofile` (
  `EmpCode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `EmpName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Gender` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Dob` date DEFAULT NULL,
  `Position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Level` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Division` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `LineManager` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Hod` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `PayParameter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Telegram` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`EmpCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrstaffprofile`
--

INSERT INTO `hrstaffprofile` (`EmpCode`, `EmpName`, `Gender`, `Dob`, `Position`, `Department`, `Company`, `Level`, `Division`, `StartDate`, `Status`, `Contact`, `Email`, `Address`, `LineManager`, `Hod`, `Photo`, `Salary`, `PayParameter`, `Telegram`) VALUES
('A01', 'Sok Kimheng', 'Male', '2025-04-11', 'P01', 'HR', 'CB', NULL, 'D01', '2025-04-01', 'Active', '', '', '', 'A02', 'A02', 'Uploads/staff_photos/A01_1745677324.png', 123.00, NULL, ''),
('A02', 'Phorn Khouch', 'Male', '2025-04-17', 'P01', 'HR', 'CB', NULL, 'D01', '2025-04-11', 'Active', '', '', '', '', '', 'Uploads/staff_photos/A02_1745661246.png', 400.00, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `lmleavebalance`
--

DROP TABLE IF EXISTS `lmleavebalance`;
CREATE TABLE IF NOT EXISTS `lmleavebalance` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LeaveType` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Balance` decimal(5,2) DEFAULT NULL,
  `Entitle` decimal(5,2) DEFAULT NULL,
  `CurrentBalance` decimal(5,2) DEFAULT NULL,
  `Taken` decimal(5,2) DEFAULT NULL,
  `inmonth` int NOT NULL,
  `inyear` int NOT NULL,
  `created_at` datetime NOT NULL,
  `DefaultBalance` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lmleavebalance`
--

INSERT INTO `lmleavebalance` (`ID`, `EmpCode`, `LeaveType`, `Balance`, `Entitle`, `CurrentBalance`, `Taken`, `inmonth`, `inyear`, `created_at`, `DefaultBalance`) VALUES
(121, 'A01', 'SL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(122, 'A01', 'AL', 18.00, 18.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(123, 'A01', 'SPL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(124, 'A02', 'ML', 90.00, 90.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(125, 'A02', 'SL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(126, 'A02', 'AL', 18.00, 18.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(127, 'A02', 'SPL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(128, 'A03', 'SL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(129, 'A03', 'AL', 18.00, 18.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(130, 'A03', 'SPL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(131, 'A04', 'ML', 90.00, 90.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(132, 'A04', 'SL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(133, 'A04', 'AL', 18.00, 18.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(134, 'A04', 'SPL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(135, 'A05', 'SL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(136, 'A05', 'AL', 18.00, 18.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0),
(137, 'A05', 'SPL', 7.00, 7.00, 0.00, 0.00, 0, 2023, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lmleaverequest`
--

DROP TABLE IF EXISTS `lmleaverequest`;
CREATE TABLE IF NOT EXISTS `lmleaverequest` (
  `ID` int NOT NULL,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LeaveType` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lmleavetype`
--

DROP TABLE IF EXISTS `lmleavetype`;
CREATE TABLE IF NOT EXISTS `lmleavetype` (
  `Code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `LeaveType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IsProbation` tinyint(1) DEFAULT NULL,
  `IsDeduct` tinyint(1) DEFAULT NULL,
  `IsOverBalance` tinyint(1) DEFAULT NULL,
  `default_balance` int NOT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lmleavetype`
--

INSERT INTO `lmleavetype` (`Code`, `LeaveType`, `IsProbation`, `IsDeduct`, `IsOverBalance`, `default_balance`) VALUES
('ML', 'Materity Leave', 0, 0, 0, 90),
('SL', 'Sick Leave', 0, 0, 0, 7),
('AL', 'Annaul Leave', 0, 0, 0, 18),
('SPL', 'Special Leave', 0, 0, 0, 7),
('UL', 'Unpaid Leave', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prallowance`
--

DROP TABLE IF EXISTS `prallowance`;
CREATE TABLE IF NOT EXISTS `prallowance` (
  `ID` int NOT NULL,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AllowanceType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prbenefit`
--

DROP TABLE IF EXISTS `prbenefit`;
CREATE TABLE IF NOT EXISTS `prbenefit` (
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Des` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `IsTax` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prbonus`
--

DROP TABLE IF EXISTS `prbonus`;
CREATE TABLE IF NOT EXISTS `prbonus` (
  `ID` int NOT NULL,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BonusType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prdeduction`
--

DROP TABLE IF EXISTS `prdeduction`;
CREATE TABLE IF NOT EXISTS `prdeduction` (
  `Prdeduction` datetime DEFAULT NULL,
  `ID` int NOT NULL,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DeductType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prleavededuct`
--

DROP TABLE IF EXISTS `prleavededuct`;
CREATE TABLE IF NOT EXISTS `prleavededuct` (
  `ID` int NOT NULL,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LeaveType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `protrate`
--

DROP TABLE IF EXISTS `protrate`;
CREATE TABLE IF NOT EXISTS `protrate` (
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Des` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Rate` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provertime`
--

DROP TABLE IF EXISTS `provertime`;
CREATE TABLE IF NOT EXISTS `provertime` (
  `Provtime` datetime DEFAULT NULL,
  `ID` int NOT NULL,
  `Empcode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OTType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OTDate` date DEFAULT NULL,
  `FromTime` time DEFAULT NULL,
  `ToTime` time DEFAULT NULL,
  `hour` decimal(5,2) DEFAULT NULL,
  `Reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prpaypolicy`
--

DROP TABLE IF EXISTS `prpaypolicy`;
CREATE TABLE IF NOT EXISTS `prpaypolicy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `workday` int DEFAULT NULL,
  `hourperday` float DEFAULT NULL,
  `hourperweek` float DEFAULT NULL,
  `fromdate` date DEFAULT NULL,
  `todate` date DEFAULT NULL,
  `mon` tinyint(1) DEFAULT '0',
  `monhours` float DEFAULT '0',
  `tues` tinyint(1) DEFAULT '0',
  `tueshours` float DEFAULT '0',
  `wed` tinyint(1) DEFAULT '0',
  `wedhours` float DEFAULT '0',
  `thur` tinyint(1) DEFAULT '0',
  `thurhours` float DEFAULT '0',
  `fri` tinyint(1) DEFAULT '0',
  `frihours` float DEFAULT '0',
  `sat` tinyint(1) DEFAULT '0',
  `sathours` float DEFAULT '0',
  `sun` tinyint(1) DEFAULT '0',
  `sunhours` float DEFAULT '0',
  `tue` tinyint DEFAULT '0',
  `tueHours` int DEFAULT '8',
  `thu` tinyint DEFAULT '0',
  `thuHours` int DEFAULT '8',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prpaypolicy`
--

INSERT INTO `prpaypolicy` (`id`, `code`, `description`, `workday`, `hourperday`, `hourperweek`, `fromdate`, `todate`, `mon`, `monhours`, `tues`, `tueshours`, `wed`, `wedhours`, `thur`, `thurhours`, `fri`, `frihours`, `sat`, `sathours`, `sun`, `sunhours`, `tue`, `tueHours`, `thu`, `thuHours`) VALUES
(3, 'AA', 'sdf', 7, 11, 48, '2025-04-17', '2025-04-17', 1, 8, 1, 1, 1, 8, 1, 1, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8),
(8, 'WK', 'Worker parameter ', 6, 8, NULL, '2025-04-25', '2025-04-25', 1, 8, 1, 8, 1, 8, 1, 8, 0, 8, 0, 8, 0, 8, 1, 8, 0, 9);

-- --------------------------------------------------------

--
-- Table structure for table `public_holidays`
--

DROP TABLE IF EXISTS `public_holidays`;
CREATE TABLE IF NOT EXISTS `public_holidays` (
  `id` int NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `holiday_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `public_holidays`
--

INSERT INTO `public_holidays` (`id`, `holiday_name`, `holiday_date`, `description`, `created_at`, `updated_at`) VALUES
(4, 'ចូលឆ្នាំ', '2025-04-12', 'តេស្ត', '2025-04-17 08:00:04', '2025-04-17 08:00:04');

-- --------------------------------------------------------

--
-- Table structure for table `rcmapplicant`
--

DROP TABLE IF EXISTS `rcmapplicant`;
CREATE TABLE IF NOT EXISTS `rcmapplicant` (
  `ID` int NOT NULL,
  `AppId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AppName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Gender` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ApplyPost` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Education` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Pob` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Dob` date DEFAULT NULL,
  `Experience` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Recruited` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rcminterprocess`
--

DROP TABLE IF EXISTS `rcminterprocess`;
CREATE TABLE IF NOT EXISTS `rcminterprocess` (
  `ID` int NOT NULL,
  `AppId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Attachment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `InterDate` date DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rcmletteroffer`
--

DROP TABLE IF EXISTS `rcmletteroffer`;
CREATE TABLE IF NOT EXISTS `rcmletteroffer` (
  `ID` int NOT NULL,
  `AppId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Recruited` tinyint(1) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rcmonboarding`
--

DROP TABLE IF EXISTS `rcmonboarding`;
CREATE TABLE IF NOT EXISTS `rcmonboarding` (
  `ID` int NOT NULL,
  `AppId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Files` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
