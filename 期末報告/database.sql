-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- 主機： sql303.infinityfree.com
-- 產生時間： 2026 年 06 月 25 日 11:12
-- 伺服器版本： 11.4.12-MariaDB
-- PHP 版本： 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `if0_41808875_sql`
--

-- --------------------------------------------------------

--
-- 資料表結構 `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_at`) VALUES
(1, '注意言行，美好健康', '大家好，今天又是美好的一天', '2026-05-22 02:13:43'),
(2, '維護', '7?9', '2026-06-25 04:35:52');

-- --------------------------------------------------------

--
-- 資料表結構 `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `tutor_request_id` int(11) NOT NULL,
  `tutor_user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `applications`
--

INSERT INTO `applications` (`id`, `tutor_request_id`, `tutor_user_id`, `created_at`) VALUES
(1, 2, 2, '2026-05-07 22:16:03'),
(2, 2, 9, '2026-06-25 00:15:13'),
(3, 2, 13, '2026-06-25 04:31:12');

-- --------------------------------------------------------

--
-- 資料表結構 `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `tutor_id`, `created_at`) VALUES
(1, 2, 1, '2026-05-15 01:42:56'),
(2, 9, 2, '2026-06-25 00:11:19'),
(3, 12, 2, '2026-06-25 04:11:48');

-- --------------------------------------------------------

--
-- 資料表結構 `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `phone`, `address`, `gender`, `birthday`, `bio`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 2, '0800000000', '桃園', '女', '2026-05-22', '你好', NULL, '2026-05-22 02:42:07', '2026-05-22 02:42:07'),
(2, 9, '0928111222', '台北市', '男', '2001-06-12', '喜歡吃哈密瓜', 'uploads/avatar_9_1782371450.png', '2026-06-25 00:10:50', '2026-06-25 00:10:50'),
(3, 12, '06222555', '台南市中西區', '男', '2013-01-25', '我數學很差 需要協助', 'uploads/avatar_12_1782386360.png', '2026-06-25 04:16:17', '2026-06-25 04:19:20'),
(4, 13, '09785956', '桃園區', '女', '2026-01-29', '數學很厲害', 'uploads/avatar_13_1782387271.png', '2026-06-25 04:34:31', '2026-06-25 04:34:31');

-- --------------------------------------------------------

--
-- 資料表結構 `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `created_at`) VALUES
(1, '數學', '2026-05-15 02:13:08'),
(2, '國文', '2026-05-15 02:13:12'),
(3, '英文', '2026-05-15 02:13:15'),
(4, '程式設計', '2026-05-15 02:13:18'),
(5, '工程數學', '2026-06-25 04:37:04');

-- --------------------------------------------------------

--
-- 資料表結構 `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `target` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `hourly_rate` int(11) NOT NULL,
  `online` varchar(20) NOT NULL,
  `intro` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT '待審核'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `tutors`
--

INSERT INTO `tutors` (`id`, `user_id`, `name`, `area`, `target`, `subject`, `hourly_rate`, `online`, `intro`, `created_at`, `status`) VALUES
(1, 2, 'lulu', '高雄市楠梓區', '國中', '英文', 400, '可以', '我很聰明\r\n', '2026-05-02 00:03:40', '已通過'),
(2, 9, '陳冠智', '台北市信義區', '高中', '數學', 300, '可以', '紐約西北大學數學系畢業', '2026-06-25 00:08:12', '已通過'),
(3, 13, 'teacher', ' 花蓮市西區', '大學', '國文', 450, '不可以', '美洲大學念書\r\n有多益證照', '2026-06-25 04:26:36', '已通過');

-- --------------------------------------------------------

--
-- 資料表結構 `tutor_requests`
--

CREATE TABLE `tutor_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `target` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `hourly_rate` int(11) DEFAULT NULL,
  `online` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT '待審核',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `tutor_requests`
--

INSERT INTO `tutor_requests` (`id`, `user_id`, `area`, `target`, `subject`, `hourly_rate`, `online`, `description`, `status`, `created_at`) VALUES
(1, 2, '高雄市楠梓區', '高中', '英文', 400, '可以', '我很棒', '已通過', '2026-05-15 01:57:52'),
(2, 9, '台北市信義區', '國小', '程式設計', 200, '可以', 'e8 e8 ', '已通過', '2026-06-25 00:13:57'),
(3, 12, '台北市信義區', '高中', '程式設計', 220, '可以', '希望老師可以在咖啡廳授課', '已通過', '2026-06-25 04:01:28');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','tutor','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT '正常'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'eason', 'eason@gmail.com', '12345', 'admin', '2026-05-01 23:14:39', '正常'),
(2, 'lulu', 'lulu@gmail.com', '12345', 'user', '2026-05-01 23:14:39', '停權'),
(7, 'hoho', 'hoho@gmail.com', '12345', 'user', '2026-05-01 23:24:36', '正常'),
(8, 'jam', 'jam@gmail.com', '12345', 'user', '2026-06-16 01:19:22', '正常'),
(9, '陳冠智', 'a1121713@mail.nuk.edu.tw', 'd123276926', 'user', '2026-06-24 10:30:05', '正常'),
(10, '小名', 'a113@gmail.com', '12345', 'user', '2026-06-25 03:46:18', '正常'),
(11, 'frank', 'apple@gmail.com', '12345', 'user', '2026-06-25 03:47:47', '正常'),
(12, 'guava', 'guava@gmail.com', '12345', 'user', '2026-06-25 03:55:30', '正常'),
(13, 'teacher', 'teacher@gmail.com', '12345', 'user', '2026-06-25 04:24:35', '正常');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`tutor_id`);

--
-- 資料表索引 `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_profile` (`user_id`);

--
-- 資料表索引 `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `tutor_requests`
--
ALTER TABLE `tutor_requests`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `tutor_requests`
--
ALTER TABLE `tutor_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
