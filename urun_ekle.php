<?php 
session_start();
require_once 'baglan.php';

if (!isset($_SESSION["giris"])) {
    header("Location: giris.php?hata=izinsiz-erisim");
    exit;
}


if ($_POST){
    $urun_adi = $_POST['urun_adi'];
    $urun_aciklama = $_POST['urun_aciklama'];
    $urun_fiyat = $_POST['urun_fiyat'];
    $urun_stok = $_POST['urun_stok'];
    $kategori = $_POST['kategori_id'];

    if (empty($urun_adi) || empty($urun_aciklama) || empty($urun_fiyat) || empty($urun_stok) || empty($kategori)) {
        echo json_encode([
            "basari" => false,
            "mesaj" => "Bütün Alanları Doldurunuz",
            "durum" => "hata"
        ]);
    } else {


        $hedef_klasor = "urun-img/";
        $dosya_adi = $_SESSION["kullanici_id"] . "_" . time() . "_" . basename($_FILES["urun_foto"]["name"]);
        $hedef_yol = $hedef_klasor . $dosya_adi;

        if (move_uploaded_file($_FILES["urun_foto"]["tmp_name"], $hedef_yol)) {

            $kaydet = $db->prepare("INSERT INTO urunler (ekleyen_id , urun_ad , urun_aciklama , urun_fiyat , urun_stok , kategori_id , urun_foto) VALUES (?,?,?,?,?,?,?)");
            $sonuc = $kaydet->execute([$_SESSION["kullanici_id"], $urun_adi, $urun_aciklama, $urun_fiyat, $urun_stok, $kategori, $dosya_adi]);
            $yeni_id = $db->lastInsertId();

            $kategori_sorgu = $db->prepare("SELECT kategori_ad FROM kategoriler WHERE kategori_id = ?");
            $kategori_sorgu->execute([$kategori]);
            $kategori_veri = $kategori_sorgu->fetch(PDO::FETCH_ASSOC);
            $kategori_ad = $kategori_veri['kategori_ad'];


            if ($sonuc) {
                echo json_encode([
                    "basari" => true,
                    "mesaj" => "Ürün Başarıyla Eklendi",
                    "durum" => "basari",
                    "urun"  => [
                        "id" => $yeni_id,
                        "adi" => $urun_adi,
                        "aciklama" => $urun_aciklama,
                        "fiyat" => $urun_fiyat,
                        "stok" => $urun_stok,
                        "kategori" => $kategori_ad,
                        "foto" => $dosya_adi
                    ]
                ]);
            } else {
                echo json_encode([
                    "basari" => false,
                    "mesaj" => "Ürün Eklenirken Bir Hata Oluştu",
                    "durum" => "hata"
                ]);
            }
        } else{
            echo json_encode([
                "basari" => false,
                "mesaj" => "Bir Hata Oluştu",
                "durum" => "hata"
            ]);
        }
    }
}


?>