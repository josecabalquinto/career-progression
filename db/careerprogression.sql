-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 11, 2025 at 11:43 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `careerprogression`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int NOT NULL,
  `city_code` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `barangay_code` varchar(255) NOT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `city_code`, `city`, `barangay_code`, `barangay`, `address_line1`) VALUES
(1, '064509000', 'City of Escalante', '064509002', 'Balintawak (Pob.)', 'Purok Dos'),
(2, '064505000', NULL, '064505017', NULL, 'Highway'),
(3, '064518000', 'Manapla', '064518009', 'Purisima', 'Highway');

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `graduation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`id`, `name`, `graduation_date`) VALUES
(6, '2020', '2020-04-30'),
(10, '2019', '2019-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`id`, `name`, `code`, `color`, `description`) VALUES
(1, 'Colleges of Information and Communication Technology and Engineering', 'CICTE', '#FF5733', 'College of Information and Communication Technology and Engineering'),
(17, 'College of Business Management', 'CBM', '#33FF57', 'Business Management'),
(18, 'College of Fisheries and Allied Sciences', 'CFAS', '#088708', NULL),
(19, 'College of Education', 'COED', '#2bd6ed', NULL),
(20, 'College of Criminal Justice Education', 'CCJE', '#9c2906', NULL),
(21, 'College of Arts and Sciences', 'CAS', '#eafa0a', NULL),
(22, 'College of Agriculture and Allied Sciences', 'CAAS', '#068f0f', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `college_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `college_id`, `name`, `code`, `description`) VALUES
(1, 1, 'Bachelor of Science in Information Technology', 'BSIT', 'Bachelor of Science in Information Technology'),
(4, 1, 'Bachelor of Science in Information System', 'BSIS', 'Information System'),
(7, 17, 'Bachelor of Science in Hotel Management', 'BSHM', 'Hotel Management'),
(8, 18, 'Bachelor of Science in Fisheries', 'BSF', 'Bachelor of Science in Fisheries'),
(9, 19, 'Bachelor in Physical Education', 'BPEd', 'Bachelor in Physical Education'),
(10, 19, 'Bachelor in Secondary Education', 'BSED', 'Bachelor in Secondary Education'),
(11, 19, 'Bachelor of Secondary Education Major in English', 'BSEd-English', 'Bachelor of Secondary Education Major in English'),
(12, 19, 'Bachelor of Secondary Education Major in Mathematics', 'BSEd-Mathematics', 'Bachelor of Secondary Education Major in Mathematics'),
(13, 19, 'Bachelor of Secondary Education Major in Science', 'BSEd-Science', 'Bachelor of Secondary Education Major in Science'),
(14, 19, 'Bachelor of Secondary Education major in Technology and Livelihood Education', 'BSEd-TLE', 'Bachelor of Secondary Education major in Technology and Livelihood Education'),
(15, 19, 'Bachelor of Technology and Livelihood Education Major in Agri-Fishery Arts', 'BTLEd-AFA', 'Bachelor of Technology and Livelihood Education Major in Agri-Fishery Arts'),
(16, 19, 'Bachelor of Technology and Livelihood Education Major in Home Economics', 'BTLEd-HE', 'Bachelor of Technology and Livelihood Education Major in Home Economics'),
(17, 19, 'Bachelor of Technology and Livelihood Education Major in Industrial Arts', 'BTLEd-IA', 'Bachelor of Technology and Livelihood Education Major in Industrial Arts'),
(18, 19, 'Bachelor of Technology and Livelihood Education Major in Information and Communications Technology', 'BTLEd-ICT', 'Bachelor of Technology and Livelihood Education Major in Information and Communications Technology'),
(19, 20, 'Bachelor of Science in Criminology', 'BSCrim', 'Bachelor of Science in Criminology'),
(20, 1, 'Bachelor of Library and Information Science', 'BLIS', 'Bachelor of Library and Information Science'),
(21, 1, 'Bachelor of Science in Entertainment and Multimedia Computing major in Animation Technology', 'BSEMC-A', 'Bachelor of Science in Entertainment and Multimedia Computing major in Animation Technology'),
(22, 21, 'Bachelor of Science in Biology', 'BSBIO', 'Bachelor of Science in Biology'),
(23, 21, 'Bachelor of Arts in English Language Studies', 'ABELS', 'Bachelor of Arts in English Language Studies'),
(24, 17, 'Bachelor of Public Administration', 'BPA', 'Bachelor of Public Administration'),
(25, 17, 'Bachelor of Science in Accounting Information Systems', 'BSAIS', 'Bachelor of Science in Accounting Information Systems'),
(26, 17, 'Bachelor of Science in Business Administration major in Financial Management', 'BSBA-FM', 'Bachelor of Science in Business Administration major in Financial Management'),
(27, 17, 'Bachelor of Science in Business Administration major in Marketing Management', 'BSBA-MM', 'Bachelor of Science in Business Administration major in Marketing Management'),
(28, 17, 'Bachelor of Science in Cooperative Management', 'BSCM', 'Bachelor of Science in Cooperative Management'),
(29, 17, 'Bachelor of Science in Tourism Management', 'BSTM', 'Bachelor of Science in Tourism Management'),
(30, 22, 'Bachelor of Science in Agribusiness', 'BSAB', 'Bachelor of Science in Agribusiness'),
(31, 22, 'Bachelor of Science in Agriculture', 'BSA', 'Bachelor of Science in Agriculture');

