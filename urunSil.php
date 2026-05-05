<?php
session_start();
require_once 'baglan.php';

if (!isset($_SESSION["giris"])) {
    header("Location: index.php?hata=izinsiz-erisim");
    exit;
}

if (isset($_GET['id'])) {

    $silinecek_id = $_GET['id'];

    $sorgu = $db->prepare("SELECT urun_foto FROM urunler WHERE id = ? and ekleyen_id = ?");
    $sorgu->execute([$silinecek_id, $_SESSION["kullanici_id"]]);
    $urun = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($urun) {
        $dosyaYolu = "urun-img/" . $urun['urun_foto'];

        if (file_exists($dosyaYolu)) {
            unlink($dosyaYolu);
        }
    }


    $kontrol = $db->prepare("DELETE FROM urunler WHERE id = ? and ekleyen_id = ?");
    $sonuc = $kontrol->execute([$silinecek_id, $_SESSION["kullanici_id"]]);

    if ($sonuc) {

        header("Location: urunler.php?durum=silindi");
    } else {
        header("Location: urunler.php?durum=hata");
    }
}

?>