-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2024 at 10:16 AM
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
-- Database: `raytech`
--

-- --------------------------------------------------------

--
-- Table structure for table `repair_bookings`
--

CREATE TABLE `repair_bookings` (
  `repair_bookings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repair_bookings`
--

INSERT INTO `repair_bookings` (`repair_bookings_id`, `user_id`, `service_id`, `service_name`, `description`, `preferred_date`, `status`, `created_at`, `updated_at`, `completed_date`) VALUES
(1, 2, 5, 'Hardware Diagnostics', NULL, NULL, 'Completed', '2024-10-01 06:46:58', '2024-10-01 08:29:04', NULL),
(2, 2, 4, 'Software Troubleshooting', NULL, NULL, 'Completed', '2024-10-01 06:54:19', '2024-10-01 08:19:16', NULL),
(3, 2, 2, 'Battery Replacement', 'romeo', '2024-10-08', 'Cancelled', '2024-10-01 08:24:07', '2024-10-01 10:22:31', NULL),
(4, 5, 2, 'Battery Replacement', '123123', '2024-10-11', 'Completed', '2024-10-01 08:37:10', '2024-10-01 08:37:39', NULL),
(5, 2, 4, 'Software Troubleshooting', '21312312', '2024-10-02', 'Completed', '2024-10-01 10:00:54', '2024-10-01 14:00:01', NULL),
(6, 2, 5, '', 'kasdkasdk', '2024-10-16', 'Cancelled', '2024-10-01 10:23:07', '2024-10-01 10:23:25', NULL),
(7, 2, 1, '', 'dasdasd', '2024-10-16', 'Completed', '2024-10-01 10:23:21', '2024-10-01 10:33:12', NULL),
(8, 2, 5, '', 'asdas', '2024-10-08', 'Cancelled', '2024-10-01 10:34:16', '2024-10-01 16:32:26', NULL),
(9, 2, 2, '', '12312', '2024-10-17', 'Cancelled', '2024-10-01 10:37:05', '2024-10-01 16:32:28', NULL),
(10, 2, 2, '', 'asd', '2024-10-07', 'Completed', '2024-10-01 10:37:50', '2024-10-01 14:00:08', NULL),
(11, 2, 2, '', 'asdasd', '2024-10-02', 'Completed', '2024-10-01 10:38:14', '2024-10-01 13:59:47', NULL),
(12, 2, 3, '', 'Naulanan di na mu on', '2024-10-14', 'Cancelled', '2024-10-01 13:58:26', '2024-10-01 16:32:24', NULL),
(13, 7, 2, '', 'iPhone 6s', '2024-10-08', 'Cancelled', '2024-10-01 14:01:57', '2024-10-01 17:37:38', NULL),
(14, 2, 2, '', 'Battery', '2024-10-10', 'Cancelled', '2024-10-01 16:33:15', '2024-10-01 18:57:16', NULL),
(15, 2, 5, '', 'asdasdasd', '2024-10-08', 'Cancelled', '2024-10-01 16:36:27', '2024-10-01 16:37:32', NULL),
(16, 7, 2, '', 'iPhone 16 Pro Max', '2024-09-30', 'Completed', '2024-10-01 17:45:09', '2024-10-01 18:46:21', NULL),
(17, 7, 5, '', 'My PC won\'t boot!', '2024-10-29', 'Completed', '2024-10-01 18:26:52', '2024-10-02 05:22:55', NULL),
(18, 2, 2, '', 'asdasdasdasdasd', '2024-10-23', 'Cancelled', '2024-10-01 18:57:23', '2024-10-01 19:04:49', NULL),
(19, 2, 2, '', 'booking test', '2024-10-09', 'Cancelled', '2024-10-01 20:03:03', '2024-10-01 20:04:38', NULL),
(20, 11, 2, '', 'Pa ilis kog battery sa motor', '2024-10-16', 'Cancelled', '2024-10-02 05:21:53', '2024-10-02 06:15:56', NULL),
(21, 11, 5, '', 'Test Description', '2024-10-16', 'Cancelled', '2024-10-02 06:16:13', '2024-10-03 08:04:49', NULL),
(22, 11, 6, '', 'Test2', '2024-10-24', 'Pending', '2024-10-02 06:17:42', '2024-10-02 06:17:42', NULL),
(23, 11, 1, '', 'dfsdfsdf', '2024-10-24', 'Completed', '2024-10-02 06:33:09', '2024-10-03 06:55:26', NULL),
(24, 11, 6, '', 'hdiasdhihaid', '2024-10-10', 'Cancelled', '2024-10-02 07:39:25', '2024-10-02 07:41:24', NULL),
(25, 11, 1, '', 'bbvnbvnbvbn', '2024-10-25', 'Pending', '2024-10-03 07:13:13', '2024-10-03 07:13:13', NULL),
(26, 13, 2, '', 'Battery Exploded', '2024-10-04', 'Cancelled', '2024-10-03 07:24:08', '2024-10-03 07:29:47', NULL),
(27, 11, 5, '', 'test', '2024-10-15', 'Pending', '2024-10-03 08:04:38', '2024-10-03 08:04:38', NULL),
(28, 11, 1, '', 'efefr', '2024-10-03', 'Pending', '2024-10-03 08:05:39', '2024-10-03 08:05:39', NULL),
(29, 11, 1, '', 'qwdwqdwd', '2024-10-03', 'Pending', '2024-10-03 08:06:05', '2024-10-03 08:06:05', NULL),
(30, 11, 1, '', 'dwdwdw', '2024-10-03', 'In Progress', '2024-10-03 08:06:27', '2024-10-03 08:13:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `repair_services`
--

CREATE TABLE `repair_services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `estimated_duration` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repair_services`
--

INSERT INTO `repair_services` (`service_id`, `service_name`, `price`, `description`, `estimated_duration`, `created_at`, `updated_at`) VALUES
(1, 'Screen Replacement', 2500.00, 'Replace damaged or cracked screen', 60, '2024-10-01 06:46:21', '2024-10-02 05:18:53'),
(2, 'Battery Replacement', 1200.00, 'Replace old or faulty battery', 30, '2024-10-01 06:46:21', '2024-10-02 05:19:06'),
(3, 'Water Damage Repair', 1200.00, 'Repair water-damaged device', 120, '2024-10-01 06:46:21', '2024-10-02 05:19:23'),
(4, 'Software Troubleshooting', 450.00, 'Diagnose and fix software issues', 45, '2024-10-01 06:46:21', '2024-10-02 05:19:33'),
(5, 'Hardware Diagnostics', 550.00, 'Diagnose hardware problems', 30, '2024-10-01 06:46:21', '2024-10-02 05:19:48'),
(6, 'Graphics Card Diagnosis & Repair', 3500.00, 'Diagnose GPU problems and issues', 30, '2024-10-01 06:46:21', '2024-10-02 05:19:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_role` enum('customer','technician') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `password_hash`, `created_at`, `user_role`) VALUES
(2, 'romeo69', 'romeo@gmail.com', '09262323233', '$2y$10$mR29lXPgclzkY/A0sc7R0ujshpWWlRegKufmUJHk7qmetKXMamXrW', '2024-10-01 06:33:27', 'customer'),
(4, 'admin', 'raytech@gmail.com', '09262353430', '$2y$10$IghXvVUJGretwqIZpVggXOuY4pBgOgkXyhjA.yGnt3SAB4ORD94/a', '2024-10-01 07:42:05', 'technician'),
(5, 'neilemborgo', 'neil@gmail.com', '09262353420', '$2y$10$KzNj3e.WA.Zjpi85h12JXOiU/p71NWHDXoRN387LhisKP5U10SKEK', '2024-10-01 08:35:18', 'customer'),
(6, 'chanlee', 'chanlee@gmail.com', '09262353410', '$2y$10$qkJtnJ5H2r2DJEKaGQ0p4eucYlcTGXgmI5A.slF4X19vMU8a6Ap1q', '2024-10-01 08:38:20', 'customer'),
(7, 'neilemborgo97', 'customer@gmail.com', '09262353450', '$2y$10$z9k11GddM3kB3QXDYEgXw.D6C.bwVNkFHKmWreMzVN9Hj2yqPax.u', '2024-10-01 14:01:14', 'customer'),
(8, 'neilemborgo1997', 'neilemborgo1997@gmail.com', '09262353400', '$2y$10$FWPXC7YKybI3W.Qi8f./ru1kijhDa/Ysd1HXz73mLzarqBHBKxMmS', '2024-10-01 19:57:24', 'customer'),
(9, 'james_go', 'jamesgo@gmail.com', '09123456789', '$2y$10$OXVDYy2/sLEU9/i2cEyPTehIwtsQudBiqFe2nQW8cBG/toHxwjJrW', '2024-10-01 19:59:51', 'customer'),
(10, 'lago29', 'lago@gmail.com', '09123456788', '$2y$10$h5I0r.dlrmKOptehlnLxEucRE5FoUtuBXIz.axmtAr9SXO2i2haG.', '2024-10-01 20:00:40', 'customer'),
(11, 'customer2', 'customer2@gmail.com', '09262352441', '$2y$10$VIurFsdmTegRbdsBrXdMMeDQS5VRikPjXxuGzXH8d4X6yhQMDVGxu', '2024-10-02 05:17:09', 'customer'),
(12, 'customer3', 'neil@customer.com', '09123456781', '$2y$10$gxTSxGPY5N4ga4tFf.hRb.nZMF6BlONKddpmeLqG86Nr5eRXfYUF.', '2024-10-03 07:12:35', 'customer'),
(13, 'grace', 'grace@gmail.com', '098765433', '$2y$10$T68x8PpfN9HEgFfWFcIM7.kVtM9CMohCiMn2ExsY72dEwVF2zjDoK', '2024-10-03 07:21:44', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `repair_bookings`
--
ALTER TABLE `repair_bookings`
  ADD PRIMARY KEY (`repair_bookings_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `repair_services`
--
ALTER TABLE `repair_services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `repair_bookings`
--
ALTER TABLE `repair_bookings`
  MODIFY `repair_bookings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `repair_services`
--
ALTER TABLE `repair_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `repair_bookings`
--
ALTER TABLE `repair_bookings`
  ADD CONSTRAINT `repair_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
