-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2025 at 02:08 AM
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
-- Database: `mgcafe_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_role` enum('admin','staff') NOT NULL,
  `module` enum('Inventory','EventBooking','User') NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `log_timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `user_role`, `module`, `record_id`, `action`, `details`, `log_timestamp`) VALUES
(1, 2, 'admin', 'Inventory', 22, 'Add', '{\"item_name\":\"coffee beans\",\"item_quantity\":10,\"item_category\":\"Food\"}', '2025-09-16 23:16:13'),
(2, 2, 'admin', 'Inventory', 7, 'Edit', '{\"old\":{\"item_name\":\"Supdrenks\",\"item_quantity\":125,\"item_category\":\"Drinks\"},\"new\":{\"item_name\":\"Supdrenks\",\"item_quantity\":100,\"item_category\":\"Drinks\"}}', '2025-09-16 23:21:03'),
(3, 2, 'staff', 'Inventory', 23, 'Add', '{\"item_name\":\"Meat\",\"item_quantity\":10,\"item_category\":\"Food\"}', '2025-09-17 00:01:44'),
(4, 2, 'staff', 'Inventory', 24, 'Add', '{\"item_name\":\"Supdrenks\",\"item_quantity\":20,\"item_category\":\"Drinks\"}', '2025-09-17 00:04:45'),
(5, 2, 'staff', 'Inventory', 25, 'Add', '{\"item_name\":\"haha\",\"item_quantity\":1,\"item_category\":\"Food\"}', '2025-09-17 00:13:28'),
(6, 2, 'staff', 'Inventory', 26, 'Add', '{\"item_name\":\"asdas\",\"item_quantity\":22,\"item_category\":\"Food\"}', '2025-09-17 01:40:20'),
(7, 2, 'staff', 'Inventory', 26, 'Edit', '{\"old\":{\"item_name\":\"asdas\",\"item_quantity\":22,\"item_category\":\"Food\"},\"new\":{\"item_name\":\"Ayos na\",\"item_quantity\":22,\"item_category\":\"Food\"}}', '2025-09-17 01:41:01'),
(8, 2, 'staff', 'Inventory', 26, 'Edit', '{\"old\":{\"item_name\":\"Ayos na\",\"item_quantity\":22,\"item_category\":\"Food\"},\"new\":{\"item_name\":\"Ayos na\",\"item_quantity\":22,\"item_category\":\"Food\"}}', '2025-09-17 01:41:26'),
(9, 1, 'admin', 'Inventory', 7, 'Edit', '{\"old\":{\"item_name\":\"Supdrenks\",\"item_quantity\":100,\"item_category\":\"Drinks\"},\"new\":{\"item_name\":\"Supdrenks\",\"item_quantity\":99,\"item_category\":\"Drinks\"}}', '2025-09-17 02:07:48'),
(10, 1, 'admin', 'Inventory', 7, 'Edit', '{\"old\":{\"item_name\":\"Supdrenks\",\"item_quantity\":99,\"item_category\":\"Drinks\"},\"new\":{\"item_name\":\"Supdrenks\",\"item_quantity\":99,\"item_category\":\"Drinks\"}}', '2025-09-17 02:12:14'),
(11, 2, 'staff', 'Inventory', 27, 'Add', '{\"item_name\":\"haha\",\"item_quantity\":23,\"item_category\":\"Food\"}', '2025-09-17 02:27:28'),
(12, 1, 'admin', 'Inventory', 25, 'Edit', '{\"old\":{\"item_name\":\"haha\",\"item_quantity\":1,\"item_category\":\"Food\"},\"new\":{\"item_name\":\"haha1111\",\"item_quantity\":1,\"item_category\":\"Food\"}}', '2025-09-17 02:31:04'),
(13, 2, 'staff', 'Inventory', 28, 'Add', '{\"item_name\":\"haha\",\"item_quantity\":23,\"item_category\":\"Food\"}', '2025-09-17 02:48:16'),
(14, 2, 'staff', 'Inventory', 20, 'Edit', '{\"old\":{\"item_name\":\"haha\",\"item_quantity\":121,\"item_category\":\"Food\"},\"new\":{\"item_name\":\"haha1123\",\"item_quantity\":121222,\"item_category\":\"Drinks\"}}', '2025-09-17 02:48:35'),
(15, 1, 'admin', 'Inventory', 29, 'Add', '{\"item_name\":\"Meat\",\"item_quantity\":199,\"item_category\":\"Food\"}', '2025-09-17 02:50:36'),
(16, 1, 'admin', 'Inventory', 29, 'Delete', '{\"item_name\":\"Meat\",\"item_quantity\":199,\"item_category\":\"Food\"}', '2025-09-17 04:00:51'),
(17, 2, 'staff', 'Inventory', 28, 'Delete', '{\"item_name\":\"haha\",\"item_quantity\":23,\"item_category\":\"Food\"}', '2025-09-17 04:03:43'),
(18, 1, 'admin', 'Inventory', 10, 'Edit', '\"Item: Magic; Name: Magic Sarap → Magic; \"', '2025-09-17 04:09:48'),
(19, 1, 'admin', 'Inventory', 25, 'Delete', '\"Item: haha1111; Quantity: 1; Category: Food;\"', '2025-09-17 04:15:17'),
(20, 1, 'admin', 'Inventory', 27, 'Delete', '\"Item: haha; Quantity: 23; Category: Food;\"', '2025-09-17 04:15:33'),
(21, 2, 'staff', 'Inventory', 20, 'Delete', '\"Item: haha1123; Quantity: 121222; Category: Drinks;\"', '2025-09-17 04:19:16'),
(22, 2, 'staff', 'Inventory', 23, 'Edit', '\"Item: Meat; Quantity: 10 → 20; \"', '2025-09-17 04:19:32'),
(26, 1, 'admin', 'EventBooking', 44, 'Delete', '\"{\\\"event_id\\\":44,\\\"customer_name\\\":\\\"migol\\\",\\\"customer_email\\\":\\\"babymigz.01@gmail.com\\\",\\\"customer_contact\\\":\\\"09232232133\\\",\\\"event_name\\\":\\\"Suntukan sa gate\\\",\\\"event_date\\\":\\\"2025-10-01\\\",\\\"event_time\\\":\\\"19:06:00\\\",\\\"event_description\\\":\\\"asdasdasdsad\\\",\\\"event_status\\\":\\\"Booked\\\",\\\"created_at\\\":\\\"2025-09-15 19:05:59\\\"}\"', '2025-09-17 05:06:01'),
(27, 3, 'staff', 'Inventory', 30, 'Add', '\"Item: kyutiks; Quantity: 3; Category: Cosmetics; Branch: MG Hub;\"', '2025-10-02 01:50:31'),
(28, 3, 'staff', 'Inventory', 31, 'Add', '\"Item: lotion; Quantity: 20; Category: Lotions; Branch: MG Spa;\"', '2025-10-02 01:50:51'),
(29, 3, 'staff', 'Inventory', 31, 'Delete', '\"Item: lotion; Quantity: 20; Category: Lotions; Branch: MG Spa;\"', '2025-10-02 02:00:50'),
(30, 3, 'staff', 'Inventory', 32, 'Add', '\"Item: lotion; Quantity: 20; Category: Lotions; Branch: MG Spa;\"', '2025-10-02 02:01:03'),
(31, 3, 'staff', 'Inventory', 32, 'Edit', '\"Item: lotion; Item_quantity: 20 → 9; Branch: MG Spa;\"', '2025-10-02 02:01:13'),
(32, 4, 'staff', 'Inventory', 33, 'Add', '\"Item: lavender; Quantity: 20; Category: Lotions; Branch: MG Spa;\"', '2025-10-02 02:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

CREATE TABLE `tbl_attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `attendance_status` enum('Pending','Present','Late','Absent','On Leave','Request') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_attendance`
--

INSERT INTO `tbl_attendance` (`attendance_id`, `employee_id`, `attendance_date`, `time_in`, `time_out`, `attendance_status`, `created_at`, `updated_at`) VALUES
(5, 7, '2025-09-06', NULL, NULL, 'Absent', '2025-09-05 19:18:35', '2025-09-05 19:18:35'),
(7, 11, '2025-09-06', NULL, NULL, 'Absent', '2025-09-05 19:18:35', '2025-09-05 19:18:35'),
(8, 12, '2025-09-06', NULL, NULL, 'Absent', '2025-09-05 19:18:35', '2025-09-05 19:18:35'),
(9, 6, '2025-09-06', NULL, NULL, 'Absent', '2025-09-05 19:18:35', '2025-09-05 19:18:35'),
(12, 5, '2025-09-06', NULL, NULL, 'Absent', '2025-09-05 19:18:35', '2025-09-05 19:18:35'),
(13, 13, '2025-09-06', NULL, NULL, 'Absent', '2025-09-05 19:18:35', '2025-09-05 19:18:35'),
(19, 15, '2025-09-06', '12:39:24', NULL, 'Late', '2025-09-05 19:34:44', '2025-09-06 04:39:24'),
(20, 14, '2025-09-06', '12:40:00', NULL, 'Late', '2025-09-05 19:34:44', '2025-09-06 04:40:00'),
(22, 7, '2025-09-07', '20:59:19', NULL, 'Late', '2025-09-06 16:00:06', '2025-09-07 12:59:19'),
(24, 11, '2025-09-07', NULL, NULL, 'Absent', '2025-09-06 16:00:06', '2025-09-06 16:00:06'),
(25, 12, '2025-09-07', NULL, NULL, 'Absent', '2025-09-06 16:00:06', '2025-09-06 16:00:06'),
(26, 6, '2025-09-07', NULL, NULL, 'Absent', '2025-09-06 16:00:06', '2025-09-06 16:00:06'),
(31, 5, '2025-09-07', NULL, NULL, 'Absent', '2025-09-06 16:00:06', '2025-09-06 16:00:06'),
(32, 15, '2025-09-07', NULL, NULL, 'Absent', '2025-09-06 16:00:06', '2025-09-06 16:00:06'),
(33, 13, '2025-09-07', NULL, NULL, 'Absent', '2025-09-06 16:00:06', '2025-09-06 16:00:06'),
(34, 14, '2025-09-07', NULL, NULL, 'Absent', '2025-09-06 16:00:06', '2025-09-06 16:00:06'),
(37, 7, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(39, 11, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(40, 12, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(41, 6, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(46, 5, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(47, 15, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(48, 13, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(49, 14, '2025-09-08', NULL, NULL, 'Absent', '2025-09-07 16:00:05', '2025-09-07 16:00:05'),
(52, 7, '2025-09-09', NULL, NULL, 'Pending', '2025-09-09 02:51:19', '2025-09-09 02:51:19'),
(54, 11, '2025-09-09', NULL, NULL, 'Pending', '2025-09-09 02:51:19', '2025-09-09 02:51:19'),
(55, 12, '2025-09-09', '07:52:23', NULL, 'Present', '2025-09-09 02:51:19', '2025-09-08 23:52:23'),
(56, 6, '2025-09-09', NULL, NULL, 'Pending', '2025-09-09 02:51:19', '2025-09-09 02:51:19'),
(61, 5, '2025-09-09', NULL, NULL, 'Pending', '2025-09-09 02:51:19', '2025-09-09 02:51:19'),
(62, 15, '2025-09-09', NULL, NULL, 'Pending', '2025-09-09 02:51:19', '2025-09-09 02:51:19'),
(63, 13, '2025-09-09', NULL, NULL, 'Pending', '2025-09-09 02:51:19', '2025-09-09 02:51:19'),
(64, 14, '2025-09-09', NULL, NULL, 'Pending', '2025-09-09 02:51:19', '2025-09-09 02:51:19'),
(66, 16, '2025-09-09', NULL, NULL, 'Pending', '2025-09-08 19:00:07', '2025-09-08 19:00:07'),
(68, 7, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(70, 11, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(71, 12, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(72, 6, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(77, 5, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(78, 15, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(79, 13, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(80, 16, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(81, 14, '2025-09-15', NULL, NULL, 'Pending', '2025-09-15 11:51:01', '2025-09-15 11:51:01'),
(84, 7, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(86, 11, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(87, 12, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(88, 6, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(93, 5, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(94, 15, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(95, 13, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(96, 16, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(97, 14, '2025-09-16', NULL, NULL, 'Pending', '2025-09-16 15:51:08', '2025-09-16 15:51:08'),
(115, 7, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(117, 11, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(118, 12, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(119, 6, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(124, 5, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(125, 15, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(126, 13, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(127, 16, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56'),
(128, 14, '2025-09-17', NULL, NULL, 'Pending', '2025-09-16 16:28:56', '2025-09-16 16:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance_reset`
--

