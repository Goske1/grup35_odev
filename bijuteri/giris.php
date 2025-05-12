<?php
include("baglanti.php");
session_start();

if (isset($_POST["giris"])) {
    $email = $_POST["email"];
    $password = $_POST["sifre"];

    $sorgu = "SELECT * FROM musteriler WHERE eposta = ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);

    if ($kullanici = mysqli_fetch_assoc($sonuc)) {
        if (password_verify($password, $kullanici["parola"])) {
            $_SESSION["kullanici_adi"] = $kullanici["ad_soyad"];
            $_SESSION["kullanici_id"] = $kullanici["musteri_id"];
            header("Location: anasayfa.php");
            exit();
        } else {
            echo '<div class="alert alert-danger">Hatalı şifre!</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Böyle bir kullanıcı bulunamadı!</div>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($baglanti);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
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

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Giriş Yap</h2>

        <form method="POST" action="giris.php">
            <label for="email">E-posta:</label>
            <input type="email" id="email" name="email" required placeholder="E-posta adresinizi girin">

            <label for="sifre">Şifre:</label>
            <input type="password" id="sifre" name="sifre" required placeholder="Şifrenizi girin">

            <input type="submit" name="giris" value="Giriş Yap">
        </form>

        <div class="form-footer">
            <p>Hesabınız yok mu? <a href="kayit.php">Kayıt olun</a></p>
        </div>
    </div>
</body>
</html>
