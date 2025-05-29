<?php

session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris.php");
    exit();
}

$musteri_id = $_SESSION["musteri_id"];
$arama = $_GET['arama'] ?? '';
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$limit = 10;
$baslangic = ($sayfa - 1) * $limit;

// Toplam sipariş sayısı
if ($arama) {
    $toplam_sorgu = mysqli_prepare($baglanti, "SELECT COUNT(*) as sayi FROM siparisler WHERE musteri_id=? AND siparis_id LIKE ?");
    $like = "%$arama%";
    mysqli_stmt_bind_param($toplam_sorgu, "is", $musteri_id, $like);
    mysqli_stmt_execute($toplam_sorgu);
    $toplam_sonuc = mysqli_stmt_get_result($toplam_sorgu);
    $toplam_sayi = mysqli_fetch_assoc($toplam_sonuc)['sayi'];
    mysqli_stmt_close($toplam_sorgu);
} else {
    $toplam_sorgu = mysqli_prepare($baglanti, "SELECT COUNT(*) as sayi FROM siparisler WHERE musteri_id=?");
    mysqli_stmt_bind_param($toplam_sorgu, "i", $musteri_id);
    mysqli_stmt_execute($toplam_sorgu);
    $toplam_sonuc = mysqli_stmt_get_result($toplam_sorgu);
    $toplam_sayi = mysqli_fetch_assoc($toplam_sonuc)['sayi'];
    mysqli_stmt_close($toplam_sorgu);
}
$toplam_sayfa = ceil($toplam_sayi / $limit);

// Siparişleri çek
if ($arama) {
    $sorgu = "SELECT * FROM siparisler WHERE musteri_id=? AND siparis_id LIKE ? ORDER BY siparis_tarihi DESC LIMIT ?, ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    $like = "%$arama%";
    mysqli_stmt_bind_param($stmt, "isii", $musteri_id, $like, $baslangic, $limit);
} else {
    $sorgu = "SELECT * FROM siparisler WHERE musteri_id=? ORDER BY siparis_tarihi DESC LIMIT ?, ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "iii", $musteri_id, $baslangic, $limit);
}
mysqli_stmt_execute($stmt);
$sonuc = mysqli_stmt_get_result($stmt);
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
    <form method="get" class="mb-3">
        <input type="text" name="arama" placeholder="Sipariş No ile ara" value="<?= htmlspecialchars($arama) ?>" class="form-control d-inline" style="width:200px;">
        <button type="submit" class="btn btn-primary btn-sm">Ara</button>
    </form>
    <?php if (mysqli_num_rows($sonuc) == 0): ?>
        <div class="alert alert-info">Henüz hiç siparişiniz yok.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sipariş No</th>
                    <th>Tarih</th>
                    <th>Tutar</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($siparis = mysqli_fetch_assoc($sonuc)): ?>
                    <tr>
                        <td><?= htmlspecialchars($siparis['siparis_id']) ?></td>
                        <td><?= htmlspecialchars($siparis['siparis_tarihi']) ?></td>
                        <td><?= htmlspecialchars($siparis['toplam_tutar']) ?> ₺</td>
                        <td><?= htmlspecialchars($siparis['durum']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <!-- Sayfalama -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $toplam_sayfa; $i++): ?>
                <li class="page-item <?= $i == $sayfa ? 'active' : '' ?>">
                    <a class="page-link" href="?sayfa=<?= $i ?>&arama=<?= urlencode($arama) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <a href="anasayfa.php" class="btn btn-secondary">Anasayfaya Dön</a>
</div>
</body>
</html>