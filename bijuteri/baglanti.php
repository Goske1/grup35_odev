<?php

$host="LocalHost";
$kullanici="root";
$parola ="";
$db="bijuteri";


$baglanti = mysqli_connect($host,$kullanici,$parola,$db); //SIRA ÖNEMLİ YUKARDAKİ SIRAYA GÖRE 
//9 .SATIR VERI TABANIYLA BAĞLANTI KURAR


mysqli_set_charset($baglanti,"UTF8");

?>