-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 09:21 AM
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
-- Database: `online e-book`
--

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE `competitions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reward` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('active','upcoming') NOT NULL DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competitions`
--

INSERT INTO `competitions` (`id`, `title`, `description`, `reward`, `deadline`, `status`, `created_at`) VALUES
(1, 'Annual Essay Competition', 'Ready for a surprise? Once you click the start button, our system will instantly assign you a random hidden topic.\r\n', 'Rs. 5,000 Instant Wallet Cash + Publication Badge', '2026-12-31', 'active', '2026-06-09 10:31:26'),
(2, 'The Future of AI in Creative Literature', 'Write an original 2000-word essay or short fiction exploring how Artificial Intelligence will reshape the world of storytelling by the year 2030.', 'Rs. 25,000 Cash Prize', '2026-06-18', 'active', '2026-06-09 10:54:14'),
(3, 'Echoes of Midnight: Mystery Short Story Contest', 'nleash your inner thrill writer! Craft a suspenseful mystery short story that begins with a clock striking midnight.', 'Premium Kindle E-Reader', '2026-06-19', 'active', '2026-06-09 10:55:23');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `competition_type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `user_id`, `competition_id`, `user_name`, `competition_type`, `title`, `content`, `file_path`, `created_at`) VALUES
(9, 1, 0, 'Ayan Ahmed', 'essay', 'Annual Essay Submission', '// --- DYNAMIC CHARACTER COUNTER FOR ESSAY ---\r\nvar essayTextarea = document.getElementById(\'comp-text\');\r\nif (essayTextarea) {\r\n    essayTextarea.addEventListener(\'input\', function() {\r\n        var charCount = this.value.length;\r\n        document.getElementById(\'char-count\').innerText = charCount;\r\n    });\r\n}// --- DYNAMIC CHARACTER COUNTER FOR ESSAY ---\r\nvar essayTextarea = document.getElementById(\'comp-text\');\r\nif (essayTextarea) {\r\n    essayTextarea.addEventListener(\'input\', function() {\r\n        var charCount = this.value.length;\r\n        document.getElementById(\'char-count\').innerText = charCount;\r\n    });\r\n}// --- DYNAMIC CHARACTER COUNTER FOR ESSAY ---\r\nvar essayTextarea = document.getElementById(\'comp-text\');\r\nif (essayTextarea) {\r\n    essayTextarea.addEventListener(\'input\', function() {\r\n        var charCount = this.value.length;\r\n        document.getElementById(\'char-count\').innerText = charCount;\r\n    });\r\n}// --- DYNAMIC CHARACTER COUNTER FOR ESSAY ---\r\nvar essayTextarea = document.getElementById(\'comp-text\');\r\nif (essayTextarea) {\r\n    essayTextarea.addEventListener(\'input\', function() {\r\n        var charCount = this.value.length;\r\n        document.getElementById(\'char-count\').innerText = charCount;\r\n    });\r\n}// --- DYNAMIC CHARACTER COUNTER FOR ESSAY ---\r\nvar essayTextarea = document.getElementById(\'comp-text\');\r\nif (essayTextarea) {\r\n    essayTextarea.addEventListener(\'input\', function() {\r\n        var charCount = this.value.length;\r\n        document.getElementById(\'char-count\').innerText = charCount;\r\n    });\r\n}', '', '2026-06-01 06:34:18'),
(10, 1, 0, 'ayan ahmed', 'story', 'hellow', '<?php if (isset($_SESSION[\'name\'])): ?>\r\n    <?php if ($already_submitted_story): ?>\r\n        <a href=\"javascript:void(0)\" class=\"comp-btn\" \r\n           style=\"background-color:#2b9348; color:#ffffff; cursor:not-allowed;\">\r\n            Submitted Successfully!\r\n        </a>\r\n    <?php else: ?>\r\n        <a href=\"javascript:void(0)\" \r\n           class=\"comp-btn btn-primary participate-story-btn\" \r\n           data-id=\"<?php echo $row[\'id\']; ?>\"\r\n           data-title=\"<?php echo htmlspecialchars($row[\'title\']); ?>\">\r\n            Participate Now <i class=\"fa-solid fa-arrow-right-long\"></i>\r\n        </a>\r\n    <?php endif; ?>\r\n<?php else: ?>\r\n    <a href=\"login.php\" class=\"comp-btn btn-primary\">\r\n        Login to Participate <i class=\"fa-solid fa-arrow-right-long\"></i>\r\n    </a>\r\n<?php endif; ?>', '', '2026-06-09 12:08:38'),
(11, 1, 0, 'ayan ahmed', 'story', 'the shadow combat', '// Database checks for both competitions\r\nif (!empty($user_name) && !empty($user_id)) {\r\n    $safe_user_id = mysqli_real_escape_string($conn, $user_id);\r\n\r\n    // 1. Check Essay Submission\r\n    $check_essay = \"SELECT id FROM submissions WHERE user_id = \'$safe_user_id\' AND competition_type = \'essay\'\";\r\n    $essay_result = mysqli_query($conn, $check_essay);\r\n    if (mysqli_num_rows($essay_result) > 0) {\r\n        $already_submitted = true;\r\n    }\r\n\r\n    // 2. Story check ab while loop mein hoga, yahan sirf variable initialize karo\r\n    $already_submitted_story = false;\r\n}', '', '2026-06-09 12:20:00'),
(12, 1, 0, 'ayan ahmed', 'story', 'the shadow combat', '$competition_id = mysqli_real_escape_string($conn, $_POST[\'competition_id\']);\r\n\r\n$insert_query = \"INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) \r\n                 VALUES (\'$user_id\', \'$safe_user_name\', \'$competition_type\', \'$competition_id\', \'$title\', \'$content\', \'$file_path\')\";', '', '2026-06-09 12:25:06'),
(13, 1, 0, 'ayan ahmed', 'story', 'the shadow combat', '$competition_id = mysqli_real_escape_string($conn, $_POST[\'competition_id\']);$competition_id = mysqli_real_escape_string($conn, $_POST[\'competition_id\']);', '', '2026-06-09 12:29:19'),
(14, 1, 3, 'ayan ahmed', 'story', 'hellow', '$insert_query = \"INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) \r\n                 VALUES (\'$user_id\', \'$safe_user_name\', \'$competition_type\', \'$competition_id\', \'$title\', \'$content\', \'$file_path\')\";$insert_query = \"INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) \r\n                 VALUES (\'$user_id\', \'$safe_user_name\', \'$competition_type\', \'$competition_id\', \'$title\', \'$content\', \'$file_path\')\";$insert_query = \"INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) \r\n                 VALUES (\'$user_id\', \'$safe_user_name\', \'$competition_type\', \'$competition_id\', \'$title\', \'$content\', \'$file_path\')\";', '', '2026-06-09 12:31:46'),
(15, 1, 2, 'ayan ahmed', 'story', 'the shadow combat', '$insert_query = \"INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) \r\n                 VALUES (\'$user_id\', \'$safe_user_name\', \'$competition_type\', \'$competition_id\', \'$title\', \'$content\', \'$file_path\')\";$insert_query = \"INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) \r\n                 VALUES (\'$user_id\', \'$safe_user_name\', \'$competition_type\', \'$competition_id\', \'$title\', \'$content\', \'$file_path\')\";$insert_query = \"INSERT INTO submissions (user_id, user_name, competition_type, competition_id, title, content, file_path) \r\n                 VALUES (\'$user_id\', \'$safe_user_name\', \'$competition_type\', \'$competition_id\', \'$title\', \'$content\', \'$file_path\')\";', '', '2026-06-09 12:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `created_at`, `role`) VALUES
(1, 'ayan ahmed', 'ayandev49503@gmail.com', '$2y$10$Dre0iqL.3QmAJJXcgPrglulZkO4yhohPyfl5dRnDEOSj0ZUIqUkgu', '', 'pakistan, karachi, malir', '2026-05-28 20:37:18', 'admin'),
(2, 'm fasih', 'fasih47@gmail.com', '$2y$10$peNvsgPE2YbfZ86yELzyD.aK/zvlQMYcnJxYvfANi50qyq49cfGJ6', '03700321926', 'khi pakistan', '2026-06-01 07:08:52', 'user'),
(3, 'ahmed', 'ahmed@gmail.com', '$2y$10$u0ql1nFceY0TYsgYUGzgQugFzTjWCvlTiJzTwLF4mKcQ6/HPgtzQ2', '03138145366', 'pakistan, karachi, malir', '2026-06-06 08:47:58', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
