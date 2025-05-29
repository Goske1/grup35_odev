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

    // Veritabanından stok miktarını çek
    include_once '../baglanti.php';
    $stok_sorgu = mysqli_prepare($baglanti, "SELECT urun_stok FROM urunler WHERE urun_id = ?");
    mysqli_stmt_bind_param($stok_sorgu, "i", $urun_id);
    mysqli_stmt_execute($stok_sorgu);
    mysqli_stmt_bind_result($stok_sorgu, $urun_stok);
    mysqli_stmt_fetch($stok_sorgu);
    mysqli_stmt_close($stok_sorgu);

    if ($adet > $urun_stok) {
        $_SESSION['mesaj'] = "Stokta en fazla $urun_stok adet var!";
        $_SESSION['mesaj_tipi'] = "danger";
        header("Location: sepet.php");
        exit();
    }

    foreach ($_SESSION['sepet'] as &$item) {
        if ($item['id'] == $urun_id) {
            $item['quantity'] = $adet;
            break;
        }
    }
    unset($item);
    header("Location: sepet.php");
    exit();
}

// Alışverişi Tamamla işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alisverisi_tamamla'])) {
    if (!isset($_SESSION["musteri_id"])) {
        $_SESSION['mesaj'] = "Alışverişi tamamlamak için giriş yapmalısınız.";
        $_SESSION['mesaj_tipi'] = "warning";
        header("Location: sepet.php");
        exit();
    }
    include_once '../baglanti.php';
    $musteri_id = $_SESSION["musteri_id"];
    $toplam_fiyat = 0;
    foreach ($_SESSION['sepet'] as $item) {
        $toplam_fiyat += $item['price'] * $item['quantity'];
    }
    // Adres seçimi yoksa varsayılan adresi al
    $adres_id = null;
    $adres_sorgu = mysqli_query($baglanti, "SELECT adres_id FROM adresler WHERE musteri_id = $musteri_id LIMIT 1");
    if ($adres_row = mysqli_fetch_assoc($adres_sorgu)) {
        $adres_id = $adres_row['adres_id'];
    }

    // Sipariş oluştur
    $siparis_ekle = mysqli_prepare($baglanti, "INSERT INTO siparisler (musteri_id, adres_id, toplam_tutar) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($siparis_ekle, "iid", $musteri_id, $adres_id, $toplam_fiyat);
    mysqli_stmt_execute($siparis_ekle);
    $siparis_id = mysqli_insert_id($baglanti);
    mysqli_stmt_close($siparis_ekle);

    // Her ürün için stok azalt ve siparis_urunleri tablosuna ekle
    foreach ($_SESSION['sepet'] as $item) {
        // Önce mevcut stok miktarını kontrol et
        $stok_sorgu = mysqli_prepare($baglanti, "SELECT urun_stok FROM urunler WHERE urun_id = ?");
        mysqli_stmt_bind_param($stok_sorgu, "i", $item['id']);
        mysqli_stmt_execute($stok_sorgu);
        mysqli_stmt_bind_result($stok_sorgu, $mevcut_stok);
        mysqli_stmt_fetch($stok_sorgu);
        mysqli_stmt_close($stok_sorgu);

        if ($mevcut_stok < $item['quantity']) {
            $_SESSION['mesaj'] = $item['name'] . " ürünü için yeterli stok yok!";
            $_SESSION['mesaj_tipi'] = "danger";
            header("Location: sepet.php");
            exit();
        }

        // Satın alınan miktar kadar stoktan düş
        $stok_guncelle = mysqli_prepare($baglanti, "UPDATE urunler SET urun_stok = urun_stok - ? WHERE urun_id = ?");
        mysqli_stmt_bind_param($stok_guncelle, "ii", $item['quantity'], $item['id']);
        mysqli_stmt_execute($stok_guncelle);
        mysqli_stmt_close($stok_guncelle);

        // Sipariş ürünleri tablosuna ekle
        $siparis_urun_ekle = mysqli_prepare($baglanti, "INSERT INTO siparis_urunleri (siparis_id, urun_id, siparis_adet, siparis_birim_fiyat) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($siparis_urun_ekle, "iiid", $siparis_id, $item['id'], $item['quantity'], $item['price']);
        mysqli_stmt_execute($siparis_urun_ekle);
        mysqli_stmt_close($siparis_urun_ekle);
    }

    // Sepeti temizle
    $_SESSION['sepet'] = [];
    $_SESSION['mesaj'] = "Siparişiniz başarıyla oluşturuldu!";
    $_SESSION['mesaj_tipi'] = "success";
    header("Location: sepet.php?basarili=1");
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
                                <li><a class="dropdown-item" href="../siparişlerim.php"><i class="fas fa-box me-2"></i>Siparişlerim</a></li>
                                <li><a class="dropdown-item" href="../favori/favorilerim.php"><i class="fas fa-heart me-2"></i>Favorilerim</a></li>
                                <li><a class="dropdown-item" href="../Destek/destek.talepleri.php"><i class="fas fa-headset me-2"></i>Destek Taleplerim</a></li>
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

            <?php if (isset($_SESSION['mesaj'])): ?>
                <div class="alert alert-<?= $_SESSION['mesaj_tipi'] ?? 'info' ?>">
                    <?= $_SESSION['mesaj'] ?>
                </div>
                <?php unset($_SESSION['mesaj'], $_SESSION['mesaj_tipi']); ?>
            <?php endif; ?>

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
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-fluid" style="max-height: 100px;">
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
                <!-- Alışverişi Tamamla butonunu siparişlerim.php'ye yönlendiren form olarak değiştiriyoruz -->
                <form action="../siparişlerim.php" method="GET">
                    <button type="submit" class="btn btn-success mt-3">Alışverişi Tamamla</button>
                </form>
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
                    <li><a href="Hızlı Linkler/Gizlilik Politikası.txt" class="text-light">Gizlilik Politikası</a></li>
                    <li><a href="#" class="text-light">İade Koşulları</a></li>
                    <li><a href="#" class="text-light">İletişim</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Bizi Takip Edin</h5>
                <div class="social-links">
                    <a href="Hızlı Linkler/Gizlilik Politikası.txt" class="text-light me-2"><i class="fab fa-facebook"></i></a>
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
