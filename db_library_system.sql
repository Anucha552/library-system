-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2025 at 07:59 AM
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
-- Database: `db_library_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL COMMENT 'ID หนังสือ',
  `title` varchar(255) NOT NULL COMMENT 'ชื่อหนังสือ',
  `author` varchar(100) DEFAULT NULL COMMENT 'ชื่อผู้แต่ง',
  `publisher` varchar(100) DEFAULT NULL COMMENT 'สำนักพิมพ์',
  `isbn` varchar(20) DEFAULT NULL COMMENT 'รหัส ISBN ของหนังสือ',
  `total_copies` int(11) DEFAULT NULL COMMENT 'จำนวนหนังสือ',
  `available_copies` int(11) DEFAULT NULL COMMENT 'จำนวนหนังสือที่ยังว่างให้ยืม',
  `created_at` date NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่เพิ่มหนังสือเข้าระบบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publisher`, `isbn`, `total_copies`, `available_copies`, `created_at`) VALUES
(1, 'คู่มือเรียนเขียนโปรแกรมภาษา C', 'ผศ. สัมพันธ์ จันทร์ดี', 'ไอดีซี พรีเมียร์, บจก.', '9786162001260', 10, 7, '2025-04-20'),
(4, 'โปรแกรมกราฟิก', 'ว. วินิจฉัยกุล', 'ซีเอ็ดยูเคชั่น, บมจ.', '9786160820733 ', 20, 17, '2025-04-22'),
(5, 'Photoshop Web Design +CD', 'วาณิช จรุงกิจอนันต์', 'ซิมพลิฟาย, สนพ.', '9786162620034 ', 3, 0, '2025-05-11'),
(6, 'พัฒนาเว็บไซต์ให้เหนือชั้นด้วย HTML5 & CSS3\r\n', 'รงค์ วงษ์สวรรค์', 'ซีเอ็ดยูเคชั่น, บมจ.', '9786160812233 ', 5, 4, '2025-06-19'),
(7, 'คู่มือสร้างเว็บด้วย HTML5 JavaScript + CSS3', 'กาญจนา นาคนันทน์', 'ซิมพลิฟาย, สนพ.', '9786162626470', 12, 12, '2025-06-20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL COMMENT 'ID การยืมหนังสือ',
  `user_id` int(11) NOT NULL COMMENT 'อ้างอิงถึงผู้ที่ยืมหนังสือ',
  `book_id` int(11) NOT NULL COMMENT 'อ้างอิงถึงหนังสือที่ถูกยืม',
  `borrow_date` date NOT NULL COMMENT 'วันที่เริ่มยืม',
  `return_date` date DEFAULT NULL COMMENT 'วันที่คืนหนังสือจริง',
  `due_date` date NOT NULL COMMENT 'กำหนดวันที่ต้องคืน',
  `status` enum('borrowed','returned','late') DEFAULT 'borrowed' COMMENT 'สถานะของการยืม เช่น ยังยืมอยู่, คืนแล้ว, คืนช้า',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'เวลาที่ทำรายการยืมหนังสือ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date`, `due_date`, `status`, `created_at`) VALUES
(1, 3, 1, '2025-04-22', NULL, '2025-04-22', 'late', '2025-04-22 08:58:05'),
(2, 3, 5, '2025-06-15', '2025-06-19', '2025-06-22', 'returned', '2025-06-15 03:50:38'),
(3, 3, 1, '2025-06-15', NULL, '2025-06-22', 'borrowed', '2025-06-15 03:58:45'),
(4, 3, 4, '2025-06-04', NULL, '2025-06-10', 'late', '2025-06-15 04:06:05'),
(5, 3, 4, '2025-06-16', NULL, '2025-06-23', 'borrowed', '2025-06-15 23:29:41'),
(6, 3, 1, '2025-06-16', '2025-06-19', '2025-06-23', 'returned', '2025-06-15 23:38:21'),
(7, 3, 1, '2025-06-16', '2025-06-19', '2025-06-23', 'returned', '2025-06-15 23:39:00'),
(8, 3, 5, '2025-06-16', '2025-06-19', '2025-06-23', 'returned', '2025-06-16 01:25:25'),
(9, 3, 4, '2025-06-16', '2025-06-16', '2025-06-23', 'returned', '2025-06-16 01:26:06'),
(10, 3, 1, '2025-06-16', '2025-06-19', '2025-06-23', 'returned', '2025-06-16 01:51:35'),
(11, 3, 4, '2025-06-16', '2025-06-19', '2025-06-23', 'returned', '2025-06-16 03:08:42'),
(12, 4, 5, '2025-06-19', '2025-06-19', '2025-06-26', 'returned', '2025-06-19 04:34:32'),
(13, 4, 1, '2025-06-19', NULL, '2025-06-26', 'borrowed', '2025-06-19 05:17:17'),
(14, 4, 4, '2025-06-19', '2025-06-19', '2025-06-26', 'returned', '2025-06-19 05:17:20'),
(15, 3, 5, '2025-06-19', NULL, '2025-06-26', 'borrowed', '2025-06-19 05:28:35'),
(16, 4, 1, '2025-06-12', NULL, '2025-06-18', 'late', '2025-06-19 05:37:22'),
(17, 4, 5, '2025-06-19', NULL, '2025-06-26', 'borrowed', '2025-06-19 05:39:52'),
(18, 4, 5, '2025-06-19', NULL, '2025-06-26', 'borrowed', '2025-06-19 05:41:12'),
(19, 4, 6, '2025-06-19', NULL, '2025-06-26', 'borrowed', '2025-06-19 05:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'ID ผู้ใช้งาน',
  `user_name` varchar(100) NOT NULL COMMENT 'ชื่อผู้ใช้งาน',
  `email` varchar(100) NOT NULL COMMENT 'E-mail ผู้ใช้งาน',
  `password` varchar(100) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
  `role` enum('admin','user') DEFAULT 'user' COMMENT 'ระดับสิทธิ์ผู้ใช้งาน',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่และเวลา ที่ผู้ใช้งานลงทะเบียน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$QLWbRFfU2GtniXg4kfR9ouVFhxcRAWqS0qLknqVqtlSkxOsW3kiS2', 'admin', '2025-04-14 03:16:37'),
(3, 'user1', 'user1@gmail.com', '$2y$10$V88yq.DGOAL1aFm0caCboO7bpzkdiZnYeu/7cW7vtsn.hRMGvUU8u', 'user', '2025-04-22 02:59:13'),
(4, 'user2', 'user2@gmail.com', '$2y$10$UM09YG13nIBSN97UaWaj.eHIQt9dUluWTu.GKXw2E9AtXn60ipxzy', 'user', '2025-06-19 04:07:49'),
(5, 'user3', 'user3@gmail.com', '$2y$10$GDNwKoyPRBFTXESpmP7FXOWnvrneKOP290QR2e5jLIyq6lE6EkFu6', 'user', '2025-06-19 05:47:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID หนังสือ', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID การยืมหนังสือ', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID ผู้ใช้งาน', AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
