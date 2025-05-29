<?php
include("../baglanti.php");
session_start();

// Giriş yapılmış mı kontrol et
if (!isset($_SESSION["musteri_id"])) {
    header("Location: ../giris.php");
    exit();
}

$musteri_id = $_SESSION["musteri_id"];

// GET ile gelen ürün ID'si
if (isset($_GET["urun_id"])) {
    $urun_id = intval($_GET["urun_id"]);

    // Daha önce favorilere eklenmiş mi kontrol et
    $kontrolSorgu = "SELECT * FROM Favoriler WHERE musteri_id = ? AND urun_id = ?";

    $kontrolStmt = mysqli_prepare($baglanti, $kontrolSorgu);
    mysqli_stmt_bind_param($kontrolStmt, "ii", $musteri_id, $urun_id);
    mysqli_stmt_execute($kontrolStmt);
    $kontrolSonuc = mysqli_stmt_get_result($kontrolStmt);

    if (mysqli_num_rows($kontrolSonuc) === 0) {
        // Eklenmemişse ekle
        $ekleSorgu = "INSERT INTO Favoriler (musteri_id, urun_id) VALUES (?, ?)";

        $ekleStmt = mysqli_prepare($baglanti, $ekleSorgu);
        mysqli_stmt_bind_param($ekleStmt, "ii", $musteri_id, $urun_id);
        if (mysqli_stmt_execute($ekleStmt)) {
            header("Location: favorilerim.php?ekleme=basarili");
            exit();
        } else {
            echo "Favoriye eklenirken hata oluştu.";
        }
        mysqli_stmt_close($ekleStmt);
    } else {
        // Zaten ekli
        header("Location: favorilerim.php?zaten=ekli");
        exit();
    }

    mysqli_stmt_close($kontrolStmt);
} else {
    echo "Ürün ID bulunamadı.";
}

mysqli_close($baglanti);
?>
