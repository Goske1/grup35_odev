<?php
// Veritabanı bağlantısı
include_once '../../baglanti.php';

// Silme işlemi
if (isset($_GET['id'])) {
    $urun_id = intval($_GET['id']);

    // Önce resim dosyalarını sunucudan sil
    $resim_sorgu = mysqli_query($baglanti, "SELECT resim_url FROM urunresimleri WHERE urun_id = $urun_id");
    while ($resim = mysqli_fetch_assoc($resim_sorgu)) {
        // Sadece dosya adını al ve tam yolu oluştur
        $dosya_yolu = realpath(__DIR__ . '/../../urunler/images/' . basename($resim['resim_url']));
        if ($dosya_yolu && file_exists($dosya_yolu)) {
            unlink($dosya_yolu);
        }
    }

    // İlişkili tablolardan sil
    mysqli_query($baglanti, "DELETE FROM siparis_urunleri WHERE urun_id = $urun_id");
    mysqli_query($baglanti, "DELETE FROM yorumlar WHERE urun_id = $urun_id");
    mysqli_query($baglanti, "DELETE FROM urunresimleri WHERE urun_id = $urun_id");

    // Sonra ürünü sil
    mysqli_query($baglanti, "DELETE FROM Urunler WHERE urun_id = $urun_id");

    header("Location: urun.sil.php?durum=ok");
    exit;
}

// UrunlerView ile ürünleri çek
$view_sql = "SELECT * FROM UrunlerView";
$view_result = mysqli_query($baglanti, $view_sql);

if (!$view_result) {
    die('<div class="alert alert-danger mt-4">UrunlerView bulunamadı. Lütfen view\'i oluşturun.</div>');
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Sil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Ürünleri Sil</h2>
    <?php if (isset($_GET['durum']) && $_GET['durum'] == 'ok'): ?>
        <div class="alert alert-success">Ürün başarıyla silindi.</div>
    <?php elseif (isset($_GET['durum']) && $_GET['durum'] == 'no'): ?>
        <div class="alert alert-danger">Ürün silinirken hata oluştu.</div>
    <?php endif; ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Kategori</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($urun = mysqli_fetch_assoc($view_result)): ?>
            <tr>
                <td><?= htmlspecialchars($urun['urun_id']) ?></td>
                <td><?= htmlspecialchars($urun['urun_ad']) ?></td>
                <td><?= htmlspecialchars($urun['kategori_ad']) ?></td>
                <td><?= htmlspecialchars($urun['urun_fiyat']) ?> TL</td>
                <td><?= htmlspecialchars($urun['urun_stok']) ?></td>
                <td>
                    <a href="urun.sil.php?id=<?= $urun['urun_id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../admin.panel.php" class="btn btn-secondary">Panele Dön</a>
</div>
</body>
</html>
