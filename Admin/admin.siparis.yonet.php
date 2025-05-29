<?php

session_start();
include("../baglanti.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin.giris.php");
    exit();
}

$arama = $_GET['arama'] ?? '';
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$limit = 10;
$baslangic = ($sayfa - 1) * $limit;

// Durum güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["siparis_id"], $_POST["yeni_durum"])) {
    $siparis_id = intval($_POST["siparis_id"]);
    $yeni_durum = $_POST["yeni_durum"];
    $guncelle = mysqli_prepare($baglanti, "UPDATE siparisler SET durum=? WHERE siparis_id=?");
    mysqli_stmt_bind_param($guncelle, "si", $yeni_durum, $siparis_id);
    mysqli_stmt_execute($guncelle);
    mysqli_stmt_close($guncelle);
    header("Location: admin.siparis.yonet.php?sayfa=$sayfa&arama=" . urlencode($arama));
    exit;
}

// Toplam sipariş sayısı
if ($arama) {
    $toplam_sorgu = mysqli_prepare($baglanti, "SELECT COUNT(*) as sayi FROM siparisler WHERE siparis_id LIKE ?");
    $like = "%$arama%";
    mysqli_stmt_bind_param($toplam_sorgu, "s", $like);
    mysqli_stmt_execute($toplam_sorgu);
    $toplam_sonuc = mysqli_stmt_get_result($toplam_sorgu);
    $toplam_sayi = mysqli_fetch_assoc($toplam_sonuc)['sayi'];
    mysqli_stmt_close($toplam_sorgu);
} else {
    $toplam_sorgu = mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM siparisler");
    $toplam_sayi = mysqli_fetch_assoc($toplam_sorgu)['sayi'];
}
$toplam_sayfa = ceil($toplam_sayi / $limit);

// Siparişleri çek
if ($arama) {
    $sorgu = "SELECT * FROM siparisler WHERE siparis_id LIKE ? ORDER BY siparis_tarihi DESC LIMIT ?, ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    $like = "%$arama%";
    mysqli_stmt_bind_param($stmt, "sii", $like, $baslangic, $limit);
} else {
    $sorgu = "SELECT * FROM siparisler ORDER BY siparis_tarihi DESC LIMIT ?, ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "ii", $baslangic, $limit);
}
mysqli_stmt_execute($stmt);
$sonuc = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Sipariş Yönetimi</h2>
    <form method="get" class="mb-3">
        <input type="text" name="arama" placeholder="Sipariş No ile ara" value="<?= htmlspecialchars($arama) ?>" class="form-control d-inline" style="width:200px;">
        <button type="submit" class="btn btn-primary btn-sm">Ara</button>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sipariş No</th>
                <th>Müşteri ID</th>
                <th>Tarih</th>
                <th>Tutar</th>
                <th>Durum</th>
                <th>Durum Güncelle</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($siparis = mysqli_fetch_assoc($sonuc)): ?>
                <tr>
                    <td><?= htmlspecialchars($siparis['siparis_id']) ?></td>
                    <td><?= htmlspecialchars($siparis['musteri_id']) ?></td>
                    <td><?= htmlspecialchars($siparis['siparis_tarihi']) ?></td>
                    <td><?= htmlspecialchars($siparis['toplam_tutar']) ?> ₺</td>
                    <td><?= htmlspecialchars($siparis['durum']) ?></td>
                    <td>
                        <form method="post" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="siparis_id" value="<?= $siparis['siparis_id'] ?>">
                            <select name="yeni_durum" class="form-select form-select-sm" required>
                                <?php
                                $durumlar = ['Hazırlanıyor', 'Kargoya Verildi', 'Teslim Edildi', 'İptal Edildi'];
                                foreach ($durumlar as $durum):
                                ?>
                                    <option value="<?= $durum ?>" <?= $siparis['durum'] == $durum ? 'selected' : '' ?>><?= $durum ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Güncelle</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
    <a href="admin.panel.php" class="btn btn-secondary">Panele Dön</a>
</div>
</body>
</html>