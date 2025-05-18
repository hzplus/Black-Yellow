-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 10:13 AM
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
-- Database: `cleaning_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `confirmed_matches`
--

CREATE TABLE `confirmed_matches` (
  `matchid` int(11) NOT NULL,
  `cleanerid` int(11) NOT NULL,
  `homeownerid` int(11) NOT NULL,
  `serviceid` int(11) NOT NULL,
  `confirmed_at` datetime DEFAULT current_timestamp(),
  `booking_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirmed_matches`
--

INSERT INTO `confirmed_matches` (`matchid`, `cleanerid`, `homeownerid`, `serviceid`, `confirmed_at`, `booking_date`) VALUES
(2, 10, 9, 16, '2025-05-13 00:12:00', '2025-05-15'),
(3, 10, 9, 16, '2025-05-13 00:14:03', '2025-05-15'),
(4, 10, 9, 16, '2025-05-13 00:14:32', '2025-05-16'),
(6, 10, 9, 16, '2025-05-13 00:16:51', '2025-05-31'),
(7, 6, 9, 2, '2025-05-13 00:40:03', '2025-05-14'),
(8, 6, 9, 21, '2025-05-13 00:40:19', '2025-05-29'),
(9, 6, 9, 22, '2025-05-13 00:40:27', '2025-05-17'),
(10, 9, 55, 1, '2025-01-24 22:44:10', '2025-05-22'),
(11, 42, 76, 54, '2025-05-15 11:23:43', '2025-06-06'),
(12, 43, 51, 90, '2025-03-31 15:28:37', '2025-05-25'),
(13, 46, 53, 96, '2025-01-18 13:02:28', '2025-05-22'),
(14, 33, 52, 61, '2025-02-04 16:55:00', '2025-05-29'),
(15, 14, 63, 84, '2025-01-20 11:34:15', '2025-06-13'),
(16, 22, 69, 66, '2025-02-21 22:31:20', '2025-06-09'),
(17, 22, 61, 29, '2025-04-28 22:50:20', '2025-05-20'),
(18, 27, 55, 52, '2025-04-23 18:10:36', '2025-06-05'),
(19, 8, 56, 83, '2025-02-01 11:48:28', '2025-06-02'),
(20, 17, 59, 72, '2025-01-24 19:11:09', '2025-06-15'),
(21, 34, 56, 94, '2025-04-06 18:52:25', '2025-06-16'),
(22, 14, 59, 33, '2025-02-10 20:11:54', '2025-05-27'),
(23, 38, 68, 15, '2025-03-26 20:47:13', '2025-06-01'),
(24, 43, 86, 36, '2025-02-10 14:06:51', '2025-06-06'),
(25, 3, 56, 29, '2025-01-14 18:04:33', '2025-06-17'),
(26, 14, 85, 10, '2025-03-22 19:35:09', '2025-06-02'),
(27, 20, 74, 14, '2025-04-17 16:53:56', '2025-06-04'),
(28, 11, 70, 84, '2025-01-21 16:51:58', '2025-06-09'),
(29, 5, 55, 20, '2025-01-31 01:49:39', '2025-05-25'),
(30, 10, 84, 56, '2025-04-01 16:48:56', '2025-06-14'),
(31, 38, 66, 30, '2025-01-28 19:18:56', '2025-06-10'),
(32, 49, 87, 47, '2025-01-30 21:44:26', '2025-05-20'),
(33, 12, 64, 91, '2025-03-22 21:07:12', '2025-06-12'),
(34, 15, 70, 35, '2025-03-21 04:36:44', '2025-06-09'),
(35, 17, 74, 86, '2025-04-14 10:08:27', '2025-06-13'),
(36, 31, 53, 34, '2025-04-22 07:00:02', '2025-06-09'),
(37, 21, 71, 16, '2025-02-15 08:30:11', '2025-06-02'),
(38, 25, 80, 68, '2025-04-12 08:00:19', '2025-05-30'),
(39, 34, 74, 10, '2025-04-06 19:44:46', '2025-06-11'),
(40, 35, 78, 52, '2025-03-19 13:50:08', '2025-05-22'),
(41, 25, 85, 31, '2025-05-03 03:41:50', '2025-05-24'),
(42, 1, 75, 65, '2025-03-29 19:56:14', '2025-06-04'),
(43, 13, 66, 25, '2025-04-11 15:02:08', '2025-06-15'),
(44, 23, 56, 29, '2025-03-03 18:40:33', '2025-05-20'),
(45, 41, 74, 71, '2025-01-21 09:22:43', '2025-06-06'),
(46, 31, 87, 88, '2025-03-27 16:23:47', '2025-06-16'),
(47, 9, 60, 100, '2025-01-02 19:52:08', '2025-06-14'),
(48, 44, 79, 50, '2025-02-07 01:21:12', '2025-06-04'),
(49, 10, 69, 57, '2025-01-07 23:07:35', '2025-05-23'),
(50, 31, 88, 83, '2025-02-23 19:32:55', '2025-06-03'),
(51, 11, 60, 5, '2025-01-01 21:26:08', '2025-05-24'),
(52, 48, 85, 74, '2025-02-13 16:20:32', '2025-05-25'),
(53, 5, 68, 11, '2025-03-14 09:30:27', '2025-06-16'),
(54, 44, 69, 14, '2025-03-01 12:53:10', '2025-06-15'),
(55, 22, 51, 96, '2025-02-08 11:39:38', '2025-06-02'),
(56, 10, 85, 67, '2025-04-25 08:20:21', '2025-06-04'),
(57, 3, 75, 8, '2025-03-04 02:44:28', '2025-05-19'),
(58, 39, 87, 23, '2025-03-01 18:05:36', '2025-05-30'),
(59, 11, 86, 18, '2025-01-04 12:18:51', '2025-05-29'),
(60, 28, 54, 65, '2025-03-26 17:11:20', '2025-05-31'),
(61, 30, 77, 70, '2025-02-10 04:17:38', '2025-05-31'),
(62, 36, 82, 83, '2025-01-02 22:12:02', '2025-05-21'),
(63, 25, 61, 76, '2025-04-18 03:13:45', '2025-05-30'),
(64, 14, 87, 64, '2025-04-24 08:17:00', '2025-06-14'),
(65, 47, 75, 52, '2025-04-07 15:05:13', '2025-06-04'),
(66, 14, 77, 38, '2025-03-10 22:04:05', '2025-05-22'),
(67, 16, 88, 14, '2025-01-28 23:39:09', '2025-05-30'),
(68, 27, 66, 79, '2025-01-30 08:11:12', '2025-06-03'),
(69, 28, 76, 63, '2025-04-25 02:56:58', '2025-06-01'),
(70, 19, 75, 77, '2025-01-16 12:37:02', '2025-05-25'),
(71, 34, 79, 60, '2025-04-02 04:03:48', '2025-06-14'),
(72, 28, 85, 17, '2025-05-09 23:23:23', '2025-05-23'),
(73, 14, 55, 79, '2025-01-05 12:22:24', '2025-05-21'),
(74, 44, 81, 75, '2025-03-07 11:42:49', '2025-06-09'),
(75, 6, 81, 99, '2025-03-30 05:21:41', '2025-05-23'),
(76, 48, 69, 29, '2025-05-16 10:33:17', '2025-05-24'),
(77, 48, 89, 52, '2025-03-02 22:03:00', '2025-06-08'),
(78, 9, 53, 59, '2025-05-11 16:31:35', '2025-06-06'),
(79, 9, 74, 52, '2025-03-15 12:28:29', '2025-05-20'),
(80, 43, 86, 48, '2025-03-05 22:41:09', '2025-06-16'),
(81, 16, 69, 29, '2025-03-21 19:08:07', '2025-06-17'),
(82, 37, 54, 60, '2025-01-04 17:32:08', '2025-05-29'),
(83, 11, 52, 6, '2025-03-02 01:26:37', '2025-05-26'),
(84, 10, 77, 96, '2025-02-04 16:27:15', '2025-06-08'),
(85, 45, 67, 12, '2025-03-18 23:16:21', '2025-05-28'),
(86, 31, 84, 56, '2025-04-10 17:10:07', '2025-05-27'),
(87, 17, 78, 56, '2025-03-06 22:54:42', '2025-06-14'),
(88, 16, 84, 58, '2025-01-06 01:22:09', '2025-05-29'),
(89, 22, 59, 69, '2025-02-10 00:28:08', '2025-06-05'),
(90, 28, 61, 64, '2025-03-09 10:14:26', '2025-05-30'),
(91, 40, 65, 19, '2025-01-31 02:29:32', '2025-06-11'),
(92, 17, 53, 53, '2025-04-14 20:00:15', '2025-06-08'),
(93, 24, 83, 65, '2025-02-13 03:59:20', '2025-06-04'),
(94, 38, 52, 28, '2025-02-18 09:24:05', '2025-06-12'),
(95, 19, 82, 35, '2025-03-14 09:56:51', '2025-06-02'),
(96, 19, 69, 59, '2025-04-05 01:18:54', '2025-06-05'),
(97, 24, 80, 4, '2025-02-10 05:28:18', '2025-05-23'),
(98, 1, 52, 42, '2025-04-30 10:44:54', '2025-06-10'),
(99, 30, 63, 97, '2025-03-07 03:45:42', '2025-05-23'),
(100, 47, 84, 61, '2025-04-14 13:04:11', '2025-05-31'),
(101, 25, 59, 91, '2025-05-09 09:42:49', '2025-05-24'),
(102, 42, 74, 31, '2025-01-26 05:18:44', '2025-06-02'),
(103, 20, 90, 67, '2025-02-20 00:22:02', '2025-05-20'),
(104, 27, 60, 99, '2025-03-31 00:19:51', '2025-05-21'),
(105, 30, 82, 44, '2025-05-02 20:27:02', '2025-05-27'),
(106, 1, 62, 34, '2025-02-06 19:40:14', '2025-05-22'),
(107, 16, 64, 18, '2025-04-26 02:23:39', '2025-06-08'),
(108, 40, 90, 71, '2025-05-05 21:58:49', '2025-06-15'),
(109, 41, 69, 17, '2025-02-09 15:36:00', '2025-05-30'),
(110, 11, 9, 16, '2025-04-08 13:17:59', '2025-05-28'),
(111, 7, 9, 20, '2025-03-22 17:00:23', '2025-06-10'),
(112, 7, 9, 21, '2025-02-27 14:06:18', '2025-06-24'),
(113, 7, 9, 21, '2025-03-31 23:51:15', '2025-06-03'),
(114, 12, 9, 9, '2025-01-16 18:14:46', '2025-06-19'),
(115, 12, 9, 9, '2025-05-03 06:26:26', '2025-06-14'),
(116, 12, 9, 20, '2025-02-03 11:32:29', '2025-06-20'),
(117, 10, 9, 2, '2025-05-18 01:35:55', '2025-07-14'),
(118, 12, 9, 18, '2025-01-05 08:30:25', '2025-05-29'),
(119, 11, 9, 19, '2025-04-08 01:18:06', '2025-06-24'),
(120, 7, 9, 22, '2025-05-17 02:38:27', '2025-07-09'),
(121, 14, 9, 9, '2025-04-17 13:12:58', '2025-07-11'),
(122, 6, 9, 21, '2025-02-01 00:45:13', '2025-07-07'),
(123, 12, 9, 16, '2025-03-08 18:54:07', '2025-07-03'),
(124, 12, 9, 2, '2025-04-22 13:57:32', '2025-06-06'),
(125, 13, 9, 18, '2025-01-26 14:54:02', '2025-06-28'),
(126, 12, 9, 10, '2025-02-20 20:03:04', '2025-06-12'),
(127, 6, 9, 22, '2025-03-08 05:47:13', '2025-07-13'),
(128, 13, 9, 2, '2025-01-27 01:41:53', '2025-06-26'),
(129, 7, 9, 16, '2025-01-30 09:47:05', '2025-06-02'),
(130, 13, 9, 9, '2025-02-09 04:26:30', '2025-07-14'),
(131, 14, 9, 9, '2025-02-28 18:41:38', '2025-07-06'),
(132, 12, 9, 20, '2025-04-17 06:18:09', '2025-07-16'),
(133, 6, 9, 18, '2025-01-18 05:55:23', '2025-05-30'),
(134, 12, 9, 9, '2025-01-21 08:39:39', '2025-06-06'),
(135, 7, 9, 20, '2025-03-19 04:37:39', '2025-07-16'),
(136, 14, 9, 18, '2025-04-29 07:07:22', '2025-06-18'),
(137, 14, 9, 18, '2025-03-10 06:39:32', '2025-06-09'),
(138, 6, 9, 10, '2025-02-07 02:14:58', '2025-06-13'),
(139, 11, 9, 19, '2025-03-18 19:18:33', '2025-06-21'),
(140, 13, 9, 22, '2025-03-03 22:03:41', '2025-07-17'),
(141, 11, 9, 8, '2025-01-31 07:59:53', '2025-06-18'),
(142, 6, 9, 21, '2025-04-27 12:50:25', '2025-06-07'),
(143, 6, 9, 19, '2025-02-19 18:03:29', '2025-05-31'),
(144, 11, 9, 9, '2025-01-06 10:59:06', '2025-05-29'),
(145, 12, 9, 10, '2025-01-20 14:48:54', '2025-05-28'),
(146, 11, 9, 19, '2025-02-24 00:19:55', '2025-06-13'),
(147, 6, 9, 2, '2025-04-16 15:53:00', '2025-06-05'),
(148, 12, 9, 16, '2025-04-15 06:38:53', '2025-06-28'),
(149, 6, 9, 17, '2025-05-03 21:52:57', '2025-06-09'),
(150, 14, 9, 19, '2025-05-07 22:04:15', '2025-06-07'),
(151, 13, 9, 17, '2025-02-13 03:28:17', '2025-05-30'),
(152, 11, 9, 21, '2025-03-06 20:23:44', '2025-06-22'),
(153, 12, 9, 10, '2025-03-01 04:51:12', '2025-06-10'),
(154, 6, 9, 18, '2025-03-31 19:16:33', '2025-06-26'),
(155, 10, 9, 9, '2025-04-22 04:00:22', '2025-06-29'),
(156, 6, 9, 10, '2025-01-27 04:30:00', '2025-06-15'),
(157, 6, 9, 10, '2025-01-06 04:32:14', '2025-07-08'),
(158, 10, 9, 20, '2025-01-07 19:34:30', '2025-05-19'),
(159, 12, 9, 22, '2025-01-14 19:30:05', '2025-06-08'),
(160, 6, 9, 2, '2025-04-28 17:17:50', '2025-07-17'),
(161, 12, 9, 2, '2025-05-09 07:40:10', '2025-06-04'),
(162, 10, 9, 18, '2025-04-13 03:02:10', '2025-06-19'),
(163, 7, 9, 9, '2025-04-09 11:03:37', '2025-06-08'),
(164, 10, 9, 21, '2025-03-03 13:52:29', '2025-05-26'),
(165, 13, 9, 9, '2025-05-17 10:28:56', '2025-05-28'),
(166, 12, 9, 20, '2025-01-05 11:00:29', '2025-05-24'),
(167, 11, 9, 8, '2025-02-03 00:06:09', '2025-07-10'),
(168, 11, 9, 19, '2025-03-18 06:43:43', '2025-06-21'),
(169, 13, 9, 16, '2025-04-23 17:48:55', '2025-06-22'),
(170, 11, 9, 18, '2025-02-10 23:17:47', '2025-07-06'),
(171, 11, 9, 2, '2025-03-02 10:15:54', '2025-07-16'),
(172, 10, 9, 10, '2025-04-13 17:31:07', '2025-06-20'),
(173, 11, 9, 9, '2025-03-08 23:26:43', '2025-07-10'),
(174, 11, 9, 9, '2025-04-15 19:45:44', '2025-06-17'),
(175, 10, 9, 10, '2025-02-10 08:19:31', '2025-06-26'),
(176, 10, 9, 8, '2025-03-29 18:31:23', '2025-06-24'),
(177, 14, 9, 21, '2025-02-06 21:16:05', '2025-06-11'),
(178, 10, 9, 8, '2025-03-15 13:14:26', '2025-05-31'),
(179, 6, 9, 21, '2025-02-05 22:23:34', '2025-06-08'),
(180, 7, 9, 20, '2025-01-06 00:54:48', '2025-07-06'),
(181, 7, 9, 21, '2025-01-10 08:02:34', '2025-06-28'),
(182, 13, 9, 2, '2025-05-04 04:59:10', '2025-06-27'),
(183, 14, 9, 21, '2025-03-30 23:06:19', '2025-06-25'),
(184, 11, 9, 20, '2025-03-11 11:40:27', '2025-05-19'),
(185, 12, 9, 10, '2025-02-16 13:06:37', '2025-07-17'),
(186, 12, 9, 19, '2025-02-01 01:45:21', '2025-07-08'),
(187, 13, 9, 20, '2025-02-23 14:47:06', '2025-05-30'),
(188, 10, 9, 22, '2025-03-31 10:54:45', '2025-05-30'),
(189, 10, 9, 18, '2025-04-10 21:37:20', '2025-06-22'),
(190, 11, 9, 20, '2025-03-04 14:00:51', '2025-07-17'),
(191, 13, 9, 10, '2025-04-05 16:29:20', '2025-07-01'),
(192, 6, 9, 17, '2025-02-25 12:47:00', '2025-06-13'),
(193, 6, 9, 17, '2025-03-04 02:43:13', '2025-05-21'),
(194, 7, 9, 2, '2025-01-21 11:08:02', '2025-06-09'),
(195, 12, 9, 17, '2025-05-09 23:04:21', '2025-06-12'),
(196, 12, 9, 8, '2025-01-11 16:46:04', '2025-07-01'),
(197, 11, 9, 2, '2025-02-20 14:01:51', '2025-06-29'),
(198, 7, 9, 17, '2025-01-31 16:13:09', '2025-06-14'),
(199, 11, 9, 21, '2025-01-04 10:52:19', '2025-06-04'),
(200, 11, 9, 21, '2025-03-19 04:26:12', '2025-06-22'),
(201, 10, 9, 2, '2025-01-10 00:16:43', '2025-06-10'),
(202, 10, 9, 9, '2025-02-11 11:57:36', '2025-07-12'),
(203, 7, 9, 20, '2025-01-11 22:17:34', '2025-06-05'),
(204, 11, 9, 18, '2025-01-06 23:39:19', '2025-07-17'),
(205, 11, 9, 18, '2025-04-11 21:14:27', '2025-05-23'),
(206, 13, 9, 9, '2025-02-14 00:47:09', '2025-06-27'),
(207, 11, 9, 20, '2025-01-04 09:52:45', '2025-06-25'),
(208, 12, 9, 10, '2025-04-05 06:23:56', '2025-06-24'),
(209, 10, 9, 2, '2025-04-23 11:36:52', '2025-06-04');

-- --------------------------------------------------------

--
-- Table structure for table `match_history`
--

CREATE TABLE `match_history` (
  `matchid` int(11) NOT NULL,
  `cleanerid` int(11) DEFAULT NULL,
  `homeownerid` int(11) DEFAULT NULL,
  `serviceid` int(11) DEFAULT NULL,
  `match_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `match_history`
