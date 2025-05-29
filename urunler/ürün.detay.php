<?php
session_start();
include_once __DIR__ . '/../baglanti.php';

// Ürün ID'si GET ile alınır
$urun_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ürün ve kategori bilgilerini çek
$sorgu = "SELECT u.*, k.kategori_ad 
          FROM urunler u 
          LEFT JOIN kategoriler k ON u.kategori_id = k.kategori_id 
          WHERE u.urun_id = ?";
$stmt = mysqli_prepare($baglanti, $sorgu);
mysqli_stmt_bind_param($stmt, "i", $urun_id);
mysqli_stmt_execute($stmt);
$sonuc = mysqli_stmt_get_result($stmt);
$urun = mysqli_fetch_assoc($sonuc);

if (!$urun) {
    echo "Ürün bulunamadı.";
    exit;
}

// Ürün resmini çek (ilk resmi)
$resim_url = "images/default.jpg";
$resim_sorgu = mysqli_prepare($baglanti, "SELECT resim_url FROM urunresimleri WHERE urun_id = ? LIMIT 1");
mysqli_stmt_bind_param($resim_sorgu, "i", $urun_id);
mysqli_stmt_execute($resim_sorgu);
$resim_sonuc = mysqli_stmt_get_result($resim_sorgu);
if ($row = mysqli_fetch_assoc($resim_sonuc)) {
    $resim_url = $row['resim_url'];
}
mysqli_stmt_close($resim_sorgu);

