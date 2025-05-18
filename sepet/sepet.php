<?php
session_start();

// Sepet dizisini başlat
if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ürün silme işlemi
if (isset($_GET['sil'])) {
    $urun_id = $_GET['sil'];
    foreach ($_SESSION['sepet'] as $key => $item) {
        if ($item['id'] == $urun_id) {
            unset($_SESSION['sepet'][$key]);
            $_SESSION['sepet'] = array_values($_SESSION['sepet']); // Dizi indekslerini sıfırla
            break;
        }
    }
    header("Location: sepet.php");
    exit();
}

// Adet güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['urun_id'], $_POST['adet'])) {
    $urun_id = $_POST['urun_id'];
    $adet = max(1, intval($_POST['adet'])); // En az 1 adet olmalı

    // Veritabanı bağlantısı
    require_once '../baglanti.php';

    foreach ($_SESSION['sepet'] as &$item) {
        if ($item['id'] == $urun_id) {
            $eski_adet = $item['quantity'];
            $fark = $adet - $eski_adet;
            $item['quantity'] = $adet;

            // Ürünün kategori bilgisini bul
            $kategori_id = $item['kategori'];

            // Kategori stok güncelle
            if ($fark != 0) {
                $kategori_stok_guncelle = mysqli_prepare($baglanti, "UPDATE kategoriler SET kategori_stok = kategori_stok - ? WHERE kategori_id = ?");
                mysqli_stmt_bind_param($kategori_stok_guncelle, "ii", $fark, $kategori_id);
                mysqli_stmt_execute($kategori_stok_guncelle);
                mysqli_stmt_close($kategori_stok_guncelle);
            }
            break;
        }
    }
    header("Location: sepet.php");
    exit();
}

if (!isset($_SESSION["musteri_id"])) {
    $_SESSION['mesaj'] = "Sepeti görüntülemek için lütfen giriş yapın.";
    $_SESSION['mesaj_tipi'] = "warning";
    header("Location: ../giris.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim - Lüks Bijüteri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="../anasayfa.php">Lüks Bijüteri</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="../anasayfa.php">Ana Sayfa</a></li>
                <li class="nav-item"><a class="nav-link" href="../urunler/kolye.php">Kolyeler</a></li>
                <li class="nav-item"><a class="nav-link" href="../urunler/yüzük.php">Yüzükler</a></li>
                <li class="nav-item"><a class="nav-link" href="../urunler/küpe.php">Küpeler</a></li>
                <li class="nav-item"><a class="nav-link" href="../urunler/bileklik.php">Bileklikler</a></li>
            </ul>
            <div class="d-flex">
                <a href="../urun.arama.php" class="btn btn-outline-dark me-2"><i class="fas fa-search"></i></a>
                 <?php if (isset($_SESSION["musteri_id"])): ?>
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                <?= htmlspecialchars($_SESSION["kullanici_ad_soyad"]) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="../profil.php"><i class="fas fa-user me-2"></i>Profilim</a></li>
                                <li><a class="dropdown-item" href="../siparis.durum.php"><i class="fas fa-box me-2"></i>Siparişlerim</a></li>
                                <li><a class="dropdown-item" href="../favori/favorilerim.php"><i class="fas fa-heart me-2"></i>Favorilerim</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="../çikis.php"><i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="../giris.php" class="btn btn-outline-dark me-2"><i class="fas fa-sign-in-alt me-1"></i>Giriş Yap</a>
                    <?php endif; ?>                    
            </div>
        </div>
    </div>
</nav>

<!-- Başlık -->
<div class="bg-light py-5">
    <div class="container">
        <h1 class="text-center">Sepetim</h1>
        <p class="text-center text-muted">Sepetinizdeki ürünleri inceleyin ve alışverişinizi tamamlayın.</p>
    </div>
</div>

<!-- İçerik -->
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h3>Sepetinizdeki Ürünler</h3>

            <?php if (isset($_GET['basarili'])): ?>
                <div class="alert alert-success">Siparişiniz başarıyla oluşturuldu!</div>
            <?php endif; ?>

            <?php if (count($_SESSION['sepet']) > 0): ?>
                <?php
                $toplam_fiyat = 0;
                foreach ($_SESSION['sepet'] as $item):
                    $toplam_fiyat += $item['price'] * $item['quantity'];
                ?>
                    <div class="cart-item row align-items-center">
                        <div class="col-md-3">
                            <img src="<?= $item['resim'] ?>" alt="<?= $item['name'] ?>" class="img-fluid" style="max-height: 100px;">
                        </div>
                        <div class="col-md-5">
                            <h5><?= $item['name'] ?></h5>
                            <p><?= number_format($item['price'], 0, ',', '.') ?> TL</p>
                        </div>
                        <div class="col-md-4">
                            <form action="sepet.php" method="POST" class="d-flex flex-column">
                                <input type="hidden" name="urun_id" value="<?= $item['id'] ?>">
                                <div class="d-flex mb-2">
                                    <input type="number" name="adet" value="<?= $item['quantity'] ?>" min="1" class="form-control me-2" style="width: 80px;">
                                    <button type="submit" class="btn btn-primary">Güncelle</button>
                                </div>
                                <a href="sepet.php?sil=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Sil</a>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <hr>
                <h4>Toplam: <?= number_format($toplam_fiyat, 0, ',', '.') ?> TL</h4>
                <a href="../siparişlerim.php" class="btn btn-success mt-3">Alışverişi Tamamla</a>
            <?php else: ?>
                <p>Sepetinizde henüz ürün bulunmamaktadır.</p>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <h3>Özet</h3>
            <p>Sepetinizde <?= count($_SESSION['sepet']) ?> ürün bulunmaktadır.</p>
            <p>Toplam Tutar: <?= number_format(isset($toplam_fiyat) ? $toplam_fiyat : 0, 0, ',', '.') ?> TL</p>
            <a href="../anasayfa.php" class="btn btn-secondary">Alışverişe Devam Et</a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Hakkımızda</h5>
                <p>Lüks ve kaliteli takı tasarımlarıyla 20 yılı aşkın süredir hizmetinizdeyiz.</p>
            </div>
            <div class="col-md-4">
                <h5>Hızlı Linkler</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light">Gizlilik Politikası</a></li>
                    <li><a href="#" class="text-light">İade Koşulları</a></li>
                    <li><a href="#" class="text-light">İletişim</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Bizi Takip Edin</h5>
                <div class="social-links">
                    <a href="#" class="text-light me-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-light me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light me-2"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
