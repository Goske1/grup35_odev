<?php
ini_set('session.cookie_path', '/');
session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"])) {
    header("Location: giris.php");
    exit();
}

$musteri_id = $_SESSION["musteri_id"];
$sorgu = "SELECT musteri_ad_soyad, musteri_eposta, musteri_telefon, dogum_tarihi,cinsiyet FROM musteriler WHERE musteri_id = ?";
$stmt = mysqli_prepare($baglanti, $sorgu);
mysqli_stmt_bind_param($stmt, "i", $musteri_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $ad_soyad, $eposta, $telefon, $dogum_tarihi, $cinsiyet);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Bilgi güncelleme işlemleri kısmı
$mesaj = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // E-posta, telefon, doğum_tarihi, cinsiyet güncelle
    if (isset($_POST["ad_soyad"], $_POST["eposta"], $_POST["telefon"], $_POST["dogum_tarihi"], $_POST["cinsiyet"])) {
        $yeni_ad_soyad = trim($_POST["ad_soyad"]);
        $yeni_eposta = trim($_POST["eposta"]);
        $yeni_telefon = trim($_POST["telefon"]);
        $yeni_dogum_tarihi = trim($_POST["dogum_tarihi"]);
        $yeni_cinsiyet = trim($_POST["cinsiyet"]);
        $guncelle = mysqli_prepare($baglanti, "UPDATE musteriler SET musteri_ad_soyad=?, musteri_eposta=?, musteri_telefon=?, dogum_tarihi=?, cinsiyet=? WHERE musteri_id=?");
        mysqli_stmt_bind_param($guncelle, "sssssi", $yeni_ad_soyad, $yeni_eposta, $yeni_telefon, $yeni_dogum_tarihi, $yeni_cinsiyet, $musteri_id);
        if (mysqli_stmt_execute($guncelle)) {
            $mesaj = '<div class="alert alert-success">Bilgileriniz başarıyla güncellendi.</div>';
            $ad_soyad = $yeni_ad_soyad;
            $eposta = $yeni_eposta;
            $telefon = $yeni_telefon;
            $dogum_tarihi = $yeni_dogum_tarihi;
            $cinsiyet = $yeni_cinsiyet;
        } else {
            $mesaj = '<div class="alert alert-danger">Güncelleme sırasında hata oluştu.</div>';
        }
        mysqli_stmt_close($guncelle);
    }
    // Şifre güncelleme alanı profilim kısmındaki
    if (isset($_POST["eski_sifre"], $_POST["yeni_sifre"], $_POST["yeni_sifre_tekrar"])) {
        $eski_sifre = $_POST["eski_sifre"];
        $yeni_sifre = $_POST["yeni_sifre"];
        $yeni_sifre_tekrar = $_POST["yeni_sifre_tekrar"];
        // Mevcut şifreyi kontrol et
        $sorgu = mysqli_prepare($baglanti, "SELECT musteri_parola FROM musteriler WHERE musteri_id=?");
        mysqli_stmt_bind_param($sorgu, "i", $musteri_id);
        mysqli_stmt_execute($sorgu);
        mysqli_stmt_bind_result($sorgu, $mevcut_parola);
        mysqli_stmt_fetch($sorgu);
        mysqli_stmt_close($sorgu);
        if (password_verify($eski_sifre, $mevcut_parola)) {
            if ($yeni_sifre === $yeni_sifre_tekrar) { // Uzunluk kontrolü kaldırıldı
                $yeni_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
                $guncelle = mysqli_prepare($baglanti, "UPDATE musteriler SET musteri_parola=? WHERE musteri_id=?");
                mysqli_stmt_bind_param($guncelle, "si", $yeni_hash, $musteri_id);
                if (mysqli_stmt_execute($guncelle)) {
                    $mesaj = '<div class="alert alert-success">Şifreniz başarıyla değiştirildi.</div>';
                } else {
                    $mesaj = '<div class="alert alert-danger">Şifre güncellenemedi.</div>';
                }
                mysqli_stmt_close($guncelle);
            } else {
                $mesaj = '<div class="alert alert-warning">Yeni şifreler uyuşmuyor.</div>';
            }
        } else {
            $mesaj = '<div class="alert alert-danger">Eski şifreniz yanlış.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profilim | Lüks Bijüteri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .profile-card {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px;
        }
        .profile-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 16px;
            border: 2px solid #eee;
        }
        .profile-label {
            color: #888;
            font-size: 0.95rem;
        }
        .tab-content {
            margin-top: 32px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="profile-card text-center">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($ad_soyad) ?>&background=eee&color=555&size=128" alt="Avatar" class="profile-avatar mb-3">
            <h3 class="mb-3"><?= htmlspecialchars($ad_soyad) ?></h3>
            <?= $mesaj ?>
            <ul class="nav nav-tabs justify-content-center" id="profilTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="bilgi-tab" data-bs-toggle="tab" data-bs-target="#bilgi" type="button" role="tab">Kişisel Bilgiler</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="duzenle-tab" data-bs-toggle="tab" data-bs-target="#duzenle" type="button" role="tab">Bilgilerimi Düzenle</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sifre-tab" data-bs-toggle="tab" data-bs-target="#sifre" type="button" role="tab">Şifre Değiştir</button>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Kişisel Bilgiler alanımız  -->
                <div class="tab-pane fade show active" id="bilgi" role="tabpanel">
                    <div class="mb-3 mt-3">
                        <span class="profile-label"><i class="fas fa-envelope me-2"></i>E-posta:</span><br>
                        <strong><?= htmlspecialchars($eposta) ?></strong>
                    </div>
                    <div class="mb-3">
                        <span class="profile-label"><i class="fas fa-phone me-2"></i>Telefon:</span><br>
                        <strong><?= htmlspecialchars($telefon) ?></strong>
                    </div>
                    <div class="mb-3">
                        <span class="profile-label"><i class="fas fa-birthday-cake me-2"></i>Doğum Tarihi:</span><br>
                        <strong><?= htmlspecialchars($dogum_tarihi) ?></strong>
                    </div>
                    <div class="mb-3">
                        <span class="profile-label"><i class="fas fa-venus-mars me-2"></i>Cinsiyet:</span><br>
                        <strong>
                            <?php
                                if ($cinsiyet === "Erkek") echo "Erkek";
                                elseif ($cinsiyet === "Kadın") echo "Kadın";
                                else echo "Belirtilmedi";
                            ?>
                        </strong>
                    </div>
                </div>
                <!-- Bilgilerimi Düzenle alanı -->
                <div class="tab-pane fade" id="duzenle" role="tabpanel">
                    <form method="post" class="text-start mt-3">
                        <div class="mb-3">
                            <label class="form-label">Ad Soyad</label>
                            <input type="text" name="ad_soyad" class="form-control" value="<?= htmlspecialchars($ad_soyad) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="eposta" class="form-control" value="<?= htmlspecialchars($eposta) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($telefon) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Doğum Tarihi</label>
                            <input type="date" name="dogum_tarihi" class="form-control" value="<?= htmlspecialchars($dogum_tarihi) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cinsiyet</label>
                            <select name="cinsiyet" class="form-select">
                                <option value="">Belirtilmedi</option>
                                <option value="Erkek" <?= $cinsiyet === "Erkek" ? "selected" : "" ?>>Erkek</option>
                                <option value="Kadın" <?= $cinsiyet === "Kadın" ? "selected" : "" ?>>Kadın</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Bilgilerimi Kaydet</button>
                    </form>
                </div>
                <!-- Şifre Değiştime bolumu -->
                <div class="tab-pane fade" id="sifre" role="tabpanel">
                    <form method="post" class="text-start mt-3">
                        <div class="mb-3">
                            <label class="form-label">Mevcut Şifreniz</label>
                            <input type="password" name="eski_sifre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Yeni Şifre</label>
                            <input type="password" name="yeni_sifre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Yeni Şifre (Tekrar)</label>
                            <input type="password" name="yeni_sifre_tekrar" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Şifremi Değiştir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/7b3e5c6e2a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>