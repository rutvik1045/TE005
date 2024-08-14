-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2024 at 12:56 PM
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
-- Database: `te005`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_page`
--

CREATE TABLE `blog_page` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `tags` text NOT NULL,
  `category` text NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_page`
--

INSERT INTO `blog_page` (`id`, `title`, `description`, `tags`, `category`, `img`) VALUES
(5, 'Artificial Intelligence (AI) ', 'The field of artificial intelligence (AI) is rapidly evolving, with researchers and companies at the forefront of innovation pushing the boundaries of what is possible. OpenAI, a non-profit AI research company co-founded by Elon Musk, has emerged as a pow', '1,3', '1,2', 'Artificial-Intelligence-CPU-Technology.jpg'),
(10, 'Artificial Intelligence ', 'The field of artificial intelligence (AI) is rapidly evolving, with researchers and companies at the forefront of innovation pushing the boundaries of what is possible. OpenAI, a non-profit AI research company co-founded by Elon Musk, has emerged as a pow', '3,4', '2,4', 'ai2.jpeg'),
(30, 'Earth', 'Earth is a vibrant planet with diverse life and ecosystems. Its beauty and complexity make it a unique home in the universe.', '5,6', '1', 'b1.jpeg'),
(33, 'InfoWorld', 'InfoWorld, a premier information technology media outlet founded in 1978, has transitioned from its origins as a monthly magazine to a dynamic online platform. It is a subsidiary of International Data Group (IDG), and its coverage spans software developme', '3,6', '3,5', 'img1.jpg'),
(46, 'Bangladesh', 'Bangladesh police allegedly shot dead 30 people in an incident of mass killing on Tuesday (August 6), as per exclusive footage accessed by CNN-News18, a day after Sheikh Hasina’s resignation and departure from the country amid violent protests.\r\n\r\nAccordi', '1', '3', 'bag1.jpg'),
(47, 'Virtual Reality (VR) 2.0', 'Enhanced VR technologies are offering more immersive and realistic experiences. With improvements in display resolutions, motion tracking, and interactive elements, VR is becoming increasingly prevalent in gaming, training, and therapeutic contexts. New V', '5,6', '2,4', 'vr1.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_page`
--
ALTER TABLE `blog_page`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_page`
--
ALTER TABLE `blog_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