-- --------------------------------------------------------

--
-- Table structure for table `graduates`
--

CREATE TABLE `graduates` (
  `id` int NOT NULL,
  `batch_id` int NOT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `address` text,
  `address_id` int DEFAULT NULL,
  `college_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `profile_path` text,
  `employed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `graduates`
--

INSERT INTO `graduates` (`id`, `batch_id`, `prefix`, `suffix`, `first_name`, `middle_name`, `last_name`, `email`, `gender`, `number`, `address`, `address_id`, `college_id`, `course_id`, `profile_path`, `employed`) VALUES
(1, 10, '', '', 'Kevin', '', 'Durant', 'kd@gmail.com', 'male', '09564875521', 'Purok Dos, Balintawak (Pob.), City of Escalante', 1, 1, 1, 'graduate_1_1741172804.png', 1),
(2, 10, '', '', 'John', '', 'Doe', 'johndoe@gmail.com', 'male', '', 'Highway, Lemery, Calatrava', 2, 1, 1, 'graduate_2_1741172821.jpg', 1),
(3, 6, '', '', 'John', '', 'Smith', 'johnsmith@gmail.com', NULL, '', 'Highway, Purisima, Manapla', 3, 17, 7, 'graduate_3_1741172842.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fname`, `mname`, `lname`, `password`) VALUES
(1, 'admin', 'Admin', NULL, 'User', '$2y$10$aE6OC5wPslCiw3QsSlgNCe95XZui/7EsgYO3xWkHe/6Y8fiyJ8xmS'),
(2, 'staff1', 'Staff', NULL, 'User', '$2y$10$Rr9tRXuV7yW4cyEgrfBvG.F2594HCm1SfxWeJu5K5S/p0qAhlTQkW');

-- --------------------------------------------------------

--
-- Table structure for table `work_experiences`
--

CREATE TABLE `work_experiences` (
  `id` int NOT NULL,
  `graduate_id` int NOT NULL,
  `company` text NOT NULL,
  `position` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `id_copy` text,
  `coe_copy` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `work_experiences`
--

INSERT INTO `work_experiences` (`id`, `graduate_id`, `company`, `position`, `start_date`, `end_date`, `id_copy`, `coe_copy`) VALUES
(1, 3, 'Seda Hotel', 'Help Desk', '2025-03-11', NULL, NULL, NULL),
(2, 1, 'CoDev', 'Software Engineer', '2025-03-11', NULL, 'graduate_id_2_1741691587.png', 'graduate_coe_2_1741691435.jpg'),
(3, 1, 'PGNO', 'Computer Programmer I', '2025-03-11', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `graduates`
--
ALTER TABLE `graduates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `college_id` (`college_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `work_experiences`
--
ALTER TABLE `work_experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `graduate_id` (`graduate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `graduates`
--
ALTER TABLE `graduates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `work_experiences`
--
ALTER TABLE `work_experiences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `graduates`
--
ALTER TABLE `graduates`
  ADD CONSTRAINT `graduates_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `graduates_ibfk_2` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `graduates_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `graduates_ibfk_4` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `work_experiences`
--
ALTER TABLE `work_experiences`
  ADD CONSTRAINT `work_experiences_ibfk_1` FOREIGN KEY (`graduate_id`) REFERENCES `graduates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
