<?php
$host = "localhost";
$kullanici = "root";
$sifre = "";
$veritabani = "x_tekstil";

$baglanti = new mysqli($host, $kullanici, $sifre, $veritabani);
if ($baglanti->connect_error) {
    die("Veritabanına bağlanılamadı: " . $baglanti->connect_error);
}
?>

