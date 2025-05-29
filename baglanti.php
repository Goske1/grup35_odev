<?php
$host = "localhost";
$kullanici = "root";
$parola = "";
$db = "bijuteri";
// BU BOLUM BUTUN DOSYALARI INCLUDE EDEREK KULLANILACAK NEDENI ISE VERI TABANIYLA BAGLAMA KISMI HER DOSYADA TEKRAR YAZILMASININ ONUNE GECMEK ICIN
$baglanti = mysqli_connect($host, $kullanici, $parola, $db);

if (!$baglanti) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

mysqli_set_charset($baglanti, "UTF8");
?>
