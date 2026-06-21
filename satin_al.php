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

    foreach ($_SESSION['sepet'] as $urun_id => $miktar) {

        $miktar = (int)$miktar;

        $stmt = $db->prepare("SELECT id, urun_fiyat, urun_stok FROM urunler WHERE id = ? FOR UPDATE");
        $stmt->execute([$urun_id]);
        $urun = $stmt->fetch();

        if (!$urun) {
            throw new Exception("Ürün bulunamadı (id: $urun_id)");
        }

        if ($urun['urun_stok'] < $miktar) {
            throw new Exception("\"" . $urun_id . "\" için yeterli stok yok. Mevcut stok: " . $urun['urun_stok']);
        }

        $urunDetaylari[$urun_id] = [
            'miktar' => $miktar,
            'fiyat'  => $urun['urun_fiyat'],
        ];

        $toplam += $urun['urun_fiyat'] * $miktar;
    }

    $siparisler = $db->prepare("INSERT INTO siparisler (kullanici_id, toplam_fiyat) VALUES (?, ?)");
    $siparisler->execute([$_SESSION["kullanici_id"], $toplam]);
    $siparis_id = $db->lastInsertId();

    $siparis_urun = $db->prepare("INSERT INTO siparis_urunleri (siparis_id, urun_id, miktar, fiyat) VALUES (?, ?, ?, ?)");
    $stokGuncelle = $db->prepare("UPDATE urunler SET urun_stok = urun_stok - ? WHERE id = ?");

    foreach ($urunDetaylari as $urun_id => $detay) {
        $siparis_urun->execute([$siparis_id, $urun_id, $detay['miktar'], $detay['fiyat']]);

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