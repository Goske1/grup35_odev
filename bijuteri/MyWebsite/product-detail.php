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
            <a class="navbar-brand" href="index.php">Lüks Bijüteri</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="necklaces.php">Kolyeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rings.php">Yüzükler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Küpeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Bileklikler</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="#" class="btn btn-outline-dark me-2">
                        <i class="fas fa-search"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark me-2">
                        <i class="fas fa-user"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark">
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
                    <?php
                    // Get product ID from URL
                    $product_id = isset($_GET['id']) ? $_GET['id'] : 1;
                    
                    // Sample product data - In a real application, this would come from a database
                    $products = [
                        1 => [
                            'name' => 'Pırlantalı Nişan Yüzüğü',
                            'price' => '24.999',
                            'image' => 'images/engagement-ring.jpg',
                            'category' => 'engagement',
                            'material' => 'platinum',
                            'description' => 'Özel tasarım pırlantalı nişan yüzüğü. 0.5 karat pırlanta taşlı, platin kaplama.',
                            'size' => '16',
                            'weight' => '2.5 gr',
                            'stone' => 'Pırlanta',
                            'stone_weight' => '0.5 karat'
                        ],
                        2 => [
                            'name' => 'Klasik Evlilik Yüzüğü',
                            'price' => '8.499',
                            'image' => 'images/wedding-ring.jpg',
                            'category' => 'wedding',
                            'material' => 'gold',
                            'description' => 'Klasik tasarım altın evlilik yüzüğü. 14 ayar altın, mat yüzey.',
                            'size' => '17',
                            'weight' => '3.2 gr',
                            'stone' => 'Yok',
                            'stone_weight' => 'Yok'
                        ]
                    ];

                    $product = isset($products[$product_id]) ? $products[$product_id] : $products[1];
                    ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="product-info">
                    <h1 class="product-title"><?php echo $product['name']; ?></h1>
                    <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> TL</div>
                    
                    <div class="product-description">
                        <p><?php echo $product['description']; ?></p>
                    </div>

                    <div class="product-meta">
                        <span><strong>Kategori:</strong> <?php echo ucfirst($product['category']); ?></span>
                        <span><strong>Materyal:</strong> <?php echo ucfirst($product['material']); ?></span>
                        <span><strong>Beden:</strong> <?php echo $product['size']; ?></span>
                        <span><strong>Ağırlık:</strong> <?php echo $product['weight']; ?></span>
                        <span><strong>Taş:</strong> <?php echo $product['stone']; ?></span>
                        <span><strong>Taş Ağırlığı:</strong> <?php echo $product['stone_weight']; ?></span>
                    </div>

                    <form action="cart.php" method="post">
                        <div class="d-flex align-items-center mb-4">
                            <select class="form-select quantity-selector" name="quantity">
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
                        <button class="btn btn-outline-secondary me-2">
                            <i class="far fa-heart"></i> Favorilere Ekle
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-share-alt"></i> Paylaş
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <section class="related-products py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Benzer Ürünler</h2>
            <div class="row">
                <?php
                // Display related products
                foreach ($products as $id => $related) {
                    if ($id != $product_id) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card product-card h-100">';
                        echo '<img src="' . $related['image'] . '" class="card-img-top" alt="' . $related['name'] . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $related['name'] . '</h5>';
                        echo '<p class="card-text">' . number_format($related['price'], 0, ',', '.') . ' TL</p>';
                        echo '<a href="product-detail.php?id=' . $id . '" class="btn btn-primary">İncele</a>';
                        echo '</div></div></div>';
                    }
                }
                ?>
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