<?php
session_start();
require_once 'baglan.php';
include 'ustmenu.php';

$kategori_sor = $db->query("SELECT * FROM kategoriler");
$kategoriler = $kategori_sor->fetchAll(PDO::FETCH_ASSOC);



if (!isset($_SESSION["giris"])) {
    header("Location: giris.php?hata=izinsiz-erisim");
    exit;
}


$sorgu = $db->prepare("SELECT urunler.*, kategoriler.kategori_ad
                      FROM urunler
                      LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.kategori_id
                      WHERE urunler.ekleyen_id = ?
                      ORDER BY urunler.id DESC");
$sorgu->execute([$_SESSION["kullanici_id"]]);
$urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/urunler.css">
    <link rel="stylesheet" href="css/toast.css">



    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>

    <p class="baslik">Ürünleriniz</p>

    <div class="urunler">


        <form id="urunEkle-form" method="POST" class="ekle_card" enctype="multipart/form-data">
            <p>Ürün Ekle</p>
            <input type="text" placeholder="Ürünün İsmi" required class="inputlar" id="urun_adi" name="urun_adi">
            <textarea id="urun_aciklama" name="urun_aciklama" placeholder="Ürünün Açıklaması" rows="4"></textarea>
            <input type="number" step="0.01" placeholder="Ürünün Fiyatı" required class="inputlar" id="urun_fiyat"
                name="urun_fiyat">
            <input type="number" placeholder="Stok Adedi" required class="inputlar" id="urun_stok" name="urun_stok">

            <select id="kategori_id" name="kategori_id" required>
                <option value="">Kategori Seçin</option>
                <?php foreach ($kategoriler as $kategori): ?>
                    <option value="<?= $kategori['kategori_id'] ?>">
                        <?= $kategori['kategori_ad'] ?>
                    </option>
                <?php endforeach; ?>
            </select>


            <div class="urun-foto-div">
                <input type="file" id="urun-foto" name="urun_foto" accept="image/*" required>
                <label for="urun-foto" class="urun-foto-label">
                    <span>Ürününüzün Görselini Yükleyin</span>
                </label>
            </div>

            <button class="ekle-btn" id="urunEkle-btn"><span class="ekle-span">Ürünü Ekle</span><span class="icon"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </span>
            </button>



        </form>


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

                <div class="urun-kategori-stok">
                    <span>Kategori : <?= $urun["kategori_ad"] ?></span>
                    <span> Kalan Stok : <?= $urun["urun_stok"] ?></span>
                </div>

                <div class="urun-fiyat-sil-duzenle">
                    <span class="fiyat">
                        <?= $urun["urun_fiyat"] ?> TL
                    </span>
                    <button class="urun-sil" data-id="<?= $urun['id'] ?>">Ürünü Sil</button>
                    <a href="urun_duzenle.php?id=<?= $urun["id"] ?>">Ürünü Düzenle</a>
                </div>
            </div>
        <?php endforeach ?>

    </div>

    <div id="toast-container" class="toast-container"></div>
</body>

</html>

<script>
    const urunler = document.querySelector(".urunler");

    urunler.addEventListener("click", function (e) {
        const btn = e.target.closest(".urun-sil");

        if (!btn) return;

        const id = btn.dataset.id;

        fetch("urunSil.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },

            body: `id=${id}`
        })

            .then(response => response.text())

            .then(data => {

                if (data != "ok") {
                    toast("Hata oluştu , tekrar deneyin!")
                    return;
                }
                btn.style.opacity = "0";

                const kart = btn.closest(".card");

                toast("Ürün Silindi");



                kart.classList.add("siliniyor");

                setTimeout(() => {
                    kart.remove();
                }, 300);
            })
    })


    const urunEkle_form = document.getElementById("urunEkle-form");

    urunEkle_form.addEventListener("submit", function (e) {

        e.preventDefault();

        const formData = new FormData(urunEkle_form);

        fetch("urun_ekle.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {

                if (data.basari) {
                    toast(data.mesaj, data.durum);
                    
                    setTimeout(() => {
                        const kart = document.createElement("div");
                        kart.classList.add("card");

                        kart.innerHTML = `
                            <div class="urun-foto">
                                <img src="urun-img/${data.urun.foto}">
                            </div>

                            <div class="urun-adi">
                                <span>${data.urun.adi}</span>
                            </div>

                            <div class="urun-aciklama">
                                ${data.urun.aciklama}
                            </div>

                            <div class="urun-kategori-stok">
                                <span>Kategori: ${data.urun.kategori}</span>
                                <span>Kalan Stok: ${data.urun.stok}</span>
                            </div>

                            <div class="urun-fiyat-sil-duzenle">
                                <span class="fiyat">
                                ${data.urun.fiyat} TL
                                </span>
                                <button class="urun-sil" data-id="${data.urun.id}">Ürünü Sil</button>
                                <a href="urun_duzenle.php?id=${data.urun.id}">Ürünü Düzenle</a>
                            </div>
                        `;
                        document.querySelector(".urunler").appendChild(kart);
                        urunEkle_form.insertAdjacentElement("afterend", kart);
                       
                    }, 1000);     
                     urunEkle_form.reset();              
                } else {
                    toast(data.mesaj, data.durum);
                }

            });

    });
</script>

<script src="js/toast.js"></script>