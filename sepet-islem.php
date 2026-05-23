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
} elseif (isset($_GET['islem']) && $_GET['islem'] == "sil") {
    // Silinecek ürünün ID'sini alıyoruz
    $id = $_GET['id'];

    // Eğer bu ID'ye sahip bir ürün sepette gerçekten varsa...
    if (isset($_SESSION['sepet'][$id])) {
        // unset() ile o ürünü sepetten tamamen yok ediyoruz
        unset($_SESSION['sepet'][$id]);
    }

    // İşlem bittikten sonra kullanıcıyı geri sepet sayfasına yolluyoruz
    header("Location: sepet.php");
    exit;
}


?>