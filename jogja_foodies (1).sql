-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 07:40 AM
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
-- Database: `jogja_foodies`
--

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `name`, `description`, `price`, `image`, `category`, `location`, `rating`, `created_at`) VALUES
(1, 'Bakpia', 'Bakpia khas Jogja isian kacang hijau manis legit, kulit tipis dan renyah', 25000.00, NULL, 'Makanan Ringan', 'Jl. Pathok, Bantul', 4.8, '2026-05-08 03:49:29'),
(2, 'Cendol', 'Minuman segar dengan cendol hijau, santan kelapa, dan gula aren cair', 10000.00, NULL, 'Minuman', 'Malioboro', 4.6, '2026-05-08 03:49:29'),
(3, 'Gudeg', 'Gudeg legendaris khas Jogja, nasi dengan sayur nangka muda yang dimasak santan', 35000.00, NULL, 'Makanan Berat', 'Jl. Kaliurang KM 4.5', 4.9, '2026-05-08 03:49:29'),
(4, 'Thiwul', 'Makanan tradisional dari Gunung Kidul, terbuat dari singkong yang diolah seperti beras', 15000.00, NULL, 'Makanan Berat', 'Gunung Kidul', 4.5, '2026-05-08 03:49:29'),
(5, 'Ronde', 'Minuman wedang jahe hangat khas Jogja, cocok dinikmati saat malam hari', 8000.00, NULL, 'Minuman', 'Pasar Beringharjo', 4.7, '2026-05-08 03:49:29'),
(6, 'Wedang Uwuh', 'Minuman rempah khas Jogja dengan campuran jahe, kayu secang, dan rempah pilihan', 12000.00, NULL, 'Minuman', 'Kotagede', 4.8, '2026-05-08 03:49:29'),
(7, 'Sate Klathak', 'kuliner khas kambing muda dari Pleret, Bantul, Yogyakarta, yang terkenal unik karena ditusuk menggunakan jeruji besi sepeda dan hanya dibumbui garam. Berbeda dengan sate umumnya, sate klathak tidak menggunakan kecap atau bumbu kacang, melainkan disajikan dengan kuah gulai encer yang gurih', 30000.00, NULL, 'Makanan Berat', 'Jl. Sultan Agung No.18, Jejeran II, Wonokromo, Kec. Pleret, Kabupaten Bantul, Yogyakarta', 4.7, '2026-05-08 13:58:42'),
(8, 'Ayam Bakar Artomoro', 'kuliner legendaris di Yogyakarta yang terkenal dengan ayam kampung pedas manis berukuran besar, bumbu meresap hingga ke tulang, dan proses masak 5 jam', 25000.00, NULL, 'Makanan Berat', 'Jl. Palagan Tentara Pelajar Km.7,8 No.30s, Karang Moko, Sariharjo, Sleman, Yogyakarta.', 4.9, '2026-05-08 14:02:55');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_item` int(11) NOT NULL,
  `delivery_fee` int(11) DEFAULT 0,
  `total_amount` int(11) NOT NULL,
  `delivery_method` enum('pickup','delivery') DEFAULT 'pickup',
  `payment_method` enum('cash','qris','transfer') DEFAULT 'cash',
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','processing','completed','cancelled') DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `food_id`, `food_name`, `quantity`, `price_per_item`, `delivery_fee`, `total_amount`, `delivery_method`, `payment_method`, `notes`, `status`, `order_date`, `created_at`) VALUES
(4, 'JGF-20260508-E530DB', 5, 1, 'Bakpia', 2, 25000, 0, 50000, 'pickup', 'qris', '', 'pending', '2026-05-08 06:27:58', '2026-05-08 13:27:58'),
(5, 'JGF-20260508-805C6A', 5, 3, 'Gudeg', 2, 35000, 10000, 80000, 'delivery', 'transfer', 'kalala', 'pending', '2026-05-08 06:28:24', '2026-05-08 13:28:24'),
(6, 'JGF-20260508-41FB51', 5, 3, 'Gudeg', 1, 35000, 0, 35000, 'pickup', 'cash', '', 'pending', '2026-05-08 14:55:16', '2026-05-08 21:55:16'),
(7, 'JGF-20260508-035B76', 5, 7, 'Sate Klathak', 1, 30000, 0, 30000, 'pickup', 'cash', '', 'pending', '2026-05-08 14:56:16', '2026-05-08 21:56:16'),
(8, 'JGF-20260508-F809DC', 5, 7, 'Sate Klathak', 1, 30000, 0, 30000, 'pickup', 'cash', '', 'pending', '2026-05-08 14:57:19', '2026-05-08 21:57:19'),
(9, 'JGF-20260508-E1B67B', 5, 7, 'Sate Klathak', 1, 30000, 0, 30000, 'pickup', 'cash', '', 'pending', '2026-05-08 14:59:10', '2026-05-08 21:59:10'),
(10, 'JGF-20260508-E29BC2', 5, 1, 'Bakpia', 1, 25000, 0, 25000, 'pickup', 'cash', '', 'pending', '2026-05-08 15:01:34', '2026-05-08 22:01:34'),
(11, 'JGF-20260508-B43EEF', 5, 7, 'Sate Klathak', 1, 30000, 0, 30000, 'pickup', 'cash', '', 'pending', '2026-05-08 15:20:27', '2026-05-08 22:20:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `avatar`, `role`, `created_at`) VALUES
(5, 'Callioz', 'lalala@email.com', '123456', 'Adeline Callista', 'default-avatar.png', 'user', '2026-05-08 06:23:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
