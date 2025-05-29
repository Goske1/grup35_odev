<?php
session_start();
require_once 'baglanti.php';

// Sepet işlemleri için fonksiyonlar
function sepeteEkle($urun_id, $adet = 1) {
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = array();
    }
    
    if (isset($_SESSION['sepet'][$urun_id])) {
        $_SESSION['sepet'][$urun_id] += $adet;
    } else {
        $_SESSION['sepet'][$urun_id] = $adet;
    }
}

function sepettenCikar($urun_id) {
    if (isset($_SESSION['sepet'][$urun_id])) {
        unset($_SESSION['sepet'][$urun_id]);
    }
}

function sepetiGuncelle($urun_id, $adet) {
    if ($adet > 0) {
        $_SESSION['sepet'][$urun_id] = $adet;
    } else {
        sepettenCikar($urun_id);
    }
}

// POST işlemleri
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ekle'])) {
        $urun_id = $_POST['urun_id'];
        $adet = isset($_POST['adet']) ? (int)$_POST['adet'] : 1;
        sepeteEkle($urun_id, $adet);
    } elseif (isset($_POST['cikar'])) {
        $urun_id = $_POST['urun_id'];
        sepettenCikar($urun_id);
    } elseif (isset($_POST['guncelle'])) {
        $urun_id = $_POST['urun_id'];
        $adet = (int)$_POST['adet'];
        sepetiGuncelle($urun_id, $adet);
    }
    
    // AJAX isteği ise JSON yanıt döndür
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}

// Sepetteki ürünleri getir
$sepet_urunleri = array();
$toplam_fiyat = 0;

if (!empty($_SESSION['sepet'])) {
    $urun_ids = array_keys($_SESSION['sepet']);
    $urun_ids_str = implode(',', $urun_ids);
    
    $sql = "SELECT * FROM urunler WHERE id IN ($urun_ids_str)";
    $result = $conn->query($sql);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['adet'] = $_SESSION['sepet'][$row['id']];
            $row['ara_toplam'] = $row['fiyat'] * $row['adet'];
            $toplam_fiyat += $row['ara_toplam'];
            $sepet_urunleri[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim - Bijuteri Dünyası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="anasayfa.php">Bijuteri Dünyası</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="anasayfa.php">Ana Sayfa</a></li>
                <li class="nav-item"><a class="nav-link" href="MyWebsite/necklaces.php">Kolyeler</a></li>
                <li class="nav-item"><a class="nav-link" href="MyWebsite/rings.php">Yüzükler</a></li>
                <li class="nav-item"><a class="nav-link" href="MyWebsite/earrings.php">Küpeler</a></li>
                <li class="nav-item"><a class="nav-link" href="MyWebsite/bracelets.php">Bileklikler</a></li>
            </ul>
            <div class="d-flex">
                <?php if (isset($_SESSION["kullanici_adi"])): ?>
                    <span class="me-2 align-self-center">
                        <strong><?php echo htmlspecialchars($_SESSION["kullanici_adi"]); ?></strong>
                    </span>
                    <a href="cikis.php" class="btn btn-outline-dark me-2">Çıkış Yap</a>
                <?php else: ?>
                    <a href="giris.php" class="btn btn-outline-dark me-2">Giriş Yap</a>
                    <a href="kayit.php" class="btn btn-outline-dark me-2">Kayıt Ol</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Sepet İçeriği -->
<div class="container my-5">
    <h2 class="mb-4">Sepetim</h2>
    
    <?php if (empty($sepet_urunleri)): ?>
        <div class="alert alert-info">
            Sepetinizde ürün bulunmamaktadır.
            <a href="anasayfa.php" class="alert-link">Alışverişe başlamak için tıklayın</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ürün</th>
                        <th>Fiyat</th>
                        <th>Adet</th>
                        <th>Toplam</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sepet_urunleri as $urun): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($urun['resim']); ?>" 
                                         alt="<?php echo htmlspecialchars($urun['ad']); ?>" 
                                         class="img-thumbnail me-3" style="width: 100px;">
                                    <div>
                                        <h5 class="mb-0"><?php echo htmlspecialchars($urun['ad']); ?></h5>
                                        <small class="text-muted"><?php echo htmlspecialchars($urun['aciklama']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo number_format($urun['fiyat'], 2, ',', '.'); ?> TL</td>
                            <td>
                                <form method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="urun_id" value="<?php echo $urun['id']; ?>">
                                    <input type="number" name="adet" value="<?php echo $urun['adet']; ?>" 
                                           min="1" max="10" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" name="guncelle" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td><?php echo number_format($urun['ara_toplam'], 2, ',', '.'); ?> TL</td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="urun_id" value="<?php echo $urun['id']; ?>">
                                    <button type="submit" name="cikar" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Toplam:</strong></td>
                        <td><strong><?php echo number_format($toplam_fiyat, 2, ',', '.'); ?> TL</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Alışverişe Devam Et
            </a>
            <a href="odeme.php" class="btn btn-success">
                <i class="fas fa-credit-card"></i> Ödemeye Geç
            </a>
        </div>
    <?php endif; ?>
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
<script>
// Adet güncelleme işlemi için AJAX
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (this.querySelector('input[name="adet"]')) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('sepete.ekle.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
});
</script>
</body>
</html>
