<?php
include("../baglanti.php");


if (isset($_POST["admin_kaydet"])) {
    $kullanici_adi = $_POST["kullanici_adi"];
    $sifre = password_hash($_POST["sifre"], PASSWORD_DEFAULT);
    $ad_soyad = $_POST["ad_soyad"];
    $yetki = $_POST["yetki_seviyesi"];

    $sorgu = "INSERT INTO adminler (kullanici_adi, parola, ad_soyad, yetki_seviyesi)
              VALUES ('$kullanici_adi', '$sifre', '$ad_soyad', '$yetki')";

    $calistir = mysqli_query($baglanti, $sorgu);

    if ($calistir) {
        echo '<div class="alert alert-success">Admin başarıyla kaydedildi.</div>';
    } else {
        echo '<div class="alert alert-danger">Hata oluştu: ' . mysqli_error($baglanti) . '</div>';
    }

    mysqli_close($baglanti);
}
?>

<form method="POST" action="admin.kayit.php">
    <h2>Admin Kayıt</h2>
    Kullanıcı Adı: <input type="text" name="kullanici_adi" required><br>
    Şifre: <input type="password" name="sifre" required><br>
    Ad Soyad: <input type="text" name="ad_soyad"><br>
    Yetki: 
    <select name="yetki_seviyesi">
        <option value="tam">Tam</option>
        <option value="urun_yonetimi">Ürün Yönetimi</option>
        <option value="siparis_yonetimi">Sipariş Yönetimi</option>
    </select><br>
    <input type="submit" name="admin_kaydet" value="Kayıt Ol">
</form>
