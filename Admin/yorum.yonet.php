<?php
// filepath: c:\xampp\htdocs\bijuteri\Admin\yorum.yonet.php
include("../baglanti.php");
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin.giris.php");
    exit();
}

// Yorum silme
if (isset($_GET['sil']) && is_numeric($_GET['sil'])) {
    $yorum_id = intval($_GET['sil']);
    mysqli_query($baglanti, "DELETE FROM yorumlar WHERE yorum_id = $yorum_id");
    header("Location: yorum.yonet.php");
    exit;
}

// Yorum düzenleme ve cevap verme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["yorum_id"])) {
    $yorum_id = intval($_POST["yorum_id"]);
    $yorum = trim($_POST["kullanici_yorum"]);
    $cevap = trim($_POST["admin_cevap"]);
    // Admin cevabı için ayrı bir tablo veya sütun yoksa, aşağıdaki ALTER ile ekleyebilirsin:
    // ALTER TABLE yorumlar ADD COLUMN admin_cevap TEXT DEFAULT NULL;
    mysqli_query($baglanti, "UPDATE yorumlar SET kullanici_yorum = '$yorum', admin_cevap = '$cevap' WHERE yorum_id = $yorum_id");
    header("Location: yorum.yonet.php");
    exit;
}

// Yorumları çek
$yorumlar = mysqli_query($baglanti, "
    SELECT y.*, u.urun_ad, m.musteri_ad_soyad 
    FROM yorumlar y
    LEFT JOIN urunler u ON y.urun_id = u.urun_id
    LEFT JOIN musteriler m ON y.musteri_id = m.musteri_id
    ORDER BY y.kullanici_yorum_tarihi DESC
");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yorum Yönetimi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h3>Ürün Yorumları</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ürün</th>
                <th>Müşteri</th>
                <th>Yorum</th>
                <th>Puan</th>
                <th>Tarih</th>
                <th>Admin Cevabı</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($y = mysqli_fetch_assoc($yorumlar)): ?>
            <tr>
                <td><?= htmlspecialchars($y['urun_ad']) ?></td>
                <td><?= htmlspecialchars($y['musteri_ad_soyad']) ?></td>
                <td>
                    <form method="post" class="d-flex flex-column">
                        <input type="hidden" name="yorum_id" value="<?= $y['yorum_id'] ?>">
                        <textarea name="kullanici_yorum" class="form-control mb-2" rows="2"><?= htmlspecialchars($y['kullanici_yorum']) ?></textarea>
                        <textarea name="admin_cevap" class="form-control mb-2" rows="2" placeholder="Cevabınız"><?= htmlspecialchars($y['admin_cevap'] ?? '') ?></textarea>
                        <button type="submit" class="btn btn-sm btn-success mb-1">Kaydet</button>
                    </form>
                </td>
                <td><?= htmlspecialchars($y['kullanici_puan']) ?></td>
                <td><?= $y['kullanici_yorum_tarihi'] ?></td>
                <td><?= htmlspecialchars($y['admin_cevap'] ?? '') ?></td>
                <td>
                    <a href="yorum.yonet.php?sil=<?= $y['yorum_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yorumu silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="admin.panel.php" class="btn btn-secondary">Panele Dön</a>
</div>
</body>
</html>