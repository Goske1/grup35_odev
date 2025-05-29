<?php
session_start();
include("../baglanti.php");

// Admin girisi kontrolu
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin.giris.php");
    exit();
}

// Admin bilgilerini çek
$admin_id = $_SESSION["admin_id"];
$admin = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT * FROM Adminler WHERE admin_id = $admin_id"));

// Sayfa verileri
$urun_sayisi = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM Urunler"))["sayi"];
$kategori_sayisi = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM Kategoriler"))["sayi"];
$musteri_sayisi = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM Musteriler"))["sayi"];
$siparis_sayisi = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM Siparisler"))["sayi"];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Paneli</h2>
        <div>
            <strong>Hoşgeldin:</strong> <?= isset($admin['admin_ad_soyad']) ? htmlspecialchars($admin['admin_ad_soyad']) : '' ?>
            <a href="admin.cikiş.php" class="btn btn-outline-danger btn-sm ms-3">Cikis</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5>Toplam Ürün</h5>
                    <p class="fs-4"><?= $urun_sayisi ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5>Toplam Kategori</h5>
                    <p class="fs-4"><?= $kategori_sayisi ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark mb-3">
                <div class="card-body">
                    <h5>Toplam Müşteri</h5>
                    <p class="fs-4"><?= $musteri_sayisi ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white mb-3">
                <div class="card-body">
                    <h5>Toplam Sipariş</h5>
                    <p class="fs-4"><?= $siparis_sayisi ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin.duzenle/urun.ekle.php" class="btn btn-outline-primary">Yeni Ürün Ekle</a>        
        <a href="musteri.goruntule.php" class="btn btn-outline-success">Müşteriler</a>
        <a href="../siparis.durum.php" class="btn btn-outline-warning">Siparişler</a>      
        <a href="admin.duzenle/stok.php" class="btn btn-outline-dark">Stok Hareketleri</a>
        <a href="admin.duzenle/urun.sil.php" class="btn btn-outline-danger">Ürün Sil</a>
    </div>
</div>
</body>
</html>