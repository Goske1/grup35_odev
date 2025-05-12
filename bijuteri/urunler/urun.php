<?php
session_start();
include("../baglanti.php");

// Oturum kontrolü
if (!isset($_SESSION["kullanici_id"])) {
    header("Location: ../giris.php");
    exit();
}

// Ürünleri veritabanından çek
$sorgu = "SELECT * FROM urunler ORDER BY urun_id DESC";
$sonuc = mysqli_query($baglanti, $sorgu);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .urun-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }
        .urun-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            transition: transform 0.2s;
        }
        .urun-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .urun-resim {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .urun-baslik {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .urun-fiyat {
            color: #e44d26;
            font-weight: bold;
            font-size: 1.1em;
        }
        .urun-aciklama {
            color: #666;
            margin: 10px 0;
            font-size: 0.9em;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-ekle {
            background-color: #28a745;
        }
        .btn-ekle:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ürünler</h1>
            <a href="urun_ekle.php" class="btn btn-ekle">Yeni Ürün Ekle</a>
        </div>

        <div class="urun-grid">
            <?php while ($urun = mysqli_fetch_assoc($sonuc)): ?>
                <div class="urun-card">
                    <?php if (!empty($urun["resim"])): ?>
                        <img src="../resimler/<?php echo htmlspecialchars($urun["resim"]); ?>" alt="<?php echo htmlspecialchars($urun["urun_adi"]); ?>" class="urun-resim">
                    <?php endif; ?>
                    <div class="urun-baslik"><?php echo htmlspecialchars($urun["urun_adi"]); ?></div>
                    <div class="urun-fiyat"><?php echo number_format($urun["fiyat"], 2); ?> TL</div>
                    <div class="urun-aciklama"><?php echo htmlspecialchars($urun["aciklama"]); ?></div>
                    <a href="urun_duzenle.php?id=<?php echo $urun["urun_id"]; ?>" class="btn">Düzenle</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html> 