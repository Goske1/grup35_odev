<?php
include("../baglanti.php");

if (isset($_POST["admin_kaydet"])) {
    $ad_soyad = $_POST["ad_soyad"];
    $eposta = $_POST["admin_eposta"];
    $telefon = $_POST["admin_telefon"];
    $parola = password_hash($_POST["admin_parola"], PASSWORD_DEFAULT);

    // E-posta zaten var mı kontrolü
    $kontrol = $baglanti->prepare("SELECT admin_id FROM adminler WHERE admin_eposta = ?");
    $kontrol->bind_param("s", $eposta);
    $kontrol->execute();
    $kontrol->store_result();

    if ($kontrol->num_rows > 0) {
        echo '<div class="alert alert-warning">Bu e-posta adresi zaten kayıtlı.</div>';
        $kontrol->close();
        $baglanti->close();
        exit;
    }
    $kontrol->close();

    // Kayıt işlemi
    $stmt = $baglanti->prepare("INSERT INTO adminler (admin_ad_soyad, admin_eposta, admin_parola, admin_telefon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $ad_soyad, $eposta, $parola, $telefon);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Admin başarıyla kaydedildi.</div>';
    } else {
        echo '<div class="alert alert-danger">Hata oluştu: ' . $stmt->error . '</div>';
    }

    $stmt->close();
    $baglanti->close();
}
?>


<form method="POST" action="admin.kayit.php">
    <h2>Admin Kayıt Formu</h2>

    <label>Ad Soyad:</label><br>
    <input type="text" name="ad_soyad" required><br><br>

    <label>E-Posta:</label><br>
    <input type="email" name="admin_eposta" required><br><br>

    <label>Telefon:</label><br>
    <input type="text" name="admin_telefon"><br><br>

    <label>Şifre:</label><br>
    <input type="password" name="admin_parola" required><br><br>

    <input type="submit" name="admin_kaydet" value="Kayıt Ol">
</form>
