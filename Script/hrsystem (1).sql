-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 11, 2025 at 03:14 PM
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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `careerhistory`
--

INSERT INTO `careerhistory` (`ID`, `CareerHistoryType`, `EmployeeID`, `Company`, `Division`, `PositionTitle`, `Department`, `StartDate`, `EndDate`, `Remark`, `Increase`, `CreatedAt`, `UpdatedAt`) VALUES
(15, 'NEW', 'A01', '', '', 'P03', 'IT', '2025-04-01', NULL, NULL, NULL, '2025-04-27 21:17:58', '2025-04-27 21:17:58'),
(16, 'NEW', 'A02', '', '', 'P05', 'HR', '2025-04-01', NULL, NULL, NULL, '2025-04-27 21:19:41', '2025-04-27 21:19:41'),
(19, 'NEW', 'A03', '', '', 'P02', 'HR', '2025-05-01', NULL, NULL, NULL, '2025-05-03 21:42:08', '2025-05-03 21:42:08');

-- --------------------------------------------------------

--
-- Table structure for table `hisgensalary`
--

DROP TABLE IF EXISTS `hisgensalary`;
CREATE TABLE IF NOT EXISTS `hisgensalary` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InMonth` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Inyear` int DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `Allowance` decimal(10,2) DEFAULT NULL,
  `OT` decimal(10,2) NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hisgensalary`
--

INSERT INTO `hisgensalary` (`ID`, `EmpCode`, `InMonth`, `Inyear`, `Salary`, `Allowance`, `OT`, `Bonus`, `Dedction`, `LeavedTax`, `Amtobetax`, `Grosspay`, `Family`, `UntaxAm`, `NSSF`, `NetSalary`) VALUES
(10, 'A01', '2025-05', 2025, 800.00, 30.00, 34.19, 123.00, 10.00, 0.00, 0.00, 987.19, 0.00, 0.00, 0.00, 977.19),
(11, 'A01', '2025-04', 2025, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 800.00, 0.00, 0.00, 0.00, 800.00),
(12, 'A02', '2025-04', 2025, 99999.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 99999.00, 0.00, 0.00, 0.00, 99999.00),
(13, 'A03', '2025-04', 2025, 123.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 123.00, 0.00, 0.00, 0.00, 123.00),
(14, 'A02', '2025-05', 2025, 99999.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 99999.00, 0.00, 0.00, 0.00, 99999.00),
(15, 'A03', '2025-05', 2025, 123.00, 123.00, 2.10, 0.00, 10.00, 0.00, 0.00, 248.10, 0.00, 0.00, 0.00, 238.10);

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
('D01', 'Research and Development', 'Active');

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
  `IsProb` int NOT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `PayParameter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Telegram` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ProbationDate` date NOT NULL,
  PRIMARY KEY (`EmpCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrstaffprofile`
--

INSERT INTO `hrstaffprofile` (`EmpCode`, `EmpName`, `Gender`, `Dob`, `Position`, `Department`, `Company`, `Level`, `Division`, `StartDate`, `Status`, `Contact`, `Email`, `Address`, `LineManager`, `Hod`, `Photo`, `IsProb`, `Salary`, `PayParameter`, `Telegram`, `ProbationDate`) VALUES
('A01', 'Phorn Khouch', 'Male', '2025-04-01', 'P03', 'IT', 'CB', NULL, 'D01', '2025-04-01', 'Active', '', '', '', '', '', 'Uploads/staff_photos/A01_1745763478.jpg', 0, 800.00, 'Staff', '', '0000-00-00'),
('A02', 'Sok kimheng', 'Male', '2025-04-01', 'P05', 'HR', 'CB', NULL, 'D01', '2025-04-01', 'Active', '', '', '', 'A01', 'A01', 'Uploads/staff_photos/A02_1745763581.jpg', 0, 99999.00, 'Staff', '', '0000-00-00'),
('A03', 'Sok Dara', 'Male', '2025-05-01', 'P02', 'HR', 'CB', NULL, 'D01', '2025-05-01', 'Active', '', '', '', '', 'A01', 'Uploads/staff_photos/A03_1746283328.jpg', 1, 123.00, 'Staff', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `hrusers`
--

DROP TABLE IF EXISTS `hrusers`;
CREATE TABLE IF NOT EXISTS `hrusers` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Role` enum('admin','manager','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `Status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `LastLogin` datetime DEFAULT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`),
  KEY `idx_username` (`Username`),
  KEY `idx_email` (`Email`),
  KEY `idx_status` (`Status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrusers`
--

INSERT INTO `hrusers` (`UserID`, `Username`, `Password`, `Email`, `Role`, `Status`, `LastLogin`, `CreatedAt`, `UpdatedAt`) VALUES
(5, 'A01', '123', 'pkhouch97@gmail.com', 'staff', 'active', NULL, '2025-05-03 19:30:38', '2025-05-04 15:00:17'),
(6, 'admin', '123', 'pkhouc@gmail.com', 'admin', 'active', NULL, '2025-05-04 15:03:42', '2025-05-11 22:03:03'),
(7, 'A02', '123', 'admin123@gmil.com', 'staff', 'active', NULL, '2025-05-04 21:19:54', '2025-05-04 21:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `hruser_permissions`
--

DROP TABLE IF EXISTS `hruser_permissions`;
CREATE TABLE IF NOT EXISTS `hruser_permissions` (
  `PermissionID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `ModuleName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CanView` tinyint(1) NOT NULL DEFAULT '0',
  `CanCreate` tinyint(1) NOT NULL DEFAULT '0',
  `CanEdit` tinyint(1) NOT NULL DEFAULT '0',
  `CanDelete` tinyint(1) NOT NULL DEFAULT '0',
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PermissionID`),
  UNIQUE KEY `user_module` (`UserID`,`ModuleName`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lmleavebalance`
--

INSERT INTO `lmleavebalance` (`ID`, `EmpCode`, `LeaveType`, `Balance`, `Entitle`, `CurrentBalance`, `Taken`, `inmonth`, `inyear`, `created_at`, `DefaultBalance`) VALUES
(207, 'A01', 'SL', 7.00, 7.00, 7.00, 0.00, 0, 2025, '0000-00-00 00:00:00', 0),
(208, 'A01', 'AL', 13.50, 13.50, 3.00, 0.00, 0, 2025, '0000-00-00 00:00:00', 0),
(209, 'A01', 'SPL', 7.00, 7.00, 7.00, 0.00, 0, 2025, '0000-00-00 00:00:00', 0),
(210, 'A02', 'SL', 7.00, 7.00, 7.00, 0.00, 0, 2025, '0000-00-00 00:00:00', 0),
(211, 'A02', 'AL', 13.50, 13.50, 1.00, 2.00, 0, 2025, '0000-00-00 00:00:00', 0),
(212, 'A02', 'SPL', 7.00, 7.00, 7.00, 0.00, 0, 2025, '0000-00-00 00:00:00', 0),
(213, 'A03', 'SL', 7.00, 7.00, 7.00, 0.00, 0, 2025, '0000-00-00 00:00:00', 0),
(214, 'A03', 'AL', 12.00, 12.00, 1.50, 0.00, 0, 2025, '0000-00-00 00:00:00', 0),
(215, 'A03', 'SPL', 7.00, 7.00, 7.00, 0.00, 0, 2025, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lmleaverequest`
--

DROP TABLE IF EXISTS `lmleaverequest`;
CREATE TABLE IF NOT EXISTS `lmleaverequest` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LeaveType` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `LeaveDay` int NOT NULL,
  `PHOrOffDay` int NOT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL,
  `ApprovedBy` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `RejectedBy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lmleaverequest`
--

INSERT INTO `lmleaverequest` (`ID`, `EmpCode`, `LeaveType`, `Reason`, `FromDate`, `ToDate`, `LeaveDay`, `PHOrOffDay`, `Status`, `UpdatedAt`, `ApprovedBy`, `RejectedBy`) VALUES
(21, 'A02', 'SPL', 'test', '2025-05-06', '2025-05-10', 4, 0, 'Approved', '2025-05-02 14:53:13', 'HR Admin', ''),
(22, 'A02', 'AL', 'test', '2025-05-09', '2025-05-09', 1, 0, 'Approved', '2025-05-02 15:13:27', 'HR Admin', ''),
(20, 'A01', 'AL', 'test', '2025-05-01', '2025-05-01', 1, 0, 'Rejected', '2025-05-02 14:50:48', '', 'HR Admin'),
(23, 'A02', 'AL', 'test', '2025-05-12', '2025-05-12', 1, 0, 'Approved', '2025-05-02 15:12:34', 'HR Admin', ''),
(24, 'A02', 'AL', 'test', '2025-05-03', '2025-05-05', 1, 0, 'Approved', '2025-05-02 15:14:28', 'HR Admin', ''),
(25, 'A01', 'AL', 'test', '2025-05-13', '2025-05-13', 1, 0, 'Approved', '2025-05-02 15:15:15', 'HR Admin', ''),
(27, 'A02', 'AL', 'test', '2025-05-16', '2025-05-23', 6, 0, 'Rejected', '2025-05-03 09:57:36', '', 'HR Admin'),
(28, 'A03', 'AL', 'test', '2025-05-06', '2025-05-09', 4, 0, 'Approved', '2025-05-03 14:47:35', 'HR Admin', ''),
(30, 'A03', 'AL', 'test', '2025-05-15', '2025-05-15', 1, 0, 'Rejected', '2025-05-03 14:51:39', '', 'HR Admin'),
(31, 'A03', 'AL', 'test', '2025-05-14', '2025-05-14', 1, 0, 'Approved', '2025-05-03 14:56:10', 'HR Admin', '');

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
('AL', 'Annaul Leave', 1, 0, 0, 18),
('SPL', 'Special Leave', 0, 0, 0, 7),
('UL', 'Unpaid Leave', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prallowance`
--

DROP TABLE IF EXISTS `prallowance`;
CREATE TABLE IF NOT EXISTS `prallowance` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AllowanceType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prallowance`
--

INSERT INTO `prallowance` (`ID`, `EmpCode`, `AllowanceType`, `Description`, `FromDate`, `ToDate`, `Amount`, `Status`, `Remark`) VALUES
(3, 'A03', 'Meal', 'test', '2025-05-01', '2025-05-30', 123.00, 'Active', ''),
(4, 'A01', 'Meal', 'test', '2025-05-06', '2025-05-23', 10.00, 'Active', ''),
(5, 'A01', 'Phone', 'test', '2025-05-01', '2025-05-31', 20.00, 'Active', '');

-- --------------------------------------------------------

--
-- Table structure for table `prapprovesalary`
--

DROP TABLE IF EXISTS `prapprovesalary`;
CREATE TABLE IF NOT EXISTS `prapprovesalary` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `InMonth` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Remark` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prapprovesalary`
--

INSERT INTO `prapprovesalary` (`ID`, `InMonth`, `Remark`, `status`) VALUES
(1, '2025-02', NULL, 'Pending'),
(2, '2025-05', NULL, 'Pending'),
(3, '2025-04', NULL, 'Pending');

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
  `ID` int NOT NULL AUTO_INCREMENT,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BonusType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prbonus`
--

INSERT INTO `prbonus` (`ID`, `EmpCode`, `BonusType`, `Description`, `FromDate`, `ToDate`, `Amount`, `Status`, `Remark`) VALUES
(2, 'A01', 'dfs', 'test', '2025-05-23', '2025-05-23', 123.00, 'Active', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `prdeduction`
--

DROP TABLE IF EXISTS `prdeduction`;
CREATE TABLE IF NOT EXISTS `prdeduction` (
  `Prdeduction` datetime DEFAULT NULL,
  `ID` int NOT NULL AUTO_INCREMENT,
  `EmpCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DeductType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prdeduction`
--

INSERT INTO `prdeduction` (`Prdeduction`, `ID`, `EmpCode`, `DeductType`, `Description`, `FromDate`, `ToDate`, `Amount`, `Status`, `Remark`) VALUES
(NULL, 2, 'A03', 'Late', 'test', '2025-05-10', '2025-05-10', 10.00, 'Active', ''),
(NULL, 3, 'A01', 'Late', 'late deduction', '2025-05-14', '2025-05-14', 10.00, 'Active', '');

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

--
-- Dumping data for table `protrate`
--

INSERT INTO `protrate` (`Code`, `Des`, `Rate`) VALUES
('SUN', 'OT Sunday', 200.00),
('PH', 'Public Holiday', 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `provertime`
--

DROP TABLE IF EXISTS `provertime`;
CREATE TABLE IF NOT EXISTS `provertime` (
  `Provtime` datetime DEFAULT NULL,
  `ID` int NOT NULL AUTO_INCREMENT,
  `Empcode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OTType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OTDate` date DEFAULT NULL,
  `FromTime` time DEFAULT NULL,
  `ToTime` time DEFAULT NULL,
  `hour` decimal(5,2) DEFAULT NULL,
  `Reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provertime`
--

INSERT INTO `provertime` (`Provtime`, `ID`, `Empcode`, `OTType`, `OTDate`, `FromTime`, `ToTime`, `hour`, `Reason`) VALUES
('2025-05-11 16:20:38', 1, 'A03', 'PH', '2025-05-11', '18:00:00', '22:00:00', 4.00, 'test'),
('2025-05-11 21:38:26', 2, 'A01', 'PH', '2025-05-14', '07:00:00', '17:00:00', 10.00, 'PH OT ');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prpaypolicy`
--

INSERT INTO `prpaypolicy` (`id`, `code`, `description`, `workday`, `hourperday`, `hourperweek`, `fromdate`, `todate`, `mon`, `monhours`, `tues`, `tueshours`, `wed`, `wedhours`, `thur`, `thurhours`, `fri`, `frihours`, `sat`, `sathours`, `sun`, `sunhours`, `tue`, `tueHours`, `thu`, `thuHours`) VALUES
(10, 'Staff', 'Staff ', 26, 9, NULL, '2025-05-11', '2025-05-11', 1, 8, 1, 1, 1, 8, 1, 1, 1, 8, 1, 8, 0, 8, 1, 8, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `pr_benefit_setting`
--

DROP TABLE IF EXISTS `pr_benefit_setting`;
CREATE TABLE IF NOT EXISTS `pr_benefit_setting` (
  `Code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`Code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pr_benefit_setting`
--

INSERT INTO `pr_benefit_setting` (`Code`, `Description`, `Status`, `Type`) VALUES
('Meal', 'Meal Allowance', 'A', 'Allowance'),
('Ann', 'Annaul Bonus', 'A', 'Bonus');

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

-- --------------------------------------------------------

--
-- Table structure for table `sytelegram_config`
--

DROP TABLE IF EXISTS `sytelegram_config`;
CREATE TABLE IF NOT EXISTS `sytelegram_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chat_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_id` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bot_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sytelegram_config`
--

INSERT INTO `sytelegram_config` (`id`, `chat_name`, `chat_id`, `bot_token`, `description`, `status`) VALUES
(3, 'fsd', 'fdsfd', 'fsdfsdfsd', 'fsd', 1),
(4, 'fsdg', 'sdds', 'gsd', 'dsg', 1),
(5, 'fsdfs', 'f534546', '655657', '', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hruser_permissions`
--
ALTER TABLE `hruser_permissions`
  ADD CONSTRAINT `hruser_permissions_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `hrusers` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
