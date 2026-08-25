<?php
session_start();
require_once 'baglan.php';

if(!isset($_SESSION['giris'])){
    echo json_encode([
        "basari" => false,
        "mesaj"  => "Bu işlem için giriş yapmanız gerekmektedir",
        "durum"  => "hata"
    ]);
    exit();
}

if($_POST){

    $guncel_ad = $_POST['guncel_ad'];
    $guncel_aciklama = $_POST['guncel_aciklama'];
    $guncel_kategori_id = $_POST['guncel_kategori_id'];
    $guncel_stok = $_POST['guncel_stok'];
    $guncel_fiyat = $_POST['guncel_fiyat'];
    $urun_id = $_POST['urun_id'];

    $guncelle = $db->prepare("UPDATE urunler Set urun_ad = ? , urun_aciklama = ? , urun_fiyat = ? , urun_stok = ? , kategori_id = ? WHERE id = ? AND ekleyen_id = ?");
    $guncelle->execute([$guncel_ad , $guncel_aciklama , $guncel_fiyat , $guncel_stok , $guncel_kategori_id , $urun_id , $_SESSION['kullanici_id']]);

    $kategori_ad = $db->prepare("SELECT kategori_ad FROM kategoriler WHERE kategori_id = ?");
    $kategori_ad->execute([$guncel_kategori_id]);
    $kategori = $kategori_ad->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "basari" => true,
        "mesaj" => "Ürün başarıyla güncellenmiştir",
        "kategori" => $kategori['kategori_ad']
    ]);
    exit();
}

?>