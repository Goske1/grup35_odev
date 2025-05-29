<?php
include("../baglanti.php");
session_start();

if (!isset($_SESSION["musteri_id"])) {
    $_SESSION["mesaj"] = "Lütfen giriş yapın.";
    $_SESSION["mesaj_tipi"] = "warning";
    header("Location: ../giris.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["urun_id"])) {
    $musteri_id = $_SESSION["musteri_id"];
    $urun_id = intval($_POST["urun_id"]);

    $sorgu = "DELETE FROM Favoriler WHERE musteri_id = ? AND urun_id = ?";
    $stmt = mysqli_prepare($baglanti, $sorgu);
    mysqli_stmt_bind_param($stmt, "ii", $musteri_id, $urun_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["mesaj"] = "Ürün favorilerden kaldırıldı.";
        $_SESSION["mesaj_tipi"] = "success";
    } else {
        $_SESSION["mesaj"] = "Favoriden kaldırma işlemi başarısız.";
        $_SESSION["mesaj_tipi"] = "danger";
    }

    header("Location: favorilerim.php");
    exit();
} else {
    $_SESSION["mesaj"] = "Geçersiz istek.";
    $_SESSION["mesaj_tipi"] = "warning";
    header("Location: favorilerim.php");
    exit();
}
?>
