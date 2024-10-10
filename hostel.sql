-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 09:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(300) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updation_date` date NOT NULL,
  `access` tinyint(1) NOT NULL,
  `adminName` varchar(255) DEFAULT NULL,
  `clgName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `username`, `email`, `password`, `reg_date`, `updation_date`, `access`, `adminName`, `clgName`) VALUES
(1, 'admin', 'admin@gmail.com', 'Test@1234', '2024-01-31 20:31:45', '2024-02-10', 1, 'abc dcb xyz', 'D. Y. Patil'),
(2, 'ash', 'ashishsatpute6747@gmail.com', 'Ash@7077', '2024-07-31 06:53:04', '0000-00-00', 1, 'Ash', 'D. Y. Patil');

-- --------------------------------------------------------

--
-- Table structure for table `adminlog`
--

CREATE TABLE `adminlog` (
  `ipId` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `logintime` timestamp NOT NULL DEFAULT current_timestamp(),
  `adminName` varchar(255) DEFAULT NULL,
  `adminEmail` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `adminlog`
--

INSERT INTO `adminlog` (`ipId`, `adminid`, `ip`, `logintime`, `adminName`, `adminEmail`, `country`, `city`) VALUES
(1, 0, 0x4445534b544f502d414a47334f4533, '2024-09-02 06:10:31', 'admin', 'admin@gmail.com', 'India', 'Mumbai'),
(2, 0, 0x4445534b544f502d414a47334f4533, '2024-09-02 08:51:18', 'admin', 'admin@gmail.com', 'India', 'Mumbai'),
(3, 0, 0x4445534b544f502d414a47334f4533, '2024-09-03 09:09:07', 'admin', 'admin@gmail.com', 'India', 'Mumbai'),
(4, 0, 0x4445534b544f502d414a47334f4533, '2024-09-04 05:47:18', 'admin', 'admin@gmail.com', 'India', 'Mumbai'),
(5, 0, 0x4445534b544f502d414a47334f4533, '2024-09-30 11:45:10', 'admin', 'admin@gmail.com', 'India', 'Mumbai'),
(6, 0, 0x4445534b544f502d414a47334f4533, '2024-10-03 05:39:08', 'admin', 'admin@gmail.com', '', ''),
(7, 0, 0x4445534b544f502d414a47334f4533, '2024-10-03 05:49:28', 'admin', 'admin@gmail.com', 'India', 'Mumbai'),
(8, 0, 0x4445534b544f502d414a47334f4533, '2024-10-03 08:15:45', 'admin', 'admin@gmail.com', 'India', 'Mumbai'),
(9, 0, 0x4c4150544f502d3131465653564348, '2024-10-08 08:09:43', 'admin', 'admin@gmail.com', 'India', 'Pune'),
(10, 0, 0x4c4150544f502d3131465653564348, '2024-10-08 09:14:44', 'admin', 'admin@gmail.com', 'India', 'Pune'),
(11, 0, 0x4c4150544f502d3131465653564348, '2024-10-08 09:41:28', 'admin', 'admin@gmail.com', 'India', 'Pune');

-- --------------------------------------------------------

--
-- Table structure for table `complainthistory`
--

CREATE TABLE `complainthistory` (
  `id` int(11) NOT NULL,
  `complaintid` int(11) DEFAULT NULL,
  `compalintStatus` varchar(255) DEFAULT NULL,
  `complaintRemark` mediumtext DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complainthistory`
--

INSERT INTO `complainthistory` (`id`, `complaintid`, `compalintStatus`, `complaintRemark`, `postingDate`) VALUES
(1, 1, 'In Process', 'jgvhjgjf', '2024-09-02 11:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `ComplainNumber` bigint(12) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `complaintType` varchar(255) DEFAULT NULL,
  `complaintDetails` mediumtext DEFAULT NULL,
  `complaintDoc` varchar(255) DEFAULT NULL,
  `complaintStatus` varchar(255) DEFAULT NULL,
  `registrationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `ComplainNumber`, `userId`, `complaintType`, `complaintDetails`, `complaintDoc`, `complaintStatus`, `registrationDate`) VALUES
(1, 673243156, 1, 'Room Transfer', 'yujrtf', '1b26659521a2996bc3856f098fec30e0.jpg', 'In Process', '2024-09-02 10:56:08'),
(2, 580746063, 1, 'Fee Related', 'gjgtykgl', 'ec8304a3a9a664b6fafb85a8c9073ce0.jpg', NULL, '2024-09-02 12:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `clgName` varchar(255) NOT NULL,
  `course_code` varchar(255) DEFAULT NULL,
  `course_sn` varchar(255) DEFAULT NULL,
  `course_fn` varchar(255) DEFAULT NULL,
  `posting_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `clgName`, `course_code`, `course_sn`, `course_fn`, `posting_date`) VALUES
(1, 'KIT College', 'B10992', 'B.Tech', 'Bachelor  of Technology', '2024-02-14 19:31:42'),
(2, 'D. Y. Patil', 'BCOM1453', 'B.Com', 'Bachelor Of commerce ', '2024-02-14 19:31:42'),
(3, 'D. Y. Patil', 'BSC12', 'BSC', 'Bachelor  of Science', '2024-02-14 19:31:42'),
(4, 'KIT College', 'BC36356', 'BCA', 'Bachelor Of Computer Application', '2024-02-14 19:31:42'),
(5, 'KIT College', 'MCA565', 'MCA', 'Master of Computer Application', '2024-02-14 19:31:42'),
(6, 'D. Y. Patil', 'MBA75', 'MBA', 'Master of Business Administration', '2024-02-14 19:31:42'),
(7, 'KIT College', 'BE765', 'BE', 'Bachelor of Engineering', '2024-02-14 19:31:42'),
(14, 'D. Y. Patil', 'MATHS-21', 'BBA', 'Bachelor of Business Administration', '2024-10-03 08:17:05'),
(15, 'D. Y. Patil', 'MATHS-21', 'BCS1', 'Artificial Inteligance', '2024-10-03 08:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `AccessibilityWarden` varchar(255) DEFAULT NULL,
  `AccessibilityMember` varchar(255) DEFAULT NULL,
  `RedressalProblem` varchar(255) DEFAULT NULL,
  `Room` varchar(255) DEFAULT NULL,
  `Mess` varchar(255) DEFAULT NULL,
  `HostelSurroundings` varchar(255) DEFAULT NULL,
  `OverallRating` varchar(255) DEFAULT NULL,
  `FeedbackMessage` varchar(255) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `academicYear` int(255) NOT NULL,
  `postinDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest_gatepass`
--

CREATE TABLE `guest_gatepass` (
  `id` int(11) NOT NULL,
  `guest_name` varchar(255) NOT NULL,
  `guestIdFile` varchar(255) NOT NULL,
  `guestCount` int(2) NOT NULL,
  `userPrn` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `visit_date` date NOT NULL,
  `visit_time` time NOT NULL,
  `leave_date` date NOT NULL,
  `leave_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_gatepass`
--

INSERT INTO `guest_gatepass` (`id`, `guest_name`, `guestIdFile`, `guestCount`, `userPrn`, `reason`, `visit_date`, `visit_time`, `leave_date`, `leave_time`) VALUES
(1, 'rajesh', '101010-GID-66d59086892785.02685084.jpg', 2, '101010', 'fdsnbf grkltrnt pyjey rtejtnk wrlksm etjlknet awliejawk wedawhrb typortjynrkly wluqgh\r\nrdtrtgtyhgyjhj gkhghjhg ehgywrleknt ghtkhjnh thtrlkulku tyjkrhtjerg yrute ytjnhjhkjt rtjrhte teuith\r\ndfgjfhdjkgkdgh', '2024-09-15', '13:55:00', '2024-09-15', '16:53:00'),
(2, 'fxdhfghbfth', '101010-GID-66d591c8706a59.08188537.png', 20, '101010', 'rdgrdg', '2024-09-28', '09:57:00', '2024-09-28', '10:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `guest_rooms`
--

CREATE TABLE `guest_rooms` (
  `id` int(11) NOT NULL,
  `room_no` varchar(10) NOT NULL,
  `active` enum('Yes','No') DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_rooms`
--

INSERT INTO `guest_rooms` (`id`, `room_no`, `active`) VALUES
(1, 'G1', 'Yes'),
(2, 'G2', 'Yes'),
(3, 'G3', 'Yes'),
(4, 'G4', 'Yes'),
(10, 'G5', 'Yes'),
(11, 'G6', 'Yes'),
(12, 'G7', 'Yes'),
(14, 'N', 'Yes'),
(15, 'CVX', 'Yes'),
(16, 'G9', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `guest_rooms_bookings`
--

CREATE TABLE `guest_rooms_bookings` (
  `id` int(11) NOT NULL,
  `userPrn` int(255) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `relation` varchar(50) NOT NULL,
  `room_no` varchar(10) NOT NULL,
  `reason` text NOT NULL,
  `visit_date` date NOT NULL,
  `visit_time` time NOT NULL,
  `leave_date` date NOT NULL,
  `leave_time` time NOT NULL,
  `guestIdFile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_rooms_bookings`
--

INSERT INTO `guest_rooms_bookings` (`id`, `userPrn`, `student_name`, `guest_name`, `relation`, `room_no`, `reason`, `visit_date`, `visit_time`, `leave_date`, `leave_time`, `guestIdFile`) VALUES
(1, 1, '', 'Vidyadar kale', '', 'CVX', 'cgbfcjg,cfgzscdZAcmjhk', '2024-09-13', '06:42:00', '2024-09-14', '06:42:00', 'GID-66d580d1356a99.40510243.png'),
(2, 101010, '', 'Rajesh Pandit', 'Father', 'G7', 'adsffdh hjghf kfjsg tri rifg;wer hurtjnh  iyouy8ieurtjwuir eruhbgj ityjiryn wyhbn et tryporyi weruiui2e topyjnjdr wuehqilr ryjhgk waersjkb rtorkykynjkfg qEUIQLEHR NH;FGHTK; ur;ytnrjky ytulkyukl;yutjky', '2024-09-15', '04:43:00', '2024-09-16', '06:45:00', '101010-GID-66d58142c58187.01147042.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `hostel`
--

CREATE TABLE `hostel` (
  `id` int(11) NOT NULL,
  `hostelName` varchar(255) NOT NULL,
  `rooms` int(11) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostel`
--

INSERT INTO `hostel` (`id`, `hostelName`, `rooms`, `gender`, `capacity`, `active`) VALUES
(1, 'ABC hostel', 4, 'male', 16, 0),
(2, 'Gargoti 01', 4, 'female', 12, 1),
(3, 'Radhanagari 02', 1, 'female', 1, 1),
(4, 'YXH hostel', 1, 'male', 1, 1),
(5, 'axz hostel', 0, 'male', 0, 1),
(6, 'CBS Hostel', 0, 'female', 0, 1),
(7, 'RDS Hostel', 0, 'male', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `institute`
--

CREATE TABLE `institute` (
  `collegeId` int(11) NOT NULL,
  `clgCode` varchar(30) NOT NULL,
  `clgName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `institute`
--

INSERT INTO `institute` (`collegeId`, `clgCode`, `clgName`) VALUES
(1, '6454', 'D. Y. Patil');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `refundId` int(11) NOT NULL,
  `userPrn` varchar(50) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `class` varchar(50) NOT NULL,
  `academicYear` varchar(10) NOT NULL,
  `refundAmount` decimal(10,2) NOT NULL,
  `requestDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `adminComments` text DEFAULT NULL,
  `receiptFile` varchar(255) DEFAULT NULL,
  `REASON` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `roomno` varchar(255) DEFAULT NULL,
  `seater` int(11) DEFAULT NULL,
  `feespm` decimal(15,2) DEFAULT NULL,
  `roomId` int(11) DEFAULT NULL,
  `stayfrom` date DEFAULT NULL,
  `academicYear` varchar(50) NOT NULL,
  `course` varchar(500) DEFAULT NULL,
  `class` varchar(20) NOT NULL,
  `userPrn` varchar(255) NOT NULL,
  `firstName` varchar(500) DEFAULT NULL,
  `middleName` varchar(500) DEFAULT NULL,
  `lastName` varchar(500) DEFAULT NULL,
  `gender` varchar(250) DEFAULT NULL,
  `contactno` bigint(11) DEFAULT NULL,
  `emailid` varchar(500) DEFAULT NULL,
  `egycontactno` bigint(11) DEFAULT NULL,
  `guardianName` varchar(500) DEFAULT NULL,
  `guardianRelation` varchar(500) DEFAULT NULL,
  `guardianContactno` bigint(11) DEFAULT NULL,
  `corresAddress` varchar(500) DEFAULT NULL,
  `corresCIty` varchar(500) DEFAULT NULL,
  `corresState` varchar(500) DEFAULT NULL,
  `corresPincode` int(11) DEFAULT NULL,
  `pmntAddress` varchar(500) DEFAULT NULL,
  `pmntCity` varchar(500) DEFAULT NULL,
  `pmnatetState` varchar(500) DEFAULT NULL,
  `pmntPincode` int(11) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(500) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `verifiedBy` int(11) NOT NULL,
  `clgName` varchar(255) NOT NULL,
  `hostelName` varchar(255) DEFAULT NULL,
  `statusUpdationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `roomno`, `seater`, `feespm`, `roomId`, `stayfrom`, `academicYear`, `course`, `class`, `userPrn`, `firstName`, `middleName`, `lastName`, `gender`, `contactno`, `emailid`, `egycontactno`, `guardianName`, `guardianRelation`, `guardianContactno`, `corresAddress`, `corresCIty`, `corresState`, `corresPincode`, `pmntAddress`, `pmntCity`, `pmnatetState`, `pmntPincode`, `postingDate`, `updationDate`, `status`, `comment`, `verifiedBy`, `clgName`, `hostelName`, `statusUpdationDate`) VALUES
(1, 'D1', 2, 6000.00, 9, '2024-09-30', '2024-2025', 'Bachelor Of commerce ', 'First-Year', '101010', 'Priyal', 'Kumar', 'Patil', 'female', 9999999992, 'prajktajagtap018@gmail.com', 5555555555, 'Rajesh', 'uncle', 4444444444, 'sdf', 'sdfg', 'Jharkhand', 555555, 'sdf', 'sdfg', 'Jharkhand', 555555, '2024-09-02 03:31:24', NULL, 'verified', 'Accepted', 1, 'D. Y. Patil', 'XYZ hostel', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reportID` int(11) NOT NULL,
  `stayFrom` date DEFAULT NULL,
  `roomId` int(11) DEFAULT NULL,
  `reportTotalAmount` decimal(15,2) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `class` varchar(20) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `userPrn` varchar(255) NOT NULL,
  `emailid` varchar(500) NOT NULL,
  `contactno` bigint(11) NOT NULL,
  `roomno` varchar(30) NOT NULL,
  `roomFeesphy` int(11) NOT NULL,
  `refund` varchar(22) DEFAULT NULL,
  `academicYear` varchar(30) NOT NULL,
  `paymentStatus` varchar(50) NOT NULL,
  `status` varchar(18) NOT NULL,
  `skimAmount` decimal(15,2) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `remainingAmountCheck` tinyint(1) DEFAULT NULL,
  `remainingAmount` decimal(15,2) DEFAULT NULL,
  `verifiedBy` int(11) NOT NULL,
  `clgName` varchar(255) NOT NULL,
  `hostelName` varchar(255) DEFAULT NULL,
  `seatsAvaibility` int(11) DEFAULT NULL,
  `occupiedSeats` int(11) DEFAULT NULL,
  `statusUpdationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`reportID`, `stayFrom`, `roomId`, `reportTotalAmount`, `profile`, `class`, `fullName`, `userPrn`, `emailid`, `contactno`, `roomno`, `roomFeesphy`, `refund`, `academicYear`, `paymentStatus`, `status`, `skimAmount`, `comment`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `clgName`, `hostelName`, `seatsAvaibility`, `occupiedSeats`, `statusUpdationDate`) VALUES
(2, '2024-09-30', 9, 6000.00, NULL, 'First-Year', 'Priyal Kumar Patil', '101010', 'prajktajagtap018@gmail.com', 9999999992, 'D1', 6000, NULL, '2024-2025', 'Full Paid', 'verified', NULL, 'Your overall amount is verified.', 1, 0.00, 1, 'D. Y. Patil', 'XYZ hostel', NULL, NULL, '2024-09-02');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `seater` int(11) DEFAULT NULL,
  `room_no` varchar(50) NOT NULL,
  `yearlyFees` decimal(15,2) NOT NULL,
  `halfyearlyAmount` decimal(15,2) DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `posting_date` timestamp NULL DEFAULT current_timestamp(),
  `clgName` varchar(255) NOT NULL,
  `hostelName` varchar(255) NOT NULL,
  `seatsAvaibility` int(11) DEFAULT NULL,
  `occupiedSeats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `seater`, `room_no`, `yearlyFees`, `halfyearlyAmount`, `gender`, `posting_date`, `clgName`, `hostelName`, `seatsAvaibility`, `occupiedSeats`) VALUES
(1, 4, 'A1', 8700.00, 0.00, 'male', '2024-02-19 22:45:43', 'D. Y. Patil', 'ABC hostel', 1, 4),
(2, 40, 'MH14-GU-9095', 6000.00, 0.00, 'female', '2024-02-19 22:45:43', 'D. Y. Patil', 'Gargoti 01', 1, 1),
(3, 2, 'B1', 6000.00, 0.00, 'male', '2024-02-19 22:45:43', 'D. Y. Patil', 'ABC hostel', 2, 0),
(4, 3, 'B2', 4000.00, 0.00, 'female', '2024-02-19 22:45:43', 'D. Y. Patil', 'XYZ hostel', 1, 2),
(5, 5, 'C1', 2000.00, 0.00, 'male', '2024-02-19 22:45:43', 'D. Y. Patil', 'ABC hostel', 4, 1),
(6, 3, 'C1', 3000.00, 0.00, 'female', '2024-04-17 11:41:16', 'D. Y. Patil', 'XYZ hostel', 0, 3),
(7, 4, 'D1', 500.00, 0.00, 'male', '2024-07-04 07:41:41', 'D. Y. Patil', 'ABC hostel', 0, 4),
(9, 4, 'D1', 5000.00, 0.00, 'female', '2024-07-09 12:37:11', 'D. Y. Patil', 'XYZ hostel', 4, 0),
(10, 1, 'A3', 5000.00, 0.00, 'female', '2024-07-10 07:57:58', 'D. Y. Patil', 'Radhanagari 02', 1, 0),
(11, 1, 'A2', 5000.00, 0.00, 'male', '2024-07-10 07:59:10', 'D. Y. Patil', 'YXH hostel', 1, 0),
(12, 1, 'N1', 3000.00, 0.00, 'female', '2024-07-31 09:26:31', 'D. Y. Patil', 'XYZ hostel', 1, 0),
(13, 1, 'H10', 10000.00, 0.00, 'female', '2024-08-29 11:33:35', 'D. Y. Patil', 'XYZ hostel', 1, 0),
(14, 2, 'D20', 3000.00, 0.00, 'male', '2024-09-04 06:19:34', 'D. Y. Patil', 'axz hostel', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settingId` int(11) NOT NULL,
  `option` varchar(255) DEFAULT NULL,
  `val` varchar(255) DEFAULT NULL,
  `fromDate` varchar(255) DEFAULT NULL,
  `toDate` varchar(255) DEFAULT NULL,
  `enableOp` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settingId`, `option`, `val`, `fromDate`, `toDate`, `enableOp`) VALUES
(1, 'renew', '7', '2024-07-25', '2024-08-31', 1),
(2, 'register', '7', '2024-09-01', '2024-10-31', 1),
(3, 'guest_room', '7', '2024-07-01', '2024-08-23', 1),
(4, 'feedback', '0', '2024-05-01', '2024-06-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `State` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `State`) VALUES
(1, 'Andaman and Nicobar Island (UT)'),
(2, 'Andhra Pradesh'),
(3, 'Arunachal Pradesh'),
(4, 'Assam'),
(5, 'Bihar'),
(6, 'Chandigarh (UT)'),
(7, 'Chhattisgarh'),
(8, 'Dadra and Nagar Haveli (UT)'),
(9, 'Daman and Diu (UT)'),
(10, 'Delhi (NCT)'),
(11, 'Goa'),
(12, 'Gujarat'),
(13, 'Haryana'),
(14, 'Himachal Pradesh'),
(15, 'Jammu and Kashmir'),
(16, 'Jharkhand'),
(17, 'Karnataka'),
(18, 'Kerala'),
(19, 'Lakshadweep (UT)'),
(20, 'Madhya Pradesh'),
(21, 'Maharashtra'),
(22, 'Manipur'),
(23, 'Meghalaya'),
(24, 'Mizoram'),
(25, 'Nagaland'),
(26, 'Odisha'),
(27, 'Puducherry (UT)'),
(28, 'Punjab'),
(29, 'Rajastha'),
(30, 'Sikkim'),
(31, 'Tamil Nadu'),
(32, 'Telangana'),
(33, 'Tripura'),
(34, 'Uttarakhand'),
(35, 'Uttar Pradesh'),
(36, 'West Bengal');

-- --------------------------------------------------------

--
-- Table structure for table `student_gatepass`
--

CREATE TABLE `student_gatepass` (
  `id` int(11) NOT NULL,
  `userPrn` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `out_date` date NOT NULL,
  `out_time` time NOT NULL,
  `in_date` date NOT NULL,
  `in_time` time NOT NULL,
  `noOfDays` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_gatepass`
--

INSERT INTO `student_gatepass` (`id`, `userPrn`, `reason`, `out_date`, `out_time`, `in_date`, `in_time`, `noOfDays`) VALUES
(1, '101010', 'df bgdf hkgk bkuy ertey  jynvbmn er yrky rtu yirht rtuhdbf fdgjkdgbketr tyubg gbmd,bsgd tutheie guib gdmns ghjtt tuhiut wruthi titj itjih hjnb bgdjbgd jrtyrtut tertbhb tjktjet gbeutu gutut gurtrut gbg', '2024-09-05', '17:32:00', '2024-09-10', '18:47:00', 5.05);

-- --------------------------------------------------------

--
-- Table structure for table `transactionhistory`
--

CREATE TABLE `transactionhistory` (
  `transactionId` int(50) NOT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `paymentDate` date NOT NULL,
  `receiptTokenId` varchar(255) NOT NULL,
  `paidAmount` decimal(10,2) NOT NULL,
  `receiptFile` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `userPrn` varchar(255) NOT NULL,
  `emailid` varchar(500) NOT NULL,
  `contactno` bigint(11) NOT NULL,
  `roomno` varchar(30) NOT NULL,
  `feespm` decimal(15,2) NOT NULL,
  `payType` varchar(22) NOT NULL,
  `academicYear` varchar(30) NOT NULL,
  `paidStatus` varchar(50) NOT NULL,
  `status` varchar(18) NOT NULL,
  `skimAmount` decimal(15,2) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `paymentDateCheck` tinyint(1) DEFAULT NULL,
  `receiptTokenidCheck` tinyint(1) DEFAULT NULL,
  `paidAmountCheck` tinyint(1) DEFAULT NULL,
  `receiptFileCheck` tinyint(1) DEFAULT NULL,
  `alluserDetailsCheck` tinyint(1) DEFAULT NULL,
  `payTypeCheck` tinyint(1) DEFAULT NULL,
  `remainingAmountCheck` tinyint(1) DEFAULT NULL,
  `remainingAmount` decimal(10,2) NOT NULL,
  `verifiedBy` int(11) DEFAULT NULL,
  `statusUpdationDate` date DEFAULT NULL,
  `hostelName` varchar(255) NOT NULL,
  `clgName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactionhistory`
--

INSERT INTO `transactionhistory` (`transactionId`, `timeStamp`, `paymentDate`, `receiptTokenId`, `paidAmount`, `receiptFile`, `fullName`, `userPrn`, `emailid`, `contactno`, `roomno`, `feespm`, `payType`, `academicYear`, `paidStatus`, `status`, `skimAmount`, `comment`, `paymentDateCheck`, `receiptTokenidCheck`, `paidAmountCheck`, `receiptFileCheck`, `alluserDetailsCheck`, `payTypeCheck`, `remainingAmountCheck`, `remainingAmount`, `verifiedBy`, `statusUpdationDate`, `hostelName`, `clgName`) VALUES
(1, '2024-09-02 07:04:41', '2024-09-05', '12345565345', 5000.00, '101010-IMG-66d56389a453f0.31950979.jpg', 'Priyal Kumar Patil', '101010', 'prajktajagtap018@gmail.com', 9999999992, 'A1', 6000.00, 'CASH', '2024-2025', 'Partial Paid', 'rejected', NULL, 'Rejected', 1, 1, 1, 0, 1, 1, NULL, 6000.00, 1, '2024-09-02', 'XYZ hostel', 'D. Y. Patil'),
(2, '2024-09-02 07:23:56', '2024-09-05', '12345569', 6000.00, '101010-IMG-66d5680cf39e36.63527475.jpg', 'Priyal Kumar Patil', '101010', 'prajktajagtap018@gmail.com', 9999999992, 'A1', 6000.00, 'CASH', '2024-2025', 'Full Paid', 'verified', NULL, 'Verrified......!', 1, 1, 1, 1, 1, 1, NULL, 6000.00, 1, '2024-09-02', 'XYZ hostel', 'D. Y. Patil'),
(3, '2024-09-02 08:54:48', '2024-09-19', '123455603634224', 0.00, '101010-IMG-66d57d581c99f6.71689073.jpg', 'Priyal Kumar Patil', '101010', 'prajktajagtap018@gmail.com', 9999999992, 'A1', 6000.00, 'CASH', '2024-2025', 'Partial Paid', 'rejected', NULL, 'Rejected.....Your amount cant be verified', 0, 0, 0, 0, 0, 0, NULL, 0.00, 1, '2024-09-02', 'XYZ hostel', 'D. Y. Patil');

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userIp` varbinary(16) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `loginTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `userId`, `userEmail`, `userIp`, `city`, `country`, `loginTime`) VALUES
(1, 1, 'prajktajagtap018@gmail.com', 0x4445534b544f502d414a47334f4533, 'Mumbai', 'India', '2024-09-02 06:54:43'),
(2, 1, 'prajktajagtap018@gmail.com', 0x4445534b544f502d414a47334f4533, 'Mumbai', 'India', '2024-09-04 05:56:12'),
(3, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Pune', 'India', '2024-10-08 09:34:21'),
(4, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Pune', 'India', '2024-10-08 09:39:24'),
(5, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Pune', 'India', '2024-10-08 09:45:34'),
(6, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Kolh?pur', 'India', '2024-10-10 06:15:55'),
(7, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Kolh?pur', 'India', '2024-10-10 06:16:08'),
(8, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Kolh?pur', 'India', '2024-10-10 06:16:14'),
(9, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Kolh?pur', 'India', '2024-10-10 06:49:12'),
(10, 3, 'sonalchindage@gmail.com', 0x4c4150544f502d3131465653564348, 'Kolh?pur', 'India', '2024-10-10 06:52:33');

-- --------------------------------------------------------

--
-- Table structure for table `userregistration`
--

CREATE TABLE `userregistration` (
  `id` int(11) NOT NULL,
  `userPrn` varchar(255) DEFAULT NULL,
  `clgName` varchar(255) DEFAULT NULL,
  `otp` int(10) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `contactNo` bigint(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `emailValidate` tinyint(1) NOT NULL,
  `regDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(45) DEFAULT NULL,
  `passUdateDate` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userregistration`
--

INSERT INTO `userregistration` (`id`, `userPrn`, `clgName`, `otp`, `firstName`, `middleName`, `lastName`, `gender`, `contactNo`, `email`, `password`, `emailValidate`, `regDate`, `updationDate`, `passUdateDate`) VALUES
(1, '101010', 'D. Y. Patil', NULL, 'Priyal', 'Kumar', 'Patil', 'female', 9999999992, 'prajktajagtap018@gmail.com', '4VcqfMk/L9o=', 1, '2024-09-02 06:53:00', NULL, NULL),
(3, '2021076956', 'D. Y. Patil', 846200, 'Sonal', 'S', 'Chindage', 'female', 9921104821, 'sonalchindage@gmail.com', '4VcqfMk/L9o=', 1, '2024-10-08 09:32:54', '10-10-2024 12:19:22', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `adminlog`
--
ALTER TABLE `adminlog`
  ADD PRIMARY KEY (`ipId`);

--
-- Indexes for table `complainthistory`
--
ALTER TABLE `complainthistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guest_gatepass`
--
ALTER TABLE `guest_gatepass`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userPrn` (`userPrn`);

--
-- Indexes for table `guest_rooms`
--
ALTER TABLE `guest_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_no` (`room_no`);

--
-- Indexes for table `guest_rooms_bookings`
--
ALTER TABLE `guest_rooms_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostel`
--
ALTER TABLE `hostel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institute`
--
ALTER TABLE `institute`
  ADD PRIMARY KEY (`collegeId`),
  ADD UNIQUE KEY `clgCode` (`clgCode`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`refundId`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clgName` (`clgName`),
  ADD KEY `hostelName` (`hostelName`),
  ADD KEY `academicYear` (`academicYear`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `verifiedBy` (`verifiedBy`),
  ADD KEY `clgName` (`clgName`),
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `academicYear` (`academicYear`) USING BTREE,
  ADD KEY `class` (`class`) USING BTREE;

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_no` (`room_no`),
  ADD KEY `clgName` (`clgName`),
  ADD KEY `hostelName` (`hostelName`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settingId`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_gatepass`
--
ALTER TABLE `student_gatepass`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactionhistory`
--
ALTER TABLE `transactionhistory`
  ADD PRIMARY KEY (`transactionId`),
  ADD UNIQUE KEY `receiptTokenId` (`receiptTokenId`),
  ADD KEY `clgName` (`clgName`),
  ADD KEY `hostelName` (`hostelName`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userregistration`
--
ALTER TABLE `userregistration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `clgName` (`clgName`),
  ADD KEY `course` (`otp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `adminlog`
--
ALTER TABLE `adminlog`
  MODIFY `ipId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `complainthistory`
--
ALTER TABLE `complainthistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guest_gatepass`
--
ALTER TABLE `guest_gatepass`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guest_rooms`
--
ALTER TABLE `guest_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `guest_rooms_bookings`
--
ALTER TABLE `guest_rooms_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hostel`
--
ALTER TABLE `hostel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `institute`
--
ALTER TABLE `institute`
  MODIFY `collegeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `refundId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `reportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `student_gatepass`
--
ALTER TABLE `student_gatepass`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactionhistory`
--
ALTER TABLE `transactionhistory`
  MODIFY `transactionId` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `userregistration`
--
ALTER TABLE `userregistration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
