-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2025 at 12:56 PM
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
  `confirmed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirmed_matches`
--

INSERT INTO `confirmed_matches` (`matchid`, `cleanerid`, `homeownerid`, `serviceid`, `confirmed_at`) VALUES
(1, 6, 9, 9, '2025-05-09 18:42:26');

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
  `availability` enum('Mon-Fri 9AM-12PM','Mon-Fri 12PM-3PM','Mon-Fri 3PM-6PM','Weekend 9AM-12PM','Weekend 12PM-3PM','Weekend 3PM-6PM','Flexible') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`serviceid`, `cleanerid`, `title`, `description`, `price`, `category`, `view_count`, `shortlist_count`, `image_path`, `availability`) VALUES
(2, 6, 'waxonwaxoff', 'waxonwaxoff', 1000.00, 'Floor', 0, 0, NULL, 'Mon-Fri 9AM-12PM'),
(8, 6, 'ss', 'ss', 111.00, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM'),
(9, 6, 'Wax on Wax off', 'a', 1000.00, 'Floor', 0, 0, 'assets/images/68176e27f3b10_waxonwaxoff.png', 'Mon-Fri 9AM-12PM'),
(10, 6, 'adada', 'aaaadaa', 1.00, 'Floor', 0, 0, NULL, 'Flexible'),
(13, 6, 'bbblol', 'bbbhehe', 2.00, 'Laundry', 0, 0, 'assets/images/681da7e0009fb_waxonwaxoff.png', 'Mon-Fri 9AM-12PM');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `categoryid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`categoryid`, `name`) VALUES
(1, 'All-in-one'),
(2, 'Floor'),
(3, 'Laundry'),
(4, 'Toilet'),
(5, 'Window');

-- --------------------------------------------------------

--
-- Table structure for table `shortlists`
--

CREATE TABLE `shortlists` (
  `shortlistid` int(11) NOT NULL,
  `homeownerid` int(11) DEFAULT NULL,
  `cleanerid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `role`, `status`) VALUES
(1, 'joonian', 'joonian656@gmail.com', '$2y$10$4VxyHkzWH5cbr7kehXVjzu8LwCyslJgTi91djB9f9Fnb8ogdsQ7Xy', 'Admin', 'suspended'),
(2, 'admin1', 'admin1@email.com', '$2y$10$baT7Tw5OpYsQ/6QQQAhs2OCkJ8cx0d1NnaYwl3pgCTGFaDxUGoqDO', 'Admin', 'suspended'),
(3, 'admin2', 'admin2@email.com', '$2y$10$q0OXMreJhU/GKvxFH8iGTu554cPIPvfALLfAU4xdMa0HmDWuVWvxi', 'Admin', 'suspended'),
(4, 'admin3', 'admin3@email.com', '$2y$10$QzhAfTVkSAdUPI5ENcmXwu2mZHFMfGfxnWoe.01rBcV.Lyq8da9T.', 'Admin', 'active'),
(5, 'admin4', 'admin4@email.com', '$2y$10$sOCJjWf68vHZjNkWuBWtp.PiNB3f/FDE1mXf92fsoVNqjlqvBnJUC', 'Admin', 'active'),
(6, 'cleaner', 'cleaner@gmail.com', '$2y$10$/zfv1vdwwyBbrZEcc6Es1e7t6WESTh.13.Vo6prb5lAoytNHa7ds2', 'Cleaner', 'active'),
(7, 'cleaner2', 'cleaner2@gmail.com', '$2y$10$Q6tPaFl17jCM3We/xxnrduEw8P1quLaEq1kiN5/up0shSLCO.iFle', 'Cleaner', 'active'),
(8, 'cleanernum2', 'cleanernum2@gmail.com', '$2y$10$E4tdpld0jxuxqeevLDPUquzXPoWLta2nfPhxnlwWu89kGapAFdwje', 'Cleaner', 'active'),
(9, 'homeowner', 'homeowner@gmail.com', '$2y$10$9LA0ymxSeN4lsM0c2n2oDebnoALGA0c83a9OkhCDfxliodmY0r74u', 'Homeowner', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `confirmed_matches`
--
ALTER TABLE `confirmed_matches`
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `confirmed_matches`
--
ALTER TABLE `confirmed_matches`
  MODIFY `matchid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `serviceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shortlists`
--
ALTER TABLE `shortlists`
  MODIFY `shortlistid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `confirmed_matches`
--
ALTER TABLE `confirmed_matches`
  ADD CONSTRAINT `confirmed_matches_ibfk_1` FOREIGN KEY (`cleanerid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `confirmed_matches_ibfk_2` FOREIGN KEY (`homeownerid`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `confirmed_matches_ibfk_3` FOREIGN KEY (`serviceid`) REFERENCES `services` (`serviceid`);

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
