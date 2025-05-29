<?php

include("../baglanti.php");
session_start();

if (!isset($_SESSION["musteri_id"])) {
    header("Location: ../giris.php");
    exit;
}

$musteri_id = $_SESSION["musteri_id"];
$sorgu = "SELECT * FROM destek_talepleri WHERE musteri_id = ? ORDER BY talep_tarihi DESC";
$stmt = mysqli_prepare($baglanti, $sorgu);
mysqli_stmt_bind_param($stmt, "i", $musteri_id);
mysqli_stmt_execute($stmt);
$sonuc = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Destek Taleplerim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h3>Destek Taleplerim</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Konu</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th>Detay</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($talep = mysqli_fetch_assoc($sonuc)): ?>
            <tr>
                <td><?= htmlspecialchars($talep['konu']) ?></td>
                <td><?= htmlspecialchars($talep['talep_tarihi']) ?></td>
                <td><?= htmlspecialchars($talep['durum']) ?></td>
                <td>
                    <a href="destek.mesajlasma.php?talep_id=<?= $talep['talep_id'] ?>" class="btn btn-sm btn-info">Mesajlaş</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../anasayfa.php" class="btn btn-secondary">Anasayfa</a>
</div>
</body>
</html>