<?php

session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris.php");
    exit();
}

$musteri_id = $_SESSION["musteri_id"];

// Siparişleri çek
$sorgu = mysqli_prepare($baglanti, "SELECT s.siparis_id, s.siparis_tarihi, s.toplam_tutar, s.durum, a.musteri_adres 
    FROM siparisler s 
    LEFT JOIN adresler a ON s.adres_id = a.adres_id 
    WHERE s.musteri_id = ? 
    ORDER BY s.siparis_tarihi DESC");
mysqli_stmt_bind_param($sorgu, "i", $musteri_id);
mysqli_stmt_execute($sorgu);
$sonuc = mysqli_stmt_get_result($sorgu);

$siparisler = [];
while ($row = mysqli_fetch_assoc($sonuc)) {
    $siparisler[] = $row;
}
mysqli_stmt_close($sorgu);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişlerim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Siparişlerim</h2>
    <?php if (count($siparisler) == 0): ?>
        <div class="alert alert-info">Henüz hiç siparişiniz yok.</div>
    <?php else: ?>
        <?php foreach ($siparisler as $siparis): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Sipariş No:</strong> <?= $siparis['siparis_id'] ?> |
                    <strong>Tarih:</strong> <?= $siparis['siparis_tarihi'] ?> |
                    <strong>Durum:</strong> <?= $siparis['durum'] ?>
                </div>
                <div class="card-body">
                    <p><strong>Teslimat Adresi:</strong> <?= htmlspecialchars($siparis['musteri_adres']) ?></p>
                    <p><strong>Toplam Tutar:</strong> <?= number_format($siparis['toplam_tutar'], 2) ?> ₺</p>
                    <h6>Sipariş Ürünleri:</h6>
                    <ul>
                        <?php
                        $urun_sorgu = mysqli_prepare($baglanti, "SELECT u.urun_ad, su.siparis_adet, su.siparis_birim_fiyat 
                            FROM siparis_urunleri su 
                            JOIN urunler u ON su.urun_id = u.urun_id 
                            WHERE su.siparis_id = ?");
                        mysqli_stmt_bind_param($urun_sorgu, "i", $siparis['siparis_id']);
                        mysqli_stmt_execute($urun_sorgu);
                        $urun_sonuc = mysqli_stmt_get_result($urun_sorgu);
                        if (mysqli_num_rows($urun_sonuc) == 0): ?>
                            <li>Bu siparişte ürün bulunamadı.</li>
                        <?php else:
                            while ($urun = mysqli_fetch_assoc($urun_sonuc)): ?>
                                <li>
                                    <?= htmlspecialchars($urun['urun_ad']) ?> -
                                    <?= $urun['siparis_adet'] ?> adet -
                                    <?= number_format($urun['siparis_birim_fiyat'], 2) ?> ₺
                                </li>
                            <?php endwhile;
                        endif;
                        mysqli_stmt_close($urun_sorgu);
                        ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <a href="anasayfa.php" class="btn btn-secondary">Anasayfaya Dön</a>
</div>
</body>
</html>