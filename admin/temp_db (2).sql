-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2025 at 07:12 AM
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
-- Database: `temp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `submission_id` varchar(255) NOT NULL,
  `lab_id` int(11) NOT NULL,
  `unique_id` varchar(50) DEFAULT NULL,
  `submission_date` datetime NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `request_type` varchar(100) DEFAULT NULL,
  `submission_date_selected` date DEFAULT NULL,
  `status` int(11) NOT NULL,
  `priority` int(11) DEFAULT 4,
  `queue` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `submission_id`, `lab_id`, `unique_id`, `submission_date`, `full_name`, `address`, `contact_number`, `email_address`, `quantity`, `category`, `request_type`, `submission_date_selected`, `status`, `priority`, `queue`) VALUES
(165, '6164479216511800214', 1, 'TTC-00023', '2025-02-27 01:45:21', 'Bryan Doverte', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 123-456-7890', 'cedrickcap7@gmail.com', 2, 'New', NULL, '2025-03-01', 2, 5, 1),
(166, '6164492406516149357', 2, 'TTC-00024', '2025-02-27 02:07:20', 'Elladle Mandal', 'Burgos St, , Cagayan De Oro, Misamis Oriental, ', '(+63) 238-789-7348', 'cedrickcap7@gmail.com', 1, 'Renewal', 'On-Site', '2025-03-01', 2, 5, 1),
(167, '6164502156518593682', 1, 'TTC-00025', '2025-02-27 02:23:35', 'zerc regner', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 213-231-2313', 'cedrickcap7@gmail.com', 1, 'New', 'On-Site', '2025-03-01', 2, 4, 1),
(168, '6164524686849133328', 1, 'TTC-00026', '2025-02-27 03:01:09', 'Ruel Wenceslao', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 898-980-9809', 'cedrickcap7@gmail.com', 1, 'Renewal', 'Face To Face', '2025-03-01', 2, 4, 1),
(169, '6164532896842945914', 1, 'TTC-00027', '2025-02-27 03:14:49', 'Ruel Wenceslao', 'Carmen, , Cagayan De Oro, Misamis Oriental, ', '(+63) 342-342-4324', 'wenceslaoruelii@gmail.com', 1, 'Renewal', 'Face To Face', '2025-03-01', 2, 1, 2),
(170, '6164535356849446415', 1, 'TTC-00028', '2025-02-27 03:18:55', 'Cedrick Capuyan', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 342-342-4324', 'cedrickcap7@gmail.com', 1, 'Renewal', 'On-Site', '2025-03-01', 3, 4, 1),
(171, '6165137066519269445', 1, 'TTC-00029', '2025-02-27 20:01:46', 'Bryan Doverte', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 231-232-1313', 'emdovz02@gmail.com', 1, 'Renewal', 'Face To Face', '2025-03-07', 3, 4, 1),
(172, '6165287846517597443', 1, 'TTC-00030', '2025-02-28 00:13:04', 'Bryan Doverte', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 908-959-6784', 'cedrickcap7@gmail.com', 1, 'Renewal', 'In-House', '2025-03-07', 2, 3, 2),
(173, '6165287846517597443', 1, 'TTC-00031', '2025-02-28 00:13:04', 'Mark Doverte', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 908-959-6785', 'cedrickcap7@gmail.com', 1, 'Renewal', 'In-House', '2025-03-08', 3, 4, 2),
(174, '6165287846517597443', 1, 'TTC-00032', '2025-02-28 00:13:04', 'Eddie Doverte', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 908-959-6784', 'cedrickcap7@gmail.com', 1, 'Renewal', 'In-House', '2025-03-09', 1, 4, 2),
(175, '6165287846517597443', 1, 'TTC-00033', '2025-02-28 00:13:04', 'Bryan Doverte', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 908-959-6784', 'cedrickcap7@gmail.com', 1, 'Renewal', 'In-House', '2025-03-10', 2, 2, 2),
(176, '6165287846517597443', 1, 'TTC-00034', '2025-02-28 00:13:04', 'Mark Doverte', 'P-13 Sayre Highway, Hagkol, , VALENCIA CITY, BUKIDNON, ', '(+63) 908-959-6784', 'cedrickcap7@gmail.com', 1, 'Renewal', 'In-House', '2025-03-11', 3, 4, 2),
(177, '6173786796516241578', 3, 'SML-21426', '2025-03-09 21:18:00', 'Cedrick Capuyan', 'P-13, Hagkol, , Valencia, Bukidnon, , Philippines', '(+63) 090-895-9678', 'cedrickcap7@gmail.com', 5, 'Swab', 'Sample Submission', '2025-03-12', 2, 5, 2),
(178, '6173804126515176471', 2, 'WPL-21332', '2025-03-09 21:46:52', 'Cedrick Capuyan', 'North Poblacion, , 8714, , , Philippines', '(+63) 090-895-9678', 'cedrickcap7@gmail.com', 5, 'Waste Water - Treated', 'Sample Submission', '2025-03-12', 2, 5, 2),
(179, '6173786796516241578', 3, 'SML-21427', '2025-03-10 21:18:00', 'Cedrick Capuyan', 'P-13, Hagkol, , Valencia, Bukidnon, , Philippines', '(+63) 090-895-9678', 'cedrickcap7@gmail.com', 5, 'Swab', 'Sample Submission', '2025-03-12', 3, 4, 2),
(180, '6173804126515176471', 2, 'WPL-21333', '2025-03-10 21:46:52', 'Cedrick Capuyan', 'North Poblacion, , 8714, , , Philippines', '(+63) 090-895-9678', 'cedrickcap7@gmail.com', 5, 'Waste Water - Treated', 'Sample Submission', '2025-03-12', 1, 4, 2),
(181, '8229561778258444770', 1, 'WMD-64837', '2025-03-25 06:26:44', 'Met', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-25', 2, 4, 2),
(182, '5553692446149786024', 1, 'WMD-18773', '2025-03-25 08:30:59', 'met', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-25', 2, 4, 2),
(183, '5102161565694961108', 4, 'WSL-93163', '2025-03-25 08:34:11', 'shelf', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-25', 2, 4, 0),
(184, '4241009794903099432', 1, 'WMD-79376', '2025-03-25 08:36:36', 'samp1', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-25', 2, 4, 0),
(185, '5940416404326030278', 4, 'WSL-76571', '2025-03-25 08:36:46', 'samp2', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-25', 2, 4, 0),
(186, '9125098885058883819', 5, 'WGC-75869', '2025-03-25 08:36:53', 'samp3', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-25', 2, 3, 2),
(187, '2465369241524236741', 6, 'WGI-70735', '2025-03-25 08:37:04', 'samp4', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-25', 2, 2, 2),
(188, '6406549902729480718', 4, 'WSL-88411', '2025-03-26 08:16:03', 'ghfg', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-26', 2, 4, 0),
(189, '8647743584810390226', 4, 'WSL-81879', '2025-03-26 08:16:49', 'bry', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-26', 2, 4, 0),
(190, '2279240433833929170', 4, 'WSL-81967', '2025-03-26 08:17:35', 'hello', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-26', 2, 4, 2),
(191, '5372619310051821749', 4, 'WSL-19213', '2025-03-26 08:17:53', 'ghg', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-26', 2, 4, 2),
(192, '6740732120435957678', 6, 'WGI-22641', '2025-03-26 08:18:13', 'gng', NULL, NULL, NULL, 0, 'Walk-in', NULL, '2025-03-26', 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1 = Admin, 2 = Metrology Analyst, 3 = Chemical Analyst, 4 = Microbiological Analyst, 5 = Shelf-life Analyst',
  `code_name` varchar(100) DEFAULT NULL,
  `user_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `code_name`, `user_picture`, `created_at`) VALUES
