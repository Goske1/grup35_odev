<?php
include_once __DIR__ . '/../../baglanti.php';
session_start();
// Yetki kontrolü
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../admin.giris.php");
    exit();
}

// Kategorileri veritabanından çek
$kategoriler = [];
$kategori_sorgu = "SELECT kategori_id, kategori_ad, kategori_sayfa FROM Kategoriler";
$kategori_sonuc = mysqli_query($baglanti, $kategori_sorgu);

// Eğer kategoriler tablosu boşsa, varsayılan kategorileri ekle
if (mysqli_num_rows($kategori_sonuc) == 0) {
    $varsayilan_kategoriler = [
        ['Yüzük', 'yüzük.php'],
        ['Kolye', 'kolye.php'],
        ['Bileklik', 'bileklik.php'],
        ['Küpe', 'küpe.php']
    ];
    
    foreach ($varsayilan_kategoriler as $kategori) {
        $ekle_sorgu = "INSERT INTO Kategoriler (kategori_ad, kategori_sayfa) VALUES (?, ?)";
        $stmt = mysqli_prepare($baglanti, $ekle_sorgu);
        mysqli_stmt_bind_param($stmt, "ss", $kategori[0], $kategori[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Kategorileri tekrar çek
    $kategori_sonuc = mysqli_query($baglanti, $kategori_sorgu);
}

while ($kategori = mysqli_fetch_assoc($kategori_sonuc)) {
    $kategoriler[] = $kategori;
}

$mesaj = '';
$mesaj_tipi = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ad = $_POST["urun_ad"];
    $aciklama = $_POST["urun_aciklama"];
    $fiyat = $_POST["urun_fiyat"];
    $stok = $_POST["urun_stok"];
    $kategori_id = $_POST["kategori_id"];
    $renk = $_POST["renk"];
    $marka = $_POST["marka"];
    $malzeme = $_POST["malzeme"];
    $beden = $_POST["beden"];
    $agirlik = $_POST["agirlik"];
    $tas = $_POST["tas"];
    $tas_agirlik = $_POST["tas_agirlik"];
    
    // Resim yükleme işlemi
    $resim_url = '';
    if(isset($_FILES['urun_resim']) && $_FILES['urun_resim']['error'] == 0) {
        $izin_verilen_uzantilar = array('jpg', 'jpeg', 'png', 'gif');
        $dosya_uzantisi = strtolower(pathinfo($_FILES['urun_resim']['name'], PATHINFO_EXTENSION));
        
        if(in_array($dosya_uzantisi, $izin_verilen_uzantilar)) {
            $yeni_dosya_adi = uniqid() . '.' . $dosya_uzantisi;
            $hedef_klasor = "../../urunler/images/";
            
            // Klasör yoksa oluştur
            if (!file_exists($hedef_klasor)) {
                mkdir($hedef_klasor, 0777, true);
            }
            
            $hedef_dosya = $hedef_klasor . $yeni_dosya_adi;
            
            if(move_uploaded_file($_FILES['urun_resim']['tmp_name'], $hedef_dosya)) {
                $resim_url = 'images/' . $yeni_dosya_adi;
            }
        }
    }

    // Urunler tablosuna eklerken resim_url yok!
    $sorgu = "INSERT INTO Urunler (urun_ad, urun_aciklama, urun_fiyat, urun_stok, kategori_id, renk, marka, malzeme, beden, agirlik, tas, tas_agirlik)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "ssdissssssdd", $ad, $aciklama, $fiyat, $stok, $kategori_id, $renk, $marka, $malzeme, $beden, $agirlik, $tas, $tas_agirlik);

    if (mysqli_stmt_execute($stmt)) {
        // Yeni eklenen ürünün ID'sini al
        $yeni_urun_id = mysqli_insert_id($baglanti);

        // Eğer resim yüklendiyse urunresimleri tablosuna ekle
        if ($resim_url) {
            $stmt_resim = mysqli_prepare($baglanti, "INSERT INTO urunresimleri (urun_id, resim_url) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt_resim, "is", $yeni_urun_id, $resim_url);
            mysqli_stmt_execute($stmt_resim);
            mysqli_stmt_close($stmt_resim);
        }

        // Başarı mesajını session'a kaydet
        $_SESSION['mesaj'] = "Ürün başarıyla eklendi!";
        $_SESSION['mesaj_tipi'] = "success";

        // Aynı sayfaya yönlendir
        header("Location: urun.ekle.php");
        exit();
    } else {
        $mesaj = "Ürün eklenirken hata oluştu.";
        $mesaj_tipi = "danger";
    }
}
// Ürün ekleme işlemi sırasında veya başka bir yerde urun_ekle_log tablosuna erişen kod olmadığından emin olun
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekle - Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($mesaj): ?>
                    <div class="alert alert-<?= $mesaj_tipi ?> alert-dismissible fade show" role="alert">
                        <?= $mesaj ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Yeni Ürün Ekle</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="urun_ad" class="form-label">Ürün Adı</label>
                                <input type="text" class="form-control" id="urun_ad" name="urun_ad" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="urun_aciklama" class="form-label">Açıklama</label>
                                <textarea class="form-control" id="urun_aciklama" name="urun_aciklama" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="urun_fiyat" class="form-label">Fiyat</label>
                                <input type="number" step="0.01" class="form-control" id="urun_fiyat" name="urun_fiyat" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="urun_stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="urun_stok" name="urun_stok" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori_id" name="kategori_id" required>
                                    <option value="">Kategori Seçin</option>
                                    <?php foreach ($kategoriler as $kategori): ?>
                                        <option value="<?= $kategori['kategori_id'] ?>"><?= htmlspecialchars($kategori['kategori_ad']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="renk" class="form-label">Renk</label>
                                <input type="text" class="form-control" id="renk" name="renk">
                            </div>
                            
                            <div class="mb-3">
                                <label for="marka" class="form-label">Marka</label>
                                <input type="text" class="form-control" id="marka" name="marka">
                            </div>
                            
                            <div class="mb-3">
                                <label for="malzeme" class="form-label">Malzeme</label>
                                <select class="form-select" id="malzeme" name="malzeme">
                                    <option value="">Tümü</option>
                                    <option value="altın">Altın</option>
                                    <option value="gümüş">Gümüş</option>
                                    <option value="platin">Platin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="beden" class="form-label">Beden</label>
                                <input type="text" class="form-control" id="beden" name="beden">
                            </div>
                            <div class="mb-3">
                                <label for="agirlik" class="form-label">Ağırlık (gr)</label>
                                <input type="number" step="0.01" class="form-control" id="agirlik" name="agirlik">
                            </div>
                            <div class="mb-3">
                                <label for="tas" class="form-label">Taş</label>
                                <input type="text" class="form-control" id="tas" name="tas">
                            </div>
                            <div class="mb-3">
                                <label for="tas_agirlik" class="form-label">Taş Ağırlığı (karat)</label>
                                <input type="number" step="0.01" class="form-control" id="tas_agirlik" name="tas_agirlik">
                            </div>
                            <div class="mb-3">
                                <label for="urun_resim" class="form-label">Ürün Resmi</label>
                                <input type="file" class="form-control" id="urun_resim" name="urun_resim" accept="image/*">
                                <small class="text-muted">İzin verilen formatlar: JPG, JPEG, PNG, GIF</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Ürün Ekle</button>
                                <a href="../admin.panel.php" class="btn btn-secondary">Geri Dön</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
