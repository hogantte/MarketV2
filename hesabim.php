<?php
include_once  'baglan.php';
session_start();

if (!isset($_SESSION["giris"])) {
    header("Location: index.php");
    exit;
}else{
    

    $sorgu = $db->prepare("SELECT kullanici_mail , kayit_tarih FROM kullanicilar WHERE id = ?");
    $sorgu->execute([$_SESSION["kullanici_id"]]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);


}

?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketV2</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/hesabim.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="ustmenu">
        <a href="index.php">
            <div class="logo"><img src="assets/logo.svg" alt="H" width="32px" height="32px"></div>
            <span>Market</span>
            <span style="color: aliceblue; font-size: 23px;">V2</span>
        </a>
        <ul class="ust-linkler">
            <li><a href="urunler.php">Ürünler</a></li>
            <li><a href="sepet.php">Sepet</a></li>
            <li><a href="hesabim.php">Hesabım</a></li>
        </ul>
    </div>


    <div class="hesabim">
        <div class="hesabim-card">
            <div class="satir"><span>Kullanıcı Adın :</span> <span><?= $_SESSION["kullanici_adi"] ?></span> </div>
            <div class="satir"><span>Mailin :</span> <span><?= $kullanici["kullanici_mail"] ?></span> </div>
            <div class="satir"><span>Kayıt Tarihi :</span> <span><?= $kullanici["kayit_tarih"] ?></span></div>
            <br><br>

            
        </div>
    </div>
</body>

</html>