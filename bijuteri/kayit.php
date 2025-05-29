<?php

include("baglanti.php");

if(isset($_POST["kaydet"]))
{
$name=$_POST["adSoyad"];
$mail=$_POST["email"];
$password=password_hash($_POST["şifre"],PASSWORD_DEFAULT);
$telno=$_POST["telefonno"];
$dogumgunu=$_POST["dogumTarihi"];
$cins=$_POST["cinsiyeti"];



$ekle="INSERT INTO musteriler (ad_soyad, eposta ,parola,telefon,dogum_tarihi,cinsiyet) VALUES ('$name','$mail','$password','$telno','$dogumgunu','$cins')";
$calistirekle= mysqli_query($baglanti,$ekle);

if($calistirekle){
  echo '<div class="alert alert-success" role="alert">
  Tebrikler Kaydınız başarıyla sağlanmıştır.
</div>';
}
else {
  echo '<div class="alert alert-danger" role="alert">
  Üzgünüz Kaydınız alınamamıştır :(
</div>';
}
mysqli_close($baglanti);
}


?>
 


 <!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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
            max-width: 400px;
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

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Kayıt Ol</h2>

    <form action="kayit.php" method="POST">
        <!-- Ad Soyad -->
        <label for="adSoyad">Ad Soyad:</label>
        <input type="text" id="adSoyad" name="adSoyad" required placeholder="Adınızı ve soyadınızı girin">

        <!-- E-posta -->
        <label for="email">E-posta:</label>
        <input type="email" id="email" name="email" required placeholder="E-posta adresinizi girin">

        <!-- Şifre -->
        <label for="sifre">Şifre:</label>
        <input type="password" id="sifre" name="şifre" required placeholder="Şifrenizi oluşturun">

        <!-- Doğum Tarihi -->
        <label for="dogumTarihi">Doğum Tarihi:</label>
        <input type="date" id="dogumTarihi" name="dogumTarihi" required>

        <!-- Telefon Numarası -->
        <label for="telefon">Telefon Numarası:</label>
        <input type="tel" id="telefon" name="telefonno" required pattern="^\+?\d{10,15}$" placeholder="Telefon numaranızı girin (ör. +905xxxxxxxxx)">
        <small>Telefon numaranız için 10-15 haneli bir sayı girin (ör. +905xxxxxxxxx)</small><br><br>
        
        <!-- Cinsiyet -->
        <label>Cinsiyet:</label><br>
        <label>
        Erkek
        <input type="radio" name="cinsiyeti" value="Erkek" required>
        </label>
        <label>
        Kadın
        <input type="radio" name="cinsiyeti" value="Kadın">
        </label>

       
        <!-- Gönder Butonu -->
        <input type="submit" name="kaydet" value="Kayıt Ol">

    </form>

    <div class="form-footer">
        <p>Zaten üye misiniz? <a href="giris.php">Giriş yapın</a></p>
    </div>
</div>

</body>
</html>