--

INSERT INTO `match_history` (`matchid`, `cleanerid`, `homeownerid`, `serviceid`, `match_date`) VALUES
(1, 1, 61, 58, '2025-03-22'),
(2, 32, 86, 65, '2025-02-23'),
(3, 16, 51, 21, '2025-05-02'),
(4, 46, 90, 78, '2025-05-15'),
(5, 47, 77, 39, '2025-01-10'),
(6, 45, 79, 92, '2025-03-23'),
(7, 22, 82, 21, '2025-01-24'),
(8, 2, 78, 63, '2025-02-04'),
(9, 50, 82, 86, '2025-01-29'),
(10, 27, 70, 29, '2025-01-14'),
(11, 35, 73, 10, '2025-01-16'),
(12, 30, 58, 69, '2025-01-13'),
(13, 25, 71, 50, '2025-02-15'),
(14, 5, 59, 93, '2025-04-02'),
(15, 36, 83, 86, '2025-02-20'),
(16, 20, 84, 2, '2025-05-03'),
(17, 14, 70, 22, '2025-01-14'),
(18, 25, 88, 28, '2025-02-22'),
(19, 42, 77, 92, '2025-01-07'),
(20, 15, 56, 83, '2025-02-28'),
(21, 17, 82, 83, '2025-03-12'),
(22, 22, 52, 64, '2025-03-16'),
(23, 7, 74, 64, '2025-02-13'),
(24, 13, 59, 70, '2025-02-20'),
(25, 37, 63, 95, '2025-03-05'),
(26, 28, 70, 91, '2025-02-19'),
(27, 17, 57, 21, '2025-03-07'),
(28, 27, 51, 58, '2025-05-08'),
(29, 7, 60, 32, '2025-05-06'),
(30, 8, 81, 27, '2025-01-19'),
(31, 18, 56, 60, '2025-01-23'),
(33, 47, 81, 52, '2025-04-08'),
(34, 6, 9, 17, '2025-03-30'),
(35, 13, 9, 18, '2025-01-01'),
(36, 6, 9, 20, '2025-03-22'),
(37, 10, 9, 18, '2025-03-24'),
(38, 13, 9, 19, '2025-02-01'),
(39, 14, 9, 22, '2025-01-07'),
(40, 11, 9, 8, '2025-01-23'),
(41, 6, 9, 16, '2025-02-17'),
(42, 6, 9, 16, '2025-02-06'),
(43, 6, 9, 8, '2025-04-05'),
(44, 12, 9, 19, '2025-01-07'),
(45, 12, 9, 21, '2025-04-25'),
(46, 13, 9, 18, '2025-03-30'),
(47, 6, 9, 16, '2025-02-14'),
(48, 14, 9, 18, '2025-05-17'),
(49, 7, 9, 2, '2025-01-13'),
(50, 6, 9, 18, '2025-03-17'),
(51, 10, 9, 17, '2025-01-03'),
(52, 10, 9, 8, '2025-02-10'),
(53, 6, 9, 8, '2025-05-04'),
(54, 11, 9, 9, '2025-01-18'),
(55, 14, 9, 2, '2025-01-06'),
(56, 13, 9, 9, '2025-01-12'),
(57, 7, 9, 2, '2025-03-31'),
(58, 12, 9, 10, '2025-04-29'),
(59, 10, 9, 17, '2025-02-08'),
(60, 10, 9, 21, '2025-01-28'),
(61, 11, 9, 17, '2025-04-18'),
(62, 6, 9, 22, '2025-01-19'),
(63, 11, 9, 21, '2025-03-04'),
(64, 7, 9, 17, '2025-03-09'),
(65, 7, 9, 16, '2025-04-01'),
(66, 6, 9, 16, '2025-02-08'),
(67, 7, 9, 16, '2025-01-12'),
(68, 11, 9, 19, '2025-01-27'),
(69, 10, 9, 10, '2025-02-08'),
(70, 12, 9, 9, '2025-04-09'),
(71, 14, 9, 22, '2025-01-08'),
(72, 13, 9, 9, '2025-04-27'),
(73, 11, 9, 19, '2025-03-14'),
(74, 7, 9, 9, '2025-05-15'),
(75, 12, 9, 19, '2025-05-12'),
(76, 13, 9, 22, '2025-01-06'),
(77, 10, 9, 19, '2025-05-09'),
(78, 7, 9, 16, '2025-03-20'),
(79, 14, 9, 20, '2025-01-20'),
(80, 12, 9, 8, '2025-03-23'),
(81, 7, 9, 18, '2025-04-15'),
(82, 6, 9, 18, '2025-03-03'),
(83, 7, 9, 9, '2025-03-05'),
(84, 10, 9, 10, '2025-03-16'),
(85, 13, 9, 8, '2025-03-15'),
(86, 7, 9, 21, '2025-05-08'),
(87, 14, 9, 19, '2025-03-06'),
(88, 13, 9, 18, '2025-01-18'),
(89, 14, 9, 22, '2025-04-09'),
(90, 13, 9, 9, '2025-05-17'),
(91, 11, 9, 9, '2025-01-11'),
(92, 13, 9, 21, '2025-04-04'),
(93, 11, 9, 17, '2025-03-31'),
(94, 13, 9, 17, '2025-02-23'),
(95, 6, 9, 20, '2025-05-11'),
(96, 11, 9, 18, '2025-01-09'),
(97, 10, 9, 20, '2025-03-26'),
(98, 7, 9, 19, '2025-03-08'),
(99, 14, 9, 9, '2025-01-11'),
(100, 13, 9, 19, '2025-01-04'),
(101, 13, 9, 10, '2025-02-05'),
(102, 7, 9, 17, '2025-01-31'),
(103, 7, 9, 20, '2025-03-18'),
(104, 6, 9, 9, '2025-01-20'),
(105, 14, 9, 17, '2025-03-21'),
(106, 7, 9, 16, '2025-05-04'),
(107, 12, 9, 21, '2025-01-29'),
(108, 11, 9, 21, '2025-01-10'),
(109, 14, 9, 10, '2025-04-10'),
(110, 6, 9, 16, '2025-02-18'),
(111, 12, 9, 22, '2025-05-13'),
(112, 14, 9, 17, '2025-04-16'),
(113, 12, 9, 16, '2025-02-09'),
(114, 14, 9, 18, '2025-01-30'),
(115, 12, 9, 20, '2025-04-13'),
(116, 10, 9, 9, '2025-05-03'),
(117, 6, 9, 16, '2025-01-16'),
(118, 10, 9, 18, '2025-03-24'),
(119, 11, 9, 9, '2025-05-10'),
(120, 13, 9, 16, '2025-04-19'),
(121, 6, 9, 20, '2025-05-04'),
(122, 12, 9, 19, '2025-03-11'),
(123, 12, 9, 19, '2025-02-24'),
(124, 10, 9, 20, '2025-02-10'),
(125, 14, 9, 22, '2025-03-12'),
(126, 7, 9, 19, '2025-03-25'),
(127, 11, 9, 8, '2025-01-02'),
(128, 11, 9, 2, '2025-05-08'),
(129, 11, 9, 22, '2025-02-18'),
(130, 10, 9, 20, '2025-03-22'),
(131, 14, 9, 8, '2025-05-12'),
(132, 11, 9, 19, '2025-04-29'),
(133, 6, 9, 8, '2025-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `serviceid` int(11) NOT NULL,
  `cleanerid` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` enum('All-in-one','Floor','Laundry','Toilet','Window') NOT NULL,
  `view_count` int(11) DEFAULT 0,
  `shortlist_count` int(11) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `availability` enum('Mon-Fri 9AM-12PM','Mon-Fri 12PM-3PM','Mon-Fri 3PM-6PM','Mon-Fri 9AM-5PM','Mon-Sat 8AM-12PM','Weekends 10AM-4PM','Mon-Fri 1PM-6PM','Flexible by appointment') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`serviceid`, `cleanerid`, `title`, `description`, `price`, `category`, `view_count`, `shortlist_count`, `image_path`, `availability`, `created_at`) VALUES
(2, 6, 'waxonwaxoffzzz', 'waxonwaxoff', 1000.00, 'Floor', 12, 1, '', 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(8, 6, 'ss', 'ss', 111.00, 'All-in-one', 4, 1, NULL, 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(9, 6, 'Wax on Wax off', 'a', 1000.00, 'Floor', 3, 1, 'assets/images/68176e27f3b10_waxonwaxoff.png', 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(10, 7, 'Test Service', 'Description of test service', 50.00, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(16, 10, 'Deep Home Cleaning', 'A thorough cleaning of your entire home including kitchen, bathrooms, and living areas.', 150.00, 'All-in-one', 71, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-12 19:10:24'),
(17, 11, 'Carpet & Upholstery Cleaning', 'Expert steam cleaning of carpets and upholstered furniture.', 120.00, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-12 19:10:24'),
(18, 12, 'Bathroom Sanitization', 'High-grade sanitization and cleaning of bathrooms with eco-friendly products.', 90.00, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-12 19:10:24'),
(19, 13, 'Kitchen Degrease Service', 'Intensive kitchen cleaning including countertops, stove, hood and floors.', 110.00, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-12 19:10:24'),
(20, 14, 'Post-Renovation Cleanup', 'Complete cleaning after home renovation, including dust removal and disposal.', 200.00, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-12 19:10:24'),
(21, 6, 'hi', 'hi', 0.05, 'Floor', 2, 0, 'assets/images/6821f61ec11bd_68176e27f3b10_waxonwaxoff.png', '', '2025-05-12 21:22:38'),
(22, 6, 'sam ', 'sam', 2.00, '', 3, 0, 'assets/images/6821fa24a1781_68176e27f3b10_waxonwaxoff.png', '', '2025-05-12 21:39:48'),
(24, 16, 'Wash house', 'wash house', 100.00, 'All-in-one', 17, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:31:05'),
(25, 9, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 145.23, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(26, 7, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 103.56, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(27, 29, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 159.41, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(28, 38, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 157.31, 'Window', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(29, 40, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 98.72, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(30, 40, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 97.48, 'Window', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(31, 17, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 131.14, 'Toilet', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(32, 12, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 92.14, 'Floor', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(33, 40, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 145.95, 'Laundry', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(34, 14, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 69.63, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(35, 34, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 42.75, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(36, 29, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 126.99, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(37, 45, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 110.33, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(38, 49, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 99.33, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(39, 12, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 36.08, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(40, 18, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 113.94, 'Window', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(41, 3, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 63.38, 'Laundry', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(42, 39, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 78.48, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(43, 47, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 96.73, 'Laundry', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(44, 34, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 109.16, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(45, 26, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 144.45, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(46, 19, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 132.60, 'Toilet', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(47, 24, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 62.55, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(48, 43, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 174.03, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(49, 33, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 47.75, 'Laundry', 1, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(50, 12, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 104.73, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(51, 40, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 65.32, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(52, 29, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 147.94, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(53, 31, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 62.65, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(54, 31, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 32.25, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(55, 44, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 40.44, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(56, 8, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 142.22, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(57, 12, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 110.40, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(58, 29, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 143.38, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(59, 32, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 58.19, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(60, 35, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 110.82, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(61, 5, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 54.18, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(62, 44, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 48.39, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(63, 14, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 135.57, 'Laundry', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(64, 48, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 81.59, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(65, 8, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 68.15, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(66, 27, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 138.95, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(67, 31, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 103.89, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(68, 30, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 91.47, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(69, 26, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 55.33, 'Window', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(70, 34, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 171.39, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(71, 19, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 74.59, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(72, 23, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 163.24, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(73, 29, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 137.43, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(74, 12, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 109.79, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(75, 21, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 176.12, 'Laundry', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(76, 24, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 49.09, 'All-in-one', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(77, 23, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 159.30, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(78, 29, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 151.34, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(79, 35, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 79.65, 'Toilet', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(80, 19, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 148.65, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(81, 48, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 137.30, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(82, 32, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 119.97, 'Toilet', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(83, 50, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 92.46, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(84, 38, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 62.67, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(85, 5, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 105.35, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(86, 4, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 163.22, 'Toilet', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(87, 11, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 158.02, 'Laundry', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(88, 22, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 121.01, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(89, 36, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 122.39, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(90, 40, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 76.64, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(91, 36, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 131.23, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(92, 21, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 129.16, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(93, 34, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 122.89, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(94, 18, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 62.62, 'Window', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(95, 16, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 90.87, 'Toilet', 3, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(96, 37, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 148.00, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(97, 14, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 45.09, 'Window', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(98, 12, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 170.46, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(99, 49, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 34.13, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(100, 21, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 103.51, 'All-in-one', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(101, 39, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 160.70, 'Laundry', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(102, 19, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 77.76, 'Toilet', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(103, 23, 'Full Home Cleaning', 'Full Home Cleaning performed by experienced professionals using eco-friendly supplies.', 113.52, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(104, 17, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 155.79, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(105, 28, 'Kitchen Cleaning', 'Kitchen Cleaning performed by experienced professionals using eco-friendly supplies.', 110.79, 'Toilet', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(106, 33, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 98.72, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(107, 6, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 176.12, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(108, 18, 'Pet Hair Removal', 'Pet Hair Removal performed by experienced professionals using eco-friendly supplies.', 53.75, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(109, 37, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 90.09, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(110, 8, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 48.39, 'Laundry', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(111, 31, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 81.76, 'Toilet', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:41:50'),
(112, 25, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 159.25, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(113, 45, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 71.87, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(114, 47, 'Deep Cleaning', 'Deep Cleaning performed by experienced professionals using eco-friendly supplies.', 46.73, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(115, 37, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 40.76, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(116, 6, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 173.57, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(117, 34, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 68.77, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(118, 15, 'Window Washing', 'Window Washing performed by experienced professionals using eco-friendly supplies.', 175.73, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(119, 6, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 41.21, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(120, 4, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning performed by experienced professionals using eco-friendly supplies.', 63.74, 'Window', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:41:50'),
(121, 10, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 75.60, 'Toilet', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(122, 7, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 153.28, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:41:50'),
(123, 40, 'Bathroom Disinfection', 'Bathroom Disinfection performed by experienced professionals using eco-friendly supplies.', 49.37, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(124, 40, 'Laundry Service', 'Laundry Service performed by experienced professionals using eco-friendly supplies.', 143.51, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:41:50'),
(125, 14, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 81.53, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:42'),
(126, 12, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 81.51, 'Laundry', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:42'),
(127, 11, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 150.13, 'Floor', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:42'),
(128, 6, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 109.37, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:42'),
(129, 12, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 146.42, 'Window', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:42'),
(130, 13, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 113.76, 'Window', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:42'),
(131, 10, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 118.82, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:42'),
(132, 14, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 60.94, 'Toilet', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:42'),
(133, 14, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 111.49, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:42'),
(134, 13, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 105.31, 'All-in-one', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:42'),
(135, 13, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 115.19, 'Toilet', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:42'),
(136, 13, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 140.05, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:42'),
(137, 7, 'Pet Hair Removal', 'Pet Hair Removal provided by experienced cleaner. Guaranteed satisfaction.', 60.29, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:42'),
(138, 12, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 141.42, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:42'),
(139, 10, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 171.28, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:42'),
(140, 12, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 48.86, 'Floor', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:42'),
(141, 10, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 133.78, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:42'),
(142, 6, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 142.86, 'Window', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:42'),
(143, 6, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 147.03, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:42'),
(144, 12, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 163.59, 'Floor', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:42'),
(145, 11, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 78.57, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:42'),
(146, 12, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 53.65, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:42'),
(147, 6, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 162.00, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:42'),
(148, 10, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 143.49, 'Laundry', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:42'),
(149, 6, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 122.69, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:42'),
(150, 7, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 138.97, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:42'),
(151, 10, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 78.89, 'All-in-one', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:47:42'),
(152, 6, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 73.46, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:42'),
(153, 10, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 81.48, 'Laundry', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:43'),
(154, 12, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 58.79, 'All-in-one', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(155, 13, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 139.06, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:47:43'),
(156, 12, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 143.84, 'Toilet', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(157, 6, 'Window Washing', 'Window Washing provided by experienced cleaner. Guaranteed satisfaction.', 110.79, 'Floor', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(158, 14, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 136.26, 'Window', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(159, 7, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 114.30, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:43'),
(160, 7, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 129.77, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:43'),
(161, 10, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 110.03, 'Laundry', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(162, 7, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 106.61, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(163, 6, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 143.92, 'Window', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(164, 14, 'Pet Hair Removal', 'Pet Hair Removal provided by experienced cleaner. Guaranteed satisfaction.', 149.92, 'Floor', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(165, 10, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 66.41, 'Floor', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:43'),
(166, 12, 'Pet Hair Removal', 'Pet Hair Removal provided by experienced cleaner. Guaranteed satisfaction.', 97.68, 'All-in-one', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:47:43'),
(167, 10, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 52.77, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:43'),
(168, 11, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 93.60, 'All-in-one', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:47:43'),
(169, 7, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 126.11, 'Laundry', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(170, 13, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 115.51, 'All-in-one', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(171, 13, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 176.20, 'Floor', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(172, 14, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 98.74, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:43'),
(173, 11, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 98.30, 'Window', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(174, 12, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 88.58, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:47:43'),
(175, 11, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 170.72, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:43'),
(176, 14, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 44.39, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:43'),
(177, 10, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 86.12, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(178, 13, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 65.01, 'Laundry', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:43'),
(179, 7, 'Pet Hair Removal', 'Pet Hair Removal provided by experienced cleaner. Guaranteed satisfaction.', 49.51, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:43'),
(180, 12, 'Pet Hair Removal', 'Pet Hair Removal provided by experienced cleaner. Guaranteed satisfaction.', 160.23, 'Window', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(181, 11, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 144.28, 'Laundry', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(182, 11, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 68.91, 'Toilet', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:43'),
(183, 14, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 84.80, 'All-in-one', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(184, 7, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 76.35, 'All-in-one', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(185, 6, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 177.58, 'Floor', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(186, 7, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 108.21, 'Toilet', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(187, 7, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 69.40, 'Laundry', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:43'),
(188, 11, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 79.53, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(189, 13, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 109.73, 'Floor', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(190, 11, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 50.75, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(191, 14, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 175.55, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:43'),
(192, 14, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 66.48, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(193, 14, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 99.70, 'Toilet', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(194, 7, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 161.01, 'Toilet', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(195, 11, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 54.64, 'All-in-one', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-18 14:47:43'),
(196, 12, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 44.42, 'Window', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:43'),
(197, 14, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 67.46, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(198, 10, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 158.40, 'Window', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(199, 7, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 119.10, 'Window', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:43'),
(200, 7, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 125.95, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(201, 7, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 170.23, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:47:43'),
(202, 12, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 114.58, 'Toilet', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(203, 10, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 130.93, 'Window', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(204, 7, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 67.04, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(205, 13, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 144.69, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:43'),
(206, 11, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 148.98, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(207, 11, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 101.25, 'Laundry', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(208, 11, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 174.40, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-18 14:47:43'),
(209, 11, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 139.20, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(210, 6, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 109.26, 'Toilet', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(211, 14, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 160.26, 'Laundry', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(212, 6, 'Kitchen Cleaning', 'Kitchen Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 69.20, 'All-in-one', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(213, 11, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 159.92, 'Window', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(214, 11, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 74.77, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(215, 13, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 57.05, 'Window', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(216, 10, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 153.55, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-18 14:47:43'),
(217, 14, 'Pet Hair Removal', 'Pet Hair Removal provided by experienced cleaner. Guaranteed satisfaction.', 76.66, 'Toilet', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(218, 10, 'Bathroom Disinfection', 'Bathroom Disinfection provided by experienced cleaner. Guaranteed satisfaction.', 174.23, 'Window', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(219, 14, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 140.06, 'Laundry', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-18 14:47:43'),
(220, 12, 'Laundry Service', 'Laundry Service provided by experienced cleaner. Guaranteed satisfaction.', 52.81, 'Window', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:43'),
(221, 10, 'Full Home Cleaning', 'Full Home Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 167.73, 'Toilet', 0, 0, NULL, 'Flexible by appointment', '2025-05-18 14:47:43'),
(222, 10, 'Move-In/Out Cleaning', 'Move-In/Out Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 69.11, 'Window', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(223, 11, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 106.02, 'All-in-one', 0, 0, NULL, 'Mon-Fri 12PM-3PM', '2025-05-18 14:47:43'),
(224, 13, 'Deep Cleaning', 'Deep Cleaning provided by experienced cleaner. Guaranteed satisfaction.', 81.89, 'All-in-one', 0, 0, NULL, 'Mon-Fri 3PM-6PM', '2025-05-18 14:47:43'),
(225, 16, 'wash house 2', 'wash house2', 200.00, 'All-in-one', 5, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-18 14:58:45');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `categoryid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`categoryid`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'All-in-one', '', '2025-05-12 17:59:17', '2025-05-12 15:14:46'),
(2, 'Floor', NULL, '2025-05-12 17:59:17', '2025-05-12 17:59:17'),
(3, 'Laundry', NULL, '2025-05-12 17:59:17', '2025-05-12 17:59:17'),
(4, 'Toilet', NULL, '2025-05-12 17:59:17', '2025-05-12 17:59:17'),
(5, 'Window', NULL, '2025-05-12 17:59:17', '2025-05-12 17:59:17'),
(7, 'Room', 'Clean room', '2025-05-12 13:12:55', '2025-05-12 13:12:55'),
(8, 'Deep Cleaning', '', '2025-05-12 14:41:38', '2025-05-12 14:41:38'),
(9, 'neww', 'new', '2025-05-12 15:12:02', '2025-05-12 15:12:39'),
(10, 'test', 'test', '2025-05-16 12:14:28', '2025-05-16 12:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `shortlists`
--

CREATE TABLE `shortlists` (
  `shortlistid` int(11) NOT NULL,
  `homeownerid` int(11) DEFAULT NULL,
  `cleanerid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shortlists`
--

INSERT INTO `shortlists` (`shortlistid`, `homeownerid`, `cleanerid`) VALUES
(10, 1, 6),
(11, 9, 6),
(12, 9, 7),
(14, 9, 10),
(22, 21, 16),
(24, 21, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'joonian1', 'joonian656@gmail.com', '$2y$10$4VxyHkzWH5cbr7kehXVjzu8LwCyslJgTi91djB9f9Fnb8ogdsQ7Xy', 'Admin', 'suspended', '2025-05-12 19:10:24'),
(2, 'admin111', 'admin1@email.com', '$2y$10$baT7Tw5OpYsQ/6QQQAhs2OCkJ8cx0d1NnaYwl3pgCTGFaDxUGoqDO', 'Admin', 'suspended', '2025-05-12 19:10:24'),
(3, 'admin2', 'admin2@email.com', '$2y$10$q0OXMreJhU/GKvxFH8iGTu554cPIPvfALLfAU4xdMa0HmDWuVWvxi', 'Admin', 'suspended', '2025-05-12 19:10:24'),
(4, 'admin3', 'admin3@email.com', '$2y$10$QzhAfTVkSAdUPI5ENcmXwu2mZHFMfGfxnWoe.01rBcV.Lyq8da9T.', 'Admin', 'active', '2025-05-12 19:10:24'),
(5, 'admin4', 'admin4@email.com', '$2y$10$sOCJjWf68vHZjNkWuBWtp.PiNB3f/FDE1mXf92fsoVNqjlqvBnJUC', 'Admin', 'active', '2025-05-12 19:10:24'),
(6, 'cleaner', 'cleaner@gmail.com', '$2y$10$/zfv1vdwwyBbrZEcc6Es1e7t6WESTh.13.Vo6prb5lAoytNHa7ds2', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(7, 'cleaner2', 'cleaner2@gmail.com', '$2y$10$Q6tPaFl17jCM3We/xxnrduEw8P1quLaEq1kiN5/up0shSLCO.iFle', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(8, 'cleaner_jacqueline68', 'cleaner_jacqueline68@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(9, 'homeowner', 'homeowner@gmail.com', '$2y$10$xNz07zz0K7iXU79vfmLhEeF7KtMiYIs/tRZnbm9uaHvBAK8n5b9FC', 'Homeowner', 'active', '2025-05-12 19:10:24'),
(10, 'alice_wong', 'alice.wong@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(11, 'john_doe', 'john.doe@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'suspended', '2025-05-12 19:10:24'),
(12, 'maria_gomez', 'maria.gomez@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(13, 'daniel_lee', 'daniel.lee@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(14, 'sophia_tan', 'sophia.tan@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(15, 'manager', 'manager@example.com', '$2y$10$JTH95rzGf0luM4Vole867OHcoLtvjjO5hnySBvrlweI3s8Y6E8Az6', 'Manager', 'active', '2025-05-12 19:10:24'),
(16, 'cleaner3', 'cleaner3@gmail.com', '$2y$10$9S864tDcumFAWv8YtfOCJuO0zqxMq/LPQovJb/cf8IwdQNyBC42Cm', 'Cleaner', 'active', '2025-05-13 19:34:59'),
(17, 'homerowner3', 'homeowner3@email.com', '$2y$10$2qVi4fN65gWJB.GekEuIi.fWuuJxjDhyhe0CrnFC./26EWyx60H6a', 'Homeowner', 'active', '2025-05-13 19:35:13'),
(18, 'manager3', 'manager3@email.com', '$2y$10$zPmDBzd2h2p0g2jf/NTnnOXhLN17CgKQ8ODo4LVLhe73nV2Ym8L/O', 'Manager', 'active', '2025-05-13 19:35:22'),
(19, 'manager1', 'manager1@email.com', '$2y$10$Zg3F/tY8JhwBt8DA9O4sueYWId1FG7XrQIfN69fAV3BayvOloQpIC', 'Manager', 'active', '2025-05-15 00:20:44'),
(20, 'owner1', 'owner1@email.com', '$2y$10$MD9OVGCkMW8033cb3DphZe3YXao6UDmjaF61KwC20k5.WT5z3jVl6', 'Homeowner', 'active', '2025-05-16 20:32:51'),
(21, 'owner3', 'owner3@email.com', '$2y$10$gKl7thrJzqVsstFq7.UolecqW6q.3Bct9du3fPv8JqWpYQKUJwXpy', 'Homeowner', 'active', '2025-05-16 20:33:41'),
(22, 'cleaner_jonathan39', 'cleaner_jonathan39@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(23, 'cleaner_george2', 'cleaner_george2@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(24, 'cleaner_angela53', 'cleaner_angela53@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(25, 'cleaner_andrea33', 'cleaner_andrea33@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(26, 'cleaner_natalie29', 'cleaner_natalie29@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(27, 'cleaner_amy16', 'cleaner_amy16@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(28, 'cleaner_katherine85', 'cleaner_katherine85@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(29, 'cleaner_madison11', 'cleaner_madison11@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(30, 'cleaner_sara84', 'cleaner_sara84@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(31, 'cleaner_kevin40', 'cleaner_kevin40@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(32, 'cleaner_kathleen40', 'cleaner_kathleen40@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(33, 'cleaner_david65', 'cleaner_david65@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(34, 'cleaner_sandra1', 'cleaner_sandra1@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(35, 'cleaner_raymond42', 'cleaner_raymond42@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(36, 'cleaner_tracey14', 'cleaner_tracey14@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(37, 'cleaner_austin40', 'cleaner_austin40@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(38, 'cleaner_susan56', 'cleaner_susan56@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(39, 'cleaner_olivia54', 'cleaner_olivia54@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(40, 'cleaner_steve12', 'cleaner_steve12@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(41, 'cleaner_ashley51', 'cleaner_ashley51@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(42, 'cleaner_george35', 'cleaner_george35@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(43, 'cleaner_michelle67', 'cleaner_michelle67@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(44, 'cleaner_erica60', 'cleaner_erica60@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(45, 'cleaner_alexis92', 'cleaner_alexis92@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(46, 'cleaner_dustin27', 'cleaner_dustin27@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(47, 'cleaner_john71', 'cleaner_john71@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(48, 'cleaner_patrick26', 'cleaner_patrick26@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(49, 'cleaner_andrew82', 'cleaner_andrew82@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(50, 'cleaner_travis85', 'cleaner_travis85@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:38:14'),
(51, 'homeowner_laura9', 'homeowner_laura9@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:38:14'),
(52, 'cleaner_catherine100', 'cleaner_catherine100@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(53, 'cleaner_elizabeth19', 'cleaner_elizabeth19@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(54, 'cleaner_tracy95', 'cleaner_tracy95@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(55, 'cleaner_david53', 'cleaner_david53@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(56, 'cleaner_rodney85', 'cleaner_rodney85@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(57, 'cleaner_mark32', 'cleaner_mark32@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(58, 'cleaner_michael50', 'cleaner_michael50@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(59, 'cleaner_heather4', 'cleaner_heather4@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(60, 'cleaner_lynn92', 'cleaner_lynn92@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(61, 'cleaner_willie10', 'cleaner_willie10@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(62, 'cleaner_amber11', 'cleaner_amber11@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(63, 'cleaner_james78', 'cleaner_james78@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(64, 'cleaner_shawn56', 'cleaner_shawn56@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(65, 'cleaner_andrew57', 'cleaner_andrew57@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(66, 'cleaner_ann15', 'cleaner_ann15@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(67, 'cleaner_dustin48', 'cleaner_dustin48@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(68, 'cleaner_andrew59', 'cleaner_andrew59@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(69, 'cleaner_jessica33', 'cleaner_jessica33@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(70, 'cleaner_jennifer26', 'cleaner_jennifer26@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(71, 'cleaner_matthew21', 'cleaner_matthew21@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(72, 'cleaner_kathleen73', 'cleaner_kathleen73@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(73, 'cleaner_juan85', 'cleaner_juan85@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(74, 'cleaner_megan72', 'cleaner_megan72@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(75, 'cleaner_mary25', 'cleaner_mary25@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(76, 'cleaner_diamond28', 'cleaner_diamond28@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(77, 'cleaner_raymond58', 'cleaner_raymond58@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(78, 'cleaner_marc64', 'cleaner_marc64@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(79, 'cleaner_joseph68', 'cleaner_joseph68@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(80, 'cleaner_lisa98', 'cleaner_lisa98@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(81, 'cleaner_jonathan90', 'cleaner_jonathan90@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(82, 'cleaner_shawn6', 'cleaner_shawn6@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(83, 'cleaner_patricia25', 'cleaner_patricia25@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(84, 'cleaner_carlos99', 'cleaner_carlos99@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(85, 'cleaner_david43', 'cleaner_david43@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(86, 'cleaner_jacqueline29', 'cleaner_jacqueline29@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(87, 'cleaner_matthew54', 'cleaner_matthew54@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(88, 'cleaner_michelle73', 'cleaner_michelle73@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(89, 'cleaner_jill34', 'cleaner_jill34@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(90, 'cleaner_bobby91', 'cleaner_bobby91@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(91, 'cleaner_jacob10', 'cleaner_jacob10@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(92, 'cleaner_april71', 'cleaner_april71@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(93, 'cleaner_michael81', 'cleaner_michael81@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(94, 'cleaner_hannah56', 'cleaner_hannah56@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(95, 'cleaner_timothy76', 'cleaner_timothy76@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(96, 'cleaner_jerry80', 'cleaner_jerry80@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(97, 'cleaner_charles70', 'cleaner_charles70@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(98, 'cleaner_ruben70', 'cleaner_ruben70@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(99, 'cleaner_james85', 'cleaner_james85@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(100, 'cleaner_jill70', 'cleaner_jill70@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(101, 'cleaner_andrea30', 'cleaner_andrea30@cleaninghub.com', '$2y$10$dummyhash', 'Cleaner', 'active', '2025-05-18 14:40:30'),
(102, 'homeowner_victoria85', 'homeowner_victoria85@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(103, 'homeowner_megan68', 'homeowner_megan68@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(104, 'homeowner_rebekah68', 'homeowner_rebekah68@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(105, 'homeowner_tyler66', 'homeowner_tyler66@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(106, 'homeowner_kevin94', 'homeowner_kevin94@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(107, 'homeowner_jacob66', 'homeowner_jacob66@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(108, 'homeowner_scott27', 'homeowner_scott27@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(109, 'homeowner_ryan33', 'homeowner_ryan33@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(110, 'homeowner_ashley21', 'homeowner_ashley21@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(111, 'homeowner_jeffery78', 'homeowner_jeffery78@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(112, 'homeowner_kristin48', 'homeowner_kristin48@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(113, 'homeowner_stephen42', 'homeowner_stephen42@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(114, 'homeowner_veronica30', 'homeowner_veronica30@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(115, 'homeowner_joseph36', 'homeowner_joseph36@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(116, 'homeowner_james22', 'homeowner_james22@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(117, 'homeowner_steven6', 'homeowner_steven6@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(118, 'homeowner_ryan76', 'homeowner_ryan76@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(119, 'homeowner_raven98', 'homeowner_raven98@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(120, 'homeowner_erin45', 'homeowner_erin45@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(121, 'homeowner_tyler25', 'homeowner_tyler25@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(122, 'homeowner_william64', 'homeowner_william64@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(123, 'homeowner_tracy23', 'homeowner_tracy23@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(124, 'homeowner_robert67', 'homeowner_robert67@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(125, 'homeowner_allison93', 'homeowner_allison93@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(126, 'homeowner_mallory90', 'homeowner_mallory90@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(127, 'homeowner_michelle31', 'homeowner_michelle31@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(128, 'homeowner_sandra3', 'homeowner_sandra3@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(129, 'homeowner_william95', 'homeowner_william95@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(130, 'homeowner_richard72', 'homeowner_richard72@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(131, 'homeowner_michael82', 'homeowner_michael82@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(132, 'homeowner_colleen87', 'homeowner_colleen87@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(133, 'homeowner_angela81', 'homeowner_angela81@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(134, 'homeowner_barbara94', 'homeowner_barbara94@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(135, 'homeowner_lori34', 'homeowner_lori34@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(136, 'homeowner_donald31', 'homeowner_donald31@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(137, 'homeowner_mark9', 'homeowner_mark9@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(138, 'homeowner_rebecca94', 'homeowner_rebecca94@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(139, 'homeowner_heather49', 'homeowner_heather49@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(140, 'homeowner_andrew57', 'homeowner_andrew57@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(141, 'homeowner_gregory51', 'homeowner_gregory51@cleaninghub.com', '$2y$10$dummyhash', 'Homeowner', 'active', '2025-05-18 14:40:30'),
(142, 'admin_julia76', 'admin_julia76@cleaninghub.com', '$2y$10$dummyhash', 'Admin', 'active', '2025-05-18 14:40:30'),
(143, 'admin_michael50', 'admin_michael50@cleaninghub.com', '$2y$10$dummyhash', 'Admin', 'active', '2025-05-18 14:40:30'),
(144, 'admin_amanda77', 'admin_amanda77@cleaninghub.com', '$2y$10$dummyhash', 'Admin', 'active', '2025-05-18 14:40:30'),
(145, 'admin_tim12', 'admin_tim12@cleaninghub.com', '$2y$10$dummyhash', 'Admin', 'active', '2025-05-18 14:40:30'),
(146, 'admin_christine22', 'admin_christine22@cleaninghub.com', '$2y$10$dummyhash', 'Admin', 'active', '2025-05-18 14:40:30'),
(147, 'manager_jessica49', 'manager_jessica49@cleaninghub.com', '$2y$10$dummyhash', 'Manager', 'active', '2025-05-18 14:40:30'),
(148, 'manager_richard32', 'manager_richard32@cleaninghub.com', '$2y$10$dummyhash', 'Manager', 'active', '2025-05-18 14:40:30'),
(149, 'manager_anthony53', 'manager_anthony53@cleaninghub.com', '$2y$10$dummyhash', 'Manager', 'active', '2025-05-18 14:40:30'),
(150, 'manager_lisa50', 'manager_lisa50@cleaninghub.com', '$2y$10$dummyhash', 'Manager', 'active', '2025-05-18 14:40:30'),
(151, 'manager_laura83', 'manager_laura83@cleaninghub.com', '$2y$10$dummyhash', 'Manager', 'active', '2025-05-18 14:40:30'),
(152, 'admin100', 'admin100@email.com', '$2y$10$YiSa6FbMnaRXD9u/hydQvOtp4VY4V.Nm7ewscFdb8m1U4W7T.Sf/.', 'Admin', 'suspended', '2025-05-18 14:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`profile_id`, `role`, `description`, `status`) VALUES
(1, 'Admin', 'System administrator with full privileges', 'active'),
(2, 'Cleaner', 'Provides cleaning services', 'active'),
(3, 'Homeowner', 'Books cleaning services', 'active'),
(4, 'Manager', 'Manages platform-level configurations and reports', 'active'),
(7, 'admin10', 'do 10x the work of a adminxx', 'suspended');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `confirmed_matches`
--
ALTER TABLE `confirmed_matches`
  ADD PRIMARY KEY (`matchid`);

--
-- Indexes for table `match_history`
--
ALTER TABLE `match_history`
  ADD PRIMARY KEY (`matchid`),
  ADD KEY `cleanerid` (`cleanerid`),
  ADD KEY `homeownerid` (`homeownerid`),
  ADD KEY `serviceid` (`serviceid`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`serviceid`),
  ADD KEY `cleanerid` (`cleanerid`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `shortlists`
--
ALTER TABLE `shortlists`
  ADD PRIMARY KEY (`shortlistid`),
  ADD KEY `homeownerid` (`homeownerid`),
  ADD KEY `cleanerid` (`cleanerid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `confirmed_matches`
--
ALTER TABLE `confirmed_matches`
  MODIFY `matchid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `match_history`
--
ALTER TABLE `match_history`
  MODIFY `matchid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `serviceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `shortlists`
--
ALTER TABLE `shortlists`
  MODIFY `shortlistid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `match_history`
--
ALTER TABLE `match_history`
  ADD CONSTRAINT `match_history_ibfk_1` FOREIGN KEY (`cleanerid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `match_history_ibfk_2` FOREIGN KEY (`homeownerid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `match_history_ibfk_3` FOREIGN KEY (`serviceid`) REFERENCES `services` (`serviceid`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`cleanerid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `shortlists`
--
ALTER TABLE `shortlists`
  ADD CONSTRAINT `shortlists_ibfk_1` FOREIGN KEY (`homeownerid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `shortlists_ibfk_2` FOREIGN KEY (`cleanerid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
