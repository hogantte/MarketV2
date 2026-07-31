<?php
session_start();
require_once 'baglan.php';

$toplam_adet = 0;

if (!isset($_SESSION["giris"])) {
    echo json_encode([
        "basari" => false,
        "mesaj"  => "Giriş Yapmanız Gerekiyor",
        "durum"  => "hata" 
    ]);
    exit();
} elseif (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode([
        "basari" => false,
        "mesaj" => "Ürün Eklenirken Bir Hata Oluştu",
        "durum" => "hata"
    ]);
    exit();
}
$id = $_POST['id'];

$sorgu = $db->prepare("SELECT * FROM urunler WHERE id = ?");
$sorgu->execute([$id]);
$urun = $sorgu->fetch(PDO::FETCH_ASSOC);

if ($urun) {
    if (isset($_SESSION['sepet'][$id])) {
        $_SESSION['sepet'][$id]['adet'] += 1;
    } else {
        $_SESSION['sepet'][$id] = [
            'ad' => $urun["urun_ad"],
            'fiyat' => $urun["urun_fiyat"],
            'foto' => $urun["urun_foto"],
            'adet' => 1
        ];
    }



    foreach ($_SESSION['sepet'] as $urun) {
    $toplam_adet = $toplam_adet += $urun['adet'];
    }



    echo json_encode([
        "basari" => true,
        "mesaj"  => "Ürün Başarıyla Eklendi",
        "adet"   => $toplam_adet
    ]);
    exit();
}



?>