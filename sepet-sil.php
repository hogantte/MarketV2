<?php
session_start();
require_once 'baglan.php';

if (!isset($_SESSION['giris'])) {
    echo json_encode([
        "basari" => false,
        "mesaj" => "Lütfen Giriş Yapınız",
        "durum" => "hata"
    ]);
    exit();
} else {
    if ($_POST) {
        $toplam_adet = 0;
        $genel_toplam = 0;
        $id = $_POST['id'];

        if (is_numeric($id) && isset($_SESSION['sepet'][$id])) {


            unset($_SESSION['sepet'][$id]);

            foreach($_SESSION['sepet'] as $urun){
                $toplam_adet += $urun['adet'];
            }

            foreach ($_SESSION['sepet'] as $id => $urun){
                $ara_toplam = $urun['fiyat'] * $urun['adet'];
                $genel_toplam += $ara_toplam;
            }


            echo json_encode([
                "basari" => true,
                "mesaj"  => "Ürün Başarıyla Sepetten Silindi",
                "adet"   => $toplam_adet,
                "toplamFiyat" => $genel_toplam
            ]);
            exit();
        }

    }
}


?>