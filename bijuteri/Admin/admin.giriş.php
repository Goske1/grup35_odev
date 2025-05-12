<?php
session_start();
include("../baglanti.php");

if (isset($_POST["admin_giris"])) {
    $kullanici_adi = $_POST["kullanici_adi"];
    $sifre = $_POST["sifre"];

    $sorgu = "SELECT * FROM adminler WHERE kullanici_adi = ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "s", $kullanici_adi);
    mysqli_stmt_execute($stmt);
    $sonuc = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($sonuc)) {
        if (password_verify($sifre, $admin["parola"])) {
            // Giriş başarılı
            $_SESSION["admin_id"] = $admin["admin_id"];
            $_SESSION["kullanici_adi"] = $admin["kullanici_adi"];
            $_SESSION["yetki_seviyesi"] = $admin["yetki_seviyesi"];

            // Son giriş zamanını güncelle
            $update = "UPDATE adminler SET son_giris = NOW() WHERE admin_id = " . $admin["admin_id"];
            mysqli_query($baglanti, $update);

            echo '<div class="alert alert-success">Hoş geldin, ' . $admin["kullanici_adi"] . '!</div>';
             header("Location: admin.panel.php");
        } else {
            echo '<div class="alert alert-danger">Hatalı şifre!</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Böyle bir admin yok!</div>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($baglanti);
}
?>

<form method="POST" action="admin.giris.php">
    <h2>Admin Giriş</h2>
    Kullanıcı Adı: <input type="text" name="kullanici_adi" required><br>
    Şifre: <input type="password" name="sifre" required><br>
    <input type="submit" name="admin_giris" value="Giriş Yap">
</form>
