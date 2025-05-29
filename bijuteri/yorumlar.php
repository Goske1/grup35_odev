<?php
include("baglanti.php");
session_start();

// Yorumları ve kullanıcı bilgilerini çek
$sorgu = "SELECT y.*, m.ad_soyad 
          FROM yorumlar y 
          JOIN musteriler m ON y.musteri_id = m.musteri_id 
          ORDER BY y.yorum_tarihi DESC";
$sonuc = mysqli_query($baglanti, $sorgu);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorumlar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 50px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .yorum {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .yorum-baslik {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #666;
        }
        .yorum-metin {
            margin-top: 10px;
            line-height: 1.5;
        }
        .yorum-yazan {
            font-weight: bold;
            color: #007BFF;
        }
        .yorum-tarih {
            color: #666;
            font-size: 0.9em;
        }
        .yorum-ekle-link {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .yorum-ekle-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Yorumlar</h2>
        <?php if (isset($_SESSION["kullanici_id"])): ?>
            <a href="yorum_yap.php" class="yorum-ekle-link">Yeni Yorum Ekle</a>
        <?php endif; ?>

        <?php while ($yorum = mysqli_fetch_assoc($sonuc)): ?>
            <div class="yorum">
                <div class="yorum-baslik">
                    <span class="yorum-yazan"><?php echo htmlspecialchars($yorum["ad_soyad"]); ?></span>
                    <span class="yorum-tarih"><?php echo date("d.m.Y H:i", strtotime($yorum["yorum_tarihi"])); ?></span>
                </div>
                <div class="yorum-metin">
                    <?php echo nl2br(htmlspecialchars($yorum["yorum_metni"])); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html> 