// Benzer ürünler (aynı kategoriden, kendisi hariç 3 ürün)
$benzer_urunler = [];
$benzer_sorgu = mysqli_prepare($baglanti, "SELECT u.urun_id, u.urun_ad, u.urun_fiyat, ur.resim_url 
    FROM urunler u 
    LEFT JOIN urunresimleri ur ON u.urun_id = ur.urun_id 
    WHERE u.kategori_id = ? AND u.urun_id != ? 
    GROUP BY u.urun_id 
    ORDER BY RAND() LIMIT 3");
mysqli_stmt_bind_param($benzer_sorgu, "ii", $urun['kategori_id'], $urun_id);
mysqli_stmt_execute($benzer_sorgu);
$benzer_sonuc = mysqli_stmt_get_result($benzer_sorgu);
while ($row = mysqli_fetch_assoc($benzer_sonuc)) {
    $benzer_urunler[] = $row;
}
mysqli_stmt_close($benzer_sorgu);

// Yorum ekleme işlemi
$yorum_mesaj = "";
if (isset($_POST['yorum_ekle']) && isset($_SESSION['musteri_id'])) {
    $yorum = trim($_POST['kullanici_yorum']);
    $puan = intval($_POST['kullanici_puan']);
    $musteri_id = $_SESSION['musteri_id'];

    if ($yorum && $puan >= 1 && $puan <= 5) {
        $ekle = mysqli_prepare($baglanti, "INSERT INTO yorumlar (urun_id, musteri_id, kullanici_yorum, kullanici_puan) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($ekle, "iisi", $urun_id, $musteri_id, $yorum, $puan);
        if (mysqli_stmt_execute($ekle)) {
            $yorum_mesaj = '<div class="alert alert-success">Yorumunuz kaydedildi.</div>';
        } else {
            $yorum_mesaj = '<div class="alert alert-warning">Bu ürüne zaten yorum yaptınız.</div>';
        }
        mysqli_stmt_close($ekle);
    } else {
        $yorum_mesaj = '<div class="alert alert-danger">Yorum ve puan zorunludur.</div>';
    }
}

// Yorumları çek
$yorumlar = [];
$yorum_sorgu = mysqli_prepare($baglanti, "SELECT y.*, m.musteri_ad_soyad FROM yorumlar y LEFT JOIN musteriler m ON y.musteri_id = m.musteri_id WHERE y.urun_id = ? ORDER BY y.kullanici_yorum_tarihi DESC");
mysqli_stmt_bind_param($yorum_sorgu, "i", $urun_id);
mysqli_stmt_execute($yorum_sorgu);
$yorum_sonuc = mysqli_stmt_get_result($yorum_sorgu);
while ($row = mysqli_fetch_assoc($yorum_sonuc)) {
    $yorumlar[] = $row;
}
mysqli_stmt_close($yorum_sorgu);

// Ürün bilgilerini veritabanından çekmek için örnek (dilersen):
// $urun_sorgu = mysqli_prepare($baglanti, "SELECT * FROM urunler WHERE urun_id = ?");
// mysqli_stmt_bind_param($urun_sorgu, "i", $urun_id);
// mysqli_stmt_execute($urun_sorgu);
// $urun_sonuc = mysqli_stmt_get_result($urun_sorgu);
// $urun = mysqli_fetch_assoc($urun_sonuc);
// mysqli_stmt_close($urun_sorgu);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Detayı - Lüks Bijüteri Mağazası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .product-gallery {
            margin-bottom: 20px;
        }
        .product-gallery img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-info {
            padding: 20px;
        }
        .product-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .product-price {
            font-size: 1.5rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
        }
        .product-description {
            margin-bottom: 2rem;
        }
        .product-meta {
            margin-bottom: 2rem;
        }
        .product-meta span {
            display: block;
            margin-bottom: 0.5rem;
        }
        .quantity-selector {
            width: 100px;
            margin-right: 1rem;
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
                    <li class="nav-item">
                        <a class="nav-link" href="../anasayfa.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kolye.php">Kolyeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="yüzük.php">Yüzükler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="küpe.php">Küpeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bileklik.php">Bileklikler</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="../urun.arama.php" class="btn btn-outline-dark me-2">
                        <i class="fas fa-search"></i>
                    </a>

                    <!-- Kullanıcı Menüsü -->
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
                    <a href="../sepet/sepet.php" class="btn btn-outline-dark">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Detail Section -->
    <div class="container py-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="product-gallery">
                    <img src="<?php echo htmlspecialchars($resim_url); ?>" alt="<?php echo htmlspecialchars($urun['urun_ad']); ?>" class="img-fluid">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="product-info">
                    <h1 class="product-title"><?php echo htmlspecialchars($urun['urun_ad']); ?></h1>
                    <div class="product-price"><?php echo number_format($urun['urun_fiyat'], 2, ',', '.'); ?> TL</div>
                    
                    <div class="product-description">
                        <p><?php echo nl2br(htmlspecialchars($urun['urun_aciklama'])); ?></p>
                    </div>

                    <div class="product-meta">
                        <span><strong>Kategori:</strong> <?php echo htmlspecialchars($urun['kategori_ad']); ?></span>
                        <span><strong>Materyal:</strong> <?php echo htmlspecialchars($urun['malzeme']); ?></span>
                        <span><strong>Beden:</strong> <?php echo htmlspecialchars($urun['beden']); ?></span>
                        <span><strong>Ağırlık:</strong> <?php echo htmlspecialchars($urun['agirlik']); ?></span>
                        <span><strong>Taş:</strong> <?php echo htmlspecialchars($urun['tas']); ?></span>
                        <span><strong>Taş Ağırlığı:</strong> <?php echo htmlspecialchars($urun['tas_agirlik']); ?></span>
                        <span><strong>Renk:</strong> <?php echo htmlspecialchars($urun['renk']); ?></span>
                        <span><strong>Marka:</strong> <?php echo htmlspecialchars($urun['marka']); ?></span>
                        <span><strong>Stok:</strong> <?php echo htmlspecialchars($urun['urun_stok']); ?></span>
                    </div>

                    <!-- Sepete Ekle Formu -->
                    <form action="../sepet/sepete.ekle.php" method="POST">
                        <input type="hidden" name="urun_id" value="<?php echo $urun_id; ?>">
                        <input type="hidden" name="urun_adi" value="<?php echo htmlspecialchars($urun['urun_ad']); ?>">
                        <input type="hidden" name="urun_fiyati" value="<?php echo htmlspecialchars($urun['urun_fiyat']); ?>">
                        <input type="hidden" name="urun_resmi" value="<?php echo htmlspecialchars($resim_url); ?>">
                        <div class="d-flex align-items-center mb-4">
                            <select class="form-select quantity-selector" name="adet">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-lg">Sepete Ekle</button>
                        </div>
                    </form>

                    <div class="product-actions">
                        <a href="../favori/favorilere.ekle.php?urun_id=<?php echo $urun_id; ?>" class="btn btn-outline-secondary me-2">
                            <i class="far fa-heart"></i> Favorilere Ekle
                        </a>
                        <!-- Paylaş butonu ve paylaş linki alanı -->
                        <button class="btn btn-outline-secondary" onclick="document.getElementById('paylas-link-alani').classList.toggle('d-none')">
                            <i class="fas fa-share-alt"></i> Paylaş
                        </button>
                    </div>
                    <div id="paylas-link-alani" class="mt-2 d-none">
                        <label for="paylas-link" class="form-label">Bu ürünü paylaşmak için linki kopyalayın:</label>
                        <input type="text" id="paylas-link" class="form-control mb-2" value="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" readonly>
                        <!-- Sosyal medya paylaş butonları eklemek istersen buraya ekleyebilirsin -->
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="btn btn-outline-primary"><i class="fab fa-facebook"></i> Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="btn btn-outline-info"><i class="fab fa-twitter"></i> Twitter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Yorumlar Bölümü -->
    <section class="product-reviews py-5">
        <div class="container">
            <h2 class="text-center mb-4">Yorumlar</h2>
            <?php echo $yorum_mesaj; ?>
            <?php if (isset($_SESSION['musteri_id'])): ?>
                <form action="" method="POST" class="mb-4">
                    <div class="mb-3">
                        <label for="kullanici_yorum" class="form-label">Yorumunuz</label>
                        <textarea class="form-control" id="kullanici_yorum" name="kullanici_yorum" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kullanici_puan" class="form-label">Puanınız</label>
                        <select class="form-select" id="kullanici_puan" name="kullanici_puan" required>
                            <option value="">Seçiniz</option>
                            <option value="5">5 ★★★★★</option>
                            <option value="4">4 ★★★★</option>
                            <option value="3">3 ★★★</option>
                            <option value="2">2 ★★</option>
                            <option value="1">1 ★</option>
                        </select>
                    </div>
                    <button type="submit" name="yorum_ekle" class="btn btn-primary">Yorum Ekle</button>
                </form>
            <?php else: ?>
                <p class="text-center">Yorum yapabilmek için <a href="../giris.php">giriş yapmalısınız</a>.</p>
            <?php endif; ?>

            <?php if (!empty($yorumlar)): ?>
                <div class="list-group">
                    <?php foreach ($yorumlar as $yorum): ?>
                        <div class="list-group-item">
                            <h5 class="mb-1"><?php echo htmlspecialchars($yorum['musteri_ad_soyad'] ?? 'Kullanıcı'); ?></h5>
                            <p class="mb-1"><?php echo nl2br(htmlspecialchars($yorum['kullanici_yorum'])); ?></p>
                            <small class="text-muted">
                                <?php for ($i = 0; $i < $yorum['kullanici_puan']; $i++) echo "★"; ?>
                                <?php for ($i = $yorum['kullanici_puan']; $i < 5; $i++) echo "☆"; ?>
                                - <?php echo date('d.m.Y H:i', strtotime($yorum['kullanici_yorum_tarihi'])); ?>
                            </small>
                            <?php if (!empty($yorum['admin_cevap'])): ?>
    <div class="mt-2 p-2 bg-light border rounded">
        <strong>Mağaza Yanıtı:</strong>
        <p class="mb-0"><?= nl2br(htmlspecialchars($yorum['admin_cevap'])) ?></p>
    </div>
<?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center">Henüz yorum yapılmamış.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Benzer Ürünler -->
    <section class="related-products py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Benzer Ürünler</h2>
            <div class="row">
                <?php foreach ($benzer_urunler as $related): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product-card h-100">
                            <img src="<?php echo htmlspecialchars($related['resim_url'] ?? 'images/default.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($related['urun_ad']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($related['urun_ad']); ?></h5>
                                <p class="card-text"><?php echo number_format($related['urun_fiyat'], 2, ',', '.'); ?> TL</p>
                                <a href="ürün.detay.php?id=<?php echo $related['urun_id']; ?>" class="btn btn-primary">İncele</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
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