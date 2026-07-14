<?php
session_start();
require_once 'baglan.php';
include 'ustmenu.php';

if (!isset($_SESSION['giris'])) {
    header("Location:giris.php?hata=izinsiz-erisim");
    exit();
}

$sorgu = $db->prepare("
    SELECT
        siparisler.id,
        siparisler.tarih,
        siparisler.toplam_fiyat,
        siparisler.Durum,
        GROUP_CONCAT(urunler.urun_ad SEPARATOR ', ') AS urunler
    FROM siparisler
    JOIN siparis_urunleri
        ON siparisler.id = siparis_urunleri.siparis_id
    JOIN urunler
        ON siparis_urunleri.urun_id = urunler.id
    WHERE siparisler.kullanici_id = ?
    GROUP BY siparisler.id, siparisler.tarih, siparisler.toplam_fiyat
    ORDER BY siparisler.tarih DESC
");

$sorgu->execute([$_SESSION['kullanici_id']]);
$siparisler = $sorgu->fetchAll(PDO::FETCH_ASSOC);



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
    <link rel="stylesheet" href="css/sepet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="urunler">
        <div class="sepet">
            <div class="baslik">
                <span class="sutun-ad">Sipariş No</span>
                <span class="sutun-ad">Ürün Adları</span>
                <span class="sutun-ad">Tarih</span>
                <span class="sutun-ad">Fiyat</span>
                <span class="sutun-ad">Durum</span>
            </div>

            <?php if (!empty($siparisler)): ?>

                <?php foreach ($siparisler as $siparis): ?>

                    <div class="sepetteki-urun">

                        <span class="sutun-ad">
                            <?= htmlspecialchars($siparis['id']) ?>
                        </span>

                        <span class="sutun-ad">
                            <?= htmlspecialchars($siparis['urunler']) ?>
                        </span>

                        <span class="sutun-ad">
                            <?= date('d.m.Y H:i', strtotime($siparis['tarih'])) ?>
                        </span>

                        <span class="sutun-ad">
                            <?= number_format($siparis['toplam_fiyat'], 2, ',', '.') ?> TL
                        </span>
                        <?php if ($siparis['Durum'] === "onay_bekliyor" || $siparis['Durum'] === "hazirlaniyor" || $siparis["Durum"] === "teslim_edildi"): ?>
                            <span class="sutun-ad"><?= $durum_etiketleri[$siparis['Durum']] ?></span>
                        <?php elseif ($siparis['Durum'] === 'kargolandi'): ?>
                            <button class="ana-btn hazirla-btn" data-id="<?= $siparis['id'] ?>" data-durum="teslim_edildi"><span
                                    class="btn-span">Siparişin Sana Ulaştımı</span><span class="icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                        viewBox="0 0 16 16" style="color: green;">
                                        <path
                                            d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                    </svg>
                                </span>
                            </button>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="bos-sepet">
                    <p>Henüz siparişiniz bulunmuyor.</p>
                </div>

            <?php endif; ?>

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
                    this.style.opacity = "0";

                    setTimeout(() => {
                       this.outerHTML = `
                        <span class="sutun-ad">
                        Teslim Edildi
                        </span>
                        `;
                    }, 300);
                })
        })

    })
</script>