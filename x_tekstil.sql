-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 15 Haz 2025, 16:24:04
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `x_tekstil`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `alpaka_kumas`
--

CREATE TABLE `alpaka_kumas` (
  `id` int(11) NOT NULL,
  `tarih` date NOT NULL,
  `kumas_cinsi` varchar(100) NOT NULL,
  `renk` varchar(50) NOT NULL,
  `metre` decimal(10,2) NOT NULL,
  `en_mt` decimal(10,2) NOT NULL,
  `gr_metre` decimal(10,2) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `alpaka_kumas`
--

INSERT INTO `alpaka_kumas` (`id`, `tarih`, `kumas_cinsi`, `renk`, `metre`, `en_mt`, `gr_metre`, `aciklama`, `user_id`) VALUES
(2, '2025-06-15', 'Polyester Alpaka', 'Mavi', 2125.00, 1.50, 220.00, '', 3),
(3, '2025-06-15', 'Polyester Alpaka', 'Kırmızı', 2125.00, 1.50, 220.00, '', 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `boya`
--

CREATE TABLE `boya` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tarih` date NOT NULL,
  `kumas_cinsleri_id` int(11) NOT NULL,
  `miktar_mt` float NOT NULL,
  `renk` varchar(50) NOT NULL,
  `ucret_tl` float NOT NULL,
  `aciklama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `boya`
--

INSERT INTO `boya` (`id`, `user_id`, `tarih`, `kumas_cinsleri_id`, `miktar_mt`, `renk`, `ucret_tl`, `aciklama`) VALUES
(9, 3, '2025-06-15', 6, 2125, 'Mavi', 5, ''),
(10, 3, '2025-06-15', 6, 2125, 'Siyah', 5, ''),
(11, 3, '2025-06-15', 6, 2125, 'Lacivert', 5, ''),
(12, 3, '2025-06-15', 6, 2125, 'Kırmızı', 5, '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cig_kumas`
--

CREATE TABLE `cig_kumas` (
  `id` int(11) NOT NULL,
  `tarih` date NOT NULL,
  `kumas_cinsi` varchar(100) NOT NULL,
  `metre` decimal(10,2) NOT NULL,
  `en_mt` decimal(10,2) NOT NULL,
  `gr_metre` decimal(10,2) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `cig_kumas`
--

INSERT INTO `cig_kumas` (`id`, `tarih`, `kumas_cinsi`, `metre`, `en_mt`, `gr_metre`, `aciklama`, `user_id`) VALUES
(3, '2025-06-15', 'Polyester Alpaka', 6000.00, 1.60, 190.00, '', 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `dokuma`
--

CREATE TABLE `dokuma` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tarih` date NOT NULL,
  `iplik_id` int(11) NOT NULL,
  `cozgu_iplik_id` int(11) NOT NULL,
  `kumas_cinsleri_id` int(11) NOT NULL,
  `miktar_kg` float NOT NULL,
  `ucret_tl` float NOT NULL,
  `aciklama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `dokuma`
--

INSERT INTO `dokuma` (`id`, `user_id`, `tarih`, `iplik_id`, `cozgu_iplik_id`, `kumas_cinsleri_id`, `miktar_kg`, `ucret_tl`, `aciklama`) VALUES
(13, 3, '2025-06-15', 7, 8, 6, 3000, 5, 'Atki 2000kg - Cozgu 1000 kg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iplik`
--

CREATE TABLE `iplik` (
  `id` int(11) NOT NULL,
  `tarih` date NOT NULL,
  `iplik_turu` varchar(50) NOT NULL,
  `iplik_rengi` varchar(50) NOT NULL,
  `kilo` decimal(10,2) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `iplik`
--

INSERT INTO `iplik` (`id`, `tarih`, `iplik_turu`, `iplik_rengi`, `kilo`, `aciklama`, `user_id`) VALUES
(8, '2025-06-15', '150 Denye Polyester İplik', 'Beyaz', 1000.00, '125 gram/metretül 2.1$', 3),
(9, '2025-06-15', '30/1 Kesik Elyaf İplik', 'Beyaz', 1000.00, '95 gram/metretül 2.8$', 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iplik_alim`
--

CREATE TABLE `iplik_alim` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tarih` date NOT NULL,
  `iplik_turu` varchar(50) NOT NULL,
  `miktar_kg` float NOT NULL,
  `fiyat_tl` float NOT NULL,
  `aciklama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `iplik_alim`
--

INSERT INTO `iplik_alim` (`id`, `user_id`, `tarih`, `iplik_turu`, `miktar_kg`, `fiyat_tl`, `aciklama`) VALUES
(7, 3, '2025-06-15', '150 Denye Polyester İplik', 3000, 6300, '125 gram/metretül\r\n2.1$'),
(8, 3, '2025-06-15', '30/1 Kesik Elyaf İplik', 2000, 5600, '95 gram/metretül\r\n2.8$');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kristal_saten_kumas`
--

CREATE TABLE `kristal_saten_kumas` (
  `id` int(11) NOT NULL,
  `tarih` date NOT NULL,
  `kumas_cinsi` varchar(100) NOT NULL,
  `renk` varchar(50) NOT NULL,
  `metre` decimal(10,2) NOT NULL,
  `en_mt` decimal(10,2) NOT NULL,
  `gr_metre` decimal(10,2) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kristal_saten_kumas`
--

INSERT INTO `kristal_saten_kumas` (`id`, `tarih`, `kumas_cinsi`, `renk`, `metre`, `en_mt`, `gr_metre`, `aciklama`, `user_id`) VALUES
(1, '2025-06-15', 'Kristal Saten Kumaş', 'Yeşil', 1000.00, 1.50, 220.00, '', 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kumas_cinsleri`
--

CREATE TABLE `kumas_cinsleri` (
  `id` int(11) NOT NULL,
  `kumas_cinsi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kumas_cinsleri`
--

INSERT INTO `kumas_cinsleri` (`id`, `kumas_cinsi`) VALUES
(6, 'Polyester Alpaka'),
(7, '%100 Pamuk Zefir'),
(8, '%50 Pamuk & %50 Polyester Zefir'),
(10, 'Kristal Saten Kumaş');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `satis`
--

CREATE TABLE `satis` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tarih` date NOT NULL,
  `kumas_cinsleri_id` int(11) NOT NULL,
  `renk` varchar(50) NOT NULL,
  `miktar_mt` float NOT NULL,
  `fiyat_tl` float NOT NULL,
  `aciklama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `satis`
--

INSERT INTO `satis` (`id`, `user_id`, `tarih`, `kumas_cinsleri_id`, `renk`, `miktar_mt`, `fiyat_tl`, `aciklama`) VALUES
(10, 3, '2025-06-15', 6, 'Siyah', 2125, 5, 'Kardesler Tekstil LTD STI'),
(11, 3, '2025-06-15', 6, 'Kırmızı', 2125, 5, 'Kardesler Tekstil LTD STI');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `rol`) VALUES
(3, 'admin123', '$2y$10$k/82vaZeBt2HwHRjiEYPXuRyc2E5huQ0phb8gt4g93Y7NyW8NlOA.', '2025-06-14 13:41:49', 'admin'),
(4, 'eren', '$2y$10$vWKlJaYUXBuHS4kcDtBlHu3/y3eWDLCNc2dhUvoC5ZRXBDnV9ftBy', '2025-06-15 12:41:09', 'kullanici');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `zefir_kumas`
--

CREATE TABLE `zefir_kumas` (
  `id` int(11) NOT NULL,
  `tarih` date NOT NULL,
  `kumas_cinsi` varchar(100) NOT NULL,
  `renk` varchar(50) NOT NULL,
  `metre` decimal(10,2) NOT NULL,
  `kare_ebati_cm` decimal(10,2) DEFAULT NULL,
  `en_mt` decimal(10,2) NOT NULL,
  `gr_metre` decimal(10,2) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `zefir_kumas`
--

INSERT INTO `zefir_kumas` (`id`, `tarih`, `kumas_cinsi`, `renk`, `metre`, `kare_ebati_cm`, `en_mt`, `gr_metre`, `aciklama`, `user_id`) VALUES
(2, '2025-06-15', '%100 Pamuk Zefir', 'Kırmızı', 500.00, 2.00, 150.00, 200.00, '', 3);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `alpaka_kumas`
--
ALTER TABLE `alpaka_kumas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `boya`
--
ALTER TABLE `boya`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boya_kumas_cinsleri_fk` (`kumas_cinsleri_id`);

--
-- Tablo için indeksler `cig_kumas`
--
ALTER TABLE `cig_kumas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `dokuma`
--
ALTER TABLE `dokuma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dokuma_kumas_cinsleri_fk` (`kumas_cinsleri_id`),
  ADD KEY `dokuma_cozgu_iplik_fk` (`cozgu_iplik_id`);

--
-- Tablo için indeksler `iplik`
--
ALTER TABLE `iplik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `iplik_alim`
--
ALTER TABLE `iplik_alim`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kristal_saten_kumas`
--
ALTER TABLE `kristal_saten_kumas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `kumas_cinsleri`
--
ALTER TABLE `kumas_cinsleri`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `satis`
--
ALTER TABLE `satis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `satis_kumas_cinsleri_fk` (`kumas_cinsleri_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `zefir_kumas`
--
ALTER TABLE `zefir_kumas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `alpaka_kumas`
--
ALTER TABLE `alpaka_kumas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `boya`
--
ALTER TABLE `boya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `cig_kumas`
--
ALTER TABLE `cig_kumas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `dokuma`
--
ALTER TABLE `dokuma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `iplik`
--
ALTER TABLE `iplik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `iplik_alim`
--
ALTER TABLE `iplik_alim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `kristal_saten_kumas`
--
ALTER TABLE `kristal_saten_kumas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `kumas_cinsleri`
--
ALTER TABLE `kumas_cinsleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `satis`
--
ALTER TABLE `satis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `zefir_kumas`
--
ALTER TABLE `zefir_kumas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `alpaka_kumas`
--
ALTER TABLE `alpaka_kumas`
  ADD CONSTRAINT `alpaka_kumas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `boya`
--
ALTER TABLE `boya`
  ADD CONSTRAINT `boya_kumas_cinsleri_fk` FOREIGN KEY (`kumas_cinsleri_id`) REFERENCES `kumas_cinsleri` (`id`);

--
-- Tablo kısıtlamaları `cig_kumas`
--
ALTER TABLE `cig_kumas`
  ADD CONSTRAINT `cig_kumas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `dokuma`
--
ALTER TABLE `dokuma`
  ADD CONSTRAINT `dokuma_cozgu_iplik_fk` FOREIGN KEY (`cozgu_iplik_id`) REFERENCES `iplik_alim` (`id`),
  ADD CONSTRAINT `dokuma_kumas_cinsleri_fk` FOREIGN KEY (`kumas_cinsleri_id`) REFERENCES `kumas_cinsleri` (`id`);

--
-- Tablo kısıtlamaları `iplik`
--
ALTER TABLE `iplik`
  ADD CONSTRAINT `iplik_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `kristal_saten_kumas`
--
ALTER TABLE `kristal_saten_kumas`
  ADD CONSTRAINT `kristal_saten_kumas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `satis`
--
ALTER TABLE `satis`
  ADD CONSTRAINT `satis_kumas_cinsleri_fk` FOREIGN KEY (`kumas_cinsleri_id`) REFERENCES `kumas_cinsleri` (`id`);

--
-- Tablo kısıtlamaları `zefir_kumas`
--
ALTER TABLE `zefir_kumas`
  ADD CONSTRAINT `zefir_kumas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
