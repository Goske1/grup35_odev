<?php

include("baglanti.php");
session_start();

$arama = $_GET['q'] ?? '';
$urunler = [];

if ($arama) {
    $sorgu = "SELECT u.urun_id, u.urun_ad, u.urun_fiyat, ur.resim_url 
              FROM Urunler u 
              LEFT JOIN UrunResimleri ur ON u.urun_id = ur.urun_id 
              WHERE u.urun_ad LIKE ?
              /* Limiting the results to 20 for performance */
              LIMIT 10";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    $like = $arama . '%'; // Bytre yoluyla index kullanmak için yandaki satırı kullanabilirsin
    // $like = '%' . $arama . '%'; // Eğer arama içinde de arama yapmak istiyorsan
    mysqli_stmt_bind_param($stmt, "s", $like);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($sonuc)) {
        $urunler[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Arama Sonuçları</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Arama Sonuçları: <strong><?= htmlspecialchars($arama) ?></strong></h2>
    <form class="my-4" method="get" action="urun.arama.php">
        <div class="input-group">
            <input type="text" class="form-control" name="q" value="<?= htmlspecialchars($arama) ?>" placeholder="Ürün adı ara...">
            <button class="btn btn-primary" type="submit">Ara</button>
        </div>
    </form>
    <div class="row">
        <?php if ($arama && count($urunler) > 0): ?>
            <?php foreach ($urunler as $urun): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($urun['resim_url'] ?? 'resimler/placeholder.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($urun['urun_ad']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($urun['urun_ad']) ?></h5>
                            <p class="card-text"><?= number_format($urun['urun_fiyat'], 2) ?>₺</p>
                            <a href="urunler/ürün.detay.php?id=<?= $urun['urun_id'] ?>" class="btn btn-primary">İncele</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif ($arama): ?>
            <div class="col-12">
                <div class="alert alert-warning">Aradığınız kritere uygun ürün bulunamadı.</div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4">
        <a href="anasayfa.php" class="btn btn-secondary">Anasayfaya Dön</a>
    </div>
</div>
</body>
</html>