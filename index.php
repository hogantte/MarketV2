<?php
require_once 'baglan.php';
include 'ustmenu.php';



$sorgu = $db->prepare("SELECT urunler.*, kategoriler.kategori_ad , kullanicilar.kullanici_adi
                      FROM urunler
                      LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.kategori_id
                      LEFT JOIN kullanicilar ON urunler.ekleyen_id = kullanicilar.id
                      ORDER BY urunler.id DESC");
$sorgu->execute();                      
$urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">


</head>

<body>

    <div class="urunler">
        <?php foreach ($urunler as $urun): ?>
        <div class="card">
            <div class="urun-foto">
                <img src="urun-img/<?= $urun["urun_foto"] ?>">
            </div>

            <div class="urun-adi">
                <span><?= $urun["urun_ad"] ?></span>
            </div>
            <div class="urun-aciklama">
                <?= $urun["urun_aciklama"] ?>
            </div>
            <div class="ekleyen-stokbildir">
                <span class="ekleyen">Ekleyen: <?= $urun["kullanici_adi"] ?></span>

                <?php if ($urun["urun_stok"] == 0): ?>
                <span class="stok-uyari">Bu ürün tükenmiştir!</span>
                <?php elseif ($urun["urun_stok"] <= 10 ): ?>
                <span class="stok-uyari">Dikkat Sadece <?= $urun["urun_stok"] ?> stok kaldı</span>
                <?php endif ?>


            </div>
            <div class="kategori">
                <span>Kategori :  <?= $urun["kategori_ad"] ?></span>
            </div>
            <div class="fiyat-sepet"><span><?= number_format($urun["urun_fiyat"], 2, ',', '.') ?>  TL</span> <a href="">Sepete Ekle</a> </div>
        </div>
        <?php endforeach ?>
    </div>
</body>

</html>