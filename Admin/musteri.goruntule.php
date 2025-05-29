<?php

session_start();
include_once '../baglanti.php';

// Admin kontrolü (isteğe bağlı)
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin.giris.php");
    exit();
}

// Müşterileri çek
$sorgu = "SELECT musteri_id, musteri_ad_soyad, musteri_eposta, musteri_telefon, dogum_tarihi, cinsiyet, musteri_kayit_tarihi FROM musteriler ORDER BY musteri_id ASC";
$sonuc = mysqli_query($baglanti, $sorgu);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Müşteri Listesi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Kayıtlı Müşteriler</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Telefon</th>
                <th>Doğum Tarihi</th>
                <th>Cinsiyet</th>
                <th>Kayıt Tarihi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($sonuc)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['musteri_id']) ?></td>
                    <td><?= htmlspecialchars($row['musteri_ad_soyad']) ?></td>
                    <td><?= htmlspecialchars($row['musteri_eposta']) ?></td>
                    <td><?= htmlspecialchars($row['musteri_telefon']) ?></td>
                    <td><?= htmlspecialchars($row['dogum_tarihi']) ?></td>
                    <td><?= htmlspecialchars($row['cinsiyet']) ?></td>
                    <td><?= htmlspecialchars($row['musteri_kayit_tarihi']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="admin.panel.php" class="btn btn-secondary">Panele Dön</a>
</div>
</body>
</html>