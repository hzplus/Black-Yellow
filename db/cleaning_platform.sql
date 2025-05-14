-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 06:43 PM
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
(9, 6, 9, 22, '2025-05-13 00:40:27', '2025-05-17');

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
(2, 6, 'waxonwaxoffzzz', 'waxonwaxoff', 1000.00, 'Floor', 7, 1, '', 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(8, 6, 'ss', 'ss', 111.00, 'All-in-one', 4, 1, NULL, 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(9, 6, 'Wax on Wax off', 'a', 1000.00, 'Floor', 3, 1, 'assets/images/68176e27f3b10_waxonwaxoff.png', 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(10, 7, 'Test Service', 'Description of test service', 50.00, 'All-in-one', 0, 0, NULL, 'Mon-Fri 9AM-12PM', '2025-05-12 19:10:24'),
(16, 10, 'Deep Home Cleaning', 'A thorough cleaning of your entire home including kitchen, bathrooms, and living areas.', 150.00, 'All-in-one', 66, 0, NULL, 'Mon-Fri 9AM-5PM', '2025-05-12 19:10:24'),
(17, 11, 'Carpet & Upholstery Cleaning', 'Expert steam cleaning of carpets and upholstered furniture.', 120.00, 'Floor', 0, 0, NULL, 'Weekends 10AM-4PM', '2025-05-12 19:10:24'),
(18, 12, 'Bathroom Sanitization', 'High-grade sanitization and cleaning of bathrooms with eco-friendly products.', 90.00, 'Toilet', 0, 0, NULL, 'Mon-Sat 8AM-12PM', '2025-05-12 19:10:24'),
(19, 13, 'Kitchen Degrease Service', 'Intensive kitchen cleaning including countertops, stove, hood and floors.', 110.00, 'Floor', 0, 0, NULL, 'Mon-Fri 1PM-6PM', '2025-05-12 19:10:24'),
(20, 14, 'Post-Renovation Cleanup', 'Complete cleaning after home renovation, including dust removal and disposal.', 200.00, 'All-in-one', 0, 0, NULL, 'Flexible by appointment', '2025-05-12 19:10:24'),
(21, 6, 'hi', 'hi', 0.05, 'Floor', 2, 0, 'assets/images/6821f61ec11bd_68176e27f3b10_waxonwaxoff.png', '', '2025-05-12 21:22:38'),
(22, 6, 'sam ', 'sam', 2.00, '', 3, 0, 'assets/images/6821fa24a1781_68176e27f3b10_waxonwaxoff.png', '', '2025-05-12 21:39:48');

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
(9, 'neww', 'new', '2025-05-12 15:12:02', '2025-05-12 15:12:39');

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
(14, 9, 10);

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
(1, 'joonian', 'joonian656@gmail.com', '$2y$10$4VxyHkzWH5cbr7kehXVjzu8LwCyslJgTi91djB9f9Fnb8ogdsQ7Xy', 'Admin', 'suspended', '2025-05-12 19:10:24'),
(2, 'admin111', 'admin1@email.com', '$2y$10$baT7Tw5OpYsQ/6QQQAhs2OCkJ8cx0d1NnaYwl3pgCTGFaDxUGoqDO', 'Admin', 'suspended', '2025-05-12 19:10:24'),
(3, 'admin2', 'admin2@email.com', '$2y$10$q0OXMreJhU/GKvxFH8iGTu554cPIPvfALLfAU4xdMa0HmDWuVWvxi', 'Admin', 'suspended', '2025-05-12 19:10:24'),
(4, 'admin3', 'admin3@email.com', '$2y$10$QzhAfTVkSAdUPI5ENcmXwu2mZHFMfGfxnWoe.01rBcV.Lyq8da9T.', 'Admin', 'active', '2025-05-12 19:10:24'),
(5, 'admin4', 'admin4@email.com', '$2y$10$sOCJjWf68vHZjNkWuBWtp.PiNB3f/FDE1mXf92fsoVNqjlqvBnJUC', 'Admin', 'active', '2025-05-12 19:10:24'),
(6, 'cleaner', 'cleaner@gmail.com', '$2y$10$/zfv1vdwwyBbrZEcc6Es1e7t6WESTh.13.Vo6prb5lAoytNHa7ds2', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(7, 'cleaner2', 'cleaner2@gmail.com', '$2y$10$Q6tPaFl17jCM3We/xxnrduEw8P1quLaEq1kiN5/up0shSLCO.iFle', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(9, 'homeowner', 'homeowner@gmail.com', '$2y$10$xNz07zz0K7iXU79vfmLhEeF7KtMiYIs/tRZnbm9uaHvBAK8n5b9FC', 'Homeowner', 'active', '2025-05-12 19:10:24'),
(10, 'alice_wong', 'alice.wong@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(11, 'john_doe', 'john.doe@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(12, 'maria_gomez', 'maria.gomez@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(13, 'daniel_lee', 'daniel.lee@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(14, 'sophia_tan', 'sophia.tan@example.com', '$2y$10$QXqKyowmlN6i3Yc5uBybfe3kK2Rb8NCIKsbGm5Hb2yZTjvVczmvX6', 'Cleaner', 'active', '2025-05-12 19:10:24'),
(15, 'manager', 'manager@example.com', '$2y$10$JTH95rzGf0luM4Vole867OHcoLtvjjO5hnySBvrlweI3s8Y6E8Az6', 'Manager', 'active', '2025-05-12 19:10:24');

-- --------------------------------------------------------

-- Table structure for table `user_profiles`

CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `user_profiles`

INSERT INTO `user_profiles` (`profile_id`, `role`, `description`, `status`) VALUES
(1, 'Admin', 'System administrator with full privileges', 'active'),
(2, 'Cleaner', 'Provides cleaning services', 'active'),
(3, 'Homeowner', 'Books cleaning services', 'active'),
(4, 'Manager', 'Manages platform-level configurations and reports', 'active');

-- Indexes for table `user_profiles`
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`);

-- AUTO_INCREMENT for table `user_profiles`
ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
  -- --------------------------------------------------------

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `confirmed_matches`
--
ALTER TABLE `confirmed_matches`
  MODIFY `matchid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `match_history`
--
ALTER TABLE `match_history`
  MODIFY `matchid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `serviceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `shortlists`
--
ALTER TABLE `shortlists`
  MODIFY `shortlistid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
