-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2026 at 02:47 AM
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
-- Database: `jogja.foodies`
--

-- --------------------------------------------------------

--
-- Table structure for table `makanan`
--

CREATE TABLE `makanan` (
  `id_makanan` int(11) NOT NULL,
  `nama_makanan` varchar(100) DEFAULT NULL,
  `deskripsi_makanan` text DEFAULT NULL,
  `harga_makanan` int(11) DEFAULT NULL,
  `lokasi_makanan` varchar(200) DEFAULT NULL,
  `rating_makanan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `makanan`
--

INSERT INTO `makanan` (`id_makanan`, `nama_makanan`, `deskripsi_makanan`, `harga_makanan`, `lokasi_makanan`, `rating_makanan`) VALUES
(1, 'Gudeg Yu Djum', 'hidangan tradisional Indonesia yang terbuat dari nangka rebus. Restoran ini telah menjadi andalan di kota ini selama lebih dari 70 tahun, menawarkan cita rasa unik yang menarik baik bagi penduduk lokal maupun wisatawan. Hidangan ini ditandai dengan rasa yang kaya dan mendalam dari nangka yang dimasak perlahan dan metode memasak kering yang digunakan, yang memusatkan rasa dan membuatnya lebih awet, sangat cocok sebagai oleh-oleh. Menu restoran ini mencakup berbagai topping seperti ayam, daging sapi, atau telur, menciptakan kombinasi rasa manis dan gurih yang tak terlupakan.', 50000, 'Jl. Kaliurang Km 4,5, Gg. Cokrowolo, Karangasem, Mbarek, Caturtunggal, Depok, Sleman, Yogyakarta', 4),
(2, 'Bakpia Pathok 25', 'kue tradisional khas Yogyakarta yang terbuat dari adonan tepung terigu berisi kacang hijau manis. Oleh-oleh khas Jogja ini sangat digemari wisatawan lokal maupun mancanegara. Kata Pathok berasal dari daerah asal pembuatan bakpia pertama kali di kawasan Pathuk, Yogyakarta. Seiring waktu, Bakpia Pathok mengalami inovasi isian, seperti coklat, keju, durian, hingga green tea, namun varian kacang hijau tetap menjadi favorit klasik.', 30000, 'Jl. Karel Sasuit Tubun No.65, Ngampilan, Kota Yogyakarta, Daerah Istimewa Yogyakarta', 5),
(3, 'Thiwul Ayu Mbok Sum', 'makanan tradisional khas Yogyakarta, khususnya wilayah Gunungkidul, yang terbuat dari gaplek (singkong kering) yang dihaluskan, dikukus, dan memiliki tekstur kenyal dengan aroma khas yang menggugah selera. Dahulu berfungsi sebagai makanan pokok pengganti nasi bagi masyarakat pedesaan, kini tiwul populer sebagai jajanan pasar yang disajikan manis dengan parutan kelapa dan gula jawa, atau dinikmati secara gurih sebagai pendamping lauk-pauk tradisional seperti sambal bawang dan ikan asin.', 10000, 'Jalan Mangunan KM.4.5, RT.15, Mangunan, Kec. Dlingo, Kabupaten Bantul, Daerah Istimewa Yogyakarta', 4);

-- --------------------------------------------------------

--
-- Table structure for table `minuman`
--

CREATE TABLE `minuman` (
  `id_minuman` int(11) NOT NULL,
  `nama_minuman` varchar(100) DEFAULT NULL,
  `deskripsi_minuman` text DEFAULT NULL,
  `harga_minuman` int(11) DEFAULT NULL,
  `lokasi_minuman` varchar(200) DEFAULT NULL,
  `rating_minuman` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `minuman`
--

INSERT INTO `minuman` (`id_minuman`, `nama_minuman`, `deskripsi_minuman`, `harga_minuman`, `lokasi_minuman`, `rating_minuman`) VALUES
(1, 'Cendol', 'Minuman tradisional khas Jawa yang terbuat dari tepung beras berbentuk bulir hijau, disajikan dengan santan, gula merah cair, dan es serut. Rasanya manis, gurih, dan segar, sangat cocok diminum saat cuaca panas. Cendol sering dijadikan minuman khas di berbagai acara tradisional maupun sebagai pelepas dahaga di siang hari.', 8000, 'Jl. Malioboro, Kota Yogyakarta, Daerah Istimewa Yogyakarta', 4),
(2, 'Ronde', 'Minuman hangat khas Yogyakarta yang berisi bola-bola ketan berisi kacang, disajikan dengan kuah jahe manis. Ronde dipercaya dapat menghangatkan tubuh, terutama saat malam hari. Kuah jahe yang pedas manis berpadu dengan bola ketan lembut menjadikan ronde sebagai minuman favorit di musim hujan.', 10000, 'Jl. Kauman, Alun-Alun Utara, Kota Yogyakarta, Daerah Istimewa Yogyakarta', 5),
(3, 'Wedang Uwuh', 'Minuman herbal khas Imogiri yang terbuat dari campuran rempah-rempah seperti jahe, kayu manis, cengkeh, dan daun pala kering. Wedang Uwuh memiliki rasa hangat, pedas manis, dan aroma rempah yang khas. Selain nikmat, minuman ini juga dipercaya memiliki khasiat kesehatan untuk menghangatkan tubuh dan meningkatkan daya tahan.', 12000, 'Imogiri, Bantul, Daerah Istimewa Yogyakarta', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `makanan`
--
ALTER TABLE `makanan`
  ADD PRIMARY KEY (`id_makanan`);

--
-- Indexes for table `minuman`
--
ALTER TABLE `minuman`
  ADD PRIMARY KEY (`id_minuman`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `makanan`
--
ALTER TABLE `makanan`
  MODIFY `id_makanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `minuman`
--
ALTER TABLE `minuman`
  MODIFY `id_minuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
