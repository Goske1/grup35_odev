<?php
session_start();
include("../baglanti.php");

if (isset($_POST["admin_giris"])) {
    // Formdan gelen verileri al
    $kullanici_adi = $_POST["kullanici_adi"];
    $sifre = $_POST["parola"];  // 'parola' olması doğru

    // Kullanıcı adı ile admin bilgilerini çek
    $sorgu = "SELECT * FROM adminler WHERE kullanici_adi = ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "s", $kullanici_adi);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);

    // Eğer admin verisi bulunduysa
    if ($admin = mysqli_fetch_assoc($sonuc)) {  // Burada $admin'i doğrudan alıyoruz
        // Şifreyi kontrol et
        if (password_verify($sifre, $admin["parola"])) {
            // Giriş başarılı, session bilgilerini kaydet
            $_SESSION["admin_id"] = $admin["admin_id"];
            $_SESSION["ad_soyad"] = $admin["ad_soyad"];
            $_SESSION["yetki_seviyesi"] = $admin["yetki_seviyesi"];
            
            // Admin paneline yönlendir
            header("Location: admin.panel.php");
            exit();
        } else {
            // Hatalı şifre
            echo '<div class="alert alert-danger">Hatalı şifre!</div>';
        }
    } else {
        // Admin bulunamadı
        echo '<div class="alert alert-danger">Böyle bir admin bulunamadı!</div>';
    }

    // Veritabanı bağlantılarını kapat
    mysqli_stmt_close($stmt);
    mysqli_close($baglanti);
}
?>

<form method="POST" action="admin.giris.php">
    <h2>Admin Giriş</h2>
    <!-- Kullanıcı adı alanı ile input name eşleşmeli -->
    Kullanıcı Adı: <input type="text" name="kullanici_adi" required><br>
    Şifre: <input type="password" name="parola" required><br>
    <input type="submit" name="admin_giris" value="Giriş Yap">
</form>
