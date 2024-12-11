-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 11:22 AM
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
-- Database: `new`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('asset','liability','equity','expense','income') NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL,
  `categoryType` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `type`, `opening_balance`, `categoryType`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'Cash', 'asset', 0.00, 'current', '2024-10-22 23:25:47', '2024-10-22 23:25:47', NULL),
(2, 'Owner\'s Capital', 'equity', 0.00, NULL, '2024-10-22 23:26:25', '2024-10-22 23:26:25', NULL),
(3, 'WATER', 'expense', 0.00, NULL, '2024-10-22 23:27:50', '2024-10-22 23:27:50', 1),
(4, 'Revenue', 'income', 0.00, NULL, '2024-10-22 23:49:59', '2024-10-22 23:49:59', NULL),
(5, 'inventory', 'asset', 0.00, 'current', '2024-10-22 23:49:59', '2024-10-22 23:49:59', NULL),
(6, 'ABC Bank', 'asset', 0.00, 'current', '2024-10-23 00:03:09', '2024-10-23 00:03:09', 2);

-- --------------------------------------------------------

--
-- Table structure for table `account_categories`
--

CREATE TABLE `account_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('asset','equity','liability','income','expense') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_categories`
--

INSERT INTO `account_categories` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Utility Bills', 'expense', '2024-10-22 23:17:56', '2024-10-22 23:17:56'),
(2, 'Banks', 'asset', '2024-10-22 23:18:41', '2024-10-22 23:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `amortizations`
--

CREATE TABLE `amortizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amortization_qty` varchar(255) DEFAULT NULL,
  `delivered_qty` varchar(255) DEFAULT NULL,
  `opening_delivered_qty` varchar(255) DEFAULT NULL,
  `balance_amortization` varchar(255) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `amortization_period` varchar(255) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amortizations`
--

INSERT INTO `amortizations` (`id`, `product_id`, `amortization_qty`, `delivered_qty`, `opening_delivered_qty`, `balance_amortization`, `start_date`, `amortization_period`, `unit_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 4, '32', '22', '10', '0', '2024-06', '2', 4, NULL, '2024-10-19 06:36:23', '2024-10-19 06:36:23');

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `rack_id` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `name`, `code`, `rack_id`, `department`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Area', 'a00', '[\"1\",\"2\"]', 'Logistic', NULL, '2024-07-29 03:29:10', '2024-07-29 03:29:10'),
(2, 'Store Zenig A1', 'A002', '[\"3\"]', 'Store', NULL, '2024-07-30 03:40:33', '2024-07-30 03:40:33'),
(3, 'Production area', 'PR00256', '[\"4\"]', 'Production', NULL, '2024-07-30 10:14:27', '2024-07-30 10:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `area_levels`
--

CREATE TABLE `area_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area_levels`
--

INSERT INTO `area_levels` (`id`, `name`, `code`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Level1', 'Amet quis voluptas', NULL, '2024-07-29 03:27:23', '2024-07-29 03:27:23'),
(2, 'Level2', 'Eligendi ex aliquid', NULL, '2024-07-29 03:27:37', '2024-07-29 03:27:37'),
(3, 'Level 3', 'Level 3', NULL, '2024-07-30 03:37:57', '2024-08-07 07:26:39'),
(4, 'prod l1', 'P002', NULL, '2024-07-30 10:13:36', '2024-07-30 10:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `area_locations`
--

CREATE TABLE `area_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area_locations`
--

INSERT INTO `area_locations` (`id`, `area_id`, `rack_id`, `level_id`, `department`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Logistic', NULL, '2024-07-29 03:29:10', '2024-07-29 03:29:10'),
(2, 1, 2, 2, 'Logistic', NULL, '2024-07-29 03:29:10', '2024-07-29 03:29:10'),
(3, 2, 3, 1, 'Store', NULL, '2024-07-30 03:40:33', '2024-07-30 03:40:33'),
(4, 2, 3, 2, 'Store', NULL, '2024-07-30 03:40:33', '2024-07-30 03:40:33'),
(5, 3, 4, 4, 'Production', NULL, '2024-07-30 10:14:27', '2024-07-30 10:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `area_racks`
--

CREATE TABLE `area_racks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `level_id` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area_racks`
--

INSERT INTO `area_racks` (`id`, `name`, `code`, `level_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Rack1', 'Nostrum voluptas con', '[\"1\"]', NULL, '2024-07-29 03:28:14', '2024-07-29 03:28:14'),
(2, 'Rack2', 'Ipsum voluptatem Co', '[\"2\"]', NULL, '2024-07-29 03:28:43', '2024-07-29 03:28:43'),
(3, 'Rack 3', 'R03', '[\"1\",\"2\"]', NULL, '2024-07-30 03:39:24', '2024-07-30 03:39:24'),
(4, 'Prd A', 'RACK009', '[\"4\"]', NULL, '2024-07-30 10:13:01', '2024-07-30 10:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `auto_assign` tinyint(4) DEFAULT NULL,
  `absent` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `timetable` varchar(255) DEFAULT NULL,
  `on_duty` varchar(255) DEFAULT NULL,
  `off_duty` varchar(255) DEFAULT NULL,
  `clock_in` varchar(255) DEFAULT NULL,
  `clock_out` varchar(255) DEFAULT NULL,
  `normal` int(11) DEFAULT NULL,
  `real_time` int(11) DEFAULT NULL,
  `late` varchar(255) DEFAULT NULL,
  `early` varchar(255) DEFAULT NULL,
  `ot_time` varchar(255) DEFAULT NULL,
  `work_time` varchar(255) DEFAULT NULL,
  `exception` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `n_days` int(11) DEFAULT NULL,
  `week_end` varchar(255) DEFAULT NULL,
  `holiday` varchar(255) DEFAULT NULL,
  `att_time` varchar(255) DEFAULT NULL,
  `n_days_ot` varchar(255) DEFAULT NULL,
  `weekend_ot` varchar(255) DEFAULT NULL,
  `holiday_ot` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `no`, `name`, `auto_assign`, `absent`, `date`, `timetable`, `on_duty`, `off_duty`, `clock_in`, `clock_out`, `normal`, `real_time`, `late`, `early`, `ot_time`, `work_time`, `exception`, `department`, `n_days`, `week_end`, `holiday`, `att_time`, `n_days_ot`, `weekend_ot`, `holiday_ot`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'Niqmah', NULL, '0', '2024-03-26', 'Normal', NULL, NULL, '08:00', '17:45', 1, NULL, NULL, NULL, '0.75', '8', NULL, 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ssss', NULL, '2024-10-23 00:54:56', '2024-10-23 01:51:15'),
(2, 'EMP002', 'ashraf', NULL, '1', '2024-03-26', 'Normal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '8', NULL, 'Engineering', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56'),
(3, 'EMP003', 'thivya', NULL, '0', '2024-03-26', 'Normal', NULL, NULL, '08:00', '17:00', 1, NULL, NULL, NULL, '0', '8', NULL, 'Finance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56'),
(4, 'EMP001', 'Niqmah', NULL, '0', '2024-03-27', 'Normal', NULL, NULL, '08:00', '17:45', 1, NULL, NULL, NULL, '0.75', '8', NULL, 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56'),
(5, 'EMP002', 'ashraf', NULL, '0', '2024-03-27', 'Normal', NULL, NULL, '09:00', '18:00', 1, NULL, NULL, NULL, '1', '7', NULL, 'Engineering', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56'),
(6, 'EMP003', 'thivya', NULL, '0', '2024-03-27', 'Normal', NULL, NULL, '09:00', '18:00', 1, NULL, NULL, NULL, '1', '7', NULL, 'Finance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56'),
(7, 'EMP001', 'Niqmah', NULL, '0', '2024-03-28', 'Normal', NULL, NULL, '08:00', '18:45', 1, NULL, NULL, NULL, '1.75', '8', NULL, 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56'),
(8, 'EMP002', 'ashraf', NULL, '0', '2024-03-28', 'Normal', NULL, NULL, '08:00', '18:45', 1, NULL, NULL, NULL, '1.75', '8', NULL, 'Engineering', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56'),
(9, 'EMP003', 'thivya', NULL, '0', '2024-03-28', 'Normal', NULL, NULL, '08:00', '18:45', 1, NULL, NULL, NULL, '1.75', '8', NULL, 'Finance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 00:54:56', '2024-10-23 00:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `boms`
--

CREATE TABLE `boms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rev_no` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_date` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `attachment1` varchar(255) DEFAULT NULL,
  `attachment2` varchar(255) DEFAULT NULL,
  `attachment3` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Submitted' COMMENT 'Submitted,Verified,Declined,Cancelled,Inactive',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boms`
--

INSERT INTO `boms` (`id`, `rev_no`, `ref_no`, `product_id`, `created_date`, `description`, `attachment1`, `attachment2`, `attachment3`, `created_by`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', 'Bom-ref/1/24', 1, '2024-07-29', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '20240729065215Untitled design.jpg', '20240729065215Untitled design.jpg', '20240729065215Untitled design.jpg', '1', 'Submitted', '2024-07-29 07:01:42', '2024-07-29 06:52:15', '2024-07-29 07:01:42'),
(2, '1', 'Bom-ref/1/24', 1, '2024-07-29', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '20240729070047Untitled design.jpg', '20240729070047Untitled design.jpg', '20240729070047Untitled design.jpg', '1', 'Inactive', NULL, '2024-07-29 07:00:47', '2024-07-29 12:04:45'),
(3, '1', 'Bom-ref/2/24', 5, '2024-07-29', 'N/A', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 08:36:50', '2024-07-29 11:45:29'),
(4, '1', 'Bom-ref/3/24', 7, '2024-07-29', 'N/A', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 08:41:49', '2024-07-29 11:45:47'),
(5, '1', 'Bom-ref/4/24', 2, '2024-07-29', 'gdfgdfgdfgfgfgfgfgfgfgfgfgf', '20240729100323SDD( Usama Rizwan S21BDOCS2M01028 ).pdf', '20240729100323SRS (Usama Rizwan) MSC 3rd Morning A.pdf', '20240729100323tcsInvoice.pdf', '1', 'Inactive', NULL, '2024-07-29 09:52:55', '2024-07-29 10:20:50'),
(6, '2', 'Bom-ref/4/24-2', 2, '2024-07-29', 'gdfgdfgdfgfgfgfgfgfgfgfgfgf', '20240729100323SDD( Usama Rizwan S21BDOCS2M01028 ).pdf', '20240729100323SRS (Usama Rizwan) MSC 3rd Morning A.pdf', '20240729100323tcsInvoice.pdf', '1', 'Submitted', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(7, '1', 'Bom-ref/5/24', 4, '2024-07-29', 'dfgdfg', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 10:47:36', '2024-07-29 11:46:04'),
(8, '1', 'Bom-ref/5/24', 4, '2024-07-29', 'dfgdfg', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(9, '1', 'Bom-ref/5/24', 4, '2024-07-29', 'dfgdfg', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(10, '1', 'Bom-ref/6/24', 3, '1996-01-24', 'Minus deserunt labor', '20240729110224ZeroTier One.msi', '20240729110224jmeter-plugins-manager-1.10 (1).jar', '20240729110224vc_redist.x64 (1).exe', '1', 'Inactive', NULL, '2024-07-29 11:02:24', '2024-07-29 11:46:18'),
(11, '1', 'Bom-ref/7/24', 4, '2024-07-29', 'itiyky', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 11:15:49', '2024-07-29 11:17:05'),
(12, '2', 'Bom-ref/7/24-2', 4, '2024-07-29', 'itiyky', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 11:46:29', '2024-07-29 11:17:05', '2024-07-29 11:46:29'),
(13, '1', 'Bom-ref/8/24', 4, '2024-07-29', 'gfh', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 11:18:18', '2024-07-29 11:46:54'),
(14, '1', 'Bom-ref/9/24', 6, '2024-07-29', 'gg', '20240729112808Untitled design.jpg', '20240729112808Untitled design.jpg', '20240729112808Untitled design.jpg', '1', 'Inactive', NULL, '2024-07-29 11:28:08', '2024-07-29 11:47:21'),
(15, '2', 'Bom-ref/2/24-2', 5, '2024-07-29', 'N/A', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 11:47:58', '2024-07-29 11:45:29', '2024-07-29 11:47:58'),
(16, '2', 'Bom-ref/3/24-2', 7, '2024-07-29', 'N/A', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 11:51:16', '2024-07-29 11:45:47', '2024-07-29 11:51:16'),
(17, '2', 'Bom-ref/5/24-2', 4, '2024-07-29', 'dfgdfg', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 11:48:33', '2024-07-29 11:46:04', '2024-07-29 11:48:33'),
(18, '2', 'Bom-ref/6/24-2', 3, '1996-01-24', 'Minus deserunt labor', '20240729110224ZeroTier One.msi', '20240729110224jmeter-plugins-manager-1.10 (1).jar', '20240729110224vc_redist.x64 (1).exe', '1', 'Submitted', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(19, '2', 'Bom-ref/8/24-2', 4, '2024-07-29', 'gfh', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 11:50:36', '2024-07-29 11:46:54', '2024-07-29 11:50:36'),
(20, '2', 'Bom-ref/9/24-2', 6, '2024-07-29', 'gg', '20240729112808Untitled design.jpg', '20240729112808Untitled design.jpg', '20240729112808Untitled design.jpg', '1', 'Submitted', '2024-07-29 11:51:59', '2024-07-29 11:47:21', '2024-07-29 11:51:59'),
(21, '1', 'Bom-ref/10/24', 1, '2024-07-29', 'ghh', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 11:56:03', '2024-07-29 12:17:33'),
(22, '2', 'Bom-ref/1/24-2', 1, '2024-07-29', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '20240729070047Untitled design.jpg', '20240729070047Untitled design.jpg', '20240729070047Untitled design.jpg', '1', 'Submitted', '2024-07-29 12:17:50', '2024-07-29 12:04:45', '2024-07-29 12:17:50'),
(23, '2', 'Bom-ref/10/24-2', 1, '2024-07-29', 'ghh', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 12:17:33', '2024-07-29 13:15:31'),
(24, '3', 'Bom-ref/10/24-2-3', 1, '2024-07-29', 'ghh', NULL, NULL, NULL, '1', 'Submitted', '2024-07-29 13:18:26', '2024-07-29 13:15:31', '2024-07-29 13:18:26'),
(25, '1', 'Bom-ref/11/24', 3, '2024-07-29', 'gbc', NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(26, '1', 'Bom-ref/12/24', 2, '2024-07-29', 'dsvfsf', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 13:35:45', '2024-07-29 13:56:13'),
(27, '1', 'Bom-ref/13/24', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 13:52:29', '2024-07-29 13:58:12'),
(28, '2', 'Bom-ref/12/24-2', 2, '2024-07-29', 'dsvfsf', NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(29, '2', 'Bom-ref/13/24-2', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 13:58:12', '2024-07-30 01:38:55'),
(30, '1', 'Bom-ref/14/24', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 14:01:10', '2024-07-29 14:18:10'),
(31, '2', 'Bom-ref/14/24-2', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 14:18:10', '2024-07-29 14:25:32'),
(32, '3', 'Bom-ref/14/24-2-3', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 14:25:32', '2024-07-29 14:31:51'),
(33, '4', 'Bom-ref/14/24-2-3-4', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-29 14:31:51', '2024-07-29 14:40:42'),
(34, '5', 'Bom-ref/14/24-2-3-4-5', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(35, '1', 'Bom-ref/15/24', 1, '2024-07-30', 'description', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-30 01:36:49', '2024-08-07 03:56:36'),
(36, '3', 'Bom-ref/13/24-2-3', 1, '2024-07-29', NULL, NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(37, '1', 'Bom-ref/14/24', 5, '2024-07-30', 'test', '20240730041621TNG_PAGE 2.jpg', NULL, NULL, '1', 'Verified', NULL, '2024-07-30 04:16:21', '2024-07-30 08:49:31'),
(38, '1', 'Bom-ref/15/24', 7, '2024-07-30', NULL, NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-07-30 09:05:43', '2024-07-30 09:05:43'),
(39, '1', 'Bom-ref/16/24', 10, '2024-07-30', NULL, '20240730134602Screenshot 2024-04-02 155250.png', NULL, NULL, '1', 'Verified', NULL, '2024-07-30 13:46:02', '2024-07-30 13:46:18'),
(40, '1', 'Bom-ref/17/24', 13, '2024-07-30', NULL, NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-07-30 14:35:28', '2024-07-30 14:35:28'),
(41, '1', 'Bom-ref/18/24', 14, '2024-07-30', NULL, NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-07-30 14:59:59', '2024-08-07 03:54:34'),
(42, '1', 'Bom-ref/18/24', 14, '2024-07-30', NULL, NULL, NULL, NULL, '1', 'Submitted', '2024-07-30 15:07:08', '2024-07-30 14:59:59', '2024-07-30 15:07:08'),
(43, '1', 'Bom-ref/19/24', 1, '2024-08-07', 'ddd', NULL, NULL, NULL, '1', 'Inactive', NULL, '2024-08-07 03:53:38', '2024-08-09 04:31:36'),
(44, '2', 'Bom-ref/18/24-2', 14, '2024-07-30', NULL, NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-08-07 03:54:34', '2024-08-07 03:54:34'),
(45, '2', 'Bom-ref/15/24-2', 1, '2024-07-30', 'description', NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(46, '2', 'Bom-ref/19/24-2', 1, '2024-08-07', 'ddd', NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(47, '1', 'ref-test', 5, '2024-10-22', 'sssss', NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-10-22 04:47:21', '2024-10-22 04:47:21'),
(48, '1', 'ref-test-1', 1, '2024-10-22', NULL, NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-10-22 04:54:38', '2024-10-22 04:54:38'),
(49, '1', 'ref-228', 2, '2024-10-22', NULL, NULL, NULL, NULL, '1', 'Submitted', NULL, '2024-10-22 05:02:56', '2024-10-22 05:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `bom_crushings`
--

CREATE TABLE `bom_crushings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bom_crushings`
--

INSERT INTO `bom_crushings` (`id`, `bom_id`, `product_id`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'okies', '2024-07-29 07:04:29', '2024-07-29 07:00:47', '2024-07-29 07:04:29'),
(2, 2, 2, 'okies', NULL, '2024-07-29 07:04:29', '2024-07-29 07:04:29'),
(3, 5, 3, '10', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(4, 5, 5, '20', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(5, 5, 8, '30', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(6, 5, 3, '10', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(7, 5, 5, '20', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(8, 5, 8, '30', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(9, 5, 1, NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(10, 5, 2, NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(11, 5, 4, NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(12, 5, 3, '10', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(13, 5, 5, '20', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(14, 5, 8, '30', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(15, 6, 3, '10', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(16, 6, 5, '20', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(17, 6, 8, '30', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(18, 8, 1, 'gg', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(19, 8, 2, 'gg', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(20, 8, 3, 'gg', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(21, 8, 5, 'gg', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(22, 8, 6, 'gg', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(23, 8, 7, 'gg', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(24, 8, 8, 'gg', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(25, 9, 1, 'gg', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(26, 9, 2, 'gg', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(27, 9, 3, 'gg', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(28, 9, 5, 'gg', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(29, 9, 6, 'gg', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(30, 9, 7, 'gg', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(31, 9, 8, 'gg', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(32, 10, 1, 'h', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(33, 10, 2, 'hjh', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(34, 10, 4, 'hhj', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(35, 10, 5, 'hgj', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(36, 10, 6, 'gg', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(37, 10, 7, 'gf', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(38, 10, 8, 'fhf', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(39, 13, 2, 'hgh', NULL, '2024-07-29 11:18:18', '2024-07-29 11:18:18'),
(40, 14, 2, 'hh', '2024-07-29 11:30:26', '2024-07-29 11:28:08', '2024-07-29 11:30:26'),
(41, 14, 2, 'hh', NULL, '2024-07-29 11:30:26', '2024-07-29 11:30:26'),
(42, 18, 1, 'h', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(43, 18, 2, 'hjh', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(44, 18, 4, 'hhj', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(45, 18, 5, 'hgj', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(46, 18, 6, 'gg', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(47, 18, 7, 'gf', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(48, 18, 8, 'fhf', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(49, 19, 2, 'hgh', '2024-07-29 11:50:36', '2024-07-29 11:46:54', '2024-07-29 11:50:36'),
(50, 20, 2, 'hh', '2024-07-29 11:51:59', '2024-07-29 11:47:21', '2024-07-29 11:51:59'),
(51, 21, 2, 'bado baddi', '2024-07-29 12:01:20', '2024-07-29 11:56:03', '2024-07-29 12:01:20'),
(52, 21, 2, 'bado baddi', NULL, '2024-07-29 12:01:20', '2024-07-29 12:01:20'),
(53, 22, 2, 'okies', '2024-07-29 12:17:50', '2024-07-29 12:04:45', '2024-07-29 12:17:50'),
(54, 23, 2, 'bado baddi', NULL, '2024-07-29 12:17:33', '2024-07-29 12:17:33'),
(55, 24, 2, 'bado baddi', '2024-07-29 13:18:26', '2024-07-29 13:15:31', '2024-07-29 13:18:26'),
(56, 25, 6, 'yes', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(57, 25, 7, 'yes', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(58, 26, 1, 'yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(59, 26, 3, 'yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(60, 26, 4, 'yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(61, 27, 5, NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(62, 27, 6, NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(63, 27, 2, NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(64, 28, 1, 'yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(65, 28, 3, 'yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(66, 28, 4, 'yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(67, 29, 5, NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(68, 29, 6, NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(69, 29, 2, NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(70, 30, 2, NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(71, 30, 5, NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(72, 30, 6, NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(73, 30, 7, NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(74, 31, 2, NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(75, 31, 5, NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(76, 31, 6, NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(77, 31, 7, NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(78, 31, 2, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(79, 31, 5, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(80, 31, 6, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(81, 31, 7, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(82, 31, 2, NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(83, 31, 5, NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(84, 31, 6, NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(85, 31, 7, NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(86, 32, 2, NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(87, 32, 5, NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(88, 32, 6, NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(89, 32, 7, NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(90, 32, 2, NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(91, 32, 5, NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(92, 32, 6, NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(93, 32, 7, NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(94, 33, 2, NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(95, 33, 5, NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(96, 33, 6, NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(97, 33, 7, NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(98, 33, 5, NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(99, 33, 2, NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(100, 34, 5, NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(101, 34, 2, NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(102, 35, 4, 'ok', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(103, 35, 5, 'ok', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(104, 36, 5, NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(105, 36, 6, NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(106, 36, 2, NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(107, 39, 9, 'test', NULL, '2024-07-30 13:46:02', '2024-07-30 13:46:02'),
(108, 42, 9, 'test', '2024-07-30 15:07:08', '2024-07-30 14:59:59', '2024-07-30 15:07:08'),
(109, 41, 9, 'test', NULL, '2024-07-30 14:59:59', '2024-07-30 14:59:59'),
(110, 43, 7, 'YES', NULL, '2024-08-07 03:53:38', '2024-08-07 03:53:38'),
(111, 43, 8, '20', NULL, '2024-08-07 03:53:38', '2024-08-07 03:53:38'),
(112, 44, 9, 'test', NULL, '2024-08-07 03:54:34', '2024-08-07 03:54:34'),
(113, 45, 4, 'ok', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(114, 45, 5, 'ok', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(115, 46, 7, 'YES', NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(116, 46, 8, '20', NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(117, 47, 4, '', NULL, '2024-10-22 04:47:21', '2024-10-22 04:47:21');

-- --------------------------------------------------------

--
-- Table structure for table `bom_processes`
--

CREATE TABLE `bom_processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `process_id` varchar(255) DEFAULT NULL,
  `process_no` varchar(255) DEFAULT NULL,
  `raw_part_ids` varchar(255) DEFAULT NULL,
  `sub_part_ids` varchar(255) DEFAULT NULL,
  `supplier_id` varchar(255) DEFAULT NULL,
  `machine_tonnage_id` varchar(255) DEFAULT NULL,
  `cavity` varchar(255) DEFAULT NULL,
  `ct` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bom_processes`
--

INSERT INTO `bom_processes` (`id`, `bom_id`, `process_id`, `process_no`, `raw_part_ids`, `sub_part_ids`, `supplier_id`, `machine_tonnage_id`, `cavity`, `ct`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, '1', 'Process1', '[\"2\"]', '[\"4\"]', '1', '1', '67', '786', '2024-07-29 07:04:29', '2024-07-29 07:00:47', '2024-07-29 07:04:29'),
(2, 2, '1', 'Process1', '[\"2\"]', '[\"4\"]', '1', '1', '67', '786', NULL, '2024-07-29 07:04:29', '2024-07-29 07:04:29'),
(3, 3, '1', '1', '[\"6\"]', '[\"7\"]', '1', '1', 'N/A', '12', '2024-07-29 08:37:06', '2024-07-29 08:36:50', '2024-07-29 08:37:06'),
(4, 3, '3', '1', '[\"6\"]', '[\"7\"]', '1', '1', 'N/A', '12', NULL, '2024-07-29 08:37:06', '2024-07-29 08:37:06'),
(5, 4, '3', '1', '[\"8\"]', '[\"8\"]', '1', '1', 'N/A', '12', '2024-07-29 08:42:30', '2024-07-29 08:41:49', '2024-07-29 08:42:30'),
(6, 4, '4', '1', '[\"8\"]', '[\"8\"]', '1', '1', 'N/A', '12', NULL, '2024-07-29 08:42:30', '2024-07-29 08:42:30'),
(7, 5, '3', '12', '[\"1\",\"2\",\"3\"]', '[\"1\",\"2\",\"3\"]', '2', '2', '1.2', '12.2', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(8, 5, '4', '12', '[\"4\",\"5\"]', '[\"4\",\"7\"]', '2', '2', '2.23', '2.222', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(9, 5, '3', '12', '[\"1\",\"2\",\"3\"]', '[\"1\",\"2\",\"3\"]', '2', '2', '1.2', '12.2', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(10, 5, '4', '12', '[\"4\",\"5\"]', '[\"4\",\"7\"]', '2', '2', '2.23', '2.222', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(11, 5, '3', '12', '[\"1\",\"2\",\"3\"]', '[\"1\",\"2\",\"3\"]', '2', '2', '1.2', '12.2', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(12, 5, '4', '12', '[\"4\",\"5\"]', '[\"4\",\"7\"]', '2', '2', '2.23', '2.222', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(13, 6, '3', '12', '[\"1\",\"2\",\"3\"]', '[\"1\",\"2\",\"3\"]', '2', '2', '1.2', '12.2', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(14, 6, '4', '12', '[\"4\",\"5\"]', '[\"4\",\"7\"]', '2', '2', '2.23', '2.222', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(15, 8, '1', 'P001', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', 'yes', '30', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(16, 9, '1', 'P001', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', 'yes', '30', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(17, 10, '1', 'P001', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '23', '30', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(18, 10, '2', 'p002', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '2', '2', '43', '40', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(19, 10, '3', 'p003', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '87', '45', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(20, 10, '4', 'p004', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '2', '78', '65', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(21, 13, '1', 'P001', '[\"1\"]', '[\"3\",\"4\"]', '1', '1', 'hh', 'hh', NULL, '2024-07-29 11:18:18', '2024-07-29 11:18:18'),
(22, 14, '1', 'Process1', '[\"1\"]', '[\"2\"]', '1', '1', '6', '56', '2024-07-29 11:30:26', '2024-07-29 11:28:09', '2024-07-29 11:30:26'),
(23, 14, '1', 'Process1', '[\"1\"]', '[\"2\"]', '1', '1', '6', '56', NULL, '2024-07-29 11:30:26', '2024-07-29 11:30:26'),
(24, 15, '3', '1', '[\"6\"]', '[\"7\"]', '1', '1', 'N/A', '12', '2024-07-29 11:47:58', '2024-07-29 11:45:29', '2024-07-29 11:47:58'),
(25, 16, '4', '1', '[\"8\"]', '[\"8\"]', '1', '1', 'N/A', '12', '2024-07-29 11:51:16', '2024-07-29 11:45:47', '2024-07-29 11:51:16'),
(26, 18, '1', 'P001', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '23', '30', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(27, 18, '2', 'p002', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '2', '2', '43', '40', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(28, 18, '3', 'p003', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '87', '45', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(29, 18, '4', 'p004', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '2', '78', '65', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(30, 19, '1', 'P001', '[\"1\"]', '[\"3\",\"4\"]', '1', '1', 'hh', 'hh', '2024-07-29 11:50:36', '2024-07-29 11:46:54', '2024-07-29 11:50:36'),
(31, 20, '1', 'Process1', '[\"1\"]', '[\"2\"]', '1', '1', '6', '56', '2024-07-29 11:51:59', '2024-07-29 11:47:21', '2024-07-29 11:51:59'),
(32, 21, '1', 'P001', '[\"1\"]', '[\"2\"]', '1', '1', '67', '56', '2024-07-29 12:01:20', '2024-07-29 11:56:03', '2024-07-29 12:01:20'),
(33, 21, '1', 'P001', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '67', '56', NULL, '2024-07-29 12:01:20', '2024-07-29 12:01:20'),
(34, 22, '1', 'Process1', '[\"2\"]', '[\"4\"]', '1', '1', '67', '786', '2024-07-29 12:17:50', '2024-07-29 12:04:45', '2024-07-29 12:17:50'),
(35, 23, '1', 'P001', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '67', '56', NULL, '2024-07-29 12:17:33', '2024-07-29 12:17:33'),
(36, 24, '1', 'P001', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '67', '56', '2024-07-29 13:18:26', '2024-07-29 13:15:31', '2024-07-29 13:18:26'),
(37, 25, '1', 'P001', '[\"3\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '67', '56', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(38, 26, '1', 'Process1', '[\"1\",\"2\",\"3\",\"4\"]', '[\"7\",\"8\"]', '1', '1', '55', '6', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(39, 26, '2', 'Process2', '[\"1\",\"2\"]', '[\"3\",\"4\",\"7\",\"8\"]', '1', '1', '556', '44', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(40, 26, '3', 'Process3', '[\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '56', '87', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(41, 27, '1', 'Process1', '[\"2\"]', '[\"2\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(42, 27, '1', 'Process2', '[\"5\"]', '[\"7\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(43, 28, '1', 'Process1', '[\"1\",\"2\",\"3\",\"4\"]', '[\"7\",\"8\"]', '1', '1', '55', '6', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(44, 28, '2', 'Process2', '[\"1\",\"2\"]', '[\"3\",\"4\",\"7\",\"8\"]', '1', '1', '556', '44', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(45, 28, '3', 'Process3', '[\"7\",\"8\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\",\"8\"]', '1', '1', '56', '87', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(46, 29, '1', 'Process1', '[\"2\"]', '[\"2\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(47, 29, '1', 'Process2', '[\"5\"]', '[\"7\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(48, 30, '1', 'P001', '[\"1\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(49, 30, '1', 'p002', '[\"2\"]', '[\"2\",\"3\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(50, 31, '1', 'P001', '[\"1\"]', '[\"1\",\"2\",\"3\",\"4\",\"7\"]', '1', '1', NULL, NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(51, 31, '1', 'p002', '[\"2\"]', '[\"2\",\"3\"]', '1', '1', NULL, NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(52, 31, '1', 'P001', '[\"1\"]', '[\"1\"]', '1', '1', NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(53, 31, '1', 'p002', '[\"2\"]', '[\"2\",\"3\"]', '1', '1', NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(54, 31, '1', 'P001', '[\"1\"]', '[\"1\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(55, 31, '1', 'p002', '[\"2\"]', '[\"2\",\"3\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(56, 32, '1', 'P001', '[\"1\"]', '[\"1\"]', '1', '1', NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(57, 32, '1', 'p002', '[\"2\"]', '[\"2\",\"3\"]', '1', '1', NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(58, 32, '1', 'P001', '[\"1\"]', '[\"1\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(59, 32, '1', 'p002', '[\"2\"]', '[\"2\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(60, 33, '1', 'P001', '[\"1\"]', '[\"1\"]', '1', '1', NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(61, 33, '1', 'p002', '[\"2\"]', '[\"2\"]', '1', '1', NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(62, 33, '1', 'P001', '[\"2\"]', '[\"2\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(63, 33, '1', 'p002', '[\"5\"]', '[\"7\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(64, 34, '1', 'P001', '[\"2\"]', '[\"2\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(65, 34, '1', 'p002', '[\"5\"]', '[\"7\"]', '1', '1', NULL, NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(66, 35, '1', 'Process1', '[\"1\"]', '[\"1\",\"2\"]', '1', '2', '67', '90', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(67, 36, '1', 'Process1', '[\"2\"]', '[\"2\"]', '1', '1', NULL, NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(68, 36, '1', 'Process2', '[\"5\"]', '[\"7\"]', '1', '1', NULL, NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(69, 37, '3', '3', '[\"7\"]', '[\"8\"]', '3', '1', '0', '10', '2024-07-30 08:49:07', '2024-07-30 04:16:21', '2024-07-30 08:49:07'),
(70, 37, '3', '3', '[\"6\"]', '[\"7\"]', '3', '1', '1', '10', NULL, '2024-07-30 08:49:07', '2024-07-30 08:49:07'),
(71, 38, '4', '1', '[\"6\"]', '[\"1\"]', '1', '1', '1', '20', NULL, '2024-07-30 09:05:43', '2024-07-30 09:05:43'),
(72, 39, '3', '1', '[\"12\"]', '[\"11\"]', '1', '2', '1', '10', NULL, '2024-07-30 13:46:02', '2024-07-30 13:46:02'),
(73, 40, '3', '1', '[\"12\"]', '[\"11\"]', '1', '1', '1', '20', NULL, '2024-07-30 14:35:28', '2024-07-30 14:35:28'),
(74, 41, '4', '1', '[\"16\"]', '[\"15\"]', '1', '1', '1', '15', NULL, '2024-07-30 14:59:59', '2024-07-30 14:59:59'),
(75, 42, '4', '1', '[\"16\"]', '[\"15\"]', '1', '1', '1', '15', '2024-07-30 15:07:08', '2024-07-30 14:59:59', '2024-07-30 15:07:08'),
(76, 43, '3', '12', '[\"1\"]', '[\"2\",\"7\"]', '3', '2', '22', '35', NULL, '2024-08-07 03:53:38', '2024-08-07 03:53:38'),
(77, 44, '4', '1', '[\"16\"]', '[\"15\"]', '1', '1', '1', '15', NULL, '2024-08-07 03:54:34', '2024-08-07 03:54:34'),
(78, 45, '1', 'Process1', '[\"1\"]', '[\"1\",\"2\"]', '1', '2', '67', '90', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(79, 46, '3', '12', '[\"1\"]', '[\"2\",\"7\"]', '3', '2', '22', '35', NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(80, 47, '3', 'process-121', '[\"1\"]', '[\"4\"]', '3', '1', '', '', NULL, '2024-10-22 04:47:22', '2024-10-22 04:47:22'),
(81, 49, '3', 'process-1', '[\"4\"]', '', '3', '1', '', '', '2024-10-22 05:03:12', '2024-10-22 05:02:56', '2024-10-22 05:03:12'),
(82, 49, '3', 'process-1', '', '', '3', '1', '', '', '2024-10-22 05:03:36', '2024-10-22 05:03:12', '2024-10-22 05:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `bom_purchase_parts`
--

CREATE TABLE `bom_purchase_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bom_purchase_parts`
--

INSERT INTO `bom_purchase_parts` (`id`, `bom_id`, `product_id`, `qty`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '14', 'Yes', '2024-07-29 07:04:29', '2024-07-29 07:00:47', '2024-07-29 07:04:29'),
(2, 2, 2, '14', 'Yes', NULL, '2024-07-29 07:04:29', '2024-07-29 07:04:29'),
(3, 3, 6, '15', 'test', '2024-07-29 08:37:06', '2024-07-29 08:36:50', '2024-07-29 08:37:06'),
(4, 3, 6, '15', 'test', NULL, '2024-07-29 08:37:06', '2024-07-29 08:37:06'),
(5, 4, 8, '3', 'test', '2024-07-29 08:42:30', '2024-07-29 08:41:49', '2024-07-29 08:42:30'),
(6, 4, 8, '3', 'test', NULL, '2024-07-29 08:42:30', '2024-07-29 08:42:30'),
(7, 5, 1, '20', 'Hello Testing', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(8, 5, 8, '10', 'Hello Testing', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(9, 5, 1, '20', 'Hello Testing', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(10, 5, 8, '10', 'Hello Testing', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(11, 5, 2, '0', NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(12, 5, 3, '0', NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(13, 5, 4, '0', NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(14, 5, 1, '20', 'Hello Testing', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(15, 5, 8, '10', 'Hello Testing', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(16, 6, 1, '20', 'Hello Testing', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(17, 6, 8, '10', 'Hello Testing', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(18, 7, 1, '55', 'Yes', NULL, '2024-07-29 10:47:36', '2024-07-29 10:47:36'),
(19, 7, 2, '66', '87', NULL, '2024-07-29 10:47:36', '2024-07-29 10:47:36'),
(20, 8, 1, '55', 'Yes', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(21, 8, 2, '66', '87', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(22, 8, 3, '44', NULL, '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(23, 8, 5, '16', NULL, '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(24, 8, 6, '34', '7uh', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(25, 8, 7, '29', 'jhk', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(26, 8, 8, '756', 'hh', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(27, 9, 1, '55', 'Yes', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(28, 9, 2, '66', '87', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(29, 9, 3, '44', NULL, '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(30, 9, 5, '16', NULL, '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(31, 9, 6, '34', '7uh', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(32, 9, 7, '29', 'jhk', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(33, 9, 8, '756', 'hh', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(34, 10, 1, '108', 'Yes', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(35, 10, 2, '456', '87', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(36, 10, 4, '34', '566', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(37, 13, 1, '65', '56h', NULL, '2024-07-29 11:18:18', '2024-07-29 11:18:18'),
(38, 14, 1, '67', 'yy', '2024-07-29 11:30:26', '2024-07-29 11:28:08', '2024-07-29 11:30:26'),
(39, 14, 1, '67', 'yy', NULL, '2024-07-29 11:30:26', '2024-07-29 11:30:26'),
(40, 15, 6, '15', 'test', '2024-07-29 11:47:58', '2024-07-29 11:45:29', '2024-07-29 11:47:58'),
(41, 16, 8, '3', 'test', '2024-07-29 11:51:16', '2024-07-29 11:45:47', '2024-07-29 11:51:16'),
(42, 17, 1, '55', 'Yes', '2024-07-29 11:48:33', '2024-07-29 11:46:04', '2024-07-29 11:48:33'),
(43, 17, 2, '66', '87', '2024-07-29 11:48:33', '2024-07-29 11:46:04', '2024-07-29 11:48:33'),
(44, 18, 1, '108', 'Yes', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(45, 18, 2, '456', '87', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(46, 18, 4, '34', '566', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(47, 19, 1, '65', '56h', '2024-07-29 11:50:36', '2024-07-29 11:46:54', '2024-07-29 11:50:36'),
(48, 20, 1, '67', 'yy', '2024-07-29 11:51:59', '2024-07-29 11:47:21', '2024-07-29 11:51:59'),
(49, 21, 2, '678', 'yes', '2024-07-29 12:01:20', '2024-07-29 11:56:03', '2024-07-29 12:01:20'),
(50, 21, 2, '678', 'yes', NULL, '2024-07-29 12:01:20', '2024-07-29 12:01:20'),
(51, 22, 2, '14', 'Yes', '2024-07-29 12:17:50', '2024-07-29 12:04:45', '2024-07-29 12:17:50'),
(52, 23, 2, '678', 'yes', NULL, '2024-07-29 12:17:33', '2024-07-29 12:17:33'),
(53, 24, 2, '678', 'yes', '2024-07-29 13:18:26', '2024-07-29 13:15:31', '2024-07-29 13:18:26'),
(54, 25, 1, '108', 'Yes', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(55, 25, 2, '654', 'ohh yes!', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(56, 26, 1, '45', 'Yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(57, 26, 3, '67', 'ohh yes!', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(58, 26, 4, '87', '566', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(59, 26, 5, '89', 'hj', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(60, 27, 2, '3636', NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(61, 27, 3, '36', NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(62, 28, 1, '45', 'Yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(63, 28, 3, '67', 'ohh yes!', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(64, 28, 4, '87', '566', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(65, 28, 5, '89', 'hj', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(66, 29, 2, '3636', NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(67, 29, 3, '36', NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(68, 30, 2, '108', 'Yes', NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(69, 30, 3, '456', 'ohh yes!', NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(70, 30, 4, '34', '566', NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(71, 31, 2, '108', 'Yes', '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(72, 31, 3, '456', 'ohh yes!', '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(73, 31, 4, '34', '566', '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(74, 31, 2, '108', 'Yes', '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(75, 31, 3, '456', 'ohh yes!', '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(76, 31, 4, '34', '566', '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(77, 31, 2, '108', 'Yes', NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(78, 31, 3, '456', 'ohh yes!', NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(79, 31, 4, '34', '566', NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(80, 32, 2, '108', 'Yes', '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(81, 32, 3, '456', 'ohh yes!', '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(82, 32, 4, '34', '566', '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(83, 32, 2, '108', 'Yes', NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(84, 32, 3, '456', 'ohh yes!', NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(85, 32, 4, '34', '566', NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(86, 33, 2, '108', 'Yes', '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(87, 33, 3, '456', 'ohh yes!', '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(88, 33, 4, '34', '566', '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(89, 33, 2, '108', 'Yes', NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(90, 33, 3, '456', 'ohh yes!', NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(91, 34, 2, '108', 'Yes', NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(92, 34, 3, '456', 'ohh yes!', NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(93, 35, 2, '106', 'yes', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(94, 35, 3, '200', 'yes', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(95, 36, 2, '3636', NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(96, 36, 3, '36', NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(97, 37, 6, '10', 'test', '2024-07-30 08:49:07', '2024-07-30 04:16:21', '2024-07-30 08:49:07'),
(98, 37, 6, '10', 'test', NULL, '2024-07-30 08:49:07', '2024-07-30 08:49:07'),
(99, 38, 6, '5', NULL, NULL, '2024-07-30 09:05:43', '2024-07-30 09:05:43'),
(100, 39, 12, '10', 'test', NULL, '2024-07-30 13:46:02', '2024-07-30 13:46:02'),
(101, 40, 12, '15', NULL, NULL, '2024-07-30 14:35:28', '2024-07-30 14:35:28'),
(102, 42, 16, '10', 'TEST', '2024-07-30 15:07:08', '2024-07-30 14:59:59', '2024-07-30 15:07:08'),
(103, 41, 16, '10', 'TEST', NULL, '2024-07-30 14:59:59', '2024-07-30 14:59:59'),
(104, 43, 2, '23', NULL, NULL, '2024-08-07 03:53:38', '2024-08-07 03:53:38'),
(105, 43, 3, '32', NULL, NULL, '2024-08-07 03:53:38', '2024-08-07 03:53:38'),
(106, 44, 16, '10', 'TEST', NULL, '2024-08-07 03:54:34', '2024-08-07 03:54:34'),
(107, 45, 2, '106', 'yes', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(108, 45, 3, '200', 'yes', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(109, 46, 2, '23', NULL, NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(110, 46, 3, '32', NULL, NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(111, 47, 1, '2', '', NULL, '2024-10-22 04:47:21', '2024-10-22 04:47:21'),
(112, 49, 4, '2', '', '2024-10-22 05:03:12', '2024-10-22 05:02:56', '2024-10-22 05:03:12'),
(113, 49, 4, '2', '', '2024-10-22 05:03:36', '2024-10-22 05:03:12', '2024-10-22 05:03:36'),
(114, 49, 4, '2', '', NULL, '2024-10-22 05:03:36', '2024-10-22 05:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `bom_sub_parts`
--

CREATE TABLE `bom_sub_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bom_sub_parts`
--

INSERT INTO `bom_sub_parts` (`id`, `bom_id`, `product_id`, `qty`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 3, '54', 'Mort', '2024-07-29 07:04:29', '2024-07-29 07:00:47', '2024-07-29 07:04:29'),
(2, 2, 4, '23', 'iago', '2024-07-29 07:04:29', '2024-07-29 07:00:47', '2024-07-29 07:04:29'),
(3, 2, 3, '54', 'Mort', NULL, '2024-07-29 07:04:29', '2024-07-29 07:04:29'),
(4, 2, 4, '23', 'iago', NULL, '2024-07-29 07:04:29', '2024-07-29 07:04:29'),
(5, 3, 7, '1', 'test', '2024-07-29 08:37:06', '2024-07-29 08:36:50', '2024-07-29 08:37:06'),
(6, 3, 7, '1', 'test', NULL, '2024-07-29 08:37:06', '2024-07-29 08:37:06'),
(7, 5, 1, '10', 'Hello Testing', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(8, 5, 4, '20', 'Hello Testing', '2024-07-29 10:00:07', '2024-07-29 09:52:55', '2024-07-29 10:00:07'),
(9, 5, 1, '10', 'Hello Testing', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(10, 5, 4, '20', 'Hello Testing', '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(11, 5, 2, '0', NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(12, 5, 3, '0', NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(13, 5, 4, '0', NULL, '2024-07-29 10:03:23', '2024-07-29 10:00:07', '2024-07-29 10:03:23'),
(14, 5, 1, '10', 'Hello Testing', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(15, 5, 4, '20', 'Hello Testing', NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(16, 6, 1, '10', 'Hello Testing', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(17, 6, 4, '20', 'Hello Testing', '2024-07-29 11:45:56', '2024-07-29 10:20:50', '2024-07-29 11:45:56'),
(18, 8, 1, '55', 'g', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(19, 8, 2, '5', 'b', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(20, 8, 3, '645', 'v', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(21, 8, 7, '56', 'b', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(22, 8, 8, '33', 'x', '2024-07-29 11:23:07', '2024-07-29 10:48:10', '2024-07-29 11:23:07'),
(23, 9, 1, '55', 'g', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(24, 9, 2, '5', 'b', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(25, 9, 3, '645', 'v', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(26, 9, 7, '56', 'b', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(27, 9, 8, '33', 'x', '2024-07-29 11:22:56', '2024-07-29 10:48:39', '2024-07-29 11:22:56'),
(28, 10, 1, '66', 'g', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(29, 10, 2, '65', 'g', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(30, 10, 4, '665', 'g', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(31, 10, 7, '67', 'g', NULL, '2024-07-29 11:02:24', '2024-07-29 11:02:24'),
(32, 13, 1, '65', 'gf', NULL, '2024-07-29 11:18:18', '2024-07-29 11:18:18'),
(33, 13, 2, '76', 'gh', NULL, '2024-07-29 11:18:18', '2024-07-29 11:18:18'),
(34, 13, 3, '34', 'gh', NULL, '2024-07-29 11:18:18', '2024-07-29 11:18:18'),
(35, 14, 3, '66', '6ty', '2024-07-29 11:30:26', '2024-07-29 11:28:08', '2024-07-29 11:30:26'),
(36, 14, 4, '65', 'yy', '2024-07-29 11:30:26', '2024-07-29 11:28:08', '2024-07-29 11:30:26'),
(37, 14, 3, '66', '6ty', NULL, '2024-07-29 11:30:26', '2024-07-29 11:30:26'),
(38, 14, 4, '65', 'yy', NULL, '2024-07-29 11:30:26', '2024-07-29 11:30:26'),
(39, 15, 7, '1', 'test', '2024-07-29 11:47:58', '2024-07-29 11:45:29', '2024-07-29 11:47:58'),
(40, 18, 1, '66', 'g', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(41, 18, 2, '65', 'g', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(42, 18, 4, '665', 'g', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(43, 18, 7, '67', 'g', '2024-07-29 11:49:38', '2024-07-29 11:46:18', '2024-07-29 11:49:38'),
(44, 19, 1, '65', 'gf', '2024-07-29 11:50:36', '2024-07-29 11:46:54', '2024-07-29 11:50:36'),
(45, 19, 2, '76', 'gh', '2024-07-29 11:50:36', '2024-07-29 11:46:54', '2024-07-29 11:50:36'),
(46, 19, 3, '34', 'gh', '2024-07-29 11:50:36', '2024-07-29 11:46:54', '2024-07-29 11:50:36'),
(47, 20, 3, '66', '6ty', '2024-07-29 11:51:59', '2024-07-29 11:47:21', '2024-07-29 11:51:59'),
(48, 20, 4, '65', 'yy', '2024-07-29 11:51:59', '2024-07-29 11:47:21', '2024-07-29 11:51:59'),
(49, 21, 2, '87', 'bado baddi', '2024-07-29 12:01:20', '2024-07-29 11:56:03', '2024-07-29 12:01:20'),
(50, 21, 2, '87', 'bado baddi', NULL, '2024-07-29 12:01:20', '2024-07-29 12:01:20'),
(51, 22, 3, '54', 'Mort', '2024-07-29 12:17:50', '2024-07-29 12:04:45', '2024-07-29 12:17:50'),
(52, 22, 4, '23', 'iago', '2024-07-29 12:17:50', '2024-07-29 12:04:45', '2024-07-29 12:17:50'),
(53, 23, 2, '87', 'bado baddi', NULL, '2024-07-29 12:17:33', '2024-07-29 12:17:33'),
(54, 24, 2, '87', 'bado baddi', '2024-07-29 13:18:26', '2024-07-29 13:15:31', '2024-07-29 13:18:26'),
(55, 25, 7, '54', 'yes', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(56, 25, 8, '931', 'yes', NULL, '2024-07-29 13:18:09', '2024-07-29 13:18:09'),
(57, 26, 3, '456', 'yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(58, 26, 4, '66', 'yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(59, 26, 7, '66', 'yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(60, 26, 8, '777', 'yes', NULL, '2024-07-29 13:35:45', '2024-07-29 13:35:45'),
(61, 27, 2, '66', NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(62, 27, 3, '500', NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(63, 27, 4, '45', NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(64, 27, 7, '66', NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(65, 27, 8, '43', NULL, NULL, '2024-07-29 13:52:29', '2024-07-29 13:52:29'),
(66, 28, 3, '456', 'yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(67, 28, 4, '66', 'yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(68, 28, 7, '66', 'yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(69, 28, 8, '777', 'yes', NULL, '2024-07-29 13:56:13', '2024-07-29 13:56:13'),
(70, 29, 2, '66', NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(71, 29, 3, '500', NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(72, 29, 4, '45', NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(73, 29, 7, '66', NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(74, 29, 8, '43', NULL, NULL, '2024-07-29 13:58:12', '2024-07-29 13:58:12'),
(75, 30, 2, '54', NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(76, 30, 3, '23', NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(77, 30, 7, '45', NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(78, 30, 8, '66', NULL, NULL, '2024-07-29 14:01:10', '2024-07-29 14:01:10'),
(79, 31, 2, '54', NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(80, 31, 3, '23', NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(81, 31, 7, '45', NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(82, 31, 8, '66', NULL, '2024-07-29 14:19:21', '2024-07-29 14:18:10', '2024-07-29 14:19:21'),
(83, 31, 2, '54', NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(84, 31, 3, '23', NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(85, 31, 7, '45', NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(86, 31, 8, '66', NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:21', '2024-07-29 14:19:24'),
(87, 31, 2, '54', NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(88, 31, 3, '23', NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(89, 31, 7, '45', NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(90, 31, 8, '66', NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(91, 32, 2, '54', NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(92, 32, 3, '23', NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(93, 32, 7, '45', NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(94, 32, 8, '66', NULL, '2024-07-29 14:27:12', '2024-07-29 14:25:32', '2024-07-29 14:27:12'),
(95, 32, 2, '54', NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(96, 32, 3, '23', NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(97, 32, 7, '45', NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(98, 32, 8, '66', NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(99, 33, 2, '54', NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(100, 33, 3, '23', NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(101, 33, 7, '45', NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(102, 33, 8, '66', NULL, '2024-07-29 14:33:44', '2024-07-29 14:31:51', '2024-07-29 14:33:44'),
(103, 33, 2, '54', NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(104, 33, 3, '23', NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(105, 33, 7, '45', NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(106, 33, 8, '66', NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(107, 34, 2, '54', NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(108, 34, 3, '23', NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(109, 34, 7, '45', NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(110, 34, 8, '66', NULL, NULL, '2024-07-29 14:40:42', '2024-07-29 14:40:42'),
(111, 35, 2, '290', 'ok', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(112, 35, 3, '300', 'ok', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(113, 35, 4, '500', 'ok', NULL, '2024-07-30 01:36:49', '2024-07-30 01:36:49'),
(114, 36, 2, '66', NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(115, 36, 3, '500', NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(116, 36, 4, '45', NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(117, 36, 7, '66', NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(118, 36, 8, '43', NULL, NULL, '2024-07-30 01:38:55', '2024-07-30 01:38:55'),
(119, 37, 7, '2', 'TEST', '2024-07-30 08:49:07', '2024-07-30 04:16:21', '2024-07-30 08:49:07'),
(120, 37, 7, '2', 'TEST', NULL, '2024-07-30 08:49:07', '2024-07-30 08:49:07'),
(121, 38, 2, '10', NULL, NULL, '2024-07-30 09:05:43', '2024-07-30 09:05:43'),
(122, 39, 11, '20', 'test', NULL, '2024-07-30 13:46:02', '2024-07-30 13:46:02'),
(123, 40, 11, '10', NULL, NULL, '2024-07-30 14:35:28', '2024-07-30 14:35:28'),
(124, 42, 15, '10', 'TEST', '2024-07-30 15:07:08', '2024-07-30 14:59:59', '2024-07-30 15:07:08'),
(125, 41, 15, '10', 'TEST', NULL, '2024-07-30 14:59:59', '2024-07-30 14:59:59'),
(126, 43, 12, '34', 'iiii', NULL, '2024-08-07 03:53:38', '2024-08-07 03:53:38'),
(127, 43, 13, '44', 'Hello Testing', NULL, '2024-08-07 03:53:38', '2024-08-07 03:53:38'),
(128, 44, 15, '10', 'TEST', NULL, '2024-08-07 03:54:34', '2024-08-07 03:54:34'),
(129, 45, 2, '290', 'ok', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(130, 45, 3, '300', 'ok', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(131, 45, 4, '500', 'ok', NULL, '2024-08-07 03:56:36', '2024-08-07 03:56:36'),
(132, 46, 12, '34', 'iiii', NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(133, 46, 13, '44', 'Hello Testing', NULL, '2024-08-09 04:31:36', '2024-08-09 04:31:36'),
(134, 47, 3, '0', '', NULL, '2024-10-22 04:47:22', '2024-10-22 04:47:22'),
(135, 47, 4, '0', '', NULL, '2024-10-22 04:47:22', '2024-10-22 04:47:22');

-- --------------------------------------------------------

--
-- Table structure for table `bom_verifications`
--

CREATE TABLE `bom_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bom_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bom_verifications`
--

INSERT INTO `bom_verifications` (`id`, `bom_id`, `status`, `date`, `approved_by`, `department_id`, `reason`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2', 'Submitted', '2024-07-29 07:04:29', '1', NULL, NULL, NULL, '2024-07-29 07:04:29', '2024-07-29 07:04:29'),
(2, '2', 'Verified', '2024-07-29 07:06:17', '1', NULL, NULL, NULL, '2024-07-29 07:06:17', '2024-07-29 07:06:17'),
(3, '3', 'Submitted', '2024-07-29 08:37:06', '1', NULL, NULL, NULL, '2024-07-29 08:37:06', '2024-07-29 08:37:06'),
(4, '3', 'Verified', '2024-07-29 08:37:15', '1', NULL, NULL, NULL, '2024-07-29 08:37:15', '2024-07-29 08:37:15'),
(5, '4', 'Submitted', '2024-07-29 08:42:30', '1', NULL, NULL, NULL, '2024-07-29 08:42:30', '2024-07-29 08:42:30'),
(6, '4', 'Verified', '2024-07-29 08:42:38', '1', NULL, NULL, NULL, '2024-07-29 08:42:38', '2024-07-29 08:42:38'),
(7, '5', 'Submitted', '2024-07-29 10:03:23', '1', NULL, NULL, NULL, '2024-07-29 10:03:23', '2024-07-29 10:03:23'),
(8, '5', 'Verified', '2024-07-29 10:14:12', '1', NULL, NULL, NULL, '2024-07-29 10:14:12', '2024-07-29 10:14:12'),
(9, '10', 'Verified', '2024-07-29 11:09:05', '1', NULL, NULL, NULL, '2024-07-29 11:09:05', '2024-07-29 11:09:05'),
(10, '11', 'Verified', '2024-07-29 11:16:40', '1', NULL, NULL, NULL, '2024-07-29 11:16:40', '2024-07-29 11:16:40'),
(11, '7', 'Verified', '2024-07-29 11:23:53', '1', NULL, NULL, NULL, '2024-07-29 11:23:53', '2024-07-29 11:23:53'),
(12, '13', 'Verified', '2024-07-29 11:29:10', '1', NULL, NULL, NULL, '2024-07-29 11:29:10', '2024-07-29 11:29:10'),
(13, '14', 'Submitted', '2024-07-29 11:30:26', '1', NULL, NULL, NULL, '2024-07-29 11:30:26', '2024-07-29 11:30:26'),
(14, '14', 'Verified', '2024-07-29 11:30:45', '1', NULL, NULL, NULL, '2024-07-29 11:30:45', '2024-07-29 11:30:45'),
(15, '21', 'Submitted', '2024-07-29 12:01:20', '1', NULL, NULL, NULL, '2024-07-29 12:01:20', '2024-07-29 12:01:20'),
(16, '21', 'Verified', '2024-07-29 12:04:18', '1', NULL, NULL, NULL, '2024-07-29 12:04:18', '2024-07-29 12:04:18'),
(17, '23', 'Verified', '2024-07-29 13:12:43', '1', NULL, NULL, NULL, '2024-07-29 13:12:43', '2024-07-29 13:12:43'),
(18, '26', 'Verified', '2024-07-29 13:36:44', '1', NULL, NULL, NULL, '2024-07-29 13:36:44', '2024-07-29 13:36:44'),
(19, '27', 'Verified', '2024-07-29 13:52:55', '1', NULL, NULL, NULL, '2024-07-29 13:52:55', '2024-07-29 13:52:55'),
(20, '30', 'Verified', '2024-07-29 14:02:37', '1', NULL, NULL, NULL, '2024-07-29 14:02:37', '2024-07-29 14:02:37'),
(21, '31', 'Submitted', '2024-07-29 14:19:21', '1', NULL, NULL, NULL, '2024-07-29 14:19:21', '2024-07-29 14:19:21'),
(22, '31', 'Submitted', '2024-07-29 14:19:24', '1', NULL, NULL, NULL, '2024-07-29 14:19:24', '2024-07-29 14:19:24'),
(23, '31', 'Verified', '2024-07-29 14:19:43', '1', NULL, NULL, NULL, '2024-07-29 14:19:43', '2024-07-29 14:19:43'),
(24, '32', 'Submitted', '2024-07-29 14:27:12', '1', NULL, NULL, NULL, '2024-07-29 14:27:12', '2024-07-29 14:27:12'),
(25, '32', 'Verified', '2024-07-29 14:27:31', '1', NULL, NULL, NULL, '2024-07-29 14:27:31', '2024-07-29 14:27:31'),
(26, '33', 'Submitted', '2024-07-29 14:33:44', '1', NULL, NULL, NULL, '2024-07-29 14:33:44', '2024-07-29 14:33:44'),
(27, '33', 'Verified', '2024-07-29 14:35:09', '1', NULL, NULL, NULL, '2024-07-29 14:35:09', '2024-07-29 14:35:09'),
(28, '29', 'Verified', '2024-07-29 14:41:05', '1', NULL, NULL, NULL, '2024-07-29 14:41:05', '2024-07-29 14:41:05'),
(29, '35', 'Verified', '2024-07-30 01:37:02', '1', NULL, NULL, NULL, '2024-07-30 01:37:02', '2024-07-30 01:37:02'),
(30, '37', 'Submitted', '2024-07-30 08:49:07', '1', NULL, NULL, NULL, '2024-07-30 08:49:07', '2024-07-30 08:49:07'),
(31, '37', 'Verified', '2024-07-30 08:49:31', '1', NULL, NULL, NULL, '2024-07-30 08:49:31', '2024-07-30 08:49:31'),
(32, '39', 'Verified', '2024-07-30 13:46:18', '1', NULL, NULL, NULL, '2024-07-30 13:46:18', '2024-07-30 13:46:18'),
(33, '41', 'Verified', '2024-07-30 15:09:07', '1', NULL, NULL, NULL, '2024-07-30 15:09:07', '2024-07-30 15:09:07'),
(34, '43', 'Verified', '2024-08-07 03:55:10', '1', NULL, NULL, NULL, '2024-08-07 03:55:10', '2024-08-07 03:55:10'),
(35, '49', 'Submitted', '2024-10-22 10:03:12', '1', '1', NULL, NULL, '2024-10-22 05:03:12', '2024-10-22 05:03:12'),
(36, '49', 'Submitted', '2024-10-22 10:03:36', '1', '1', NULL, NULL, '2024-10-22 05:03:36', '2024-10-22 05:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `call_for_assistances`
--

CREATE TABLE `call_for_assistances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `datetime` varchar(255) DEFAULT NULL,
  `mc_no` varchar(255) DEFAULT NULL,
  `call` varchar(255) DEFAULT NULL,
  `package_no` varchar(255) DEFAULT NULL,
  `msg_no` varchar(255) DEFAULT NULL,
  `attended_datetime` varchar(255) DEFAULT NULL,
  `attended_pic` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `submitted_datetime` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `call_for_assistances`
--

INSERT INTO `call_for_assistances` (`id`, `datetime`, `mc_no`, `call`, `package_no`, `msg_no`, `attended_datetime`, `attended_pic`, `remarks`, `submitted_datetime`, `status`, `image`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '29-07-2024 03:41:07 PM', 'M1', '0', '001', 'M1/001_2024-07-29 15:41:07_1', '29-07-2024 03:43:18 PM', NULL, NULL, NULL, 'Completed', NULL, NULL, '2024-07-29 07:41:18', '2024-07-29 07:43:37'),
(2, '30-07-2024 11:44:05 PM', 'M1', '0', '001', 'M1/001_2024-07-30 23:44:05_5888', '30-07-2024 11:48:22 PM', NULL, NULL, NULL, 'Completed', NULL, NULL, '2024-07-30 15:44:06', '2024-07-30 15:48:23'),
(3, '31-07-2024 07:39:24 AM', 'M2', '0', '001', 'M2/001_2024-07-31 07:39:24_5888', '31-07-2024 07:41:17 AM', NULL, NULL, NULL, 'Completed', NULL, NULL, '2024-07-30 23:39:24', '2024-07-30 23:41:17'),
(4, '31-07-2024 12:04:37 PM', 'M1', '0', '001', 'M1/001_2024-07-31 12:04:37_5888', '31-07-2024 12:05:36 PM', NULL, NULL, NULL, 'Completed', NULL, NULL, '2024-07-31 04:04:39', '2024-07-31 04:05:39'),
(5, '31-07-2024 11:33:58 PM', 'M2', '0', '001', 'M2/001_2024-07-31 23:33:58_1', '31-07-2024 11:57:46 PM', '2', 'exceed', '31-07-2024 23:41:33 PM', 'Completed', '2024073115413320230202_172123.jpg', NULL, '2024-07-31 15:34:05', '2024-07-31 15:58:00'),
(6, '01-08-2024 01:23:52 AM', 'M2', '0', '001', 'M2/001_2024-08-01 01:23:52_1', '01-08-2024 01:43:57 AM', '2', 'rs485', '01-08-2024 01:53:24 AM', 'Submitted', '2024073117532420230109_151527.jpg', NULL, '2024-07-31 17:24:20', '2024-07-31 17:53:24'),
(7, '01-08-2024 08:40:49 AM', 'M1', '0', '001', 'M1/001_2024-08-01 08:40:49_5888', '01-08-2024 08:41:03 AM', NULL, NULL, NULL, 'Completed', NULL, NULL, '2024-08-01 00:40:51', '2024-08-01 00:41:04'),
(8, '01-08-2024 12:05:22 PM', 'M2', '0', '001', 'M2/001_2024-08-01 12:05:22_5888', '01-08-2024 12:14:39 PM', '2', 'Machine issue', '01-08-2024 12:14:25 PM', 'Completed', '20240801041425istockphoto-510162486-612x612.jpg', NULL, '2024-08-01 04:05:23', '2024-08-01 04:14:42'),
(9, '01-08-2024 05:05:21 PM', 'M12', '0', '001', 'M12/001_2024-08-01 17:05:21_4', '01-08-2024 05:07:12 PM', '2', 'Nozzle', '01-08-2024 17:08:44 PM', 'Submitted', NULL, NULL, '2024-08-01 09:05:28', '2024-08-01 09:08:44'),
(10, '01-08-2024 05:08:06 PM', 'M12', '0', '001', 'M12/001_2024-08-01 17:08:06_7', '01-08-2024 05:08:10 PM', NULL, NULL, NULL, 'Completed', NULL, NULL, '2024-08-01 09:08:58', '2024-08-01 09:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `carry_forwards`
--

CREATE TABLE `carry_forwards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year` int(10) UNSIGNED NOT NULL,
  `balance` decimal(15,2) NOT NULL,
  `status` enum('profit','loss') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `pic_name` varchar(255) DEFAULT NULL,
  `pic_department` varchar(255) DEFAULT NULL,
  `pic_phone_work` varchar(255) DEFAULT NULL,
  `pic_phone_mobile` varchar(255) DEFAULT NULL,
  `pic_fax` varchar(255) DEFAULT NULL,
  `pic_email` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `code`, `address`, `phone`, `pic_name`, `pic_department`, `pic_phone_work`, `pic_phone_mobile`, `pic_fax`, `pic_email`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Phoebe Hendrix', 'Corrupti consectetu', 'Consequuntur similiq', '93', 'Quentin Watson', 'Incidunt laborum ev', '21', '35', '+1 (295) 492-4934', 'wocix@mailinator.com', NULL, '2024-07-29 03:23:26', '2024-07-29 03:23:26'),
(2, 'Caesar Davenport', 'Corporis accusamus t', 'Sit perspiciatis e', '1', 'Alexandra Richmond', 'Distinctio Qui at q', '15', '54', '+1 (363) 404-9225', 'mifyvawos@mailinator.com', NULL, '2024-07-29 03:23:37', '2024-07-29 03:23:37'),
(3, 'A', 'A', 'A', '123456789', 'Sanya', 'Head of Engineering', '0123456789', '0123456789', '0123456789', 'abc@gmail.com', NULL, '2024-07-29 07:42:33', '2024-07-29 07:42:33'),
(4, 'Perodua', 'CUST01', 'Selangor', '0198881212', 'Mr. Rahman', 'Sales', '0198881212', NULL, NULL, 'rahman@gmail.com', NULL, '2024-07-30 03:33:49', '2024-07-30 03:33:49'),
(5, 'PERODUA_EXT', 'PEXT', 'Rawang, Selangor', '0192221123', 'Mr.Amirul', 'Sales', '0181231233', '0181231231', NULL, 'amirul@gmail.com', NULL, '2024-07-30 13:55:29', '2024-07-30 13:55:29'),
(6, 'CUSTOMER 6', 'CUSTOMER_006', 'Rawang Selangor', '0192221123', 'Mr.Amirul Ariff', 'Sales', '0171111212', '0171111211', NULL, 'afif@gmail.com', NULL, '2024-07-30 14:49:06', '2024-07-30 14:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_child_parts`
--

CREATE TABLE `daily_production_child_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dpp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_part_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `subpart_qty` varchar(255) DEFAULT NULL,
  `inventory_qty` varchar(255) DEFAULT NULL,
  `total_required_qty` varchar(255) DEFAULT NULL,
  `est_plan_qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_production_child_parts`
--

INSERT INTO `daily_production_child_parts` (`id`, `dpp_id`, `parent_part_id`, `product_id`, `subpart_qty`, `inventory_qty`, `total_required_qty`, `est_plan_qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '3', '54', '300', '2160', '1860', NULL, '2024-07-29 07:17:13', '2024-07-29 07:17:13'),
(2, 1, '1', '4', '23', '400', '920', '520', NULL, '2024-07-29 07:17:13', '2024-07-29 07:17:13'),
(3, 3, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(4, 3, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(5, 3, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(6, 4, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(7, 4, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(8, 4, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(9, 5, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(10, 5, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(11, 5, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(12, 7, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(13, 7, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(14, 7, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(15, 8, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(16, 8, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(17, 8, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(18, 9, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(19, 9, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(20, 9, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(21, 10, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(22, 10, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(23, 10, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(24, 12, '1', '2', '290', '200', '209885760', '209885560', NULL, '2024-07-30 08:24:15', '2024-07-30 08:24:15'),
(25, 12, '1', '4', '500', '400', '361872000', '361871600', NULL, '2024-07-30 08:24:15', '2024-07-30 08:24:15'),
(26, 12, '1', '3', '300', '300', '217123200', '217122900', NULL, '2024-07-30 08:24:15', '2024-07-30 08:24:15'),
(27, 13, '1', '2', '290', '200', '209885760', '209885560', NULL, '2024-07-30 08:25:29', '2024-07-30 08:25:29'),
(28, 13, '1', '3', '300', '300', '217123200', '217122900', NULL, '2024-07-30 08:25:29', '2024-07-30 08:25:29'),
(29, 13, '1', '4', '500', '400', '361872000', '361871600', NULL, '2024-07-30 08:25:29', '2024-07-30 08:25:29'),
(30, 18, '1', '4', '500', '400', '20000', '19600', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(31, 18, '1', '3', '300', '300', '12000', '11700', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(32, 18, '1', '2', '290', '200', '11600', '11400', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(33, 33, '5', '7', '2', '0', '2420', '2420', NULL, '2024-07-31 03:33:28', '2024-07-31 03:33:28'),
(34, 34, '5', '7', '2', '0', '2466', '2466', NULL, '2024-07-31 06:26:33', '2024-07-31 06:26:33'),
(35, 35, '14', '15', '10', '0', '700', '700', NULL, '2024-07-31 11:32:19', '2024-07-31 11:32:19'),
(36, 36, '5', '7', '2', '0', '2420', '2420', NULL, '2024-07-31 14:07:19', '2024-07-31 14:07:19'),
(37, 37, '5', '7', '2', '0', '2420', '2420', NULL, '2024-07-31 14:45:52', '2024-07-31 14:45:52'),
(38, 39, '5', '7', '2', '0', '2420', '2420', NULL, '2024-08-01 07:55:00', '2024-08-01 07:55:00'),
(39, 40, '1', '13', '44', '0', '1760', '1760', NULL, '2024-08-07 04:32:11', '2024-08-07 04:32:11'),
(40, 40, '1', '12', '34', '0', '1360', '1360', NULL, '2024-08-07 04:32:11', '2024-08-07 04:32:11');

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_plannings`
--

CREATE TABLE `daily_production_plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `planning_date` varchar(255) NOT NULL,
  `created_date` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_production_plannings`
--

INSERT INTO `daily_production_plannings` (`id`, `order_id`, `customer_id`, `planning_date`, `created_date`, `created_by`, `ref_no`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '1', '2024-07-29', '2024-07-29', '1', '13/1/24', 'In Progress', NULL, '2024-07-29 07:17:13', '2024-07-29 07:17:13'),
(2, '2', '3', '2024-07-29', '2024-07-29', '1', '13/2/24', 'In Progress', NULL, '2024-07-29 08:46:58', '2024-07-29 08:46:58'),
(3, '1', '1', '2024-07-30', '2024-07-30', '1', '13/3/24', 'In Progress', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(4, '1', '1', '2024-07-30', '2024-07-30', '1', '13/4/24', 'In Progress', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(5, '1', '1', '2024-07-30', '2024-07-30', '1', '13/4/24', 'In Progress', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(6, '1', '1', '2024-07-30', '2024-07-30', '1', '13/4/24', 'In Progress', NULL, '2024-07-30 07:13:45', '2024-07-30 07:13:45'),
(7, '1', '1', '2024-07-30', '2024-07-30', '1', '13/5/24', 'In Progress', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(8, '1', '1', '2024-07-30', '2024-07-30', '1', '13/5/24', 'In Progress', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(9, '1', '1', '2024-07-30', '2024-07-30', '1', '13/5/24', 'In Progress', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(10, '1', '1', '2024-07-30', '2024-07-30', '1', '13/6/24', 'In Progress', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(11, '4', '4', '2024-07-30', '2024-07-30', '1', '13/7/24', 'In Progress', '2024-07-30 08:43:28', '2024-07-30 08:23:37', '2024-07-30 08:43:28'),
(12, '3', '1', '2024-07-30', '2024-07-30', '1', '13/7/24', 'In Progress', NULL, '2024-07-30 08:24:15', '2024-07-30 08:24:15'),
(13, '3', '1', '2024-07-30', '2024-07-30', '1', '13/7/24', 'In Progress', NULL, '2024-07-30 08:25:29', '2024-07-30 08:25:29'),
(14, '4', '4', '2024-08-05', '2024-07-30', '1', '13/8/24', 'In Progress', '2024-07-30 08:50:27', '2024-07-30 08:44:57', '2024-07-30 08:50:27'),
(15, '4', '4', '2024-08-05', '2024-07-30', '1', '13/8/24', 'In Progress', '2024-07-30 09:00:50', '2024-07-30 08:51:54', '2024-07-30 09:00:50'),
(16, '5', '3', '2024-07-30', '2024-07-30', '1', '13/9/24', 'In Progress', NULL, '2024-07-30 08:58:18', '2024-07-30 08:58:18'),
(17, '5', '3', '2024-07-30', '2024-07-30', '1', '13/9/24', 'In Progress', '2024-07-30 15:06:23', '2024-07-30 08:58:58', '2024-07-30 15:06:23'),
(18, '1', '1', '2024-07-30', '2024-07-30', '1', '13/10/24', 'In Progress', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(19, '6', '5', '2024-07-30', '2024-07-30', '1', '13/11/24', 'In Progress', NULL, '2024-07-30 14:05:39', '2024-07-30 14:05:39'),
(20, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:40:39', '2024-07-30 14:38:08', '2024-07-30 14:40:39'),
(21, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:40:09', '2024-07-30 14:38:17', '2024-07-30 14:40:09'),
(22, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:40:17', '2024-07-30 14:38:17', '2024-07-30 14:40:17'),
(23, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:40:22', '2024-07-30 14:38:17', '2024-07-30 14:40:22'),
(24, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:39:39', '2024-07-30 14:38:17', '2024-07-30 14:39:39'),
(25, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:39:14', '2024-07-30 14:38:18', '2024-07-30 14:39:14'),
(26, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:39:15', '2024-07-30 14:38:18', '2024-07-30 14:39:15'),
(27, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:39:54', '2024-07-30 14:38:18', '2024-07-30 14:39:54'),
(28, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:40:02', '2024-07-30 14:38:18', '2024-07-30 14:40:02'),
(29, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', '2024-07-30 14:39:12', '2024-07-30 14:38:19', '2024-07-30 14:39:12'),
(30, '7', '5', '2024-07-30', '2024-07-30', '1', '13/12/24', 'In Progress', NULL, '2024-07-30 14:42:36', '2024-07-30 14:42:36'),
(31, '8', '6', '2024-07-30', '2024-07-30', '1', '13/13/24', 'In Progress', '2024-07-30 15:07:59', '2024-07-30 15:05:10', '2024-07-30 15:07:59'),
(32, '8', '6', '2024-07-30', '2024-07-30', '1', '13/13/24', 'In Progress', NULL, '2024-07-30 15:08:29', '2024-07-30 15:08:29'),
(33, '4', '4', '2024-08-01', '2024-07-31', '1', '13/14/24', 'In Progress', NULL, '2024-07-31 03:33:28', '2024-07-31 03:33:28'),
(34, '5', '3', '2024-07-31', '2024-07-31', '1', '13/15/24', 'In Progress', NULL, '2024-07-31 06:26:33', '2024-07-31 06:26:33'),
(35, '8', '6', '2024-07-31', '2024-07-31', '1', '13/16/24', 'In Progress', NULL, '2024-07-31 11:32:19', '2024-07-31 11:32:19'),
(36, '4', '4', '2024-07-31', '2024-07-31', '1', '13/17/24', 'In Progress', NULL, '2024-07-31 14:07:19', '2024-07-31 14:07:19'),
(37, '4', '4', '2024-07-31', '2024-07-31', '1', '13/17/24', 'In Progress', NULL, '2024-07-31 14:45:52', '2024-07-31 14:45:52'),
(38, '7', '5', '2024-08-01', '2024-08-01', '1', '13/18/24', 'In Progress', NULL, '2024-08-01 07:54:43', '2024-08-01 07:54:43'),
(39, '4', '4', '2024-08-01', '2024-08-01', '1', '13/19/24', 'In Progress', NULL, '2024-08-01 07:55:00', '2024-08-01 07:55:00'),
(40, '1', '1', '2024-08-07', '2024-08-07', '1', '13/20/24', 'Completed', NULL, '2024-08-07 04:32:11', '2024-08-07 04:32:11'),
(41, '1', '1', '2024-08-09', '2024-08-09', '1', 'DPP/21/24', 'Completed', NULL, '2024-08-09 04:49:41', '2024-08-09 04:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_planning_details`
--

CREATE TABLE `daily_production_planning_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dpp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `pro_order_no` varchar(255) DEFAULT NULL,
  `planned_date` varchar(255) DEFAULT NULL,
  `op_name` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `spec_break` varchar(255) DEFAULT NULL,
  `planned_qty` varchar(255) DEFAULT NULL,
  `machine` varchar(255) DEFAULT NULL,
  `tonnage` varchar(255) DEFAULT NULL,
  `cavity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_production_planning_details`
--

INSERT INTO `daily_production_planning_details` (`id`, `dpp_id`, `product_id`, `pro_order_no`, `planned_date`, `op_name`, `shift`, `spec_break`, `planned_qty`, `machine`, `tonnage`, `cavity`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '13/1/24 /Rhoda Osborne/1', '2024-07-29', '[\"2\"]', 'AM', 'Normal Hour', '10', '1', '1', '2', '2024-07-29 07:17:57', '2024-07-29 07:17:57'),
(2, 2, '5', '13/2/24/ASSEMBLE/1', '2024-07-29', '[\"2\"]', 'AM', 'Normal Hour', '1617', '1', '1', 'N/A', '2024-07-29 08:51:38', '2024-07-29 08:51:38'),
(3, 4, '1', '13/4/24 /Rhoda Osborne/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '150', '1', '1', '1', '2024-07-30 07:05:15', '2024-07-30 07:05:15'),
(4, 5, '1', '13/4/24 /Rhoda Osborne/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '150', '1', '1', '1', '2024-07-30 07:11:34', '2024-07-30 07:11:34'),
(5, 7, '1', '13/5/24 /Rhoda Osborne/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '100', '1', '1', '1', '2024-07-30 08:10:33', '2024-07-30 08:10:33'),
(6, 8, '1', '13/5/24 /Rhoda Osborne/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '100', '1', '1', '1', '2024-07-30 08:13:38', '2024-07-30 08:13:38'),
(7, 9, '1', '13/5/24 /Rhoda Osborne/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '11', '1', '1', '1', '2024-07-30 08:15:22', '2024-07-30 08:15:22'),
(8, 12, '1', '13/7/24 /Rhoda Osborne/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '100', '1', '1', '12', '2024-07-30 08:24:52', '2024-07-30 08:24:52'),
(10, 18, '1', '13/10/24 /Rhoda Osborne/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '123', '1', '1', '12', '2024-07-30 09:56:30', '2024-07-30 09:56:30'),
(11, 19, '10', '13/11/24/ASSEMBLE/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '1200', '16', '2', '1', '2024-07-30 14:07:57', '2024-07-30 14:07:57'),
(12, 19, '10', '13/11/24/ASSEMBLE/1', '2024-07-30', '[\"1\"]', 'AM', 'Normal Hour', '1200', '16', '2', '1', '2024-07-30 14:07:57', '2024-07-30 14:07:57'),
(13, 32, '14', '13/13/24/Injection/1', '2024-07-30', '[\"1\"]', 'PM', 'Normal Hour', '50', '4', '2', '1', '2024-07-30 15:11:12', '2024-07-30 15:11:12'),
(14, 33, '5', '13/14/24 /ASSEMBLE/1', '2024-08-01', '[\"2\"]', 'AM', 'Normal Hour', '1210', '5', '1', '1', '2024-07-31 03:39:44', '2024-07-31 03:39:44'),
(15, 34, '5', '13/15/24 /ASSEMBLE/1', '2024-08-01', '[\"1\"]', 'AM', 'Normal Hour', '1617', '14', '1', '1', '2024-07-31 06:27:28', '2024-07-31 06:27:28'),
(16, 16, '5', '13/9/24/ASSEMBLE/1', '2024-07-25', '[\"1\"]', 'AM', 'Normal Hour', '1617', '1', '1', '22', '2024-07-31 11:31:50', '2024-07-31 11:31:50'),
(17, 35, '14', '13/16/24 /Injection/1', '2024-07-31', '[\"2\"]', 'AM', 'Normal Hour', '2', '1', '1', '1', '2024-07-31 11:32:34', '2024-07-31 11:32:34'),
(18, 36, '5', '13/17/24 /ASSEMBLE/1', '2024-07-31', '[\"1\"]', 'AM', 'Normal Hour', '1000', '1', '1', '2', '2024-07-31 14:11:35', '2024-07-31 14:11:35'),
(19, 37, '5', '13/17/24/ASSEMBLE/1', '2024-08-01', '[\"1\"]', 'AM', 'Normal Hour', '1210', '1', '1', '1', '2024-08-01 07:54:19', '2024-08-01 07:54:19'),
(20, 37, '5', '13/17/24/ASSEMBLE/1', '2024-08-01', '[\"1\"]', 'AM', 'Normal Hour', '1210', '1', '1', '1', '2024-08-01 07:54:25', '2024-08-01 07:54:25'),
(21, 39, '5', '13/19/24 /ASSEMBLE/1', '2024-08-01', '[\"1\"]', 'AM', 'Normal Hour', '1210', '1', '1', '2', '2024-08-01 07:55:20', '2024-08-01 07:55:20'),
(22, 40, '1', '13/20/24 /ASSEMBLE/1', '2024-08-07', '[\"1\"]', 'AM', 'OT', '23', '1', '3', '2', '2024-08-07 04:35:34', '2024-08-07 04:35:34');

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_products`
--

CREATE TABLE `daily_production_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dpp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` varchar(255) NOT NULL,
  `di_qty` varchar(255) NOT NULL,
  `inventory_qty` varchar(255) NOT NULL,
  `total_required_qty` varchar(255) NOT NULL,
  `est_plan_qty` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_production_products`
--

INSERT INTO `daily_production_products` (`id`, `dpp_id`, `product_id`, `di_qty`, `inventory_qty`, `total_required_qty`, `est_plan_qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '40', '100', '70', '70', NULL, '2024-07-29 07:17:13', '2024-07-29 07:17:13'),
(2, 1, '2', '40', '200', '40', '40', NULL, '2024-07-29 07:17:13', '2024-07-29 07:17:13'),
(3, 1, '3', '40', '300', '40', '40', NULL, '2024-07-29 07:17:13', '2024-07-29 07:17:13'),
(4, 1, '4', '60', '400', '60', '60', NULL, '2024-07-29 07:17:13', '2024-07-29 07:17:13'),
(5, 2, '5', '1233', '0', '1617', '1617', NULL, '2024-07-29 08:46:58', '2024-07-29 08:46:58'),
(6, 2, '6', '1233', '0', '1617', '1617', NULL, '2024-07-29 08:46:58', '2024-07-29 08:46:58'),
(7, 2, '7', '1233', '0', '1617', '1617', NULL, '2024-07-29 08:46:58', '2024-07-29 08:46:58'),
(8, 3, '1', '40', '100', '70', '70', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(9, 3, '2', '40', '200', '40', '40', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(10, 3, '3', '40', '300', '40', '40', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(11, 3, '4', '60', '400', '60', '60', NULL, '2024-07-30 06:22:12', '2024-07-30 06:22:12'),
(12, 4, '1', '40', '100', '70', '70', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(13, 4, '2', '40', '200', '40', '40', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(14, 4, '3', '40', '300', '40', '40', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(15, 4, '4', '60', '400', '60', '60', NULL, '2024-07-30 06:48:57', '2024-07-30 06:48:57'),
(16, 5, '1', '40', '100', '70', '70', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(17, 5, '2', '40', '200', '40', '40', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(18, 5, '3', '40', '300', '40', '40', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(19, 5, '4', '60', '400', '60', '60', NULL, '2024-07-30 07:08:37', '2024-07-30 07:08:37'),
(20, 6, '1', '40', '100', '70', '70', NULL, '2024-07-30 07:13:45', '2024-07-30 07:13:45'),
(21, 6, '2', '40', '200', '40', '40', NULL, '2024-07-30 07:13:45', '2024-07-30 07:13:45'),
(22, 6, '3', '40', '300', '40', '40', NULL, '2024-07-30 07:13:45', '2024-07-30 07:13:45'),
(23, 6, '4', '60', '400', '60', '60', NULL, '2024-07-30 07:13:45', '2024-07-30 07:13:45'),
(24, 7, '1', '40', '100', '70', '70', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(25, 7, '2', '40', '200', '40', '40', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(26, 7, '3', '40', '300', '40', '40', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(27, 7, '4', '60', '400', '60', '60', NULL, '2024-07-30 08:10:12', '2024-07-30 08:10:12'),
(28, 8, '1', '40', '100', '70', '70', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(29, 8, '2', '40', '200', '40', '40', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(30, 8, '3', '40', '300', '40', '40', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(31, 8, '4', '60', '400', '60', '60', NULL, '2024-07-30 08:13:23', '2024-07-30 08:13:23'),
(32, 9, '1', '40', '100', '70', '70', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(33, 9, '2', '40', '200', '40', '40', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(34, 9, '3', '40', '300', '40', '40', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(35, 9, '4', '60', '400', '60', '60', NULL, '2024-07-30 08:14:38', '2024-07-30 08:14:38'),
(36, 10, '1', '40', '100', '70', '70', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(37, 10, '2', '40', '200', '40', '40', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(38, 10, '3', '40', '300', '40', '40', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(39, 10, '4', '60', '400', '60', '60', NULL, '2024-07-30 08:22:38', '2024-07-30 08:22:38'),
(40, 11, '5', '910', '0', '910', '910', '2024-07-30 08:43:28', '2024-07-30 08:23:37', '2024-07-30 08:43:28'),
(41, 12, '1', '723744', '100', '724156', '724056', NULL, '2024-07-30 08:24:15', '2024-07-30 08:24:15'),
(42, 13, '1', '723744', '100', '724156', '724056', NULL, '2024-07-30 08:25:29', '2024-07-30 08:25:29'),
(43, 14, '5', '910', '0', '910', '910', '2024-07-30 08:50:27', '2024-07-30 08:44:57', '2024-07-30 08:50:27'),
(44, 15, '5', '910', '0', '910', '910', '2024-07-30 09:00:50', '2024-07-30 08:51:54', '2024-07-30 09:00:50'),
(45, 16, '5', '1233', '0', '1617', '1617', NULL, '2024-07-30 08:58:18', '2024-07-30 08:58:18'),
(46, 16, '6', '1233', '0', '1617', '1617', NULL, '2024-07-30 08:58:18', '2024-07-30 08:58:18'),
(47, 16, '7', '1233', '0', '1617', '1617', NULL, '2024-07-30 08:58:18', '2024-07-30 08:58:18'),
(48, 17, '5', '1233', '0', '1617', '1617', '2024-07-30 15:06:23', '2024-07-30 08:58:58', '2024-07-30 15:06:23'),
(49, 17, '6', '1233', '0', '1617', '1617', '2024-07-30 15:06:23', '2024-07-30 08:58:58', '2024-07-30 15:06:23'),
(50, 17, '7', '1233', '0', '1617', '1617', '2024-07-30 15:06:23', '2024-07-30 08:58:58', '2024-07-30 15:06:23'),
(51, 18, '1', '40', '100', '70', '70', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(52, 18, '2', '40', '200', '40', '40', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(53, 18, '3', '40', '300', '40', '40', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(54, 18, '4', '60', '400', '60', '60', NULL, '2024-07-30 09:56:11', '2024-07-30 09:56:11'),
(55, 19, '10', '1000', '0', '1200', '1200', NULL, '2024-07-30 14:05:39', '2024-07-30 14:05:39'),
(56, 20, '13', '500', '0', '500', '500', '2024-07-30 14:40:39', '2024-07-30 14:38:08', '2024-07-30 14:40:39'),
(57, 21, '13', '500', '0', '500', '500', '2024-07-30 14:40:09', '2024-07-30 14:38:17', '2024-07-30 14:40:09'),
(58, 22, '13', '500', '0', '500', '500', '2024-07-30 14:40:17', '2024-07-30 14:38:17', '2024-07-30 14:40:17'),
(59, 23, '13', '500', '0', '500', '500', '2024-07-30 14:40:22', '2024-07-30 14:38:17', '2024-07-30 14:40:22'),
(60, 24, '13', '500', '0', '500', '500', '2024-07-30 14:39:39', '2024-07-30 14:38:17', '2024-07-30 14:39:39'),
(61, 25, '13', '500', '0', '500', '500', '2024-07-30 14:39:14', '2024-07-30 14:38:18', '2024-07-30 14:39:14'),
(62, 26, '13', '500', '0', '500', '500', '2024-07-30 14:39:15', '2024-07-30 14:38:18', '2024-07-30 14:39:15'),
(63, 27, '13', '500', '0', '500', '500', '2024-07-30 14:39:54', '2024-07-30 14:38:18', '2024-07-30 14:39:54'),
(64, 28, '13', '500', '0', '500', '500', '2024-07-30 14:40:02', '2024-07-30 14:38:18', '2024-07-30 14:40:02'),
(65, 29, '13', '500', '0', '500', '500', '2024-07-30 14:39:12', '2024-07-30 14:38:19', '2024-07-30 14:39:12'),
(66, 30, '13', '500', '0', '500', '500', NULL, '2024-07-30 14:42:36', '2024-07-30 14:42:36'),
(67, 31, '14', '50', '0', '50', '50', '2024-07-30 15:07:59', '2024-07-30 15:05:10', '2024-07-30 15:07:59'),
(68, 32, '14', '50', '0', '50', '50', NULL, '2024-07-30 15:08:29', '2024-07-30 15:08:29'),
(69, 33, '5', '1210', '0', '1210', '1210', NULL, '2024-07-31 03:33:28', '2024-07-31 03:33:28'),
(70, 34, '5', '1233', '0', '1617', '1617', NULL, '2024-07-31 06:26:33', '2024-07-31 06:26:33'),
(71, 34, '6', '1233', '0', '1617', '1617', NULL, '2024-07-31 06:26:33', '2024-07-31 06:26:33'),
(72, 34, '7', '1233', '0', '1617', '1617', NULL, '2024-07-31 06:26:33', '2024-07-31 06:26:33'),
(73, 35, '14', '70', '0', '70', '70', NULL, '2024-07-31 11:32:19', '2024-07-31 11:32:19'),
(74, 36, '5', '1210', '0', '1210', '1210', NULL, '2024-07-31 14:07:19', '2024-07-31 14:07:19'),
(75, 37, '5', '1210', '0', '1210', '1210', NULL, '2024-07-31 14:45:52', '2024-07-31 14:45:52'),
(76, 38, '13', '500', '0', '500', '500', NULL, '2024-08-01 07:54:43', '2024-08-01 07:54:43'),
(77, 39, '5', '1210', '0', '1210', '1210', NULL, '2024-08-01 07:55:00', '2024-08-01 07:55:00'),
(78, 40, '1', '40', '108', '70', '70', NULL, '2024-08-07 04:32:11', '2024-08-07 04:32:11'),
(79, 40, '2', '40', '162', '40', '40', NULL, '2024-08-07 04:32:11', '2024-08-07 04:32:11'),
(80, 40, '3', '40', '290', '40', '40', NULL, '2024-08-07 04:32:11', '2024-08-07 04:32:11'),
(81, 40, '4', '60', '395', '60', '60', NULL, '2024-08-07 04:32:11', '2024-08-07 04:32:11'),
(82, 41, '1', '40', '1275', '70', '70', NULL, '2024-08-09 04:49:41', '2024-08-09 04:49:41'),
(83, 41, '2', '40', '128', '40', '40', NULL, '2024-08-09 04:49:41', '2024-08-09 04:49:41'),
(84, 41, '3', '40', '290', '40', '40', NULL, '2024-08-09 04:49:41', '2024-08-09 04:49:41'),
(85, 41, '4', '60', '395', '60', '60', NULL, '2024-08-09 04:49:41', '2024-08-09 04:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_instructions`
--

CREATE TABLE `delivery_instructions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_instructions`
--

INSERT INTO `delivery_instructions` (`id`, `order_id`, `date`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '29-07-2024', 1, NULL, '2024-07-29 07:13:16', '2024-07-29 07:13:16'),
(2, 2, '29-07-2024', 1, '2024-07-30 03:59:34', '2024-07-29 08:44:18', '2024-07-30 03:59:34'),
(3, 3, '30-07-2024', 1, NULL, '2024-07-30 04:00:35', '2024-07-30 04:00:35'),
(4, 4, '31-07-2024', 1, NULL, '2024-07-30 08:10:28', '2024-07-31 00:12:22'),
(5, 5, '30-07-2024', 1, NULL, '2024-07-30 08:38:40', '2024-07-30 08:38:40'),
(6, 6, '30-07-2024', 1, NULL, '2024-07-30 14:01:43', '2024-07-30 14:01:43'),
(7, 7, '30-07-2024', 1, NULL, '2024-07-30 14:37:35', '2024-07-30 14:37:35'),
(8, 8, '31-07-2024', 1, NULL, '2024-07-30 15:04:34', '2024-07-30 16:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_instruction_details`
--

CREATE TABLE `delivery_instruction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `di_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `calendar` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_instruction_details`
--

INSERT INTO `delivery_instruction_details` (`id`, `di_id`, `product_id`, `calendar`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '[{\"day\":\"1\",\"value\":\"10\"},{\"day\":\"2\",\"value\":\"10\"},{\"day\":\"3\",\"value\":\"10\"},{\"day\":\"4\",\"value\":\"10\"},{\"day\":\"5\",\"value\":\"\"},{\"day\":\"6\",\"value\":\"\"},{\"day\":\"7\",\"value\":\"\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', NULL, '2024-07-29 07:13:16', '2024-07-29 07:13:16'),
(2, 1, 2, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"10\"},{\"day\":\"5\",\"value\":\"10\"},{\"day\":\"6\",\"value\":\"10\"},{\"day\":\"7\",\"value\":\"10\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', NULL, '2024-07-29 07:13:16', '2024-07-29 07:13:16'),
(3, 1, 3, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"\"},{\"day\":\"6\",\"value\":\"\"},{\"day\":\"7\",\"value\":\"\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"10\"},{\"day\":\"11\",\"value\":\"10\"},{\"day\":\"12\",\"value\":\"10\"},{\"day\":\"13\",\"value\":\"10\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', NULL, '2024-07-29 07:13:16', '2024-07-29 07:13:16'),
(4, 1, 4, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"\"},{\"day\":\"6\",\"value\":\"\"},{\"day\":\"7\",\"value\":\"\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"10\"},{\"day\":\"23\",\"value\":\"10\"},{\"day\":\"24\",\"value\":\"10\"},{\"day\":\"25\",\"value\":\"10\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"10\"},{\"day\":\"29\",\"value\":\"10\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', NULL, '2024-07-29 07:13:16', '2024-07-29 07:13:16'),
(5, 2, 5, '[{\"day\":\"1\",\"value\":\"130\"},{\"day\":\"2\",\"value\":\"82\"},{\"day\":\"3\",\"value\":\"172\"},{\"day\":\"4\",\"value\":\"109\"},{\"day\":\"5\",\"value\":\"126\"},{\"day\":\"6\",\"value\":\"106\"},{\"day\":\"7\",\"value\":\"34\"},{\"day\":\"8\",\"value\":\"83\"},{\"day\":\"9\",\"value\":\"132\"},{\"day\":\"10\",\"value\":\"175\"},{\"day\":\"11\",\"value\":\"8\"},{\"day\":\"12\",\"value\":\"76\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-30 03:59:34', '2024-07-29 08:44:18', '2024-07-30 03:59:34'),
(6, 3, 1, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"66\"},{\"day\":\"3\",\"value\":\"346\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"\"},{\"day\":\"6\",\"value\":\"\"},{\"day\":\"7\",\"value\":\"\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"676567\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"46765\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', NULL, '2024-07-30 04:00:35', '2024-07-30 04:00:35'),
(7, 4, 5, '[{\"day\":\"1\",\"value\":\"200\"},{\"day\":\"2\",\"value\":\"250\"},{\"day\":\"3\",\"value\":\"0\"},{\"day\":\"4\",\"value\":\"0\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-30 08:17:16', '2024-07-30 08:10:28', '2024-07-30 08:17:16'),
(8, 4, 5, '[{\"day\":\"1\",\"value\":\"200\"},{\"day\":\"2\",\"value\":\"250\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-30 08:19:52', '2024-07-30 08:17:16', '2024-07-30 08:19:52'),
(9, 4, 5, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"150\"},{\"day\":\"9\",\"value\":\"250\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-31 00:12:22', '2024-07-30 08:19:52', '2024-07-31 00:12:22'),
(10, 5, 5, '[{\"day\":\"1\",\"value\":\"130\"},{\"day\":\"2\",\"value\":\"82\"},{\"day\":\"3\",\"value\":\"172\"},{\"day\":\"4\",\"value\":\"109\"},{\"day\":\"5\",\"value\":\"126\"},{\"day\":\"6\",\"value\":\"106\"},{\"day\":\"7\",\"value\":\"34\"},{\"day\":\"8\",\"value\":\"83\"},{\"day\":\"9\",\"value\":\"132\"},{\"day\":\"10\",\"value\":\"175\"},{\"day\":\"11\",\"value\":\"8\"},{\"day\":\"12\",\"value\":\"76\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"}]', NULL, '2024-07-30 08:38:40', '2024-07-30 08:38:40'),
(11, 6, 10, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"200\"},{\"day\":\"4\",\"value\":\"200\"},{\"day\":\"5\",\"value\":\"200\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"200\"},{\"day\":\"8\",\"value\":\"\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"}]', NULL, '2024-07-30 14:01:43', '2024-07-30 14:01:43'),
(12, 7, 13, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"\"},{\"day\":\"6\",\"value\":\"100\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"100\"},{\"day\":\"9\",\"value\":\"100\"},{\"day\":\"10\",\"value\":\"100\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', NULL, '2024-07-30 14:37:35', '2024-07-30 14:37:35'),
(13, 8, 14, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"\"},{\"day\":\"6\",\"value\":\"\"},{\"day\":\"7\",\"value\":\"\"},{\"day\":\"8\",\"value\":\"10\"},{\"day\":\"9\",\"value\":\"10\"},{\"day\":\"10\",\"value\":\"10\"},{\"day\":\"11\",\"value\":\"10\"},{\"day\":\"12\",\"value\":\"10\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"}]', '2024-07-30 16:14:25', '2024-07-30 15:04:34', '2024-07-30 16:14:25'),
(14, 8, 14, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"10\"},{\"day\":\"5\",\"value\":\"10\"},{\"day\":\"6\",\"value\":\"\"},{\"day\":\"7\",\"value\":\"\"},{\"day\":\"8\",\"value\":\"10\"},{\"day\":\"9\",\"value\":\"10\"},{\"day\":\"10\",\"value\":\"10\"},{\"day\":\"11\",\"value\":\"10\"},{\"day\":\"12\",\"value\":\"10\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"}]', '2024-07-30 23:13:36', '2024-07-30 16:14:25', '2024-07-30 23:13:36'),
(15, 8, 14, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"10\"},{\"day\":\"5\",\"value\":\"10\"},{\"day\":\"6\",\"value\":\"0\"},{\"day\":\"7\",\"value\":\"0\"},{\"day\":\"8\",\"value\":\"10\"},{\"day\":\"9\",\"value\":\"10\"},{\"day\":\"10\",\"value\":\"10\"},{\"day\":\"11\",\"value\":\"10\"},{\"day\":\"12\",\"value\":\"10\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"}]', '2024-07-30 23:14:35', '2024-07-30 23:13:36', '2024-07-30 23:14:35'),
(16, 8, 14, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"10\"},{\"day\":\"5\",\"value\":\"10\"},{\"day\":\"6\",\"value\":\"\"},{\"day\":\"7\",\"value\":\"\"},{\"day\":\"8\",\"value\":\"10\"},{\"day\":\"9\",\"value\":\"10\"},{\"day\":\"10\",\"value\":\"10\"},{\"day\":\"11\",\"value\":\"10\"},{\"day\":\"12\",\"value\":\"10\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"}]', NULL, '2024-07-30 23:14:35', '2024-07-30 23:14:35'),
(17, 4, 5, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"150\"},{\"day\":\"9\",\"value\":\"\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-31 00:12:59', '2024-07-31 00:12:22', '2024-07-31 00:12:59'),
(18, 4, 5, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"150\"},{\"day\":\"9\",\"value\":\"200\"},{\"day\":\"10\",\"value\":\"0\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-31 00:14:01', '2024-07-31 00:12:59', '2024-07-31 00:14:01'),
(19, 4, 5, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"0\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"150\"},{\"day\":\"9\",\"value\":\"200\"},{\"day\":\"10\",\"value\":\"0\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"\"},{\"day\":\"13\",\"value\":\"\"},{\"day\":\"14\",\"value\":\"\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-31 00:15:34', '2024-07-31 00:14:01', '2024-07-31 00:15:34'),
(20, 4, 5, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"0\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"150\"},{\"day\":\"9\",\"value\":\"200\"},{\"day\":\"10\",\"value\":\"\"},{\"day\":\"11\",\"value\":\"\"},{\"day\":\"12\",\"value\":\"150\"},{\"day\":\"13\",\"value\":\"200\"},{\"day\":\"14\",\"value\":\"0\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', '2024-07-31 00:17:36', '2024-07-31 00:15:34', '2024-07-31 00:17:36'),
(21, 4, 5, '[{\"day\":\"1\",\"value\":\"\"},{\"day\":\"2\",\"value\":\"\"},{\"day\":\"3\",\"value\":\"\"},{\"day\":\"4\",\"value\":\"0\"},{\"day\":\"5\",\"value\":\"210\"},{\"day\":\"6\",\"value\":\"200\"},{\"day\":\"7\",\"value\":\"100\"},{\"day\":\"8\",\"value\":\"150\"},{\"day\":\"9\",\"value\":\"200\"},{\"day\":\"10\",\"value\":\"0\"},{\"day\":\"11\",\"value\":\"0\"},{\"day\":\"12\",\"value\":\"150\"},{\"day\":\"13\",\"value\":\"200\"},{\"day\":\"14\",\"value\":\"0\"},{\"day\":\"15\",\"value\":\"\"},{\"day\":\"16\",\"value\":\"\"},{\"day\":\"17\",\"value\":\"\"},{\"day\":\"18\",\"value\":\"\"},{\"day\":\"19\",\"value\":\"\"},{\"day\":\"20\",\"value\":\"\"},{\"day\":\"21\",\"value\":\"\"},{\"day\":\"22\",\"value\":\"\"},{\"day\":\"23\",\"value\":\"\"},{\"day\":\"24\",\"value\":\"\"},{\"day\":\"25\",\"value\":\"\"},{\"day\":\"26\",\"value\":\"\"},{\"day\":\"27\",\"value\":\"\"},{\"day\":\"28\",\"value\":\"\"},{\"day\":\"29\",\"value\":\"\"},{\"day\":\"30\",\"value\":\"\"},{\"day\":\"31\",\"value\":\"\"}]', NULL, '2024-07-31 00:17:36', '2024-07-31 00:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Finance', NULL, '2024-07-27 09:35:30', '2024-07-27 09:35:30'),
(2, 'Administration', NULL, '2024-07-27 09:47:43', '2024-07-27 09:47:43'),
(3, 'Production', NULL, '2024-07-31 00:06:55', '2024-07-31 00:06:55'),
(4, 'Store', NULL, '2024-07-31 00:07:01', '2024-07-31 00:07:01'),
(5, 'Logistic', NULL, '2024-07-31 00:07:12', '2024-07-31 00:07:12'),
(6, 'BD', NULL, '2024-07-31 00:07:30', '2024-07-31 00:07:30'),
(7, 'Engineering', NULL, '2024-07-31 00:07:45', '2024-07-31 00:07:45'),
(8, 'PPC', NULL, '2024-07-31 03:40:44', '2024-07-31 03:40:44'),
(9, 'Production Injection', NULL, '2024-08-01 06:41:33', '2024-08-01 06:41:33'),
(10, 'Assembly', NULL, '2024-08-01 07:26:19', '2024-08-01 07:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'COO', NULL, '2024-07-27 09:35:30', '2024-07-27 09:35:30'),
(2, 'CEO', NULL, '2024-07-27 09:35:30', '2024-07-27 09:35:30'),
(3, 'MD', NULL, '2024-07-27 09:35:30', '2024-07-27 09:35:30'),
(4, 'CTO', NULL, '2024-07-27 09:49:33', '2024-07-27 09:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `discrepancies`
--

CREATE TABLE `discrepancies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `mrf_tr_id` varchar(255) DEFAULT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `issue_qty` varchar(255) DEFAULT NULL,
  `rcv_qty` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending' COMMENT 'Pending,Issuer,Reciever',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discrepancies`
--

INSERT INTO `discrepancies` (`id`, `product_id`, `ref_no`, `mrf_tr_id`, `order_no`, `issue_qty`, `rcv_qty`, `date`, `remarks`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '8/1/24', '14', '13/16/24 /Injection/1', '10', '5', '2024-08-01', 'ok', 'Issuer', NULL, '2024-08-01 06:47:07', '2024-08-07 05:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `external_statements`
--

CREATE TABLE `external_statements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `description` longtext NOT NULL,
  `type` enum('debit','credit') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reconciled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_statements`
--

INSERT INTO `external_statements` (`id`, `account_id`, `description`, `type`, `amount`, `transaction_date`, `created_at`, `updated_at`, `reconciled`) VALUES
(1, 1, 'checking first payment', 'credit', 210.00, '2024-10-16', '2024-10-17 05:13:32', '2024-10-17 05:13:32', 0),
(2, 1, 'wwww', 'debit', 100.00, '2024-10-14', '2024-10-17 05:14:30', '2024-10-17 05:14:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `family_child_users`
--

CREATE TABLE `family_child_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `birth_certificate_no` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_child_users`
--

INSERT INTO `family_child_users` (`id`, `family_id`, `name`, `dob`, `age`, `birth_certificate_no`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'niba', '2017-01-09', '7 year(s) - 6 month(s)', NULL, NULL, '2024-07-29 05:30:28', '2024-07-29 05:30:28'),
(2, 2, 'rabta', '2018-06-23', '6 year(s) - 1 month(s)', NULL, NULL, '2024-07-29 05:30:28', '2024-07-29 05:30:28'),
(3, 2, 'nibbi', '2021-06-17', '3 year(s) - 1 month(s)', NULL, NULL, '2024-07-29 05:30:28', '2024-07-29 05:30:28'),
(4, 6, 'sss', '2024-10-23', '0 year(s) - 0 month(s)', NULL, '2024-10-23 03:09:44', '2024-10-23 03:06:36', '2024-10-23 03:09:44'),
(5, 6, 'ssss', '2024-10-23', '0 year(s) - 0 month(s)', 'sssss', NULL, '2024-10-23 03:09:44', '2024-10-23 03:09:44'),
(6, 7, 'ssss', '2024-10-23', '0 year(s) - 0 month(s)', 'eeeee', '2024-10-23 03:12:32', '2024-10-23 03:11:59', '2024-10-23 03:12:32'),
(7, 7, 'ssss', '2024-10-23', '0 year(s) - 0 month(s)', 'eeeee', NULL, '2024-10-23 03:12:32', '2024-10-23 03:12:32'),
(8, 7, 'sssss', '2024-10-22', '0 year(s) - 0 month(s)', 'ssssss', NULL, '2024-10-23 03:12:32', '2024-10-23 03:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `family_users`
--

CREATE TABLE `family_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `family_dob` varchar(255) DEFAULT NULL,
  `family_age` varchar(255) DEFAULT NULL,
  `family_phone` varchar(255) DEFAULT NULL,
  `family_mobile` varchar(255) DEFAULT NULL,
  `family_nric` varchar(255) DEFAULT NULL,
  `family_passport` varchar(255) DEFAULT NULL,
  `family_passport_expiry_date` varchar(255) DEFAULT NULL,
  `family_immigration_no` varchar(255) DEFAULT NULL,
  `family_immigration_no_expiry_date` varchar(255) DEFAULT NULL,
  `family_permit_no` varchar(255) DEFAULT NULL,
  `family_permit_no_expiry_date` varchar(255) DEFAULT NULL,
  `children_no` varchar(255) DEFAULT NULL,
  `family_address` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_users`
--

INSERT INTO `family_users` (`id`, `user_id`, `spouse_name`, `family_dob`, `family_age`, `family_phone`, `family_mobile`, `family_nric`, `family_passport`, `family_passport_expiry_date`, `family_immigration_no`, `family_immigration_no_expiry_date`, `family_permit_no`, `family_permit_no_expiry_date`, `children_no`, `family_address`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-27 09:35:30', '2024-07-27 09:35:30'),
(2, 2, 'sakina', '2003-06-13', '21 year(s) - 1 month(s)', '664354365533', '5532237775', NULL, '54532256532', '2024-08-09', '353424334333', '2024-08-10', '353424334333', '2024-08-06', '3', 'GPO Chowk, Shahrah-e-Quaid-e-Azam, Mozang Chungi, Lahore, Punjab 54000', NULL, '2024-07-29 05:30:28', '2024-07-29 05:30:28'),
(3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-21 23:39:01', '2024-10-19 06:31:52', '2024-10-21 23:39:01'),
(5, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-22 01:26:06', '2024-10-22 01:26:06'),
(6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 03:06:36', '2024-10-23 03:06:36'),
(7, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 03:11:59', '2024-10-23 03:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `good_receivings`
--

CREATE TABLE `good_receivings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `po_pr` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `incoming_qty` varchar(255) DEFAULT NULL,
  `received_qty` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supplier_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `good_receivings`
--

INSERT INTO `good_receivings` (`id`, `po_id`, `pr_id`, `po_pr`, `ref_no`, `date`, `attachment`, `incoming_qty`, `received_qty`, `status`, `created_by`, `deleted_at`, `created_at`, `updated_at`, `supplier_id`) VALUES
(1, 2, NULL, NULL, '12/1/24', '2024-08-01', NULL, '1000', '700', 'waiting', 1, NULL, '2024-08-01 06:54:00', '2024-08-01 06:54:00', NULL),
(2, 3, NULL, NULL, '12/2/24', '2024-08-07', NULL, '4290', '4100', 'waiting', 1, NULL, '2024-08-07 04:57:27', '2024-08-07 04:57:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `good_receiving_locations`
--

CREATE TABLE `good_receiving_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `good_receiving_products`
--

CREATE TABLE `good_receiving_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `incoming_qty` varchar(255) DEFAULT NULL,
  `received_qty` varchar(255) DEFAULT NULL,
  `rejected_qty` varchar(255) DEFAULT NULL,
  `accepted_qty` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `allocation_remarks` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `good_receiving_products`
--

INSERT INTO `good_receiving_products` (`id`, `gr_id`, `product_id`, `incoming_qty`, `received_qty`, `rejected_qty`, `accepted_qty`, `remarks`, `status`, `allocation_remarks`, `deleted_at`, `created_at`, `updated_at`, `date`) VALUES
(1, 1, 1, '100', '100', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-01 06:54:00', '2024-08-01 06:54:00', NULL),
(2, 1, 2, '200', '100', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-01 06:54:00', '2024-08-01 06:54:00', NULL),
(3, 1, 3, '300', '300', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-01 06:54:00', '2024-08-01 06:54:00', NULL),
(4, 1, 4, '400', '200', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-01 06:54:00', '2024-08-01 06:54:00', NULL),
(5, 2, 2, '2000', '2000', NULL, NULL, 'Received', NULL, NULL, NULL, '2024-08-07 04:57:27', '2024-08-07 04:57:27', NULL),
(6, 2, 3, '1690', '1500', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-07 04:57:27', '2024-08-07 04:57:27', NULL),
(7, 2, 4, '600', '600', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-07 04:57:27', '2024-08-07 04:57:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `good_receiving_qcs`
--

CREATE TABLE `good_receiving_qcs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` longtext DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `initail_nos`
--

CREATE TABLE `initail_nos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `screen` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `running_no` varchar(255) DEFAULT NULL,
  `sample` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `initail_nos`
--

INSERT INTO `initail_nos` (`id`, `screen`, `ref_no`, `running_no`, `sample`, `created_at`, `updated_at`) VALUES
(1, 'Quotation', '23', '3', '23/3/2024', '2024-08-07 06:56:20', '2024-08-07 06:56:20'),
(2, 'Purchase Planning', '24', '3', '24/3/2024', '2024-08-07 06:56:20', '2024-08-07 06:56:20');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `acc_no` varchar(255) DEFAULT NULL,
  `term` longtext DEFAULT NULL,
  `outgoing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'due',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_no`, `date`, `acc_no`, `term`, `outgoing_id`, `created_by`, `payment_status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '987', '09-08-2024', '433', 'finals', 1, 1, 'partially_paid', NULL, '2024-08-09 05:15:07', '2024-10-22 23:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `disc` varchar(255) DEFAULT NULL,
  `excl_sst` varchar(255) DEFAULT NULL,
  `sst` varchar(255) DEFAULT NULL,
  `incl_sst` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_details`
--

INSERT INTO `invoice_details` (`id`, `invoice_id`, `product_id`, `qty`, `price`, `disc`, `excl_sst`, `sst`, `incl_sst`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '100', '6000', '10', '599990.00', '10', '659989.00', NULL, '2024-08-09 05:15:07', '2024-08-09 05:15:07'),
(2, 1, 2, '0', '1001', '0', '0.00', '10', '0.00', NULL, '2024-08-09 05:15:07', '2024-08-09 05:15:07'),
(3, 1, 3, '0', '2500', '0', '0.00', '10', '0.00', NULL, '2024-08-09 05:15:07', '2024-08-09 05:15:07'),
(4, 1, 4, '0', '650', '0', '0.00', '10', '0.00', NULL, '2024-08-09 05:15:07', '2024-08-09 05:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `entitlement` varchar(255) DEFAULT NULL,
  `emergency` tinyint(4) DEFAULT NULL,
  `session` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `balance_day` varchar(255) DEFAULT NULL,
  `from_date` varchar(255) DEFAULT NULL,
  `to_date` varchar(255) DEFAULT NULL,
  `from_time` varchar(255) DEFAULT NULL,
  `to_time` varchar(255) DEFAULT NULL,
  `day` varchar(255) DEFAULT NULL,
  `reason` longtext DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `name`, `entitlement`, `emergency`, `session`, `status`, `balance_day`, `from_date`, `to_date`, `from_time`, `to_time`, `day`, `reason`, `attachment`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'besihygyki', 'Annual', NULL, '1st Half', 'Approved', '22', '1987-09-04', '2014-09-22', '12:45', '17:28', 'Tuesday', 'Aute quia soluta lab', NULL, NULL, '2024-10-22 01:39:39', '2024-10-22 02:56:37'),
(2, 'admin', 'Annual', NULL, 'Full Day', 'Approved', '2222', '2024-10-22', '2024-10-23', NULL, NULL, '2', NULL, NULL, NULL, '2024-10-22 02:38:47', '2024-10-23 00:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `used_qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `area_id`, `rack_id`, `level_id`, `product_id`, `department`, `lot_no`, `used_qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, NULL, '1', '806', NULL, '2024-07-29 07:12:33', '2024-08-19 06:14:48'),
(2, 1, 1, 1, 2, NULL, '2', '100', NULL, '2024-07-29 07:12:33', '2024-08-19 05:46:40'),
(3, 1, 2, 2, 3, NULL, '3', '290', NULL, '2024-07-29 07:12:33', '2024-08-01 06:20:35'),
(4, 1, 1, 1, 4, NULL, '4', '395', NULL, '2024-07-29 07:12:33', '2024-08-09 05:01:46'),
(5, 3, 4, 4, 1, NULL, '123', '225', NULL, '2024-07-30 10:15:45', '2024-08-19 06:01:52'),
(6, 3, 4, 4, 1, NULL, '1', '5', NULL, '2024-08-01 06:47:07', '2024-08-07 05:16:10'),
(7, 1, 2, 2, 1, NULL, '2', '100', NULL, '2024-08-07 05:13:18', '2024-08-19 06:08:54'),
(8, 1, 2, 2, 4, NULL, NULL, '200', NULL, '2024-08-09 05:01:46', '2024-08-09 05:01:46'),
(9, 1, 1, 1, 3, NULL, '4', '46', NULL, '2024-08-09 05:04:26', '2024-08-09 05:04:26'),
(10, 2, 3, 2, 6, NULL, '1', '300', NULL, '2024-08-19 06:04:54', '2024-08-19 06:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `lot_nos`
--

CREATE TABLE `lot_nos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lot_nos`
--

INSERT INTO `lot_nos` (`id`, `lot_no`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(2, '2', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(3, '3', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(4, '4', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(5, NULL, NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(6, '1', NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(7, '2', NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(8, NULL, NULL, '2024-08-07 05:22:49', '2024-08-07 05:22:49'),
(9, '1', NULL, '2024-08-07 05:22:49', '2024-08-07 05:22:49'),
(10, '1', NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(11, '1', NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(12, '1', NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(13, '1', NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(14, NULL, NULL, '2024-08-09 05:04:26', '2024-08-09 05:04:26'),
(15, '4', NULL, '2024-08-09 05:04:26', '2024-08-09 05:04:26'),
(16, NULL, NULL, '2024-08-19 06:04:54', '2024-08-19 06:04:54'),
(17, '1', NULL, '2024-08-19 06:04:54', '2024-08-19 06:04:54'),
(18, NULL, NULL, '2024-08-19 06:08:54', '2024-08-19 06:08:54'),
(19, '2', NULL, '2024-08-19 06:08:54', '2024-08-19 06:08:54'),
(20, '2', NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30'),
(21, '2', NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30'),
(22, '2', NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30'),
(23, '2', NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30'),
(24, NULL, NULL, '2024-08-19 06:14:48', '2024-08-19 06:14:48'),
(25, '1', NULL, '2024-08-19 06:14:48', '2024-08-19 06:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `tonnage_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`id`, `name`, `code`, `tonnage_id`, `category`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'M1', 'M1', 3, 'Big', NULL, '2024-07-29 04:23:33', '2024-07-31 07:26:17'),
(2, 'mesin1', 'm001', 1, 'Big', '2024-07-29 09:42:52', '2024-07-29 04:25:12', '2024-07-29 09:42:52'),
(3, 'Mesin 3', 'm003', 2, 'Small', '2024-07-29 09:42:54', '2024-07-29 05:33:41', '2024-07-29 09:42:54'),
(4, 'M2', 'M2', 1, 'Big', NULL, '2024-07-29 09:43:57', '2024-07-29 09:43:57'),
(5, 'M3', 'M3', 1, 'Big', NULL, '2024-07-29 09:44:13', '2024-07-29 09:44:13'),
(6, 'M4', 'M4', 2, 'Small', NULL, '2024-07-29 09:44:30', '2024-07-29 09:44:30'),
(7, 'M5', 'M5', 2, 'Big', NULL, '2024-07-29 09:44:47', '2024-07-29 09:44:47'),
(8, 'M6', 'M6', 1, 'Big', NULL, '2024-07-29 09:45:04', '2024-07-29 09:45:04'),
(9, 'M7', 'M7', 1, 'Big', NULL, '2024-07-29 09:45:22', '2024-07-29 09:45:22'),
(10, 'M8', 'M8', 1, 'Small', NULL, '2024-07-29 09:46:44', '2024-07-29 09:46:44'),
(11, 'M9', 'M9', 1, 'Small', NULL, '2024-07-29 09:47:02', '2024-07-29 09:47:02'),
(12, 'M10', 'M10', 2, 'Small', NULL, '2024-07-29 09:47:37', '2024-07-29 09:47:37'),
(13, 'M11', 'M11', 1, 'Big', NULL, '2024-07-29 09:47:53', '2024-07-29 09:47:53'),
(14, 'M12', 'M12', 1, 'Big', NULL, '2024-07-29 09:49:08', '2024-07-29 09:49:08'),
(15, 'M13', 'M13', 2, 'Big', NULL, '2024-07-29 09:49:26', '2024-07-29 09:49:26'),
(16, 'M14', 'M14', 1, 'Big', NULL, '2024-07-29 09:49:41', '2024-07-29 09:49:41'),
(17, 'B1', 'B1', 1, 'Small', NULL, '2024-07-29 09:49:57', '2024-07-29 09:49:57'),
(18, 'B2', 'B2', 2, 'Big', NULL, '2024-07-29 09:50:11', '2024-07-29 09:50:11'),
(19, 'B3', 'B3', 2, 'Big', NULL, '2024-07-29 09:50:26', '2024-07-29 09:50:26'),
(20, 'M11 INJ', 'M123', 1, 'Big', NULL, '2024-07-30 03:42:56', '2024-07-30 03:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `machine_apis`
--

CREATE TABLE `machine_apis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `mc_no` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machine_apis`
--

INSERT INTO `machine_apis` (`id`, `start_time`, `end_time`, `mc_no`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '29-07-2024 03:26:55 PM', '31-07-2024 12:10:00 AM', 'M1', NULL, '2024-07-29 07:27:17', '2024-07-30 16:10:25'),
(2, '30-07-2024 12:22:38 PM', '31-07-2024 12:10:00 AM', 'M3', NULL, '2024-07-30 04:23:22', '2024-07-30 16:11:35'),
(3, '30-07-2024 12:22:38 PM', '31-07-2024 12:10:00 AM', 'M10', NULL, '2024-07-30 04:24:01', '2024-07-30 16:15:39'),
(4, '30-07-2024 12:22:39 PM', '30-07-2024 01:48:20 PM', 'M12', NULL, '2024-07-30 04:25:05', '2024-07-30 05:48:41'),
(5, '30-07-2024 06:25:35 AM', '31-07-2024 12:10:00 AM', 'M13', NULL, '2024-07-30 06:26:02', '2024-07-30 16:17:26'),
(6, '30-07-2024 06:42:29 AM', '30-07-2024 06:42:59 AM', 'B3', NULL, '2024-07-30 06:43:38', '2024-07-30 06:45:16'),
(7, '30-07-2024 06:42:29 AM', '30-07-2024 06:42:59 AM', 'B2', NULL, '2024-07-30 06:44:08', '2024-07-30 06:45:51'),
(8, '30-07-2024 06:43:00 AM', '30-07-2024 06:43:04 AM', 'B3', NULL, '2024-07-30 06:47:02', '2024-07-30 06:48:52'),
(9, '30-07-2024 06:43:00 AM', '30-07-2024 06:43:04 AM', 'B2', NULL, '2024-07-30 06:47:36', '2024-07-30 06:49:22'),
(10, '30-07-2024 10:13:56 PM', '30-07-2024 10:16:50 PM', 'M14', NULL, '2024-07-30 14:14:18', '2024-07-30 14:17:15'),
(11, '30-07-2024 10:17:55 PM', '01-08-2024 12:10:00 AM', 'M14', NULL, '2024-07-30 14:18:23', '2024-07-31 16:17:50'),
(12, '31-07-2024 12:20:28 AM', '31-07-2024 12:21:27 AM', 'M1', NULL, '2024-07-30 16:20:30', '2024-07-30 16:21:29'),
(13, '31-07-2024 12:21:35 AM', '31-07-2024 12:01:44 PM', 'M1', NULL, '2024-07-30 16:21:36', '2024-07-31 04:01:47'),
(14, '31-07-2024 07:14:53 AM', '31-07-2024 10:32:42 PM', 'M2', NULL, '2024-07-30 23:14:54', '2024-07-31 14:32:52'),
(15, '31-07-2024 12:02:11 PM', '01-08-2024 12:10:00 AM', 'M1', NULL, '2024-07-31 04:02:12', '2024-07-31 16:10:15'),
(16, '31-07-2024 10:34:29 PM', '01-08-2024 11:21:03 AM', 'M2', NULL, '2024-07-31 14:34:34', '2024-08-01 03:22:12'),
(17, '01-08-2024 08:37:45 AM', '01-08-2024 11:20:48 AM', 'M1', NULL, '2024-08-01 00:37:46', '2024-08-01 03:20:51'),
(18, '01-08-2024 11:40:44 AM', '02-08-2024 06:33:54 AM', 'M1', NULL, '2024-08-01 03:40:46', '2024-08-01 22:35:06'),
(19, '01-08-2024 11:41:17 AM', '02-08-2024 06:33:54 AM', 'M2', NULL, '2024-08-01 03:41:19', '2024-08-01 22:35:42'),
(20, '30-07-2024 06:43:05 AM', '30-07-2024 06:44:17 AM', 'B3', NULL, '2024-08-01 05:50:26', '2024-08-01 05:53:58'),
(21, '30-07-2024 06:43:05 AM', '30-07-2024 06:44:17 AM', 'B2', NULL, '2024-08-01 05:51:38', '2024-08-01 05:55:10'),
(22, '30-07-2024 02:43:36 PM', '02-08-2024 06:33:54 AM', 'M11', NULL, '2024-08-01 05:53:33', '2024-08-01 22:40:55'),
(23, '30-07-2024 06:44:18 AM', '30-07-2024 06:44:21 AM', 'B3', NULL, '2024-08-01 05:56:10', '2024-08-01 05:57:59'),
(24, '30-07-2024 06:44:18 AM', '30-07-2024 06:44:21 AM', 'B2', NULL, '2024-08-01 05:56:45', '2024-08-01 05:58:30'),
(25, '30-07-2024 06:44:22 AM', '30-07-2024 06:44:51 AM', 'B3', NULL, '2024-08-01 06:00:22', '2024-08-01 06:03:13'),
(26, '30-07-2024 06:44:22 AM', '30-07-2024 06:44:51 AM', 'B2', NULL, '2024-08-01 06:01:31', '2024-08-01 06:04:33'),
(27, '30-07-2024 06:44:52 AM', '30-07-2024 06:45:07 AM', 'B3', NULL, '2024-08-01 06:07:02', '2024-08-01 06:09:45'),
(28, '30-07-2024 06:45:10 AM', '30-07-2024 06:45:14 AM', 'B3', NULL, '2024-08-01 06:14:00', '2024-08-01 06:16:39'),
(29, '30-07-2024 06:45:18 AM', '30-07-2024 06:45:14 AM', 'B3', NULL, '2024-08-01 06:20:18', '2024-08-01 06:20:22'),
(30, '30-07-2024 06:45:18 AM', '30-07-2024 06:45:31 AM', 'B2', NULL, '2024-08-01 06:22:01', '2024-08-01 06:25:34'),
(31, '30-07-2024 06:45:18 AM', '30-07-2024 06:45:31 AM', 'B3', NULL, '2024-08-01 06:23:09', '2024-08-01 06:24:54'),
(32, '30-07-2024 02:45:21 PM', '02-08-2024 06:33:54 AM', 'M4', NULL, '2024-08-01 06:23:35', '2024-08-01 22:37:26'),
(33, '30-07-2024 02:45:24 PM', '02-08-2024 06:33:54 AM', 'M7', NULL, '2024-08-01 06:24:18', '2024-08-01 22:38:36'),
(34, '30-07-2024 06:45:33 AM', '30-07-2024 06:46:49 AM', 'B2', NULL, '2024-08-01 06:29:59', '2024-08-01 06:31:54'),
(35, '30-07-2024 06:45:33 AM', '30-07-2024 06:46:49 AM', 'B3', NULL, '2024-08-01 06:30:48', '2024-08-01 06:31:13'),
(36, '30-07-2024 06:46:59 AM', '30-07-2024 06:48:05 AM', 'B2', NULL, '2024-08-01 06:33:47', '2024-08-01 06:36:59'),
(37, '30-07-2024 06:46:59 AM', '30-07-2024 06:48:05 AM', 'B3', NULL, '2024-08-01 06:35:15', '2024-08-01 06:36:25'),
(38, '30-07-2024 06:48:06 AM', '30-07-2024 06:48:11 AM', 'B3', NULL, '2024-08-01 06:38:18', '2024-08-01 06:39:58'),
(39, '30-07-2024 06:48:06 AM', '30-07-2024 06:48:11 AM', 'B2', NULL, '2024-08-01 06:38:55', '2024-08-01 06:40:31'),
(40, '30-07-2024 06:48:12 AM', '30-07-2024 06:49:21 AM', 'B3', NULL, '2024-08-01 06:41:39', '2024-08-01 06:45:49'),
(41, '30-07-2024 06:48:12 AM', '30-07-2024 06:49:21 AM', 'B2', NULL, '2024-08-01 06:42:19', '2024-08-01 06:45:08'),
(42, '30-07-2024 06:49:23 AM', '30-07-2024 06:49:27 AM', 'B3', NULL, '2024-08-01 06:46:20', '2024-08-01 06:48:13'),
(43, '30-07-2024 06:49:23 AM', '30-07-2024 06:49:27 AM', 'B2', NULL, '2024-08-01 06:46:55', '2024-08-01 06:48:39'),
(44, '01-08-2024 03:09:31 PM', '02-08-2024 06:33:54 AM', 'M5', NULL, '2024-08-01 07:10:15', '2024-08-01 22:36:51'),
(45, '01-08-2024 03:09:31 PM', '02-08-2024 06:33:54 AM', 'B1', NULL, '2024-08-01 07:11:17', '2024-08-01 22:43:16'),
(46, '01-08-2024 04:18:47 PM', '10-08-2024 11:01:29 PM', 'M13', NULL, '2024-08-01 08:55:33', '2024-08-12 01:33:54'),
(47, '01-08-2024 04:45:43 PM', '01-08-2024 05:15:07 PM', 'M12', NULL, '2024-08-01 08:57:19', '2024-08-01 09:15:23'),
(48, '30-07-2024 06:49:28 AM', '02-08-2024 06:33:54 AM', 'B3', NULL, '2024-08-01 09:08:10', '2024-08-01 22:45:01'),
(49, '30-07-2024 06:49:28 AM', '02-08-2024 06:33:54 AM', 'B2', NULL, '2024-08-01 09:08:54', '2024-08-01 22:44:26'),
(50, '01-08-2024 02:54:08 PM', '02-08-2024 06:33:54 AM', 'M10', NULL, '2024-08-01 09:12:04', '2024-08-01 22:40:21'),
(51, '01-08-2024 05:18:37 PM', '10-08-2024 11:01:29 PM', 'M12', NULL, '2024-08-01 09:18:53', '2024-08-12 01:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `machine_counts`
--

CREATE TABLE `machine_counts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` varchar(255) DEFAULT NULL,
  `datetime` varchar(255) DEFAULT NULL,
  `mc_no` varchar(255) DEFAULT NULL,
  `count` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machine_counts`
--

INSERT INTO `machine_counts` (`id`, `production_id`, `datetime`, `mc_no`, `count`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '29-07-2024 03:32:36 PM', 'M1', '10', NULL, '2024-07-29 07:33:07', '2024-07-29 07:33:07'),
(2, '1', '29-07-2024 03:36:01 PM', 'M1', '10', NULL, '2024-07-29 07:36:03', '2024-07-29 07:36:03'),
(3, '1', '29-07-2024 03:49:34 PM', 'M1', '10', NULL, '2024-07-29 07:50:03', '2024-07-29 07:50:03'),
(4, '11', '30-07-2024 10:25:16 PM', 'M14', '3', NULL, '2024-07-30 14:25:24', '2024-07-30 14:25:24'),
(5, '2', '29-07-2024 12:22:06 PM', 'M1', '15', NULL, '2024-07-30 16:22:07', '2024-07-30 16:22:07'),
(6, '13', '31-07-2024 07:39:13 AM', 'M2', '15', NULL, '2024-07-30 23:39:13', '2024-07-30 23:39:13'),
(7, '2', '31-07-2024 12:06:20 PM', 'M1', '15', NULL, '2024-07-31 04:06:23', '2024-07-31 04:06:23'),
(8, '2', '31-07-2024 12:06:37 PM', 'M1', '15', NULL, '2024-07-31 04:06:39', '2024-07-31 04:06:39'),
(9, '13', '31-07-2024 10:45:12 PM', 'M2', '5', NULL, '2024-07-31 14:45:39', '2024-07-31 14:45:39'),
(10, '13', '31-07-2024 10:46:31 PM', 'M2', '5', NULL, '2024-07-31 14:46:48', '2024-07-31 14:46:48'),
(11, '13', '31-07-2024 11:30:39 PM', 'M2', '5', NULL, '2024-07-31 15:31:09', '2024-07-31 15:31:09'),
(12, '13', '31-07-2024 11:32:27 PM', 'M2', '5', NULL, '2024-07-31 15:32:55', '2024-07-31 15:32:55'),
(13, '21', '01-08-2024 05:21:57 PM', 'M12', '1', NULL, '2024-08-01 09:22:23', '2024-08-01 09:22:23'),
(14, '21', '01-08-2024 05:23:07 PM', 'M12', '3', NULL, '2024-08-01 09:23:33', '2024-08-01 09:23:33'),
(15, '21', '01-08-2024 05:24:24 PM', 'M12', '1', NULL, '2024-08-01 09:24:43', '2024-08-01 09:24:43'),
(16, '21', '01-08-2024 05:25:24 PM', 'M12', '2', NULL, '2024-08-01 09:25:53', '2024-08-01 09:25:53'),
(17, '21', '01-08-2024 05:26:24 PM', 'M12', '1', NULL, '2024-08-01 09:26:29', '2024-08-01 09:26:29'),
(18, '21', '01-08-2024 05:27:24 PM', 'M12', '1', NULL, '2024-08-01 09:27:38', '2024-08-01 09:27:38'),
(19, '21', '01-08-2024 05:29:24 PM', 'M12', '1', NULL, '2024-08-01 09:29:59', '2024-08-01 09:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `machine_downimes`
--

CREATE TABLE `machine_downimes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` varchar(255) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `mc_no` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine_preperations`
--

CREATE TABLE `machine_preperations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` varchar(255) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `mc_no` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machine_preperations`
--

INSERT INTO `machine_preperations` (`id`, `production_id`, `start_time`, `end_time`, `mc_no`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '29-07-2024 03:28:55 PM', '29-07-2024 03:29:41 PM', 'M1', NULL, '2024-07-29 07:29:02', '2024-07-29 07:30:13'),
(2, '11', '30-07-2024 10:20:58 PM', '30-07-2024 10:22:14 PM', 'M14', NULL, '2024-07-30 14:21:18', '2024-07-30 14:22:30'),
(3, '13', '31-07-2024 07:34:20 AM', '31-07-2024 07:35:04 AM', 'M2', NULL, '2024-07-30 23:34:20', '2024-07-30 23:35:05'),
(4, '13', '31-07-2024 07:38:16 AM', '31-07-2024 07:38:45 AM', 'M2', NULL, '2024-07-30 23:38:17', '2024-07-30 23:38:46'),
(5, '13', '31-07-2024 07:40:02 AM', '31-07-2024 10:26:30 PM', 'M2', NULL, '2024-07-30 23:40:02', '2024-07-31 14:27:03'),
(6, '2', '31-07-2024 12:04:03 PM', '31-07-2024 12:04:18 PM', 'M1', NULL, '2024-07-31 04:04:04', '2024-07-31 04:04:20'),
(7, '13', '31-07-2024 10:39:34 PM', '31-07-2024 10:42:23 PM', 'M2', NULL, '2024-07-31 14:39:49', '2024-07-31 14:42:44'),
(8, '13', '01-08-2024 12:46:12 AM', '01-08-2024 01:09:14 AM', 'M2', NULL, '2024-07-31 16:46:25', '2024-07-31 17:09:46'),
(9, '2', '01-08-2024 08:37:55 AM', '01-08-2024 08:38:24 AM', 'M1', NULL, '2024-08-01 00:37:56', '2024-08-01 00:38:24'),
(10, '21', '01-08-2024 05:10:49 PM', '01-08-2024 05:11:24 PM', 'M12', NULL, '2024-08-01 09:11:18', '2024-08-01 09:11:53'),
(11, '21', '01-08-2024 05:12:00 PM', '01-08-2024 05:12:51 PM', 'M12', NULL, '2024-08-01 09:12:28', '2024-08-01 09:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `machine_tonnages`
--

CREATE TABLE `machine_tonnages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tonnage` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machine_tonnages`
--

INSERT INTO `machine_tonnages` (`id`, `tonnage`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '400ton', NULL, '2024-07-29 03:30:18', '2024-07-29 03:30:18'),
(2, '500ton', NULL, '2024-07-29 03:30:28', '2024-07-29 03:30:28'),
(3, '80 TON', NULL, '2024-07-31 07:24:45', '2024-07-31 07:24:45'),
(4, '120 TON', NULL, '2024-07-31 07:24:49', '2024-07-31 07:24:49'),
(5, '418 TON', NULL, '2024-07-31 07:24:54', '2024-07-31 07:24:54'),
(6, '450 TON', NULL, '2024-07-31 07:24:59', '2024-07-31 07:24:59'),
(7, '550 TON', NULL, '2024-07-31 07:25:03', '2024-07-31 07:25:03'),
(8, '650 TON', NULL, '2024-07-31 07:25:08', '2024-07-31 07:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `material_plannings`
--

CREATE TABLE `material_plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dppd_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `request_date` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `process` varchar(255) DEFAULT NULL,
  `plan_date` varchar(255) DEFAULT NULL,
  `total_planned_qty` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_planning_details`
--

CREATE TABLE `material_planning_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `planning_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `required_qty` varchar(255) DEFAULT NULL,
  `inventory_qty` varchar(255) DEFAULT NULL,
  `request_qty` varchar(255) DEFAULT NULL,
  `difference` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_requisitions`
--

CREATE TABLE `material_requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pro_order_no` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `request_date` varchar(255) DEFAULT NULL,
  `plan_date` varchar(255) DEFAULT NULL,
  `request_from` varchar(255) DEFAULT NULL,
  `request_to` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `machine` varchar(255) DEFAULT NULL,
  `issue_by` varchar(255) DEFAULT NULL,
  `issue_date` varchar(255) DEFAULT NULL,
  `issue_remarks` varchar(255) DEFAULT NULL,
  `issue_time` varchar(255) DEFAULT NULL,
  `rcv_by` varchar(255) DEFAULT NULL,
  `rcv_date` varchar(255) DEFAULT NULL,
  `rcv_remarks` varchar(255) DEFAULT NULL,
  `rcv_time` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Requested' COMMENT 'Requested,Issued,Received',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requisitions`
--

INSERT INTO `material_requisitions` (`id`, `pro_order_no`, `ref_no`, `request_date`, `plan_date`, `request_from`, `request_to`, `shift`, `description`, `machine`, `issue_by`, `issue_date`, `issue_remarks`, `issue_time`, `rcv_by`, `rcv_date`, `rcv_remarks`, `rcv_time`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '13/1/24 /Rhoda Osborne/1', '6/1/24', '2024-07-30', '2024-07-29', '1', '2', 'AM', NULL, '1', '1', '2024-07-30', NULL, '10:09', '1', '2024-07-30', NULL, '10:14', 'Received', NULL, '2024-07-30 10:09:33', '2024-07-30 10:15:45'),
(2, '13/2/24/ASSEMBLE/1', '6/2/24', '2024-07-31', '2024-07-29', '8', '4', 'AM', 'Request', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', '2024-07-31 07:32:33', '2024-07-31 06:15:06', '2024-07-31 07:32:33'),
(3, '13/2/24/ASSEMBLE/1', '6/3/24', '2024-07-31', '2024-07-29', '8', '3', 'AM', NULL, '1', '1', '2024-07-31', NULL, '06:53', NULL, NULL, NULL, NULL, 'Issued', NULL, '2024-07-31 06:19:54', '2024-07-31 06:54:01'),
(4, '13/1/24 /Rhoda Osborne/1', '6/2/24', '2024-07-31', '2024-07-29', '3', '4', 'AM', 'test', '1', '1', '2024-07-31', NULL, '06:53', NULL, NULL, NULL, NULL, 'Requested', '2024-07-31 07:32:29', '2024-07-31 06:43:31', '2024-07-31 07:32:29'),
(5, '13/15/24 /ASSEMBLE/1', '6/3/24', '2024-07-31', '2024-08-01', '1', '2', 'AM', 'text area', '14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', '2024-07-31 07:32:26', '2024-07-31 07:20:32', '2024-07-31 07:32:26'),
(6, '13/1/24 /Rhoda Osborne/1', '6/4/24', '2024-07-31', '2024-07-29', '1', '2', 'AM', 'test', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-07-31 07:33:34', '2024-07-31 07:33:34'),
(7, '13/2/24/ASSEMBLE/1', '6/5/24', '2024-07-31', '2024-07-29', '2', '3', 'AM', 'test', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-07-31 07:33:53', '2024-07-31 07:33:53'),
(8, '13/4/24 /Rhoda Osborne/1', '6/6/24', '2024-07-31', '2024-07-30', '3', '4', 'AM', 'test', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', '2024-07-31 07:34:44', '2024-07-31 07:34:10', '2024-07-31 07:34:44'),
(9, '13/4/24 /Rhoda Osborne/1', '6/6/24', '2024-07-31', '2024-07-30', '5', '4', 'AM', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-07-31 07:35:03', '2024-07-31 07:35:03'),
(10, '13/5/24 /Rhoda Osborne/1', '6/7/24', '2024-07-31', '2024-07-30', '6', '7', 'AM', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-07-31 07:35:25', '2024-07-31 07:35:25'),
(11, '13/4/24 /Rhoda Osborne/1', '6/8/24', '2024-07-31', '2024-07-30', '8', '4', 'AM', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', '2024-07-31 16:44:38', '2024-07-31 16:44:10', '2024-07-31 16:44:38'),
(12, '13/16/24 /Injection/1', '6/8/24', '2024-08-01', '2024-07-31', '8', '4', 'AM', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', '2024-08-01 03:17:56', '2024-08-01 03:17:23', '2024-08-01 03:17:56'),
(13, '13/16/24 /Injection/1', '6/8/24', '2024-08-01', '2024-07-31', '8', '4', 'AM', NULL, '1', '1', '2024-08-01', NULL, '03:19', NULL, NULL, NULL, NULL, 'Issued', NULL, '2024-08-01 03:18:49', '2024-08-01 03:19:44'),
(14, '13/16/24 /Injection/1', '6/9/24', '2024-08-01', '2024-07-31', '8', '8', 'AM', NULL, '1', '1', '2024-08-01', NULL, '03:24', '1', '2024-08-01', NULL, '06:44', 'Received', NULL, '2024-08-01 03:24:00', '2024-08-01 06:47:07'),
(15, '13/17/24 /ASSEMBLE/1', '6/10/24', '2024-08-01', '2024-07-31', '3', '4', 'AM', 'product', '1', '1', '2024-08-01', NULL, '06:35', NULL, NULL, NULL, NULL, 'Issued', NULL, '2024-08-01 06:34:11', '2024-08-01 06:36:13'),
(16, '13/4/24 /Rhoda Osborne/1', '6/11/24', '2024-08-07', '2024-07-30', '1', '2', 'AM', 'ok', '1', '1', '2024-08-07', NULL, '05:01', NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-07 05:00:04', '2024-08-07 05:01:58'),
(17, '13/1/24 /Rhoda Osborne/1', 'MRF/12/24', '2024-08-09', '2024-07-29', '2', '3', 'AM', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-09 04:57:58', '2024-08-09 04:57:58'),
(18, '13/1/24 /Rhoda Osborne/1', 'MRF/13/24', '2024-08-19', '2024-07-29', '1', '2', 'AM', NULL, '1', '1', '2024-08-19', NULL, '05:41', NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-19 05:41:28', '2024-08-19 05:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `material_requisition_details`
--

CREATE TABLE `material_requisition_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_requisition_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `request_qty` varchar(255) DEFAULT NULL,
  `issue_qty` varchar(255) DEFAULT NULL,
  `issue_remarks` varchar(255) DEFAULT NULL,
  `rcv_qty` varchar(255) DEFAULT NULL,
  `rcv_remarks` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requisition_details`
--

INSERT INTO `material_requisition_details` (`id`, `material_requisition_id`, `product_id`, `request_qty`, `issue_qty`, `issue_remarks`, `rcv_qty`, `rcv_remarks`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '12', '12', 'null', '12', 'null', 'test', NULL, '2024-07-30 10:09:33', '2024-07-30 10:15:45'),
(2, 1, '2', '13', '13', 'null', '13', 'null', 'test', NULL, '2024-07-30 10:09:33', '2024-07-30 10:15:45'),
(3, 2, '7', '1936', NULL, NULL, NULL, NULL, NULL, '2024-07-31 06:20:20', '2024-07-31 06:15:06', '2024-07-31 06:20:20'),
(4, 2, '9', '484', NULL, NULL, NULL, NULL, NULL, '2024-07-31 06:20:20', '2024-07-31 06:15:06', '2024-07-31 06:20:20'),
(5, 3, '9', '434', NULL, NULL, NULL, NULL, NULL, '2024-07-31 06:20:45', '2024-07-31 06:19:54', '2024-07-31 06:20:45'),
(6, 2, '7', '1936', NULL, NULL, NULL, NULL, 'null', '2024-07-31 06:21:55', '2024-07-31 06:20:20', '2024-07-31 06:21:55'),
(7, 3, '9', '434', NULL, NULL, NULL, NULL, 'null', '2024-07-31 06:40:46', '2024-07-31 06:20:45', '2024-07-31 06:40:46'),
(8, 2, '7', '1936', NULL, NULL, NULL, NULL, 'null', '2024-07-31 07:32:33', '2024-07-31 06:21:55', '2024-07-31 07:32:33'),
(9, 3, '7', '434', '434', 'null', NULL, NULL, NULL, NULL, '2024-07-31 06:40:46', '2024-07-31 06:54:01'),
(10, 4, '5', '1000', '200', 'null', NULL, NULL, 'i want this urgent', '2024-07-31 07:32:29', '2024-07-31 06:43:31', '2024-07-31 07:32:29'),
(11, 4, '6', '1000', '1000', 'null', NULL, NULL, 'i want this urgent', '2024-07-31 07:32:29', '2024-07-31 06:43:31', '2024-07-31 07:32:29'),
(12, 4, '7', '1000', '500', 'null', NULL, NULL, 'i want this urgent', '2024-07-31 07:32:29', '2024-07-31 06:43:31', '2024-07-31 07:32:29'),
(13, 6, '1', '100', NULL, NULL, NULL, NULL, 'test', NULL, '2024-07-31 07:33:34', '2024-07-31 07:33:34'),
(14, 7, '2', '100', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-31 07:33:53', '2024-07-31 07:33:53'),
(15, 8, '3', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-31 07:34:44', '2024-07-31 07:34:10', '2024-07-31 07:34:44'),
(16, 9, '3', '100', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-31 07:35:03', '2024-07-31 07:35:03'),
(17, 10, '5', '100', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-31 07:35:25', '2024-07-31 07:35:25'),
(18, 11, '5', '10', NULL, NULL, NULL, NULL, NULL, '2024-07-31 16:44:38', '2024-07-31 16:44:10', '2024-07-31 16:44:38'),
(19, 11, '6', '5', NULL, NULL, NULL, NULL, NULL, '2024-07-31 16:44:38', '2024-07-31 16:44:10', '2024-07-31 16:44:38'),
(20, 12, '6', '20', NULL, NULL, NULL, NULL, NULL, '2024-08-01 03:17:56', '2024-08-01 03:17:23', '2024-08-01 03:17:56'),
(21, 13, '2', '20', '20', 'null', NULL, NULL, NULL, NULL, '2024-08-01 03:18:49', '2024-08-01 03:19:44'),
(22, 14, '1', '10', '10', 'null', '5', 'null', NULL, NULL, '2024-08-01 03:24:00', '2024-08-01 06:47:07'),
(23, 15, '1', '10', '10', 'null', NULL, NULL, 'test', NULL, '2024-08-01 06:34:11', '2024-08-01 06:36:13'),
(24, 16, '2', '44', '40', 'kkk', NULL, NULL, 'ok', NULL, '2024-08-07 05:00:04', '2024-08-07 05:01:42'),
(25, 16, '3', '22', '0', 'null', NULL, NULL, 'ok', NULL, '2024-08-07 05:00:04', '2024-08-07 05:01:42'),
(26, 16, '6', '46', '0', 'null', NULL, NULL, 'pk', NULL, '2024-08-07 05:00:04', '2024-08-07 05:01:42'),
(27, 17, '3', '100', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-09 04:57:58', '2024-08-09 04:57:58'),
(28, 17, '4', '200', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-09 04:57:58', '2024-08-09 04:57:58'),
(29, 18, '1', '100', '50', 'ytr', NULL, NULL, NULL, NULL, '2024-08-19 05:41:28', '2024-08-19 05:45:52'),
(30, 18, '2', '200', '28', 'null', NULL, NULL, NULL, NULL, '2024-08-19 05:41:28', '2024-08-19 05:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `material_requisition_product_details`
--

CREATE TABLE `material_requisition_product_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `rack` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `mrf_detail_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requisition_product_details`
--

INSERT INTO `material_requisition_product_details` (`id`, `area`, `rack`, `level`, `lot_no`, `qty`, `mrf_detail_id`, `product_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '1', '1', '1', '12', '1', '1', NULL, '2024-07-30 10:10:32', '2024-07-30 10:10:32'),
(2, '1', '1', '1', '2', '13', '2', '2', NULL, '2024-07-30 10:10:32', '2024-07-30 10:10:32'),
(3, '1', '1', '1', '2', '20', '21', '2', NULL, '2024-08-01 03:19:44', '2024-08-01 03:19:44'),
(4, '1', '1', '1', '1', '10', '22', '1', NULL, '2024-08-01 03:24:46', '2024-08-01 03:24:46'),
(5, '1', '1', '1', '1', '10', '23', '1', NULL, '2024-08-01 06:36:13', '2024-08-01 06:36:13'),
(6, '1', '1', '1', '2', '40', '24', '2', '2024-08-07 05:01:58', '2024-08-07 05:01:42', '2024-08-07 05:01:58'),
(7, '1', '1', '1', '2', '40', '24', '2', NULL, '2024-08-07 05:01:58', '2024-08-07 05:01:58'),
(8, '1', '1', '1', '1', '50', '29', '1', '2024-08-19 05:46:40', '2024-08-19 05:45:52', '2024-08-19 05:46:40'),
(9, '1', '1', '1', '2', '28', '30', '2', '2024-08-19 05:46:40', '2024-08-19 05:45:52', '2024-08-19 05:46:40'),
(10, '1', '1', '1', '1', '50', '29', '1', NULL, '2024-08-19 05:46:40', '2024-08-19 05:46:40'),
(11, '1', '1', '1', '2', '28', '30', '2', NULL, '2024-08-19 05:46:40', '2024-08-19 05:46:40');

-- --------------------------------------------------------

--
-- Table structure for table `material_requisition_product_details_receives`
--

CREATE TABLE `material_requisition_product_details_receives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `rack` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `mrf_detail_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requisition_product_details_receives`
--

INSERT INTO `material_requisition_product_details_receives` (`id`, `area`, `rack`, `level`, `lot_no`, `qty`, `mrf_detail_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, '3', '4', '4', '123', '12', '1', '1', '2024-07-30 10:15:45', '2024-07-30 10:15:45'),
(2, '3', '4', '4', '123', '13', '2', '2', '2024-07-30 10:15:45', '2024-07-30 10:15:45'),
(3, '3', '4', '4', '1', '05', '22', '1', '2024-08-01 06:47:07', '2024-08-01 06:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_04_18_110331_create_notifications_table', 1),
(7, '2024_05_03_111818_create_type_of_rejections_table', 1),
(8, '2024_05_04_064358_create_type_of_products_table', 1),
(9, '2024_05_04_104053_create_machine_tonnages_table', 1),
(10, '2024_05_04_111852_create_machines_table', 1),
(11, '2024_05_06_054715_create_area_levels_table', 1),
(12, '2024_05_06_070407_create_area_racks_table', 1),
(13, '2024_05_06_102217_create_areas_table', 1),
(14, '2024_05_07_045505_create_units_table', 1),
(15, '2024_05_07_053142_create_processes_table', 1),
(16, '2024_05_07_065213_create_customers_table', 1),
(17, '2024_05_07_084851_create_suppliers_table', 1),
(18, '2024_05_07_110909_create_products_table', 1),
(19, '2024_05_08_045741_create_designations_table', 1),
(20, '2024_05_08_045953_create_departments_table', 1),
(21, '2024_05_13_025507_create_area_locations_table', 1),
(22, '2024_05_13_025833_create_locations_table', 1),
(23, '2024_05_13_031940_create_sst_percentages_table', 1),
(24, '2024_05_13_032123_create_po_important_notes_table', 1),
(25, '2024_05_13_032204_create_spec_breaks_table', 1),
(26, '2024_05_13_032314_create_purchase_prices_table', 1),
(27, '2024_05_13_033103_create_pr_approvals_table', 1),
(28, '2024_05_13_042646_create_initail_nos_table', 1),
(29, '2024_05_14_064037_create_sale_prices_table', 1),
(30, '2024_05_15_041310_create_supplier_rankings_table', 1),
(31, '2024_05_15_070134_create_purchase_requisitions_table', 1),
(32, '2024_05_15_071917_create_purchase_requisition_details_table', 1),
(33, '2024_05_15_103158_create_orders_table', 1),
(34, '2024_05_16_100709_create_permission_tables', 1),
(35, '2024_05_21_162103_create_order_details_table', 1),
(36, '2024_05_22_095223_create_delivery_instructions_table', 1),
(37, '2024_05_22_095229_create_delivery_instruction_details_table', 1),
(38, '2024_05_23_051414_create_stock_relocations_table', 1),
(39, '2024_05_23_051420_create_stock_relocation_details_table', 1),
(40, '2024_05_23_074453_create_product_reorderings_table', 1),
(41, '2024_05_24_052723_create_quotations_table', 1),
(42, '2024_05_24_052734_create_quotation_details_table', 1),
(43, '2024_05_25_071003_create_purchase_requisition_statuses_table', 1),
(44, '2024_05_25_074725_create_sales_returns_table', 1),
(45, '2024_05_25_074738_create_sales_return_details_table', 1),
(46, '2024_05_25_074749_create_sales_return_locations_table', 1),
(47, '2024_05_27_220252_create_boms_table', 1),
(48, '2024_05_28_113911_create_bom_purchase_parts_table', 1),
(49, '2024_05_28_113927_create_bom_crushings_table', 1),
(50, '2024_05_28_113938_create_bom_sub_parts_table', 1),
(51, '2024_05_28_113950_create_bom_processes_table', 1),
(52, '2024_05_29_034803_create_purchase_plannings_table', 1),
(53, '2024_05_29_034826_create_purchase_planning_products_table', 1),
(54, '2024_05_29_034846_create_purchase_planning_details_table', 1),
(55, '2024_05_29_034902_create_purchase_planning_suppliers_table', 1),
(56, '2024_05_29_041616_create_purchase_planning_verifications_table', 1),
(57, '2024_05_30_130707_create_bom_verifications_table', 1),
(58, '2024_05_31_042542_create_purchase_orders_table', 1),
(59, '2024_05_31_042608_create_purchase_order_details_table', 1),
(60, '2024_06_01_033909_create_purchase_returns_table', 1),
(61, '2024_06_01_034005_create_purchase_return_products_table', 1),
(62, '2024_06_01_034024_create_purchase_return_locations_table', 1),
(63, '2024_06_01_034132_create_purchase_receive_locations_table', 1),
(64, '2024_06_01_122529_create_daily_production_plannings_table', 1),
(65, '2024_06_03_092641_create_outgoings_table', 1),
(66, '2024_06_03_092648_create_outgoing_details_table', 1),
(67, '2024_06_03_095545_create_outgoing_locations_table', 1),
(68, '2024_06_03_095546_create_invoices_table', 1),
(69, '2024_06_03_095547_create_invoice_details_table', 1),
(70, '2024_06_05_062544_create_good_receivings_table', 1),
(71, '2024_06_05_062603_create_good_receiving_products_table', 1),
(72, '2024_06_05_062612_create_good_receiving_locations_table', 1),
(73, '2024_06_05_071302_create_outgoing_sale_locations_table', 1),
(74, '2024_06_06_041810_create_good_receiving_qcs_table', 1),
(75, '2024_06_07_115209_create_daily_production_child_parts_table', 1),
(76, '2024_06_07_115226_create_daily_production_products_table', 1),
(77, '2024_06_08_031217_create_lot_nos_table', 1),
(78, '2024_06_08_051210_create_call_for_assistances_table', 1),
(79, '2024_06_08_073513_create_daily_production_planning_details_table', 1),
(80, '2024_06_08_091901_create_production_output_traceabilities_table', 1),
(81, '2024_06_08_100130_create_production_output_traceability_details_table', 1),
(82, '2024_06_08_113531_create_machine_apis_table', 1),
(83, '2024_06_08_113539_create_machine_counts_table', 1),
(84, '2024_06_10_051031_create_production_apis_table', 1),
(85, '2024_06_10_051117_create_machine_preperations_table', 1),
(86, '2024_06_10_153142_create_material_requisitions_table', 1),
(87, '2024_06_11_230928_create_production_schedulings_table', 1),
(88, '2024_06_13_070532_create_material_requisition_details_table', 1),
(89, '2024_06_15_065316_create_production_output_traceability_shifts_table', 1),
(90, '2024_06_15_071330_create_production_output_traceability_rejections_table', 1),
(91, '2024_06_18_064101_create_production_output_traceability_q_c_s_table', 1),
(92, '2024_06_21_093127_create_material_requisition_product_details_table', 1),
(93, '2024_06_21_100630_create_personal_users_table', 1),
(94, '2024_06_21_100641_create_family_users_table', 1),
(95, '2024_06_21_100659_create_family_child_users_table', 1),
(96, '2024_06_21_100710_create_more_users_table', 1),
(97, '2024_06_22_044741_create_leaves_table', 1),
(98, '2024_06_22_063723_create_material_requisition_product_details_receives_table', 1),
(99, '2024_06_25_130512_create_transfer_requests_table', 1),
(100, '2024_06_25_150534_create_transfer_request_issues_table', 1),
(101, '2024_06_25_150548_create_transfer_request_receives_table', 1),
(102, '2024_06_25_150706_create_transfer_request_details_table', 1),
(103, '2024_06_27_111145_create_discrepancies_table', 1),
(104, '2024_07_09_102812_create_attendances_table', 1),
(105, '2024_09_21_095944_create_accounts_table', 2),
(106, '2024_09_21_095945_create_transactions_table', 2),
(107, '2024_09_23_043837_create_external_statements_table', 2),
(108, '2024_09_23_044314_add_reconciled_to_transactions_table', 2),
(109, '2024_09_25_072956_create_account_categories_table', 2),
(110, '2024_09_25_095319_add_account_categories_id_to_accounts_table', 2),
(111, '2024_10_01_121107_create_amortizations_table', 2),
(112, '2024_10_04_074203_create_machine_downimes_table', 2),
(113, '2024_10_04_114050_add_column_to_good_receivings_table', 2),
(114, '2024_10_05_061324_add_column_to_good_receiving_products_table', 2),
(115, '2024_10_07_104748_create_carry_forwards_table', 2),
(116, '2024_10_11_013608_create_purchase_order_verification_histories_table', 2),
(117, '2024_10_11_111044_add_payment_status_to_purchase_orders_table', 2),
(118, '2024_10_11_123032_create_payments_table', 2),
(119, '2024_10_12_053409_create_user_bank_details_table', 2),
(120, '2024_10_12_080317_create_payroll_setups_table', 2),
(121, '2024_10_12_100512_create_payrolls_table', 2),
(122, '2024_10_12_100527_create_payroll_details_table', 2),
(123, '2024_10_12_100534_create_payroll_detail_children_table', 2),
(124, '2024_10_13_103227_create_payroll_approves_table', 2),
(125, '2024_10_14_071333_add_payment_status_to_invoices_table', 2),
(126, '2024_10_14_090415_add_remaining_balance_to_payments_table', 2),
(127, '2024_10_16_110908_create_categories_table', 2),
(128, '2024_09_04_091726_create_material_plannings', 3),
(129, '2024_09_04_175456_create_material_planning_details_table', 3),
(130, '2024_10_08_051144_create_notification_screens_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11);

-- --------------------------------------------------------

--
-- Table structure for table `more_users`
--

CREATE TABLE `more_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `annual_leave` varchar(255) DEFAULT NULL,
  `annual_leave_balance_day` varchar(255) DEFAULT NULL,
  `carried_leave` varchar(255) DEFAULT NULL,
  `carried_leave_balance_day` varchar(255) DEFAULT NULL,
  `medical_leave` varchar(255) DEFAULT NULL,
  `medical_leave_balance_day` varchar(255) DEFAULT NULL,
  `unpaid_leave` varchar(255) DEFAULT NULL,
  `unpaid_leave_balance_day` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_relationship` varchar(255) DEFAULT NULL,
  `emergency_contact_address` text DEFAULT NULL,
  `emergency_contact_phone_no` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `more_users`
--

INSERT INTO `more_users` (`id`, `user_id`, `annual_leave`, `annual_leave_balance_day`, `carried_leave`, `carried_leave_balance_day`, `medical_leave`, `medical_leave_balance_day`, `unpaid_leave`, `unpaid_leave_balance_day`, `deleted_at`, `created_at`, `updated_at`, `emergency_contact_name`, `emergency_contact_relationship`, `emergency_contact_address`, `emergency_contact_phone_no`) VALUES
(1, NULL, 'annual leave', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-27 09:35:30', '2024-07-27 09:35:30', NULL, NULL, NULL, NULL),
(2, 2, '4', '677', '6', '788', '9', '67844', '4', '234', NULL, '2024-07-29 05:30:28', '2024-07-29 05:30:28', NULL, NULL, NULL, NULL),
(3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-21 23:39:01', '2024-10-19 06:31:52', '2024-10-21 23:39:01', NULL, NULL, NULL, NULL),
(4, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-22 01:26:06', '2024-10-22 01:26:06', NULL, NULL, NULL, NULL),
(5, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 03:11:59', '2024-10-23 03:11:59', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0290cf70-6f42-4582-a385-91fa79449f87', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Material Issue & Receive\",\"action\":\"Create\",\"message\":\"Material Issue & Receive Create\",\"datetime\":\"09-10-2024 18:33:47\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/material-requisition\\/view\\/4\",\"created_at\":\"2024-10-09 10:33:47\"}', NULL, '2024-10-09 05:33:47', '2024-10-09 05:33:47'),
('05a094e5-1397-4d8e-8744-803bfeaa14d0', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Monthly Production Planning\",\"action\":\"List\",\"message\":\"Monthly Production Planning List\",\"datetime\":\"11-10-2024 17:56:25\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/production-scheduling\",\"created_at\":\"2024-10-11 09:56:25\"}', NULL, '2024-10-11 04:56:25', '2024-10-11 04:56:25'),
('0d2de193-a6da-4b4c-82c0-b358f44e5e1a', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Good Receiving\",\"action\":\"Create\",\"message\":\"Good Receiving Create\",\"datetime\":\"11-10-2024 22:13:52\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/good-receiving\\/view\\/13\",\"created_at\":\"2024-10-11 14:13:52\"}', NULL, '2024-10-11 09:13:52', '2024-10-11 09:13:52'),
('0d4c908b-5794-4433-ac38-6138336d1130', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Supplier Ranking\",\"action\":\"Create\",\"message\":\"Supplier Ranking Create\",\"datetime\":\"22-10-2024 17:30:09\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/pvd\\/supplier-ranking\\/view\\/6\",\"created_at\":\"2024-10-22 09:30:09\"}', NULL, '2024-10-22 04:30:09', '2024-10-22 04:30:09'),
('0d72c63b-7171-4056-bf5b-75b5b97225e5', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Price\",\"action\":\"Verify\",\"message\":\"Purchase Price Verify\",\"datetime\":\"23-10-2024 16:22:24\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/pvd\\/purchase-price\\/view\\/10\",\"created_at\":\"2024-10-23 08:22:24\"}', NULL, '2024-10-23 03:22:24', '2024-10-23 03:22:24'),
('11383704-13bc-471c-b92a-da00d480261f', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 15:05:51\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/12\",\"created_at\":\"2024-10-11 07:05:51\"}', NULL, '2024-10-11 02:05:51', '2024-10-11 02:05:51'),
('135a739b-6db7-4d95-adcd-ebbefe817a24', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Good Receiving\",\"action\":\"Create\",\"message\":\"Good Receiving Create\",\"datetime\":\"11-10-2024 22:20:06\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/good-receiving\\/view\\/14\",\"created_at\":\"2024-10-11 14:20:06\"}', NULL, '2024-10-11 09:20:06', '2024-10-11 09:20:06'),
('19c949cf-aa85-4956-b06d-3b0920b75ce2', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Planning\",\"action\":\"Create\",\"message\":\"Purchase Planning Create\",\"datetime\":\"20-10-2024 21:09:39\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/erp\\/pvd\\/purchase-planning\\/view\\/5\",\"created_at\":\"2024-10-20 13:09:39\"}', NULL, '2024-10-20 08:09:39', '2024-10-20 08:09:39'),
('233d7f30-b38c-4abb-bd7c-714b25343f7c', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Sales Price\",\"action\":\"Create\",\"message\":\"Sales Price Create\",\"datetime\":\"22-10-2024 17:39:04\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/sale-price\\/view\\/21\",\"created_at\":\"2024-10-22 09:39:04\"}', NULL, '2024-10-22 04:39:04', '2024-10-22 04:39:04'),
('2985270c-7837-4300-89ca-f6b630e22830', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Sales Price\",\"action\":\"Verify\",\"message\":\"Sales Price Verify\",\"datetime\":\"22-10-2024 17:49:13\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/sale-price\\/view\\/11\",\"created_at\":\"2024-10-22 09:49:13\"}', NULL, '2024-10-22 04:49:13', '2024-10-22 04:49:13'),
('2bd89b60-2de1-4d77-ae2b-ae020ae3f9b5', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Sales Price\",\"action\":\"Create\",\"message\":\"Sales Price Create\",\"datetime\":\"23-10-2024 12:02:20\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/sale-price\\/view\\/23\",\"created_at\":\"2024-10-23 04:02:20\"}', NULL, '2024-10-22 23:02:20', '2024-10-22 23:02:20'),
('2d4f4537-1d6a-4304-8323-41c0f45546b6', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 15:26:50\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/16\",\"created_at\":\"2024-10-11 07:26:50\"}', NULL, '2024-10-11 02:26:50', '2024-10-11 02:26:50'),
('3758cb52-1849-4797-b645-4066bb8fd419', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 15:24:56\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/15\",\"created_at\":\"2024-10-11 07:24:56\"}', NULL, '2024-10-11 02:24:56', '2024-10-11 02:24:56'),
('3854800d-707d-460c-97b5-ec852ca387e5', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Material Requisition\",\"action\":\"Create\",\"message\":\"Material Requisition Create\",\"datetime\":\"08-10-2024 17:44:16\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/material-requisition\\/view\\/4\",\"created_at\":\"2024-10-08 09:44:16\"}', NULL, '2024-10-08 04:44:16', '2024-10-08 04:44:16'),
('39fe6c0c-04cd-49aa-889a-930bf65a6c66', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Delivery Instruction\",\"action\":\"Create\",\"message\":\"Delivery Instruction Create\",\"datetime\":\"11-10-2024 14:19:37\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/delivery-instruction\\/view\\/8\",\"created_at\":\"2024-10-11 06:19:37\"}', NULL, '2024-10-11 01:19:37', '2024-10-11 01:19:37'),
('3b2dd9b3-8e34-4b13-aba5-8dac87810351', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Sales Price\",\"action\":\"Verify\",\"message\":\"Sales Price Verify\",\"datetime\":\"23-10-2024 12:11:54\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/sale-price\\/view\\/23\",\"created_at\":\"2024-10-23 04:11:54\"}', NULL, '2024-10-22 23:11:54', '2024-10-22 23:11:54'),
('3d7cc2d4-3fff-469d-a40a-c5ba0d14bf65', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"20-10-2024 21:18:22\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/mes\\/ppc\\/daily-production-planning\\/view\\/42\",\"created_at\":\"2024-10-20 13:18:22\"}', NULL, '2024-10-20 08:18:22', '2024-10-20 08:18:22'),
('4504caaa-49d6-4ef7-a135-8457763dc6e9', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Requisition\",\"action\":\"Create\",\"message\":\"Purchase Requisition Create\",\"datetime\":\"22-10-2024 18:44:39\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/pvd\\/purchase-requisition\\/view\\/4\",\"created_at\":\"2024-10-22 10:44:39\"}', NULL, '2024-10-22 05:44:39', '2024-10-22 05:44:39'),
('45c19151-2e08-4985-a7a2-59c764747aa6', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Order\",\"action\":\"Create\",\"message\":\"Order Create\",\"datetime\":\"22-10-2024 17:36:38\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/order\\/view\\/10\",\"created_at\":\"2024-10-22 09:36:38\"}', NULL, '2024-10-22 04:36:38', '2024-10-22 04:36:38'),
('482e89f1-0511-4685-af5a-50ef2f5edef5', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"21-10-2024 06:53:55\",\"route\":\"http:\\/\\/localhost\\/ZENIG\\/mes\\/ppc\\/daily-production-planning\\/view\\/49\",\"created_at\":\"2024-10-20 22:53:55\"}', NULL, '2024-10-20 17:53:55', '2024-10-20 17:53:55'),
('4893b5cc-8836-41b3-ac45-f5301f23a6e2', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Delivery Instruction\",\"action\":\"Create\",\"message\":\"Delivery Instruction Create\",\"datetime\":\"10-10-2024 19:18:26\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/delivery-instruction\\/view\\/7\",\"created_at\":\"2024-10-10 11:18:26\"}', NULL, '2024-10-10 06:18:26', '2024-10-10 06:18:26'),
('4940a4cf-c30b-4f31-8fbd-d1bc12210854', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Sales Price\",\"action\":\"Verify\",\"message\":\"Sales Price Verify\",\"datetime\":\"22-10-2024 18:00:14\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/sale-price\\/view\\/12\",\"created_at\":\"2024-10-22 10:00:14\"}', NULL, '2024-10-22 05:00:14', '2024-10-22 05:00:14'),
('4a51f97a-ab79-4323-8677-d9ebaaab56e3', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 17:56:04\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/18\",\"created_at\":\"2024-10-11 09:56:04\"}', NULL, '2024-10-11 04:56:04', '2024-10-11 04:56:04'),
('4d8c7f12-6cc0-4c0a-9567-d8622cbc3372', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Order\",\"action\":\"Update\",\"message\":\"Order Update\",\"datetime\":\"09-10-2024 14:03:24\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/bd\\/order\\/view\\/1\",\"created_at\":\"2024-10-09 06:03:24\"}', NULL, '2024-10-09 01:03:24', '2024-10-09 01:03:24'),
('54deda03-7688-4e39-a92a-57b120f0a11e', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Planning\",\"action\":\"Create\",\"message\":\"Purchase Planning Create\",\"datetime\":\"10-10-2024 19:06:05\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/pvd\\/purchase-planning\\/view\\/3\",\"created_at\":\"2024-10-10 11:06:05\"}', NULL, '2024-10-10 06:06:05', '2024-10-10 06:06:05'),
('5af6d5aa-852f-4ac1-87c1-d99f4acc8862', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 18:38:49\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/19\",\"created_at\":\"2024-10-11 10:38:49\"}', NULL, '2024-10-11 05:38:49', '2024-10-11 05:38:49'),
('5da42995-55ab-449a-a8a8-b932ba602dca', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Material Requisition\",\"action\":\"Create\",\"message\":\"Material Requisition Create\",\"datetime\":\"09-10-2024 19:10:41\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/material-requisition\\/view\\/5\",\"created_at\":\"2024-10-09 11:10:41\"}', NULL, '2024-10-09 06:10:41', '2024-10-09 06:10:41'),
('60058fba-9af6-452e-94fa-e79d3fa6512e', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Sales Price\",\"action\":\"Verify\",\"message\":\"Sales Price Verify\",\"datetime\":\"22-10-2024 18:45:44\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/sale-price\\/view\\/13\",\"created_at\":\"2024-10-22 10:45:44\"}', NULL, '2024-10-22 05:45:44', '2024-10-22 05:45:44'),
('6303643d-fc09-401c-8150-aa6d2f43e98d', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Supplier Ranking\",\"action\":\"Create\",\"message\":\"Supplier Ranking Create\",\"datetime\":\"09-10-2024 14:37:54\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/pvd\\/supplier-ranking\\/view\\/2\",\"created_at\":\"2024-10-09 06:37:54\"}', NULL, '2024-10-09 01:37:54', '2024-10-09 01:37:54'),
('716ea67e-d650-4c49-a76b-0ffcf9a28d9f', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Order\",\"action\":\"Create\",\"message\":\"Order Create\",\"datetime\":\"10-10-2024 18:08:39\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/bd\\/order\\/view\\/30\",\"created_at\":\"2024-10-10 10:08:39\"}', NULL, '2024-10-10 05:08:39', '2024-10-10 05:08:39'),
('724a8be9-5ecf-4ef7-abf9-53d02d42b5c7', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Price\",\"action\":\"Create\",\"message\":\"Purchase Price Create\",\"datetime\":\"23-10-2024 16:22:47\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/pvd\\/purchase-price\\/view\\/12\",\"created_at\":\"2024-10-23 08:22:48\"}', NULL, '2024-10-23 03:22:48', '2024-10-23 03:22:48'),
('753a30c2-98a3-4761-a4cf-492dc0d8a994', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 15:24:00\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/14\",\"created_at\":\"2024-10-11 07:24:00\"}', NULL, '2024-10-11 02:24:00', '2024-10-11 02:24:00'),
('7662efd5-391f-4177-97e1-ffb78486bcb2', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"20-10-2024 21:26:21\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/mes\\/ppc\\/daily-production-planning\\/view\\/43\",\"created_at\":\"2024-10-20 13:26:21\"}', NULL, '2024-10-20 08:26:21', '2024-10-20 08:26:21'),
('7a3ac06f-8abf-4468-8738-cf2ce7fd1cd9', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 15:00:23\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/11\",\"created_at\":\"2024-10-11 07:00:23\"}', NULL, '2024-10-11 02:00:23', '2024-10-11 02:00:23'),
('7ac06a25-eb93-4f3a-8f07-507ac6ab697e', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Order\",\"action\":\"Update\",\"message\":\"Order Update\",\"datetime\":\"09-10-2024 14:03:13\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/bd\\/order\\/view\\/1\",\"created_at\":\"2024-10-09 06:03:13\"}', NULL, '2024-10-09 01:03:13', '2024-10-09 01:03:13'),
('7f98d13a-46be-4567-90ce-4828c9366c39', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Order\",\"action\":\"Update\",\"message\":\"Order Update\",\"datetime\":\"11-10-2024 14:58:01\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/bd\\/order\\/view\\/31\",\"created_at\":\"2024-10-11 06:58:01\"}', NULL, '2024-10-11 01:58:01', '2024-10-11 01:58:01'),
('8ae6ff32-8a34-40ae-bc4e-e8f019289a60', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Order\",\"action\":\"Update\",\"message\":\"Order Update\",\"datetime\":\"09-10-2024 14:03:00\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/bd\\/order\\/view\\/1\",\"created_at\":\"2024-10-09 06:03:00\"}', NULL, '2024-10-09 01:03:00', '2024-10-09 01:03:00'),
('8af5a111-a376-4618-8da9-4c67a3c6a65d', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 15:21:19\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/13\",\"created_at\":\"2024-10-11 07:21:19\"}', NULL, '2024-10-11 02:21:19', '2024-10-11 02:21:19'),
('8b1045fd-7c66-4e81-a1f2-05c59a0c8811', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Price\",\"action\":\"Create\",\"message\":\"Purchase Price Create\",\"datetime\":\"22-10-2024 17:10:56\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/pvd\\/purchase-price\\/view\\/11\",\"created_at\":\"2024-10-22 09:10:56\"}', NULL, '2024-10-22 04:10:56', '2024-10-22 04:10:56'),
('95871dbf-ef58-41f1-a87f-06cffdd58a30', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Production Output Traceability\",\"action\":\"Create\",\"message\":\"Production Output Traceability Create\",\"datetime\":\"11-10-2024 17:56:25\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/production\\/production-output-traceability\",\"created_at\":\"2024-10-11 09:56:25\"}', NULL, '2024-10-11 04:56:25', '2024-10-11 04:56:25'),
('96018a41-8755-43f4-a674-b17917655c2a', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Good Receiving\",\"action\":\"Create\",\"message\":\"Good Receiving Create\",\"datetime\":\"09-10-2024 19:09:07\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/good-receiving\\/view\\/12\",\"created_at\":\"2024-10-09 11:09:07\"}', NULL, '2024-10-09 06:09:07', '2024-10-09 06:09:07'),
('97741c24-a178-4f5b-8a40-c81e79814978', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"20-10-2024 21:36:57\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/mes\\/ppc\\/daily-production-planning\\/view\\/46\",\"created_at\":\"2024-10-20 13:36:57\"}', NULL, '2024-10-20 08:36:57', '2024-10-20 08:36:57'),
('97e390ea-1da9-4629-9c81-4d9f6cc4215a', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Good Receiving\",\"action\":\"Create\",\"message\":\"Good Receiving Create\",\"datetime\":\"09-10-2024 14:28:58\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/good-receiving\\/view\\/11\",\"created_at\":\"2024-10-09 06:28:58\"}', NULL, '2024-10-09 01:28:58', '2024-10-09 01:28:58'),
('98a5239b-21ea-4097-8899-da680e9a981c', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Material Issue & Receive\",\"action\":\"Create\",\"message\":\"Material Issue & Receive Create\",\"datetime\":\"09-10-2024 17:51:48\",\"route\":\"http:\\/\\/localhost\\/pna\\/wms\\/operations\\/material-requisition\\/view\\/3\",\"created_at\":\"2024-10-09 09:51:48\"}', NULL, '2024-10-09 04:51:48', '2024-10-09 04:51:48'),
('995840fc-c948-4eca-bb15-77cb3974af0f', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Order\",\"action\":\"Verify\",\"message\":\"Purchase Order Verify\",\"datetime\":\"11-10-2024 19:47:59\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/bd\\/purchase-order\\/view\\/2\",\"created_at\":\"2024-10-11 11:47:59\"}', NULL, '2024-10-11 06:47:59', '2024-10-11 06:47:59'),
('a8dec2a3-3376-4d0f-a88a-766a3b207d0e', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Sales Price\",\"action\":\"Create\",\"message\":\"Sales Price Create\",\"datetime\":\"22-10-2024 18:45:35\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/bd\\/sale-price\\/view\\/22\",\"created_at\":\"2024-10-22 10:45:35\"}', NULL, '2024-10-22 05:45:35', '2024-10-22 05:45:35'),
('baf1173d-47a9-488f-b579-17ee9854a155', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 19:04:00\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/20\",\"created_at\":\"2024-10-11 11:04:00\"}', NULL, '2024-10-11 06:04:00', '2024-10-11 06:04:00'),
('bc39fc28-a2d5-4031-a441-7e0f7692cf54', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"20-10-2024 21:32:21\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/mes\\/ppc\\/daily-production-planning\\/view\\/45\",\"created_at\":\"2024-10-20 13:32:21\"}', NULL, '2024-10-20 08:32:21', '2024-10-20 08:32:21'),
('c347f676-3c99-496e-9175-96f8a61b789c', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Monthly Production Planning\",\"action\":\"List\",\"message\":\"Monthly Production Planning List\",\"datetime\":\"08-10-2024 19:14:34\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/production-scheduling\",\"created_at\":\"2024-10-08 11:14:34\"}', NULL, '2024-10-08 06:14:34', '2024-10-08 06:14:34'),
('cd48437e-8a82-4784-8cf3-6590ed899927', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"08-10-2024 19:14:22\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/9\",\"created_at\":\"2024-10-08 11:14:22\"}', NULL, '2024-10-08 06:14:22', '2024-10-08 06:14:22'),
('cd4a3e1c-3c9b-47d9-a01a-e138f2b1bb2f', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Quotation\",\"action\":\"Verify\",\"message\":\"Quotation Verify\",\"datetime\":\"19-10-2024 19:34:59\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/erp\\/bd\\/quotation\\/view\\/7\",\"created_at\":\"2024-10-19 11:34:59\"}', NULL, '2024-10-19 06:34:59', '2024-10-19 06:34:59'),
('e12b49b1-7b98-4c88-9581-c0c6d820ff11', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"21-10-2024 06:30:09\",\"route\":\"http:\\/\\/localhost\\/ZENIG\\/mes\\/ppc\\/daily-production-planning\\/view\\/48\",\"created_at\":\"2024-10-20 22:30:11\"}', NULL, '2024-10-20 17:30:11', '2024-10-20 17:30:11'),
('e5aa9332-f707-4a5a-ab58-d20c0b4c07d2', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Order\",\"action\":\"Create\",\"message\":\"Order Create\",\"datetime\":\"11-10-2024 14:19:06\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/bd\\/order\\/view\\/31\",\"created_at\":\"2024-10-11 06:19:06\"}', NULL, '2024-10-11 01:19:06', '2024-10-11 01:19:06'),
('eb3aa509-8197-4bf5-96e6-76df180c1dee', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Production Output Traceability\",\"action\":\"Create\",\"message\":\"Production Output Traceability Create\",\"datetime\":\"08-10-2024 19:14:34\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/production\\/production-output-traceability\",\"created_at\":\"2024-10-08 11:14:34\"}', NULL, '2024-10-08 06:14:34', '2024-10-08 06:14:34'),
('ec61e131-a692-4fa3-87d7-fda648bb5d4b', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Supplier Ranking\",\"action\":\"Create\",\"message\":\"Supplier Ranking Create\",\"datetime\":\"10-10-2024 19:08:53\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/pvd\\/supplier-ranking\\/view\\/3\",\"created_at\":\"2024-10-10 11:08:53\"}', NULL, '2024-10-10 06:08:53', '2024-10-10 06:08:53'),
('ef5bfbc3-97f1-4ba1-ad99-371e6fb96862', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Daily Production Planning\",\"action\":\"Create\",\"message\":\"Daily Production Planning Create\",\"datetime\":\"11-10-2024 15:57:06\",\"route\":\"http:\\/\\/localhost\\/pna\\/mes\\/ppc\\/daily-production-planning\\/view\\/17\",\"created_at\":\"2024-10-11 07:57:06\"}', NULL, '2024-10-11 02:57:06', '2024-10-11 02:57:06'),
('f419b9be-50f2-4981-a08f-1409c73c8e8e', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Purchase Requisition\",\"action\":\"Verify HOD\",\"message\":\"Purchase Requisition Verify HOD\",\"datetime\":\"22-10-2024 18:44:53\",\"route\":\"http:\\/\\/127.0.0.1:8002\\/erp\\/pvd\\/purchase-requisition\\/view\\/4\",\"created_at\":\"2024-10-22 10:44:53\"}', NULL, '2024-10-22 05:44:53', '2024-10-22 05:44:53'),
('f5cf6f9c-2f14-4971-bdb5-8fe9a9aa8253', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Supplier Ranking\",\"action\":\"Create\",\"message\":\"Supplier Ranking Create\",\"datetime\":\"10-10-2024 19:10:55\",\"route\":\"http:\\/\\/localhost\\/pna\\/erp\\/pvd\\/supplier-ranking\\/view\\/4\",\"created_at\":\"2024-10-10 11:10:55\"}', NULL, '2024-10-10 06:10:55', '2024-10-10 06:10:55'),
('f9035664-cc7b-4733-a8c8-8c77d3881d08', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Quotation\",\"action\":\"Create\",\"message\":\"Quotation Create\",\"datetime\":\"19-10-2024 19:24:18\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/erp\\/bd\\/quotation\\/view\\/8\",\"created_at\":\"2024-10-19 11:24:18\"}', NULL, '2024-10-19 06:24:18', '2024-10-19 06:24:18'),
('fb319c43-e6c1-48db-aba5-c913faedcc2b', 'App\\Notifications\\InAppNotification', 'App\\Models\\User', 1, '{\"screen\":\"Quotation\",\"action\":\"Verify\",\"message\":\"Quotation Verify\",\"datetime\":\"19-10-2024 19:34:02\",\"route\":\"http:\\/\\/localhost\\/ZENIGNEW-1\\/erp\\/bd\\/quotation\\/view\\/8\",\"created_at\":\"2024-10-19 11:34:02\"}', NULL, '2024-10-19 06:34:02', '2024-10-19 06:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `notification_screens`
--

CREATE TABLE `notification_screens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `screen` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `po_no` varchar(255) DEFAULT NULL,
  `order_month` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `date`, `order_no`, `po_no`, `order_month`, `status`, `attachment`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '29-07-2024', 'Order1', '222', '2024-07', 'complete', '20240729063856Untitled design.jpg', 1, NULL, '2024-07-29 06:38:56', '2024-07-29 06:38:56'),
(2, 3, '29-07-2024', 'Order 2', 'PO12345', '2024-08', 'in-progress', NULL, 1, '2024-07-30 01:32:24', '2024-07-29 07:54:50', '2024-07-30 01:32:24'),
(3, 1, '30-07-2024', 'Order2', 'PO2', '2024-10', 'complete', NULL, 1, NULL, '2024-07-30 01:32:15', '2024-07-30 01:32:15'),
(4, 4, '30-07-2024', 'CUST_ORDER_001', 'CUST_PO_001', '2024-08', 'complete', '20240730065526TNG_PAGE 2.jpg', 1, NULL, '2024-07-30 06:55:26', '2024-07-30 13:50:35'),
(5, 3, '30-07-2024', 'Order 3', 'PO4321', '2024-09', 'complete', NULL, 1, NULL, '2024-07-30 08:32:05', '2024-07-30 13:48:39'),
(6, 5, '30-07-2024', '03', 'CUST-03', '2024-06', 'complete', NULL, 1, NULL, '2024-07-30 13:58:44', '2024-07-30 15:01:52'),
(7, 5, '30-07-2024', '04', '04', '2024-05', 'complete', NULL, 1, NULL, '2024-07-30 14:36:37', '2024-07-30 15:02:10'),
(8, 6, '19-08-2024', 'CUST_ORDER_003', 'CUST_PO_003', '2024-04', 'complete', NULL, 1, NULL, '2024-07-30 15:03:15', '2024-08-19 03:57:10'),
(9, 1, '07-08-2024', 'TESTING ORDER', '55', '2024-12', 'in-progress', NULL, 1, NULL, '2024-08-07 03:45:31', '2024-08-07 03:45:31'),
(10, 5, '22-10-2024', 'eeeee', 'eeeeee', '2024-12', 'in-progress', NULL, 1, NULL, '2024-10-22 04:36:38', '2024-10-22 04:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `sst_percentage` varchar(255) DEFAULT NULL,
  `sst_value` varchar(255) DEFAULT NULL,
  `firm_qty` varchar(255) DEFAULT NULL,
  `n1_qty` varchar(255) DEFAULT NULL,
  `n2_qty` varchar(255) DEFAULT NULL,
  `n3_qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `sst_percentage`, `sst_value`, `firm_qty`, `n1_qty`, `n2_qty`, `n3_qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '455', '10', '45.50', '10', '20', '30', '40', NULL, '2024-07-29 06:38:56', '2024-07-29 06:38:56'),
(2, 1, 2, '1001', '10', '100.10', '20', '30', '40', '50', NULL, '2024-07-29 06:38:56', '2024-07-29 06:38:56'),
(3, 1, 3, '2500', '10', '250.00', '30', '40', '50', '60', NULL, '2024-07-29 06:38:56', '2024-07-29 06:38:56'),
(4, 1, 4, '3987', '10', '398.70', '40', '50', '60', '70', NULL, '2024-07-29 06:38:56', '2024-07-29 06:38:56'),
(5, 2, 5, '0', '10', '0.00', '100', '100', '100', '100', '2024-07-29 07:56:31', '2024-07-29 07:54:50', '2024-07-29 07:56:31'),
(6, 2, 6, '0', '10', '0.00', '200', '200', '200', '200', '2024-07-29 07:56:31', '2024-07-29 07:54:50', '2024-07-29 07:56:31'),
(7, 2, 7, '0', '10', '0.00', '300', '300', '300', '300', '2024-07-29 07:56:31', '2024-07-29 07:54:50', '2024-07-29 07:56:31'),
(8, 2, 5, '0', '10', '0.00', '100', '100', '100', '100', '2024-07-30 01:32:24', '2024-07-29 07:56:31', '2024-07-30 01:32:24'),
(9, 2, 6, '0', '10', '0.00', '200', '200', '200', '200', '2024-07-30 01:32:24', '2024-07-29 07:56:31', '2024-07-30 01:32:24'),
(10, 2, 7, '0', '10', '0.00', '300', '300', '300', '300', '2024-07-30 01:32:24', '2024-07-29 07:56:31', '2024-07-30 01:32:24'),
(11, 3, 1, '6000', '10', '600.00', '10', '20', '30', '40', NULL, '2024-07-30 01:32:15', '2024-07-30 01:32:15'),
(12, 4, 5, '100', '10', '10.00', '200', '250', '201', '250', '2024-07-30 13:50:35', '2024-07-30 06:55:26', '2024-07-30 13:50:35'),
(13, 5, 5, '100', '10', '10.00', '0', '0', '0', '0', '2024-07-30 08:32:20', '2024-07-30 08:32:05', '2024-07-30 08:32:20'),
(14, 5, 6, '100', '10', '10.00', '0', '0', '0', '0', '2024-07-30 08:32:20', '2024-07-30 08:32:05', '2024-07-30 08:32:20'),
(15, 5, 7, '100', '10', '10.00', '0', '0', '0', '0', '2024-07-30 08:32:20', '2024-07-30 08:32:05', '2024-07-30 08:32:20'),
(16, 5, 5, '100', '10', '10.00', '0', '0', '0', '0', '2024-07-30 13:48:39', '2024-07-30 08:32:20', '2024-07-30 13:48:39'),
(17, 5, 6, '100', '10', '10.00', '0', '0', '0', '0', '2024-07-30 13:48:39', '2024-07-30 08:32:20', '2024-07-30 13:48:39'),
(18, 5, 7, '100', '10', '10.00', '0', '0', '0', '0', '2024-07-30 13:48:39', '2024-07-30 08:32:20', '2024-07-30 13:48:39'),
(19, 5, 5, '100', '10', '10.00', '0', '0', '0', '0', NULL, '2024-07-30 13:48:39', '2024-07-30 13:48:39'),
(20, 5, 6, '100', '10', '10.00', '0', '0', '0', '0', NULL, '2024-07-30 13:48:39', '2024-07-30 13:48:39'),
(21, 5, 7, '100', '10', '10.00', '0', '0', '0', '0', NULL, '2024-07-30 13:48:39', '2024-07-30 13:48:39'),
(22, 4, 5, '100', '10', '10.00', '200', '250', '201', '250', NULL, '2024-07-30 13:50:35', '2024-07-30 13:50:35'),
(23, 6, 10, '200', '10', '20.00', '100', '150', '200', '250', '2024-07-30 15:01:52', '2024-07-30 13:58:44', '2024-07-30 15:01:52'),
(24, 7, 13, '150', '10', '15.00', '100', '180', '180', '180', '2024-07-30 15:02:10', '2024-07-30 14:36:37', '2024-07-30 15:02:10'),
(25, 6, 10, '200', '10', '20.00', '100', '150', '200', '250', NULL, '2024-07-30 15:01:52', '2024-07-30 15:01:52'),
(26, 7, 13, '150', '10', '15.00', '100', '180', '180', '180', NULL, '2024-07-30 15:02:10', '2024-07-30 15:02:10'),
(27, 8, 14, '500', '10', '50.00', '120', '120', '120', '120', '2024-08-19 03:57:10', '2024-07-30 15:03:15', '2024-08-19 03:57:10'),
(28, 9, 1, '6000', '10', '600.00', '10', '20', '30', '40', NULL, '2024-08-07 03:45:31', '2024-08-07 03:45:31'),
(29, 8, 14, '500', '10', '50.00', '120', '120', '120', '120', NULL, '2024-08-19 03:57:10', '2024-08-19 03:57:10'),
(30, 10, 2, '1001', '10', '100.10', '2', '1', '1', '1', NULL, '2024-10-22 04:36:38', '2024-10-22 04:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `outgoings`
--

CREATE TABLE `outgoings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `acc_no` varchar(255) DEFAULT NULL,
  `payment_term` varchar(255) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outgoings`
--

INSERT INTO `outgoings` (`id`, `order_id`, `sr_id`, `pr_id`, `category`, `ref_no`, `date`, `acc_no`, `payment_term`, `mode`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 1, '2', '2/1/24', '2024-08-07', '424', '525', 'yes', 1, NULL, '2024-08-07 05:27:10', '2024-08-07 05:27:10'),
(2, NULL, 2, NULL, '1', 'DO/2/24', '2024-08-19', '433', 'cash', 'red', 1, NULL, '2024-08-19 06:14:48', '2024-08-19 06:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `outgoing_details`
--

CREATE TABLE `outgoing_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outgoing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_qty` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outgoing_details`
--

INSERT INTO `outgoing_details` (`id`, `outgoing_id`, `product_id`, `return_qty`, `qty`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '100', NULL, NULL, '2024-08-07 05:27:10', '2024-08-07 05:27:10'),
(2, 1, 2, NULL, '0', NULL, NULL, '2024-08-07 05:27:10', '2024-08-07 05:27:10'),
(3, 1, 3, NULL, '0', NULL, NULL, '2024-08-07 05:27:10', '2024-08-07 05:27:10'),
(4, 1, 4, NULL, '0', NULL, NULL, '2024-08-07 05:27:10', '2024-08-07 05:27:10'),
(5, 2, 1, '1252', '15', NULL, NULL, '2024-08-19 06:14:48', '2024-08-19 06:14:48'),
(6, 2, 2, '6', '0', NULL, NULL, '2024-08-19 06:14:48', '2024-08-19 06:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `outgoing_locations`
--

CREATE TABLE `outgoing_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outgoing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `available_qty` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outgoing_sale_locations`
--

CREATE TABLE `outgoing_sale_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outgoing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `available_qty` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outgoing_sale_locations`
--

INSERT INTO `outgoing_sale_locations` (`id`, `outgoing_id`, `product_id`, `area_id`, `rack_id`, `level_id`, `available_qty`, `lot_no`, `qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, NULL, '0', NULL, '0', NULL, '2024-08-19 06:14:48', '2024-08-19 06:14:48'),
(2, 2, 1, 1, 1, 1, '821', '1', '15', NULL, '2024-08-19 06:14:48', '2024-08-19 06:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `paying_amount` decimal(15,2) NOT NULL,
  `balance` decimal(15,2) NOT NULL,
  `remaining_balance` decimal(15,2) DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `payment_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `purchase_order_id`, `invoice_id`, `payroll_id`, `total_amount`, `paying_amount`, `balance`, `remaining_balance`, `payment_method`, `account_id`, `payment_note`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, NULL, 659989.00, 200.00, 659789.00, 659789.00, 'cash', 1, 'paying first amount', '2024-10-22 23:49:59', '2024-10-22 23:49:59'),
(2, 2, NULL, NULL, 818554.00, 100.00, 818454.00, 818454.00, 'cash', 1, 'paying first purchase', '2024-10-22 23:58:52', '2024-10-22 23:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year` varchar(255) DEFAULT NULL,
  `month` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT 'due',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `year`, `month`, `date`, `status`, `payment_status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2024', 'October', '22-10-2024', 'Preparing', 'due', '2024-10-22 01:32:26', '2024-10-22 01:32:11', '2024-10-22 01:32:26'),
(2, '2024', 'October', '22-10-2024', 'Approved', 'due', NULL, '2024-10-22 01:32:29', '2024-10-22 23:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_approves`
--

CREATE TABLE `payroll_approves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_approves`
--

INSERT INTO `payroll_approves` (`id`, `payroll_id`, `created_by`, `designation_id`, `department_id`, `date`, `status`, `reason`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 1, '2024-10-22 09:06:02', 'Approved', NULL, NULL, '2024-10-22 04:06:02', '2024-10-22 04:06:02'),
(2, 2, 1, 1, 1, '2024-10-23 04:56:34', 'Approved', NULL, NULL, '2024-10-22 23:56:34', '2024-10-22 23:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_details`
--

CREATE TABLE `payroll_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `net_salary` varchar(255) DEFAULT NULL,
  `gross_salary` varchar(255) DEFAULT NULL,
  `total_deduction` varchar(255) DEFAULT NULL,
  `company_contribution` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `hrdf` varchar(255) DEFAULT NULL,
  `kwsp` varchar(255) DEFAULT NULL,
  `socso` varchar(255) DEFAULT NULL,
  `eis` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_details`
--

INSERT INTO `payroll_details` (`id`, `payroll_id`, `user_id`, `created_by`, `net_salary`, `gross_salary`, `total_deduction`, `company_contribution`, `ref_no`, `hrdf`, `kwsp`, `socso`, `eis`, `date`, `attachment`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '0', '0', '0', '0', 'SLIP/12024', '0', '1', '0', '1', '22-10-2024', '', '', NULL, '2024-10-22 01:32:11', '2024-10-23 02:55:56'),
(2, 1, 2, 1, '0', NULL, '0', '0', 'SLIP/22024', '0', '1', '0', '1', '22-10-2024', '', '', '2024-10-22 01:32:26', '2024-10-22 01:32:11', '2024-10-22 01:32:26'),
(3, 2, 1, 1, '0', '0', '0', '0', 'SLIP/12024', '0', '1', '0', '1', '22-10-2024', '', '', NULL, '2024-10-22 01:32:29', '2024-10-22 01:32:29'),
(4, 2, 2, 1, '0', NULL, '0', '0', 'SLIP/22024', '0', '1', '0', '1', '22-10-2024', '', '', NULL, '2024-10-22 01:32:29', '2024-10-22 01:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_detail_children`
--

CREATE TABLE `payroll_detail_children` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `particular` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `checkbox` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_setups`
--

CREATE TABLE `payroll_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrdf` varchar(255) DEFAULT NULL,
  `hrdf_per` varchar(255) DEFAULT NULL,
  `paysilp` varchar(255) DEFAULT NULL,
  `paysilp_remarks` varchar(255) DEFAULT NULL,
  `kwsp` varchar(255) DEFAULT NULL,
  `kwsp_category_1_employee_per` varchar(255) DEFAULT NULL,
  `kwsp_category_1_employer_per` varchar(255) DEFAULT NULL,
  `kwsp_category_2_employee_per` varchar(255) DEFAULT NULL,
  `kwsp_category_2_employer_per` varchar(255) DEFAULT NULL,
  `socso` varchar(255) DEFAULT NULL,
  `socso_employee_per` varchar(255) DEFAULT NULL,
  `socso_employer_per` varchar(255) DEFAULT NULL,
  `eis` varchar(255) DEFAULT NULL,
  `eis_employee_per` varchar(255) DEFAULT NULL,
  `eis_employer_per` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_setups`
--

INSERT INTO `payroll_setups` (`id`, `hrdf`, `hrdf_per`, `paysilp`, `paysilp_remarks`, `kwsp`, `kwsp_category_1_employee_per`, `kwsp_category_1_employer_per`, `kwsp_category_2_employee_per`, `kwsp_category_2_employer_per`, `socso`, `socso_employee_per`, `socso_employer_per`, `eis`, `eis_employee_per`, `eis_employer_per`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '0', '63', '1', 'Delectus mollit non', '1', '46', '17', '48', '20', '0', '18', '45', '1', '59', '39', NULL, '2024-10-21 23:59:19', '2024-10-21 23:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Quotation List', 'web', '2024-07-27 09:35:24', '2024-07-27 09:35:24'),
(2, 'Quotation Create', 'web', '2024-07-27 09:35:24', '2024-07-27 09:35:24'),
(3, 'Quotation Edit', 'web', '2024-07-27 09:35:24', '2024-07-27 09:35:24'),
(4, 'Quotation Verify', 'web', '2024-07-27 09:35:24', '2024-07-27 09:35:24'),
(5, 'Quotation View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(6, 'Quotation Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(7, 'Order List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(8, 'Order Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(9, 'Order Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(10, 'Order View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(11, 'Order Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(12, 'Sales Price List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(13, 'Sales Price Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(14, 'Sales Price Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(15, 'Sales Price Verify', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(16, 'Sales Price View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(17, 'Sales Price Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(18, 'Invoice List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(19, 'Invoice Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(20, 'Invoice Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(21, 'Invoice View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(22, 'Invoice Preview', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(23, 'Invoice Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(24, 'Machine Status View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(25, 'Shopfloor View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(26, 'Production Output Traceability List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(27, 'Production Output Traceability Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(28, 'Production Output Traceability QC', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(29, 'Production Output Traceability View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(30, 'Summary Report View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(31, 'Call For Assistance List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(32, 'Call For Assistance Update', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(33, 'Call For Assistance View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(34, 'OEE Report View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(35, 'Inventory Shopfloor View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(36, 'Inventory Dashboard View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(37, 'Good Receiving List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(38, 'Good Receiving Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(39, 'Good Receiving Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(40, 'Good Receiving Receive', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(41, 'Good Receiving QC', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(42, 'Good Receiving Approve', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(43, 'Good Receiving Allocation', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(44, 'Good Receiving View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(45, 'Good Receiving Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(46, 'Delivery Instruction List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(47, 'Delivery Instruction Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(48, 'Delivery Instruction Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(49, 'Delivery Instruction View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(50, 'Delivery Instruction Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(51, 'Stock Adjustment List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(52, 'Stock Adjustment Update', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(53, 'Stock Relocation List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(54, 'Stock Relocation Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(55, 'Stock Relocation Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(56, 'Stock Relocation View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(57, 'Stock Relocation Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(58, 'Product Reordering List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(59, 'Product Reordering Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(60, 'Product Reordering Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(61, 'Product Reordering View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(62, 'Product Reordering Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(63, 'Outgoing List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(64, 'Outgoing Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(65, 'Outgoing Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(66, 'Outgoing View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(67, 'Outgoing Preview', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(68, 'Outgoing Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(69, 'Sales Return List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(70, 'Sales Return Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(71, 'Sales Return Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(72, 'Sales Return View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(73, 'Sales Return Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(74, 'Purchase Return List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(75, 'Purchase Return Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(76, 'Purchase Return Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(77, 'Purchase Return View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(78, 'Purchase Return Preview', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(79, 'Purchase Return QC', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(80, 'Purchase Return Receive', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(81, 'Purchase Return Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(82, 'Inventory Report View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(83, 'Stock Card Report View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(84, 'Summary DO Report View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(85, 'Type Of Rejection List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(86, 'Type Of Rejection Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(87, 'Type Of Rejection Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(88, 'Type Of Rejection View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(89, 'Type Of Rejection Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(90, 'Type Of Product List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(91, 'Type Of Product Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(92, 'Type Of Product Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(93, 'Type Of Product View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(94, 'Type Of Product Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(95, 'Machine Tonnage List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(96, 'Machine Tonnage Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(97, 'Machine Tonnage Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(98, 'Machine Tonnage View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(99, 'Machine Tonnage Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(100, 'Machine List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(101, 'Machine Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(102, 'Machine Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(103, 'Machine View', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(104, 'Machine Delete', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(105, 'Area List', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(106, 'Area Create', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(107, 'Area Edit', 'web', '2024-07-27 09:35:25', '2024-07-27 09:35:25'),
(108, 'Area View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(109, 'Area Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(110, 'Area Rack List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(111, 'Area Rack Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(112, 'Area Rack Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(113, 'Area Rack View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(114, 'Area Rack Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(115, 'Area Level List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(116, 'Area Level Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(117, 'Area Level Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(118, 'Area Level View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(119, 'Area Level Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(120, 'Unit List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(121, 'Unit Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(122, 'Unit Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(123, 'Unit View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(124, 'Unit Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(125, 'Process List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(126, 'Process Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(127, 'Process Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(128, 'Process View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(129, 'Process Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(130, 'Customer List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(131, 'Customer Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(132, 'Customer Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(133, 'Customer View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(134, 'Customer Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(135, 'Supplier List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(136, 'Supplier Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(137, 'Supplier Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(138, 'Supplier View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(139, 'Supplier Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(140, 'Product List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(141, 'Product Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(142, 'Product Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(143, 'Product View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(144, 'Product Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(145, 'Department List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(146, 'Department Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(147, 'Department Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(148, 'Department View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(149, 'Department Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(150, 'Designation List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(151, 'Designation Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(152, 'Designation Edit', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(153, 'Designation View', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(154, 'Designation Delete', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(155, 'Role List', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(156, 'Role Create', 'web', '2024-07-27 09:35:26', '2024-07-27 09:35:26'),
(157, 'Role Edit', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(158, 'Role View', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(159, 'Role Delete', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(160, 'User List', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(161, 'User Create', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(162, 'User Edit', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(163, 'User View', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(164, 'User Delete', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(165, 'Leave List', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(166, 'Leave Create', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(167, 'Leave Edit', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(168, 'Leave View', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(169, 'Leave Manage', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(170, 'Leave Delete', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(171, 'General Settings SST Percentage', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(172, 'General Settings PO Important Note', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(173, 'General Settings Spec Break', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(174, 'General Settings Initial Ref No', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(175, 'General Settings PR Approval', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(176, 'Purchase Price List', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(177, 'Purchase Price Create', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(178, 'Purchase Price Edit', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(179, 'Purchase Price Verify', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(180, 'Purchase Price View', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(181, 'Purchase Price Delete', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(182, 'Purchase Requisition List', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(183, 'Purchase Requisition Create', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(184, 'Purchase Requisition Edit', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(185, 'Purchase Requisition View', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(186, 'Purchase Requisition Delete', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(187, 'Purchase Requisition Verify HOD', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(188, 'Purchase Requisition Verify ACC', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(189, 'Purchase Requisition Approve', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(190, 'Purchase Requisition Decline', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(191, 'Purchase Requisition Cancel', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(192, 'Purchase Planning List', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(193, 'Purchase Planning Create', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(194, 'Purchase Planning Edit', 'web', '2024-07-27 09:35:27', '2024-07-27 09:35:27'),
(195, 'Purchase Planning View', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(196, 'Purchase Planning Delete', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(197, 'Purchase Planning Verify HOD', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(198, 'Purchase Planning Verify ACC', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(199, 'Purchase Planning Approve', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(200, 'Purchase Planning Check', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(201, 'Purchase Planning Decline', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(202, 'Purchase Planning Cancel', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(203, 'Purchase Order List', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(204, 'Purchase Order Create', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(205, 'Purchase Order Edit', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(206, 'Purchase Order View', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(207, 'Purchase Order Preview', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(208, 'Purchase Order Delete', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(209, 'Purchase Order Verify', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(210, 'Purchase Order Check', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(211, 'Purchase Order Decline', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(212, 'Purchase Order Cancel', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(213, 'Supplier Ranking List', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(214, 'Supplier Ranking Create', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(215, 'Supplier Ranking Edit', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(216, 'Supplier Ranking View', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(217, 'Supplier Ranking Delete', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(218, 'BOM List', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(219, 'BOM Create', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(220, 'BOM Edit', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(221, 'BOM View', 'web', '2024-07-27 09:35:28', '2024-07-27 09:35:28'),
(222, 'BOM Delete', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(223, 'BOM Verification', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(224, 'BOM Verify', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(225, 'BOM Decline', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(226, 'BOM Cancel', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(227, 'BOM Inactive', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(228, 'BOM Report', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(229, 'Daily Production Planning List', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(230, 'Daily Production Planning Create', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(231, 'Daily Production Planning Edit', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(232, 'Daily Production Planning View', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(233, 'Daily Production Planning Delete', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(234, 'Monthly Production Planning', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(235, 'Production Scheduling View', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(236, 'Material Requisition List', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(237, 'Material Requisition Create', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(238, 'Material Requisition Edit', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(239, 'Material Requisition View', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(240, 'Material Requisition Delete', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(241, 'Material Requisition Issue', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(242, 'Material Requisition Receive', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(243, 'Transfer Request List', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(244, 'Transfer Request Create', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(245, 'Transfer Request Edit', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(246, 'Transfer Request View', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(247, 'Transfer Request Delete', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(248, 'Transfer Request Issue', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(249, 'Transfer Request Receive', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(250, 'Transfer Request QC', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(251, 'Discrepancy List', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(252, 'Discrepancy Edit', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(253, 'Discrepancy View', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(254, 'Attendance List', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(255, 'Attendance Import', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(256, 'Attendance Export', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(257, 'Category List', 'web', '2024-09-19 05:15:07', NULL),
(258, 'Category Create', 'web', '2024-10-19 07:16:05', NULL),
(260, 'Category Edit', 'web', '2024-10-19 07:16:05', NULL),
(261, 'Category View', 'web', '2024-10-19 07:16:05', NULL),
(262, 'Category Delete', 'web', '2024-10-19 07:16:05', NULL),
(263, 'Product Amortization Edit', 'web', '2024-10-21 06:33:19', '2024-10-21 06:33:19'),
(264, 'General Settings Payroll Setup', 'web', '2024-10-21 06:33:19', '2024-10-21 06:33:19'),
(265, 'Account Dashboard', 'web', '2024-10-21 06:33:19', '2024-10-21 06:33:19'),
(266, 'Account Category List', 'web', '2024-10-21 06:33:19', '2024-10-21 06:33:19'),
(267, 'Account Category Create', 'web', '2024-10-21 06:33:19', '2024-10-21 06:33:19'),
(268, 'Account Category Edit', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(269, 'Account Category View', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(270, 'Account Category Delete', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(271, 'Account List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(272, 'Account Create', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(273, 'Account Ledger List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(274, 'Account Transaction Create', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(275, 'Account Reconcile List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(276, 'Ledger List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(277, 'Trial Balance List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(278, 'Carryforward List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(279, 'Profit Loss List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(280, 'Balance Sheet List', 'web', '2024-10-21 06:33:20', '2024-10-21 06:33:20'),
(281, 'Summary Attendence List', 'web', '2024-10-22 00:19:23', '2024-10-22 00:19:23'),
(282, 'Payroll List', 'web', '2024-10-22 00:19:24', '2024-10-22 00:19:24'),
(283, 'Payroll Create', 'web', '2024-10-22 00:19:24', '2024-10-22 00:19:24');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_users`
--

CREATE TABLE `personal_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `ethnic` varchar(255) DEFAULT NULL,
  `personal_phone` varchar(255) DEFAULT NULL,
  `personal_mobile` varchar(255) DEFAULT NULL,
  `nric` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `sosco_no` varchar(255) DEFAULT NULL,
  `epf_no` varchar(255) DEFAULT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `base_salary` bigint(20) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `passport_expiry_date` varchar(255) DEFAULT NULL,
  `immigration_no` varchar(255) DEFAULT NULL,
  `immigration_no_expiry_date` varchar(255) DEFAULT NULL,
  `permit_no` varchar(255) DEFAULT NULL,
  `permit_no_expiry_date` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_users`
--

INSERT INTO `personal_users` (`id`, `user_id`, `gender`, `marital_status`, `dob`, `age`, `ethnic`, `personal_phone`, `personal_mobile`, `nric`, `nationality`, `sosco_no`, `epf_no`, `tin`, `base_salary`, `passport`, `passport_expiry_date`, `immigration_no`, `immigration_no_expiry_date`, `permit_no`, `permit_no_expiry_date`, `address`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2222, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-27 09:35:30', '2024-07-27 09:35:30'),
(2, 2, 'male', 'unmarried', '2001-09-13', '22 year(s) - 10 month(s)', NULL, '8637866683', '34342344', '4353534535', NULL, NULL, NULL, NULL, NULL, '3453534553', '2024-08-02', '5564434656', '2024-08-08', '56', '2024-08-05', 'GPO Chowk, Shahrah-e-Quaid-e-Azam, Mozang Chungi, Lahore, Punjab 54000', NULL, '2024-07-29 05:30:28', '2024-07-29 05:30:28'),
(3, 3, 'male', 'married', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-21 23:39:01', '2024-10-19 06:31:52', '2024-10-21 23:39:01'),
(5, 10, 'male', 'married', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-22 01:26:06', '2024-10-22 01:26:06'),
(6, 1, 'male', 'married', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 03:03:49', '2024-10-23 03:03:49'),
(7, 11, 'female', 'unmarried', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-23 03:11:59', '2024-10-23 03:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `po_important_notes`
--

CREATE TABLE `po_important_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_note` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `po_important_notes`
--

INSERT INTO `po_important_notes` (`id`, `po_note`, `created_at`, `updated_at`) VALUES
(1, 'This purchase order is valid for xxxx period.', '2024-07-27 09:35:30', '2024-07-30 03:52:03');

-- --------------------------------------------------------

--
-- Table structure for table `processes`
--

CREATE TABLE `processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `processes`
--

INSERT INTO `processes` (`id`, `name`, `code`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Rhoda Osborne', 'Fugiat vel dolore i', 'Blanditiis dolor inc', '2024-07-31 07:26:38', '2024-07-29 03:25:11', '2024-07-31 07:26:38'),
(2, 'May Lowery', 'Aspernatur eiusmod e', 'Debitis esse pariat', '2024-07-31 07:26:40', '2024-07-29 03:25:46', '2024-07-31 07:26:40'),
(3, 'ASSEMBLE', 'ASM', 'assembling components', NULL, '2024-07-29 08:33:44', '2024-07-29 08:33:44'),
(4, 'Injection', 'INJ', 'injecting', NULL, '2024-07-29 08:42:14', '2024-07-29 08:42:14'),
(5, 'Blow', 'BLOW', 'test', NULL, '2024-07-30 03:34:50', '2024-07-30 03:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `production_apis`
--

CREATE TABLE `production_apis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` varchar(255) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `mc_no` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_apis`
--

INSERT INTO `production_apis` (`id`, `production_id`, `start_time`, `end_time`, `mc_no`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '29-07-2024 03:31:05 PM', '29-07-2024 04:44:27 PM', 'M1', NULL, '2024-07-29 07:31:22', '2024-07-29 08:44:53'),
(2, '1', '29-07-2024 04:45:32 PM', '29-07-2024 06:01:36 PM', 'M1', NULL, '2024-07-29 08:46:04', '2024-07-29 10:01:56'),
(3, '11', '30-07-2024 10:22:38 PM', '30-07-2024 10:23:20 PM', 'M14', NULL, '2024-07-30 14:23:03', '2024-07-30 14:23:38'),
(4, '11', '30-07-2024 10:24:32 PM', NULL, 'M14', NULL, '2024-07-30 14:24:48', '2024-07-30 14:24:48'),
(5, '2', '31-07-2024 12:21:51 AM', '01-08-2024 12:10:00 AM', 'M1', NULL, '2024-07-30 16:21:52', '2024-07-31 16:10:15'),
(6, '13', '31-07-2024 07:35:23 AM', '31-07-2024 07:35:59 AM', 'M2', NULL, '2024-07-30 23:35:24', '2024-07-30 23:36:00'),
(7, '13', '31-07-2024 07:38:35 AM', '31-07-2024 07:39:40 AM', 'M2', NULL, '2024-07-30 23:38:35', '2024-07-30 23:39:41'),
(8, '13', '31-07-2024 10:44:50 PM', '31-07-2024 10:52:00 PM', 'M2', NULL, '2024-07-31 14:45:03', '2024-07-31 14:52:05'),
(9, '13', '31-07-2024 11:30:16 PM', '02-08-2024 06:33:54 AM', 'M2', NULL, '2024-07-31 15:30:37', '2024-08-01 22:35:42'),
(10, '2', '01-08-2024 08:38:44 AM', '01-08-2024 08:38:58 AM', 'M1', NULL, '2024-08-01 00:38:45', '2024-08-01 00:38:59'),
(11, '2', '01-08-2024 08:39:48 AM', '01-08-2024 08:40:06 AM', 'M1', NULL, '2024-08-01 00:39:50', '2024-08-01 00:40:07'),
(12, '21', '01-08-2024 05:19:38 PM', NULL, 'M12', NULL, '2024-08-01 09:20:03', '2024-08-01 09:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `production_output_traceabilities`
--

CREATE TABLE `production_output_traceabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dpp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `planned_date` varchar(255) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `spec_break` varchar(255) DEFAULT NULL,
  `planned_qty` varchar(255) DEFAULT NULL,
  `machine_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cavity` varchar(255) DEFAULT NULL,
  `po_no` varchar(255) DEFAULT NULL,
  `total_produced` varchar(255) DEFAULT NULL,
  `total_rejected_qty` varchar(255) DEFAULT NULL,
  `total_good_qty` varchar(255) DEFAULT NULL,
  `qc_produced` varchar(255) DEFAULT NULL,
  `qc_rejected_qty` varchar(255) DEFAULT NULL,
  `qc_good_qty` varchar(255) DEFAULT NULL,
  `process` varchar(255) DEFAULT NULL,
  `shift_length` varchar(255) DEFAULT NULL,
  `purging_weight` varchar(255) DEFAULT NULL,
  `report_qty` varchar(255) DEFAULT NULL,
  `remaining_qty` varchar(255) DEFAULT NULL,
  `planned_cycle_time` varchar(255) DEFAULT NULL,
  `actual_cycle_time` varchar(255) DEFAULT NULL,
  `planned_qty_hr` varchar(255) DEFAULT NULL,
  `actual_qty_hr` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_output_traceabilities`
--

INSERT INTO `production_output_traceabilities` (`id`, `dpp_id`, `planned_date`, `operator`, `shift`, `spec_break`, `planned_qty`, `machine_id`, `product_id`, `cavity`, `po_no`, `total_produced`, `total_rejected_qty`, `total_good_qty`, `qc_produced`, `qc_rejected_qty`, `qc_good_qty`, `process`, `shift_length`, `purging_weight`, `report_qty`, `remaining_qty`, `planned_cycle_time`, `actual_cycle_time`, `planned_qty_hr`, `actual_qty_hr`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-07-29', '[\"1\"]', 'DAY', 'Normal Hour', '10', 1, 1, '2', '13/1/24 /Rhoda Osborne/1', '0', '0', '0', '0', '2', '-2', 'Rhoda Osborne', NULL, NULL, '30', '-20', '786', '0', '4.5801526717557', '0', 'Checked', NULL, '2024-07-29 07:17:57', '2024-07-30 10:01:32'),
(2, 2, '2024-07-29', '[\"2\"]', 'DAY', 'Normal Hour', '1617', 1, 5, 'N/A', '13/2/24/ASSEMBLE/1', '0', '0', '0', '15', '0', '15', 'ASSEMBLE', NULL, NULL, NULL, NULL, '12', NULL, NULL, NULL, 'Checked', NULL, '2024-07-29 08:51:38', '2024-08-07 04:46:44'),
(3, 4, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '150', 1, 1, '1', '13/4/24 /Rhoda Osborne/1', '0', '0', '0', NULL, NULL, NULL, 'Rhoda Osborne', NULL, NULL, NULL, NULL, '90', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 07:05:15', '2024-07-30 07:05:15'),
(4, 5, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '150', 1, 1, '1', '13/4/24 /Rhoda Osborne/1', '0', '0', '0', NULL, NULL, NULL, 'Rhoda Osborne', NULL, NULL, NULL, NULL, '90', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 07:11:34', '2024-07-30 07:11:34'),
(5, 7, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '100', 1, 1, '1', '13/5/24 /Rhoda Osborne/1', '0', '0', '0', NULL, NULL, NULL, 'Rhoda Osborne', NULL, NULL, NULL, NULL, '90', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 08:10:33', '2024-07-30 08:10:33'),
(6, 8, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '100', 1, 1, '1', '13/5/24 /Rhoda Osborne/1', '0', '0', '0', NULL, NULL, NULL, 'Rhoda Osborne', NULL, NULL, NULL, NULL, '90', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 08:13:38', '2024-07-30 08:13:38'),
(7, 9, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '11', 1, 1, '1', '13/5/24 /Rhoda Osborne/1', '0', '0', '0', NULL, NULL, NULL, 'Rhoda Osborne', NULL, NULL, NULL, NULL, '90', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 08:15:22', '2024-07-30 08:15:22'),
(8, 12, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '100', 1, 1, '12', '13/7/24 /Rhoda Osborne/1', '0', '0', '0', NULL, NULL, NULL, 'Rhoda Osborne', NULL, NULL, NULL, NULL, '90', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 08:24:52', '2024-07-30 08:24:52'),
(9, 17, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '10', 1, 5, '12', '13/9/24/ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 09:20:20', '2024-07-30 09:20:20'),
(10, 18, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '123', 1, 1, '12', '13/10/24 /Rhoda Osborne/1', '0', '0', '0', NULL, NULL, NULL, 'Rhoda Osborne', NULL, NULL, NULL, NULL, '90', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 09:56:30', '2024-07-30 09:56:30'),
(11, 19, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '1200', 16, 10, '1', '13/11/24/ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Stop', NULL, '2024-07-30 14:07:57', '2024-07-30 14:30:51'),
(12, 19, '2024-07-30', '[\"1\"]', 'DAY', 'Normal Hour', '1200', 16, 10, '1', '13/11/24/ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-30 14:07:57', '2024-07-30 14:07:57'),
(13, 32, '2024-07-30', '[\"1\"]', 'NIGHT', 'Normal Hour', '50', 4, 14, '1', '13/13/24/Injection/1', '0', '0', '0', NULL, NULL, NULL, 'Injection', '7', '23', '15', '35', '15', '0', '240', '0', 'Start', NULL, '2024-07-30 15:11:12', '2024-08-01 04:04:59'),
(14, 33, '2024-08-01', '[\"2\"]', 'DAY', 'Normal Hour', '1210', 5, 5, '1', '13/14/24 /ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-31 03:39:44', '2024-07-31 03:39:44'),
(15, 34, '2024-08-01', '[\"1\"]', 'DAY', 'Normal Hour', '1617', 14, 5, '1', '13/15/24 /ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-31 06:27:28', '2024-07-31 06:27:28'),
(16, 16, '2024-07-25', '[\"1\"]', 'DAY', 'Normal Hour', '1617', 1, 5, '22', '13/9/24/ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-31 11:31:50', '2024-07-31 11:31:50'),
(17, 35, '2024-07-31', '[\"2\"]', 'DAY', 'Normal Hour', '2', 1, 14, '1', '13/16/24 /Injection/1', '0', '0', '0', NULL, NULL, NULL, 'Injection', NULL, NULL, NULL, NULL, '15', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-31 11:32:34', '2024-07-31 11:32:34'),
(18, 36, '2024-07-31', '[\"1\"]', 'DAY', 'Normal Hour', '1000', 1, 5, '2', '13/17/24 /ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-07-31 14:11:35', '2024-07-31 14:11:35'),
(19, 37, '2024-08-01', '[\"1\"]', 'DAY', 'Normal Hour', '1210', 1, 5, '1', '13/17/24/ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-08-01 07:54:19', '2024-08-01 07:54:19'),
(20, 37, '2024-08-01', '[\"1\"]', 'DAY', 'Normal Hour', '1210', 1, 5, '1', '13/17/24/ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '10', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-08-01 07:54:25', '2024-08-01 07:54:25'),
(21, 39, '2024-08-01', '[\"1\"]', 'DAY', 'Normal Hour', '1210', 14, 5, '2', '13/19/24 /ASSEMBLE/1', '0', '4', '-4', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, '8', '1202', '10', '0', '360', '0', 'Stop', NULL, '2024-08-01 07:55:20', '2024-08-01 09:30:29'),
(22, 40, '2024-08-07', '[\"1\"]', 'DAY', 'OT', '23', 1, 1, '2', '13/20/24 /ASSEMBLE/1', '0', '0', '0', NULL, NULL, NULL, 'ASSEMBLE', NULL, NULL, NULL, NULL, '35', NULL, NULL, NULL, 'Not-initiated', NULL, '2024-08-07 04:35:34', '2024-08-07 04:35:34');

-- --------------------------------------------------------

--
-- Table structure for table `production_output_traceability_details`
--

CREATE TABLE `production_output_traceability_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dpp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `machine_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `count` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_output_traceability_details`
--

INSERT INTO `production_output_traceability_details` (`id`, `dpp_id`, `pot_id`, `machine_id`, `start_time`, `end_time`, `duration`, `operator`, `count`, `remarks`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 1, '29-07-2024 03:28:24 PM', '30-07-2024 04:44:29 PM', '1516', '[\"2\"]', NULL, NULL, '2', NULL, '2024-07-29 07:28:24', '2024-07-30 08:44:29'),
(2, NULL, 1, 1, '30-07-2024 04:44:58 PM', '30-07-2024 04:52:29 PM', '7', '[\"1\",\"2\"]', NULL, 'finish', '3', NULL, '2024-07-30 08:44:58', '2024-07-30 08:52:29'),
(3, NULL, 2, 1, '30-07-2024 05:43:47 PM', '01-08-2024 11:58:57 AM', '2535', '[\"2\"]', NULL, NULL, '2', NULL, '2024-07-30 09:43:47', '2024-08-01 03:58:57'),
(4, NULL, 11, 16, '30-07-2024 10:20:40 PM', '30-07-2024 10:27:00 PM', '6', '[\"1\"]', NULL, NULL, '2', NULL, '2024-07-30 14:20:40', '2024-07-30 14:27:00'),
(5, NULL, 11, 16, '30-07-2024 10:30:44 PM', '30-07-2024 10:30:51 PM', '0', '[\"1\"]', NULL, 'done', '3', NULL, '2024-07-30 14:30:44', '2024-07-30 14:30:51'),
(6, NULL, 13, 4, '31-07-2024 07:33:54 AM', '31-07-2024 07:41:33 AM', '7', '[\"1\"]', NULL, NULL, '2', NULL, '2024-07-30 23:33:54', '2024-07-30 23:41:33'),
(7, NULL, 13, 4, '31-07-2024 10:21:10 PM', '31-07-2024 11:10:16 PM', '49', '[\"1\"]', NULL, NULL, '2', NULL, '2024-07-31 14:21:10', '2024-07-31 15:10:16'),
(8, NULL, 13, 4, '31-07-2024 11:21:08 PM', '01-08-2024 01:56:08 AM', '155', '[\"1\"]', NULL, NULL, '2', NULL, '2024-07-31 15:21:08', '2024-07-31 17:56:08'),
(9, NULL, 13, 4, '01-08-2024 11:41:33 AM', '01-08-2024 11:45:02 AM', '3', '[\"1\"]', NULL, NULL, '2', NULL, '2024-08-01 03:41:33', '2024-08-01 03:45:02'),
(10, NULL, 2, 1, '01-08-2024 11:59:33 AM', '01-08-2024 12:00:13 PM', '0', '[\"2\"]', NULL, 'Production done', '3', NULL, '2024-08-01 03:59:33', '2024-08-01 04:00:13'),
(11, NULL, 13, 4, '01-08-2024 12:04:59 PM', NULL, NULL, '[\"1\"]', NULL, NULL, '1', NULL, '2024-08-01 04:04:59', '2024-08-01 04:04:59'),
(12, NULL, 21, 14, '01-08-2024 05:03:20 PM', '01-08-2024 05:28:09 PM', '24', '[\"1\"]', NULL, NULL, '2', NULL, '2024-08-01 09:03:20', '2024-08-01 09:28:09'),
(13, NULL, 21, 14, '01-08-2024 05:29:06 PM', '01-08-2024 05:30:29 PM', '1', '[\"1\"]', NULL, 'Output done', '3', NULL, '2024-08-01 09:29:06', '2024-08-01 09:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `production_output_traceability_q_c_s`
--

CREATE TABLE `production_output_traceability_q_c_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `comments` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_output_traceability_q_c_s`
--

INSERT INTO `production_output_traceability_q_c_s` (`id`, `pot_id`, `rt_id`, `qty`, `comments`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2', 'test', NULL, '2024-07-30 10:01:32', '2024-07-30 10:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `production_output_traceability_rejections`
--

CREATE TABLE `production_output_traceability_rejections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `time` longtext DEFAULT NULL,
  `rt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `comments` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_output_traceability_rejections`
--

INSERT INTO `production_output_traceability_rejections` (`id`, `pot_id`, `time`, `rt_id`, `qty`, `comments`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '0', NULL, '2024-07-30 08:52:37', '2024-07-30 07:42:23', '2024-07-30 08:52:37'),
(2, 1, NULL, NULL, '0', NULL, NULL, '2024-07-30 08:52:37', '2024-07-30 08:52:37'),
(3, 13, NULL, NULL, '0', NULL, NULL, '2024-07-30 23:40:59', '2024-07-30 23:40:59'),
(4, 21, NULL, NULL, '0', NULL, '2024-08-01 08:55:58', '2024-08-01 08:55:00', '2024-08-01 08:55:58'),
(5, 21, NULL, NULL, '0', NULL, '2024-08-01 09:02:17', '2024-08-01 08:55:58', '2024-08-01 09:02:17'),
(6, 21, NULL, NULL, '0', NULL, '2024-08-01 09:25:08', '2024-08-01 09:02:17', '2024-08-01 09:25:08'),
(7, 21, NULL, NULL, '0', NULL, '2024-08-01 09:25:55', '2024-08-01 09:25:08', '2024-08-01 09:25:55'),
(8, 21, '8AM-9AM', 5, '1', '', '2024-08-01 09:25:55', '2024-08-01 09:25:08', '2024-08-01 09:25:55'),
(9, 21, NULL, NULL, '0', NULL, '2024-08-01 09:27:00', '2024-08-01 09:25:55', '2024-08-01 09:27:00'),
(10, 21, '8AM-9AM', 4, '1', '', '2024-08-01 09:27:00', '2024-08-01 09:25:55', '2024-08-01 09:27:00'),
(11, 21, NULL, NULL, '0', NULL, '2024-08-01 09:27:31', '2024-08-01 09:27:00', '2024-08-01 09:27:31'),
(12, 21, '8AM-9AM', 4, '1', '', '2024-08-01 09:27:31', '2024-08-01 09:27:00', '2024-08-01 09:27:31'),
(13, 21, '8AM-9AM', 5, '1', '', '2024-08-01 09:27:31', '2024-08-01 09:27:00', '2024-08-01 09:27:31'),
(14, 21, NULL, NULL, '0', NULL, '2024-08-01 09:27:51', '2024-08-01 09:27:31', '2024-08-01 09:27:51'),
(15, 21, '8AM-9AM', 4, '1', '', '2024-08-01 09:27:51', '2024-08-01 09:27:31', '2024-08-01 09:27:51'),
(16, 21, '8AM-9AM', 4, '1', '', '2024-08-01 09:27:51', '2024-08-01 09:27:31', '2024-08-01 09:27:51'),
(17, 21, '8AM-9AM', 6, '1', '', '2024-08-01 09:27:51', '2024-08-01 09:27:31', '2024-08-01 09:27:51'),
(18, 21, NULL, NULL, '0', NULL, NULL, '2024-08-01 09:27:51', '2024-08-01 09:27:51'),
(19, 21, '8AM-9AM', 4, '1', '', NULL, '2024-08-01 09:27:51', '2024-08-01 09:27:51'),
(20, 21, '8AM-9AM', 4, '1', '', NULL, '2024-08-01 09:27:51', '2024-08-01 09:27:51'),
(21, 21, '8AM-9AM', 4, '1', '', NULL, '2024-08-01 09:27:51', '2024-08-01 09:27:51'),
(22, 21, '8AM-9AM', 4, '1', '', NULL, '2024-08-01 09:27:51', '2024-08-01 09:27:51');

-- --------------------------------------------------------

--
-- Table structure for table `production_output_traceability_shifts`
--

CREATE TABLE `production_output_traceability_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dpp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `total_qty` varchar(255) DEFAULT NULL,
  `reject_qty` varchar(255) DEFAULT NULL,
  `good_qty` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_output_traceability_shifts`
--

INSERT INTO `production_output_traceability_shifts` (`id`, `dpp_id`, `pot_id`, `time`, `total_qty`, `reject_qty`, `good_qty`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '8AM-9AM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(2, 1, 1, '9AM-10AM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(3, 1, 1, '10AM-11AM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(4, 1, 1, '11AM-12PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(5, 1, 1, '12PM-1PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(6, 1, 1, '1PM-2PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(7, 1, 1, '2PM-3PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(8, 1, 1, '3PM-4PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(9, 1, 1, '4PM-5PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(10, 1, 1, '5PM-6PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(11, 1, 1, '6PM-7PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(12, 1, 1, '7PM-8PM', '0', '0', '0', NULL, '2024-07-30 08:52:37', NULL, '2024-07-30 08:52:37'),
(13, 1, 1, '8AM-9AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(14, 1, 1, '9AM-10AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(15, 1, 1, '10AM-11AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(16, 1, 1, '11AM-12PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(17, 1, 1, '12PM-1PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(18, 1, 1, '1PM-2PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(19, 1, 1, '2PM-3PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(20, 1, 1, '3PM-4PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(21, 1, 1, '4PM-5PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(22, 1, 1, '5PM-6PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(23, 1, 1, '6PM-7PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(24, 1, 1, '7PM-8PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(25, 32, 13, '8PM-9PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(26, 32, 13, '9PM-10PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(27, 32, 13, '10PM-11PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(28, 32, 13, '11PM-12AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(29, 32, 13, '12AM-1AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(30, 32, 13, '1AM-2AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(31, 32, 13, '2AM-3AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(32, 32, 13, '3AM-4AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(33, 32, 13, '4AM-5AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(34, 32, 13, '5AM-6AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(35, 32, 13, '6AM-7AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(36, 32, 13, '7AM-8AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(37, 39, 21, '8AM-9AM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(38, 39, 21, '9AM-10AM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(39, 39, 21, '10AM-11AM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(40, 39, 21, '11AM-12PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(41, 39, 21, '12PM-1PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(42, 39, 21, '1PM-2PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(43, 39, 21, '2PM-3PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(44, 39, 21, '3PM-4PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(45, 39, 21, '4PM-5PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(46, 39, 21, '5PM-6PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(47, 39, 21, '6PM-7PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(48, 39, 21, '7PM-8PM', '0', '0', '0', NULL, '2024-08-01 08:55:58', NULL, '2024-08-01 08:55:58'),
(49, 39, 21, '8AM-9AM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(50, 39, 21, '9AM-10AM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(51, 39, 21, '10AM-11AM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(52, 39, 21, '11AM-12PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(53, 39, 21, '12PM-1PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(54, 39, 21, '1PM-2PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(55, 39, 21, '2PM-3PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(56, 39, 21, '3PM-4PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(57, 39, 21, '4PM-5PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(58, 39, 21, '5PM-6PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(59, 39, 21, '6PM-7PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(60, 39, 21, '7PM-8PM', '0', '0', '0', NULL, '2024-08-01 09:02:17', NULL, '2024-08-01 09:02:17'),
(61, 39, 21, '8AM-9AM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(62, 39, 21, '9AM-10AM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(63, 39, 21, '10AM-11AM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(64, 39, 21, '11AM-12PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(65, 39, 21, '12PM-1PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(66, 39, 21, '1PM-2PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(67, 39, 21, '2PM-3PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(68, 39, 21, '3PM-4PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(69, 39, 21, '4PM-5PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(70, 39, 21, '5PM-6PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(71, 39, 21, '6PM-7PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(72, 39, 21, '7PM-8PM', '0', '0', '0', NULL, '2024-08-01 09:25:08', NULL, '2024-08-01 09:25:08'),
(73, 39, 21, '8AM-9AM', '0', '1', '-1', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(74, 39, 21, '9AM-10AM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(75, 39, 21, '10AM-11AM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(76, 39, 21, '11AM-12PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(77, 39, 21, '12PM-1PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(78, 39, 21, '1PM-2PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(79, 39, 21, '2PM-3PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(80, 39, 21, '3PM-4PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(81, 39, 21, '4PM-5PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(82, 39, 21, '5PM-6PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(83, 39, 21, '6PM-7PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(84, 39, 21, '7PM-8PM', '0', '0', '0', NULL, '2024-08-01 09:25:55', NULL, '2024-08-01 09:25:55'),
(85, 39, 21, '8AM-9AM', '0', '1', '-1', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(86, 39, 21, '9AM-10AM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(87, 39, 21, '10AM-11AM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(88, 39, 21, '11AM-12PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(89, 39, 21, '12PM-1PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(90, 39, 21, '1PM-2PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(91, 39, 21, '2PM-3PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(92, 39, 21, '3PM-4PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(93, 39, 21, '4PM-5PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(94, 39, 21, '5PM-6PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(95, 39, 21, '6PM-7PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(96, 39, 21, '7PM-8PM', '0', '0', '0', NULL, '2024-08-01 09:27:00', NULL, '2024-08-01 09:27:00'),
(97, 39, 21, '8AM-9AM', '0', '2', '-2', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(98, 39, 21, '9AM-10AM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(99, 39, 21, '10AM-11AM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(100, 39, 21, '11AM-12PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(101, 39, 21, '12PM-1PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(102, 39, 21, '1PM-2PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(103, 39, 21, '2PM-3PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(104, 39, 21, '3PM-4PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(105, 39, 21, '4PM-5PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(106, 39, 21, '5PM-6PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(107, 39, 21, '6PM-7PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(108, 39, 21, '7PM-8PM', '0', '0', '0', NULL, '2024-08-01 09:27:31', NULL, '2024-08-01 09:27:31'),
(109, 39, 21, '8AM-9AM', '0', '3', '-3', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(110, 39, 21, '9AM-10AM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(111, 39, 21, '10AM-11AM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(112, 39, 21, '11AM-12PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(113, 39, 21, '12PM-1PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(114, 39, 21, '1PM-2PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(115, 39, 21, '2PM-3PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(116, 39, 21, '3PM-4PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(117, 39, 21, '4PM-5PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(118, 39, 21, '5PM-6PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(119, 39, 21, '6PM-7PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(120, 39, 21, '7PM-8PM', '0', '0', '0', NULL, '2024-08-01 09:27:51', NULL, '2024-08-01 09:27:51'),
(121, 39, 21, '8AM-9AM', '0', '4', '-4', NULL, NULL, NULL, NULL),
(122, 39, 21, '9AM-10AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(123, 39, 21, '10AM-11AM', '0', '0', '0', NULL, NULL, NULL, NULL),
(124, 39, 21, '11AM-12PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(125, 39, 21, '12PM-1PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(126, 39, 21, '1PM-2PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(127, 39, 21, '2PM-3PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(128, 39, 21, '3PM-4PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(129, 39, 21, '4PM-5PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(130, 39, 21, '5PM-6PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(131, 39, 21, '6PM-7PM', '0', '0', '0', NULL, NULL, NULL, NULL),
(132, 39, 21, '7PM-8PM', '0', '0', '0', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `production_schedulings`
--

CREATE TABLE `production_schedulings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_no` varchar(255) DEFAULT NULL,
  `part_name` varchar(255) DEFAULT NULL,
  `type_of_product` bigint(20) UNSIGNED DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `variance` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `moq` varchar(255) DEFAULT NULL,
  `unit` bigint(20) UNSIGNED DEFAULT NULL,
  `part_weight` varchar(255) DEFAULT NULL,
  `standard_packaging` varchar(255) DEFAULT NULL,
  `customer_supplier` varchar(255) DEFAULT NULL,
  `customer_name` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_product_code` varchar(255) DEFAULT NULL,
  `supplier_name` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_product_code` varchar(255) DEFAULT NULL,
  `have_bom` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `part_no`, `part_name`, `type_of_product`, `model`, `category`, `variance`, `description`, `moq`, `unit`, `part_weight`, `standard_packaging`, `customer_supplier`, `customer_name`, `customer_product_code`, `supplier_name`, `supplier_product_code`, `have_bom`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'part1', 'Bumper', 1, 'Mark X', '', '45', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '500', 4, '65.667', '10', 'on', 1, 'c101', 4, '2222', '1', NULL, '2024-07-29 05:42:24', '2024-10-22 05:10:29'),
(2, 'part2', 'rust', 2, 'mercedes', 'REM', '56', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2000', 4, '34.654', '8', '', 2, 'C102', NULL, NULL, '1', NULL, '2024-07-29 05:44:35', '2024-07-29 07:16:52'),
(3, 'part3', 'front hood', 3, 'civic', 'OEM', '87', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '1690', 3, '67', '24', 'on', NULL, NULL, 1, 'S101', '1', NULL, '2024-07-29 05:45:45', '2024-07-29 07:17:07'),
(4, 'part4', 'Rims', 1, 'Series 7', 'REM', '29', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '600', 3, '23.779', '550', 'on', NULL, NULL, 2, 'S102', '1', NULL, '2024-07-29 05:53:36', '2024-07-29 07:18:04'),
(5, 'D38L 64780-BZ200-C0', 'PANEL ASSY, BACK DOOR TRIM', 4, 'ARUZ D38L', '', 'STD', 'N/A', '0', 4, '0', '0', 'on', 3, 'A', 4, 'B', NULL, NULL, '2024-07-29 07:46:47', '2024-10-22 05:14:25'),
(6, 'D38L 90467-10161-CKD', 'CLIP', 6, 'N/A', '', 'N/A', 'N/A', '0', 4, '0', '0', '', 3, 'A', NULL, NULL, '', NULL, '2024-07-29 07:48:17', '2024-07-29 07:48:17'),
(7, 'D38L 64780-BZ030-INJ', 'BOARD BACK DOOR TRIM', 5, 'Model A', 'OEM', 'Var 1', 'N/A', '10', 4, '0', '1', '', 3, 'A', NULL, NULL, '1', NULL, '2024-07-29 07:49:13', '2024-07-30 09:02:55'),
(8, 'PP02UVAS(M3)BK190BUV', 'PP LA880T-146B', 6, 'N/A', 'OEM', 'N/A', 'N/A', '0', 3, '0', '0', '', 3, 'A', NULL, NULL, '1', NULL, '2024-07-29 08:39:03', '2024-07-30 08:29:30'),
(9, 'CRUSH001', 'P001 CRUSHED', 5, 'Model A', 'REM', 'Var1', 'test', '1', 3, '2.5', '1', '', 4, 'PROD12_PEROD', NULL, NULL, '', NULL, '2024-07-30 04:10:11', '2024-07-30 04:10:11'),
(10, 'Part001', 'Part001_FG', 4, 'Model 1', '', 'Var 3', '', '2', 4, '13', '1', 'on', NULL, NULL, 4, 'sarmad', '1', NULL, '2024-07-30 13:39:32', '2024-10-22 05:23:33'),
(11, 'Part002', 'Part001_CP', 5, 'Model A', 'OEM', 'Var 3', '', '4', 3, '2.5', '2', '', 4, 'Part001_CP_PERODUA', NULL, NULL, '1', NULL, '2024-07-30 13:41:38', '2024-07-30 13:41:38'),
(12, 'Part003', 'Part003_RAW', 6, 'Model A', 'OEM', 'Var 3', '', '4', 4, '1.1', '1', '', 4, 'Part003_RAW_PERODUA', NULL, NULL, '1', NULL, '2024-07-30 13:43:01', '2024-07-30 13:43:01'),
(13, 'Part005', 'Part005_FG', 4, 'Model A', 'OEM', 'Var 3', '', '5', 4, '1.1', '2', '', 5, 'Part005_FG_PERODUA', NULL, NULL, '1', NULL, '2024-07-30 14:33:45', '2024-07-30 14:33:45'),
(14, 'Part006', 'Part006_FG', 4, 'Model B', 'OEM', 'Var 4', '', '6', 4, '1.1', '1', '', 6, 'Part006_FG_CUST006', NULL, NULL, '1', NULL, '2024-07-30 14:50:41', '2024-07-30 14:50:41'),
(15, 'Part007', 'Part007_CP', 5, 'Model B', 'OEM', 'Var 4', 'TEST', '1', 4, '2.5', '1', '', 6, 'Part007_CP_CUST006', NULL, NULL, '1', NULL, '2024-07-30 14:53:29', '2024-07-30 14:53:29'),
(16, 'Part008', 'Part008_RAW', 6, 'Model B', 'REM', 'Var 4', '', '4', 4, '1.1', '2', 'on', NULL, NULL, 4, 'Part008_RAW_CUST006', '1', NULL, '2024-07-30 14:56:32', '2024-07-30 14:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_reorderings`
--

CREATE TABLE `product_reorderings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `critical_min` varchar(255) DEFAULT NULL,
  `min_qty` varchar(255) DEFAULT NULL,
  `max_qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reorderings`
--

INSERT INTO `product_reorderings` (`id`, `product_id`, `critical_min`, `min_qty`, `max_qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '4', '30', NULL, '2024-08-07 05:18:59', '2024-08-07 05:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `pr_approvals`
--

CREATE TABLE `pr_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pr_approvals`
--

INSERT INTO `pr_approvals` (`id`, `designation_id`, `operator`, `amount`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '=', '1002', NULL, '2024-10-21 23:39:30', '2024-10-21 23:39:30'),
(2, 2, '>', '1000', NULL, '2024-10-21 23:39:30', '2024-10-21 23:39:30'),
(3, 3, '=', '10000', NULL, '2024-10-21 23:39:30', '2024-10-21 23:39:30'),
(4, 4, '>', '2222', NULL, '2024-10-21 23:39:30', '2024-10-21 23:39:30');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pp_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pp_pr` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `quotation_ref_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `payment_term` varchar(255) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `important_note` longtext DEFAULT NULL,
  `required_date` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `bulk_wo_discount` varchar(255) DEFAULT NULL,
  `bulk_required_date` varchar(255) DEFAULT NULL,
  `total_qty` varchar(255) DEFAULT NULL,
  `total_sale_tax` varchar(255) DEFAULT NULL,
  `total_discount` varchar(255) DEFAULT NULL,
  `net_total` varchar(255) DEFAULT NULL,
  `checked_by` varchar(255) DEFAULT NULL,
  `checked_by_time` varchar(255) DEFAULT NULL,
  `verify_by` varchar(255) DEFAULT NULL,
  `verify_by_time` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'due'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `pr_id`, `pp_id`, `supplier_id`, `pp_pr`, `ref_no`, `quotation_ref_no`, `date`, `attachment`, `payment_term`, `department_id`, `important_note`, `required_date`, `status`, `bulk_wo_discount`, `bulk_required_date`, `total_qty`, `total_sale_tax`, `total_discount`, `net_total`, `checked_by`, `checked_by_time`, `verify_by`, `verify_by_time`, `reason`, `created_by`, `deleted_at`, `created_at`, `updated_at`, `payment_status`) VALUES
(1, NULL, 1, 1, NULL, '5/1/24', '7/8897/24', '2024-07-29', '20240729073330Untitled design.jpg', 'cash', 1, 'Lorem ipsum dolor sit amet . The graphic and typographic operators know this well, in reality all the professions dealing with the universe of communication have a stable relationship with these words, but what is it? Lorem ipsum is a dummy text without any sense.\r\n\r\nIt is a sequence of Latin words that, as they are positioned, do not form sentences with a complete sense, but give life to a test text useful to fill spaces that will subsequently be occupied from ad hoc texts composed by communication professionals.\r\n\r\nIt is certainly the most famous placeholder text even if there are different versions distinguishable from the order in which the Latin words are repeated.\r\n\r\nLorem ipsum contains the typefaces more in use, an aspect that allows you to have an overview of the rendering of the text in terms of font choice and font size .\r\n\r\nWhen referring to Lorem ipsum, different expressions are used, namely fill text , fictitious text , blind text or placeholder text : in short, its meaning can also be zero, but its usefulness is so clear as to go through the centuries and resist the ironic and modern versions that came with the arrival of the web.\r\n\r\nThe most used version of Lorem Ipsum?\r\n« Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. »\r\n\r\n \r\nLorem Ipsum Text Generator is SEO-proof!\r\nWith our filling text generation tool, in addition to customizing the text with HTML elements, you have the possibility to create a new one starting directly from your text!\r\n\r\nIn this way you will avoid indexing the website with the keywords contained in the classic Lorem Ipsum.\r\n\r\nWith Lorem Ipzum\'s tool, you can insert texts directly with the keywords that will serve to index your website. Try it!', '2024-07-29', 'verified', NULL, '1', '2290', '160922.10', '399469.00', '1770143.10', 'admin', '29-07-2024 15:36:50', 'admin', '29-07-2024 15:38:36', NULL, 1, '2024-07-29 07:41:55', '2024-07-29 07:33:30', '2024-07-29 07:41:55', 'due'),
(2, 1, NULL, 1, '1', '5/2/24', '7/97/24', '2024-07-29', '20240729073950Untitled design.jpg', 'cashs', 2, 'Lorem ipsum dolor sit amet . The graphic and typographic operators know this well, in reality all the professions dealing with the universe of communication have a stable relationship with these words, but what is it? Lorem ipsum is a dummy text without any sense.\r\n\r\nIt is a sequence of Latin words that, as they are positioned, do not form sentences with a complete sense, but give life to a test text useful to fill spaces that will subsequently be occupied from ad hoc texts composed by communication professionals.\r\n\r\nIt is certainly the most famous placeholder text even if there are different versions distinguishable from the order in which the Latin words are repeated.\r\n\r\nLorem ipsum contains the typefaces more in use, an aspect that allows you to have an overview of the rendering of the text in terms of font choice and font size .\r\n\r\nWhen referring to Lorem ipsum, different expressions are used, namely fill text , fictitious text , blind text or placeholder text : in short, its meaning can also be zero, but its usefulness is so clear as to go through the centuries and resist the ironic and modern versions that came with the arrival of the web.\r\n\r\nThe most used version of Lorem Ipsum?\r\n« Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. »\r\n\r\n \r\nLorem Ipsum Text Generator is SEO-proof!\r\nWith our filling text generation tool, in addition to customizing the text with HTML elements, you have the possibility to create a new one starting directly from your text!\r\n\r\nIn this way you will avoid indexing the website with the keywords contained in the classic Lorem Ipsum.\r\n\r\nWith Lorem Ipzum\'s tool, you can insert texts directly with the keywords that will serve to index your website. Try it!', '2024-07-29', 'verified', NULL, '1', '1000', '74414.00', '198460.00', '818554.00', 'admin', '29-07-2024 15:40:27', 'admin', '29-07-2024 15:40:37', NULL, 1, NULL, '2024-07-29 07:39:50', '2024-10-22 23:58:52', 'partially_paid'),
(3, NULL, 2, 3, NULL, '5/3/24', NULL, '2024-08-07', NULL, NULL, 3, 'This purchase order is valid for xxxx period.', '2024-08-07', 'in-progress', NULL, '1', '4290', '252962.10', '281069.00', '2782583.10', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-07 04:13:12', '2024-10-21 01:35:55', 'partially_paid');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_details`
--

CREATE TABLE `purchase_order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `required_date` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `disc` varchar(255) DEFAULT NULL,
  `disc_percent` varchar(255) DEFAULT NULL,
  `sale_tax` varchar(255) DEFAULT NULL,
  `sale_tax_percent` varchar(255) DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `wo_discount` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_details`
--

INSERT INTO `purchase_order_details` (`id`, `purchase_order_id`, `product_id`, `required_date`, `price`, `qty`, `disc`, `disc_percent`, `sale_tax`, `sale_tax_percent`, `total`, `wo_discount`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2024-07-29', '601', '1690', '101569.00', '10', '91412.10', '10', '1005533.10', '0', '2024-07-29 07:41:55', '2024-07-29 07:33:30', '2024-07-29 07:41:55'),
(2, 1, 4, '2024-07-29', '1655', '600', '297900.00', '30', '69510.00', '10', '764610.00', '0', '2024-07-29 07:41:55', '2024-07-29 07:33:30', '2024-07-29 07:41:55'),
(3, 2, 1, '2024-07-29', '201', '100', '8040.00', '40', '1206.00', '10', '13266.00', '0', NULL, '2024-07-29 07:39:50', '2024-07-29 07:39:50'),
(4, 2, 2, '2024-07-29', '401', '200', '16040.00', '20', '6416.00', '10', '70576.00', '0', NULL, '2024-07-29 07:39:50', '2024-07-29 07:39:50'),
(5, 2, 3, '2024-07-29', '601', '300', '108180.00', '60', '7212.00', '10', '79332.00', '0', NULL, '2024-07-29 07:39:50', '2024-07-29 07:39:50'),
(6, 2, 4, '2024-07-29', '1655', '400', '66200.00', '10', '59580.00', '10', '655380.00', '0', NULL, '2024-07-29 07:39:50', '2024-07-29 07:39:50'),
(7, 3, 2, '2024-08-07', '401', '2000', '80200.00', '10', '72180.00', '10', '793980.00', '0', NULL, '2024-08-07 04:13:12', '2024-08-07 04:13:12'),
(8, 3, 3, '2024-08-07', '601', '1690', '101569.00', '10', '91412.10', '10', '1005533.10', '0', NULL, '2024-08-07 04:13:12', '2024-08-07 04:13:12'),
(9, 3, 4, '2024-08-07', '1655', '600', '99300.00', '10', '89370.00', '10', '983070.00', '0', NULL, '2024-08-07 04:13:12', '2024-08-07 04:13:12');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_verification_histories`
--

CREATE TABLE `purchase_order_verification_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `action_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_plannings`
--

CREATE TABLE `purchase_plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_plannings`
--

INSERT INTO `purchase_plannings` (`id`, `order_id`, `ref_no`, `date`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '3/1/24', '2024-07-29', 1, NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(2, 3, '3/2/24', '2024-07-30', 1, NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(3, 9, '3/3/24', '2024-08-07', 1, NULL, '2024-08-07 04:01:10', '2024-08-07 04:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_planning_details`
--

CREATE TABLE `purchase_planning_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_planning_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_qty` varchar(255) DEFAULT NULL,
  `inventory_qty` varchar(255) DEFAULT NULL,
  `balance` varchar(255) DEFAULT NULL,
  `moq` varchar(255) DEFAULT NULL,
  `planning_qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_planning_details`
--

INSERT INTO `purchase_planning_details` (`id`, `purchase_planning_id`, `product_id`, `total_qty`, `inventory_qty`, `balance`, `moq`, `planning_qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '21600', '300', '1390', '1690', '3000', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(2, 1, 4, '9200', '400', '200', '600', '3500', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(3, 2, 2, '116000', '200', '1800', '2000', '250', NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(4, 2, 3, '120000', '300', '1390', '1690', '350', NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(5, 2, 4, '200000', '400', '200', '600', '450', NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(6, 3, 12, '13600', '0', '4', '4', '0', NULL, '2024-08-07 04:01:10', '2024-08-07 04:01:10'),
(7, 3, 13, '17600', '0', '5', '5', '0', NULL, '2024-08-07 04:01:10', '2024-08-07 04:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_planning_products`
--

CREATE TABLE `purchase_planning_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_planning_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_qty` varchar(255) DEFAULT NULL,
  `total_qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_planning_products`
--

INSERT INTO `purchase_planning_products` (`id`, `purchase_planning_id`, `product_id`, `product_qty`, `total_qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '10', '50', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(2, 1, 2, '20', '70', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(3, 1, 3, '30', '90', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(4, 1, 4, '40', '110', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(5, 2, 1, '10', '50', NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(6, 3, 1, '10', '50', NULL, '2024-08-07 04:01:10', '2024-08-07 04:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_planning_suppliers`
--

CREATE TABLE `purchase_planning_suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_planning_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_planning_suppliers`
--

INSERT INTO `purchase_planning_suppliers` (`id`, `purchase_planning_id`, `product_id`, `supplier_id`, `qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, '3000', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(2, 1, 4, 1, '3500', NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(3, 2, 2, 1, '250', NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(4, 2, 3, 1, '350', NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(5, 2, 4, 1, '450', NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(6, 3, NULL, NULL, '0', NULL, '2024-08-07 04:01:10', '2024-08-07 04:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_planning_verifications`
--

CREATE TABLE `purchase_planning_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_planning_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `designation_id` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_planning_verifications`
--

INSERT INTO `purchase_planning_verifications` (`id`, `purchase_planning_id`, `user_id`, `status`, `date`, `department_id`, `designation_id`, `reason`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'prepared', '29-07-2024 03:18:55 PM', NULL, NULL, NULL, NULL, '2024-07-29 07:18:55', '2024-07-29 07:18:55'),
(2, 2, 1, 'prepared', '30-07-2024 09:43:29 AM', NULL, NULL, NULL, NULL, '2024-07-30 01:43:29', '2024-07-30 01:43:29'),
(3, 3, 1, 'prepared', '07-08-2024 12:01:10 PM', NULL, NULL, NULL, NULL, '2024-08-07 04:01:10', '2024-08-07 04:01:10'),
(4, 1, 1, 'checked', '07-08-2024 12:01:50 PM', NULL, NULL, NULL, NULL, '2024-08-07 04:01:50', '2024-08-07 04:01:50'),
(5, 2, 1, 'checked', '07-08-2024 12:02:41 PM', NULL, NULL, NULL, NULL, '2024-08-07 04:02:41', '2024-08-07 04:02:41');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_prices`
--

CREATE TABLE `purchase_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'submitted' COMMENT 'submitted,verified,declined',
  `reason` varchar(255) DEFAULT NULL,
  `verification_by` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_prices`
--

INSERT INTO `purchase_prices` (`id`, `product_id`, `price`, `date`, `status`, `reason`, `verification_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '201', '2024-07-29', 'verified', NULL, '1', '2024-07-30 01:32:40', '2024-07-29 06:39:42', '2024-07-30 01:32:40'),
(2, 2, '401', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 06:45:54', '2024-07-29 06:46:41'),
(3, 3, '601', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 06:46:08', '2024-07-29 06:46:47'),
(4, 4, '1655', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 06:46:27', '2024-07-29 06:46:52'),
(5, 5, '100', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 07:57:22', '2024-07-29 07:58:01'),
(6, 6, '100', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 07:57:37', '2024-07-29 07:57:57'),
(7, 7, '100', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 07:57:50', '2024-07-29 07:57:54'),
(8, 1, '2500', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-30 01:32:53', '2024-07-30 01:33:09'),
(9, 1, '9876', '2024-08-07', 'verified', NULL, '1', NULL, '2024-08-07 03:46:33', '2024-08-07 03:47:19'),
(10, 4, '788', '2024-08-08', 'verified', NULL, '1', NULL, '2024-08-09 04:26:09', '2024-10-23 03:22:24'),
(11, 2, '44', '21-10-2024', 'submitted', NULL, NULL, NULL, '2024-10-22 04:10:56', '2024-10-22 04:10:56'),
(12, 3, '34443', '20-10-2024', 'submitted', NULL, NULL, NULL, '2024-10-23 03:22:47', '2024-10-23 03:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_receive_locations`
--

CREATE TABLE `purchase_receive_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_receive_locations`
--

INSERT INTO `purchase_receive_locations` (`id`, `purchase_return_id`, `product_id`, `area_id`, `rack_id`, `level_id`, `lot_no`, `qty`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 1, 1, '1', '100', NULL, NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(2, 1, 2, 1, 1, 1, '1', '100', NULL, NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(3, 1, 2, 1, 1, 1, '1', '100', NULL, NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(4, 1, 2, 1, 1, 1, '1', '100', NULL, NULL, '2024-08-07 05:25:51', '2024-08-07 05:25:51'),
(5, 2, 2, 1, 2, 2, '2', '20', NULL, NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30'),
(6, 2, 2, 1, 2, 2, '2', '20', NULL, NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30'),
(7, 2, 2, 1, 2, 2, '2', '20', NULL, NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30'),
(8, 2, 2, 1, 2, 2, '2', '20', NULL, NULL, '2024-08-19 06:11:30', '2024-08-19 06:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pr_no` varchar(255) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `require_date` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `category_other` varchar(255) DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `current_status` varchar(255) DEFAULT 'Requested' COMMENT 'Requested,Verified,Approved,Declined,Cancelled',
  `requested_by` varchar(255) DEFAULT NULL,
  `verified_by` varchar(255) DEFAULT NULL,
  `verified_by_id` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requisitions`
--

INSERT INTO `purchase_requisitions` (`id`, `pr_no`, `department_id`, `status`, `date`, `require_date`, `category`, `category_other`, `total`, `attachment`, `remarks`, `current_status`, `requested_by`, `verified_by`, `verified_by_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '4/1/24', NULL, 'Urgent', '2024-07-29', '2024-07-31', 'Others', 'red', '942600', '20240729072700Untitled design.jpg', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Approved', '1', 'hod', NULL, NULL, '2024-07-29 07:27:00', '2024-07-29 07:32:20'),
(2, '4/2/24', NULL, 'Priority', '2024-08-07', '2024-08-07', 'Direct Item', NULL, '36268', NULL, NULL, 'Approved', '1', 'hod', NULL, NULL, '2024-08-07 04:06:56', '2024-08-07 04:08:12'),
(3, 'PR/3/24', NULL, 'Priority', '2024-08-19', '2024-08-20', 'Printing & Stationary', NULL, '10330', NULL, NULL, 'Requested', '1', NULL, NULL, NULL, '2024-08-19 04:24:17', '2024-08-19 04:24:17'),
(4, 'PR/4/24', 1, 'Not Urgent', '2024-10-22', '2024-10-21', 'Direct Item', NULL, '25266', NULL, NULL, 'Requested', '1', 'hod', '1', NULL, '2024-10-22 05:44:39', '2024-10-22 05:44:53');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_details`
--

CREATE TABLE `purchase_requisition_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_requisition_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `purpose` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requisition_details`
--

INSERT INTO `purchase_requisition_details` (`id`, `product_id`, `purchase_requisition_id`, `price`, `qty`, `total`, `purpose`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '201', '100', '20100', 'MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM', NULL, '2024-07-29 07:27:00', '2024-07-29 07:27:00'),
(2, 2, 1, '401', '200', '80200', 'MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM', NULL, '2024-07-29 07:27:00', '2024-07-29 07:27:00'),
(3, 3, 1, '601', '300', '180300', 'MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM', NULL, '2024-07-29 07:27:00', '2024-07-29 07:27:00'),
(4, 4, 1, '1655', '400', '662000', 'MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM', NULL, '2024-07-29 07:27:00', '2024-07-29 07:27:00'),
(5, 2, 2, '401', '23', '9223', 'ss', NULL, '2024-08-07 04:06:56', '2024-08-07 04:06:56'),
(6, 3, 2, '601', '45', '27045', 'ss', NULL, '2024-08-07 04:06:56', '2024-08-07 04:06:56'),
(7, 4, 3, '1655', '6', '9930', NULL, NULL, '2024-08-19 04:24:17', '2024-08-19 04:24:17'),
(8, 5, 3, '100', '4', '400', NULL, NULL, '2024-08-19 04:24:17', '2024-08-19 04:24:17'),
(9, 1, 4, '9876', '2', '19752', NULL, NULL, '2024-10-22 05:44:39', '2024-10-22 05:44:39'),
(10, 2, 4, '401', '2', '802', NULL, NULL, '2024-10-22 05:44:39', '2024-10-22 05:44:39'),
(11, 3, 4, '601', '2', '1202', NULL, NULL, '2024-10-22 05:44:39', '2024-10-22 05:44:39'),
(12, 4, 4, '1655', '2', '3310', NULL, NULL, '2024-10-22 05:44:39', '2024-10-22 05:44:39'),
(13, 5, 4, '100', '2', '200', NULL, NULL, '2024-10-22 05:44:39', '2024-10-22 05:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_statuses`
--

CREATE TABLE `purchase_requisition_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_requisition_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requisition_statuses`
--

INSERT INTO `purchase_requisition_statuses` (`id`, `purchase_requisition_id`, `status`, `date`, `approved_by`, `department_id`, `reason`, `created_at`, `updated_at`) VALUES
(1, '1', 'Verified', '2024-07-29 07:32:12', '1', NULL, NULL, '2024-07-29 07:32:12', '2024-07-29 07:32:12'),
(2, '1', 'Approved', '2024-07-29 07:32:20', '1', NULL, NULL, '2024-07-29 07:32:20', '2024-07-29 07:32:20'),
(3, '2', 'Verified', '2024-08-07 04:07:59', '1', NULL, NULL, '2024-08-07 04:07:59', '2024-08-07 04:07:59'),
(4, '2', 'Approved', '2024-08-07 04:08:12', '1', NULL, NULL, '2024-08-07 04:08:12', '2024-08-07 04:08:12'),
(5, '4', 'Verified', '2024-10-22 10:44:53', '1', '1', NULL, '2024-10-22 05:44:53', '2024-10-22 05:44:53');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_id` bigint(20) UNSIGNED DEFAULT NULL,
  `grd_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `for_office` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `checked_by_time` varchar(255) DEFAULT NULL,
  `received_by_time` varchar(255) DEFAULT NULL,
  `checked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_returns`
--

INSERT INTO `purchase_returns` (`id`, `po_id`, `grd_no`, `date`, `supplier_id`, `for_office`, `attachment`, `qty`, `status`, `remarks`, `checked_by_time`, `received_by_time`, `checked_by`, `received_by`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, '11/1/24', '2024-08-07', 3, 'Return For Credit', NULL, '100', 'completed', 'bbb', '07-08-2024 13:25:00', '07-08-2024 13:25:51', 1, 1, 1, NULL, '2024-08-07 05:22:49', '2024-08-07 05:25:51'),
(2, 2, 'GRD/1/24', '2024-08-19', 3, 'Return For Replacement', NULL, '45', 'completed', 'k', '19-08-2024 14:10:16', '19-08-2024 14:11:30', 1, 1, 1, NULL, '2024-08-19 06:08:54', '2024-08-19 06:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_locations`
--

CREATE TABLE `purchase_return_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `available_qty` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_return_locations`
--

INSERT INTO `purchase_return_locations` (`id`, `purchase_return_id`, `product_id`, `area_id`, `rack_id`, `level_id`, `available_qty`, `lot_no`, `qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, '0', NULL, '0', NULL, '2024-08-07 05:22:49', '2024-08-07 05:22:49'),
(2, 1, 1, 1, 1, 1, '1000', '1', '100', NULL, '2024-08-07 05:22:49', '2024-08-07 05:22:49'),
(3, 2, NULL, NULL, NULL, NULL, '0', NULL, '0', NULL, '2024-08-19 06:08:54', '2024-08-19 06:08:54'),
(4, 2, 1, 1, 2, 2, '145', '2', '45', NULL, '2024-08-19 06:08:54', '2024-08-19 06:08:54');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_products`
--

CREATE TABLE `purchase_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `return_qty` varchar(255) DEFAULT NULL,
  `reject_qty` varchar(255) DEFAULT NULL,
  `receive_qty` varchar(255) DEFAULT NULL,
  `to_receive` varchar(255) DEFAULT NULL,
  `reason` longtext DEFAULT NULL,
  `reject_remarks` varchar(255) DEFAULT NULL,
  `receive_remarks` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_return_products`
--

INSERT INTO `purchase_return_products` (`id`, `purchase_return_id`, `product_id`, `qty`, `return_qty`, `reject_qty`, `receive_qty`, `to_receive`, `reason`, `reject_remarks`, `receive_remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '100', '100', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-07 05:22:49', '2024-08-07 05:22:49'),
(2, 1, 2, '200', '0', '0', '100', '0', NULL, NULL, NULL, NULL, '2024-08-07 05:22:49', '2024-08-07 05:25:51'),
(3, 1, 3, '300', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-07 05:22:49', '2024-08-07 05:22:49'),
(4, 1, 4, '400', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-07 05:22:49', '2024-08-07 05:22:49'),
(5, 2, 1, '100', '45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-19 06:08:54', '2024-08-19 06:08:54'),
(6, 2, 2, '200', '0', '0', '20', '0', NULL, NULL, NULL, NULL, '2024-08-19 06:08:54', '2024-08-19 06:11:30'),
(7, 2, 3, '300', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-19 06:08:54', '2024-08-19 06:08:54'),
(8, 2, 4, '400', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-19 06:08:54', '2024-08-19 06:08:54');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `verification_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `reason` longtext DEFAULT NULL,
  `term_conditions` longtext DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `ref_no`, `date`, `cc`, `department`, `verification_by`, `status`, `reason`, `term_conditions`, `customer_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1/1/24', '2024-07-29', 'Wasay', 'CCIA', '1', 'verified', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 1, NULL, '2024-07-29 06:24:10', '2024-07-29 06:34:31'),
(2, '1/2/24', '2024-07-29', 'sanya', 'head of production', '1', 'verified', NULL, '1. Test\r\n2. Test\r\n3. Test', 3, 1, NULL, '2024-07-29 07:50:31', '2024-07-29 07:50:43'),
(3, '1/3/24', '2024-07-30', 'Wasay', 'Jujutsu High', '1', 'verified', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 1, NULL, '2024-07-30 01:30:49', '2024-07-30 01:30:59'),
(4, 'Quotation/4/24', '2024-07-30', 'Mr.Ali', 'Sales', '1', 'verified', NULL, '1. Valid for xx period\r\n2. Test', 4, 1, NULL, '2024-07-30 06:44:58', '2024-07-30 06:45:40'),
(5, 'Quotation/5/24', '2024-08-07', 'Wasay', 'Prod.', '1', 'verified', NULL, 'wasay', 3, 1, NULL, '2024-08-07 03:42:17', '2024-08-09 04:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_details`
--

CREATE TABLE `quotation_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotation_details`
--

INSERT INTO `quotation_details` (`id`, `quotation_id`, `product_id`, `remarks`, `price`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '455', '2024-07-29 06:25:16', '2024-07-29 06:24:10', '2024-07-29 06:25:16'),
(2, 1, 2, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '1001', '2024-07-29 06:25:16', '2024-07-29 06:24:10', '2024-07-29 06:25:16'),
(3, 1, 3, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2500', '2024-07-29 06:25:16', '2024-07-29 06:24:10', '2024-07-29 06:25:16'),
(4, 1, 4, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '3987', '2024-07-29 06:25:16', '2024-07-29 06:24:10', '2024-07-29 06:25:16'),
(5, 1, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '455', NULL, '2024-07-29 06:25:16', '2024-07-29 06:25:16'),
(6, 1, 2, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '1000', NULL, '2024-07-29 06:25:16', '2024-07-29 06:25:16'),
(7, 1, 3, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2500', NULL, '2024-07-29 06:25:16', '2024-07-29 06:25:16'),
(8, 1, 4, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '3987', NULL, '2024-07-29 06:25:16', '2024-07-29 06:25:16'),
(9, 2, 5, 'testing', '1000', NULL, '2024-07-29 07:50:31', '2024-07-29 07:50:31'),
(10, 2, 6, 'testing', '1000', NULL, '2024-07-29 07:50:31', '2024-07-29 07:50:31'),
(11, 2, 7, 'testing', '1000', NULL, '2024-07-29 07:50:31', '2024-07-29 07:50:31'),
(12, 3, 1, 'hello world', '6000', NULL, '2024-07-30 01:30:49', '2024-07-30 01:30:49'),
(13, 4, 5, 'Added REMARKS', '100', NULL, '2024-07-30 06:44:58', '2024-07-30 06:44:58'),
(14, 5, 1, 'good', '6000', NULL, '2024-08-07 03:42:17', '2024-08-07 03:42:17'),
(15, 5, 2, 'good', '1001', NULL, '2024-08-07 03:42:17', '2024-08-07 03:42:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2024-07-27 09:35:29', '2024-07-27 09:35:29'),
(2, 'QC', 'web', '2024-07-27 09:46:34', '2024-07-27 09:46:34'),
(3, 'Production', 'web', '2024-07-30 03:25:38', '2024-07-30 03:25:38');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(26, 3),
(27, 1),
(27, 2),
(27, 3),
(28, 1),
(28, 2),
(28, 3),
(29, 1),
(29, 2),
(29, 3),
(30, 1),
(30, 2),
(30, 3),
(31, 1),
(31, 2),
(31, 3),
(32, 1),
(32, 2),
(32, 3),
(33, 1),
(33, 2),
(33, 3),
(34, 1),
(34, 2),
(35, 1),
(35, 2),
(36, 1),
(36, 2),
(37, 1),
(37, 2),
(38, 1),
(38, 2),
(39, 1),
(39, 2),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(42, 2),
(43, 1),
(43, 2),
(44, 1),
(44, 2),
(45, 1),
(45, 2),
(46, 1),
(46, 2),
(47, 1),
(47, 2),
(48, 1),
(48, 2),
(49, 1),
(49, 2),
(50, 1),
(50, 2),
(51, 1),
(51, 2),
(52, 1),
(52, 2),
(53, 1),
(53, 2),
(54, 1),
(54, 2),
(55, 1),
(55, 2),
(56, 1),
(56, 2),
(57, 1),
(57, 2),
(58, 1),
(58, 2),
(59, 1),
(59, 2),
(60, 1),
(60, 2),
(61, 1),
(61, 2),
(62, 1),
(62, 2),
(63, 1),
(63, 2),
(64, 1),
(64, 2),
(65, 1),
(65, 2),
(66, 1),
(66, 2),
(67, 1),
(67, 2),
(68, 1),
(68, 2),
(69, 1),
(69, 2),
(70, 1),
(70, 2),
(71, 1),
(71, 2),
(72, 1),
(72, 2),
(73, 1),
(73, 2),
(74, 1),
(74, 2),
(75, 1),
(75, 2),
(76, 1),
(76, 2),
(77, 1),
(77, 2),
(78, 1),
(78, 2),
(79, 1),
(79, 2),
(80, 1),
(80, 2),
(81, 1),
(81, 2),
(82, 1),
(82, 2),
(83, 1),
(83, 2),
(84, 1),
(84, 2),
(85, 1),
(85, 2),
(86, 1),
(86, 2),
(87, 1),
(87, 2),
(88, 1),
(88, 2),
(89, 1),
(89, 2),
(90, 1),
(90, 2),
(91, 1),
(91, 2),
(92, 1),
(92, 2),
(93, 1),
(93, 2),
(94, 1),
(94, 2),
(95, 1),
(95, 2),
(96, 1),
(96, 2),
(97, 1),
(97, 2),
(98, 1),
(98, 2),
(99, 1),
(99, 2),
(100, 1),
(100, 2),
(101, 1),
(101, 2),
(102, 1),
(102, 2),
(103, 1),
(103, 2),
(104, 1),
(104, 2),
(105, 1),
(105, 2),
(106, 1),
(106, 2),
(107, 1),
(107, 2),
(108, 1),
(108, 2),
(109, 1),
(109, 2),
(110, 1),
(110, 2),
(111, 1),
(111, 2),
(112, 1),
(112, 2),
(113, 1),
(113, 2),
(114, 1),
(114, 2),
(115, 1),
(115, 2),
(116, 1),
(116, 2),
(117, 1),
(117, 2),
(118, 1),
(118, 2),
(119, 1),
(119, 2),
(120, 1),
(120, 2),
(121, 1),
(121, 2),
(122, 1),
(122, 2),
(123, 1),
(123, 2),
(124, 1),
(124, 2),
(125, 1),
(125, 2),
(126, 1),
(126, 2),
(127, 1),
(127, 2),
(128, 1),
(128, 2),
(129, 1),
(129, 2),
(130, 1),
(130, 2),
(131, 1),
(131, 2),
(132, 1),
(132, 2),
(133, 1),
(133, 2),
(134, 1),
(134, 2),
(135, 1),
(135, 2),
(136, 1),
(136, 2),
(137, 1),
(137, 2),
(138, 1),
(138, 2),
(139, 1),
(139, 2),
(140, 1),
(140, 2),
(141, 1),
(141, 2),
(142, 1),
(142, 2),
(143, 1),
(143, 2),
(144, 1),
(144, 2),
(145, 1),
(145, 2),
(146, 1),
(146, 2),
(147, 1),
(147, 2),
(148, 1),
(148, 2),
(149, 1),
(149, 2),
(150, 1),
(150, 2),
(151, 1),
(151, 2),
(152, 1),
(152, 2),
(153, 1),
(153, 2),
(154, 1),
(154, 2),
(155, 1),
(155, 2),
(156, 1),
(156, 2),
(157, 1),
(157, 2),
(158, 1),
(158, 2),
(159, 1),
(159, 2),
(160, 1),
(160, 2),
(161, 1),
(161, 2),
(162, 1),
(162, 2),
(163, 1),
(163, 2),
(164, 1),
(164, 2),
(165, 1),
(165, 2),
(166, 1),
(166, 2),
(167, 1),
(167, 2),
(168, 1),
(168, 2),
(169, 1),
(169, 2),
(170, 1),
(170, 2),
(171, 1),
(171, 2),
(172, 1),
(172, 2),
(173, 1),
(173, 2),
(174, 1),
(174, 2),
(175, 1),
(175, 2),
(176, 1),
(176, 2),
(177, 1),
(177, 2),
(178, 1),
(178, 2),
(179, 1),
(179, 2),
(180, 1),
(180, 2),
(181, 1),
(181, 2),
(182, 1),
(182, 2),
(183, 1),
(183, 2),
(184, 1),
(184, 2),
(185, 1),
(185, 2),
(186, 1),
(186, 2),
(187, 1),
(187, 2),
(188, 1),
(188, 2),
(189, 1),
(189, 2),
(190, 1),
(190, 2),
(191, 1),
(191, 2),
(192, 1),
(192, 2),
(193, 1),
(193, 2),
(194, 1),
(194, 2),
(195, 1),
(195, 2),
(196, 1),
(196, 2),
(197, 1),
(197, 2),
(198, 1),
(198, 2),
(199, 1),
(199, 2),
(200, 1),
(200, 2),
(201, 1),
(201, 2),
(202, 1),
(202, 2),
(203, 1),
(203, 2),
(204, 1),
(204, 2),
(205, 1),
(205, 2),
(206, 1),
(206, 2),
(207, 1),
(207, 2),
(208, 1),
(208, 2),
(209, 1),
(209, 2),
(210, 1),
(210, 2),
(211, 1),
(211, 2),
(212, 1),
(212, 2),
(213, 1),
(213, 2),
(214, 1),
(214, 2),
(215, 1),
(215, 2),
(216, 1),
(216, 2),
(217, 1),
(217, 2),
(218, 1),
(218, 2),
(219, 1),
(219, 2),
(220, 1),
(220, 2),
(221, 1),
(221, 2),
(222, 1),
(222, 2),
(223, 1),
(223, 2),
(224, 1),
(224, 2),
(225, 1),
(225, 2),
(226, 1),
(226, 2),
(227, 1),
(227, 2),
(228, 1),
(228, 2),
(229, 1),
(229, 2),
(229, 3),
(230, 1),
(230, 2),
(230, 3),
(231, 1),
(231, 2),
(231, 3),
(232, 1),
(232, 2),
(232, 3),
(233, 1),
(233, 2),
(233, 3),
(234, 1),
(234, 2),
(234, 3),
(235, 1),
(235, 2),
(235, 3),
(236, 1),
(236, 2),
(237, 1),
(237, 2),
(238, 1),
(238, 2),
(239, 1),
(239, 2),
(240, 1),
(240, 2),
(241, 1),
(241, 2),
(242, 1),
(242, 2),
(243, 1),
(243, 2),
(244, 1),
(244, 2),
(245, 1),
(245, 2),
(246, 1),
(246, 2),
(247, 1),
(247, 2),
(248, 1),
(248, 2),
(249, 1),
(249, 2),
(250, 1),
(250, 2),
(251, 1),
(251, 2),
(252, 1),
(252, 2),
(253, 1),
(253, 2),
(254, 1),
(255, 1),
(256, 1),
(257, 1),
(258, 1),
(260, 1),
(261, 1),
(262, 1),
(263, 1),
(264, 1),
(265, 1),
(266, 1),
(267, 1),
(268, 1),
(269, 1),
(270, 1),
(271, 1),
(272, 1),
(273, 1),
(274, 1),
(275, 1),
(276, 1),
(277, 1),
(278, 1),
(279, 1),
(280, 1),
(281, 1),
(282, 1),
(283, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_returns`
--

CREATE TABLE `sales_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_returns`
--

INSERT INTO `sales_returns` (`id`, `ref_no`, `date`, `qty`, `customer_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '10/1/24', '2024-07-29', '1000', 1, 1, NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(2, '10/2/24', '2024-08-07', '1258', 1, 1, NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(3, 'SR/3/24', '2024-08-09', '46', 1, 1, NULL, '2024-08-09 05:04:26', '2024-08-09 05:04:26'),
(4, 'SR/4/24', '2024-08-19', '300', 2, 1, NULL, '2024-08-19 06:04:54', '2024-08-19 06:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_details`
--

CREATE TABLE `sales_return_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `reason` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_return_details`
--

INSERT INTO `sales_return_details` (`id`, `sales_return_id`, `product_id`, `qty`, `reason`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '100', 'yes', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(2, 1, 2, '200', 'yes', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(3, 1, 3, '300', 'yes', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(4, 1, 4, '400', 'yes', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(5, 2, 1, '1252', NULL, NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(6, 2, 2, '6', NULL, NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(7, 3, 3, '46', 'yes', NULL, '2024-08-09 05:04:26', '2024-08-09 05:04:26'),
(8, 4, 6, '300', 'vx', NULL, '2024-08-19 06:04:54', '2024-08-19 06:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_locations`
--

CREATE TABLE `sales_return_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_return_locations`
--

INSERT INTO `sales_return_locations` (`id`, `sales_return_id`, `product_id`, `area_id`, `rack_id`, `level_id`, `lot_no`, `qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, '1', '100', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(2, 1, 2, 1, 1, 1, '2', '200', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(3, 1, 3, 1, 2, 2, '3', '300', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(4, 1, 4, 1, 1, 1, '4', '400', NULL, '2024-07-29 07:12:33', '2024-07-29 07:12:33'),
(5, 2, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(6, 2, 1, 1, 1, 1, '1', '1252', NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(7, 2, 2, 1, 1, 1, '2', '6', NULL, '2024-08-07 05:14:43', '2024-08-07 05:14:43'),
(8, 3, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2024-08-09 05:04:26', '2024-08-09 05:04:26'),
(9, 3, 3, 1, 1, 1, '4', '46', NULL, '2024-08-09 05:04:26', '2024-08-09 05:04:26'),
(10, 4, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2024-08-19 06:04:54', '2024-08-19 06:04:54'),
(11, 4, 6, 2, 3, 2, '1', '300', NULL, '2024-08-19 06:04:54', '2024-08-19 06:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `sale_prices`
--

CREATE TABLE `sale_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'submitted' COMMENT 'submitted,verified,declined',
  `reason` varchar(255) DEFAULT NULL,
  `verification_by` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_prices`
--

INSERT INTO `sale_prices` (`id`, `product_id`, `price`, `date`, `status`, `reason`, `verification_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '2000', '2024-07-29', 'submitted', NULL, NULL, '2024-07-29 06:16:15', '2024-07-29 05:59:27', '2024-07-29 06:16:15'),
(2, 1, '455', '2024-07-29', 'verified', NULL, '1', '2024-07-30 01:29:54', '2024-07-29 06:18:43', '2024-07-30 01:29:54'),
(3, 2, '1001', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 06:21:03', '2024-07-29 06:21:11'),
(4, 3, '2500', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 06:21:31', '2024-07-29 06:22:24'),
(5, 4, '3987', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 06:22:12', '2024-07-29 06:22:32'),
(6, 5, '100', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 07:55:22', '2024-07-29 07:56:56'),
(7, 6, '100', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 07:55:36', '2024-07-29 07:56:51'),
(8, 7, '100', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-29 07:56:09', '2024-07-29 07:57:01'),
(9, 1, '6000', '2024-07-29', 'verified', NULL, '1', NULL, '2024-07-30 01:29:01', '2024-07-30 01:29:16'),
(10, 8, '120', '2024-07-30', 'verified', 'test', '1', NULL, '2024-07-30 06:35:16', '2024-07-30 06:37:47'),
(11, 8, '160', '2024-08-10', 'verified', NULL, '1', NULL, '2024-07-30 06:39:56', '2024-10-22 04:49:13'),
(12, 8, '200', '2024-09-01', 'verified', NULL, '1', NULL, '2024-07-30 07:04:32', '2024-10-22 05:00:14'),
(13, 10, '200', '2024-07-30', 'verified', NULL, '1', NULL, '2024-07-30 13:43:45', '2024-10-22 05:45:44'),
(14, 11, '100', '2024-07-30', 'submitted', NULL, NULL, NULL, '2024-07-30 13:44:03', '2024-07-30 13:44:03'),
(15, 12, '50', '2024-07-30', 'submitted', NULL, NULL, NULL, '2024-07-30 13:44:17', '2024-07-30 13:44:17'),
(16, 13, '150', '2024-07-30', 'submitted', NULL, NULL, NULL, '2024-07-30 14:34:13', '2024-07-30 14:34:13'),
(17, 14, '500', '2024-07-30', 'submitted', NULL, NULL, NULL, '2024-07-30 15:00:41', '2024-07-30 15:00:41'),
(18, 15, '150', '2024-07-30', 'submitted', NULL, NULL, NULL, '2024-07-30 15:01:02', '2024-07-30 15:01:02'),
(19, 16, '100', '2024-07-30', 'submitted', NULL, NULL, NULL, '2024-07-30 15:01:20', '2024-07-30 15:01:20'),
(20, 4, '650', '2024-08-07', 'submitted', NULL, NULL, NULL, '2024-08-07 03:40:06', '2024-08-07 03:40:06'),
(21, 5, '22', '13-10-2024', 'submitted', NULL, NULL, NULL, '2024-10-22 04:39:04', '2024-10-22 04:39:04'),
(22, 3, '22', '21-10-2024', 'submitted', NULL, NULL, NULL, '2024-10-22 05:45:35', '2024-10-22 05:45:35'),
(23, 1, '22', '20-10-2024', 'verified', NULL, '1', NULL, '2024-10-22 23:02:20', '2024-10-22 23:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `spec_breaks`
--

CREATE TABLE `spec_breaks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `normal_hour` varchar(255) DEFAULT NULL,
  `ot_hour` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spec_breaks`
--

INSERT INTO `spec_breaks` (`id`, `normal_hour`, `ot_hour`, `created_at`, `updated_at`) VALUES
(1, '1', '1.5', '2024-07-27 09:35:30', '2024-07-30 04:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `sst_percentages`
--

CREATE TABLE `sst_percentages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sst_percentage` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sst_percentages`
--

INSERT INTO `sst_percentages` (`id`, `sst_percentage`, `created_at`, `updated_at`) VALUES
(1, '10', '2024-07-27 09:35:30', '2024-07-30 06:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `stock_relocations`
--

CREATE TABLE `stock_relocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `previous_area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `previous_rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `previous_level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `new_area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `new_rack_id` bigint(20) UNSIGNED DEFAULT NULL,
  `new_level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_relocations`
--

INSERT INTO `stock_relocations` (`id`, `ref_no`, `date`, `description`, `previous_area_id`, `previous_rack_id`, `previous_level_id`, `new_area_id`, `new_rack_id`, `new_level_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '9/1/24', '07-08-2024', NULL, 1, 1, 1, 1, 2, 2, 1, NULL, '2024-08-07 05:17:31', '2024-08-07 05:17:31'),
(2, 'SR/2/24', '09-08-2024', NULL, 1, 1, 1, 1, 2, 2, 1, NULL, '2024-08-09 05:01:46', '2024-08-09 05:01:46'),
(3, 'SR/3/24', '19-08-2024', 'nb', 1, 2, 2, 3, 4, 4, 1, NULL, '2024-08-19 06:01:52', '2024-08-19 06:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `stock_relocation_details`
--

CREATE TABLE `stock_relocation_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_relocation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `available_qty` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_relocation_details`
--

INSERT INTO `stock_relocation_details` (`id`, `stock_relocation_id`, `product_id`, `available_qty`, `qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1340', '340', NULL, '2024-08-07 05:17:31', '2024-08-07 05:17:31'),
(2, 2, 4, '595', '200', NULL, '2024-08-09 05:01:46', '2024-08-09 05:01:46'),
(3, 3, 1, '345', '200', NULL, '2024-08-19 06:01:52', '2024-08-19 06:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `contact_person_name` varchar(255) DEFAULT NULL,
  `contact_person_telephone` varchar(255) DEFAULT NULL,
  `contact_person_department` varchar(255) DEFAULT NULL,
  `contact_person_mobile` varchar(255) DEFAULT NULL,
  `contact_person_fax` varchar(255) DEFAULT NULL,
  `contact_person_email` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `contact`, `group`, `contact_person_name`, `contact_person_telephone`, `contact_person_department`, `contact_person_mobile`, `contact_person_fax`, `contact_person_email`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Brody Rose', 'Ut asperiores accusa', '12', 'Direct', 'Barclay Rowland', '3', 'Expedita dolores dig', '77', '+1 (224) 846-7116', 'befixeza@mailinator.com', '2024-07-31 07:26:52', '2024-07-29 03:22:46', '2024-07-31 07:26:52'),
(2, 'Tad Bartlett', 'Quidem est aliqua', '84', 'InDirect', 'Cain Lawson', '66', 'Quasi sint recusanda', '12', '+1 (205) 272-4472', 'rudybori@mailinator.com', '2024-07-31 07:26:54', '2024-07-29 03:23:04', '2024-07-31 07:26:54'),
(3, 'Supp A', 'Rawang Selangor', '0192222123', 'Direct', 'Ali Marwan', '0134567878', 'Sales', '0187631234', NULL, 'ali@gmail.com', NULL, '2024-07-30 03:32:44', '2024-07-30 03:32:44'),
(4, 'Supp B', 'AAAA, Rawang Selangor', '0176541234', 'Direct', 'Ali Marwan', '0176541239', 'Sales', '0176541237', NULL, 'alimarwan@gmail.com', NULL, '2024-07-30 14:55:29', '2024-07-30 14:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_rankings`
--

CREATE TABLE `supplier_rankings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `ranking` varchar(255) NOT NULL,
  `ranking_date` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_rankings`
--

INSERT INTO `supplier_rankings` (`id`, `supplier_id`, `created_by`, `date`, `ranking`, `ranking_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '2024-07-29', 'A', '2024-07', NULL, '2024-07-29 07:42:09', '2024-07-29 07:42:09'),
(2, 2, '1', '2024-07-29', 'B', '2024-07', '2024-07-29 07:43:26', '2024-07-29 07:42:20', '2024-07-29 07:43:26'),
(3, 2, '1', '2024-07-29', 'B', '2024-07', '2024-07-29 07:43:22', '2024-07-29 07:42:23', '2024-07-29 07:43:22'),
(4, 2, '1', '2024-07-29', 'B', '2024-07', NULL, '2024-07-29 07:43:41', '2024-07-29 07:43:41'),
(5, 3, '1', '2024-08-07', 'A', '2024-08', NULL, '2024-08-07 04:14:36', '2024-08-07 04:14:36'),
(6, 3, '1', '22-10-2024', 'B', '2024-09', NULL, '2024-10-22 04:30:09', '2024-10-22 04:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `secondary_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('debit','credit') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reconciled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `account_id`, `secondary_account_id`, `customer_id`, `supplier_id`, `product_id`, `type`, `amount`, `description`, `created_at`, `updated_at`, `reconciled`) VALUES
(1, 1, NULL, NULL, NULL, NULL, 'debit', 100.00, 'Enter a first amount', '2024-10-21 19:00:00', '2024-10-22 23:30:07', 0),
(2, 2, NULL, NULL, NULL, NULL, 'credit', 100.00, 'Counter entry for Cash to Owner\'s Capital', '2024-10-21 19:00:00', '2024-10-22 23:30:07', 0),
(3, 2, NULL, NULL, NULL, NULL, 'debit', 50.00, 'second entry', '2024-10-05 19:00:00', '2024-10-22 23:41:22', 0),
(4, 1, NULL, NULL, NULL, NULL, 'credit', 50.00, 'Counter entry for Owner\'s Capital to Cash', '2024-10-05 19:00:00', '2024-10-22 23:41:22', 0),
(5, 4, NULL, NULL, NULL, NULL, 'credit', 659989.00, 'Revenue for Invoice #1', '2024-10-22 23:49:59', '2024-10-22 23:49:59', 0),
(6, 1, NULL, NULL, NULL, NULL, 'debit', 200.00, 'Cash payment for Invoice #1', '2024-10-22 23:49:59', '2024-10-22 23:49:59', 0),
(7, 5, NULL, NULL, NULL, NULL, 'credit', 200.00, 'Decrease inventory for Invoice #1', '2024-10-22 23:49:59', '2024-10-22 23:49:59', 0),
(8, 1, NULL, NULL, NULL, NULL, 'credit', 100.00, 'Payment for PO #2', '2024-10-22 23:58:52', '2024-10-22 23:58:52', 0),
(9, 5, NULL, NULL, NULL, NULL, 'debit', 100.00, 'Increase inventory for PO #2', '2024-10-22 23:58:52', '2024-10-22 23:58:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transfer_requests`
--

CREATE TABLE `transfer_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `request_date` varchar(255) DEFAULT NULL,
  `request_from` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `mrf_no` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `machine` varchar(255) DEFAULT NULL,
  `request_to` varchar(255) DEFAULT NULL,
  `issue_by` varchar(255) DEFAULT NULL,
  `issue_date` varchar(255) DEFAULT NULL,
  `issue_remarks` longtext DEFAULT NULL,
  `issue_time` varchar(255) DEFAULT NULL,
  `rcv_by` varchar(255) DEFAULT NULL,
  `rcv_date` varchar(255) DEFAULT NULL,
  `rcv_remarks` longtext DEFAULT NULL,
  `rcv_time` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Requested' COMMENT 'Requested,Issued,Received',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_requests`
--

INSERT INTO `transfer_requests` (`id`, `ref_no`, `request_date`, `request_from`, `description`, `mrf_no`, `shift`, `machine`, `request_to`, `issue_by`, `issue_date`, `issue_remarks`, `issue_time`, `rcv_by`, `rcv_date`, `rcv_remarks`, `rcv_time`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '7/1/24', '2024-07-30', '2', NULL, '', '', '', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', '2024-07-31 00:09:53', '2024-07-30 10:18:06', '2024-07-31 00:09:53'),
(2, '7/2/24', '2024-07-31', '3', NULL, '', '', '', '4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', '2024-08-01 03:29:39', '2024-07-31 00:09:21', '2024-08-01 03:29:39'),
(3, '7/3/24', '2024-08-01', '3', NULL, '1', 'AM', '1', '4', '1', '2024-08-01', NULL, '03:27', '1', NULL, NULL, '03:28', 'Received', NULL, '2024-08-01 03:27:31', '2024-08-01 03:29:13'),
(4, '7/4/24', '2024-08-01', '3', NULL, '', '', '', '5', '1', '2024-08-01', NULL, '06:15', NULL, NULL, NULL, NULL, 'Issued', NULL, '2024-08-01 06:13:14', '2024-08-01 06:15:40'),
(5, '7/5/24', '2024-08-01', '3', NULL, '3', 'AM', '1', '5', '1', '2024-08-01', NULL, '06:16', NULL, NULL, NULL, NULL, 'Issued', NULL, '2024-08-01 06:16:08', '2024-08-01 06:16:55'),
(6, '7/6/24', '2024-08-01', '3', NULL, '6', 'AM', '1', '5', '1', '2024-08-01', NULL, '06:18', '1', NULL, NULL, '07:22', 'Received', NULL, '2024-08-01 06:18:19', '2024-08-01 07:23:22'),
(7, '7/7/24', '2024-08-01', '3', 'extra', '9', 'AM', '1', '4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-01 07:22:04', '2024-08-01 07:22:04'),
(8, '7/8/24', '2024-08-01', '3', 'FG', '', '', '', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-01 07:24:32', '2024-08-01 07:24:32'),
(9, '7/9/24', '2024-08-07', '1', NULL, '1', 'AM', '1', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-07 05:10:40', '2024-08-07 05:10:40'),
(10, 'TR/10/24', '2024-08-09', '2', NULL, '1', 'AM', '1', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-09 04:58:52', '2024-08-09 04:58:52'),
(11, 'TR/11/24', '2024-08-19', '1', 'yes', '1', 'AM', '1', '2', '1', '2024-08-19', NULL, '05:50', NULL, NULL, NULL, NULL, 'Requested', NULL, '2024-08-19 05:50:29', '2024-08-19 05:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_request_details`
--

CREATE TABLE `transfer_request_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `request_qty` varchar(255) DEFAULT NULL,
  `request_remarks` longtext DEFAULT NULL,
  `issue_qty` varchar(255) DEFAULT NULL,
  `issue_remarks` longtext DEFAULT NULL,
  `rcv_qty` varchar(255) DEFAULT NULL,
  `rcv_remarks` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_request_details`
--

INSERT INTO `transfer_request_details` (`id`, `transfer_request_id`, `product_id`, `request_qty`, `request_remarks`, `issue_qty`, `issue_remarks`, `rcv_qty`, `rcv_remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 3, '1', '5', NULL, '5', NULL, '5', NULL, NULL, '2024-08-01 03:27:31', '2024-08-01 03:29:13'),
(4, 4, '4', '5', NULL, '5', NULL, NULL, NULL, NULL, '2024-08-01 06:13:14', '2024-08-01 06:15:40'),
(5, 5, '2', '5', NULL, '5', NULL, NULL, NULL, NULL, '2024-08-01 06:16:08', '2024-08-01 06:16:55'),
(6, 6, '3', '10', NULL, '10', NULL, '10', NULL, NULL, '2024-08-01 06:18:19', '2024-08-01 07:23:22'),
(7, 7, '1', '5', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-01 07:22:04', '2024-08-01 07:22:04'),
(8, 8, '1', '20', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-01 07:24:32', '2024-08-01 07:24:32'),
(9, 9, '1', '12', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-07 05:10:40', '2024-08-07 05:10:40'),
(10, 9, '2', '21', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-07 05:10:40', '2024-08-07 05:10:40'),
(11, 10, '3', '100', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-09 04:58:52', '2024-08-09 04:58:52'),
(12, 10, '4', '200', NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-09 04:58:52', '2024-08-09 04:58:52'),
(13, 11, '1', '100', NULL, '29', NULL, NULL, NULL, NULL, '2024-08-19 05:50:29', '2024-08-19 05:51:18'),
(14, 11, '2', '200', NULL, '0', NULL, NULL, NULL, NULL, '2024-08-19 05:50:29', '2024-08-19 05:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_request_issues`
--

CREATE TABLE `transfer_request_issues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `rack` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `tr_detail_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_request_issues`
--

INSERT INTO `transfer_request_issues` (`id`, `area`, `rack`, `level`, `lot_no`, `qty`, `tr_detail_id`, `product_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '1', '1', '1', '5', '3', '1', NULL, '2024-08-01 03:28:40', '2024-08-01 03:28:40'),
(2, '1', '1', '1', '4', '5', '4', '4', NULL, '2024-08-01 06:15:40', '2024-08-01 06:15:40'),
(3, '1', '1', '1', '2', '5', '5', '2', NULL, '2024-08-01 06:16:55', '2024-08-01 06:16:55'),
(4, '1', '2', '2', '3', '10', '6', '3', NULL, '2024-08-01 06:20:35', '2024-08-01 06:20:35'),
(5, '1', '1', '1', '1', '29', '13', '1', NULL, '2024-08-19 05:51:18', '2024-08-19 05:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_request_receives`
--

CREATE TABLE `transfer_request_receives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `rack` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `tr_detail_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_request_receives`
--

INSERT INTO `transfer_request_receives` (`id`, `area`, `rack`, `level`, `lot_no`, `qty`, `tr_detail_id`, `product_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '1', '1', '1', '5', '3', '1', NULL, '2024-08-01 03:29:13', '2024-08-01 03:29:13'),
(2, '1', '1', '1', '1', '10', '6', '3', NULL, '2024-08-01 07:23:22', '2024-08-01 07:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `type_of_products`
--

CREATE TABLE `type_of_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type_of_products`
--

INSERT INTO `type_of_products` (`id`, `type`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Wheels', 'Odit sint sint fugi', NULL, '2024-07-29 03:31:01', '2024-07-29 03:31:01'),
(2, 'Chain', NULL, NULL, '2024-07-29 03:31:16', '2024-07-29 03:31:16'),
(3, 'oil', 'Sit soluta optio u', NULL, '2024-07-29 03:32:29', '2024-07-29 03:32:29'),
(4, 'Finish Good', 'FG', NULL, '2024-07-29 07:27:23', '2024-07-29 07:27:23'),
(5, 'Childpart/Component', 'Childpart', NULL, '2024-07-29 07:27:49', '2024-07-29 07:27:49'),
(6, 'Raw Material', 'Raw', NULL, '2024-07-29 07:28:19', '2024-07-29 07:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `type_of_rejections`
--

CREATE TABLE `type_of_rejections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type_of_rejections`
--

INSERT INTO `type_of_rejections` (`id`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Sit aliquip ea aliqu', '2024-07-31 07:24:14', '2024-07-29 03:33:51', '2024-07-31 07:24:14'),
(2, 'Magni quisquam dolor', '2024-07-31 07:24:16', '2024-07-29 03:33:57', '2024-07-31 07:24:16'),
(3, 'Eos perspiciatis au', '2024-07-31 07:24:19', '2024-07-29 03:34:02', '2024-07-31 07:24:19'),
(4, 'Stain', NULL, '2024-07-30 03:46:14', '2024-07-30 03:46:14'),
(5, 'Black Dot', NULL, '2024-07-30 03:46:22', '2024-07-30 03:46:22'),
(6, 'Damage', NULL, '2024-07-30 03:46:29', '2024-07-30 03:46:29');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Abigail Odonnell', 'Officia vel quo dist', '2024-07-31 07:26:27', '2024-07-29 03:26:06', '2024-07-31 07:26:27'),
(2, 'Yoshi Gardner', 'Veritatis nesciunt', '2024-07-31 07:26:28', '2024-07-29 03:27:02', '2024-07-31 07:26:28'),
(3, 'kg', 'kg', NULL, '2024-07-29 07:46:28', '2024-07-29 07:46:28'),
(4, 'pcs', 'pieces', NULL, '2024-07-29 07:46:38', '2024-07-29 07:46:38'),
(5, 'g', 'unit001', NULL, '2024-07-30 03:36:24', '2024-07-30 03:36:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `role_ids` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `designation_id` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `code`, `user_name`, `role_ids`, `email`, `phone`, `is_active`, `department_id`, `designation_id`, `email_verified_at`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Mr admin', NULL, 'admin', '[\"1\"]', 'admin@gmail.com', NULL, 'yes', '1', '1', NULL, '$2y$12$xwAVFtG27oe.AKH8holvReJHxwZm.WbyokSFSy4GbMs0MytTxCSuG', NULL, NULL, '2024-07-27 09:35:30', '2024-10-23 03:09:44'),
(2, 'Wasay', NULL, 'Wasayj', '[\"2\"]', 'wasay@gmail.com', '54465535', 'yes', '1', '4', NULL, '$2y$12$cPB8szKH6HgdTvtVDWTdBuCDMLhI6DESmmihrs3Y4PDQfBhglRqtW', NULL, NULL, '2024-07-29 05:30:28', '2024-07-29 05:30:28'),
(10, 'rocky', '31930507', 'rock', '[\"2\"]', 'rocky@gmail.com', '3322332222', 'no', '2', '2', NULL, '$2y$12$kgR.TPdXWF4RZ6PHx1QdxOLM8gnS0WkmcQI1rK6wxKyDaeqHyY1Ae', NULL, NULL, '2024-10-22 01:26:06', '2024-10-22 01:26:06'),
(11, 'nigga', '29436252', 'nigg', '[\"2\"]', 'niggg@gmail.com', NULL, 'no', '3', '2', NULL, '$2y$12$4quos3P5Ix1NjbrTu2MfFuLx1b2q76tHkODAQuDMnZBHH44m.ukta', NULL, NULL, '2024-10-23 03:11:59', '2024-10-23 03:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_details`
--

CREATE TABLE `user_bank_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `account_no` varchar(255) DEFAULT NULL,
  `account_type` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `account_status` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_bank_details`
--

INSERT INTO `user_bank_details` (`id`, `user_id`, `bank`, `account_no`, `account_type`, `branch`, `account_status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 10, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-22 01:26:06', '2024-10-22 01:26:06'),
(3, 1, NULL, '0', NULL, NULL, NULL, NULL, '2024-10-23 03:09:44', '2024-10-23 03:09:44'),
(4, 11, NULL, '0', NULL, NULL, NULL, NULL, '2024-10-23 03:11:59', '2024-10-23 03:12:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_category_id_foreign` (`category_id`);

--
-- Indexes for table `account_categories`
--
ALTER TABLE `account_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amortizations`
--
ALTER TABLE `amortizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `amortizations_product_id_foreign` (`product_id`),
  ADD KEY `amortizations_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_levels`
--
ALTER TABLE `area_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_locations`
--
ALTER TABLE `area_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_locations_area_id_foreign` (`area_id`),
  ADD KEY `area_locations_rack_id_foreign` (`rack_id`),
  ADD KEY `area_locations_level_id_foreign` (`level_id`);

--
-- Indexes for table `area_racks`
--
ALTER TABLE `area_racks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boms`
--
ALTER TABLE `boms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boms_product_id_foreign` (`product_id`);

--
-- Indexes for table `bom_crushings`
--
ALTER TABLE `bom_crushings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_crushings_bom_id_foreign` (`bom_id`),
  ADD KEY `bom_crushings_product_id_foreign` (`product_id`);

--
-- Indexes for table `bom_processes`
--
ALTER TABLE `bom_processes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_processes_bom_id_foreign` (`bom_id`);

--
-- Indexes for table `bom_purchase_parts`
--
ALTER TABLE `bom_purchase_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_purchase_parts_bom_id_foreign` (`bom_id`),
  ADD KEY `bom_purchase_parts_product_id_foreign` (`product_id`);

--
-- Indexes for table `bom_sub_parts`
--
ALTER TABLE `bom_sub_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_sub_parts_bom_id_foreign` (`bom_id`),
  ADD KEY `bom_sub_parts_product_id_foreign` (`product_id`);

--
-- Indexes for table `bom_verifications`
--
ALTER TABLE `bom_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `call_for_assistances`
--
ALTER TABLE `call_for_assistances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carry_forwards`
--
ALTER TABLE `carry_forwards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carry_forwards_year_unique` (`year`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_production_child_parts`
--
ALTER TABLE `daily_production_child_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_child_parts_dpp_id_foreign` (`dpp_id`);

--
-- Indexes for table `daily_production_plannings`
--
ALTER TABLE `daily_production_plannings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_production_planning_details`
--
ALTER TABLE `daily_production_planning_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_planning_details_dpp_id_foreign` (`dpp_id`);

--
-- Indexes for table `daily_production_products`
--
ALTER TABLE `daily_production_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_products_dpp_id_foreign` (`dpp_id`);

--
-- Indexes for table `delivery_instructions`
--
ALTER TABLE `delivery_instructions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_instructions_order_id_foreign` (`order_id`),
  ADD KEY `delivery_instructions_created_by_foreign` (`created_by`);

--
-- Indexes for table `delivery_instruction_details`
--
ALTER TABLE `delivery_instruction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_instruction_details_di_id_foreign` (`di_id`),
  ADD KEY `delivery_instruction_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discrepancies`
--
ALTER TABLE `discrepancies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `external_statements`
--
ALTER TABLE `external_statements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `external_statements_account_id_foreign` (`account_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `family_child_users`
--
ALTER TABLE `family_child_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `family_child_users_family_id_foreign` (`family_id`);

--
-- Indexes for table `family_users`
--
ALTER TABLE `family_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `family_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `good_receivings`
--
ALTER TABLE `good_receivings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `good_receivings_po_id_foreign` (`po_id`),
  ADD KEY `good_receivings_pr_id_foreign` (`pr_id`),
  ADD KEY `good_receivings_created_by_foreign` (`created_by`);

--
-- Indexes for table `good_receiving_locations`
--
ALTER TABLE `good_receiving_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `good_receiving_locations_gr_id_foreign` (`gr_id`),
  ADD KEY `good_receiving_locations_product_id_foreign` (`product_id`),
  ADD KEY `good_receiving_locations_area_id_foreign` (`area_id`),
  ADD KEY `good_receiving_locations_rack_id_foreign` (`rack_id`),
  ADD KEY `good_receiving_locations_level_id_foreign` (`level_id`);

--
-- Indexes for table `good_receiving_products`
--
ALTER TABLE `good_receiving_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `good_receiving_products_gr_id_foreign` (`gr_id`),
  ADD KEY `good_receiving_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `good_receiving_qcs`
--
ALTER TABLE `good_receiving_qcs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `good_receiving_qcs_gr_id_foreign` (`gr_id`),
  ADD KEY `good_receiving_qcs_product_id_foreign` (`product_id`),
  ADD KEY `good_receiving_qcs_rt_id_foreign` (`rt_id`);

--
-- Indexes for table `initail_nos`
--
ALTER TABLE `initail_nos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_outgoing_id_foreign` (`outgoing_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_details_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `locations_area_id_foreign` (`area_id`),
  ADD KEY `locations_rack_id_foreign` (`rack_id`),
  ADD KEY `locations_level_id_foreign` (`level_id`),
  ADD KEY `locations_product_id_foreign` (`product_id`);

--
-- Indexes for table `lot_nos`
--
ALTER TABLE `lot_nos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `machines_tonnage_id_foreign` (`tonnage_id`);

--
-- Indexes for table `machine_apis`
--
ALTER TABLE `machine_apis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_counts`
--
ALTER TABLE `machine_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_downimes`
--
ALTER TABLE `machine_downimes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_preperations`
--
ALTER TABLE `machine_preperations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_tonnages`
--
ALTER TABLE `machine_tonnages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_plannings`
--
ALTER TABLE `material_plannings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_plannings_dppd_id_foreign` (`dppd_id`);

--
-- Indexes for table `material_planning_details`
--
ALTER TABLE `material_planning_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_planning_details_planning_id_foreign` (`planning_id`);

--
-- Indexes for table `material_requisitions`
--
ALTER TABLE `material_requisitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_requisition_details`
--
ALTER TABLE `material_requisition_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_requisition_details_material_requisition_id_foreign` (`material_requisition_id`);

--
-- Indexes for table `material_requisition_product_details`
--
ALTER TABLE `material_requisition_product_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_requisition_product_details_receives`
--
ALTER TABLE `material_requisition_product_details_receives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `more_users`
--
ALTER TABLE `more_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `more_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `notification_screens`
--
ALTER TABLE `notification_screens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_created_by_foreign` (`created_by`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `outgoings`
--
ALTER TABLE `outgoings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `outgoings_order_id_foreign` (`order_id`),
  ADD KEY `outgoings_sr_id_foreign` (`sr_id`),
  ADD KEY `outgoings_pr_id_foreign` (`pr_id`),
  ADD KEY `outgoings_created_by_foreign` (`created_by`);

--
-- Indexes for table `outgoing_details`
--
ALTER TABLE `outgoing_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `outgoing_details_outgoing_id_foreign` (`outgoing_id`),
  ADD KEY `outgoing_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `outgoing_locations`
--
ALTER TABLE `outgoing_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `outgoing_locations_outgoing_id_foreign` (`outgoing_id`),
  ADD KEY `outgoing_locations_product_id_foreign` (`product_id`),
  ADD KEY `outgoing_locations_area_id_foreign` (`area_id`),
  ADD KEY `outgoing_locations_rack_id_foreign` (`rack_id`),
  ADD KEY `outgoing_locations_level_id_foreign` (`level_id`);

--
-- Indexes for table `outgoing_sale_locations`
--
ALTER TABLE `outgoing_sale_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `outgoing_sale_locations_outgoing_id_foreign` (`outgoing_id`),
  ADD KEY `outgoing_sale_locations_product_id_foreign` (`product_id`),
  ADD KEY `outgoing_sale_locations_area_id_foreign` (`area_id`),
  ADD KEY `outgoing_sale_locations_rack_id_foreign` (`rack_id`),
  ADD KEY `outgoing_sale_locations_level_id_foreign` (`level_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `payments_account_id_foreign` (`account_id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_approves`
--
ALTER TABLE `payroll_approves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_approves_payroll_id_foreign` (`payroll_id`),
  ADD KEY `payroll_approves_created_by_foreign` (`created_by`),
  ADD KEY `payroll_approves_designation_id_foreign` (`designation_id`),
  ADD KEY `payroll_approves_department_id_foreign` (`department_id`);

--
-- Indexes for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_details_payroll_id_foreign` (`payroll_id`),
  ADD KEY `payroll_details_user_id_foreign` (`user_id`),
  ADD KEY `payroll_details_created_by_foreign` (`created_by`);

--
-- Indexes for table `payroll_detail_children`
--
ALTER TABLE `payroll_detail_children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_detail_children_payroll_detail_id_foreign` (`payroll_detail_id`);

--
-- Indexes for table `payroll_setups`
--
ALTER TABLE `payroll_setups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `personal_users`
--
ALTER TABLE `personal_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personal_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `po_important_notes`
--
ALTER TABLE `po_important_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_apis`
--
ALTER TABLE `production_apis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_output_traceabilities`
--
ALTER TABLE `production_output_traceabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_output_traceabilities_dpp_id_foreign` (`dpp_id`),
  ADD KEY `production_output_traceabilities_machine_id_foreign` (`machine_id`),
  ADD KEY `production_output_traceabilities_product_id_foreign` (`product_id`);

--
-- Indexes for table `production_output_traceability_details`
--
ALTER TABLE `production_output_traceability_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_output_traceability_details_dpp_id_foreign` (`dpp_id`),
  ADD KEY `production_output_traceability_details_pot_id_foreign` (`pot_id`),
  ADD KEY `production_output_traceability_details_machine_id_foreign` (`machine_id`);

--
-- Indexes for table `production_output_traceability_q_c_s`
--
ALTER TABLE `production_output_traceability_q_c_s`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_output_traceability_q_c_s_pot_id_foreign` (`pot_id`),
  ADD KEY `production_output_traceability_q_c_s_rt_id_foreign` (`rt_id`);

--
-- Indexes for table `production_output_traceability_rejections`
--
ALTER TABLE `production_output_traceability_rejections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_output_traceability_rejections_pot_id_foreign` (`pot_id`),
  ADD KEY `production_output_traceability_rejections_rt_id_foreign` (`rt_id`);

--
-- Indexes for table `production_output_traceability_shifts`
--
ALTER TABLE `production_output_traceability_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_output_traceability_shifts_dpp_id_foreign` (`dpp_id`),
  ADD KEY `production_output_traceability_shifts_pot_id_foreign` (`pot_id`);

--
-- Indexes for table `production_schedulings`
--
ALTER TABLE `production_schedulings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_type_of_product_foreign` (`type_of_product`),
  ADD KEY `products_unit_foreign` (`unit`),
  ADD KEY `products_customer_name_foreign` (`customer_name`),
  ADD KEY `products_supplier_name_foreign` (`supplier_name`);

--
-- Indexes for table `product_reorderings`
--
ALTER TABLE `product_reorderings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reorderings_product_id_foreign` (`product_id`);

--
-- Indexes for table `pr_approvals`
--
ALTER TABLE `pr_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pr_approvals_designation_id_foreign` (`designation_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_orders_pr_id_foreign` (`pr_id`),
  ADD KEY `purchase_orders_pp_id_foreign` (`pp_id`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_department_id_foreign` (`department_id`),
  ADD KEY `purchase_orders_created_by_foreign` (`created_by`);

--
-- Indexes for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_details_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_order_verification_histories`
--
ALTER TABLE `purchase_order_verification_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_verification_histories_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `purchase_plannings`
--
ALTER TABLE `purchase_plannings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_plannings_order_id_foreign` (`order_id`),
  ADD KEY `purchase_plannings_created_by_foreign` (`created_by`);

--
-- Indexes for table `purchase_planning_details`
--
ALTER TABLE `purchase_planning_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_planning_details_purchase_planning_id_foreign` (`purchase_planning_id`),
  ADD KEY `purchase_planning_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_planning_products`
--
ALTER TABLE `purchase_planning_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_planning_products_purchase_planning_id_foreign` (`purchase_planning_id`),
  ADD KEY `purchase_planning_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_planning_suppliers`
--
ALTER TABLE `purchase_planning_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_planning_suppliers_purchase_planning_id_foreign` (`purchase_planning_id`),
  ADD KEY `purchase_planning_suppliers_product_id_foreign` (`product_id`),
  ADD KEY `purchase_planning_suppliers_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_planning_verifications`
--
ALTER TABLE `purchase_planning_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_planning_verifications_purchase_planning_id_foreign` (`purchase_planning_id`),
  ADD KEY `purchase_planning_verifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `purchase_prices`
--
ALTER TABLE `purchase_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_prices_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_receive_locations`
--
ALTER TABLE `purchase_receive_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_receive_locations_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_receive_locations_product_id_foreign` (`product_id`),
  ADD KEY `purchase_receive_locations_area_id_foreign` (`area_id`),
  ADD KEY `purchase_receive_locations_rack_id_foreign` (`rack_id`),
  ADD KEY `purchase_receive_locations_level_id_foreign` (`level_id`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_requisitions_department_id_foreign` (`department_id`);

--
-- Indexes for table `purchase_requisition_details`
--
ALTER TABLE `purchase_requisition_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_requisition_details_product_id_foreign` (`product_id`),
  ADD KEY `purchase_requisition_details_purchase_requisition_id_foreign` (`purchase_requisition_id`);

--
-- Indexes for table `purchase_requisition_statuses`
--
ALTER TABLE `purchase_requisition_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_returns_po_id_foreign` (`po_id`),
  ADD KEY `purchase_returns_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_returns_checked_by_foreign` (`checked_by`),
  ADD KEY `purchase_returns_received_by_foreign` (`received_by`),
  ADD KEY `purchase_returns_created_by_foreign` (`created_by`);

--
-- Indexes for table `purchase_return_locations`
--
ALTER TABLE `purchase_return_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_locations_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_locations_product_id_foreign` (`product_id`),
  ADD KEY `purchase_return_locations_area_id_foreign` (`area_id`),
  ADD KEY `purchase_return_locations_rack_id_foreign` (`rack_id`),
  ADD KEY `purchase_return_locations_level_id_foreign` (`level_id`);

--
-- Indexes for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_products_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotations_customer_id_foreign` (`customer_id`),
  ADD KEY `quotations_created_by_foreign` (`created_by`);

--
-- Indexes for table `quotation_details`
--
ALTER TABLE `quotation_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_details_quotation_id_foreign` (`quotation_id`),
  ADD KEY `quotation_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_returns_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_returns_created_by_foreign` (`created_by`);

--
-- Indexes for table `sales_return_details`
--
ALTER TABLE `sales_return_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_return_details_sales_return_id_foreign` (`sales_return_id`),
  ADD KEY `sales_return_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `sales_return_locations`
--
ALTER TABLE `sales_return_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_return_locations_sales_return_id_foreign` (`sales_return_id`),
  ADD KEY `sales_return_locations_product_id_foreign` (`product_id`),
  ADD KEY `sales_return_locations_area_id_foreign` (`area_id`),
  ADD KEY `sales_return_locations_rack_id_foreign` (`rack_id`),
  ADD KEY `sales_return_locations_level_id_foreign` (`level_id`);

--
-- Indexes for table `sale_prices`
--
ALTER TABLE `sale_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_prices_product_id_foreign` (`product_id`);

--
-- Indexes for table `spec_breaks`
--
ALTER TABLE `spec_breaks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sst_percentages`
--
ALTER TABLE `sst_percentages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_relocations`
--
ALTER TABLE `stock_relocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_relocations_previous_area_id_foreign` (`previous_area_id`),
  ADD KEY `stock_relocations_previous_rack_id_foreign` (`previous_rack_id`),
  ADD KEY `stock_relocations_previous_level_id_foreign` (`previous_level_id`),
  ADD KEY `stock_relocations_new_area_id_foreign` (`new_area_id`),
  ADD KEY `stock_relocations_new_rack_id_foreign` (`new_rack_id`),
  ADD KEY `stock_relocations_new_level_id_foreign` (`new_level_id`),
  ADD KEY `stock_relocations_created_by_foreign` (`created_by`);

--
-- Indexes for table `stock_relocation_details`
--
ALTER TABLE `stock_relocation_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_relocation_details_stock_relocation_id_foreign` (`stock_relocation_id`),
  ADD KEY `stock_relocation_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_rankings`
--
ALTER TABLE `supplier_rankings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_rankings_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_account_id_foreign` (`account_id`),
  ADD KEY `transactions_secondary_account_id_foreign` (`secondary_account_id`),
  ADD KEY `transactions_customer_id_foreign` (`customer_id`),
  ADD KEY `transactions_supplier_id_foreign` (`supplier_id`),
  ADD KEY `transactions_product_id_foreign` (`product_id`);

--
-- Indexes for table `transfer_requests`
--
ALTER TABLE `transfer_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfer_request_details`
--
ALTER TABLE `transfer_request_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_request_details_transfer_request_id_foreign` (`transfer_request_id`);

--
-- Indexes for table `transfer_request_issues`
--
ALTER TABLE `transfer_request_issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfer_request_receives`
--
ALTER TABLE `transfer_request_receives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type_of_products`
--
ALTER TABLE `type_of_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type_of_rejections`
--
ALTER TABLE `type_of_rejections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_bank_details_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `account_categories`
--
ALTER TABLE `account_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `amortizations`
--
ALTER TABLE `amortizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `area_levels`
--
ALTER TABLE `area_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `area_locations`
--
ALTER TABLE `area_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `area_racks`
--
ALTER TABLE `area_racks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `boms`
--
ALTER TABLE `boms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `bom_crushings`
--
ALTER TABLE `bom_crushings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `bom_processes`
--
ALTER TABLE `bom_processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `bom_purchase_parts`
--
ALTER TABLE `bom_purchase_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `bom_sub_parts`
--
ALTER TABLE `bom_sub_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `bom_verifications`
--
ALTER TABLE `bom_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `call_for_assistances`
--
ALTER TABLE `call_for_assistances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `carry_forwards`
--
ALTER TABLE `carry_forwards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `daily_production_child_parts`
--
ALTER TABLE `daily_production_child_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `daily_production_plannings`
--
ALTER TABLE `daily_production_plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `daily_production_planning_details`
--
ALTER TABLE `daily_production_planning_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `daily_production_products`
--
ALTER TABLE `daily_production_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `delivery_instructions`
--
ALTER TABLE `delivery_instructions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `delivery_instruction_details`
--
ALTER TABLE `delivery_instruction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `discrepancies`
--
ALTER TABLE `discrepancies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `external_statements`
--
ALTER TABLE `external_statements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_child_users`
--
ALTER TABLE `family_child_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `family_users`
--
ALTER TABLE `family_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `good_receivings`
--
ALTER TABLE `good_receivings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `good_receiving_locations`
--
ALTER TABLE `good_receiving_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `good_receiving_products`
--
ALTER TABLE `good_receiving_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `good_receiving_qcs`
--
ALTER TABLE `good_receiving_qcs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `initail_nos`
--
ALTER TABLE `initail_nos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lot_nos`
--
ALTER TABLE `lot_nos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `machine_apis`
--
ALTER TABLE `machine_apis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `machine_counts`
--
ALTER TABLE `machine_counts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `machine_downimes`
--
ALTER TABLE `machine_downimes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_preperations`
--
ALTER TABLE `machine_preperations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `machine_tonnages`
--
ALTER TABLE `machine_tonnages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `material_plannings`
--
ALTER TABLE `material_plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_planning_details`
--
ALTER TABLE `material_planning_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_requisitions`
--
ALTER TABLE `material_requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `material_requisition_details`
--
ALTER TABLE `material_requisition_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `material_requisition_product_details`
--
ALTER TABLE `material_requisition_product_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `material_requisition_product_details_receives`
--
ALTER TABLE `material_requisition_product_details_receives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `more_users`
--
ALTER TABLE `more_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notification_screens`
--
ALTER TABLE `notification_screens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `outgoings`
--
ALTER TABLE `outgoings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `outgoing_details`
--
ALTER TABLE `outgoing_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `outgoing_locations`
--
ALTER TABLE `outgoing_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outgoing_sale_locations`
--
ALTER TABLE `outgoing_sale_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payroll_approves`
--
ALTER TABLE `payroll_approves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payroll_details`
--
ALTER TABLE `payroll_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_detail_children`
--
ALTER TABLE `payroll_detail_children`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_setups`
--
ALTER TABLE `payroll_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_users`
--
ALTER TABLE `personal_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `po_important_notes`
--
ALTER TABLE `po_important_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `processes`
--
ALTER TABLE `processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `production_apis`
--
ALTER TABLE `production_apis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `production_output_traceabilities`
--
ALTER TABLE `production_output_traceabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `production_output_traceability_details`
--
ALTER TABLE `production_output_traceability_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `production_output_traceability_q_c_s`
--
ALTER TABLE `production_output_traceability_q_c_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `production_output_traceability_rejections`
--
ALTER TABLE `production_output_traceability_rejections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `production_output_traceability_shifts`
--
ALTER TABLE `production_output_traceability_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `production_schedulings`
--
ALTER TABLE `production_schedulings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_reorderings`
--
ALTER TABLE `product_reorderings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pr_approvals`
--
ALTER TABLE `pr_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `purchase_order_verification_histories`
--
ALTER TABLE `purchase_order_verification_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_plannings`
--
ALTER TABLE `purchase_plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_planning_details`
--
ALTER TABLE `purchase_planning_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purchase_planning_products`
--
ALTER TABLE `purchase_planning_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_planning_suppliers`
--
ALTER TABLE `purchase_planning_suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_planning_verifications`
--
ALTER TABLE `purchase_planning_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_prices`
--
ALTER TABLE `purchase_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchase_receive_locations`
--
ALTER TABLE `purchase_receive_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_requisition_details`
--
ALTER TABLE `purchase_requisition_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `purchase_requisition_statuses`
--
ALTER TABLE `purchase_requisition_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_return_locations`
--
ALTER TABLE `purchase_return_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quotation_details`
--
ALTER TABLE `quotation_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales_returns`
--
ALTER TABLE `sales_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales_return_details`
--
ALTER TABLE `sales_return_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sales_return_locations`
--
ALTER TABLE `sales_return_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sale_prices`
--
ALTER TABLE `sale_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `spec_breaks`
--
ALTER TABLE `spec_breaks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sst_percentages`
--
ALTER TABLE `sst_percentages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_relocations`
--
ALTER TABLE `stock_relocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_relocation_details`
--
ALTER TABLE `stock_relocation_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier_rankings`
--
ALTER TABLE `supplier_rankings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transfer_requests`
--
ALTER TABLE `transfer_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transfer_request_details`
--
ALTER TABLE `transfer_request_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transfer_request_issues`
--
ALTER TABLE `transfer_request_issues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transfer_request_receives`
--
ALTER TABLE `transfer_request_receives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `type_of_products`
--
ALTER TABLE `type_of_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `type_of_rejections`
--
ALTER TABLE `type_of_rejections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `account_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `amortizations`
--
ALTER TABLE `amortizations`
  ADD CONSTRAINT `amortizations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `amortizations_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `area_locations`
--
ALTER TABLE `area_locations`
  ADD CONSTRAINT `area_locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `area_locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `area_locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `boms`
--
ALTER TABLE `boms`
  ADD CONSTRAINT `boms_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `bom_crushings`
--
ALTER TABLE `bom_crushings`
  ADD CONSTRAINT `bom_crushings_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`),
  ADD CONSTRAINT `bom_crushings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `bom_processes`
--
ALTER TABLE `bom_processes`
  ADD CONSTRAINT `bom_processes_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`);

--
-- Constraints for table `bom_purchase_parts`
--
ALTER TABLE `bom_purchase_parts`
  ADD CONSTRAINT `bom_purchase_parts_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`),
  ADD CONSTRAINT `bom_purchase_parts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `bom_sub_parts`
--
ALTER TABLE `bom_sub_parts`
  ADD CONSTRAINT `bom_sub_parts_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`),
  ADD CONSTRAINT `bom_sub_parts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `daily_production_child_parts`
--
ALTER TABLE `daily_production_child_parts`
  ADD CONSTRAINT `daily_production_child_parts_dpp_id_foreign` FOREIGN KEY (`dpp_id`) REFERENCES `daily_production_plannings` (`id`);

--
-- Constraints for table `daily_production_planning_details`
--
ALTER TABLE `daily_production_planning_details`
  ADD CONSTRAINT `daily_production_planning_details_dpp_id_foreign` FOREIGN KEY (`dpp_id`) REFERENCES `daily_production_plannings` (`id`);

--
-- Constraints for table `daily_production_products`
--
ALTER TABLE `daily_production_products`
  ADD CONSTRAINT `daily_production_products_dpp_id_foreign` FOREIGN KEY (`dpp_id`) REFERENCES `daily_production_plannings` (`id`);

--
-- Constraints for table `delivery_instructions`
--
ALTER TABLE `delivery_instructions`
  ADD CONSTRAINT `delivery_instructions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `delivery_instructions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `delivery_instruction_details`
--
ALTER TABLE `delivery_instruction_details`
  ADD CONSTRAINT `delivery_instruction_details_di_id_foreign` FOREIGN KEY (`di_id`) REFERENCES `delivery_instructions` (`id`),
  ADD CONSTRAINT `delivery_instruction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `external_statements`
--
ALTER TABLE `external_statements`
  ADD CONSTRAINT `external_statements_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `family_child_users`
--
ALTER TABLE `family_child_users`
  ADD CONSTRAINT `family_child_users_family_id_foreign` FOREIGN KEY (`family_id`) REFERENCES `family_users` (`id`);

--
-- Constraints for table `family_users`
--
ALTER TABLE `family_users`
  ADD CONSTRAINT `family_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `good_receivings`
--
ALTER TABLE `good_receivings`
  ADD CONSTRAINT `good_receivings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `good_receivings_po_id_foreign` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `good_receivings_pr_id_foreign` FOREIGN KEY (`pr_id`) REFERENCES `purchase_returns` (`id`);

--
-- Constraints for table `good_receiving_locations`
--
ALTER TABLE `good_receiving_locations`
  ADD CONSTRAINT `good_receiving_locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `good_receiving_locations_gr_id_foreign` FOREIGN KEY (`gr_id`) REFERENCES `good_receivings` (`id`),
  ADD CONSTRAINT `good_receiving_locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `good_receiving_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `good_receiving_locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `good_receiving_products`
--
ALTER TABLE `good_receiving_products`
  ADD CONSTRAINT `good_receiving_products_gr_id_foreign` FOREIGN KEY (`gr_id`) REFERENCES `good_receivings` (`id`),
  ADD CONSTRAINT `good_receiving_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `good_receiving_qcs`
--
ALTER TABLE `good_receiving_qcs`
  ADD CONSTRAINT `good_receiving_qcs_gr_id_foreign` FOREIGN KEY (`gr_id`) REFERENCES `good_receivings` (`id`),
  ADD CONSTRAINT `good_receiving_qcs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `good_receiving_qcs_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `type_of_rejections` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoices_outgoing_id_foreign` FOREIGN KEY (`outgoing_id`) REFERENCES `outgoings` (`id`);

--
-- Constraints for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD CONSTRAINT `invoice_details_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `invoice_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `machines`
--
ALTER TABLE `machines`
  ADD CONSTRAINT `machines_tonnage_id_foreign` FOREIGN KEY (`tonnage_id`) REFERENCES `machine_tonnages` (`id`);

--
-- Constraints for table `material_plannings`
--
ALTER TABLE `material_plannings`
  ADD CONSTRAINT `material_plannings_dppd_id_foreign` FOREIGN KEY (`dppd_id`) REFERENCES `daily_production_planning_details` (`id`);

--
-- Constraints for table `material_planning_details`
--
ALTER TABLE `material_planning_details`
  ADD CONSTRAINT `material_planning_details_planning_id_foreign` FOREIGN KEY (`planning_id`) REFERENCES `material_plannings` (`id`);

--
-- Constraints for table `material_requisition_details`
--
ALTER TABLE `material_requisition_details`
  ADD CONSTRAINT `material_requisition_details_material_requisition_id_foreign` FOREIGN KEY (`material_requisition_id`) REFERENCES `material_requisitions` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `more_users`
--
ALTER TABLE `more_users`
  ADD CONSTRAINT `more_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `outgoings`
--
ALTER TABLE `outgoings`
  ADD CONSTRAINT `outgoings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `outgoings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `outgoings_pr_id_foreign` FOREIGN KEY (`pr_id`) REFERENCES `purchase_returns` (`id`),
  ADD CONSTRAINT `outgoings_sr_id_foreign` FOREIGN KEY (`sr_id`) REFERENCES `sales_returns` (`id`);

--
-- Constraints for table `outgoing_details`
--
ALTER TABLE `outgoing_details`
  ADD CONSTRAINT `outgoing_details_outgoing_id_foreign` FOREIGN KEY (`outgoing_id`) REFERENCES `outgoings` (`id`),
  ADD CONSTRAINT `outgoing_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `outgoing_locations`
--
ALTER TABLE `outgoing_locations`
  ADD CONSTRAINT `outgoing_locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `outgoing_locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `outgoing_locations_outgoing_id_foreign` FOREIGN KEY (`outgoing_id`) REFERENCES `outgoings` (`id`),
  ADD CONSTRAINT `outgoing_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `outgoing_locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `outgoing_sale_locations`
--
ALTER TABLE `outgoing_sale_locations`
  ADD CONSTRAINT `outgoing_sale_locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `outgoing_sale_locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `outgoing_sale_locations_outgoing_id_foreign` FOREIGN KEY (`outgoing_id`) REFERENCES `outgoings` (`id`),
  ADD CONSTRAINT `outgoing_sale_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `outgoing_sale_locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `payments_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_approves`
--
ALTER TABLE `payroll_approves`
  ADD CONSTRAINT `payroll_approves_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payroll_approves_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `payroll_approves_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`),
  ADD CONSTRAINT `payroll_approves_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`);

--
-- Constraints for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD CONSTRAINT `payroll_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payroll_details_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`),
  ADD CONSTRAINT `payroll_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payroll_detail_children`
--
ALTER TABLE `payroll_detail_children`
  ADD CONSTRAINT `payroll_detail_children_payroll_detail_id_foreign` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`);

--
-- Constraints for table `personal_users`
--
ALTER TABLE `personal_users`
  ADD CONSTRAINT `personal_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `production_output_traceabilities`
--
ALTER TABLE `production_output_traceabilities`
  ADD CONSTRAINT `production_output_traceabilities_dpp_id_foreign` FOREIGN KEY (`dpp_id`) REFERENCES `daily_production_plannings` (`id`),
  ADD CONSTRAINT `production_output_traceabilities_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`),
  ADD CONSTRAINT `production_output_traceabilities_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `production_output_traceability_details`
--
ALTER TABLE `production_output_traceability_details`
  ADD CONSTRAINT `production_output_traceability_details_dpp_id_foreign` FOREIGN KEY (`dpp_id`) REFERENCES `daily_production_plannings` (`id`),
  ADD CONSTRAINT `production_output_traceability_details_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`),
  ADD CONSTRAINT `production_output_traceability_details_pot_id_foreign` FOREIGN KEY (`pot_id`) REFERENCES `production_output_traceabilities` (`id`);

--
-- Constraints for table `production_output_traceability_q_c_s`
--
ALTER TABLE `production_output_traceability_q_c_s`
  ADD CONSTRAINT `production_output_traceability_q_c_s_pot_id_foreign` FOREIGN KEY (`pot_id`) REFERENCES `production_output_traceabilities` (`id`),
  ADD CONSTRAINT `production_output_traceability_q_c_s_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `type_of_rejections` (`id`);

--
-- Constraints for table `production_output_traceability_rejections`
--
ALTER TABLE `production_output_traceability_rejections`
  ADD CONSTRAINT `production_output_traceability_rejections_pot_id_foreign` FOREIGN KEY (`pot_id`) REFERENCES `production_output_traceabilities` (`id`),
  ADD CONSTRAINT `production_output_traceability_rejections_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `type_of_rejections` (`id`);

--
-- Constraints for table `production_output_traceability_shifts`
--
ALTER TABLE `production_output_traceability_shifts`
  ADD CONSTRAINT `production_output_traceability_shifts_dpp_id_foreign` FOREIGN KEY (`dpp_id`) REFERENCES `daily_production_plannings` (`id`),
  ADD CONSTRAINT `production_output_traceability_shifts_pot_id_foreign` FOREIGN KEY (`pot_id`) REFERENCES `production_output_traceabilities` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_customer_name_foreign` FOREIGN KEY (`customer_name`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `products_supplier_name_foreign` FOREIGN KEY (`supplier_name`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `products_type_of_product_foreign` FOREIGN KEY (`type_of_product`) REFERENCES `type_of_products` (`id`),
  ADD CONSTRAINT `products_unit_foreign` FOREIGN KEY (`unit`) REFERENCES `units` (`id`);

--
-- Constraints for table `product_reorderings`
--
ALTER TABLE `product_reorderings`
  ADD CONSTRAINT `product_reorderings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `pr_approvals`
--
ALTER TABLE `pr_approvals`
  ADD CONSTRAINT `pr_approvals_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_orders_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `purchase_orders_pp_id_foreign` FOREIGN KEY (`pp_id`) REFERENCES `purchase_plannings` (`id`),
  ADD CONSTRAINT `purchase_orders_pr_id_foreign` FOREIGN KEY (`pr_id`) REFERENCES `purchase_requisitions` (`id`),
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD CONSTRAINT `purchase_order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_order_details_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`);

--
-- Constraints for table `purchase_order_verification_histories`
--
ALTER TABLE `purchase_order_verification_histories`
  ADD CONSTRAINT `purchase_order_verification_histories_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`);

--
-- Constraints for table `purchase_plannings`
--
ALTER TABLE `purchase_plannings`
  ADD CONSTRAINT `purchase_plannings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_plannings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `purchase_planning_details`
--
ALTER TABLE `purchase_planning_details`
  ADD CONSTRAINT `purchase_planning_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_planning_details_purchase_planning_id_foreign` FOREIGN KEY (`purchase_planning_id`) REFERENCES `purchase_plannings` (`id`);

--
-- Constraints for table `purchase_planning_products`
--
ALTER TABLE `purchase_planning_products`
  ADD CONSTRAINT `purchase_planning_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_planning_products_purchase_planning_id_foreign` FOREIGN KEY (`purchase_planning_id`) REFERENCES `purchase_plannings` (`id`);

--
-- Constraints for table `purchase_planning_suppliers`
--
ALTER TABLE `purchase_planning_suppliers`
  ADD CONSTRAINT `purchase_planning_suppliers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_planning_suppliers_purchase_planning_id_foreign` FOREIGN KEY (`purchase_planning_id`) REFERENCES `purchase_plannings` (`id`),
  ADD CONSTRAINT `purchase_planning_suppliers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_planning_verifications`
--
ALTER TABLE `purchase_planning_verifications`
  ADD CONSTRAINT `purchase_planning_verifications_purchase_planning_id_foreign` FOREIGN KEY (`purchase_planning_id`) REFERENCES `purchase_plannings` (`id`),
  ADD CONSTRAINT `purchase_planning_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_prices`
--
ALTER TABLE `purchase_prices`
  ADD CONSTRAINT `purchase_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchase_receive_locations`
--
ALTER TABLE `purchase_receive_locations`
  ADD CONSTRAINT `purchase_receive_locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `purchase_receive_locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `purchase_receive_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_receive_locations_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`),
  ADD CONSTRAINT `purchase_receive_locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD CONSTRAINT `purchase_requisitions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `purchase_requisition_details`
--
ALTER TABLE `purchase_requisition_details`
  ADD CONSTRAINT `purchase_requisition_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_requisition_details_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`);

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_checked_by_foreign` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_returns_po_id_foreign` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `purchase_returns_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_return_locations`
--
ALTER TABLE `purchase_return_locations`
  ADD CONSTRAINT `purchase_return_locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `purchase_return_locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `purchase_return_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_return_locations_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`),
  ADD CONSTRAINT `purchase_return_locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD CONSTRAINT `purchase_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_return_products_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quotations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `quotation_details`
--
ALTER TABLE `quotation_details`
  ADD CONSTRAINT `quotation_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `quotation_details_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD CONSTRAINT `sales_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_returns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `sales_return_details`
--
ALTER TABLE `sales_return_details`
  ADD CONSTRAINT `sales_return_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_return_details_sales_return_id_foreign` FOREIGN KEY (`sales_return_id`) REFERENCES `sales_returns` (`id`);

--
-- Constraints for table `sales_return_locations`
--
ALTER TABLE `sales_return_locations`
  ADD CONSTRAINT `sales_return_locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `sales_return_locations_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `sales_return_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_return_locations_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `area_racks` (`id`),
  ADD CONSTRAINT `sales_return_locations_sales_return_id_foreign` FOREIGN KEY (`sales_return_id`) REFERENCES `sales_returns` (`id`);

--
-- Constraints for table `sale_prices`
--
ALTER TABLE `sale_prices`
  ADD CONSTRAINT `sale_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_relocations`
--
ALTER TABLE `stock_relocations`
  ADD CONSTRAINT `stock_relocations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_relocations_new_area_id_foreign` FOREIGN KEY (`new_area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `stock_relocations_new_level_id_foreign` FOREIGN KEY (`new_level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `stock_relocations_new_rack_id_foreign` FOREIGN KEY (`new_rack_id`) REFERENCES `area_racks` (`id`),
  ADD CONSTRAINT `stock_relocations_previous_area_id_foreign` FOREIGN KEY (`previous_area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `stock_relocations_previous_level_id_foreign` FOREIGN KEY (`previous_level_id`) REFERENCES `area_levels` (`id`),
  ADD CONSTRAINT `stock_relocations_previous_rack_id_foreign` FOREIGN KEY (`previous_rack_id`) REFERENCES `area_racks` (`id`);

--
-- Constraints for table `stock_relocation_details`
--
ALTER TABLE `stock_relocation_details`
  ADD CONSTRAINT `stock_relocation_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_relocation_details_stock_relocation_id_foreign` FOREIGN KEY (`stock_relocation_id`) REFERENCES `stock_relocations` (`id`);

--
-- Constraints for table `supplier_rankings`
--
ALTER TABLE `supplier_rankings`
  ADD CONSTRAINT `supplier_rankings_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_secondary_account_id_foreign` FOREIGN KEY (`secondary_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transfer_request_details`
--
ALTER TABLE `transfer_request_details`
  ADD CONSTRAINT `transfer_request_details_transfer_request_id_foreign` FOREIGN KEY (`transfer_request_id`) REFERENCES `transfer_requests` (`id`);

--
-- Constraints for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  ADD CONSTRAINT `user_bank_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
