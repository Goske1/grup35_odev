<?php

session_start();
include_once __DIR__ . '/../../baglanti.php';

// Admin kontrolü
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../admin.giris.php");
    exit();
}

// stoklist view'dan verileri çek
$stok_sorgu = "SELECT kategori_ad, urun_ad, urun_stok FROM stoklist";
$stok_sonuc = mysqli_query($baglanti, $stok_sorgu);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kategori ve Ürün Bazında Stok Listesi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Kategori ve Ürün Bazında Stok Listesi</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Ürün</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($stok_sonuc)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['kategori_ad']) ?></td>
                    <td><?= htmlspecialchars($row['urun_ad']) ?></td>
                    <td><?= $row['urun_stok'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../admin.panel.php" class="btn btn-secondary">Panele Dön</a>
</div>
</body>
</html>