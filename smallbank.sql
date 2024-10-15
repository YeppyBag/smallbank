-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 15, 2024 at 04:09 PM
-- Server version: 10.11.8-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smallbank`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_fee`
--

CREATE TABLE `tb_fee` (
  `fee_id` int(11) NOT NULL,
  `amount_min` decimal(10,2) NOT NULL,
  `amount_max` decimal(10,2) NOT NULL,
  `fee_percentage` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_fee`
--

INSERT INTO `tb_fee` (`fee_id`, `amount_min`, `amount_max`, `fee_percentage`) VALUES
(1, 0.00, 99.99, 5.00),
(2, 100.00, 500.00, 5.00),
(3, 501.00, 1000.00, 4.00),
(4, 1001.00, 5000.00, 3.00),
(5, 0.00, 0.00, 0.01);

-- --------------------------------------------------------

--
-- Table structure for table `tb_permission`
--

CREATE TABLE `tb_permission` (
  `permission_id` int(1) NOT NULL,
  `permission_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_permission`
--

INSERT INTO `tb_permission` (`permission_id`, `permission_name`) VALUES
(0, 'user'),
(1, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_point`
--

CREATE TABLE `tb_point` (
  `point_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `expiration_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_point`
--

INSERT INTO `tb_point` (`point_id`, `user_id`, `points`, `expiration_date`, `created_at`) VALUES
(45, 1, 4480, '2024-10-17', '2024-10-14 05:39:31'),
(46, 1, 9480, '2024-10-17', '2024-10-14 06:20:14'),
(47, 1, 9480, '2024-10-17', '2024-10-14 06:20:39'),
(50, 1, 200000, '2024-10-18', '2024-10-15 12:16:25'),
(51, 9, 37, '2024-10-18', '2024-10-15 12:17:30'),
(52, 9, 400, '2024-10-18', '2024-10-15 12:18:42'),
(53, 1, 200000, '2024-10-18', '2024-10-15 12:38:28');

-- --------------------------------------------------------

--
-- Table structure for table `tb_point_transaction`
--

CREATE TABLE `tb_point_transaction` (
  `point_transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `point_amount` int(11) NOT NULL,
  `transaction_type_id` int(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_point_transaction`
--

INSERT INTO `tb_point_transaction` (`point_transaction_id`, `user_id`, `point_amount`, `transaction_type_id`, `created_at`, `transaction_id`) VALUES
(44, 1, 2000, 5, '2024-10-04 12:42:28', 116),
(45, 1, 2000, 6, '2024-10-04 12:42:52', 118),
(46, 1, 1000, 5, '2024-10-04 13:00:02', 119),
(47, 1, 1000, 5, '2024-10-04 13:00:06', 121),
(48, 1, 0, 6, '2024-10-04 13:07:38', 124),
(49, 1, 1000, 6, '2024-10-04 13:07:38', 124),
(50, 1, 1000, 6, '2024-10-04 13:07:38', 124),
(51, 1, 9480, 5, '2024-10-04 13:22:41', 125),
(52, 1, 9000, 6, '2024-10-04 13:23:00', 127),
(53, 6, 15, 5, '2024-10-06 03:02:49', 129),
(54, 1, 9480, 5, '2024-10-10 04:13:58', 132),
(55, 1, 9480, 5, '2024-10-14 05:39:31', 134),
(56, 1, 9480, 5, '2024-10-14 06:20:14', 138),
(57, 1, 9480, 5, '2024-10-14 06:20:39', 140),
(58, 1, 9480, 5, '2024-10-14 06:21:35', 142),
(59, 1, 5000, 6, '2024-10-14 11:11:10', 144),
(60, 9, 37, 5, '2024-10-15 12:15:12', 151),
(61, 1, 200000, 5, '2024-10-15 12:16:25', 153),
(62, 9, 2000, 5, '2024-10-15 12:17:31', 156),
(63, 9, 37, 6, '2024-10-15 12:18:42', 158),
(64, 9, 1963, 6, '2024-10-15 12:18:42', 158),
(65, 9, 400, 5, '2024-10-15 12:18:42', 158),
(66, 1, 200000, 5, '2024-10-15 12:38:28', 161);

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaction`
--

CREATE TABLE `tb_transaction` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(3) NOT NULL,
  `transaction_type_id` int(1) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `fee` decimal(5,2) DEFAULT 0.00,
  `fee_amount` decimal(10,2) DEFAULT 0.00,
  `recipient_user_id` int(3) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaction`
--

INSERT INTO `tb_transaction` (`transaction_id`, `user_id`, `transaction_type_id`, `amount`, `fee`, `fee_amount`, `recipient_user_id`, `created_at`) VALUES
(1, 1, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-02 10:54:10'),
(2, 1, 2, 50000.00, 0.01, 500.00, 2, '2024-10-02 10:54:47'),
(3, 2, 1, 50000.00, 0.00, 0.00, 1, '2024-10-02 10:54:47'),
(4, 1, 2, 1000.00, 0.01, 9.00, 2, '2024-10-02 10:55:01'),
(5, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-02 10:55:01'),
(6, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-02 11:43:11'),
(7, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-02 11:43:11'),
(8, 1, 3, 1000.00, 4.00, 40.00, NULL, '2024-10-02 12:33:24'),
(9, 1, 3, 2000.00, 3.00, 60.00, NULL, '2024-10-02 12:42:30'),
(10, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-02 12:43:47'),
(11, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-02 12:43:47'),
(12, 1, 3, 1000.00, 4.00, 40.00, NULL, '2024-10-02 12:44:08'),
(13, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-02 12:44:44'),
(14, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-02 12:44:44'),
(15, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-02 12:45:47'),
(16, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-02 12:45:47'),
(17, 1, 2, 400000.00, 0.01, 4000.00, 2, '2024-10-02 13:57:32'),
(18, 2, 1, 400000.00, 0.00, 0.00, 1, '2024-10-02 13:57:32'),
(19, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 04:24:07'),
(20, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 04:24:07'),
(21, 1, 2, 300000.00, 0.01, 3000.00, 2, '2024-10-03 07:33:27'),
(22, 2, 1, 300000.00, 0.00, 0.00, 1, '2024-10-03 07:33:27'),
(23, 2, 2, 1000000.00, 0.01, 10000.00, 1, '2024-10-03 07:35:36'),
(24, 1, 1, 1000000.00, 0.00, 0.00, 2, '2024-10-03 07:35:36'),
(25, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:52:45'),
(26, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:52:54'),
(27, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:53:01'),
(28, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:53:10'),
(29, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:53:16'),
(30, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:53:25'),
(31, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:53:36'),
(32, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:53:47'),
(33, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:53:55'),
(34, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:02'),
(35, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:11'),
(36, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:23'),
(37, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:29'),
(38, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:34'),
(39, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:40'),
(40, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:45'),
(41, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:54:56'),
(42, 5, 4, 100.00, 0.00, 0.00, NULL, '2024-10-03 11:55:23'),
(43, 5, 2, 6000.00, 0.01, 60.00, 4, '2024-10-03 11:56:35'),
(44, 4, 1, 6000.00, 0.00, 0.00, 5, '2024-10-03 11:56:35'),
(45, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:57:35'),
(46, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:57:36'),
(47, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:57:41'),
(48, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:57:45'),
(49, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:57:47'),
(50, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:57:53'),
(51, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:57:58'),
(52, 4, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:58:01'),
(53, 5, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-03 11:58:05'),
(54, 5, 2, 2000.00, 0.01, 20.00, 4, '2024-10-03 11:58:34'),
(55, 4, 1, 2000.00, 0.00, 0.00, 5, '2024-10-03 11:58:34'),
(56, 5, 4, 20.00, 0.00, 0.00, NULL, '2024-10-03 11:59:33'),
(57, 5, 2, 3000.00, 0.01, 30.00, 4, '2024-10-03 12:01:20'),
(58, 4, 1, 3000.00, 0.00, 0.00, 5, '2024-10-03 12:01:20'),
(59, 4, 2, 10000.00, 0.01, 100.00, 5, '2024-10-03 12:01:25'),
(60, 5, 1, 10000.00, 0.00, 0.00, 4, '2024-10-03 12:01:26'),
(61, 1, 2, 10000000.00, 0.01, 100000.00, 5, '2024-10-03 12:15:48'),
(62, 5, 1, 10000000.00, 0.00, 0.00, 1, '2024-10-03 12:15:48'),
(63, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 12:25:00'),
(64, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 12:25:00'),
(65, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 12:29:05'),
(66, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 12:29:06'),
(67, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 12:29:49'),
(68, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 12:29:49'),
(69, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 12:31:34'),
(70, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 12:31:34'),
(71, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 12:31:49'),
(72, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 12:31:49'),
(73, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 13:03:06'),
(74, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 13:03:06'),
(75, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 13:03:47'),
(76, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 13:03:47'),
(77, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 13:48:20'),
(78, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 13:48:20'),
(79, 2, 2, 100000.00, 0.01, 1000.00, 1, '2024-10-03 14:18:58'),
(80, 1, 1, 100000.00, 0.00, 0.00, 2, '2024-10-03 14:18:58'),
(81, 1, 2, 20000000.00, 0.01, 200000.00, 2, '2024-10-03 14:19:41'),
(82, 2, 1, 20000000.00, 0.00, 0.00, 1, '2024-10-03 14:19:41'),
(83, 1, 2, 20000000.00, 0.01, 200000.00, 2, '2024-10-03 14:20:04'),
(84, 2, 1, 20000000.00, 0.00, 0.00, 1, '2024-10-03 14:20:04'),
(85, 1, 2, 200000.00, 0.01, 2000.00, 2, '2024-10-03 14:20:25'),
(86, 2, 1, 200000.00, 0.00, 0.00, 1, '2024-10-03 14:20:25'),
(87, 2, 2, 100000.00, 0.01, 1000.00, 1, '2024-10-03 14:20:34'),
(88, 1, 1, 100000.00, 0.00, 0.00, 2, '2024-10-03 14:20:34'),
(89, 2, 2, 100000.00, 0.01, 1000.00, 1, '2024-10-03 14:21:28'),
(90, 1, 1, 100000.00, 0.00, 0.00, 2, '2024-10-03 14:21:28'),
(91, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 14:26:45'),
(92, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 14:26:45'),
(93, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 14:27:24'),
(94, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 14:27:24'),
(95, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 14:27:49'),
(96, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 14:27:49'),
(97, 1, 2, 1000.00, 0.01, 10.00, 2, '2024-10-03 14:27:55'),
(98, 2, 1, 1000.00, 0.00, 0.00, 1, '2024-10-03 14:27:55'),
(99, 2, 2, 100.00, 0.01, 1.00, 1, '2024-10-03 14:32:16'),
(100, 1, 1, 100.00, 0.00, 0.00, 2, '2024-10-03 14:32:16'),
(101, 2, 2, 1000000.00, 0.01, 10000.00, 1, '2024-10-03 14:35:32'),
(102, 1, 1, 1000000.00, 0.00, 0.00, 2, '2024-10-03 14:35:32'),
(103, 2, 2, 1000000.00, 0.01, 10000.00, 1, '2024-10-03 14:38:01'),
(104, 1, 1, 1000000.00, 0.00, 0.00, 2, '2024-10-03 14:38:01'),
(105, 2, 2, 1000.00, 0.01, 10.00, 1, '2024-10-03 14:50:07'),
(106, 1, 1, 1000.00, 0.00, 0.00, 2, '2024-10-03 14:50:07'),
(107, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-04 06:17:20'),
(108, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-04 06:17:20'),
(109, 1, 4, 30000000.00, 0.00, 0.00, NULL, '2024-10-04 11:03:14'),
(110, 1, 2, 10002.00, 0.01, 100.00, 2, '2024-10-04 12:37:58'),
(111, 2, 1, 10002.00, 0.00, 0.00, 1, '2024-10-04 12:37:58'),
(112, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-04 12:38:56'),
(113, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-04 12:38:56'),
(114, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-04 12:38:56'),
(115, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-04 12:38:57'),
(116, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-04 12:42:28'),
(117, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-04 12:42:28'),
(118, 1, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-04 12:42:52'),
(119, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-04 13:00:01'),
(120, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-04 13:00:02'),
(121, 1, 2, 100000.00, 0.01, 1000.00, 2, '2024-10-04 13:00:06'),
(122, 2, 1, 100000.00, 0.00, 0.00, 1, '2024-10-04 13:00:06'),
(123, 1, 3, 1000.00, 4.00, 40.00, NULL, '2024-10-04 13:00:17'),
(124, 1, 3, 1000.00, 4.00, 40.00, NULL, '2024-10-04 13:07:38'),
(125, 1, 2, 948000.00, 0.01, 9480.00, 2, '2024-10-04 13:22:40'),
(126, 2, 1, 948000.00, 0.00, 0.00, 1, '2024-10-04 13:22:41'),
(127, 1, 3, 1200.00, 3.00, 36.00, NULL, '2024-10-04 13:23:00'),
(128, 6, 3, 2000.00, 3.00, 60.00, NULL, '2024-10-05 06:35:04'),
(129, 6, 2, 1500.00, 0.01, 15.00, 1, '2024-10-06 03:02:49'),
(130, 1, 1, 1500.00, 0.00, 0.00, 6, '2024-10-06 03:02:49'),
(131, 6, 4, 25.00, 0.00, 0.00, NULL, '2024-10-06 06:21:34'),
(132, 1, 2, 948000.00, 0.01, 9480.00, 2, '2024-10-10 04:13:58'),
(133, 2, 1, 948000.00, 0.00, 0.00, 1, '2024-10-10 04:13:58'),
(134, 1, 2, 948000.00, 0.01, 9480.00, 2, '2024-10-14 05:39:31'),
(135, 2, 1, 948000.00, 0.00, 0.00, 1, '2024-10-14 05:39:31'),
(136, 1, 3, 2000.00, 3.00, 60.00, NULL, '2024-10-14 06:14:14'),
(137, 1, 4, 500000.00, 0.00, 0.00, NULL, '2024-10-14 06:15:32'),
(138, 1, 2, 948000.00, 0.01, 9480.00, 2, '2024-10-14 06:20:14'),
(139, 2, 1, 948000.00, 0.00, 0.00, 1, '2024-10-14 06:20:14'),
(140, 1, 2, 948000.00, 0.01, 9480.00, 2, '2024-10-14 06:20:39'),
(141, 2, 1, 948000.00, 0.00, 0.00, 1, '2024-10-14 06:20:39'),
(142, 1, 2, 948000.00, 0.01, 9480.00, 2, '2024-10-14 06:21:35'),
(143, 2, 1, 948000.00, 0.00, 0.00, 1, '2024-10-14 06:21:35'),
(144, 1, 3, 100.00, 5.00, 5.00, NULL, '2024-10-14 11:11:09'),
(145, 6, 3, 1000.00, 4.00, 40.00, NULL, '2024-10-14 14:05:18'),
(146, 1, 4, 2000.00, 0.00, 0.00, NULL, '2024-10-14 23:10:26'),
(147, 1, 3, 2000.00, 3.00, 60.00, NULL, '2024-10-14 23:10:33'),
(148, 1, 2, 200.00, 0.01, 2.00, 2, '2024-10-14 23:10:49'),
(149, 2, 1, 200.00, 0.00, 0.00, 1, '2024-10-14 23:10:49'),
(150, 9, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-15 12:13:07'),
(151, 9, 2, 1850.00, 0.01, 19.00, 1, '2024-10-15 12:15:12'),
(152, 1, 1, 1850.00, 0.00, 0.00, 9, '2024-10-15 12:15:12'),
(153, 1, 2, 10000000.00, 0.01, 100000.00, 9, '2024-10-15 12:16:25'),
(154, 9, 1, 10000000.00, 0.00, 0.00, 1, '2024-10-15 12:16:25'),
(155, 9, 4, 2981.00, 0.00, 0.00, NULL, '2024-10-15 12:17:05'),
(156, 9, 2, 100000.00, 0.01, 1000.00, 1, '2024-10-15 12:17:30'),
(157, 1, 1, 100000.00, 0.00, 0.00, 9, '2024-10-15 12:17:31'),
(158, 9, 2, 20000.00, 0.01, 200.00, 1, '2024-10-15 12:18:41'),
(159, 1, 1, 20000.00, 0.00, 0.00, 9, '2024-10-15 12:18:42'),
(160, 1, 3, 5000.00, 3.00, 150.00, NULL, '2024-10-15 12:27:43'),
(161, 1, 2, 10000000.00, 0.01, 100000.00, 9, '2024-10-15 12:38:28'),
(162, 9, 1, 10000000.00, 0.00, 0.00, 1, '2024-10-15 12:38:28');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaction_type`
--

CREATE TABLE `tb_transaction_type` (
  `transaction_type_id` int(1) NOT NULL,
  `transaction_type_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaction_type`
--

INSERT INTO `tb_transaction_type` (`transaction_type_id`, `transaction_type_name`) VALUES
(1, 'receive'),
(2, 'send'),
(3, 'deposit'),
(4, 'withdraw'),
(5, 'earn'),
(6, 'use');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `wallet_balance` decimal(24,2) DEFAULT 0.00,
  `profile` varchar(255) DEFAULT 'img/default-profile.png',
  `permission_id` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `username`, `password`, `email`, `wallet_balance`, `profile`, `permission_id`) VALUES
(1, 'mrchai', '$2y$10$wzT89MInfAUuReG9nvOEVezD.H0vjzDXEIy6OeMsulSAOCQf21WIS', 'asd@sad.c.c', 379503286.00, 'profiles/profile_mrchai.jpg', 1),
(2, 'rich', '$2y$10$xaNtjhwjDoAPIZJjAOKQ8uoLJXPQ0IkARJBVf8PVfOs38LE3MwlR6', 'asd@sad.c.c', 44328091.00, 'img/default-profile.png', 0),
(3, '123', '$2y$10$QYS0isSZ3J7G6a9GPewhN.xDbVvZdLzL3SavGB/0EfbLjJB5Q77dK', 'chainot@hotmail.co.th', 0.00, 'img/default-profile.png', 0),
(4, 'Jeejee', '$2y$10$u.NXe40xttqUki7SNcKaJO5niwr2kkuujbdu6Z74cUt1UV1J1UeSu', 'jeejee@gmail.com', 68800.00, 'img/default-profile.png', 0),
(5, 'คนสวย', '$2y$10$zE2Lpt0tZwl.JjH.Imj7sOcaTmL/NqFsoNkYFXhYGkEXr/4B3mj82', 'natnichachantho@gmail.com', 10056970.00, 'img/default-profile.png', 0),
(6, 'Qu4rtzer', '$2y$10$2OMfWZ6jgBGvCPTWu77G0Oaqo56UdaRHx.hmwUKGjyNq9diCXc/um', 'uoufghgamemode.1@gmail.com', 1360.00, 'img/default-profile.png', 1),
(7, 'MasterVII', '$2y$10$SoXtKEzvL/tuZ2wvgqxUN.PNxK/vesTf11xp04USr6HwB4PUrWRf.', '123@123.c', 0.00, 'img/default-profile.png', 0),
(8, 'gyat', '$2y$10$BPKzt9u72WlP7yEb8my/Pe1XbjrvTJgEXx/6iIS8Zdhx3mUON0R.a', '123@123.c', 0.00, 'profiles/profile_gyat.png', 0),
(9, 'tern', '$2y$10$F2fcECzZwN1sMZTtmHaNOu/TljOFvp0kXWZpgdXPWc/kzsOybHZCa', 'tttt@gmail.com', 19878800.00, 'img/default-profile.png', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_fee`
--
ALTER TABLE `tb_fee`
  ADD PRIMARY KEY (`fee_id`);

--
-- Indexes for table `tb_permission`
--
ALTER TABLE `tb_permission`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `tb_point`
--
ALTER TABLE `tb_point`
  ADD PRIMARY KEY (`point_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_point_transaction`
--
ALTER TABLE `tb_point_transaction`
  ADD PRIMARY KEY (`point_transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_point_transaction_id` (`transaction_type_id`),
  ADD KEY `fk_transaction_id` (`transaction_id`);

--
-- Indexes for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipient_user_id` (`recipient_user_id`),
  ADD KEY `transaction_type_id` (`transaction_type_id`);

--
-- Indexes for table `tb_transaction_type`
--
ALTER TABLE `tb_transaction_type`
  ADD PRIMARY KEY (`transaction_type_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_permission` (`permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_fee`
--
ALTER TABLE `tb_fee`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_point`
--
ALTER TABLE `tb_point`
  MODIFY `point_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tb_point_transaction`
--
ALTER TABLE `tb_point_transaction`
  MODIFY `point_transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_point`
--
ALTER TABLE `tb_point`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`),
  ADD CONSTRAINT `tb_point_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_point_transaction`
--
ALTER TABLE `tb_point_transaction`
  ADD CONSTRAINT `fk_point_transaction_id` FOREIGN KEY (`transaction_type_id`) REFERENCES `tb_transaction_type` (`transaction_type_id`),
  ADD CONSTRAINT `fk_transaction_id` FOREIGN KEY (`transaction_id`) REFERENCES `tb_transaction` (`transaction_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_point_transaction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD CONSTRAINT `tb_transaction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`),
  ADD CONSTRAINT `tb_transaction_ibfk_2` FOREIGN KEY (`recipient_user_id`) REFERENCES `tb_user` (`user_id`),
  ADD CONSTRAINT `tb_transaction_ibfk_3` FOREIGN KEY (`transaction_type_id`) REFERENCES `tb_transaction_type` (`transaction_type_id`);

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `fk_permission` FOREIGN KEY (`permission_id`) REFERENCES `tb_permission` (`permission_id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`mrchai`@`localhost` EVENT `delete_expired_points` ON SCHEDULE EVERY 1 DAY STARTS '2024-10-15 22:32:41' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM tb_point WHERE expiration_date <= CURDATE()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
