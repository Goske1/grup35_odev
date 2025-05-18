<?php
include("../baglanti.php");

// Kategori parametresi alınıyor (Kolye, Yüzük, Küpe, Bileklik)
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : 'Kolye';
$malzeme = isset($_GET['malzeme']) ? trim($_GET['malzeme']) : '';
$fiyat = isset($_GET['fiyat']) ? trim($_GET['fiyat']) : '';

$where = "WHERE k.kategori_ad = '" . mysqli_real_escape_string($baglanti, $kategori) . "'";

if ($malzeme != '') {
    if ($malzeme == 'gold') $where .= " AND u.malzeme = 'altın'";
    elseif ($malzeme == 'silver') $where .= " AND u.malzeme = 'gümüş'";
    elseif ($malzeme == 'platinum') $where .= " AND u.malzeme = 'platin'";
}
if ($fiyat != '') {
    if ($fiyat == '0-1000') $where .= " AND u.urun_fiyat BETWEEN 0 AND 1000";
    elseif ($fiyat == '1000-5000') $where .= " AND u.urun_fiyat BETWEEN 1000 AND 5000";
    elseif ($fiyat == '5000+') $where .= " AND u.urun_fiyat > 5000";
}

// Ürün resmi için urunresimleri tablosundan ilk resmi çekiyoruz
$sorgu = "SELECT u.*, k.kategori_ad,
         (SELECT resim_url FROM urunresimleri ur WHERE ur.urun_id = u.urun_id LIMIT 1) as resim_url
          FROM urunler u
          JOIN kategoriler k ON u.kategori_id = k.kategori_id
          $where
          ORDER BY u.urun_id DESC";
$sonuc = mysqli_query($baglanti, $sorgu);

if ($sonuc && mysqli_num_rows($sonuc) > 0) {
    while ($urun = mysqli_fetch_assoc($sonuc)) {
        // Kategoriye göre varsayılan resim
        $default_img = 'images/default-necklace.jpg';
        if ($kategori == 'Yüzük') $default_img = 'images/default-ring.jpg';
        elseif ($kategori == 'Küpe') $default_img = 'images/default-earring.jpg';
        elseif ($kategori == 'Bileklik') $default_img = 'images/default-bracelet.jpg';

        // Ürün resmi varsa onu kullan, yoksa varsayılanı kullan
        $img = !empty($urun['resim_url']) ? htmlspecialchars($urun['resim_url']) : $default_img;
        ?>
        <div class="col-md-4 mb-4">
            <div class="product-card">
                <img src="<?= $img ?>" class="product-image" alt="<?= htmlspecialchars($urun['urun_ad']) ?>">
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
    echo '<div class="col-12"><p class="text-center">Henüz ürün bulunmamaktadır.</p></div>';
}
?>