<?php
session_start();
require_once 'baglan.php';
include 'ustmenu.php';

if (!isset($_SESSION["giris"])) {
    header("Location:giris.php?hata=izinsiz-erisim");
    exit;
}



?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketV2</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/sepet.css">
    <link rel="stylesheet" href="css/toast.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="urunler">
        <div class="sepet">
            <?php if (!empty($_SESSION['sepet'])): ?>

                <div class="baslik">
                    <span class="sutun-ad">Ürünün Adı</span>
                    <span class="sutun-gorsel">Ürün Görsel</span>
                    <span class="sutun-adet">Adet</span>
                    <span class="sutun-fiyat">Ürünün Fiyatı</span>
                    <span class="sutun-toplam">Toplam</span>
                    <span class="sutun-islem">İşlem</span>
                </div>

                <?php
                $genel_toplam = 0;
                foreach ($_SESSION['sepet'] as $id => $urun):
                    $urun_adi = isset($urun['ad']) ? $urun['ad'] : 'Bilinmeyen Ürün';
                    $urun_fiyati = isset($urun['fiyat']) ? $urun['fiyat'] : 0;
                    $urun_adedi = isset($urun['adet']) ? $urun['adet'] : 1;

                    $ara_toplam = $urun_fiyati * $urun_adedi;
                    $genel_toplam += $ara_toplam;
                    ?>
                    <div class="sepetteki-urun">
                        <span class="sutun-ad"><?= $urun_adi ?></span>
                        <span class="sutun-gorsel">
                            <img src="urun-img/<?= $urun['foto'] ?>" class="sepet-foto" alt="Ürün Fotoğrafı">
                        </span>
                        <span class="sutun-adet"><?= $urun_adedi ?> Adet</span>
                        <span class="sutun-fiyat"><?= $urun_fiyati ?> TL</span>
                        <span class="sutun-toplam"><?= $ara_toplam ?> TL</span>
                        <div class="sutun-islem">
                            <div class="kaldir">
                                <a href="sepet-islem.php?islem=sil&id=<?= $id ?>" style="text-decoration: none;">
                                    <button class="noselect">
                                        <span class="text">Sil</span>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z">
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="sepet-ozet">
                    <h3 class="toplam-yazi">Genel Toplam: <span class="toplam-fiyat"><?= $genel_toplam ?> TL</span></h3>

                    <button class="tamamla-btn" id="alisveris-btn">Alışverişi Tamamla</button>

                </div>

            <?php else: ?>
                <div class="bos-sepet">
                    <p>Sepetinizde henüz bir ürün bulunmuyor.</p>
                    <a href="index.php" class="basla-btn">Alışverişe Başla</a>
                </div>
            <?php endif; ?>
        </div>


    </div>


    
    <div id="toast-container" class="toast-container"></div>
</body>

</html>

<script>
    const btn = document.getElementById("alisveris-btn");

    btn.addEventListener("click", function (e) {

        e.preventDefault();

        fetch("satin_al.php", {
            method: "POST",



        })


            .then(response => response.json())

            .then(data => {

                const sepet = btn.closest(".sepet");


                if (data.basari) {
                    toast(data.mesaj, data.durum);
                    const kartsil = btn.closest(".sepet");
                    const kartekle = document.createElement("div");

                    kartekle.classList.add("bos-sepet");
                    

                    setTimeout(() => {
                        sepet.innerHTML = `
                            <div class="bos-sepet">
                                <p>Sepetinizde henüz bir ürün bulunmuyor.</p>
                                <a href="index.php" class="basla-btn">Alışverişe Başla</a>
                            </div>
                        `;
                    }, 300);
                }
            });


    })
</script>

<script src="js/toast.js"></script>