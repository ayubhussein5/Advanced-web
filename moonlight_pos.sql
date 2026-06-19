-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2026 at 04:25 PM
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
-- Database: `moonlight_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `stock_code` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp(),
  `recorded_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `stock_code`, `quantity`, `purchase_price`, `selling_price`, `supplier`, `date_recorded`, `recorded_by`) VALUES
(12, 'Laptop', 'LAP123', 1, 500.00, 650.00, 'TechWorld', '2025-10-11 12:51:16', 12),
(14, 'Keyboard', 'KEY789', 27, 15.00, 25.00, 'GadgetSupply', '2025-10-11 12:51:16', 12),
(19, 'External HDD', 'HDD753', 10, 60.00, 85.00, 'StoragePro', '2025-10-11 12:51:16', 12),
(21, 'Tablet4', 'TAB369', 300, 200.00, 280.00, 'MobileHub', '2025-10-11 12:51:16', 12),
(22, 'twest', 'tw34', 63, 899.00, 568.00, 'lolo', '2025-10-11 11:57:05', 1),
(27, 'USB Cable', 'USB6544', 45, 10.00, 440.00, 'StoragePro', '2025-10-16 07:51:11', 12),
(30, 'Gaming Headset', 'HSET99', 15, 35.50, 55.99, 'AudioMax', '2025-10-17 09:16:51', 12),
(33, 'Webcam HD', 'CAM003', 22, 18.99, 30.50, 'VisionTech', '2025-10-17 09:16:51', 12),
(34, 'Power Bank 10K', 'PBANK10', 40, 25.00, 40.00, 'MobileHub', '2025-10-17 09:16:51', 12),
(35, 'HDMI Cable 2m', 'HDMI2M', 75, 5.00, 12.00, 'CableCorp', '2025-10-17 09:16:51', 12),
(37, 'External Keyboard', 'KEXT80', 25, 17.00, 30.00, 'GadgetSupply', '2025-10-17 09:16:51', 12),
(38, 'Micro SD 64GB', 'SD64GB', 148, 8.00, 15.00, 'StoragePro', '2025-10-17 09:16:51', 12),
(43, 'phone', 'hhff', 23, 1000.00, 2000.00, 'ahmed', '2025-11-08 06:37:14', 12),
(44, 'table 4*6', 'TABxyz', 40, 4500.00, 5600.00, 'yare towetr', '2026-03-10 09:19:11', 12);

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) NOT NULL,
  `logout_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`id`, `user_id`, `login_time`, `ip_address`, `logout_time`) VALUES
(1, 1, '2025-09-18 18:49:25', '::1', '2025-09-18 19:51:11'),
(2, 1, '2025-09-18 18:56:34', '::1', '2025-09-18 19:56:35'),
(3, 1, '2025-09-18 19:01:10', '::1', '2025-09-18 20:03:18'),
(5, 1, '2025-09-19 02:47:54', '::1', '2025-09-19 03:48:07');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `sold_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `item_id`, `quantity_sold`, `sale_price`, `sale_date`, `sold_by`) VALUES
(3, 12, 34, 33.00, '2025-10-11 11:52:39', 12),
(4, 21, 7, 56.00, '2025-10-11 11:52:56', 12),
(5, 21, 8, 78.00, '2025-10-11 11:55:06', 12),
(6, 14, 1, 15000.00, '2025-10-14 06:19:57', 12),
(7, 19, 6, 85.00, '2025-10-16 08:12:51', 12),
(8, 14, 1, 25.00, '2025-10-16 08:29:09', 12),
(9, 19, 1, 85.00, '2025-10-16 08:49:18', 12),
(10, 21, 7, 280.00, '2025-10-16 09:14:43', 12),
(11, 21, 3, 280.00, '2025-10-16 09:15:10', 12),
(12, 38, 2, 100.00, '2025-10-21 08:04:28', 12),
(13, 35, 5, 12.00, '2025-11-08 08:10:34', 12),
(14, 12, 28, 566778.00, '2025-11-08 08:39:51', 12),
(15, 44, 5, 5600.00, '2026-03-10 11:19:37', 12),
(16, 22, 4, 100000.00, '2026-05-20 09:34:15', 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `created_at`) VALUES
(1, '@amazon', 'ayubhussein024@gmail.com', '0712345678', '$2y$10$o44q2gMviJqK8YTdwFcJEOuK4.qZKp8w4kmdaIHTPAZ1Rh3vdpmBC', '2025-09-18 19:40:16'),
(2, '@amazon', 'ayubh7037@gmail.com', '0712345678', '$2y$10$./GaG0n027ZKdZcCR2i/Puk39qXFFVfnYWZ.Jk2ptBVrxDhhe/mQu', '2025-09-18 19:46:58'),
(11, 'yussuf', 'yussuf23@gmail.com', '', '$2y$10$M99xjSgYN6qz0VuIylRVq.3HZMTt9npp4MoVogxjnp2FzDbdphHXu', '2025-10-11 06:14:32'),
(12, 'lolo', 'admin@mail.com6', '', '$2y$10$vD731uqOs7SSgcFx02lCv.sL71l/97/B/f7fH1SkIjCHhD3.ey2Oq', '2025-10-11 12:24:06'),
(13, 'rere', 'rrret23@gmail.com', '071234567890', '$2y$10$WFJ8zzR/dkfCFcArEWbdN.jJtznBEfusFkvjVn9zJHIbRGlEVpmT.', '2025-10-11 12:40:49'),
(14, 'rose', 'rose123@gmail.com', '0743140286', '$2y$10$1Ivb8chspiLf932bi.hdVupUd.2rKiw2XxxoLqs6kLzsClIrB42gS', '2025-10-14 06:16:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `stock_code` (`stock_code`),
  ADD KEY `recorded_by` (`recorded_by`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `sold_by` (`sold_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`sold_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
