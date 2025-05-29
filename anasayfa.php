<?php
include("baglanti.php");
session_start();

// Kullanıcı adı (oturum kontrolü)
$kullanici_adi = $_SESSION["kullanici_ad_soyad"] ?? null;

// Ürünleri veritabanından çek
$urunler = [];

// Her kategoriden 3'er ürün çekmek için sorgu yazdık
$sorgu = "(SELECT u.urun_id, u.urun_ad, u.urun_fiyat, ur.resim_url, k.kategori_ad
          FROM urunler u 
          LEFT JOIN urunresimleri ur ON u.urun_id = ur.urun_id 
          JOIN kategoriler k ON u.kategori_id = k.kategori_id
          WHERE k.kategori_ad = 'Yüzük'
          GROUP BY u.urun_id
          ORDER BY u.urun_id DESC 
          LIMIT 3)
          UNION ALL
          (SELECT u.urun_id, u.urun_ad, u.urun_fiyat, ur.resim_url, k.kategori_ad
          FROM urunler u 
          LEFT JOIN urunresimleri ur ON u.urun_id = ur.urun_id 
          JOIN kategoriler k ON u.kategori_id = k.kategori_id
          WHERE k.kategori_ad = 'Kolye'
          GROUP BY u.urun_id
          ORDER BY u.urun_id DESC 
          LIMIT 3)
          UNION ALL
          (SELECT u.urun_id, u.urun_ad, u.urun_fiyat, ur.resim_url, k.kategori_ad
          FROM urunler u 
          LEFT JOIN urunresimleri ur ON u.urun_id = ur.urun_id 
          JOIN kategoriler k ON u.kategori_id = k.kategori_id
          WHERE k.kategori_ad = 'Küpe'
          GROUP BY u.urun_id
          ORDER BY u.urun_id DESC 
          LIMIT 3)
          UNION ALL
          (SELECT u.urun_id, u.urun_ad, u.urun_fiyat, ur.resim_url, k.kategori_ad
          FROM urunler u 
          LEFT JOIN urunresimleri ur ON u.urun_id = ur.urun_id 
          JOIN kategoriler k ON u.kategori_id = k.kategori_id
          WHERE k.kategori_ad = 'Bileklik'
          GROUP BY u.urun_id
          ORDER BY u.urun_id DESC 
          LIMIT 3)";

$sonuc = mysqli_query($baglanti, $sorgu);

if ($sonuc) {
    while ($row = mysqli_fetch_assoc($sonuc)) {
        $urunler[] = $row;
    }
} else {
    echo "<div class='alert alert-danger'>Sorgu hazırlanırken hata oluştu: " . mysqli_error($baglanti) . "</div>";
}

