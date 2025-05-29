<?php
include("../baglanti.php");
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bileklikler - Lüks Bijüteri Mağazası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .product-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-info {
            padding: 15px;
        }
        .product-title {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #333;
        }
        .product-price {
            font-size: 1.2rem;
            color: #e44d26;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-material {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }
        .highlight-new {
            animation: highlight 2s ease-out;
        }
        @keyframes highlight {
            0% { background-color: #fff3cd; }
            100% { background-color: transparent; }
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
                        <a class="nav-link" href="kolye.php">Kolyeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="yüzük.php">Yüzükler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="küpe.php">Küpeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="bileklik.php">Bileklikler</a>
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
                    <form action="../sepet/sepet.php" method="POST" class="d-inline flex-grow-1">
                        <input type="hidden" name="urun_id" value="">
                        <input type="hidden" name="adet" value="1">
                        <button type="submit" class="btn btn-success w-100">🛒 Sepetiniz </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Syfa ust kısmı-->
    <div class="bg-light py-5">
        <div class="container">
            <h1 class="text-center">Bileklik Koleksiyonu</h1>
            <p class="text-center text-muted">En özel ve zarif bileklik tasarımlarımız</p>
        </div>
    </div>

    <!-- Ana ssayfa hattı -->
    <div class="container py-5">
        <?php if (isset($_SESSION['mesaj'])): ?>
            <div class="alert alert-<?= $_SESSION['mesaj_tipi'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['mesaj'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php 
            unset($_SESSION['mesaj']);
            unset($_SESSION['mesaj_tipi']);
            ?>
        <?php endif; ?>

        <div class="row">
            <!-- Filtreleme butan kısmı  -->
            <div class="col-md-3">
                <div class="filter-section">
                    <h4>Filtreler</h4>
                    <div class="mb-3">
                        <label class="form-label">Materyal</label>
                        <select class="form-select" id="material-filter">
                            <option value="">Tümü</option>
                            <option value="gold">Altın</option>
                            <option value="silver">Gümüş</option>
                            <option value="platinum">Platin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fiyat Aralığı</label>
                        <select class="form-select" id="price-filter">
                            <option value="">Tümü</option>
                            <option value="0-1000">0 - 1.000 TL</option>
                            <option value="1000-5000">1.000 - 5.000 TL</option>
                            <option value="5000+">5.000 TL ve üzeri</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Urunler -->
            <div class="col-md-9" id="product-list">
                <div class="row">
                    <?php
                    // İlk açılışta filtre.php'deki sorgunun aynısını kullan!
                    $sorgu = "SELECT u.*, k.kategori_ad,
                        (SELECT resim_url FROM urunresimleri ur WHERE ur.urun_id = u.urun_id LIMIT 1) as resim_url
                        FROM urunler u
                        JOIN kategoriler k ON u.kategori_id = k.kategori_id
                        WHERE k.kategori_ad = 'Bileklik'
                        ORDER BY u.urun_id DESC";
                    $sonuc = mysqli_query($baglanti, $sorgu);
                    if ($sonuc && mysqli_num_rows($sonuc) > 0) {
                        while ($urun = mysqli_fetch_assoc($sonuc)) {
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="product-card">
                                    <img src="<?= !empty($urun['resim_url']) ? htmlspecialchars($urun['resim_url']) : 'images/default-bracelet.jpg' ?>"
                                         class="product-image"
                                         alt="<?= htmlspecialchars($urun['urun_ad']) ?>">
                                    <div class="product-info">
                                        <h5 class="product-title"><?= htmlspecialchars($urun['urun_ad']) ?></h5>
                                        <p class="product-material"><?= htmlspecialchars($urun['malzeme']) ?></p>
                                        <p class="product-price"><?= number_format($urun['urun_fiyat'], 0, ',', '.') ?> TL</p>
                                        <div class="d-flex gap-2">
                                            <a href="ürün.detay.php?id=<?= $urun['urun_id'] ?>" class="btn btn-primary flex-grow-1">İncele</a>
                                            <form action="../sepet/sepete.ekle.php" method="POST" class="d-inline flex-grow-1">
                                                <input type="hidden" name="urun_id" value="<?= $urun['urun_id'] ?>">
                                                <input type="hidden" name="adet" value="1">
                                                <button type="submit" class="btn btn-success w-100">Sepete Ekle</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-12"><p class="text-center">Henüz bileklik ürünü bulunmamaktadır.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // AJAX filtreleme kullandık tum urunlerde aynısı var
        $('#material-filter, #price-filter').on('change', function() {
            var malzeme = $('#material-filter').val();
            var fiyat = $('#price-filter').val();
            $.get('filtre.php', { kategori: 'Bileklik', malzeme: malzeme, fiyat: fiyat }, function(data) {
                $('#product-list').html(data);
            });
        });
    </script>
</body>
</html>