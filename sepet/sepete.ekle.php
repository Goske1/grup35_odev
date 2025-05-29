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
    $stmt = mysqli_prepare($baglanti, "SELECT u.urun_ad, u.urun_fiyat, u.urun_stok, (SELECT resim_url FROM urunresimleri WHERE urun_id = u.urun_id LIMIT 1) as resim_url FROM urunler u WHERE u.urun_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $urun_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $urun_ad, $urun_fiyat, $urun_stok, $resim_url);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    if ($urun_ad) {
        
        // Adet kontrolü
        $adet = isset($_POST['adet']) && intval($_POST['adet']) > 0 ? intval($_POST['adet']) : 1;
        
        // Ürün verisi oluşturma
        $yeni_urun = [
            'id' => $urun_id,
            'name' => $urun_ad,
            'price' => $urun_fiyat,
            'image' => !empty($resim_url) ? '../urunler/images/' . $resim_url : '../resimler/placeholder.jpg',
            'quantity' => $adet,
            'stok' => $urun_stok
        ];

        // Aynı ürün zaten sepette var mı kontrol et kısmı
        $urun_var = false;
        foreach ($_SESSION['sepet'] as &$sepet_urun) {
            if ($sepet_urun['id'] === $urun_id) {
                // Stok kontrolü
                if (($sepet_urun['quantity'] + $adet) <= $urun_stok) {
                    $sepet_urun['quantity'] += $adet;
                    $urun_var = true;
                } else {
                    $_SESSION['hata'] = "Üzgünüz, istediğiniz miktarda ürün stokta bulunmamaktadır.";
                }
                break;
            }
        }

        // Sepette yoksa ve stok yeterliyse yeni olarak ekle
        if (!$urun_var && $adet <= $urun_stok) {
            $_SESSION['sepet'][] = $yeni_urun;
            $_SESSION['mesaj'] = "Ürün sepete eklendi.";
            $_SESSION['mesaj_tipi'] = "success";
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
