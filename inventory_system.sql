-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2026 at 01:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `article_code` varchar(30) NOT NULL,
  `article_name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `article_code`, `article_name`, `category`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ART-001', 'Access Point', 'Networking Equipment', 'TP Link Wireless MU-MIMO Gigabit Indoor/Outdoor Access Point EAP225-Outdoor', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(2, 'ART-002', 'CCTV', 'ICT Equipment', 'Wireless CCTV with Monitor', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(3, 'ART-003', 'Clickers', 'ICT Equipment', 'Logitech Presentation Clickers', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(4, 'ART-004', 'Desktop Computer', 'Computer Equipment', 'Acer Veriton Monitor/CPU/Keyboard', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(5, 'ART-005', 'GPS', 'ICT Equipment', 'Garmin eTrex 10 GPS', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(6, 'ART-006', 'Hard Drive 1TB', 'Computer Equipment', 'Seagate Hard Drive 1TB', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(7, 'ART-007', 'Hard Drive 2TB', 'Computer Equipment', 'Seagate Hard Drive 2TB', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(8, 'ART-008', 'Headset', 'Computer Equipment', 'Logitech Headset', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(9, 'ART-009', 'Laptop', 'Computer Equipment', 'Laptop Computer', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(10, 'ART-010', 'Monitor', 'Computer Equipment', 'LED Monitor', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(11, 'ART-011', 'Printer', 'Printers and Scanners', 'Laser Printer', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(12, 'ART-012', 'Projector', 'Office Equipment', 'Multimedia Projector', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(13, 'ART-013', 'Router', 'Networking Equipment', 'Wireless Router', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(14, 'ART-014', 'Scanner', 'Printers and Scanners', 'Document Scanner', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(15, 'ART-015', 'Switch', 'Networking Equipment', 'Network Switch', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54'),
(16, 'ART-016', 'UPS', 'Power Equipment', 'Uninterruptible Power Supply', 'Active', '2026-09-16 05:37:54', '2026-09-16 05:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_items`
--

CREATE TABLE `borrowed_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `released_by` int(11) NOT NULL,
  `released_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `returned_at` datetime DEFAULT NULL,
  `returned_by` int(11) DEFAULT NULL,
  `condition_before` varchar(100) DEFAULT NULL,
  `condition_after` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_history`
--

CREATE TABLE `borrowing_history` (
  `id` int(11) NOT NULL,
  `borrowing_id` int(11) NOT NULL,
  `from_status` varchar(50) DEFAULT NULL,
  `to_status` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `acted_by` int(11) NOT NULL,
  `acted_name` varchar(100) DEFAULT NULL,
  `acted_role` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_logs`
--

CREATE TABLE `borrowing_logs` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowing_logs`
--

INSERT INTO `borrowing_logs` (`id`, `request_id`, `user_id`, `action`, `remarks`, `created_at`) VALUES
(1, NULL, 2, 'Borrow Request Submitted', 'Borrowing request created.', '2026-09-22 04:59:19'),
(2, NULL, 2, 'Borrow Request Submitted', 'Borrowing request created.', '2026-09-22 05:07:06'),
(3, NULL, 2, 'Borrow Request Submitted', 'Borrowing request created.', '2026-09-29 06:03:32'),
(4, 1, 2, 'Borrow Request Submitted', 'Borrow request BR-2026-000001 was submitted.', '2026-09-30 16:34:49'),
(5, 2, 2, 'Borrow Request Submitted', 'Borrow request BR-2026-000002 was submitted.', '2026-09-30 16:53:21'),
(6, 3, 2, 'Borrow Request Submitted', 'Borrow request BR-2026-000003 was submitted.', '2026-10-01 03:05:21'),
(7, 4, 2, 'Borrow Request Submitted', 'Borrow request BR-2026-000004 was submitted.', '2026-10-08 00:31:06'),
(8, 5, 2, 'Borrow Request Submitted', 'Borrow request BR-2026-000005 was submitted.', '2026-10-08 00:31:33');

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_old`
--

