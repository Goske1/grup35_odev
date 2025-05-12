<?php
session_start();
include("../baglanti.php");

// Oturum kontrolü
if (!isset($_SESSION["kullanici_id"])) {
    header("Location: ../giris.php");
    exit();
}

if (isset($_POST["urun_ekle"])) {
    $urun_adi = $_POST["urun_adi"];
    $fiyat = $_POST["fiyat"];
    $aciklama = $_POST["aciklama"];
    $resim = "";

    // Resim yükleme işlemi
    if (isset($_FILES["resim"]) && $_FILES["resim"]["error"] == 0) {
        $izin_verilen_uzantilar = array("jpg", "jpeg", "png", "gif");
        $dosya_uzantisi = strtolower(pathinfo($_FILES["resim"]["name"], PATHINFO_EXTENSION));

        if (in_array($dosya_uzantisi, $izin_verilen_uzantilar)) {
            $yeni_dosya_adi = uniqid() . "." . $dosya_uzantisi;
            $hedef_klasor = "../resimler/";
            
            // Klasör yoksa oluştur
            if (!file_exists($hedef_klasor)) {
                mkdir($hedef_klasor, 0777, true);
            }

            if (move_uploaded_file($_FILES["resim"]["tmp_name"], $hedef_klasor . $yeni_dosya_adi)) {
                $resim = $yeni_dosya_adi;
            }
        }
    }

    $sorgu = "INSERT INTO urunler (urun_adi, fiyat, aciklama, resim) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "sdss", $urun_adi, $fiyat, $aciklama, $resim);

    if (mysqli_stmt_execute($stmt)) {
        echo '<div class="alert alert-success">Ürün başarıyla eklendi!</div>';
    } else {
        echo '<div class="alert alert-danger">Ürün eklenirken bir hata oluştu!</div>';
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Ürün Ekle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007BFF;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="urun.php" class="back-link">← Ürünlere Dön</a>
        <h1>Yeni Ürün Ekle</h1>

        <form method="POST" action="urun_ekle.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="urun_adi">Ürün Adı:</label>
                <input type="text" id="urun_adi" name="urun_adi" required>
            </div>

            <div class="form-group">
                <label for="fiyat">Fiyat (TL):</label>
                <input type="number" id="fiyat" name="fiyat" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="aciklama">Açıklama:</label>
                <textarea id="aciklama" name="aciklama" required></textarea>
            </div>

            <div class="form-group">
                <label for="resim">Ürün Resmi:</label>
                <input type="file" id="resim" name="resim" accept="image/*">
            </div>

            <button type="submit" name="urun_ekle" class="btn">Ürün Ekle</button>
        </form>
    </div>
</body>
</html> 