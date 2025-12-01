-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 03:35 PM
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
-- Database: `air_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `airline` varchar(100) NOT NULL,
  `origin` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `depart_time` varchar(50) NOT NULL,
  `passengers` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `flight_id`, `airline`, `origin`, `destination`, `depart_time`, `passengers`, `total_price`, `status`, `created_at`) VALUES
(1, 1, 'Test Airline', 'กรุงเทพฯ', 'เชียงใหม่', '08:00', 1, 1000.00, 'PENDING', '2025-11-30 14:50:02'),
(2, 0, 'Thai Airways', 'กรุงเทพฯ (BKK)', 'เชียงใหม่ (CNX)', '08:00', 2, 5000.00, 'PENDING', '2025-11-30 14:55:35'),
(3, 0, 'AirAsia', 'ดอนเมือง (DMK)', 'ภูเก็ต (HKT)', '10:00', 1, 1200.00, 'PENDING', '2025-11-30 14:56:19'),
(4, 0, 'Thai Airways', 'กรุงเทพฯ (BKK)', 'เชียงใหม่ (CNX)', '08:00', 1, 2500.00, 'PENDING', '2025-11-30 14:56:24'),
(5, 0, 'AirAsia', 'ดอนเมือง (DMK)', 'ภูเก็ต (HKT)', '10:00', 1, 1200.00, 'PAID', '2025-11-30 14:56:30'),
(6, 0, 'Thai Airways', 'กรุงเทพฯ (BKK)', 'เชียงใหม่ (CNX)', '08:00', 1, 2500.00, 'PENDING', '2025-11-30 14:58:06'),
(7, 0, 'AirAsia', 'ดอนเมือง (DMK)', 'ภูเก็ต (HKT)', '10:00', 1, 1200.00, 'PENDING', '2025-11-30 15:03:55'),
(8, 0, 'AirAsia', 'ดอนเมือง (DMK)', 'ภูเก็ต (HKT)', '10:00', 1, 1200.00, 'PAID', '2025-11-30 15:03:59'),
(9, 0, 'Thai Airways', 'กรุงเทพฯ (BKK)', 'เชียงใหม่ (CNX)', '08:00', 2, 5000.00, 'PAID', '2025-11-30 15:15:56'),
(10, 0, 'Thai Airways', 'กรุงเทพฯ (BKK)', 'เชียงใหม่ (CNX)', '08:00', 3, 7500.00, 'PAID', '2025-11-30 15:19:44'),
(11, 1, 'Thai Airways', 'กรุงเทพฯ (BKK)', 'เชียงใหม่ (CNX)', '08:00', 4, 10000.00, 'PAID', '2025-11-30 15:22:55'),
(12, 1, 'Thai Airways', 'กรุงเทพฯ (BKK)', 'เชียงใหม่ (CNX)', '08:00', 1, 2500.00, 'PAID', '2025-11-30 15:34:49');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `firstname`, `lastname`, `email`, `tel`, `username`, `password`, `created_at`) VALUES
(1, 'สมชาย', 'ใจดี', 'somchai.j@example.com', '0812345678', 'somchai', 'pass123', '2025-11-08 08:39:49'),
(2, 'สมหญิง', 'รักไทย', 'somying.r@example.com', '0987654321', 'somying', 'admin999', '2025-11-08 08:39:49'),
(3, 'ศุภกร', 'ป้อมเนียม', '@dfg.com', '12345', 'WP', '$2y$10$9WJdSvLyTNu4BJs0Pt2e7.C8377DozPl2FeL8r0IsSgOHD4iZpvSG', '2025-11-08 08:58:05'),
(4, '2', '2', '2@2', '2', '2', '1', '2025-11-08 09:02:23'),
(5, '1', '1', '1@1', '1', '1', '$2y$10$/lG6SfYlq6OCqZKXxF1Lm.Nx1xQybe0VuoiztpGkZ3zxzw3OPVoY6', '2025-12-01 14:28:57'),
(6, '3', '3', '3@3', '3', '3', '3', '2025-12-01 14:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `username`, `password`, `profile_picture`) VALUES
(18, 'จิรายุ', 'หิรัญวร', 'โอ้ตเด็กวัด', '$2y$10$faB03vrDW/S63kRJ.dC8ue6JvY2hvvOGiGNey4BmaKDAiU/Vbd1Oq', '1764598361_692da259e1c99.jpg'),
(19, 'ศุภกร', 'ป้อมเนียม', 'Auto', '$2y$10$C1xFGuLqckbgL51OEnNb9.LOw904deDrkcv8/OZ//YbrSluF6INEe', '1764598423_692da2971ef12.jpg'),
(20, 'สุเชษฎ', 'บุญมา', 'Win', '$2y$10$w4tJ.e6Q32ROPeos8U6tROaoqaHRrx0A8qZK/hIpm7Zcgy.EaOsoG', '1764598522_692da2fa14f3f.gif'),
(21, 'พิชญาภัค', 'คนชม', 'พิชชี่', '$2y$10$Kgom5ZwQDOEPuTJuljC6buQS.F1oWaV9C1fN.29Xuiv35mlUmChvi', '1764598597_692da34530884.webp');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` text DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `image`, `stock`, `created_at`) VALUES
(1, 'เสื้อยืดคอกลม', 'เสื้อยืดผ้าฝ้าย 100% สวมใส่สบาย', 199.00, 'tshirt.jpg', 50, '2025-09-19 17:29:36'),
(2, 'กางเกงยีนส์', 'กางเกงยีนส์ทรงกระบอก สีฟ้าอ่อน', 799.00, 'jeans.jpg', 30, '2025-09-19 17:29:36'),
(3, 'รองเท้าผ้าใบ', 'รองเท้าผ้าใบสีขาว ใส่ได้ทุกโอกาส', 1299.00, 'sneakers.jpg', 20, '2025-09-19 17:29:36'),
(5, 'เสื้อยืดคอกลม', 'เสื้อยืดผ้าฝ้าย 100% สวมใส่สบาย', 199.00, 'tshirt.jpg', 50, '2025-10-17 16:49:42'),
(6, 'กางเกงยีนส์', 'กางเกงยีนส์ทรงกระบอก สีฟ้าอ่อน', 799.00, 'jeans.jpg', 30, '2025-10-17 16:49:42'),
(7, 'รองเท้าผ้าใบ', 'รองเท้าผ้าใบสีขาว ใส่ได้ทุกโอกาส', 1299.00, 'sneakers.jpg', 20, '2025-10-17 16:49:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_detail_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
