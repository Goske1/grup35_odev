<?php
// filepath: c:\xampp\htdocs\bijuteri\sifre\sifre.yenile.php
include("../baglanti.php");
session_start();

$bilgi = "";
$token = $_GET["token"] ?? "";

if (!$token) {
    $bilgi = "Geçersiz bağlantı.";
} else {
    // Token kontrolü
    $sorgu = "SELECT * FROM musteriler WHERE sifre_sifirla_token = ? AND sifre_sifirla_son > NOW()";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);
    $kullanici = mysqli_fetch_assoc($sonuc);

    if (!$kullanici) {
        $bilgi = "Bağlantı geçersiz veya süresi dolmuş.";
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["yeni_sifre"])) {
        $yeni_sifre = $_POST["yeni_sifre"];
        $yeni_sifre_tekrar = $_POST["yeni_sifre_tekrar"];

        if ($yeni_sifre !== $yeni_sifre_tekrar) {
            $bilgi = "Şifreler eşleşmiyor!";
        } elseif (strlen($yeni_sifre) < 6) {
            $bilgi = "Şifre en az 6 karakter olmalı!";
        } else {
            $hashli = password_hash($yeni_sifre, PASSWORD_DEFAULT);
            $guncelle = "UPDATE musteriler SET musteri_parola=?, sifre_sifirla_token=NULL, sifre_sifirla_son=NULL WHERE musteri_id=?";
            $stmt2 = mysqli_prepare($baglanti, $guncelle);
            mysqli_stmt_bind_param($stmt2, "si", $hashli, $kullanici["musteri_id"]);
            mysqli_stmt_execute($stmt2);
            $bilgi = "Şifreniz başarıyla güncellendi. <a href='../giris.php'>Giriş yap</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Şifre Belirle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5" style="max-width:400px;">
    <h4>Yeni Şifre Belirle</h4>
    <?php if ($bilgi): ?>
        <div class="alert alert-info"><?= $bilgi ?></div>
    <?php endif; ?>
    <?php if ($kullanici && empty($bilgi) || (isset($kullanici) && $_SERVER["REQUEST_METHOD"] != "POST")): ?>
    <form method="post">
        <div class="mb-3">
            <label>Yeni Şifre</label>
            <input type="password" name="yeni_sifre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Yeni Şifre (Tekrar)</label>
            <input type="password" name="yeni_sifre_tekrar" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>