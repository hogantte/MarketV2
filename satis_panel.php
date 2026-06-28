<?php
require_once 'baglan.php';
session_start();
include 'ustmenu.php';

if (!isset($_SESSION["giris"])) {
    header("Location:giris.php?hata=izinsiz-erisim");
    exit();
}



$sorgu = $db->prepare("SELECT 
    siparisler.id AS siparis_id,
    siparisler.toplam_fiyat,
    siparisler.Durum,
    siparisler.tarih,
    siparis_urunleri.miktar,
    siparis_urunleri.fiyat AS urun_birim_fiyat,
    urunler.urun_ad,
    urunler.urun_foto
FROM siparisler 
JOIN siparis_urunleri ON siparisler.id = siparis_urunleri.siparis_id 
JOIN urunler ON siparis_urunleri.urun_id = urunler.id 
WHERE urunler.ekleyen_id = ? 
ORDER BY siparisler.id DESC");
$sorgu->execute([$_SESSION["kullanici_id"]]);

$satilmis = $sorgu->fetchALL(PDO::FETCH_ASSOC);

$gruplu = [];
foreach ($satilmis as $satir) {
    $id = $satir['siparis_id'];
    if (!isset($gruplu[$id])) {
        $gruplu[$id] = [
            'siparis_id' => $satir['siparis_id'],
            'tarih' => $satir['tarih'],
            'toplam_fiyat' => $satir['toplam_fiyat'],
            'Durum' => $satir['Durum'],
            'urunler' => []
        ];
    }
    $gruplu[$id]['urunler'][] = [
        'urun_ad' => $satir['urun_ad'],
        'miktar' => $satir['miktar'],
        'urun_birim_fiyat' => $satir['urun_birim_fiyat']
    ];
}

$durum_etiketleri = [
    'onay_bekliyor' => 'Onay Bekliyor',
    'hazirlaniyor' => 'Hazırlanıyor',
    'kargolandi' => 'Kargolandı',
    'teslim_edildi' => 'Teslim Edildi'
];

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketV2</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/satis_panel.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="dis-satis">
        <div class="satispanel">
            <div class="satis">
                <div class="baslik">
                    <div class="sutun">Sipariş No #</div>
                    <div class="sutun">Satış Tarihi</div>
                    <div class="sutun">Fiyat</div>
                    <div class="sutun">Durum</div>
                    <div class="sutun">Yönet</div>
                </div>
            </div>
            <?php foreach ($gruplu as $satilmislar): ?>
                <div class="satilanlar">
                    <div class="sutun">#<?= $satilmislar['siparis_id'] ?></div>
                    <div class="sutun"><?= date('d.m.Y H:i', strtotime($satilmislar['tarih'])) ?></div>
                    <div class="sutun"><?= $satilmislar['toplam_fiyat'] ?></div>
                    <div class="sutun"><?= $durum_etiketleri[$satilmislar['Durum']] ?></div>
                    <div class="sutun">
                        <?php if ($satilmislar['Durum'] == 'onay_bekliyor'): ?>
                            <a href="panel_onayla.php?id=<?= $satilmislar['siparis_id'] ?>" class="onayla-btn">Siparişi Onayla!</a>
                        <?php elseif($satilmislar['Durum'] == 'hazirlaniyor'): ?>
                            <a href="panel_kargola.php?id=<?= $satilmislar['siparis_id'] ?>" class="kargola-btn" >Siparişi Kargola!</a>
                        <?php else: ?>
                            
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach ?>

        </div>
    </div>

</body>

</html>