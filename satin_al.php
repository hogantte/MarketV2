<?php
require_once 'baglan.php';
session_start();

if (!isset($_SESSION["giris"])) {
    header("Location:giris.php?hata=izinsiz-erisim");
    exit();
}

if (empty($_SESSION['sepet'])) {
    header("Location:sepet.php?hata=sepet-bos");
    exit();
}

$toplam = 0;

try {



    $db->beginTransaction();

    $urunDetaylari = [];

    foreach ($_SESSION['sepet'] as $urun_id => $detay) {



        $miktar = (int) $detay['adet'];

        $stmt = $db->prepare("SELECT id, urun_fiyat, urun_stok, urun_ad FROM urunler WHERE id = ? FOR UPDATE ");
        $stmt->execute([$urun_id]);
        $urun = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$urun) {
            throw new Exception("Ürün bulunamadı (id: $urun_id)");
        }

        if ($urun['urun_stok'] < $miktar) {
            throw new Exception("Yeterli stok yok (ürün id: $urun_id)");
        }

        $urunDetaylari[$urun_id] = [
            'ad' => $urun['urun_ad'],
            'miktar' => $miktar,
            'fiyat' => $urun['urun_fiyat'],
        ];

        $toplam += $urun['urun_fiyat'] * $miktar;
    }

    $siparisler = $db->prepare("INSERT INTO siparisler (kullanici_id, toplam_fiyat) VALUES (?, ?)");
    $siparisler->execute([$_SESSION["kullanici_id"], $toplam]);
    $siparis_id = $db->lastInsertId();



    $siparis_urun = $db->prepare("INSERT INTO siparis_urunleri (siparis_id, urun_id, urun_adi, miktar, fiyat) VALUES (?, ?, ?, ?, ? )");
    $stokGuncelle = $db->prepare("UPDATE urunler SET urun_stok = urun_stok - ? WHERE id = ?");

    foreach ($urunDetaylari as $urun_id => $detay) {

        $sql = "INSERT INTO siparis_urunleri
(siparis_id, urun_id, urun_adi, miktar, fiyat)
VALUES (?, ?, ?, ?, ?)";

        echo $sql . "<br>";

        print_r([
            $siparis_id,
            $urun_id,
            $detay['ad'],
            $detay['miktar'],
            $detay['fiyat']
        ]);

        exit;


        $stokGuncelle->execute([$detay['miktar'], $urun_id]);
    }

    $db->commit();

    unset($_SESSION['sepet']);

    header("Location:sepet.php?basarili=siparis-alindi");
    exit();

} catch (Exception $e) {
    $db->rollBack();
    header("Location:sepet.php?hata=" . urlencode($e->getMessage()));
    exit();
}