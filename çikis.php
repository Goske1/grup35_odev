<?php
session_start();
$_SESSION = array();

// Oturum çerezini sil
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Çıkış sonrası mesaj için yönlendirme
header("Location: giris.php?cikis=1");
exit;
?>