(7, 'admin', 'admin@gmail.com', '$2y$10$kHyrqqo3tuv09mc6e8D29eNvrcZXNNwWE4Jvpc.60UM288PDZySh6', 1, 'ADMIN', '', '2025-03-12 01:50:25'),
(12, 'metrology', 'metrology@gmail.com', '$2y$10$DZFStXbo5krcy.muuV7DrOOP6Zvy3N1/jZEGgpdHcyFFlThjD8o.y', 2, 'Metrology Analyst', '', '2025-03-12 07:20:32'),
(13, 'chemical', 'chemical@gmail.com', '$2y$10$WXuvw/PzLGK7W5booCRggO5vk7JO7hk/RvM7IJ72.oaLDSbqHV8wa', 3, 'Chemical Analyst', '', '2025-03-12 07:21:01'),
(14, 'microbiological', 'microbiological@gmail.com', '$2y$10$HwIuPODR4p1Rty.vxXRYr.H.w31ah23mxv3pLStRrCx4AsIKHC2y2', 4, 'Microbiological Analyst', '', '2025-03-12 07:27:32'),
(15, 'shelflife', 'shelflife@gmail.com', '$2y$10$4d9fKDVttqQdawA9IJwzj.jbMeNjNMiBfdS.pC29v1IX4EEFXPeUS', 5, 'Shelf-Life Analyst', '', '2025-03-12 07:28:26'),
(16, 'bryan', 'bryan@gmail.com', '$2y$10$wNb8bzdr19WFBfCZVwQFfupeP0QecStfwmdXFef5zl1UGKREmCfoe', 1, 'Bryan_Boi', 'uploads/pic.jpg', '2025-03-12 23:50:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `activity`, `timestamp`) VALUES
(91, 7, 'User logged out', '2025-03-14 07:16:14'),
(92, 13, 'User logged in', '2025-03-14 07:16:20'),
(93, 13, 'User logged out', '2025-03-14 07:16:44'),
(94, 7, 'User logged in', '2025-03-17 01:23:47'),
(95, 7, 'User logged in', '2025-03-17 03:18:46'),
(96, 7, 'User logged in', '2025-03-25 01:36:00'),
(97, 7, 'User logged out', '2025-03-25 01:36:28'),
(99, 7, 'User logged in', '2025-03-25 05:36:13'),
(100, 7, 'User logged in', '2025-03-26 00:33:15'),
(101, 7, 'User logged out', '2025-03-26 00:35:06'),
(102, 7, 'User logged in', '2025-03-26 01:42:51'),
(103, 7, 'User logged out', '2025-03-26 02:05:17'),
(104, 16, 'User logged in', '2025-03-26 02:05:24'),
(105, 16, 'Bryan_Boi approved reservation ID: TTC-00029', '2025-03-26 02:48:42'),
(106, 16, 'Bryan_Boi rejected reservation ID: TTC-00029', '2025-03-26 02:48:56'),
(107, 16, 'User logged out', '2025-03-26 05:16:37'),
(108, 7, 'User logged in', '2025-03-26 06:18:47'),
(109, 7, 'User logged in', '2025-03-26 06:21:56'),
(110, 7, 'User logged out', '2025-03-26 06:35:09'),
(111, 7, 'User logged in', '2025-03-26 07:06:45'),
(112, 7, 'ADMIN approved reservation ID: TTC-00029', '2025-03-26 07:07:45'),
(113, 7, 'User logged out', '2025-03-26 07:12:27'),
(114, 12, 'User logged in', '2025-03-26 07:12:36'),
(115, 12, 'User logged out', '2025-03-26 07:15:32'),
(116, 7, 'User logged in', '2025-03-26 07:36:47'),
(117, 7, 'ADMIN rejected reservation ID: TTC-00029', '2025-03-26 07:37:02'),
(118, 7, 'User logged in', '2025-03-28 02:05:54'),
(119, 7, 'User logged out', '2025-03-28 02:21:39'),
(120, 7, 'User logged in', '2025-03-28 02:21:44'),
(121, 7, 'User logged in', '2025-03-28 05:20:52'),
(122, 7, 'User logged in', '2025-03-31 00:40:43'),
(123, 7, 'User logged in', '2025-03-31 01:18:06'),
(124, 7, 'User logged in', '2025-03-31 06:15:13'),
(125, 7, 'User logged in', '2025-03-31 06:44:34'),
(126, 7, 'User logged in', '2025-04-03 00:04:40'),
(127, 7, 'User logged in', '2025-04-03 01:53:43'),
(128, 7, 'User logged in', '2025-04-03 02:18:56'),
(129, 16, 'User logged in', '2025-04-03 05:58:22'),
(130, 7, 'User logged in', '2025-04-03 06:30:29'),
(131, 16, 'User logged in', '2025-04-03 06:53:03'),
(132, 7, 'User logged in', '2025-04-04 01:41:20'),
(133, 7, 'User logged in', '2025-04-04 02:14:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
