<?php
session_start();

// Oturumu sonlandır
$_SESSION = array();  // Oturumdaki tüm verileri temizle
session_destroy();  // Oturumu tamamen yok et

// Admin giriş sayfasına yönlendir
header("Location: admin.giris.php");
exit();
