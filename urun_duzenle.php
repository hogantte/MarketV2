<?php
session_start();
require_once 'baglan.php';

if (!isset($_SESSION["giris"])) {
    header("Location: giris.php?hata=izinsiz-erisim");
    exit();
} elseif (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: urunler.php?durum=id-bulunamadi");
    exit();
}

$id = $_GET['id'];
$hata = "";

$sorgu = $db->prepare("
    SELECT 
        u.*,
        k.kategori_ad,
        kul.kullanici_adi
    FROM urunler u
    LEFT JOIN kategoriler k ON u.kategori_id = k.kategori_id
    LEFT JOIN kullanicilar kul ON u.ekleyen_id = kul.id
    WHERE u.id = ?
    LIMIT 1
");

$sorgu->execute([$id]);
$urun = $sorgu->fetch(PDO::FETCH_ASSOC);

$kategori_sor = $db->query("SELECT * FROM kategoriler");
$kategoriler = $kategori_sor->fetchAll(PDO::FETCH_ASSOC);

if (!$urun) {
    header("Location: urunler.php?durum=urun-bulunamadi");
}


if($_POST){
    $yeni_urun_ad = $_POST['yeni_urun_ad'];
    $yeni_urun_aciklama = $_POST['yeni_urun_aciklama'];
    $yeni_urun_fiyat = $_POST['yeni_urun_fiyat'];
    $yeni_urun_stok = $_POST['yeni_urun_stok'];
    $yeni_kategori = $_POST['kategori_id'];

    if(empty($yeni_urun_ad) || empty($yeni_urun_aciklama) || empty ($yeni_urun_fiyat) || empty($yeni_urun_stok) || empty($yeni_kategori)){
    $hata = "Lüten Bütün Alanları Doldurunuz";
    }else{
        $guncelle =  $db->prepare("UPDATE urunler Set
                                    urun_ad = ? , urun_aciklama = ? , urun_fiyat = ? , urun_stok = ? , kategori_id = ?");
        $guncelle->execute ([
            $yeni_urun_ad , $yeni_urun_aciklama , $yeni_urun_fiyat , $yeni_urun_stok ,$yeni_kategori
        ]);
        header("Location: urunler.php?durum=guncellendi");
        exit();
    }

}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketV2</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/urun_duzenle.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<span><?= $hata ?></span>

<form method="post" enctype="multipart/form-data">
    <div class="duzenle-card">
        <div class="inputlar">
            <label>Ürünün Adı:</label>
            <input type="text" name="yeni_urun_ad" value="<?= $urun['urun_ad'] ?>">
        </div>

        <div class="textarea">
            <label>Ürünün Açıklaması:</label>
            <textarea name="yeni_urun_aciklama" id="" required><?= $urun['urun_aciklama'] ?></textarea>
        </div>

        <div class="inputlar">
            <label>Ürünün Fiyatı:</label>
            <input name="yeni_urun_fiyat" type="number" step="0.01" value="<?= $urun['urun_fiyat'] ?>" required>
        </div>

        <div class="inputlar">
            <label>Ürünün Stoğu:</label>
            <input name = "yeni_urun_stok" type="number" value="<?= $urun['urun_stok'] ?>" required>
        </div>

        <div class="urun_kategori">
            <label for="">Ürün Kategorisi:</label>
            <select name="kategori_id">
                <option value="">Kategori Seçin</option>

                <?php foreach ($kategoriler as $kategori): ?>
                    <option value="<?= $kategori['kategori_id'] ?>" <?= ($urun['kategori_id'] == $kategori['kategori_id']) ? 'selected' : '' ?>>
                        <?= $kategori['kategori_ad'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="kaydet-btn"><span class="kaydet-span">Ürünü Kaydet</span><span class="icon"><svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus-icon lucide-plus">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
            </span>
        </button>
    </div>
</form>

</html>