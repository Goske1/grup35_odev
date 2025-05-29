<?php
include("../baglanti.php");
session_start();

if (!isset($_SESSION["musteri_id"])) {
    $_SESSION['mesaj'] = "Favorileri görüntülemek için lütfen giriş yapın.";
    $_SESSION['mesaj_tipi'] = "warning";
    $_SESSION['redirect_after_login'] = "favori/favorilerim.php";
    header("Location: ../giris.php");
    exit();
}

$musteri_id = $_SESSION["musteri_id"];

$sorgu = "SELECT Urunler.* FROM Favoriler 
          INNER JOIN Urunler ON Favoriler.urun_id = Urunler.urun_id 
          WHERE Favoriler.musteri_id = ?";
$stmt = mysqli_prepare($baglanti, $sorgu);
mysqli_stmt_bind_param($stmt, "i", $musteri_id);
mysqli_stmt_execute($stmt);
$sonuc = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Favorilerim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Favori Ürünlerim</h2>

    <?php if (isset($_SESSION["mesaj"])): ?>
        <div class="alert alert-<?php echo $_SESSION["mesaj_tipi"]; ?>">
            <?php
                echo $_SESSION["mesaj"];
                unset($_SESSION["mesaj"]);
                unset($_SESSION["mesaj_tipi"]);
            ?>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($sonuc) > 0): ?>
        <div class="row">
            <?php while ($urun = mysqli_fetch_assoc($sonuc)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($urun['urun_resim'])): ?>
                            <img src="resimler/<?php echo $urun['urun_resim']; ?>" class="card-img-top" alt="Ürün Resmi">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($urun['urun_ad']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($urun['urun_aciklama']); ?></p>
                            <p class="card-text"><strong><?php echo $urun['urun_fiyat']; ?> TL</strong></p>
                            <form action="favori.sil.php" method="POST">
                                <input type="hidden" name="urun_id" value="<?php echo $urun['urun_id']; ?>">
                                <button type="submit" class="btn btn-danger w-100">Favorilerden Kaldır</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Henüz favori ürününüz bulunmamaktadır.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../anasayfa.php" class="btn btn-primary me-2">Anasayfaya Dön</a>
        <a href="javascript:history.back()" class="btn btn-secondary">Önceki Sayfaya Dön</a>
    </div>

</div>
</body>
</html>
