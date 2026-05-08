-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Bulan Mei 2026 pada 08.02
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.0

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
-- Struktur dari tabel `foods`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `foods`
--

INSERT INTO `foods` (`id`, `name`, `description`, `price`, `image`, `category`, `location`, `rating`, `created_at`) VALUES
(1, 'Bakpia', 'Bakpia khas Jogja isian kacang hijau manis legit, kulit tipis dan renyah', '25000.00', NULL, 'Makanan Ringan', 'Jl. Pathok, Bantul', '4.8', '2026-05-08 03:49:29'),
(2, 'Cendol', 'Minuman segar dengan cendol hijau, santan kelapa, dan gula aren cair', '10000.00', NULL, 'Minuman', 'Malioboro', '4.6', '2026-05-08 03:49:29'),
(3, 'Gudeg', 'Gudeg legendaris khas Jogja, nasi dengan sayur nangka muda yang dimasak santan', '35000.00', NULL, 'Makanan Berat', 'Jl. Kaliurang KM 4.5', '4.9', '2026-05-08 03:49:29'),
(4, 'Thiwul', 'Makanan tradisional dari Gunung Kidul, terbuat dari singkong yang diolah seperti beras', '15000.00', NULL, 'Makanan Berat', 'Gunung Kidul', '4.5', '2026-05-08 03:49:29'),
(5, 'Wedang', 'Minuman wedang jahe hangat khas Jogja, cocok dinikmati saat malam hari', '8000.00', NULL, 'Minuman', 'Pasar Beringharjo', '4.7', '2026-05-08 03:49:29'),
(6, 'Wedang Uwuh', 'Minuman rempah khas Jogja dengan campuran jahe, kayu secang, dan rempah pilihan', '12000.00', NULL, 'Minuman', 'Kotagede', '4.8', '2026-05-08 03:49:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