// Yeni destek mesajı bildirimi için değişken
$okunmamis_admin_mesaj = 0;
if (isset($_SESSION["musteri_id"])) {
    $musteri_id = $_SESSION["musteri_id"];
    $sorgu = "SELECT COUNT(*) as sayi FROM destek_mesajlari dm
              JOIN destek_talepleri dt ON dm.talep_id = dt.talep_id
              WHERE dt.musteri_id = $musteri_id AND dm.gonderen = 'yetkili' AND dm.okundu = 0";
    $sonuc = mysqli_query($baglanti, $sorgu);
    $okunmamis_admin_mesaj = mysqli_fetch_assoc($sonuc)['sayi'];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bijuteri Dünyası</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .urun-kart {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .urun-resim {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
        }
        .navbar {
            margin-bottom: 30px;
        }
        footer {
            margin-top: 60px;
            background-color: #f8f9fa;
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
<?php if ($okunmamis_admin_mesaj > 0): ?>
    <div class="alert alert-info text-center mb-0">
        <strong>Yeni destek mesajınız var! <a href="Destek/destek.talepleri.php" class="alert-link">Görüntüle</a></strong>
    </div>
<?php endif; ?>
<!-- Sayfanın UST kısmı  -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Lüks Bijuteri</a>
    <form class="form-inline my-2 my-lg-0 mx-auto" method="get" action="urun.arama.php">
        <input class="form-control mr-sm-2" type="search" name="q" placeholder="Ürün ara..." aria-label="Ara">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Ara</button>
    </form>
    <div class="d-flex">
        <div class="d-flex align-items-center">
            <a href="favori/favorilerim.php" class="btn btn-outline-dark me-2 position-relative">
                <i class="fas fa-heart"></i>
                <?php if (isset($_SESSION["musteri_id"])): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php
                        // Favori sayısını veritabanından çek
                        $favori_sorgu = "SELECT COUNT(*) as favori_sayisi FROM favoriler WHERE musteri_id = ?";
                        $favori_stmt = mysqli_prepare($baglanti, $favori_sorgu);
                        mysqli_stmt_bind_param($favori_stmt, "i", $_SESSION["musteri_id"]);
                        mysqli_stmt_execute($favori_stmt);
                        $favori_sonuc = mysqli_stmt_get_result($favori_stmt);
                        $favori_sayisi = mysqli_fetch_assoc($favori_sonuc)['favori_sayisi'];
                        echo $favori_sayisi;
                        mysqli_stmt_close($favori_stmt);
                        ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="sepet/sepet.php" class="btn btn-outline-dark me-2 position-relative">                
                <i class="fas fa-shopping-cart"></i>
                <?php if (isset($_SESSION['sepet']) && count($_SESSION['sepet']) > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= count($_SESSION['sepet']) ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="#" class="btn btn-outline-primary me-2" data-toggle="modal" data-target="#destekTalepModal">
                <i class="fas fa-headset"></i> Destek Talebi
            </a>
            <!-- Kullanıcı dropdown menüsü -->
            <?php if (isset($_SESSION["kullanici_ad_soyad"])): ?>
                <div class="dropdown me-2">
                    <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION["kullanici_ad_soyad"]) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profil.php"><i class="fas fa-user me-2"></i>Profilim</a></li>
                        <li><a class="dropdown-item" href="siparişlerim.php"><i class="fas fa-box me-2"></i>Siparişlerim</a></li>
                        <li><a class="dropdown-item" href="favori/favorilerim.php"><i class="fas fa-heart me-2"></i>Favorilerim</a></li>
                        <li><a class="dropdown-item" href="Destek/destek.talepleri.php"><i class="fas fa-headset me-2"></i>Destek Taleplerim</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="çikis.php"><i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="giris.php" class="btn btn-outline-dark"><i class="fas fa-sign-in-alt me-1"></i>Giriş Yap</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- KATEGORİLER -->
<div class="container mb-4">
    <div class="row text-center">
        <div class="col"><a href="urunler/kolye.php" class="btn btn-outline-secondary btn-block">Kolye</a></div>
        <div class="col"><a href="urunler/küpe.php" class="btn btn-outline-secondary btn-block">Küpe</a></div>
        <div class="col"><a href="urunler/bileklik.php" class="btn btn-outline-secondary btn-block">Bileklik</a></div>
        <div class="col"><a href="urunler/yüzük.php" class="btn btn-outline-secondary btn-block">Yüzük</a></div>
    </div>
</div>

<!-- ÜRÜNLER -->
<div class="container">
    <div class="row">
        <?php foreach ($urunler as $urun): ?>
            <div class="col-md-3 mb-4">
                <div class="urun-kart">
                    <img src="<?= !empty($urun['resim_url']) ? 'urunler/images/' . htmlspecialchars(basename($urun['resim_url'])) : 'resimler/placeholder.jpg' ?>"
                         class="urun-resim" alt="<?= htmlspecialchars($urun['urun_ad']) ?>">
                    <h5 class="mt-3"><?= htmlspecialchars($urun['urun_ad']) ?></h5>
                    <p><strong><?= number_format($urun['urun_fiyat'], 2) ?>₺</strong></p>
                    <div class="d-flex gap-2">
                        <?php if (isset($_SESSION["musteri_id"])): ?>
                            <a href="favori/favorilere.ekle.php?urun_id=<?= $urun['urun_id'] ?>" class="btn btn-outline-danger flex-grow-1">❤️ Favorilere Ekle</a>
                        <?php else: ?>
                            <a href="giris.php" class="btn btn-outline-danger flex-grow-1">❤️ Favorilere Ekle</a>
                        <?php endif; ?>
                        <form action="sepet/sepete.ekle.php" method="POST" class="d-inline flex-grow-1">
                            <input type="hidden" name="urun_id" value="<?= $urun['urun_id'] ?>">
                            <input type="hidden" name="adet" value="1">
                            <button type="submit" class="btn btn-success w-100">🛒 Sepete Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- DESTEK TALEP MODAL -->
<div class="modal fade" id="destekTalepModal" tabindex="-1" aria-labelledby="destekTalepModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="Destek/destek.talep.gonder.php">
        <div class="modal-header">
          <h5 class="modal-title" id="destekTalepModalLabel">Destek Talebi Oluştur</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <?php if (isset($_GET['talep']) && $_GET['talep'] == 'ok'): ?>
                <div class="alert alert-success">Talebiniz başarıyla iletildi. En kısa sürede dönüş yapılacaktır.</div>
            <?php endif; ?>
            <div class="mb-3">
                <label>Ad Soyad</label>
                <input type="text" name="ad_soyad" class="form-control" required value="<?= htmlspecialchars($_SESSION['kullanici_ad_soyad'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>E-posta</label>
                <input type="email" name="eposta" class="form-control" required value="<?= htmlspecialchars($_SESSION['kullanici_eposta'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>Konu</label>
                <input type="text" name="konu" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mesajınız</label>
                <textarea name="mesaj" class="form-control" rows="4" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
          <button type="submit" class="btn btn-primary">Gönder</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ALT BİLGİ -->
<footer>
    &copy; <?= date("Y") ?> Bijuteri Dünyası. Tüm hakları saklıdır.
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>