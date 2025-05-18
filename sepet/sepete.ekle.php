<?php
// Hata ayıklama açık
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantısı
require_once '../baglanti.php';

// Oturumu başlat
session_start();

// Sepet dizisi yoksa oluştur
if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

// POST ile ürün bilgileri gönderildiyse işleme al
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['urun_id'])) {
    
    // Ürün ID'sini al
    $urun_id = intval($_POST['urun_id']);
    
    // Veritabanından ürün detaylarını çek
    $stmt = mysqli_prepare($baglanti, "SELECT * FROM Urunler WHERE urun_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $urun_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $urun = mysqli_fetch_assoc($result);

        // Adet kontrolü
        $adet = isset($_POST['adet']) && intval($_POST['adet']) > 0 ? intval($_POST['adet']) : 1;

        // Ürün verisi oluştur
        $yeni_urun = [
            'id' => $urun['urun_id'],
            'name' => $urun['urun_ad'],
            'price' => $urun['urun_fiyat'],
            'image' => $urun['resim_url'],
            'quantity' => $adet,
            'kategori' => $urun['kategori_id'],
            'aciklama' => $urun['urun_aciklama'],
            'stok' => $urun['urun_stok']
        ];

        // Aynı ürün zaten sepette var mı kontrol et
        $urun_var = false;
        foreach ($_SESSION['sepet'] as &$sepet_urun) {
            if ($sepet_urun['id'] === $urun_id) {
                // Stok kontrolü
                if (($sepet_urun['quantity'] + $adet) <= $urun['urun_stok']) {
                    $sepet_urun['quantity'] += $adet;
                    $urun_var = true;
                    // Kategori stoktan düş
                    $kategori_id = $urun['kategori_id'];
                    $kategori_stok_guncelle = mysqli_prepare($baglanti, "UPDATE kategoriler SET kategori_stok = kategori_stok - ? WHERE kategori_id = ?");
                    mysqli_stmt_bind_param($kategori_stok_guncelle, "ii", $adet, $kategori_id);
                    mysqli_stmt_execute($kategori_stok_guncelle);
                    mysqli_stmt_close($kategori_stok_guncelle);
                } else {
                    $_SESSION['hata'] = "Üzgünüz, istediğiniz miktarda ürün stokta bulunmamaktadır.";
                }
                break;
            }
        }

        // Sepette yoksa ve stok yeterliyse yeni olarak ekle
        if (!$urun_var && $adet <= $urun['urun_stok']) {
            $_SESSION['sepet'][] = $yeni_urun;
            $_SESSION['mesaj'] = "Ürün sepete eklendi.";
            $_SESSION['mesaj_tipi'] = "success";
            // Kategori stoktan düş
            $kategori_id = $urun['kategori_id'];
            $kategori_stok_guncelle = mysqli_prepare($baglanti, "UPDATE kategoriler SET kategori_stok = kategori_stok - ? WHERE kategori_id = ?");
            mysqli_stmt_bind_param($kategori_stok_guncelle, "ii", $adet, $kategori_id);
            mysqli_stmt_execute($kategori_stok_guncelle);
            mysqli_stmt_close($kategori_stok_guncelle);
        }

        // Sepet sayfasına yönlendir
        header("Location: sepet.php");
        exit();
    } else {
        $_SESSION['mesaj'] = "Ürün bulunamadı.";
        $_SESSION['mesaj_tipi'] = "danger";
        header("Location: ../urunler/yüzük.php");
        exit();
    }
} else {
    // Eksik veri varsa uyarı göster
    $_SESSION['hata'] = "Ürün bilgileri eksik gönderildi.";
    header("Location: ../anasayfa.php");
    exit();
}
?>
