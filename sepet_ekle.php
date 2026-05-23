<?php
require_once 'baglan.php';
session_start();

if (!isset($_SESSION["giris"])) {
    header("Location: giris.php?hata=izinsiz-erisim");
    exit;
} elseif (!isset($_SERVER['HTTP_REFERER'])) {
    header("Location: index.php");
    exit;
} elseif (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?hata=gecersiz-urun");
    exit;
} elseif (isset($_GET['islem']) && $_GET['islem'] == "ekle") {
    $id = $_GET['id'];

    $sorgu = $db->prepare("SELECT * FROM urunler WHERE id = ?");
    $sorgu->execute([$id]);
    $urun = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($urun) {
        if (isset($_SESSION['sepet'][$id])) {
            $_SESSION['sepet'][$id]['adet'] += 1; 
        } 
        else {
            $_SESSION['sepet'][$id] = [
                'ad' => $urun["urun_ad"],
                'fiyat' => $urun["urun_fiyat"],
                'foto' => $urun["urun_foto"],
                'adet' => 1
            ];
        }

        header("Location: " . $_SERVER['HTTP_REFERER'] . "?durum=sepet-eklendi");
        exit;
    }
}


?>