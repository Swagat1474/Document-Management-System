-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2024 at 11:16 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `student-registeration`
--

CREATE TABLE `student-registeration` (
  `sno` int(11) NOT NULL,
  `fname` varchar(11) NOT NULL,
  `lname` varchar(11) NOT NULL,
  `fathername` varchar(20) NOT NULL,
  `Mothername` varchar(20) NOT NULL,
  `CRN` int(7) NOT NULL,
  `URN` int(7) NOT NULL,
  `course` varchar(10) NOT NULL,
  `department` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `current_stage` VARCHAR(50) DEFAULT 'Teacher',
  `dt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student-registeration`
--

INSERT INTO `student-registeration` (`sno`, `fname`, `lname`, `fathername`, `Mothername`, `CRN`, `URN`, `course`, `department`, `email`, `status`, `dt`) VALUES
(20, 'Ranveer', 'Singh', 'father', 'mother', 2121153, 2203932, 'B.tech', 'IT', 'Ranveer@gmail.com', 'rejected', '2024-04-12 10:31:49'),
(22, 'keshav', 'bubber', 'father', 'mother', 22112211, 22002200, 'B.tech', 'IT', 'keshav@gmail.com', 'approved', '2024-04-12 12:30:18'),
(24, 'gourav', 'gourav', 'father', 'mother', 212122, 212155, 'B.tech', 'it', 'gourav@gmail.com', 'approved', '2024-04-12 16:14:13'),
(25, 'Harjot', 'Singh', 'father', 'mother', 22022333, 22550022, 'B.tech', 'it', 'Harjot@gmail.com', 'approved', '2024-04-15 10:13:28'),
(27, 'harsh', 'singh', 'father', 'mother', 2121444, 2121156, 'B.TECH', 'INFORMATIO', 'harsh@gmail.com', 'pending', '2024-04-15 14:27:48'),
(28, 'swagat', ' ', 'father', 'mother', 212156, 2203598, 'B.TECH', 'COMPUTER S', 'swagat@gmail.com', 'approved', '2024-04-15 14:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `s.no` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `branch` varchar(30) NOT NULL,
  `course` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(260) NOT NULL,
  `dt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`s.no`, `name`, `branch`, `course`, `email`, `username`, `password`, `dt`) VALUES
(10, 'Admin', 'b.tech', 'admin', 'admin@gmail.com', 'admin', '$2y$10$I86MokvpbIxN2jbEw257WebnBvX815dLDpmfOot.rEhaAe3pHUKA6', '2024-04-12 11:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `s.no` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `urn` int(10) NOT NULL,
  `password` varchar(260) NOT NULL,
  `dt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`s.no`, `name`, `urn`, `password`, `dt`) VALUES
(8, 'Ranveer', 2203932, '$2y$10$PzFR25KAmNqOzavhED3E6eD7tFD52aIutA/YJCjsk1qETVHjOaDdy', '2024-04-11 17:02:37'),
(9, 'Vishu', 22002233, '$2y$10$P86/oe3R7bOfH2OwfkVA2eRlW6mkvTjwcrmsb21Xhu.isST.Ge6pq', '2024-04-11 18:32:26'),
(10, 'Keshav', 2203914, '$2y$10$Nq8r7hEl38QUkYVoVg7j4.NS8OJNWbt0DyL3blE6PyAan28Yrdjly', '2024-04-12 10:09:13'),
(12, 'gourav', 2121155, '$2y$10$67P6CMoPy6qqwXZLDPzfk.R7GDnUUR0hZbA980zfrxvqrhzeJe6Ke', '2024-04-12 16:12:32'),
(13, 'Harjot', 2202266, '$2y$10$fZCrMXWnG8I8K2.YWunmMue9iz3QZBs0DFsMW3Oo5oQbhN.C2ma7O', '2024-04-15 10:09:37'),
(14, 'harman', 22334455, '$2y$10$2yDYFzBV42JFBLBoBxDZ9.uY8q/p/MY1JCZ4Bb2wLIly/qfW90bjG', '2024-04-15 12:18:06'),
(16, 'harsh', 2121156, '$2y$10$e2BQw692cKDFX0tWCjY0GO243lFpYyb9kNRdTxCHQfI3fAowgow.6', '2024-04-15 14:26:40'),
(17, 'swagat', 2203598, '$2y$10$nngrWdavxQ3LtjZKEfFWhOpwQU3vbdrqJ1WTn9JVj2f4wapMwLoGm', '2024-04-15 14:35:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student-registeration`
--
ALTER TABLE `student-registeration`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `URN` (`URN`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`s.no`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`s.no`),
  ADD UNIQUE KEY `urn` (`urn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student-registeration`
--
ALTER TABLE `student-registeration`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `s.no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `s.no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE `student-registeration` ADD `file_path` VARCHAR(255) NULL;


ALTER TABLE teacher ADD COLUMN role VARCHAR(50) NOT NULL DEFAULT 'Teacher';



ALTER TABLE `student-registeration` ADD COLUMN `rejection_comment` TEXT DEFAULT NULL;
