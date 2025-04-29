-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307:3307
-- Generation Time: Apr 26, 2025 at 08:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gymcenter`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `name`, `password`) VALUES
('icbt1101@gmail.com', 'icbt1101', '88df719b77ddadc98d972414e29d0074cef679e7');

-- --------------------------------------------------------

--
-- Table structure for table `bmi_records`
--

CREATE TABLE `bmi_records` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `height` decimal(4,2) NOT NULL,
  `bmi` decimal(5,2) NOT NULL,
  `category` varchar(20) NOT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bmi_records`
--

INSERT INTO `bmi_records` (`id`, `email`, `weight`, `height`, `bmi`, `category`, `calculated_at`) VALUES
(1, 'icbt0602@gmail.com', 110.00, 99.99, 0.00, 'Underweight', '2025-04-19 07:31:15'),
(2, 'icbt0602@gmail.com', 110.00, 99.99, 0.00, 'Underweight', '2025-04-19 07:31:17'),
(3, 'icbt0602@gmail.com', 110.00, 99.99, 0.00, 'Underweight', '2025-04-19 07:31:19'),
(4, 'icbt0602@gmail.com', 110.00, 99.99, 0.00, 'Underweight', '2025-04-19 07:31:20'),
(5, 'icbt0602@gmail.com', 110.00, 99.99, 0.00, 'Underweight', '2025-04-19 07:31:21'),
(6, 'icbt0602@gmail.com', 94.00, 1.77, 30.00, 'Obesity', '2025-04-19 07:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `cid` varchar(6) NOT NULL,
  `class_name` text NOT NULL,
  `trainer` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_bookings`
--

CREATE TABLE `class_bookings` (
  `bid` varchar(6) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cid` varchar(6) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `email` varchar(100) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image_url` text DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `membership` varchar(50) DEFAULT NULL,
  `payment_option` text DEFAULT NULL,
  `membership_start` date DEFAULT NULL,
  `membership_end` date DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`email`, `first_name`, `last_name`, `contact_number`, `password`, `image_url`, `dob`, `membership`, `payment_option`, `membership_start`, `membership_end`, `is_deleted`) VALUES
('abi18@gmail.com', 'abi', 'abith', '0777353485', '$2y$10$trCTe3h00EzlqxnvTxOgbe5Xgam7IowxlYrKflFD45jl4UwEyknAO', NULL, NULL, NULL, NULL, NULL, NULL, 0),
('icbt0601@gmail.com', 'icbt', '0601', '+94777353488', '$2y$10$2HQm4JBcVwb720eS9W5nGeXFmytxyGXzqPWpWkSi.3ncv86P.Vtu6', NULL, NULL, NULL, NULL, NULL, NULL, 0),
('icbt0602@gmail.com', 'abdul', '0602', '+94777353485', '$2y$10$X6ZnJ/I4M2.XTlxpQQTTfeP2jSsd/MuEv.ik5d7KU4fEmjFmvSB2K', NULL, '2003-11-24', 'M003', 'paypal', NULL, NULL, 0),
('nana18@gmail.com', 'abdul', 'nana', '0770883095', '$2y$10$3lphNQNKwa8d.gRWMF7Hme/XmPONEBSVmc8s/5TPu2FmdhZqeCBUO', NULL, NULL, NULL, NULL, NULL, NULL, 0),
('raws18@gmail.com', 'rows', 'ahamed', '0769637929', '$2y$10$RHuPtSlagbss5juGCm.IM.KaRE8gLW/a0hOGmkigDOw2jBnhGszpK', NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `fid` varchar(6) NOT NULL,
  `email` varchar(100) NOT NULL,
  `feedback_text` text NOT NULL,
  `feedback_date` date NOT NULL DEFAULT curdate(),
  `feedback_time` time NOT NULL DEFAULT curtime(),
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`fid`, `email`, `feedback_text`, `feedback_date`, `feedback_time`, `status`) VALUES
('F001', 'icbt0602@gmail.com', 'fgththttrtrtgfgrg', '2025-04-19', '10:40:23', 'Resolved'),
('F002', 'icbt0602@gmail.com', '080', '2025-04-19', '11:00:30', 'Resolved'),
('F003', 'icbt0602@gmail.com', 'rerwecbvfggxcfdsfg', '2025-04-26', '05:02:37', 'Resolved'),
('F004', 'icbt0602@gmail.com', 'the meal plan is goood', '2025-04-26', '05:20:29', 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `management`
--

CREATE TABLE `management` (
  `email` varchar(100) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `management`
--

INSERT INTO `management` (`email`, `first_name`, `last_name`, `contact_number`, `password`, `image_url`) VALUES
('ahamed123@gmail.com', 'saneej', 'sa', '', '$2y$12$yKuaaFn53Eh1MQ3afi8Xs.9HjV9mVk6BFBXUUentMUGM6pKxOwI4y', NULL),
('ahamedsajee2002@gmail.com', 'Ahamed', 'sajee', '0769637929', '$2y$10$vcXGYdd.uahNp8mapyX2N.jke3QVOE10m7nwOuQT/.wtJpeJbADkC', NULL),
('djblack060@gmail.com', 'SABOOR', 'BAASITH', '0777353481', '$2y$10$b0Ffaz2ixvuSeI3IpHb6D.p2gmuwc6bdjrBJlU.2fyfD0dR2PAKHu', NULL),
('mohi50@gmail.com', 'saboor', 'mohi', '0777353485', '$2y$10$bkgAxeEYnXswDKcJPSFUy.UDQ8LMWFyaPB5m9v.fpFwDeQXjPkPPu', NULL),
('newtest02@gmail.com', 'newtest', '02', '0769637929', '$2y$10$LsV/RoZ33BDewrM9m/C81O3tWLt7uV/TwZe.6dEUMkl.j9GCp/GXS', NULL),
('rejina123@gmail.com', 'Abdul', 'rejin', '0777353481', '$2y$10$5CeNABoyfD.6b4dFk2zYG.HYrUmQrXg8ifm3421XpU9SDbc9Y0VU.', NULL),
('test01@gmail.com', 'test', '01', '0777353485', '$2y$10$Ckb0TOy7BqZt9Oixn/slPup1ZkzBRvcpxuoRBsg2Ai3XCAPc43hVW', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` int(11) NOT NULL,
  `meal_type` varchar(255) DEFAULT NULL,
  `muscle_gain` text DEFAULT NULL,
  `fat_loss` text DEFAULT NULL,
  `general_fitness` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `membership_package`
--

CREATE TABLE `membership_package` (
  `mid` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_package`
--

INSERT INTO `membership_package` (`mid`, `name`, `price`) VALUES
('M001', 'Basic Membership', 2000),
('M002', 'Standard Membership', 3500),
('M003', 'Premium Membership', 5000),
('M004', 'Student Membership', 3000),
('M005', 'Online Membership', 1500),
('M006', 'Couples Wales', 2300),
('M007', 'single goal', 4000);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `pid` varchar(6) NOT NULL,
  `email` varchar(100) NOT NULL,
  `payment_date` date NOT NULL DEFAULT curdate(),
  `payment_time` time NOT NULL DEFAULT curtime(),
  `amount` double NOT NULL,
  `payment_method` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`name`, `email`, `message`, `date`) VALUES
('SABOOR MOHAIDEEN ABDUL BAASITH', 'djblack060@gmail.com', 'i want to know about more trainng and activities', '2025-04-26');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `target_id` varchar(6) DEFAULT NULL,
  `target_type` enum('class','trainer') NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `rated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_log`
--

CREATE TABLE `report_log` (
  `id` int(11) NOT NULL,
  `generated_by` varchar(100) DEFAULT NULL,
  `report_type` varchar(100) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `tid` varchar(6) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `image_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`tid`, `name`, `email`, `contact_number`, `image_url`) VALUES
('T001', 'SABOOR MOHAIDEEN ABDUL BAASITH', 'abaasith18@gmail.com', '0777353481', NULL),
('T002', 'SABOOR MOHAIDEEN ABDUL BAASITH', 'djblack060@gmail.com', '0777353481', NULL),
('T003', 'abdul', 'abdulbaasith1124@gmail.com', '0777353481', NULL),
('T004', 'ahamed', 'saeej123@gmail.com', '0777353481', NULL),
('T005', 'ahamed', 'saeej1234@gmail.com', '0777353481', NULL),
('T006', 'baka', 'baka123@gmail.com', '0770883095', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

CREATE TABLE `usertypes` (
  `email` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`email`, `user_type`) VALUES
('abi18@gmail.com', 'customer'),
('ahamed123@gmail.com', 'management'),
('ahamedsajee2002@gmail.com', 'management'),
('djblack060@gmail.com', 'management'),
('icbt0601@gmail.com', 'customer'),
('icbt0602@gmail.com', 'customer'),
('icbt1101@gmail.com', 'admin'),
('mohi50@gmail.com', 'management'),
('nana18@gmail.com', 'customer'),
('newtest02@gmail.com', 'management'),
('raws18@gmail.com', 'customer'),
('rejina123@gmail.com', 'management'),
('test01@gmail.com', 'management');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `bmi_records`
--
ALTER TABLE `bmi_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `fk_class_trainer` (`trainer`);

--
-- Indexes for table `class_bookings`
--
ALTER TABLE `class_bookings`
  ADD PRIMARY KEY (`bid`),
  ADD KEY `fk_cb_email` (`email`),
  ADD KEY `fk_cb_cid` (`cid`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `fk_feedback_email` (`email`);

--
-- Indexes for table `management`
--
ALTER TABLE `management`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_package`
--
ALTER TABLE `membership_package`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `fk_payment_email` (`email`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_log`
--
ALTER TABLE `report_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `usertypes`
--
ALTER TABLE `usertypes`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bmi_records`
--
ALTER TABLE `bmi_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_log`
--
ALTER TABLE `report_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bmi_records`
--
ALTER TABLE `bmi_records`
  ADD CONSTRAINT `bmi_records_ibfk_1` FOREIGN KEY (`email`) REFERENCES `customers` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `fk_class_trainer` FOREIGN KEY (`trainer`) REFERENCES `trainers` (`tid`);

--
-- Constraints for table `class_bookings`
--
ALTER TABLE `class_bookings`
  ADD CONSTRAINT `fk_cb_cid` FOREIGN KEY (`cid`) REFERENCES `classes` (`cid`),
  ADD CONSTRAINT `fk_cb_email` FOREIGN KEY (`email`) REFERENCES `customers` (`email`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_email` FOREIGN KEY (`email`) REFERENCES `customers` (`email`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payment_email` FOREIGN KEY (`email`) REFERENCES `customers` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
