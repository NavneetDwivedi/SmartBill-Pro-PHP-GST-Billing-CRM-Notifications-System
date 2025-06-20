-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 12:50 PM
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
-- Database: `gst`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gstin` varchar(20) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `incorporation_date` date DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `gstin`, `contact`, `email`, `address`, `company_name`, `pan`, `incorporation_date`, `city`, `state`, `website`) VALUES
(4, 'Navneet  new', '32132132', '654654654654', 'Navneet@mail.com', 'test', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Munnu', '89651987465', '12356465465', 'Munnu@mail.com', 'Gorakhpur, Uttar Pradesh', 'XYZ pvt. ltd.', '23132asdfs', '2025-06-19', 'Gorakhpur', 'Uttar Pradesh', '');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `place_of_supply` varchar(100) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reverse_charge` varchar(5) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `total_invoice_value` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_no`, `client_id`, `place_of_supply`, `invoice_date`, `due_date`, `payment_status`, `payment_method`, `reverse_charge`, `discount`, `total_invoice_value`) VALUES
(11, '#DST00001', 5, 'Rajasthan', '2025-06-19', '2025-06-20', 'Unpaid', 'Bank Transfer', 'No', 0.00, 6983.68),
(12, '#DST00012', 4, 'Rajasthan', '2025-06-20', '2025-06-21', 'Paid', 'Cash', 'No', 0.00, 9440.00),
(13, '#DST00013', 4, 'Rajasthan', '2025-06-19', '0000-00-00', 'Unpaid', 'Bank Transfer', 'No', 100.00, 1436.00),
(15, '#DST00014', 5, 'Uttar Pradesh', '2025-06-20', '2025-06-28', 'Unpaid', 'UPI', 'Yes', 15.00, 171128.36),
(16, '#DST00016', 5, 'Uttar Pradesh', '2025-06-20', '2025-06-20', 'Paid', 'Bank Transfer', 'No', 1000.00, 58375.68);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `sac_code` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `gst_percent` decimal(5,2) NOT NULL,
  `cgst` decimal(10,2) NOT NULL,
  `sgst` decimal(10,2) NOT NULL,
  `igst` decimal(10,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `sac_code`, `qty`, `rate`, `gst_percent`, `cgst`, `sgst`, `igst`, `total`, `created_at`) VALUES
(24, 11, 'Wordpress', 'WP56465', 1, 5456.00, 28.00, 0.00, 0.00, 1527.68, 6983.68, '2025-06-19 12:48:28'),
(25, 13, 'Wordpress', 'WP56465', 1, 1200.00, 28.00, 0.00, 0.00, 336.00, 1536.00, '2025-06-19 13:08:46'),
(26, 12, 'SEO', 'SEO87798', 1, 8000.00, 18.00, 0.00, 0.00, 1440.00, 9440.00, '2025-06-20 06:38:56'),
(31, 15, 'Wordpress', 'WP56465', 2, 5456.00, 28.00, 0.00, 0.00, 3055.36, 13967.36, '2025-06-20 09:29:11'),
(32, 15, 'php', 'php00', 3, 44400.00, 18.00, 0.00, 0.00, 23976.00, 157176.00, '2025-06-20 09:29:11'),
(33, 16, 'Wordpress', 'WP56465', 1, 5456.00, 28.00, 0.00, 0.00, 1527.68, 6983.68, '2025-06-20 09:35:45'),
(34, 16, 'php', 'php00', 1, 44400.00, 18.00, 0.00, 0.00, 7992.00, 52392.00, '2025-06-20 09:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `invoice_id`, `message`, `created_at`, `is_read`) VALUES
(10, 11, '#DST00001 for Munnu is due on 2025-06-20', '2025-06-20 16:20:27', 0),
(11, 12, '#DST00012 for Navneet  new is due on 2025-06-21', '2025-06-20 16:20:27', 0),
(12, 16, '#DST00016 for Munnu is due on 2025-06-20', '2025-06-20 16:20:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sac_code` varchar(50) DEFAULT NULL,
  `gst_percent` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rate` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sac_code`, `gst_percent`, `created_at`, `rate`) VALUES
(2, 'SEO', 'SEO87798', 18.00, '2025-06-19 07:17:13', 8000.00),
(3, 'Wordpress', 'WP56465', 28.00, '2025-06-19 07:17:37', 5456.00),
(4, 'php', 'php00', 18.00, '2025-06-19 07:40:02', 44400.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
