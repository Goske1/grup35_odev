<?php

session_start();
include("baglanti.php");

if (!isset($_SESSION["musteri_id"]) || empty($_SESSION['sepet'])) {
    header("Location: sepet/sepet.php");
    exit();
}

$musteri_id = $_SESSION["musteri_id"];
$sepet = $_SESSION['sepet'];
$mesaj = "";

// Sipariş formu gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adres = trim($_POST['adres']);
    $kart_no = trim($_POST['kart_no']);
    $kart_ad = trim($_POST['kart_ad']);
    $kart_skt = trim($_POST['kart_skt']);
    $kart_cvc = trim($_POST['kart_cvc']);

    // Adres kaydı için kullanılır
    $adres_ekle = mysqli_prepare($baglanti, "INSERT INTO adresler (musteri_id, musteri_adres) VALUES (?, ?)");
    mysqli_stmt_bind_param($adres_ekle, "is", $musteri_id, $adres);
    mysqli_stmt_execute($adres_ekle);
    $adres_id = mysqli_insert_id($baglanti);
    mysqli_stmt_close($adres_ekle);

    // Toplam tutarı hesaplama yaaprız
    $toplam_tutar = 0;
    foreach ($sepet as $item) {
        $toplam_tutar += $item['price'] * $item['quantity'];
    }

    // Sipariş oluşturulan sorgulama alanı
    $siparis_sorgu = mysqli_prepare($baglanti, "INSERT INTO siparisler (musteri_id, adres_id, toplam_tutar) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($siparis_sorgu, "iid", $musteri_id, $adres_id, $toplam_tutar);
    mysqli_stmt_execute($siparis_sorgu);
    $siparis_id = mysqli_insert_id($baglanti);
    mysqli_stmt_close($siparis_sorgu);

    // Sipariş ürünlerini ekler
    foreach ($sepet as $item) {
        $urun_id = $item['id'];
        $adet = $item['quantity'];
        $fiyat = $item['price'];
        $urun_sorgu = mysqli_prepare($baglanti, "INSERT INTO siparis_urunleri (siparis_id, urun_id, siparis_adet, siparis_birim_fiyat) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($urun_sorgu, "iiid", $siparis_id, $urun_id, $adet, $fiyat);
        mysqli_stmt_execute($urun_sorgu);
        mysqli_stmt_close($urun_sorgu);

        // Stok azaltma işlemi EKLENDİ
        $stok_guncelle = mysqli_prepare($baglanti, "UPDATE urunler SET urun_stok = urun_stok - ? WHERE urun_id = ?");
        mysqli_stmt_bind_param($stok_guncelle, "ii", $adet, $urun_id);
        mysqli_stmt_execute($stok_guncelle);
        mysqli_stmt_close($stok_guncelle);
    }

    // Sepeti temizler
    unset($_SESSION['sepet']);

    // Başarı mesajı gösterir
    $mesaj = '<div class="alert alert-success text-center">Ödeme başarılı! Siparişiniz alınmıştır.</div>';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme ve Sipariş Tamamla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Sipariş Bilgileri</h2>
    <?php if ($mesaj) echo $mesaj; ?>
    <?php if (!$mesaj): ?>
    <form method="POST" class="row g-3">
        <div class="col-12">
            <label class="form-label">Teslimat Adresi</label>
            <textarea name="adres" class="form-control" required></textarea>
        </div>
        <h4 class="mt-4">Kart Bilgileri</h4>
        <div class="col-md-6">
            <label class="form-label">Kart Numarası</label>
            <input type="text" name="kart_no" class="form-control" maxlength="19" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Kart Üzerindeki İsim</label>
            <input type="text" name="kart_ad" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Son Kullanma Tarihi (AA/YY)</label>
            <input type="text" name="kart_skt" class="form-control" maxlength="5" placeholder="12/29" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">CVC</label>
            <input type="text" name="kart_cvc" class="form-control" maxlength="4" required>
        </div>
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-success w-100">Siparişi Tamamla</button>
        </div>
    </form>
    <?php endif; ?>
</div>
</body>
</html>