-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS cleaning_platform;
USE cleaning_platform;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample users (can be removed or edited)
INSERT INTO `users` (`userid`, `username`, `email`, `password`, `role`, `status`) VALUES
(1, 'joonian', 'joonian656@gmail.com', '$2y$10$4VxyHkzWH5cbr7kehXVjzu8LwCyslJgTi91djB9f9Fnb8ogdsQ7Xy', 'Admin', 'suspended'),
(2, 'admin1', 'admin1@email.com', '$2y$10$baT7Tw5OpYsQ/6QQQAhs2OCkJ8cx0d1NnaYwl3pgCTGFaDxUGoqDO', 'Admin', 'suspended'),
(3, 'admin2', 'admin2@email.com', '$2y$10$q0OXMreJhU/GKvxFH8iGTu554cPIPvfALLfAU4xdMa0HmDWuVWvxi', 'Admin', 'suspended'),
(4, 'admin3', 'admin3@email.com', '$2y$10$QzhAfTVkSAdUPI5ENcmXwu2mZHFMfGfxnWoe.01rBcV.Lyq8da9T.', 'Admin', 'active'),
(5, 'admin4', 'admin4@email.com', '$2y$10$sOCJjWf68vHZjNkWuBWtp.PiNB3f/FDE1mXf92fsoVNqjlqvBnJUC', 'Admin', 'active');

-- --------------------------------------------------------
-- Table structure for table `service_categories`
-- --------------------------------------------------------

CREATE TABLE `service_categories` (
  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample categories
INSERT INTO `service_categories` (`name`) VALUES
('All-in-one'), ('Floor'), ('Laundry'), ('Toilet'), ('Window');

-- --------------------------------------------------------
-- Table structure for table `services`
-- --------------------------------------------------------

CREATE TABLE `services` (
  `serviceid` int(11) NOT NULL AUTO_INCREMENT,
  `cleanerid` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `available_from` TIME NOT NULL,
  `available_to` TIME NOT NULL,
  `category` ENUM('All-in-one', 'Floor', 'Laundry', 'Toilet', 'Window') NOT NULL,
  PRIMARY KEY (`serviceid`),
  KEY `cleanerid` (`cleanerid`),
  CONSTRAINT `services_ibfk_1` FOREIGN KEY (`cleanerid`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `shortlists`
-- --------------------------------------------------------

CREATE TABLE `shortlists` (
  `shortlistid` int(11) NOT NULL AUTO_INCREMENT,
  `homeownerid` int(11) DEFAULT NULL,
  `cleanerid` int(11) DEFAULT NULL,
  PRIMARY KEY (`shortlistid`),
  KEY `homeownerid` (`homeownerid`),
  KEY `cleanerid` (`cleanerid`),
  CONSTRAINT `shortlists_ibfk_1` FOREIGN KEY (`homeownerid`) REFERENCES `users` (`userid`),
  CONSTRAINT `shortlists_ibfk_2` FOREIGN KEY (`cleanerid`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `match_history`
-- --------------------------------------------------------

CREATE TABLE `match_history` (
  `matchid` int(11) NOT NULL AUTO_INCREMENT,
  `cleanerid` int(11) DEFAULT NULL,
  `homeownerid` int(11) DEFAULT NULL,
  `serviceid` int(11) DEFAULT NULL,
  `match_date` date DEFAULT NULL,
  PRIMARY KEY (`matchid`),
  KEY `cleanerid` (`cleanerid`),
  KEY `homeownerid` (`homeownerid`),
  KEY `serviceid` (`serviceid`),
  CONSTRAINT `match_history_ibfk_1` FOREIGN KEY (`cleanerid`) REFERENCES `users` (`userid`),
  CONSTRAINT `match_history_ibfk_2` FOREIGN KEY (`homeownerid`) REFERENCES `users` (`userid`),
  CONSTRAINT `match_history_ibfk_3` FOREIGN KEY (`serviceid`) REFERENCES `services` (`serviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
