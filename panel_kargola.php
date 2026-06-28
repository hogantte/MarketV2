<?php
session_start();
require_once 'baglan.php';

if (!isset($_SESSION['giris'])) {
    header("Location:giris.php?hata=izinsiz-erisim");
    exit();
}

$kontrol = $db->prepare("SELECT kullanici_id FROM siparisler WHERE id = ?");
$kontrol->execute([$_GET['id']]);

if (($kontrol->fetchColumn()) != $_SESSION['kullanici_id']) {
    header("Location: hesabim.php?hata=izinsiz-erisim");
    exit();
} else {
$guncelle = $db->prepare("UPDATE siparisler SET durum = ? WHERE id = ? ");
$guncelle->execute(["kargolandi" , $_GET['id']]);

header("Location:satis_panel.php?durum=kargolama-basarili");
exit();
}

?>