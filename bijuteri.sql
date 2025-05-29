-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 18 May 2025, 20:21:59
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Veritabanı: `bijuteri`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adminler`
--

CREATE TABLE `adminler` (
  `admin_id` int(11) NOT NULL,
  `admin_ad_soyad` varchar(50) NOT NULL,
  `admin_eposta` varchar(100) NOT NULL,
  `admin_parola` varchar(255) NOT NULL,
  `admin_telefon` varchar(15) DEFAULT NULL,
  `admin_kayit_tarihi` datetime DEFAULT current_timestamp(),
  `aktif` tinyint(1) DEFAULT 1,
  `admin_last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `adminler`
--

INSERT INTO `adminler` (`admin_id`, `admin_ad_soyad`, `admin_eposta`, `admin_parola`, `admin_telefon`, `admin_kayit_tarihi`, `aktif`, `admin_last_login`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$RhiwVgYe5ESwnkxpTPnHkeCDWPgWL/YI8OkBitlZ9SO0X9gjvarvW', '1', '2025-05-17 19:41:32', 1, '2025-05-18 14:15:58'),
(2, 'Emirhan Bıkmaz', 'emirhanbk00@gmail.com', '$2y$10$8Z1lWXdnIzPjMCmbwzILjevFUkNPidQrlHIYN1a8t5DvZdEfkruEq', '5372700393', '2025-05-18 15:57:43', 1, '2025-05-18 20:12:24');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adresler`
--

CREATE TABLE `adresler` (
  `adres_id` int(11) NOT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `musteri_adres` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `adresler`
--

INSERT INTO `adresler` (`adres_id`, `musteri_id`, `musteri_adres`) VALUES
(1, 1, 'KanlıcaZ/Beykoz İstanbul'),
(2, 1, 'Kanlıca /Beykoz /Istanbul'),
(6, 1, 'KOCAELİ/IZMIT'),
(7, 3, 'Emir');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `favoriler`
--

CREATE TABLE `favoriler` (
  `favori_id` int(11) NOT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `urun_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

CREATE TABLE `kategoriler` (
  `kategori_id` int(11) NOT NULL,
  `kategori_ad` varchar(50) NOT NULL,
  `kategori_sayfa` varchar(50) NOT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`kategori_id`, `kategori_ad`, `kategori_sayfa`, `stok`) VALUES
(1, 'Yüzük', 'yüzük.php', 0),
(2, 'Kolye', 'kolye.php', 0),
(3, 'Bileklik', 'bileklik.php', 0),
(4, 'Küpe', 'küpe.php', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `musteriler`
--

CREATE TABLE `musteriler` (
  `musteri_id` int(11) NOT NULL,
  `musteri_ad_soyad` varchar(40) NOT NULL,
  `musteri_eposta` varchar(40) NOT NULL,
  `musteri_parola` varchar(100) NOT NULL,
  `musteri_telefon` varchar(15) DEFAULT NULL,
  `dogum_tarihi` date DEFAULT NULL,
  `cinsiyet` enum('Erkek','Kadın') DEFAULT NULL,
  `musteri_kayit_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `musteriler`
--

INSERT INTO `musteriler` (`musteri_id`, `musteri_ad_soyad`, `musteri_eposta`, `musteri_parola`, `musteri_telefon`, `dogum_tarihi`, `cinsiyet`, `musteri_kayit_tarihi`) VALUES
(1, 'Emre Yasin Yıldan', 'emreyildan@gmail.com', '$2y$10$T2N8glWH7v4AityhoXFcH.V.g3KCn9exuXZk6wEK45FSzn/9iE0dC', '+905339674624', '2005-07-03', 'Erkek', '2025-05-17 13:54:32'),
(2, 'Emirhan Bıkmaz', 'emirhanbikmaz@gmail.com', '$2y$10$0p7M070xA5Kgo2ZzMaEXfeNCITG0o46InPw0PHPkJN2AbPfK/OR/W', '+905372700393', '2003-03-31', 'Kadın', '2025-05-17 19:05:04'),
(3, 'Emirhan Bıkmaz', 'emirhanbk00@gmail.com', '$2y$10$ZMUQEjM4xzeAoVrB74xa4.4LZZRaFdxfL4vUQnURmEMHDs1oaA3sy', '+905372700393', '2003-03-03', 'Erkek', '2025-05-18 20:00:24');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sepet`
--

CREATE TABLE `sepet` (
  `sepet_id` int(11) NOT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `sepet_adet` int(11) DEFAULT NULL,
  `sepet_eklenme_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

CREATE TABLE `siparisler` (
  `siparis_id` int(11) NOT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `adres_id` int(11) DEFAULT NULL,
  `siparis_tarihi` datetime DEFAULT current_timestamp(),
  `toplam_tutar` decimal(10,2) DEFAULT NULL,
  `durum` enum('Hazırlanıyor','Kargoya Verildi','Teslim Edildi','İptal Edildi') DEFAULT 'Hazırlanıyor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`siparis_id`, `musteri_id`, `adres_id`, `siparis_tarihi`, `toplam_tutar`, `durum`) VALUES
(8, 3, 7, '2025-05-18 20:15:47', 2290.00, 'Hazırlanıyor');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_urunleri`
--

CREATE TABLE `siparis_urunleri` (
  `siparis_urun_id` int(11) NOT NULL,
  `siparis_id` int(11) DEFAULT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `siparis_adet` int(11) DEFAULT NULL,
  `siparis_birim_fiyat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `siparis_urunleri`
--

INSERT INTO `siparis_urunleri` (`siparis_urun_id`, `siparis_id`, `urun_id`, `siparis_adet`, `siparis_birim_fiyat`) VALUES
(11, 8, 52, 1, 2290.00);

--
-- Tetikleyiciler `siparis_urunleri`
--
DELIMITER $$
CREATE TRIGGER `stok_azalinca_logla` AFTER INSERT ON `siparis_urunleri` FOR EACH ROW BEGIN
    INSERT INTO urun_ekle_log (urun_id, eklenme_tarihi)
    VALUES (NEW.urun_id, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `stokhareketleri`
--

CREATE TABLE `stokhareketleri` (
  `hareket_id` int(11) NOT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `hareket_turu` enum('Giriş','Çıkış','İade') NOT NULL,
  `miktar` int(11) DEFAULT NULL,
  `tarih` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `stoklist`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `stoklist` (
`kategori_id` int(11)
,`kategori_ad` varchar(50)
,`urun_ad` varchar(50)
,`urun_stok` int(11)
);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `urun_id` int(11) NOT NULL,
  `urun_ad` varchar(50) DEFAULT NULL,
  `urun_aciklama` text DEFAULT NULL,
  `urun_fiyat` decimal(10,2) DEFAULT NULL,
  `urun_stok` int(11) DEFAULT 0,
  `kategori_id` int(11) DEFAULT NULL,
  `renk` varchar(20) DEFAULT NULL,
  `marka` varchar(35) DEFAULT NULL,
  `malzeme` varchar(35) DEFAULT NULL,
  `beden` varchar(50) DEFAULT NULL,
  `agirlik` decimal(10,2) DEFAULT NULL,
  `tas` varchar(100) DEFAULT NULL,
  `tas_agirlik` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`urun_id`, `urun_ad`, `urun_aciklama`, `urun_fiyat`, `urun_stok`, `kategori_id`, `renk`, `marka`, `malzeme`, `beden`, `agirlik`, `tas`, `tas_agirlik`) VALUES
(39, 'PARLAK TAŞLI YUSUFÇUK KÜPE', 'Parlak taş ve suni inci aplikeli, metal, sallantılı yusufçuk küpe. Arkası iğneli.', 920.00, 23, 4, 'Gümüş Rengi', 'Zara', '', 'Tek', 3.00, '0', 0.00),
(40, 'METAL HALKALI ZİNCİR KOLYE', '925 MM gümüş kaplama metal halkalı zincir. Çubuk kapamalı', 1190.00, 43, 2, 'Gümüş Rengi', 'Zara', 'gümüş', 'Tek', 15.00, '0', 0.00),
(41, 'KONTRAST TAŞLI YÜZÜK', 'Kontrast taş aplikeli, metal mühür yüzük. 925 ayar gümüş kaplama.', 1190.00, 52, 1, 'Kahve', 'Zara', 'gümüş', '10', 6.00, '0', 1.00),
(43, 'Guess Kalpli Kadın Bileklik', '925 Ayar Gümüş\r\nBakım ve Paketleme: Parfüm, Alkol, Krem, Çamaşır ve Deniz Suyu gibi maddeler ile temastan kaçınıldığı sürece kararma yapmaz. Kullanılmadığında kutusunda hava almayacak şekilde saklanması tavsiye edilmektedir.', 1159.00, 24, 3, 'Gümüş Renk', 'Guess', 'gümüş', '15', 11.00, '0', 2.00),
(44, 'KABARTMALI ÇİÇEK KÜPE', 'Kabartmalı çiçek şeklinde metal küpe. Arkası iğneli.', 920.00, 43, 4, 'Kahve,Siyah', 'Zara', 'gümüş', 'Tek', 4.00, '0', 0.00),
(45, 'BALIKLI HALKA KÜPE', 'Kabartmalı dairesel şekilde işlenmiş metal küpeler. Renkli reçineden yapılmış balık şeklinde kolye ucu. Klipsli ve somunlu kapama.', 750.00, 21, 4, 'Kahve Siyah Altın ', 'Zara', 'altın', 'Tek', 12.00, '0', 0.00),
(46, 'HALKALI BİLEKLİK', 'Metal halkalardan oluşan bileklik. 925 ayar gümüş.', 1990.00, 14, 3, 'Gümüş Renk', 'Zara', 'gümüş', '18cm', 19.00, '0', 0.00),
(49, 'Pandora Moments Kalp Klipsli Bileklik', 'Bu şık Yılan Zincir Bileklik ile her anı bir Pandora anına dönüştür. 925 ayar gümüş kullanılarak elde tamamlanan ve kalp bir klipse sahip olan bu klasik bileklik çok çeşitli klips ve charm’larla kullanılabilir. Bu çarpıcı bileklik her Pandora mücevheri aşığı için güzel bir seçim ve aynı zamanda sevdiklerine hediye etmen için mükemmel bir seçim.', 2669.00, 220, 3, 'Gümüş Renk', 'Pandora', 'gümüş', '15cm', 100.00, '0', 0.00),
(50, 'GUESS Gümüş Kadın Kolye', 'Guess 925 Ayar Gümüş Kadın Kolye;Maden:925 Ayar Gümüş  \r\nRenk:Gümüş \r\nEbat:Tek Ebat;\r\nGUESS Kutusunda Gönderilmektedir;\r\nKozmetik, parfüm ve temizlik ürünlerinden uzak tutulması önerilir.', 7960.00, 14, 2, 'Gümüş Renk', 'Guess', 'gümüş', 'Tek', 93.00, '0', 0.00),
(51, 'Sirius Gümüş Renk 3,38 Karat Pırlanta Tamtur Yüzük', 'Pırlanta Adet: 18 \r\nBerraklık: SI \r\nRenk: Gümüş Renk\r\nYuvarlak Altın Özellikleri: Altın Ayarı: 14 Ayar Renk: Beyaz Altın \r\nSertifikası ve şık kutusunda teslim edilmektedir', 29990.00, 2, 1, 'Gümüş Renk', 'Sirius ', 'gümüş', '10', 2.70, '0', 3.38),
(52, 'TAŞLI KABARTMALI YÜZÜK', 'Kontrast taş aplikeli, mühür tipi metal yüzük. 925 ayar gümüş .', 2290.00, 26, 1, 'Altıın, Siyah', 'Zara', 'gümüş', '14', 6.00, '1', 0.00),
(53, '3’LÜ KARMA KOLYE SETİ', 'Üçlü karma kolye seti.\r\n\r\n- Orta uzunlukta, uyumlu boncuklu metal zincir.\r\n- Kontrast boncuklu uzun metal zincir.\r\n- Kontrast boncuklu kolye.', 3390.00, 7, 2, 'Kahve Siyah Altın ', 'Zara', 'gümüş', 'Tek', 123.00, '0', 0.00);

--
-- Tetikleyiciler `urunler`
--
DELIMITER $$
CREATE TRIGGER `urun_eklenince_logla` AFTER INSERT ON `urunler` FOR EACH ROW BEGIN
    INSERT INTO urun_ekle_log (urun_id)
    VALUES (NEW.urun_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `urun_silinince_logla` AFTER DELETE ON `urunler` FOR EACH ROW BEGIN
    INSERT INTO urun_ekle_log (urun_id, eklenme_tarihi)
    VALUES (OLD.urun_id, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Görünüm yapısı durumu `urunlerview`
-- (Asıl görünüm için aşağıya bakın)
--
CREATE TABLE `urunlerview` (
`urun_id` int(11)
,`urun_ad` varchar(50)
,`urun_fiyat` decimal(10,2)
,`urun_stok` int(11)
,`kategori_ad` varchar(50)
);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunresimleri`
--

CREATE TABLE `urunresimleri` (
  `resim_id` int(11) NOT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `resim_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `urunresimleri`
--

INSERT INTO `urunresimleri` (`resim_id`, `urun_id`, `resim_url`) VALUES
(14, 39, 'images/6829ed0676dd3.jpg'),
(15, 40, 'images/6829edbc50675.jpg'),
(16, 41, 'images/6829ee98a61d7.jpg'),
(17, 43, 'images/6829f043d6932.jpg'),
(18, 44, 'images/6829f124016b4.jpg'),
(19, 45, 'images/6829f24f3eefd.jpg'),
(20, 46, 'images/6829f32115cef.jpg'),
(21, 49, 'images/6829f6552a991.jpg'),
(22, 50, 'images/6829f7dc6e3b6.jpg'),
(23, 51, 'images/6829f96ac561d.jpg'),
(24, 52, 'images/6829fa5fedc72.jpg'),
(25, 53, 'images/6829fb04ee135.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urun_ekle_log`
--

CREATE TABLE `urun_ekle_log` (
  `log_id` int(11) NOT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `ekleyen_admin_id` int(11) DEFAULT NULL,
  `eklenme_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `urun_ekle_log`
--

INSERT INTO `urun_ekle_log` (`log_id`, `urun_id`, `ekleyen_admin_id`, `eklenme_tarihi`) VALUES
(37, 39, NULL, '2025-05-18 17:21:58'),
(38, 40, NULL, '2025-05-18 17:25:00'),
(39, 41, NULL, '2025-05-18 17:28:40'),
(40, 42, NULL, '2025-05-18 17:31:43'),
(41, 42, NULL, '2025-05-18 17:32:11'),
(42, 43, NULL, '2025-05-18 17:35:47'),
(43, 44, NULL, '2025-05-18 17:39:32'),
(44, 45, NULL, '2025-05-18 17:44:31'),
(45, 46, NULL, '2025-05-18 17:48:01'),
(46, 47, NULL, '2025-05-18 17:54:28'),
(47, 47, NULL, '2025-05-18 17:54:56'),
(48, 48, NULL, '2025-05-18 17:57:15'),
(49, 48, NULL, '2025-05-18 17:58:45'),
(50, 49, NULL, '2025-05-18 18:01:41'),
(51, 50, NULL, '2025-05-18 18:08:12'),
(52, 51, NULL, '2025-05-18 18:14:50'),
(53, 52, NULL, '2025-05-18 18:18:55'),
(54, 53, NULL, '2025-05-18 18:21:40'),
(55, 52, NULL, '2025-05-18 20:15:47');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

CREATE TABLE `yorumlar` (
  `yorum_id` int(11) NOT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `kullanici_yorum` text DEFAULT NULL,
  `kullanici_puan` tinyint(4) DEFAULT NULL CHECK (`kullanici_puan` between 1 and 5),
  `kullanici_yorum_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `yorumlar`
--

INSERT INTO `yorumlar` (`yorum_id`, `urun_id`, `musteri_id`, `kullanici_yorum`, `kullanici_puan`, `kullanici_yorum_tarihi`) VALUES
(4, 45, 3, 'Harika bir küpe sevgilim çok beğendi', 5, '2025-05-18 20:02:06');

-- --------------------------------------------------------

--
-- Görünüm yapısı `stoklist`
--
DROP TABLE IF EXISTS `stoklist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `stoklist`  AS SELECT `k`.`kategori_id` AS `kategori_id`, `k`.`kategori_ad` AS `kategori_ad`, `u`.`urun_ad` AS `urun_ad`, `u`.`urun_stok` AS `urun_stok` FROM (`kategoriler` `k` join `urunler` `u` on(`k`.`kategori_id` = `u`.`kategori_id`)) ;

-- --------------------------------------------------------

--
-- Görünüm yapısı `urunlerview`
--
DROP TABLE IF EXISTS `urunlerview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `urunlerview`  AS SELECT `u`.`urun_id` AS `urun_id`, `u`.`urun_ad` AS `urun_ad`, `u`.`urun_fiyat` AS `urun_fiyat`, `u`.`urun_stok` AS `urun_stok`, `k`.`kategori_ad` AS `kategori_ad` FROM (`urunler` `u` join `kategoriler` `k` on(`u`.`kategori_id` = `k`.`kategori_id`)) ;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `adminler`
--
ALTER TABLE `adminler`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_eposta` (`admin_eposta`);

--
-- Tablo için indeksler `adresler`
--
ALTER TABLE `adresler`
  ADD PRIMARY KEY (`adres_id`),
  ADD KEY `musteri_id` (`musteri_id`);

--
-- Tablo için indeksler `favoriler`
--
ALTER TABLE `favoriler`
  ADD PRIMARY KEY (`favori_id`),
  ADD KEY `musteri_id` (`musteri_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Tablo için indeksler `musteriler`
--
ALTER TABLE `musteriler`
  ADD PRIMARY KEY (`musteri_id`),
  ADD UNIQUE KEY `musteri_eposta` (`musteri_eposta`);

--
-- Tablo için indeksler `sepet`
--
ALTER TABLE `sepet`
  ADD PRIMARY KEY (`sepet_id`),
  ADD KEY `musteri_id` (`musteri_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`siparis_id`),
  ADD KEY `musteri_id` (`musteri_id`),
  ADD KEY `adres_id` (`adres_id`);

--
-- Tablo için indeksler `siparis_urunleri`
--
ALTER TABLE `siparis_urunleri`
  ADD PRIMARY KEY (`siparis_urun_id`),
  ADD KEY `siparis_id` (`siparis_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `stokhareketleri`
--
ALTER TABLE `stokhareketleri`
  ADD PRIMARY KEY (`hareket_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`urun_id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `idx_urun_ad` (`urun_ad`);

--
-- Tablo için indeksler `urunresimleri`
--
ALTER TABLE `urunresimleri`
  ADD PRIMARY KEY (`resim_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `urun_ekle_log`
--
ALTER TABLE `urun_ekle_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Tablo için indeksler `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD PRIMARY KEY (`yorum_id`),
  ADD UNIQUE KEY `uniq_yorum` (`urun_id`,`musteri_id`),
  ADD KEY `musteri_id` (`musteri_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `adminler`
--
ALTER TABLE `adminler`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `adresler`
--
ALTER TABLE `adresler`
  MODIFY `adres_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `favoriler`
--
ALTER TABLE `favoriler`
  MODIFY `favori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `musteriler`
--
ALTER TABLE `musteriler`
  MODIFY `musteri_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `sepet`
--
ALTER TABLE `sepet`
  MODIFY `sepet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `siparis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_urunleri`
--
ALTER TABLE `siparis_urunleri`
  MODIFY `siparis_urun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `stokhareketleri`
--
ALTER TABLE `stokhareketleri`
  MODIFY `hareket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `urun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Tablo için AUTO_INCREMENT değeri `urunresimleri`
--
ALTER TABLE `urunresimleri`
  MODIFY `resim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `urun_ekle_log`
--
ALTER TABLE `urun_ekle_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Tablo için AUTO_INCREMENT değeri `yorumlar`
--
ALTER TABLE `yorumlar`
  MODIFY `yorum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `adresler`
--
ALTER TABLE `adresler`
  ADD CONSTRAINT `adresler_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`);

--
-- Tablo kısıtlamaları `favoriler`
--
ALTER TABLE `favoriler`
  ADD CONSTRAINT `favoriler_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`),
  ADD CONSTRAINT `favoriler_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `sepet`
--
ALTER TABLE `sepet`
  ADD CONSTRAINT `sepet_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`),
  ADD CONSTRAINT `sepet_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `siparisler`
--
ALTER TABLE `siparisler`
  ADD CONSTRAINT `siparisler_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`),
  ADD CONSTRAINT `siparisler_ibfk_2` FOREIGN KEY (`adres_id`) REFERENCES `adresler` (`adres_id`);

--
-- Tablo kısıtlamaları `siparis_urunleri`
--
ALTER TABLE `siparis_urunleri`
  ADD CONSTRAINT `siparis_urunleri_ibfk_1` FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`siparis_id`),
  ADD CONSTRAINT `siparis_urunleri_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `stokhareketleri`
--
ALTER TABLE `stokhareketleri`
  ADD CONSTRAINT `stokhareketleri_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `urunler`
--
ALTER TABLE `urunler`
  ADD CONSTRAINT `urunler_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`kategori_id`);

--
-- Tablo kısıtlamaları `urunresimleri`
--
ALTER TABLE `urunresimleri`
  ADD CONSTRAINT `urunresimleri_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD CONSTRAINT `yorumlar_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`),
  ADD CONSTRAINT `yorumlar_ibfk_2` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`);
COMMIT;