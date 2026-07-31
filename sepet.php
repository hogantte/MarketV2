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
                            <button class="sil-btn" data-id="<?= $id ?>">Sil</button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="sepet-ozet">
                    <h3 class="toplam-yazi" >Genel Toplam: <span class="toplam-fiyat" id="genel-toplam"><?= $genel_toplam ?> TL</span></h3>

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
            })
    })


    const kaldir = document.querySelector(".urunler");

    kaldir.addEventListener("click", function (e) {
        const kaldirBtn = e.target.closest(".sil-btn");

        if (!kaldirBtn) return;

        const id = kaldirBtn.dataset.id;


        fetch("sepet-sil.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },

            body: `id=${encodeURIComponent(id)}`
        })


            .then(response => response.json())

            .then(data => {


                if (data.basari) {
                    toast(data.mesaj);
                    if(data.adet > 0){
                        document.getElementById("sepet-adet").textContent = data.adet;
                    }else if (data.adet <= 0){
                            document.getElementById("sepet-adet").textContent = "";
                    }
                    document.getElementById("genel-toplam").textContent = data.toplamFiyat + " TL";

                    const sepetSil = kaldirBtn.closest(".sepetteki-urun");

                    if (data.adet == 0) {
                        const sepet = kaldirBtn.closest(".sepet");


                        setTimeout(() => {
                            sepet.innerHTML = `
                            <div class="bos-sepet">
                                <p>Sepetinizde henüz bir ürün bulunmuyor.</p>
                                <a href="index.php" class="basla-btn">Alışverişe Başla</a>
                            </div>
                        `;
                        }, 300);

                    }


                    setTimeout(() => {
                        sepetSil.remove();
                    }, 300);
                } else {
                    toast(data.mesaj, data.durum);

                }
            })

    })
</script>

<script src="js/toast.js"></script>