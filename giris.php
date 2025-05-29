<?php
include("baglanti.php");
session_start();

if (isset($_POST["giris"])) {
    $eposta = $_POST["eposta"];
    $parola = $_POST["parola"];

    $sorgu = "SELECT * FROM musteriler WHERE musteri_eposta = ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "s", $eposta);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);

    if ($kullanici = mysqli_fetch_assoc($sonuc)) {
        if (password_verify($parola, $kullanici["musteri_parola"])) {
            $_SESSION["musteri_id"] = $kullanici["musteri_id"];
            $_SESSION["kullanici_ad_soyad"] = $kullanici["musteri_ad_soyad"];
            $_SESSION["kullanici_eposta"] = $kullanici["musteri_eposta"];
            
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: " . $redirect);
            } else {
                header("Location: anasayfa.php");
            }
            exit();
        } else {
            $hata = "Şifreniz hatalıdır. Lütfen tekrar deneyiniz!";
        }
    } else {
        $hata = "Böyle bir kullanıcı bulunamadı!";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Giriş Yap</h2>
        
        <?php if (!empty($hata)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Hata!</strong> <?php echo $hata; ?>
            </div>
        <?php endif; ?>
        
        <form action="giris.php" method="POST">
            <div class="form-group">
                <label for="eposta">E-posta</label>
                <input type="email" id="eposta" name="eposta" required>
            </div>
            
            <div class="form-group">
                <label for="parola">Parola</label>
                <input type="password" id="parola" name="parola" required>
            </div>
            
            <button type="submit" name="giris">Giriş Yap</button>
        </form>
        
        <div class="register-link mt-3">
            Henüz hesabınız yok mu? <a href="kayit.php">Kayıt Olun</a>
        </div>
    </div>
</body>
</html>