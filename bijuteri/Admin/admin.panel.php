<?php
session_start();

// Eğer admin oturumu yoksa giriş sayfasına yönlendir
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin.giris.php");
    exit;
}

$admin_ad = $_SESSION["kullanici_adi"];
$yetki = $_SESSION["yetki_seviyesi"];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 40px;
        }

        .panel {
            background-color: #fff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        .section {
            margin-top: 20px;
        }

        .section a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .section a:hover {
            background-color: #0056b3;
        }

        .logout {
            margin-top: 30px;
            text-align: center;
        }

        .logout a {
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="panel">
    <h2>Hoş geldiniz, <?php echo htmlspecialchars($admin_ad); ?>!</h2>
    <p><strong>Yetki Seviyesi:</strong> <?php echo htmlspecialchars($yetki); ?></p>

    <div class="section">
        <?php if ($yetki === 'tam' || $yetki === 'urun_yonetimi'): ?>
            <a href="urunler.php">Ürün Yönetimi</a>
        <?php endif; ?>

        <?php if ($yetki === 'tam' || $yetki === 'siparis_yonetimi'): ?>
            <a href="siparisler.php">Sipariş Yönetimi</a>
        <?php endif; ?>

        <?php if ($yetki === 'tam'): ?>
            <a href="adminler.php">Admin Ayarları</a>
        <?php endif; ?>
    </div>

    <div class="logout">
        <a href="admin_cikis.php">Çıkış Yap</a>
    </div>
</div>

</body>
</html>
