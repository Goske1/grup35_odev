<!-- Ürün Kartları -->
<div class="container">
    <div class="row">
        <?php foreach ($urunler as $urun): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($urun['resim']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($urun['ad']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($urun['ad']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($urun['aciklama']); ?></p>
                        <p class="card-text"><strong><?php echo number_format($urun['fiyat'], 2, ',', '.'); ?> TL</strong></p>
                        <div class="d-flex justify-content-between">
                            <a href="favorilere.ekle.php?id=<?php echo $urun['id']; ?>" class="btn btn-outline-danger">
                                <i class="fas fa-heart"></i> Favorilere Ekle
                            </a>
                            <form method="POST" action="sepete.ekle.php" class="d-inline">
                                <input type="hidden" name="urun_id" value="<?php echo $urun['id']; ?>">
                                <input type="hidden" name="adet" value="1">
                                <button type="submit" name="ekle" class="btn btn-success">
                                    <i class="fas fa-shopping-cart"></i> Sepete Ekle
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div> 