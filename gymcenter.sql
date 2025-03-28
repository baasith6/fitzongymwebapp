-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 02:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
('email@admin.com', 'Admin 1', '23d42f5f3f66498b2c8ff4c20b8c5ac826e47146');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `cid` varchar(6) NOT NULL,
  `class_name` text NOT NULL,
  `trainer` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`cid`, `class_name`, `trainer`) VALUES
('C001', 'Strength Training', 'T002'),
('C002', 'Yoga Flow', 'T003'),
('C003', 'Cardio Burn', 'T001');

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

--
-- Dumping data for table `class_bookings`
--

INSERT INTO `class_bookings` (`bid`, `email`, `cid`, `date`, `time`) VALUES
('', 'iamtabishek@gmail.com', 'C001', '2024-11-30', '05:39:48'),
('', 'itstabishek@gmail.com', 'C002', '2024-11-29', '19:39:48'),
('B001', 'itstabishek@gmail.com', 'C002', '2024-11-28', '07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` int(10) NOT NULL,
  `dob` date NOT NULL,
  `membership` varchar(50) NOT NULL,
  `payment_option` text NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`first_name`, `last_name`, `email`, `contact_number`, `dob`, `membership`, `payment_option`, `password`) VALUES
('Abishek', 'Abi', 'abi@gmail.com', 771234567, '2001-10-06', 'M002', 'paypal', '$2y$10$WABt/hKKa8emafDgYIDDa.KdkHOCxtQ87274qYJvAnVHMf/R6VsOW'),
('Abishek', 'Thayanantham', 'abishek@gmail.com', 771234567, '2001-10-06', 'M002', 'paypal', '$2y$10$fhS0zeqcmpSCMA0E3O8bnugeoIsrNYwBT07Dnw90tAZkFrFPzJbYW'),
('Abishek', 'Thayaanantham', 'itstabishek@gmail.com', 752220380, '2024-11-28', 'M002', 'credit', '$2y$10$oo8NLLLSvkZColr9h6JyR.sMeZYTdzQDvnMW.G0VHXIyr72ccp0Oy');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `fid` varchar(6) NOT NULL,
  `email` varchar(100) NOT NULL,
  `feedback_text` text NOT NULL,
  `feedback_date` date NOT NULL DEFAULT current_timestamp(),
  `feedback_time` time NOT NULL DEFAULT current_timestamp(),
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`fid`, `email`, `feedback_text`, `feedback_date`, `feedback_time`, `status`) VALUES
('F001', 'itstabishek@gmail.com', '0', '2024-11-29', '02:47:51', 'Resolved'),
('F002', 'itstabishek@gmail.com', '0', '2024-11-29', '02:48:14', 'Resolved'),
('F003', 'itstabishek@gmail.com', '0', '2024-11-29', '02:50:29', 'Resolved'),
('F004', 'itstabishek@gmail.com', '0', '2024-11-29', '02:53:20', 'Resolved'),
('F005', 'itstabishek@gmail.com', 'jbk', '2024-11-29', '02:56:15', 'Resolved'),
('F006', 'itstabishek@gmail.com', ' k', '2024-11-29', '03:34:22', 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `management`
--

CREATE TABLE `management` (
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` int(10) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `management`
--

INSERT INTO `management` (`first_name`, `last_name`, `email`, `contact_number`, `password`) VALUES
('Tony', 'Stark', 'tony@gmail.com', 771234567, '$2y$10$Fw5sAQy2WIydJGDc/jyQTeNCbGpK3VVQ1/BbTS1nzffFun5Zjx7Lu');

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

--
-- Dumping data for table `meal_plans`
--

INSERT INTO `meal_plans` (`id`, `meal_type`, `muscle_gain`, `fat_loss`, `general_fitness`) VALUES
(1, 'Breakfast', 'Scrambled eggs (3) with spinach, whole-grain toast, oatmeal with protein powder, chia seeds, fruit', 'Egg whites (4-5) with tomatoes & spinach, whole-grain toast with almond butter', 'Whole-grain toast with avocado & poached eggs (2), smoothie with spinach, banana, protein powder'),
(2, 'Snack', 'Protein shake with banana or apple, mixed nuts', 'Protein shake, small handful of almonds or walnuts', 'Greek yogurt (150g) with granola & berries'),
(3, 'Lunch', 'Grilled chicken (200g), quinoa (1 cup), roasted veggies, avocado', 'Grilled turkey breast (150g), mixed greens, quinoa (1/2 cup), olive oil dressing', 'Grilled chicken (150g) or tofu, brown rice (1/2 cup), mixed vegetables, pumpkin seeds'),
(4, 'Snack (Post-Workout)', 'Protein shake, whole-grain bread with almond butter', 'Protein shake with water, small apple or carrot sticks', 'Protein shake, small fruit (apple/orange)'),
(5, 'Dinner', 'Grilled salmon (200g), sweet potatoes (1 medium), steamed veggies, salad', 'Grilled chicken (150g), cauliflower rice, steamed broccoli, avocado', 'Grilled salmon (150g), quinoa, roasted veggies, side salad'),
(6, 'Evening Snack', 'Greek yogurt (200g) with berries & honey or cottage cheese with cucumber', 'Cottage cheese with cucumber slices or boiled egg', 'Boiled egg or cottage cheese with cucumber slices');

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
('M005', 'Online Membership', 1500);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `pid` varchar(6) NOT NULL,
  `email` varchar(100) NOT NULL,
  `payment_date` date NOT NULL DEFAULT current_timestamp(),
  `payment_time` time NOT NULL DEFAULT current_timestamp(),
  `amount` double NOT NULL,
  `payment_method` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`pid`, `email`, `payment_date`, `payment_time`, `amount`, `payment_method`) VALUES
('P001', 'itstabishek@gmail.com', '2024-11-29', '02:26:32', 400, 'Credit'),
('P002', 'iamtabishek@gmail.com', '2024-11-29', '02:26:32', 500, 'Debit'),
('P9502', 'istabishek@gmail.com', '2024-11-22', '13:07:00', 1000, 'Credit Card');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`name`, `email`, `message`, `date`) VALUES
('sfd', 'itstabishek@gmail.com', 'fass', '2024-11-29'),
('sfd', 'itstabishek@gmail.com', 'fass', '2024-11-29'),
('Abishek', 'itstabishek@gmail.com', 'sample1', '2024-11-29');

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `tid` varchar(6) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`tid`, `name`, `email`, `contact_number`) VALUES
('T001', 'Albert Einstein', 'albert@gmail.com', 771234567),
('T002', 'Isaac Newton', 'isaac@gmail.com', 751234567),
('T003', 'Nikola Tesla', 'tesla@gmail.com', 781234567),
('T004', 'Elon Musk', 'elon@gmail.com', 711234567);

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
('abc@gmail.com', 'management'),
('abcd@gmail.com', 'management'),
('abi@gmail.com', 'customer'),
('abishek@gmail.com', 'customer'),
('itstabishek@gmail.com', 'customer'),
('tony@gmail.com', 'management');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fid`);

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
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
