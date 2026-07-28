<?php
session_start();
require_once 'baglan.php';

if (!isset($_SESSION["giris"])) {
    header("Location: giris.php?hata=izinsiz-erisim");
    exit();
}

if (empty($_SESSION['sepet'])) {
    echo json_encode([
        "basari" => false,
        "mesaj" => "Sepetiniz Boş Lüfen Birkaç Ürün Ekleyin.",
        "durum" => "hata"
    ]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") { 

$toplam = 0;
$urun_detaylari = [];

    try {
        $db->beginTransaction();

        foreach ($_SESSION['sepet'] as $urun_id => $detay) {

            $miktar = (int) $detay['adet'];

            $stmt = $db->prepare("SELECT id, urun_fiyat, urun_stok, urun_ad FROM urunler WHERE id = ? FOR UPDATE ");
            $stmt->execute([$urun_id]);
            $urun = $stmt->fetch(PDO::FETCH_ASSOC);


            if (!$urun) {
                throw new Exception("Ürün Bulunamadı");
            } else if ($urun['urun_stok'] < $miktar) {
                throw new Exception($urun['urun_ad'] . "Yeterli Stoğu Yok");
            }

            $urun_detaylari[$urun_id] = [   
                'ad' => $urun['urun_ad'],
                'miktar' => $miktar,
                'fiyat' => $urun['urun_fiyat']
            ];

            $toplam += $urun['urun_fiyat'] * $miktar;
        }
        $siparisler = $db->prepare("INSERT INTO siparisler (kullanici_id, toplam_fiyat) VALUES (?, ?) ");
        $siparisler->execute([$_SESSION['kullanici_id'], $toplam]);
        $siparis_id = $db->lastInsertId();


        $siparis_urun = $db->prepare("INSERT INTO siparis_urunleri (siparis_id, urun_id, urun_adi, miktar, fiyat) VALUES(?, ?, ?, ?, ?)");
        $stok_guncelle = $db->prepare("UPDATE urunler SET urun_stok = urun_stok - ? WHERE id = ?");

        foreach ($urun_detaylari as $urun_id => $detay) {

            $siparis_urun->execute([$siparis_id, $urun_id, $detay['ad'], $detay['miktar'], $detay['fiyat']]);
            $stok_guncelle->execute([$detay['miktar'], $urun_id]);
        }

        $db->commit();

        unset($_SESSION['sepet']);
        echo json_encode([
            "basari" => true,
            "mesaj"  => "Siparişiniz Alındı",
            "durum"  => "basari"
        ]);
        exit();

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode([
            "basari" => false,
            "mesaj" => $e->getMessage(),
            "durum" => "hata"
        ]);
        exit();
    }



}

?>