<?php
// filepath: c:\xampp\htdocs\bijuteri\destek.talep.gonder.php
include("../baglanti.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad_soyad = trim($_POST["ad_soyad"]);
    $eposta = trim($_POST["eposta"]);
    $konu = trim($_POST["konu"]);
    $mesaj = trim($_POST["mesaj"]);
    $musteri_id = $_SESSION["musteri_id"] ?? null;

    // Önce talebi ekle
    $sorgu = "INSERT INTO destek_talepleri (musteri_id, ad_soyad, eposta, konu, mesaj) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "issss", $musteri_id, $ad_soyad, $eposta, $konu, $mesaj);
    if (mysqli_stmt_execute($stmt)) {
        $talep_id = mysqli_insert_id($baglanti);

        // İlk mesajı destek_mesajlari tablosuna da ekle
        $mesaj_sorgu = "INSERT INTO destek_mesajlari (talep_id, gonderen, mesaj, mesaj_tarihi, okundu) VALUES (?, 'musteri', ?, NOW(), 0)";
        $mesaj_stmt = mysqli_prepare($baglanti, $mesaj_sorgu);
        mysqli_stmt_bind_param($mesaj_stmt, "is", $talep_id, $mesaj);
        mysqli_stmt_execute($mesaj_stmt);
        mysqli_stmt_close($mesaj_stmt);

        header("Location: ../anasayfa.php?talep=ok");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Talebiniz kaydedilemedi: " . mysqli_error($baglanti) . "</div>";
    }
    mysqli_stmt_close($stmt);
}
?>