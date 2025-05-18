-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 13 May 2025, 12:55:31
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adresler`
--

CREATE TABLE `adresler` (
  `adres_id` int(11) NOT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `musteri_adres` text DEFAULT NULL
 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `kategori_sayfa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`kategori_id`, `kategori_ad`, `kategori_sayfa`) VALUES
(1, 'Yüzük', 'yüzük.php'),
(2, 'Kolye', 'kolye.php'),
(3, 'Bileklik', 'bileklik.php'),
(4, 'Küpe', 'küpe.php');

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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sepet`
--

CREATE TABLE `sepet` (
  `sepet_id` int(11) NOT NULL,
  `musteri_id` int(11) DEFAULT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `varyant_id` int(11) DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_urunleri`
--

CREATE TABLE `siparis_urunleri` (
  `siparis_urun_id` int(11) NOT NULL,
  `siparis_id` int(11) DEFAULT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `varyant_id` int(11) DEFAULT NULL,
  `siparis_adet` int(11) DEFAULT NULL,
  `siparis_birim_fiyat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `malzeme` varchar(35) DEFAULT NULL
  `beden` VARCHAR(50),
  `agirlik` DECIMAL(10,2),
  `tas` VARCHAR(100),
  `tas_agirlik` DECIMAL(10,2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 



--
-- Tablo için tablo yapısı `urunresimleri`
--

CREATE TABLE `urunresimleri` (
  `resim_id` int(11) NOT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `resim_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
-- Tablo için indeksler `destektalep`
--
ALTER TABLE `destektalep`
  ADD PRIMARY KEY (`talep_id`),
  ADD KEY `musteri_id` (`musteri_id`),
  ADD KEY `admin_id` (`admin_id`);

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
  ALTER TABLE Kategoriler ADD COLUMN stok INT DEFAULT 0;

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
  ADD KEY `urun_id` (`urun_id`),
  ADD KEY `varyant_id` (`varyant_id`);

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
  ADD KEY `urun_id` (`urun_id`),
  ADD KEY `varyant_id` (`varyant_id`);

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
  ADD KEY `kategori_id` (`kategori_id`);



--
-- Tablo için indeksler `urunozellikleri`
--
ALTER TABLE `urunozellikleri`
  ADD PRIMARY KEY (`ozellik_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `urunresimleri`
--
ALTER TABLE `urunresimleri`
  ADD PRIMARY KEY (`resim_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `urunvaryantlari`
--
ALTER TABLE `urunvaryantlari`
  ADD PRIMARY KEY (`varyant_id`),
  ADD KEY `urun_id` (`urun_id`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `adresler`
--
ALTER TABLE `adresler`
  MODIFY `adres_id` int(11) NOT NULL AUTO_INCREMENT;

--


--
-- Tablo için AUTO_INCREMENT değeri `favoriler`
--
ALTER TABLE `favoriler`
  MODIFY `favori_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `musteriler`
--
ALTER TABLE `musteriler`
  MODIFY `musteri_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `sepet`
--
ALTER TABLE `sepet`
  MODIFY `sepet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `siparis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_urunleri`
--
ALTER TABLE `siparis_urunleri`
  MODIFY `siparis_urun_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `stokhareketleri`
--
ALTER TABLE `stokhareketleri`
  MODIFY `hareket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `urun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--


--
-- Tablo için AUTO_INCREMENT değeri `urunresimleri`
--
ALTER TABLE `urunresimleri`
  MODIFY `resim_id` int(11) NOT NULL AUTO_INCREMENT;

--

--
-- Tablo için AUTO_INCREMENT değeri `yorumlar`
--
ALTER TABLE `yorumlar`
  MODIFY `yorum_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `adresler`
--
ALTER TABLE `adresler`
  ADD CONSTRAINT `adresler_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`);

--
-- Tablo kısıtlamaları `destektalep`
--
ALTER TABLE `destektalep`
  ADD CONSTRAINT `destektalep_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`),
  ADD CONSTRAINT `destektalep_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `adminler` (`admin_id`);

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
  ADD CONSTRAINT `sepet_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`),
  

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
  ADD CONSTRAINT `siparis_urunleri_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`),
 

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
-- Tablo kısıtlamaları `urunozellikleri`
--
ALTER TABLE `urunozellikleri`
  ADD CONSTRAINT `urunozellikleri_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `urunresimleri`
--
ALTER TABLE `urunresimleri`
  ADD CONSTRAINT `urunresimleri_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `urunvaryantlari`
--
ALTER TABLE `urunvaryantlari`
  ADD CONSTRAINT `urunvaryantlari_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`);

--
-- Tablo kısıtlamaları `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD CONSTRAINT `yorumlar_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`),
  ADD CONSTRAINT `yorumlar_ibfk_2` FOREIGN KEY (`musteri_id`) REFERENCES `musteriler` (`musteri_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*




VİEW KISMI*/

CREATE VIEW stoklist AS
SELECT
    k.kategori_id,
    k.kategori_ad,
    u.urun_ad,
    u.urun_stok
FROM kategoriler k
JOIN urunler u ON k.kategori_id = u.kategori_id;

CREATE VIEW UrunlerView AS
SELECT
    u.urun_id,
    u.urun_ad,
    u.urun_fiyat,
    u.urun_stok,
    k.kategori_ad
FROM urunler u
JOIN kategoriler k ON u.kategori_id = k.kategori_id;



/* INDEX KISMI*/
/* ındex ekleme yapıldı */
ALTER TABLE urunler ADD INDEX idx_urun_ad (urun_ad);

------------------
/* SQL Sorgusu ındexın çalısıp çalısmadığını anlamak  ıcın  sorguyu yazınca ıdx_urun_ad*/
EXPLAIN SELECT u.urun_id, u.urun_ad, u.urun_fiyat, ur.resim_url
FROM Urunler u
LEFT JOIN UrunResimleri ur ON u.urun_id = ur.urun_id
WHERE u.urun_ad LIKE 'Altın%'
LIMIT 20;



/* KARŞILAŞILAN TABLO SILME PROBLEM COZUMU*/

SELECT 
    TABLE_NAME, 
    COLUMN_NAME, 
    CONSTRAINT_NAME, 
    REFERENCED_TABLE_NAME, 
    REFERENCED_COLUMN_NAME
FROM
    information_schema.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_NAME = 'urunvaryantlari';


    /* BU SORGU İLE HANGİ TABLODA HANGİ KISIMDA PROBLEM OLDUĞUNU GÖRÜYORUZ*/
    ----------------------------------------
    ALTER TABLE sepet DROP FOREIGN KEY sepet_ibfk_3;
ALTER TABLE siparis_urunleri DROP FOREIGN KEY siparis_urunleri_ibfk_3;
DROP TABLE urunvaryantlari;

/*Sorunları bulduktan sonra Foreign keylerden dolayı olduğunu goruyoruz ve bu foreign keylerı kaldırıyoruz*/



/* TRİGGER KISMI EKLEME */


 DELIMITER $$
CREATE TRIGGER urun_silince_resimleri_sil
AFTER DELETE ON Urunler
FOR EACH ROW
BEGIN
    DELETE FROM urunresimleri WHERE urun_id = OLD.urun_id;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER siparis_ekle_stok_azalt
AFTER INSERT ON siparis_urunleri
FOR EACH ROW
BEGIN
    UPDATE Urunler SET urun_stok = urun_stok - NEW.adet WHERE urun_id = NEW.urun_id;
END $$
DELIMITER ;
