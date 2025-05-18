<?php
$host = "localhost";
$kullanici = "root";
$parola = "";
$db = "bijuteri";

$baglanti = mysqli_connect($host, $kullanici, $parola, $db);

if (!$baglanti) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

mysqli_set_charset($baglanti, "UTF8");
?>