CREATE TABLE `borrowing_old` (
  `id` int(11) NOT NULL,
  `request_no` varchar(30) DEFAULT NULL,
  `inventory_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `purpose` text DEFAULT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date NOT NULL,
  `status` enum('Pending','Pending Review','Custodian Approved','Admin Approved','Ready for Release','Borrowed','Return Requested','Inspection','Completed','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `supply_officer_id` int(11) DEFAULT NULL,
  `custodian_id` int(11) DEFAULT NULL,
  `super_admin_id` int(11) DEFAULT NULL,
  `supply_approved_at` datetime DEFAULT NULL,
  `custodian_approved_at` datetime DEFAULT NULL,
  `super_admin_approved_at` datetime DEFAULT NULL,
  `released_at` datetime DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL,
  `rejected_reason` text DEFAULT NULL,
  `digital_signature` varchar(255) DEFAULT NULL,
  `email_sent` tinyint(1) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowing_old`
--

INSERT INTO `borrowing_old` (`id`, `request_no`, `inventory_id`, `personnel_id`, `purpose`, `borrow_date`, `return_date`, `status`, `created_at`, `supply_officer_id`, `custodian_id`, `super_admin_id`, `supply_approved_at`, `custodian_approved_at`, `super_admin_approved_at`, `released_at`, `returned_at`, `rejected_reason`, `digital_signature`, `email_sent`, `last_updated`) VALUES
(34, 'BR-2026-000034', 2033, 1, '2', '2026-09-22', '2026-09-22', 'Pending', '2026-09-22 04:59:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-09-22 04:59:19'),
(35, 'BR-2026-000035', 2031, 2, 'wefwefwefqw', '2026-09-22', '2026-09-22', 'Pending', '2026-09-22 05:07:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-09-22 05:07:06'),
(36, 'BR-2026-000036', 2030, 5, 'feafasfas', '2026-09-29', '2026-09-29', 'Pending', '2026-09-29 06:03:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-09-29 06:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_approvals`
--

CREATE TABLE `borrow_approvals` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `approval_step` varchar(50) DEFAULT NULL,
  `decision` enum('Approved','Rejected') DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_notifications`
--

CREATE TABLE `borrow_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_notifications`
--

INSERT INTO `borrow_notifications` (`id`, `user_id`, `request_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 34, 'New Borrowing Request', 'Borrowing Request BR-2026-000034 has been submitted.', 0, '2026-09-22 04:59:19'),
(2, 2, 35, 'New Borrowing Request', 'Borrowing Request BR-2026-000035 has been submitted.', 0, '2026-09-22 05:07:06'),
(3, 2, 36, 'New Borrowing Request', 'Borrowing Request BR-2026-000036 has been submitted.', 0, '2026-09-29 06:03:32'),
(4, 2, 1, 'New Borrow Request', 'Borrow request BR-2026-000001 has been submitted.', 0, '2026-09-30 16:34:49'),
(5, 2, 2, 'New Borrow Request', 'Borrow request BR-2026-000002 has been submitted.', 0, '2026-09-30 16:53:21'),
(6, 2, 3, 'New Borrow Request', 'Borrow request BR-2026-000003 has been submitted.', 0, '2026-10-01 03:05:21'),
(7, 2, 4, 'New Borrow Request', 'Borrow request BR-2026-000004 has been submitted.', 0, '2026-10-08 00:31:06'),
(8, 2, 5, 'New Borrow Request', 'Borrow request BR-2026-000005 has been submitted.', 0, '2026-10-08 00:31:33');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_release_items`
--

CREATE TABLE `borrow_release_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `released_by` int(11) NOT NULL,
  `released_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `returned_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_requests`
--

CREATE TABLE `borrow_requests` (
  `id` int(11) NOT NULL,
  `request_no` varchar(30) DEFAULT NULL,
  `borrower_id` int(11) NOT NULL,
  `purpose` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `expected_return` date DEFAULT NULL,
  `permanent_release` enum('No','Yes') DEFAULT 'No',
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `workflow_step` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_requests`
--

INSERT INTO `borrow_requests` (`id`, `request_no`, `borrower_id`, `purpose`, `remarks`, `borrow_date`, `expected_return`, `permanent_release`, `status`, `workflow_step`, `created_at`) VALUES
(1, 'BR-2026-000001', 1, 'asdasdasd', '', '2026-10-01', '2026-10-01', 'No', 'Pending', 1, '2026-09-30 16:34:49'),
(2, 'BR-2026-000002', 2, 'sdasdasdasdasd', '', '2026-10-01', '2026-10-01', 'No', 'Pending', 1, '2026-09-30 16:53:21'),
(3, 'BR-2026-000003', 4, 'dasdas', 'asdasd', '2026-10-01', '2026-10-01', 'No', 'Pending', 1, '2026-10-01 03:05:21'),
(4, 'BR-2026-000004', 2, 'adasdasdas', '', '2026-10-08', '2026-10-08', 'No', 'Pending', 1, '2026-10-08 00:31:06'),
(5, 'BR-2026-000005', 2, 'sdasdasd', '', '2026-10-08', '2026-10-08', 'No', 'Pending', 1, '2026-10-08 00:31:33');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_request_items`
--

CREATE TABLE `borrow_request_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_request_items`
--

INSERT INTO `borrow_request_items` (`id`, `request_id`, `article_id`, `quantity`) VALUES
(1, 1, 4, 1),
(2, 2, 6, 1),
(3, 3, 4, 5),
(4, 4, 3, 1),
(5, 5, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `borrow_request_serials`
--

CREATE TABLE `borrow_request_serials` (
  `id` int(11) NOT NULL,
  `request_item_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `released_at` datetime DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL,
  `return_condition` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `class_id` varchar(20) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `class_level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `class_id`, `class_name`, `class_level`, `created_at`) VALUES
(1, 'AA', 'Administrative Aide', 6, '2026-09-02 06:07:23'),
(2, 'ADAS', 'Administrative Assistant', 6, '2026-09-02 06:07:23'),
(3, 'AO', 'Administrative Officer', 5, '2026-09-02 06:07:23'),
(4, 'BUDU', 'Budget Officer', 5, '2026-09-02 06:07:23'),
(5, 'CADOF', 'Chief Administrative Officer', 1, '2026-09-02 06:07:23'),
(6, 'CO', 'Computer Operator', 4, '2026-09-02 06:07:23'),
(7, 'Dir', 'Director', 4, '2026-09-02 06:07:23'),
(8, 'DMO', 'Development Management Officer', 5, '2026-09-02 06:07:23'),
(9, 'Engr', 'Engineer', 5, '2026-09-02 06:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `property_no` varchar(100) NOT NULL,
  `article_id` int(11) NOT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `unit_cost` decimal(12,2) DEFAULT 0.00,
  `date_acquired` datetime DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` enum('Available','Borrowed','Repair','Disposed') DEFAULT 'Available',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `ics_no` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `property_no` varchar(100) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `object_code` varchar(100) DEFAULT NULL,
  `series` varchar(100) DEFAULT NULL,
  `location_code` varchar(100) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `type_of_ppe` varchar(150) DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `unit_of_measure` varchar(50) DEFAULT NULL,
  `unit_value` decimal(12,2) DEFAULT NULL,
  `se_e` varchar(50) DEFAULT NULL,
  `condition` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `accountable_officer_id` int(11) DEFAULT NULL,
  `equipment_status` enum('Available','Reserved','Borrowed','Under Repair','Lost','Disposed') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `ics_no`, `province`, `property_no`, `year`, `month`, `object_code`, `series`, `location_code`, `article_id`, `type_of_ppe`, `item_description`, `serial_number`, `date_acquired`, `qty`, `unit_of_measure`, `unit_value`, `se_e`, `condition`, `remarks`, `accountable_officer_id`, `equipment_status`) VALUES
