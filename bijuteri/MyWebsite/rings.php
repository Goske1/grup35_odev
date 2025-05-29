<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yüzükler - Lüks Bijüteri Mağazası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .filter-section {
            background-color:rgb(254, 254, 254);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .product-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .ring-category {
            font-size: 0.9rem;
            color: #666;
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
                        <a class="nav-link" href="necklaces.php">Kolyeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="rings.php">Yüzükler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="earrings.php">Küpeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bracelets.php">Bileklikler</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="#" class="btn btn-outline-dark me-2">
                        <i class="fas fa-search"></i>
                    </a>
                    <a href="../giris.php" class="btn btn-outline-dark me-2">
                        <i class="fas fa-user"></i>
                    </a>
                    <a href="../sepete.ekle.php" class="btn btn-outline-dark">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-light py-5">
        <div class="container">
            <h1 class="text-center">Yüzük Koleksiyonu</h1>
            <p class="text-center text-muted">En özel ve zarif yüzük tasarımlarımız</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Filters -->
            <div class="col-md-3">
                <div class="filter-section">
                    <h4>Filtreler</h4>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select">
                            <option value="">Tümü</option>
                            <option value="diamond">Pırlantalı Yüzükler</option>
                            <option value="pearl">İnci Yüzükler</option>
                            <option value="fashion">Moda Yüzükler</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Materyal</label>
                        <select class="form-select">
                            <option value="">Tümü</option>
                            <option value="gold">Altın</option>
                            <option value="silver">Gümüş</option>
                            <option value="platinum">Platin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fiyat Aralığı</label>
                        <select class="form-select">
                            <option value="">Tümü</option>
                            <option value="0-1000">0 - 1.000 TL</option>
                            <option value="1000-5000">1.000 - 5.000 TL</option>
                            <option value="5000+">5.000 TL ve üzeri</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="col-md-9">
                <div class="row">
                    <?php
                    $rings = [
                        ['name' => 'Pırlantalı Yüzük', 'price' => '12.999', 'image' => 'images/diamond-ring.jpg', 'category' => 'diamond', 'material' => 'gold'],
                        ['name' => 'İnci Yüzük', 'price' => '9.499', 'image' => 'images/pearl-ring.jpg', 'category' => 'pearl', 'material' => 'silver'],
                        ['name' => 'Moda Yüzük', 'price' => '3.999', 'image' => 'images/fashion-ring.jpg', 'category' => 'fashion', 'material' => 'silver'],
                        ['name' => 'Altın Pırlantalı Yüzük', 'price' => '15.999', 'image' => 'images/gold-diamond-ring.jpg', 'category' => 'diamond', 'material' => 'gold'],
                        ['name' => 'Gümüş Moda Yüzük', 'price' => '2.499', 'image' => 'images/silver-fashion-ring.jpg', 'category' => 'fashion', 'material' => 'silver'],
                        ['name' => 'Platin İnci Yüzük', 'price' => '18.999', 'image' => 'images/platinum-pearl-ring.jpg', 'category' => 'pearl', 'material' => 'platinum']
                    ];

                    foreach ($rings as $index => $ring) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card product-card h-100">';
                        echo '<img src="' . $ring['image'] . '" class="card-img-top" alt="' . $ring['name'] . '">';
                        echo '<div class="card-body">';
                        echo '<span class="ring-category">' . ucfirst($ring['category']) . '</span>';
                        echo '<h5 class="card-title">' . $ring['name'] . '</h5>';
                        echo '<p class="card-text">' . number_format($ring['price'], 0, ',', '.') . ' TL</p>';
                        echo '<a href="product-detail.php?id=' . ($index + 1) . '" class="btn btn-primary">İncele</a>';
                        echo '</div></div></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

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