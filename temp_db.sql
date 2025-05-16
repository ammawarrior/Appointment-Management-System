-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 03:31 AM
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
-- Table structure for table `currently_serving`
--

CREATE TABLE `currently_serving` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `submission_id` varchar(255) DEFAULT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currently_serving`
--

INSERT INTO `currently_serving` (`id`, `user_id`, `submission_id`, `started_at`) VALUES
(14, 24, NULL, '2025-04-08 02:26:08');

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
  `analysis` varchar(255) DEFAULT 'Tank Truck Calibration',
  `quantity` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `request_type` varchar(100) DEFAULT 'Evaluation',
  `submission_date_selected` date DEFAULT NULL,
  `status` int(11) NOT NULL,
  `priority` int(11) DEFAULT 4,
  `queue` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1 = Admin, 2 = Metrology Analyst, 3 = Chemical Analyst, 4 = Microbiological Analyst, 5 = Shelf-life Analyst, 6 = Sample Receiving/Releasing, 7 = Inquiry Counter',
  `code_name` varchar(100) DEFAULT NULL,
  `user_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `code_name`, `user_picture`, `created_at`) VALUES
(12, 'metrology', 'metrology@gmail.com', '$2y$10$DZFStXbo5krcy.muuV7DrOOP6Zvy3N1/jZEGgpdHcyFFlThjD8o.y', 2, 'Metrology Analyst', '', '2025-03-12 07:20:32'),
(13, 'chemical', 'chemical@gmail.com', '$2y$10$WXuvw/PzLGK7W5booCRggO5vk7JO7hk/RvM7IJ72.oaLDSbqHV8wa', 3, 'Chemical Analyst', '', '2025-03-12 07:21:01'),
(14, 'microbiological', 'microbiological@gmail.com', '$2y$10$HwIuPODR4p1Rty.vxXRYr.H.w31ah23mxv3pLStRrCx4AsIKHC2y2', 4, 'Microbiological Analyst', '', '2025-03-12 07:27:32'),
(15, 'shelflife', 'shelflife@gmail.com', '$2y$10$4d9fKDVttqQdawA9IJwzj.jbMeNjNMiBfdS.pC29v1IX4EEFXPeUS', 5, 'Shelf-Life Analyst', '', '2025-03-12 07:28:26'),
(21, 'admin', 'admin@gmail.com', '$2y$10$geLaBh/9aNHz97JSLf3UuOxp4QFJuTK48P4Z4DLTmbi8EQVCRLJFK', 1, 'admin', '', '2025-04-08 01:42:33'),
(22, 'counter1', 'counter1@gmail.com', '$2y$10$/J0JKMR5H7smTPpN.ILjIeTniXcfdGIF0JxhyIH8wNHUYWP/4X0my', 6, 'Counter 1 ', '', '2025-04-08 01:44:10'),
(23, 'counter2', 'counter2@gmail.com', '$2y$10$HABl9KqJZHssmpcKSPcHGu2x63f5weDww2c60QVqJ2RySETPAS8A2', 6, 'Counter 2', '', '2025-04-08 01:45:37'),
(24, 'counter3', 'counter3@gmail.com', '$2y$10$GCWxlIRONVz/AUMX.YChvOQMt8p/mj7Af0VWxwrLxsFjGg.3qLCce', 7, 'Counter 3', '', '2025-04-08 01:53:13');

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
(92, 13, 'User logged in', '2025-03-14 07:16:20'),
(93, 13, 'User logged out', '2025-03-14 07:16:44'),
(114, 12, 'User logged in', '2025-03-26 07:12:36'),
(115, 12, 'User logged out', '2025-03-26 07:15:32'),
(176, 21, 'User logged in', '2025-04-08 01:42:41'),
(177, 22, 'User logged in', '2025-04-08 01:54:01'),
(178, 24, 'User logged in', '2025-04-08 01:54:28'),
(179, 21, 'User logged out', '2025-04-08 03:22:20'),
(180, 22, 'User logged in', '2025-04-08 03:22:30'),
(181, 22, 'User logged out', '2025-04-08 05:46:04'),
(182, 22, 'User logged in', '2025-04-08 05:46:15'),
(183, 22, 'User logged out', '2025-04-08 06:09:19'),
(184, 22, 'User logged in', '2025-04-08 06:09:29'),
(185, 22, 'User logged out', '2025-04-08 06:09:32'),
(186, 21, 'User logged in', '2025-04-08 06:09:37'),
(187, 21, 'User logged out', '2025-04-08 06:09:41'),
(188, 22, 'User logged in', '2025-04-08 06:14:52'),
(189, 22, 'User logged in', '2025-04-08 07:03:27'),
(190, 22, 'User logged out', '2025-04-08 07:04:19'),
(191, 21, 'User logged in', '2025-04-08 07:04:30'),
(192, 21, 'User logged out', '2025-04-08 07:21:05'),
(193, 22, 'User logged in', '2025-04-10 00:23:26'),
(194, 22, 'User logged in', '2025-04-10 01:08:58'),
(195, 22, 'User logged out', '2025-04-10 06:35:43'),
(196, 21, 'User logged in', '2025-04-10 06:38:42'),
(197, 21, 'User logged out', '2025-04-10 07:24:24'),
(198, 22, 'User logged in', '2025-04-10 07:24:37'),
(199, 22, 'User logged out', '2025-04-11 02:19:04'),
(200, 22, 'User logged in', '2025-04-11 03:26:41'),
(201, 22, 'User logged in', '2025-04-11 03:27:21'),
(202, 21, 'User logged in', '2025-04-11 03:30:10'),
(203, 21, 'admin rejected reservation ID: WIC-14060', '2025-04-11 03:31:28'),
(204, 21, 'admin rejected reservation ID: TTC-00029', '2025-04-11 03:31:43'),
(205, 21, 'admin approved reservation ID: TTC-00029', '2025-04-11 03:47:35'),
(206, 21, 'admin rejected reservation ID: TTC-00029', '2025-04-11 03:48:04'),
(207, 21, 'admin approved reservation ID: TTC-00029', '2025-04-11 03:50:57'),
(208, 21, 'admin rejected reservation ID: TTC-00029', '2025-04-11 03:51:10'),
(209, 21, 'admin approved reservation ID: TTC-00029', '2025-04-11 05:04:30'),
(210, 21, 'admin rejected reservation ID: TTC-00029', '2025-04-11 05:04:44'),
(211, 21, 'User logged out', '2025-04-11 05:28:47'),
(212, 21, 'User logged in', '2025-04-11 06:33:20'),
(213, 21, 'User logged in', '2025-04-14 00:36:46'),
(214, 21, 'User logged in', '2025-04-14 00:47:28'),
(215, 21, 'User logged in', '2025-04-14 00:55:29'),
(216, 22, 'User logged in', '2025-04-14 01:15:28'),
(217, 22, 'User logged in', '2025-04-14 01:36:39'),
(218, 22, 'User logged in', '2025-04-14 01:38:55'),
(219, 22, 'User logged out', '2025-04-14 01:46:07'),
(220, 22, 'User logged in', '2025-04-14 01:46:48'),
(221, 22, 'User logged in', '2025-04-14 01:54:24'),
(222, 22, 'User logged in', '2025-04-14 23:45:53'),
(223, 22, 'User logged in', '2025-04-15 01:08:24'),
(224, 22, 'Counter 1  approved reservation ID: TTC-00029', '2025-04-15 01:09:27'),
(225, 22, 'Counter 1  rejected reservation ID: TTC-00029', '2025-04-15 01:09:45'),
(226, 22, 'TTC-12345 was assigned to Counter 1  for transaction.', '2025-04-15 03:36:17'),
(227, 22, 'TTC-12345 was assigned to Counter 1  for transaction.', '2025-04-15 03:36:35'),
(228, 22, ' was assigned to Counter 1  for transaction.', '2025-04-15 03:38:44'),
(229, 22, ' was assigned to Counter 1  for transaction.', '2025-04-15 03:38:47'),
(230, 22, 'User logged out', '2025-04-15 03:44:52'),
(231, 22, 'User logged in', '2025-04-15 03:46:11'),
(232, 21, 'User logged in', '2025-04-16 14:02:40'),
(233, 21, 'User logged out', '2025-04-16 14:02:53'),
(234, 21, 'User logged in', '2025-04-16 14:06:53'),
(235, 21, 'User logged out', '2025-04-16 14:09:24'),
(237, 22, 'User logged in', '2025-04-16 14:09:58'),
(238, 22, 'Counter 1  approved reservation ID: TTC-00029', '2025-04-16 14:11:57'),
(239, 22, 'Counter 1  rejected reservation ID: TTC-00029', '2025-04-16 14:12:25'),
(240, 22, 'User logged in', '2025-04-16 14:17:36'),
(241, 21, 'User logged in', '2025-04-21 00:14:32'),
(242, 22, 'User logged in', '2025-04-21 00:14:58'),
(243, 21, 'User logged in', '2025-04-22 00:55:48'),
(244, 21, 'User logged in', '2025-04-22 01:44:38'),
(245, 21, 'User logged in', '2025-04-23 07:30:50'),
(246, 21, 'User logged in', '2025-04-24 01:28:52'),
(247, 21, 'User logged out', '2025-04-24 01:51:19'),
(248, 21, 'User logged in', '2025-04-24 01:52:35'),
(249, 21, 'User logged in', '2025-04-24 02:26:52'),
(250, 21, 'User logged in', '2025-04-24 05:28:16'),
(251, 21, 'User logged in', '2025-04-24 06:03:41'),
(252, 21, 'User logged in', '2025-04-28 03:33:30'),
(253, 21, 'User logged in', '2025-04-29 01:15:11'),
(254, 22, 'User logged out', '2025-04-29 01:22:02'),
(255, 21, 'User logged out', '2025-04-29 01:35:17'),
(256, 21, 'User logged out', '2025-04-29 01:36:17'),
(257, 22, 'User logged out', '2025-04-29 01:45:01'),
(258, 21, 'User logged in', '2025-04-29 01:50:37'),
(259, 21, 'User logged out', '2025-04-29 01:57:30'),
(260, 21, 'User logged out', '2025-04-29 01:58:19'),
(261, 21, 'User logged in', '2025-04-29 01:58:28'),
(262, 21, 'admin updated the appointment date for submission ID: 6165137066519269445', '2025-04-29 02:35:41'),
(263, 21, 'admin updated the appointment date for submission ID: 6165137066519269445', '2025-04-29 02:37:54'),
(264, 21, 'admin updated the appointment date for submission ID: 6165137066519269445', '2025-04-29 02:40:52'),
(265, 21, 'admin rejected reservation ID: TTC-00029', '2025-04-29 02:41:35'),
(266, 21, 'User logged out', '2025-04-29 02:46:04'),
(267, 21, 'User logged in', '2025-04-30 02:54:05'),
(268, 21, 'User logged in', '2025-05-02 02:06:26'),
(269, 21, 'User logged out', '2025-05-02 02:24:17'),
(270, 21, 'User logged in', '2025-05-06 05:20:39'),
(271, 21, 'admin updated the appointment date for submission ID: 6212713056517156913', '2025-05-06 06:13:57'),
(272, 21, 'admin updated the appointment date for submission ID: 6212713056517156913', '2025-05-06 06:23:18'),
(273, 21, 'admin updated the appointment date for submission ID: 6212713056517156913', '2025-05-06 06:32:36'),
(274, 21, 'admin updated the appointment date for submission ID: 6212713056517156913', '2025-05-06 06:32:54'),
(275, 21, 'admin updated the appointment date for submission ID: 6212713056517156914', '2025-05-06 07:04:28'),
(276, 21, 'User logged in', '2025-05-07 00:27:00'),
(277, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 02:39:16'),
(278, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 02:41:48'),
(279, 21, 'User logged out', '2025-05-07 02:43:41'),
(280, 21, 'User logged in', '2025-05-07 02:43:49'),
(281, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 02:47:13'),
(282, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 02:48:22'),
(283, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 02:50:22'),
(284, 21, 'admin updated the appointment date for submission ID: 6164492401216149357', '2025-05-07 02:51:00'),
(285, 21, 'admin updated the appointment date for submission ID: 6164492401216149357', '2025-05-07 03:03:16'),
(286, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 03:21:22'),
(287, 21, 'admin updated the appointment date for submission ID: 6164492401216149357', '2025-05-07 03:21:38'),
(288, 21, 'User logged out', '2025-05-07 03:22:53'),
(289, 21, 'User logged in', '2025-05-07 03:26:28'),
(290, 21, 'admin updated the appointment date for submission ID: 6164492401216149357', '2025-05-07 03:34:22'),
(291, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 03:34:37'),
(292, 21, 'admin updated the appointment date for submission ID: 6164492406912149357', '2025-05-07 03:37:16'),
(293, 21, 'User logged in', '2025-05-07 04:58:19'),
(294, 21, 'admin updated the appointment date for submission ID: 6164492401216149357', '2025-05-07 05:09:03'),
(295, 21, 'User logged out', '2025-05-07 05:11:32'),
(296, 21, 'User logged in', '2025-05-07 05:11:42'),
(297, 21, 'User logged in', '2025-05-07 05:15:08'),
(298, 21, 'admin updated the appointment date for submission ID: 6164492401216149357', '2025-05-07 05:15:34'),
(299, 21, 'User logged in', '2025-05-07 06:02:23'),
(300, 21, 'admin updated the appointment date for submission ID: 6212491250433206635', '2025-05-07 06:28:03'),
(301, 21, 'admin updated the appointment date for submission ID: 17440903196922', '2025-05-07 06:28:30'),
(302, 21, 'admin updated the appointment date for submission ID: 6212491250611206635', '2025-05-07 06:28:46'),
(303, 21, 'User logged in', '2025-05-08 00:07:14'),
(304, 21, 'admin updated the appointment date for submission ID: 6164502156518593682', '2025-05-08 00:34:38'),
(305, 21, 'User logged out', '2025-05-08 00:54:53'),
(306, 21, 'User logged in', '2025-05-14 02:01:47'),
(307, 21, 'User logged out', '2025-05-15 23:16:43'),
(308, 21, 'User logged out', '2025-05-15 23:18:14'),
(309, 23, 'User logged out', '2025-05-15 23:35:08'),
(310, 22, 'User logged out', '2025-05-15 23:35:20'),
(311, 21, 'User logged out', '2025-05-15 23:35:34'),
(312, 21, 'User logged out', '2025-05-15 23:35:43'),
(313, 21, 'User logged in', '2025-05-16 00:52:22'),
(314, 21, 'User logged in', '2025-05-16 00:58:42'),
(315, 21, 'User logged out', '2025-05-16 01:06:29'),
(316, 22, 'User logged out', '2025-05-16 01:14:24'),
(317, 21, 'User logged in', '2025-05-16 01:14:50'),
(318, 21, 'User logged out', '2025-05-16 01:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `video_ads`
--

CREATE TABLE `video_ads` (
  `id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video_ads`
--

INSERT INTO `video_ads` (`id`, `file_path`, `uploaded_at`) VALUES
(1, 'uploads/videos/video_67f34b87245704.38111235.mp4', '2025-04-07 03:50:31'),
(2, 'uploads/videos/video_67f4626c8c2933.45433015.mp4', '2025-04-07 23:40:28'),
(3, 'uploads/videos/video_67f46499769786.51550446.mp4', '2025-04-07 23:49:45'),
(4, 'uploads/videos/video_67f465476c82d9.90153850.mp4', '2025-04-07 23:52:39'),
(5, 'uploads/videos/video_67f759bd63e853.00757174.mp4', '2025-04-10 05:40:13'),
(6, 'uploads/videos/video_67f75db4abc993.67732610.mp4', '2025-04-10 05:57:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currently_serving`
--
ALTER TABLE `currently_serving`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `submission_id` (`submission_id`);

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
-- Indexes for table `video_ads`
--
ALTER TABLE `video_ads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `currently_serving`
--
ALTER TABLE `currently_serving`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

--
-- AUTO_INCREMENT for table `video_ads`
--
ALTER TABLE `video_ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `currently_serving`
--
ALTER TABLE `currently_serving`
  ADD CONSTRAINT `currently_serving_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `currently_serving_ibfk_2` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`submission_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
