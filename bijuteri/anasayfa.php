<?php
session_start();

// Sahte kullanıcı kontrolü
$kullanici_adi = $_SESSION["kullanici_adi"] ?? null;

// Sahte ürün listesi (normalde veritabanından gelir)
$urunler = [
    [
        "id" => 1,
        "ad" => "Altın Kaplama Kolye",
        "fiyat" => "249.99₺",
        "resim" => "resimler/kolye1.jpg"
    ],
    [
        "id" => 2,
        "ad" => "Zirkon Taşlı Küpe",
        "fiyat" => "179.99₺",
        "resim" => "resimler/kupe1.jpg"
    ],
    [
        "id" => 3,
        "ad" => "El Yapımı Bileklik",
        "fiyat" => "89.99₺",
        "resim" => "resimler/bileklik1.jpg"
    ]
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bijuteri Dünyası</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        .yorum-alani {
            margin-top: 50px;
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

<!-- ÜST MENÜ -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Bijuteri Dünyası</a>
    <form class="form-inline my-2 my-lg-0 mx-auto">
        <input class="form-control mr-sm-2" type="search" placeholder="Ürün ara..." aria-label="Ara">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Ara</button>
    </form>
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#">Favoriler</a></li>
        <li class="nav-item"><a class="nav-link" href="sepete.ekle.php">Sepet</a></li>
        <?php if ($kullanici_adi): ?>
            <li class="nav-item"><a class="nav-link" href="#">Merhaba, <?= htmlspecialchars($kullanici_adi) ?></a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="cikis.php">Çıkış</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="giris.php">Giriş</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- KATEGORİLER -->
<div class="container mb-4">
    <div class="row text-center">
        <div class="col"><a href="MyWebsite/necklaces.php" class="btn btn-outline-secondary btn-block">Kolye</a></div>
        <div class="col"><a href="MyWebsite/earrings.php" class="btn btn-outline-secondary btn-block">Küpe</a></div>
        <div class="col"><a href="MyWebsite/bracelets.php" class="btn btn-outline-secondary btn-block">Bileklik</a></div>
        <div class="col"><a href="MyWebsite/rings.php" class="btn btn-outline-secondary btn-block">Yüzük</a></div>
    </div>
</div>

<!-- ÜRÜNLER -->
<div class="container">
    <div class="row">
        <?php foreach ($urunler as $urun): ?>
            <div class="col-md-4">
                <div class="urun-kart">
                    <img src="<?= $urun['resim'] ?>" class="urun-resim" alt="<?= htmlspecialchars($urun['ad']) ?>">
                    <h5 class="mt-3"><?= htmlspecialchars($urun['ad']) ?></h5>
                    <p><strong><?= $urun['fiyat'] ?></strong></p>
                    <div class="d-flex justify-content-between">
                        <a href="favorilere.ekle.php?id=<?= $urun['id'] ?>" class="btn btn-outline-danger btn-sm">❤️ Favorilere Ekle</a>
                        <form method="POST" action="sepete.ekle.php" class="d-inline">
                            <input type="hidden" name="urun_id" value="<?= $urun['id'] ?>">
                            <input type="hidden" name="adet" value="1">
                            <button type="submit" name="ekle" class="btn btn-success btn-sm">🛒 Sepete Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- YORUMLAR -->
<div class="container yorum-alani">
    <h4>Kullanıcı Yorumları</h4>
    <div class="media mb-3">
        <img src="resimler/kullanici1.jpg" width="60" class="mr-3 rounded-circle" alt="Kullanıcı">
        <div class="media-body">
            <h6>Ayşe Y.</h6>
            <p>Kolye tam beklediğim gibi geldi, çok şık ve kaliteli.</p>
        </div>
    </div>

    <div class="media mb-3">
        <img src="resimler/kullanici2.jpg" width="60" class="mr-3 rounded-circle" alt="Kullanıcı">
        <div class="media-body">
            <h6>Burcu K.</h6>
            <p>Küpe tasarımı harika ama kargoda biraz gecikme oldu.</p>
        </div>
    </div>

    <!-- Yorum Formu -->
    <form method="post" action="yorum_gonder.php">
        <div class="form-group">
            <label for="yorum">Yorumunuzu yazın:</label>
            <textarea class="form-control" name="yorum" id="yorum" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
    </form>
</div>

<!-- ALT BİLGİ -->
<footer>
    &copy; <?= date("Y") ?> Bijuteri Dünyası. Tüm hakları saklıdır.
</footer>

</body>
</html>
