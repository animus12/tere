-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2023 at 05:03 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aso`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_code` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `size` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_code`, `name`, `category`, `description`, `size`, `price`, `date_created`) VALUES
(1, '309874256222', 'Sample Shirt', 'Male', 'Sample only', 'MEDIUM', 500, '2020-11-04 09:51:01'),
(8, '248786943297', 'Try Damit', 'Female', 'Pang bata', 'LARGE', 200, '2023-04-01 15:20:09'),
(9, '812866022890', 'ngayon', 'Female', 'pangit', 'EXTRA SMALL', 12, '2023-04-01 20:55:29'),
(10, '804469072990', 'mark', 'Female', 'cutitpie', 'EXTRA LARGE', 1000, '2023-04-01 20:55:51'),
(11, '108213750895', 'pol', 'Female', 'pol', 'EXTRA LARGE', 1000, '2023-04-01 20:58:23'),
(12, '896793972322', 'Gabriela', 'Female', 'as', 'MEDIUM', 1111, '2023-04-01 21:00:52'),
(13, '891072227991', 'wewe', 'Female', 'wewe', 'SMALL', 2222, '2023-04-01 21:10:06'),
(14, '827658747152', 'popo', 'Female', 'popo', 'LARGE', 133, '2023-04-01 21:41:28'),
(15, '397300484471', 'papa', 'Female', 'papa', 'SMALL', 133, '2023-04-01 21:45:16'),
(18, '40651681137101', 'pilo', 'Male', 'dfdfdf', 'LARGE', 4, '2023-04-10 22:31:41'),
(29, '1685602230', 'kity', 'Male', 'dfdfdf', 'SMALL', 452, '2023-06-01 14:50:30'),
(30, '1685607280', 'fdfdf', 'Female', 'fdfdf', 'LARGE', 3, '2023-06-01 16:14:40'),
(31, '1685607369', 'ererr', 'Female', 'dfdf', 'MEDIUM', 77, '2023-06-01 16:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `receiving`
--

CREATE TABLE `receiving` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `total_cost` float NOT NULL,
  `inventory_ids` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` float NOT NULL,
  `amount_tendered` int(11) NOT NULL,
  `inventory_ids` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `total_amount`, `amount_tendered`, `inventory_ids`, `date_created`) VALUES
(197, 1, 3333, 4000, '476', '2023-05-28 19:52:07'),
(198, 1, 3000, 3200, '477', '2023-05-28 19:52:23'),
(199, 1, 12, 50, '478', '2023-05-28 19:52:43'),
(200, 1, 3333, 4000, '479', '2023-05-28 19:53:02'),
(201, 1, 4000, 5555, '480', '2023-05-28 20:54:40'),
(202, 1, 3000, 4000, '481', '2023-05-28 20:55:34'),
(203, 1, 2000, 3000, '482', '2023-05-28 20:55:54'),
(204, 1, 2222, 4343, '483', '2023-05-28 21:00:47'),
(205, 1, 420175, 2147483647, '493,494,495,496,497', '2023-05-28 21:07:20'),
(206, 1, 2930190, 443434434, '503,504,505,506,507', '2023-05-28 21:08:31'),
(207, 1, 1000, 3423, '518', '2023-05-31 15:06:17'),
(208, 1, 1000, 23232, '519', '2023-06-01 14:39:24'),
(209, 1, 1111, 3432, '521', '2023-06-01 14:57:08');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1= in,2=out',
  `qty` int(11) NOT NULL,
  `price` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `item_id`, `type`, `qty`, `price`, `date_created`) VALUES
(518, 10, 2, 1, 1000, '2023-05-31 15:06:17'),
(519, 10, 2, 1, 1000, '2023-06-01 14:39:24'),
(521, 12, 2, 1, 1111, '2023-06-01 14:57:08');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `contact`, `date_created`) VALUES
(1, 'ABC Apparel', 'CBD St., EFG City', '+6948 8542 623', '2020-11-04 09:33:26'),
(2, 'Men Apparel', 'Sample Address', '65524556', '2020-11-04 09:33:48'),
(3, 'Ladies Apparel', 'Company address', '65524556', '2020-11-04 09:34:15'),
(4, 'Trends Apparel', 'Sample Address', '8747808787', '2020-11-04 09:34:37'),
(5, 'cheicken', 'animus', '8445975', '2023-05-16 09:42:07');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'Clothing Store Management System', 'mypersonalacount4@gmail.com', '3434', '1677304680_haha.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1=Admin,2=Staff',
  `login_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `login_status`) VALUES
(1, 'admin', 'admin', '0192023a7bbd73250516f069df18b500', 1, 'no'),
(15, 'sharon', 'mari', 'd40b913237b22c538b948e7e44aeb9cf', 2, 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receiving`
--
ALTER TABLE `receiving`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `receiving`
--
ALTER TABLE `receiving`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
