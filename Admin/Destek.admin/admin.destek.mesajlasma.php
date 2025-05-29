<?php
// filepath: c:\xampp\htdocs\bijuteri\Admin\destek.mesajlasma.php
include("../../baglanti.php");
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../admin.giris.php");
    exit();
}

// Admin mesajları okuduysa:
$talep_id = intval($_GET['talep_id'] ?? 0);
if (isset($_SESSION["admin_id"])) {
    mysqli_query($baglanti, "UPDATE destek_mesajlari SET okundu=1 WHERE talep_id=$talep_id AND gonderen='musteri'");
}

$talep_id = intval($_GET['talep_id'] ?? 0);

// Mesaj gönderme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mesaj"])) {
    $mesaj = trim($_POST["mesaj"]);
    $gonderen = "yetkili";
    $sorgu = "INSERT INTO destek_mesajlari (talep_id, gonderen, mesaj, mesaj_tarihi) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "iss", $talep_id, $gonderen, $mesaj);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Talep durumunu "Yanıtlandı" yap
    mysqli_query($baglanti, "UPDATE destek_talepleri SET durum='Yanıtlandı' WHERE talep_id=$talep_id");
}

// Talep bilgisi
$talep_sorgu = "SELECT * FROM destek_talepleri WHERE talep_id = ?";
$talep_stmt = mysqli_prepare($baglanti, $talep_sorgu);
mysqli_stmt_bind_param($talep_stmt, "i", $talep_id);
mysqli_stmt_execute($talep_stmt);
$talep_sonuc = mysqli_stmt_get_result($talep_stmt);
$talep = mysqli_fetch_assoc($talep_sonuc);
mysqli_stmt_close($talep_stmt);

// Mesajlar
$mesaj_sorgu = "SELECT * FROM destek_mesajlari WHERE talep_id = ? ORDER BY mesaj_tarihi ASC";
$mesaj_stmt = mysqli_prepare($baglanti, $mesaj_sorgu);
mysqli_stmt_bind_param($mesaj_stmt, "i", $talep_id);
mysqli_stmt_execute($mesaj_stmt);
$mesaj_sonuc = mysqli_stmt_get_result($mesaj_stmt);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Destek Mesajlaşma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h4>Konu: <?= htmlspecialchars($talep['konu']) ?></h4>
    <div class="border p-3 mb-3" style="background:#f8f9fa;max-height:300px;overflow-y:auto;">
        <?php while ($mesaj = mysqli_fetch_assoc($mesaj_sonuc)): ?>
            <div class="mb-2">
                <strong><?= $mesaj['gonderen'] == 'musteri' ? 'Müşteri' : 'Siz' ?>:</strong>
                <span><?= nl2br(htmlspecialchars($mesaj['mesaj'])) ?></span>
                <small class="text-muted float-end"><?= $mesaj['mesaj_tarihi'] ?></small>
            </div>
        <?php endwhile; ?>
    </div>
    <form method="post">
        <div class="mb-3">
            <textarea name="mesaj" class="form-control" rows="3" required placeholder="Yanıtınızı yazın..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
        <a href="admin.destek.talepleri.php" class="btn btn-secondary">Geri</a>
    </form>
</div>
</body>
</html>

