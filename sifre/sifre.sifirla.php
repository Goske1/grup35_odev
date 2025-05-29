<?php

include("../baglanti.php");
session_start();

$bilgi = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eposta"])) {
    $eposta = trim($_POST["eposta"]);
    $sorgu = "SELECT * FROM musteriler WHERE musteri_eposta = ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "s", $eposta);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);

    if ($kullanici = mysqli_fetch_assoc($sonuc)) {
        // Token oluştur
        $token = bin2hex(random_bytes(32));
        $son = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Tokeni kaydet
        $guncelle = "UPDATE musteriler SET sifre_sifirla_token=?, sifre_sifirla_son=? WHERE musteri_id=?";
        $stmt2 = mysqli_prepare($baglanti, $guncelle);
        mysqli_stmt_bind_param($stmt2, "ssi", $token, $son, $kullanici["musteri_id"]);
        mysqli_stmt_execute($stmt2);

        // Mail gönder (mail fonksiyonunu sunucunda yapılandırmalısın)
        $link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/sifre.yenile.php?token=$token";
        $mesaj = "Şifre sıfırlama için bu bağlantıya tıklayın: $link";
        mail($eposta, "Şifre Sıfırlama", $mesaj);

        $bilgi = "Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.";
    } else {
        $bilgi = "Bu e-posta ile kayıtlı kullanıcı bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Sıfırlama</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5" style="max-width:400px;">
    <h4>Şifre Sıfırlama</h4>
    <?php if ($bilgi): ?>
        <div class="alert alert-info"><?= $bilgi ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>E-posta adresiniz</label>
            <input type="email" name="eposta" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Sıfırlama Linki Gönder</button>
    </form>
</div>
</body>
</html>