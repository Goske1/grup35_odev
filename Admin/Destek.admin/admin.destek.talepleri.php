<?php

include("../../baglanti.php"); // Doğru yol
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../admin.giris.php");
    exit();
}

// Silme işlemi
if (isset($_GET['sil']) && is_numeric($_GET['sil'])) {
    $talep_id = intval($_GET['sil']);
    // Önce mesajları sil
    mysqli_query($baglanti, "DELETE FROM destek_mesajlari WHERE talep_id = $talep_id");
    // Sonra talebi sil
    mysqli_query($baglanti, "DELETE FROM destek_talepleri WHERE talep_id = $talep_id");
    // Sayfayı yenile
    header("Location: admin.destek.talepleri.php");
    exit;
}

// Talep kapama işlemi
if (isset($_GET['kapat']) && is_numeric($_GET['kapat'])) {
    $talep_id = intval($_GET['kapat']);
    mysqli_query($baglanti, "UPDATE destek_talepleri SET durum='Kapalı' WHERE talep_id=$talep_id");
    header("Location: admin.destek.talepleri.php");
    exit;
}

$sorgu = "SELECT dt.*, m.musteri_ad_soyad FROM destek_talepleri dt LEFT JOIN musteriler m ON dt.musteri_id = m.musteri_id ORDER BY dt.talep_tarihi DESC";
$sonuc = mysqli_query($baglanti, $sorgu);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Destek Talepleri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h3>Destek Talepleri</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Müşteri</th>
                <th>Konu</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th>Mesajlaş</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($talep = mysqli_fetch_assoc($sonuc)): ?>
            <tr>
                <td><?= htmlspecialchars($talep['ad_soyad']) ?></td>
                <td><?= htmlspecialchars($talep['konu']) ?></td>
                <td><?= htmlspecialchars($talep['talep_tarihi']) ?></td>
                <td><?= htmlspecialchars($talep['durum']) ?></td>
                <td>
                    <a href="admin.destek.mesajlasma.php?talep_id=<?= $talep['talep_id'] ?>" class="btn btn-sm btn-info">Mesajlaş</a>
                    <a href="?kapat=<?= $talep['talep_id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('Talebi kapatmak istediğinize emin misiniz?')">Kapat</a>
                    <a href="?sil=<?= $talep['talep_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu talebi ve tüm mesajlarını silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../admin.panel.php" class="btn btn-secondary">Panele Dön</a>
</div>
</body>
</html>