(2030, '', 'Zambales Provincial Office', '#REF!', '2020', '1', '#REF!', '1', '#REF!', 1, '1', 'Access Point', 'ICT EQUIPMENT', '0000-00-00', 221, '1/1/2021', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Reserved'),
(2031, '', 'Zambales Provincial Office', '#REF!', '2019', '1', '#REF!', '2', '#REF!', 2, '8', 'CCTV', 'ICT EQUIPMENT', '0000-00-00', 0, '1/8/2019', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Reserved'),
(2032, '', 'Zambales Provincial Office', '#REF!', '2020', '2', '#REF!', '3', '#REF!', 3, '12', 'Clickers', 'ICT EQUIPMENT', '0000-00-00', 1941, '2/12/2020', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Available'),
(2033, '', 'Zambales Provincial Office', '#REF!', '2018', '11', '#REF!', '4', '#REF!', 4, '23', 'Desktop', 'ICT EQUIPMENT', '0000-00-00', 0, '11/23/2018', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Reserved'),
(2034, '', 'Zambales Provincial Office', '#REF!', '2018', '2', '#REF!', '5', '#REF!', 5, '17', 'GPS', 'ICT EQUIPMENT', '0000-00-00', 53, '2/17/2018', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Available'),
(2035, '', 'Zambales Provincial Office', '#REF!', '2016', '4', '#REF!', '6', '#REF!', 6, '20', 'Hard Drive', 'ICT EQUIPMENT', '0000-00-00', 0, '4/20/2016', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Available'),
(2036, '', 'Zambales Provincial Office', '#REF!', '2016', '4', '#REF!', '7', '#REF!', 6, '20', 'Hard Drive', 'ICT EQUIPMENT', '0000-00-00', 0, '4/20/2016', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Available'),
(2037, '', 'Zambales Provincial Office', '#REF!', '2020', '1', '#REF!', '8', '#REF!', 8, '20', 'Headset', 'ICT EQUIPMENT', '0000-00-00', 0, '1/20/2020', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Available'),
(2038, '', 'Zambales Provincial Office', '#REF!', '2020', '1', '#REF!', '9', '#REF!', 8, '20', 'Headset', 'ICT EQUIPMENT', '0000-00-00', 0, '1/20/2020', 0.00, 'unit', 'Serviceable', 'SEMI-EXPENDABLE', 0, 'Available'),
(3026, 'ICS-2026-0001', 'Zambales Provincial Office', 'PROP-2026-0001', '2026', 'September', 'ICT-001', '001', 'ZPO-01', 4, 'ICT EQUIPMENT', 'Dell OptiPlex 7060 Desktop Computer', 'SN-123456789', '2026-09-17', 1, 'Unit', 35000.00, 'SEMI-EXPENDABLE', 'Serviceable', 'For Office Use', 1, 'Available'),
(3027, 'ICS-2026-0001', 'Zambales Provincial Office', 'PROP-2026-0001', '2026', 'September', 'ICT-001', '001', 'ZPO-01', 4, 'ICT EQUIPMENT', 'Dell OptiPlex 7060 Desktop Computer', 'SN-123456789', '2026-09-17', 1, 'Unit', 35000.00, 'SEMI-EXPENDABLE', 'Serviceable', 'For Office Use', 1, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) NOT NULL,
  `employment_status` enum('Permanent','Contractual','COS','JO') DEFAULT 'Permanent',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived` enum('No','Yes') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`id`, `employee_id`, `fullname`, `email`, `contact_number`, `position`, `designation`, `division`, `employment_status`, `status`, `photo`, `created_at`, `updated_at`, `archived`) VALUES
(1, 'EMP-001', 'Juan Dela Cruz', 'juan@dict.gov.ph', '09123456789', 'Division Chief', 'Officer-in-Charge', 'Operations Division', 'Permanent', 'Active', NULL, '2026-09-01 03:13:39', '2026-09-01 03:13:39', 'No'),
(2, 'EMP-002', 'Maria Santos', 'maria@dict.gov.ph', '09111222333', 'Administrative Officer IV', 'Admin Officer', 'Administrative Services', 'Permanent', 'Active', NULL, '2026-09-01 03:13:39', '2026-09-01 03:13:39', 'No'),
(3, 'EMP-003', 'Pedro Reyes', 'pedro@dict.gov.ph', '09998887777', 'Information Systems Analyst', 'Technical Staff', 'ICT Division', 'Contractual', 'Active', NULL, '2026-09-01 03:13:39', '2026-09-09 02:01:37', 'Yes'),
(4, 'ABC-001', 'John irish Ragot', 'johnirishcruzragot@gmail.com', '09633670530', 'Super visor', 'Op', 'Administrative and Finance Division', 'Permanent', 'Active', NULL, '2026-09-03 06:49:06', '2026-10-01 03:04:55', 'No'),
(5, 'ABC-002', 'hahahha', 'hahaha@gmail.com', '09633670590', 'supply officer', 'Op', 'Administrative and Finance Division', 'Contractual', 'Active', NULL, '2026-09-09 05:44:30', '2026-09-21 03:08:14', 'No'),
(6, 'abc-009', 'john irish', 'johnirishragot@gmailo.com', '09633670530', 'admin', 'op', 'Administrative and Finance Division', 'Permanent', 'Active', NULL, '2026-10-01 03:03:37', '2026-10-01 03:04:08', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `division` varchar(100) NOT NULL,
  `role` enum('super_admin','admin','supply_officer','custodian','encoder','viewer') NOT NULL DEFAULT 'encoder',
  `status` enum('active','inactive','blocked') DEFAULT 'active',
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expire` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `division`, `role`, `status`, `otp_code`, `otp_expire`, `last_login`, `created_at`) VALUES
(2, 'john irish ragot', 'johnirishcruzragot@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 'admin', '', NULL, NULL, '2026-10-10 13:15:53', '2026-08-24 03:07:24'),
(15, 'miguel', 'johnpaulmartires01@gmail.com', '$2y$10$A/n152UqKyf9tTLpQWCjdOmS/C2EmQiKo2gwIt9gR42VsrR791mci', 'Administrative and Finance Division', 'custodian', 'active', NULL, NULL, '2026-09-17 14:09:54', '2026-09-14 02:21:25'),
(16, 'Lhean', 'lhean.bhel25@gmail.com', '$2y$10$WyqTSvh63IR9wYJ3Sro.6OLIWWfckceY.PX804T9xxdb1Dr6fzGJ2', 'Administrative and Finance Division', 'encoder', 'active', NULL, NULL, '2026-09-16 08:41:10', '2026-09-14 06:20:45'),
(20, 'System Administrator', 'johnirishragot28@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ICT', 'super_admin', '', NULL, NULL, '2026-09-22 15:09:32', '2026-09-17 06:56:18');

-- --------------------------------------------------------

--
-- Table structure for table `user_signatures`
--

CREATE TABLE `user_signatures` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_code` (`article_code`);

--
-- Indexes for table `borrowed_items`
--
ALTER TABLE `borrowed_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_borrowed_request` (`request_id`),
  ADD KEY `fk_borrowed_inventory` (`inventory_id`);

--
-- Indexes for table `borrowing_history`
--
ALTER TABLE `borrowing_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_borrowing` (`borrowing_id`),
  ADD KEY `idx_user` (`acted_by`),
  ADD KEY `idx_date` (`created_at`);

--
-- Indexes for table `borrowing_logs`
--
ALTER TABLE `borrowing_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_borrowing_logs_request` (`request_id`);

--
-- Indexes for table `borrowing_old`
--
ALTER TABLE `borrowing_old`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_no` (`request_no`),
  ADD KEY `personnel_id` (`personnel_id`),
  ADD KEY `borrowing_ibfk_1` (`inventory_id`);

--
-- Indexes for table `borrow_approvals`
--
ALTER TABLE `borrow_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_request` (`request_id`),
  ADD KEY `idx_approver` (`approver_id`);

--
-- Indexes for table `borrow_notifications`
--
ALTER TABLE `borrow_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrow_release_items`
--
ALTER TABLE `borrow_release_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_release_request` (`request_id`),
  ADD KEY `fk_release_inventory` (`inventory_id`),
  ADD KEY `fk_release_user` (`released_by`);

--
-- Indexes for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_no` (`request_no`);

--
-- Indexes for table `borrow_request_items`
--
ALTER TABLE `borrow_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `borrow_request_serials`
--
ALTER TABLE `borrow_request_serials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_item_id` (`request_item_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_id` (`class_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_no` (`property_no`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `gallery_ibfk_1` (`inventory_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_property_no` (`property_no`),
  ADD KEY `idx_article_id` (`article_id`),
  ADD KEY `idx_accountable_officer_id` (`accountable_officer_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_signatures`
--
ALTER TABLE `user_signatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `borrowed_items`
--
ALTER TABLE `borrowed_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrowing_history`
--
ALTER TABLE `borrowing_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrowing_logs`
--
ALTER TABLE `borrowing_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `borrowing_old`
--
ALTER TABLE `borrowing_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `borrow_approvals`
--
ALTER TABLE `borrow_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_notifications`
--
ALTER TABLE `borrow_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `borrow_release_items`
--
ALTER TABLE `borrow_release_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrow_request_items`
--
ALTER TABLE `borrow_request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrow_request_serials`
--
ALTER TABLE `borrow_request_serials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3028;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_signatures`
--
ALTER TABLE `user_signatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_items`
--
ALTER TABLE `borrowed_items`
  ADD CONSTRAINT `fk_borrowed_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_borrowed_request` FOREIGN KEY (`request_id`) REFERENCES `borrow_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrowing_logs`
--
ALTER TABLE `borrowing_logs`
  ADD CONSTRAINT `fk_borrowing_logs_request` FOREIGN KEY (`request_id`) REFERENCES `borrow_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrowing_old`
--
ALTER TABLE `borrowing_old`
  ADD CONSTRAINT `borrowing_old_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `borrowing_old_ibfk_2` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow_release_items`
--
ALTER TABLE `borrow_release_items`
  ADD CONSTRAINT `fk_release_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_release_request` FOREIGN KEY (`request_id`) REFERENCES `borrow_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_release_user` FOREIGN KEY (`released_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow_request_items`
--
ALTER TABLE `borrow_request_items`
  ADD CONSTRAINT `borrow_request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `borrow_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_request_items_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Constraints for table `borrow_request_serials`
--
ALTER TABLE `borrow_request_serials`
  ADD CONSTRAINT `borrow_request_serials_ibfk_1` FOREIGN KEY (`request_item_id`) REFERENCES `borrow_request_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_request_serials_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`);

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `equipment_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `personnel` (`id`);

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gallery_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
