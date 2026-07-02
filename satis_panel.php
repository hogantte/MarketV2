<?php
require_once 'baglan.php';
session_start();
include 'ustmenu.php';

if (!isset($_SESSION["giris"])) {
    header("Location:giris.php?hata=izinsiz-erisim");
    exit();
}



$sorgu = $db->prepare("
    SELECT
    siparisler.id AS siparis_id,
    siparisler.tarih,
    siparisler.toplam_fiyat,
    siparisler.Durum,
    siparis_urunleri.miktar,
    siparis_urunleri.fiyat AS urun_birim_fiyat,
    urunler.urun_ad
    FROM siparisler
    JOIN siparis_urunleri ON siparisler.id = siparis_urunleri.siparis_id
    JOIN urunler ON siparis_urunleri.urun_id = urunler.id
    WHERE urunler.ekleyen_id = ?
    ORDER BY siparisler.id DESC
");
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
                            <button class="ana-btn hazirla-btn" data-id="<?= $satilmislar['siparis_id'] ?>"
                                data-durum="hazirlaniyor"><span class="btn-span">Siparişi Onayla!</span><span class="icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                        viewBox="0 0 16 16" style="color: green;">
                                        <path
                                            d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                    </svg>
                                </span>
                            </button>
                        <?php elseif ($satilmislar['Durum'] == 'hazirlaniyor'): ?>
                            <button class="ana-btn kargola-btn" data-id="<?= $satilmislar['siparis_id'] ?>"
                                data-durum="kargolandi"><span class="btn-span">Siparişi Kargola!</span><span class="icon"><svg
                                        version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"
                                        fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <style type="text/css">
                                                .st0 {
                                                    fill: white;
                                                }
                                            </style>
                                            <g>
                                                <path class="st0"
                                                    d="M0,30.118v15.059v120.471h22.588v316.235h466.824V165.647h7.529H512V30.118H0z M459.294,451.765H52.706 V158.118h406.588V451.765z M481.882,135.53h-7.53h-15.059H52.706H30.118V60.235h451.765V135.53z">
                                                </path>
                                                <rect x="195.765" y="214.588" class="st0" width="120.47" height="37.647"></rect>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </button>
                        <?php else: ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach ?>

        </div>
    </div>

</body>

</html>

<script>
    document.querySelectorAll(".ana-btn").forEach(function (btn) {

        btn.addEventListener("click", function () {

            const id = this.dataset.id;
            const durum = this.dataset.durum;

            fetch("siparis_guncelle.php", {

                method: "POST",

                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },

                body: `id=${id}&durum=${durum}`

            })

                .then(response => response.text())

                .then(data => {

                    console.log(data);

                    location.reload();

                });

        });

    });
</script>