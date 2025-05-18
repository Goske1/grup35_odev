<?php
session_start();
include("../baglanti.php");

if (isset($_POST["admin_giris"])) {
    $eposta = $_POST["eposta"];
    $sifre = $_POST["parola"];

    // E-posta ile admin bilgilerini çek
    $sorgu = "SELECT * FROM adminler WHERE admin_eposta = ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "s", $eposta);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($sonuc)) {
        // Şifreyi doğrula
        if (password_verify($sifre, $admin["admin_parola"])) {
            // Giriş başarılı
            $_SESSION["admin_id"] = $admin["admin_id"];
            $_SESSION["admin_ad_soyad"] = $admin["admin_ad_soyad"];
            $_SESSION["admin_eposta"] = $admin["admin_eposta"];

            // Son giriş tarihini güncelle
            $guncelle = "UPDATE adminler SET admin_last_login = NOW() WHERE admin_id = ?";
            $stmt_guncelle = mysqli_prepare($baglanti, $guncelle);
            mysqli_stmt_bind_param($stmt_guncelle, "i", $admin["admin_id"]);
            mysqli_stmt_execute($stmt_guncelle);
            mysqli_stmt_close($stmt_guncelle);

            // Admin paneline yönlendir
            header("Location: admin.panel.php");
            exit();
        } else {
            echo '<div class="alert alert-danger">Hatalı şifre!</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Böyle bir admin bulunamadı!</div>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($baglanti);
}
?>

<form method="POST" action="admin.giris.php">
    <h2>Admin Giriş</h2>
    E-Posta: <input type="email" name="eposta" required><br>
    Şifre: <input type="password" name="parola" required><br>
    <input type="submit" name="admin_giris" value="Giriş Yap">
</form>
