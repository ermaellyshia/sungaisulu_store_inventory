-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 28, 2026 at 09:40 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `convenience_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `created_at`) VALUES
(1, 'Beverages', '2026-05-28 09:35:04'),
(2, 'Snacks', '2026-05-28 09:35:04'),
(3, 'Instant Noodles', '2026-05-28 09:35:04'),
(4, 'Cigarettes', '2026-05-28 09:35:04'),
(5, 'Personal Care', '2026-05-28 09:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int DEFAULT NULL,
  `min_stock` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `category_id`, `min_stock`, `created_at`) VALUES
(1, 'Coca Cola Can', 2.50, 1, 20, '2026-05-28 09:35:05'),
(2, 'Pepsi Can', 2.50, 1, 20, '2026-05-28 09:35:05'),
(3, 'Sprite Can', 2.50, 1, 20, '2026-05-28 09:35:05'),
(4, 'Mr. Potato Chips', 3.80, 2, 15, '2026-05-28 09:35:05'),
(5, 'Super Ring Cheese', 2.20, 2, 15, '2026-05-28 09:35:05'),
(6, 'Maggi Curry', 4.50, 3, 30, '2026-05-28 09:35:05'),
(7, 'Maggi Chicken', 4.50, 3, 30, '2026-05-28 09:35:05'),
(8, 'Dunhill Red', 17.50, 4, 10, '2026-05-28 09:35:05'),
(9, 'Marlboro Gold', 18.00, 4, 10, '2026-05-28 09:35:05'),
(10, 'Lifebuoy Soap', 3.50, 5, 10, '2026-05-28 09:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `movement_id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quantity` int NOT NULL,
  `type` enum('in','out') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`movement_id`, `product_id`, `user_id`, `quantity`, `type`, `created_at`) VALUES
(1, 1, 1, 50, 'in', '2026-05-01 01:00:00'),
(2, 2, 1, 40, 'in', '2026-05-01 01:00:00'),
(3, 3, 1, 35, 'in', '2026-05-01 01:00:00'),
(4, 4, 1, 25, 'in', '2026-05-01 01:00:00'),
(5, 5, 1, 30, 'in', '2026-05-01 01:00:00'),
(6, 6, 1, 100, 'in', '2026-05-01 01:00:00'),
(7, 7, 1, 100, 'in', '2026-05-01 01:00:00'),
(8, 8, 1, 20, 'in', '2026-05-01 01:00:00'),
(9, 9, 1, 20, 'in', '2026-05-01 01:00:00'),
(10, 10, 1, 15, 'in', '2026-05-01 01:00:00'),
(11, 1, 2, -5, 'out', '2026-05-05 06:30:00'),
(12, 4, 3, -3, 'out', '2026-05-10 03:20:00'),
(13, 6, 2, -10, 'out', '2026-05-15 01:45:00'),
(14, 1, 1, 30, 'in', '2026-05-20 02:00:00'),
(15, 8, 3, -2, 'out', '2026-05-25 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `employee_id` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `phone`, `address`, `role`, `employee_id`, `password`, `created_at`) VALUES
(1, 'Admin Sulu', '0123456789', 'Sungai Sulu Headquarters', 'admin', NULL, 'admin123', '2026-05-28 09:35:04'),
(2, 'Ahmad Bin Abdullah', '0112345678', 'No 5, Jalan Sulu 1', 'employee', 'EMP001', NULL, '2026-05-28 09:35:04'),
(3, 'Siti Binti Rahman', '0118765432', 'No 12, Jalan Sulu 2', 'employee', 'EMP002', NULL, '2026-05-28 09:35:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_products_category` (`category_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_movements_product` (`product_id`),
  ADD KEY `idx_movements_date` (`created_at`),
  ADD KEY `idx_movements_type` (`type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD KEY `idx_users_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `movement_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
