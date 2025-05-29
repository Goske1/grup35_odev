<?php
include("baglanti.php");
session_start();

// Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION["kullanici_id"])) {
    header("Location: giris.php");
    exit();
}

if (isset($_POST["yorum_ekle"])) {
    $yorum_metni = $_POST["yorum_metni"];
    $musteri_id = $_SESSION["kullanici_id"];
    
    $sorgu = "INSERT INTO yorumlar (musteri_id, yorum_metni) VALUES (?, ?)";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "is", $musteri_id, $yorum_metni);
    
    if (mysqli_stmt_execute($stmt)) {
        echo '<div class="alert alert-success">Yorumunuz başarıyla eklendi!</div>';
    } else {
        echo '<div class="alert alert-danger">Yorum eklenirken bir hata oluştu!</div>';
    }
    
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorum Yap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 50px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 100px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Yorum Yap</h2>
        <form method="POST" action="yorum_yap.php">
            <textarea name="yorum_metni" required placeholder="Yorumunuzu buraya yazın..."></textarea>
            <input type="submit" name="yorum_ekle" value="Yorumu Gönder">
        </form>
        <p><a href="yorumlar.php">Tüm Yorumları Görüntüle</a></p>
    </div>
</body>
</html> 