CREATE TABLE `tbl_attendance_reset` (
  `reset_id` int(11) NOT NULL,
  `reset_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_attendance_reset`
--

INSERT INTO `tbl_attendance_reset` (`reset_id`, `reset_date`) VALUES
(1, '2025-08-31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `employee_id` int(11) NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `employee_image` varchar(255) DEFAULT NULL,
  `work_station` enum('Cafe','Spa','Beauty Lounge') NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `shift` enum('Morning','Mid','Night','Fixed') NOT NULL,
  `status` enum('New','Active','Inactive') NOT NULL DEFAULT 'New',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`employee_id`, `employee_code`, `qr_code`, `first_name`, `last_name`, `email_address`, `contact_number`, `employee_image`, `work_station`, `role`, `shift`, `status`, `created_at`, `updated_at`) VALUES
(5, 'EMP005', 'EMP005', 'Mark', 'Santos', 'mark.santos5@example.com', '09305678901', '1757098697_uifaces-popular-avatar (4).jpg', 'Cafe', 'Head Chef', 'Night', 'Inactive', '2025-09-05 20:58:17', '2025-09-08 00:10:03'),
(6, 'EMP006', 'EMP006', 'Jessica', 'Navarro', 'jessica.navarro6@example.com', '09406789012', '1757098747_uifaces-popular-avatar (17).jpg', 'Spa', 'Nail Technician', 'Fixed', 'Inactive', '2025-09-05 20:59:07', '2025-09-08 00:10:03'),
(7, 'EMP007', 'EMP007', 'Chloe', 'Garcia', 'chloe.garcia10@example.com', '09800123456', '1757098792_uifaces-popular-avatar (16).jpg', 'Spa', 'Massage Therapist', 'Fixed', 'Active', '2025-09-05 20:59:52', '2025-09-07 21:04:32'),
(11, 'EMP011', 'EMP011', 'Ethan', 'Morales', 'ethan.morales15@example.com', '09445678901', '1757099198_uifaces-popular-avatar (6).jpg', 'Cafe', 'Line Cook', 'Night', 'Inactive', '2025-09-05 21:06:38', '2025-09-08 00:10:03'),
(12, 'EMP012', 'EMP012', 'Grace', 'Aquino', 'grace.aquino16@example.com', '09556789012', '1757099237_uifaces-popular-avatar (21).jpg', 'Cafe', 'Cashier / Waitress', 'Night', 'Inactive', '2025-09-05 21:07:17', '2025-09-08 00:10:03'),
(13, 'EMP013', 'EMP013', 'Olivia', 'Santos', 'olivia.santos20@example.com', '09990123456', '1757099325_uifaces-popular-avatar (7).jpg', 'Beauty Lounge', 'Team Leader / Receptionist', 'Fixed', 'Inactive', '2025-09-05 21:08:45', '2025-09-08 00:10:03'),
(14, 'EMP014', 'EMP014', 'Ruby', 'Torres', 'ruby.torres36@example.com', '09556789036', '1757100613_v3_0894457.jpg', 'Beauty Lounge', 'Massage Therapist', 'Fixed', 'Active', '2025-09-05 21:30:13', '2025-09-06 12:41:43'),
(15, 'EMP015', 'EMP015', 'Mia', 'Rivera', 'mia.rivera50@example.com', '09800123450', '1757100680_uifaces-popular-avatar (28).jpg', 'Beauty Lounge', 'Nail Technician', 'Fixed', 'Active', '2025-09-05 21:31:20', '2025-09-06 12:41:43'),
(16, 'EMP016', 'EMP016', 'Romulo', 'Erroba', 'romulolang8@gmail.com', '09324234721', '1757357997_uifaces-popular-avatar (14).jpg', 'Cafe', 'On-Call', 'Night', 'New', '2025-09-08 20:59:57', '2025-09-08 20:59:57'),
(17, 'EMP017', 'EMP017', 'Miguel', 'Lequin', 'babymigz.01@gmail.com', '923223213', '1759359815_uifaces-popular-avatar (20).jpg', 'Cafe', 'Barista', 'Night', 'New', '2025-10-02 01:03:35', '2025-10-02 08:06:39'),
(18, 'EMP018', 'EMP018', 'Carlos', 'Dizon', 'carlosjosephdizon19@gmail.com', '09231283723', '1759362012_uifaces-popular-avatar (12).jpg', 'Cafe', 'Barista', 'Mid', 'New', '2025-10-02 01:40:12', '2025-10-02 01:40:12'),
(19, 'EMP019', 'EMP019', 'Anknown', 'Nymous', 'anknownnymous1@gmail.com', '091238127323', '1759363339_uifaces-popular-avatar (11).jpg', 'Cafe', 'Cashier / Waitress', 'Mid', 'New', '2025-10-02 02:02:19', '2025-10-02 08:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_booking`
--

CREATE TABLE `tbl_event_booking` (
  `event_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_contact` varchar(20) DEFAULT NULL,
  `event_name` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `event_status` enum('Booked','Cancelled','Completed') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_event_booking`
--

INSERT INTO `tbl_event_booking` (`event_id`, `customer_name`, `customer_email`, `customer_contact`, `event_name`, `event_date`, `event_time`, `event_description`, `event_status`, `created_at`) VALUES
(11, 'Staff', 'staff@mg.local', '09219837223', 'Birthday Party!', '2025-09-15', '19:01:00', 'sdsadsadas', 'Completed', '2025-09-14 19:56:27'),
(23, 'migol', 'babymigz.01@gmail.com', '09232232133', 'Birthday Party', '2025-10-04', '03:00:00', 'sadsadasdsad', 'Booked', '2025-09-15 00:23:54'),
(47, 'migol', 'babymigz.01@gmail.com', '09232232133', 'Birthday Party', '2025-09-26', '15:00:00', 'hahahahaha', 'Cancelled', '2025-09-17 22:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_pending`
--

CREATE TABLE `tbl_event_pending` (
  `pending_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_contact` varchar(20) DEFAULT NULL,
  `event_name` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `event_status` enum('Pending','Approved','Declined') NOT NULL DEFAULT 'Pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_event_pending`
--

INSERT INTO `tbl_event_pending` (`pending_id`, `customer_name`, `customer_email`, `customer_contact`, `event_name`, `event_date`, `event_time`, `event_description`, `event_status`, `created_at`) VALUES
(2, 'migol', 'babymigz.01@gmail.com', '09232232133', 'hindi party', '2025-10-05', '16:27:00', 'testing 2', 'Declined', '2025-09-30 02:27:37'),
(4, 'Von', 'babymigz.01@gmail.com', '0923223213', 'rainny', '2025-10-02', '16:51:00', 'testing', 'Declined', '2025-09-30 23:52:00'),
(5, 'Arlo', 'babymigz.01@gmail.com', '0923223213', 'kapitbahayt', '2025-10-03', '13:49:00', 'hahahaha', 'Approved', '2025-09-30 23:53:38'),
(6, 'Migolito', 'babymigz.01@gmail.com', '0923223213', 'kapitbahayt', '2025-10-02', '15:03:00', 'hahahahja', 'Approved', '2025-10-01 00:10:30'),
(7, 'Migolito', 'babymigz.01@gmail.com', '0923223213', 'kapitbahayt', '2025-10-05', '13:30:00', 'hahahahahahah', 'Declined', '2025-10-01 00:34:37'),
(8, 'Migolito', 'babymigz.01@gmail.com', '0923223213', 'buhay', '2025-10-05', '00:38:00', 'asdsadsa', 'Approved', '2025-10-01 00:36:02'),
(9, 'Migolito', 'babymigz.01@gmail.com', '0923223213', 'buhay', '2025-10-06', '15:00:00', 'testing time interval', 'Approved', '2025-10-01 01:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory`
--

CREATE TABLE `tbl_inventory` (
  `inventory_id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `item_quantity` int(11) DEFAULT 0,
  `item_category` varchar(50) DEFAULT NULL,
  `item_status` enum('In Stock','Low Stock','Out of Stock') DEFAULT 'In Stock',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch` enum('MG Cafe','MG Hub','MG Spa') NOT NULL DEFAULT 'MG Cafe'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inventory`
--

INSERT INTO `tbl_inventory` (`inventory_id`, `item_name`, `item_quantity`, `item_category`, `item_status`, `created_at`, `updated_at`, `branch`) VALUES
(7, 'Supdrenks', 99, 'Drinks', '', '2025-08-26 19:26:36', '2025-09-16 18:07:48', 'MG Cafe'),
(8, 'Meat', 18, 'Food', '', '2025-08-26 19:34:04', '2025-08-26 11:34:04', 'MG Cafe'),
(9, 'pork', 12, 'Food', '', '2025-08-26 19:34:42', '2025-08-26 11:34:42', 'MG Cafe'),
(10, 'Magic', 20, 'Supplies', '', '2025-08-26 19:35:13', '2025-09-16 20:09:48', 'MG Cafe'),
(11, 'Salt', 8, 'Supplies', '', '2025-08-26 19:35:33', '2025-08-26 11:35:33', 'MG Cafe'),
(12, 'Mangga', 60, 'Food', '', '2025-08-26 19:36:03', '2025-08-26 11:36:03', 'MG Cafe'),
(13, 'Cornstartch', 20, 'Food', '', '2025-08-26 19:38:03', '2025-08-26 11:38:03', 'MG Cafe'),
(14, 'Toyo', 0, 'Supplies', '', '2025-08-26 19:38:43', '2025-08-26 11:56:26', 'MG Cafe'),
(15, 'Suka', 2, 'Supplies', '', '2025-08-26 19:38:58', '2025-08-26 11:38:58', 'MG Cafe'),
(16, 'Cinamon', 100, 'Supplies', '', '2025-08-26 19:39:33', '2025-08-26 11:39:33', 'MG Cafe'),
(17, 'Pepper', 10, 'Supplies', '', '2025-08-26 19:40:00', '2025-08-26 11:40:00', 'MG Cafe'),
(18, 'Coke', 5, 'Drinks', '', '2025-09-07 17:43:49', '2025-09-07 09:43:49', 'MG Cafe'),
(19, 'Coke', 5, 'Drinks', '', '2025-09-07 17:49:39', '2025-09-07 09:49:39', 'MG Cafe'),
(21, 'Cheese', 10, 'Supplies', '', '2025-09-07 18:42:11', '2025-09-07 10:42:34', 'MG Cafe'),
(22, 'coffee beans', 10, 'Food', '', '2025-09-16 23:16:13', '2025-09-16 15:16:13', 'MG Cafe'),
(23, 'Meat', 20, 'Food', '', '2025-09-17 00:01:44', '2025-09-16 20:19:32', 'MG Cafe'),
(24, 'Supdrenks', 20, 'Drinks', '', '2025-09-17 00:04:45', '2025-09-16 16:04:45', 'MG Cafe'),
(26, 'Ayos na', 22, 'Food', '', '2025-09-17 01:40:20', '2025-09-16 17:41:01', 'MG Cafe'),
(30, 'kyutiks', 3, 'Cosmetics', 'In Stock', '2025-10-02 01:50:31', '2025-10-01 17:50:31', 'MG Hub'),
(32, 'lotion', 9, 'Lotions', 'In Stock', '2025-10-02 02:01:03', '2025-10-01 18:01:13', 'MG Spa'),
(33, 'lavender', 20, 'Lotions', 'In Stock', '2025-10-02 02:59:15', '2025-10-01 18:59:15', 'MG Spa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request`
--

CREATE TABLE `tbl_request` (
  `request_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `request_type` enum('Change Shift','On Leave') NOT NULL,
  `target_employee_id` int(11) DEFAULT NULL,
  `leave_type` enum('Vacation','Sick','Emergency','Other') DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `target_date` date NOT NULL,
  `status` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `email_sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_request`
--

INSERT INTO `tbl_request` (`request_id`, `employee_id`, `request_type`, `target_employee_id`, `leave_type`, `reason`, `request_date`, `target_date`, `status`, `email_sent`, `created_at`, `updated_at`) VALUES
(2, 16, 'Change Shift', 5, NULL, 'hahahahaha', '2025-10-02 01:19:44', '2025-10-10', 'Pending', 0, '2025-10-01 17:19:44', '2025-10-01 17:19:44'),
(3, 16, 'On Leave', NULL, 'Sick', 'hahahahahaa', '2025-10-02 01:20:18', '2025-10-10', 'Pending', 0, '2025-10-01 17:20:18', '2025-10-01 17:20:18'),
(4, 16, 'On Leave', NULL, 'Sick', 'hahahahahaa', '2025-10-02 01:21:42', '2025-10-10', 'Approved', 0, '2025-10-01 17:21:42', '2025-10-01 23:02:48'),
(5, 17, 'Change Shift', 5, NULL, 'personal matters', '2025-10-02 07:04:38', '2025-10-03', 'Approved', 0, '2025-10-01 23:04:38', '2025-10-01 23:04:47'),
(6, 17, 'Change Shift', 5, NULL, 'personal matters', '2025-10-02 07:09:15', '2025-10-08', 'Approved', 0, '2025-10-01 23:09:15', '2025-10-01 23:09:22'),
(7, 17, 'On Leave', NULL, 'Sick', 'lagnat', '2025-10-02 07:14:11', '2025-10-10', 'Declined', 0, '2025-10-01 23:14:11', '2025-10-01 23:36:36'),
(8, 17, 'On Leave', NULL, 'Sick', 'testing', '2025-10-02 07:29:37', '2025-10-11', 'Approved', 0, '2025-10-01 23:29:37', '2025-10-01 23:36:13'),
(9, 18, 'Change Shift', 17, NULL, 'testing 1', '2025-10-02 07:41:05', '2025-10-08', 'Approved', 0, '2025-10-01 23:41:05', '2025-10-01 23:41:23'),
(10, 19, 'Change Shift', 17, NULL, 'testing version 2', '2025-10-02 08:06:32', '2025-10-07', 'Approved', 0, '2025-10-02 00:06:32', '2025-10-02 00:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_todo`
--

CREATE TABLE `tbl_todo` (
  `todo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `station` enum('Cafe','MG Hub','Spa') NOT NULL,
  `todo_text` varchar(255) NOT NULL,
  `progress` int(11) DEFAULT 0,
  `is_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_todo`
--

INSERT INTO `tbl_todo` (`todo_id`, `user_id`, `station`, `todo_text`, `progress`, `is_completed`, `created_at`, `updated_at`) VALUES
(1, 1, 'MG Hub', 'ayusin yung nasirang lababo', 10, 0, '2025-10-01 23:50:39', '2025-10-01 23:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` enum('admin','staff') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `user_role`, `created_at`) VALUES
(1, 'mgcafe.adm2025@gmail.com', '$2y$10$NzCGvmc1vbfyNaClahOx/eBiM9LL4xrKlNfq9j4KpBuNNSNGAhN8u', 'admin', '2025-09-16 14:52:55'),
(2, 'mgcafe123@gmail.com', '$2y$10$NkT/nZZlbOe.Y72JBSsPh.6ufqI7YKTN2rIgRGgNegcMwyGQWTKEu', 'staff', '2025-09-16 14:52:56'),
(3, 'mgspa123@gmail.com', '$2y$10$Hiq2a2D7ug3wvNdantjAsOiIlFRrlIQY4aQBCHRq7fNf8f50NCYQC', 'staff', '2025-09-28 08:05:16'),
(4, 'mghub123@gmail.com', '$2y$10$zMV7iJcerZedv/jySxpOgechra0mk6IAfyRb9gi4.PKEPWG3hJv1e', 'staff', '2025-09-28 08:05:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `fk_employee` (`employee_id`);

--
-- Indexes for table `tbl_attendance_reset`
--
ALTER TABLE `tbl_attendance_reset`
  ADD PRIMARY KEY (`reset_id`);

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `tbl_event_booking`
--
ALTER TABLE `tbl_event_booking`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tbl_event_pending`
--
ALTER TABLE `tbl_event_pending`
  ADD PRIMARY KEY (`pending_id`);

--
-- Indexes for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `tbl_request`
--
ALTER TABLE `tbl_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `target_employee_id` (`target_employee_id`);

--
-- Indexes for table `tbl_todo`
--
ALTER TABLE `tbl_todo`
  ADD PRIMARY KEY (`todo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `tbl_attendance_reset`
--
ALTER TABLE `tbl_attendance_reset`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_event_booking`
--
ALTER TABLE `tbl_event_booking`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_event_pending`
--
ALTER TABLE `tbl_event_pending`
  MODIFY `pending_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tbl_request`
--
ALTER TABLE `tbl_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_todo`
--
ALTER TABLE `tbl_todo`
  MODIFY `todo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `tbl_employee` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_request`
--
ALTER TABLE `tbl_request`
  ADD CONSTRAINT `fk_request_employee` FOREIGN KEY (`employee_id`) REFERENCES `tbl_employee` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_request_target` FOREIGN KEY (`target_employee_id`) REFERENCES `tbl_employee` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_todo`
--
ALTER TABLE `tbl_todo`
  ADD CONSTRAINT `fk_todo_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
