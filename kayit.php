<?php
include("baglanti.php");
session_start();

$hata = '';
$basarili = '';

if (isset($_POST["kaydet"])) {
    $name = $_POST["adSoyad"];
    $mail = $_POST["email"];
    $password = password_hash($_POST["sifre"], PASSWORD_DEFAULT);
    $telno = $_POST["telefonno"];
    $dogumTarihi = $_POST["dogumTarihi"];
    $cinsiyet = isset($_POST["cinsiyet"]) ? $_POST["cinsiyet"] : null;

    // E-posta adresi kontrolü
    $kontrolSorgu = "SELECT musteri_id FROM musteriler WHERE musteri_eposta = ?";
    $kontrolStmt = mysqli_prepare($baglanti, $kontrolSorgu);
    mysqli_stmt_bind_param($kontrolStmt, "s", $mail);
    mysqli_stmt_execute($kontrolStmt);
    mysqli_stmt_store_result($kontrolStmt);

    if (mysqli_stmt_num_rows($kontrolStmt) > 0) {
        $hata = "Bu e-posta adresi ile daha önce kayıt yapılmış.";
    } else {
        // Yeni kullanıcı ekle
        $ekle = "INSERT INTO musteriler (
                    musteri_ad_soyad,
                    musteri_eposta,
                    musteri_parola,
                    musteri_telefon,
                    dogum_tarihi,
                    cinsiyet
                 ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($baglanti, $ekle);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $mail, $password, $telno, $dogumTarihi, $cinsiyet);

        if (mysqli_stmt_execute($stmt)) {
            $basarili = "Tebrikler, kaydınız başarıyla alınmıştır. <a href='giris.php'>Giriş yapmak için tıklayın</a>.";
        } else {
            $hata = "Üzgünüz, kaydınız alınamadı: " . mysqli_error($baglanti);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_stmt_close($kontrolStmt);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 50px;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="tel"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            color: #007BFF;
            text-decoration: none;
        }
        .radio-group {
            margin: 15px 0;
        }
        .radio-group label {
            display: inline;
            font-weight: normal;
            margin-right: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Kayıt Ol</h2>
    
    <?php if (!empty($hata)): ?>
        <div class="alert alert-danger"><?php echo $hata; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($basarili)): ?>
        <div class="alert alert-success"><?php echo $basarili; ?></div>
    <?php else: ?>
    
    <form action="kayit.php" method="POST">
        <!-- Ad Soyad -->
        <label for="adSoyad">Ad Soyad:</label>
        <input type="text" id="adSoyad" name="adSoyad" required placeholder="Adınızı ve soyadınızı girin">

        <!-- E-posta -->
        <label for="email">E-posta:</label>
        <input type="email" id="email" name="email" required placeholder="E-posta adresinizi girin">

        <!-- Şifre -->
        <label for="sifre">Şifre:</label>
        <input type="password" id="sifre" name="sifre" required placeholder="Şifrenizi oluşturun">

        <!-- Doğum Tarihi -->
        <label for="dogumTarihi">Doğum Tarihi:</label>
        <input type="date" id="dogumTarihi" name="dogumTarihi" required>

        <!-- Telefon Numarası -->
        <label for="telefon">Telefon Numarası:</label>
        <input type="tel" id="telefon" name="telefonno" required pattern="^\+?\d{10,15}$" placeholder="Telefon numaranızı girin (ör. +905xxxxxxxxx)">
        <small class="text-muted">Telefon numaranız için 10-15 haneli bir sayı girin (ör. +905xxxxxxxxx)</small>
        
        <!-- Cinsiyet -->
        <div class="radio-group">
            <label>Cinsiyet:</label><br>
            <input type="radio" name="cinsiyet" value="Erkek" required> Erkek
            <input type="radio" name="cinsiyet" value="Kadın" required> Kadın
        </div>
       
        <!-- Gönder Butonu -->
        <input type="submit" name="kaydet" value="Kayıt Ol">
    </form>

    <div class="form-footer">
        <p>Zaten üye misiniz? <a href="giris.php">Giriş yapın</a></p>
    </div>
    
    <?php endif; ?>
</div>
</body>
</html>