-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 02:11 PM
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
-- Database: `ida`
--

-- --------------------------------------------------------

--
-- Table structure for table `driver_info`
--

CREATE TABLE `driver_info` (
  `id` int(11) NOT NULL,
  `driver_mode` varchar(50) NOT NULL,
  `driver_name` varchar(255) NOT NULL,
  `driver_photo` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dl_number` varchar(50) NOT NULL,
  `area_postal_code` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `license_expiry` date NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `signature_file` varchar(255) DEFAULT NULL,
  `signature_data` text DEFAULT NULL,
  `registration_date` datetime DEFAULT current_timestamp(),
  `staff_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_info`
--

INSERT INTO `driver_info` (`id`, `driver_mode`, `driver_name`, `driver_photo`, `phone`, `dl_number`, `area_postal_code`, `address`, `license_expiry`, `vehicle_type`, `signature_file`, `signature_data`, `registration_date`, `staff_id`) VALUES
(16, 'Standard', 'sandhya', 'driver_673d684b5a7d56.29727004.jpeg', '786541230', 'dl1234', '345678', 'knp', '0000-00-00', 'auto', 'signature_673d684b5a99e9.46600900.png', NULL, '2024-11-20 10:10:43', 'Staff003'),
(17, 'Standard', 's', 'driver_673d69c75a27a3.17500905.jpeg', '789654123', 'dl12', '258963', 'lko', '0000-00-00', 'bike', 'signature_673d69c75a5166.77963660.png', NULL, '2024-11-20 10:17:03', 'Staff005'),
(18, 'Standard', 'demo2', 'driver_673d6ac8a456c5.80919786.jpeg', '8965231470', 'dl123', '5263', 'knk', '0000-00-00', 'truck', 'signature_673d6ac8a48130.11738110.png', NULL, '2024-11-20 10:21:20', 'Staff005'),
(19, 'Standard', 'n', 'driver_6749943c9ddb62.68976019.jpeg', '987456321', 'kl85', '8965231470', 'lkok', '0000-00-00', 'bus', 'signature_6749943c9ee344.38150992.png', NULL, '2024-11-22 15:45:24', 'Staff003'),
(20, 'Standard', 'raj', 'driver_674994be2609b7.57831891.jpeg', '896541', 'ff52', '852', 'lkok', '0000-00-00', 'bus', 'signature_674994be267b72.79894176.png', NULL, '2024-11-29 15:47:34', 'Staff003'),
(21, 'Standard', 'ababa', 'driver_67499584c92090.77745704.jpeg', '9874520', 'ffd78', '4521', 'lkolk', '0000-00-00', 'truck', 'signature_67499584c95f80.72885572.png', NULL, '2024-11-29 15:50:52', 'Staff003'),
(22, 'Standard', 'aaaaa', 'driver_67499609ded741.90641563.jpeg', '3216598', 'hk52', '85201', 'lko', '0000-00-00', 'car', 'signature_67499609defff1.52186232.png', NULL, '2024-11-29 15:53:05', 'Staff003'),
(23, 'Standard', 'n_demo2', 'driver_675ad10b55bed8.90913052.jpeg', '9874563210', 'hj41', '125874', 'lkoo', '0000-00-00', 'truck', 'signature_675ad10b560729.13498202.png', NULL, '2024-12-12 17:33:23', 'Staff006');

-- --------------------------------------------------------

--
-- Table structure for table `forms_filled`
--

CREATE TABLE `forms_filled` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_links`
--

CREATE TABLE `group_links` (
  `id` int(11) NOT NULL,
  `whatsapp_link` varchar(255) NOT NULL,
  `telegram_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_links`
--

INSERT INTO `group_links` (`id`, `whatsapp_link`, `telegram_link`) VALUES
(1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_members`
--

CREATE TABLE `staff_members` (
  `id` int(11) UNSIGNED NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(60) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_members`
--

INSERT INTO `staff_members` (`id`, `staff_id`, `staff_name`, `email`, `phone`, `password`, `status`, `created_at`) VALUES
(27, 'Staff003', 'news', 'news@gmail.com', '789654', '12345', 'active', '2024-10-11 09:20:49'),
(30, 'Staff005', 'soni', 'soni@gmail.com', '896574123', '1234', 'active', '2024-10-14 08:33:54'),
(33, 'Staff006', 'demo2', 'demo2@gmail.com', '8965471230', '1234', 'active', '2024-12-12 11:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(25, 'Admin', 'admin@admin.com', '$2y$10$peh94Z.mZ28kkTxAw/RDPOaIrf69y/voEt5ss5y1frXA6EcvQ4vx.', '2024-09-27 05:57:10'),
(27, 'demo', 'demo@gmail.com', '$2y$10$V6lcJJCX88jR9sejW9U8Qug3y8cW68KEBxllH1ZSg.PMI330H7lPG', '2024-10-11 06:34:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `driver_info`
--
ALTER TABLE `driver_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms_filled`
--
ALTER TABLE `forms_filled`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `group_links`
--
ALTER TABLE `group_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `staff_members`
--
ALTER TABLE `staff_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `driver_info`
--
ALTER TABLE `driver_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `forms_filled`
--
ALTER TABLE `forms_filled`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_links`
--
ALTER TABLE `group_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_members`
--
ALTER TABLE `staff_members`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `forms_filled`
--
ALTER TABLE `forms_filled`
  ADD CONSTRAINT `forms_filled_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff_members` (`staff_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
