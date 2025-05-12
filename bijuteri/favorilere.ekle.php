<?php
session_start();
include("baglanti.php"); // $db bağlantısı

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit;
}

$musteri_id = $_SESSION['kullanici_id'];
$urun_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($urun_id <= 0) {
    echo "Geçersiz ürün ID.";
    exit;
}

// Ürün gerçekten var mı kontrol et
$urunSorgu = $db->prepare("SELECT * FROM urunler WHERE urun_id = ?");
$urunSorgu->execute([$urun_id]);

if ($urunSorgu->rowCount() == 0) {
    echo "Bu ürün veritabanında bulunamadı.";
    exit;
}

// Aynı ürün daha önce favorilere eklendi mi?
$kontrol = $db->prepare("SELECT * FROM favoriler WHERE musteri_id = ? AND urun_id = ?");
$kontrol->execute([$musteri_id, $urun_id]);

if ($kontrol->rowCount() == 0) {
    // Yeni favori ekle
    $ekle = $db->prepare("INSERT INTO favoriler (musteri_id, urun_id) VALUES (?, ?)");
    $ekle->execute([$musteri_id, $urun_id]);
}

header("Location: favoriler.php");
exit;
?>
