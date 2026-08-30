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

            <div class="urun-foto-div">
                <input type="file" id="urun-foto" name="urun_foto" accept="image/*">
                <label for="urun-foto" class="urun-foto-label">
                    <span>Ürününüzün Görselini Yükleyin</span>
                </label>
            </div>
            <img id="foto-onizleme" src="urun-img/default-product.png" alt="Ürün önizleme" class="ekle_img"
                width="200px" height="100px">

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
                    <button class="urun-duzenle" data-duzenlenecek-id="<?= $urun['id'] ?>"
                        data-foto="<?= $urun["urun_foto"] ?>" data-ad="<?= htmlspecialchars($urun["urun_ad"]) ?>"
                        data-aciklama="<?= htmlspecialchars($urun["urun_aciklama"]) ?>"
                        data-kategori="<?= $urun["kategori_ad"] ?>" data-stok="<?= $urun["urun_stok"] ?>"
                        data-fiyat="<?= $urun["urun_fiyat"] ?>">Ürünü Düzenle</button>
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

    const fotoInput = document.getElementById("urun-foto");
    const fotoOnizleme = document.getElementById("foto-onizleme");

    fotoInput.addEventListener("change", function () {

        const dosya = this.files[0];

        if (!dosya) return;

        fotoOnizleme.src = URL.createObjectURL(dosya);
        
        if (!dosya) {
            fotoOnizleme.src = "urun-img/default-product.png";
            return;
        }

    });

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
                    fotoOnizleme.src = "urun-img/default-product.png";
                } else {
                    toast(data.mesaj, data.durum);
                }

            });

    });


    const urunDuzenle = document.querySelector(".urunler");

    urunDuzenle.addEventListener("click", function (e) {

        const btn = e.target.closest(".urun-duzenle");

        if (!btn) return;

        const duzenlenecekId = btn.dataset.duzenlenecekId;
        const foto = btn.dataset.foto;
        const ad = btn.dataset.ad;
        const aciklama = btn.dataset.aciklama;
        const stok = btn.dataset.stok;
        const fiyat = btn.dataset.fiyat;

        const duzenleme = btn.closest(".card");


        duzenleme.innerHTML = `
        <form id="urunDuzenle-form" method="POST"  data-urun-id="${duzenlenecekId}" data-foto="${foto}" enctype="multipart/form-data">
    <div class="urun-foto">
        <img src="urun-img/${foto}">
    </div>

    <div class="urun-adi">
        <input type="text" value="${ad}" required class="guncel-ad" id="guncel_ad">
    </div>

    <div class="urun-aciklama">
        <textarea id="guncel_aciklama" required  class="guncel-aciklama">${aciklama}</textarea>
    </div>

    <div class="urun-kategori-stok">
        <span>Kategori :
        <select id="guncel_kategori_id" name="kategori_id" required class="guncel-kategori">
                <?php foreach ($kategoriler as $kategori): ?>
                    <option value="<?= $kategori['kategori_id'] ?>">
                        <?= $kategori['kategori_ad'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </span>
        <span> Kalan Stok :
        <input type="number" value="${stok}" required class="guncel-stok" id="guncel_stok">
        </span>
    </div>

    <div class="urun-fiyat-sil-duzenle">
    <div>
         <input type="number" step = "0.01" value="${fiyat}" required class="guncel-fiyat" id="guncel_fiyat">
         <span style="font-size: 20px; font-weight: 500;color: #273F4F;"> TL </span>
    </div>     
    <button  type="submit" class= "guncel-btn" id="guncel-btn" >Kaydet</button>
    </div>
                
    </form>
    `;
    });


    const guncel_urunler = document.querySelector(".urunler");

    guncel_urunler.addEventListener("submit", function (e) {

        const form = e.target.closest("#urunDuzenle-form");

        if (!form) return;

        e.preventDefault();

        const guncel_ad = form.querySelector("#guncel_ad").value;
        const guncel_aciklama = form.querySelector("#guncel_aciklama").value;
        const guncel_kategori_id = form.querySelector("#guncel_kategori_id").value;
        const guncel_stok = form.querySelector("#guncel_stok").value;
        const guncel_fiyat = form.querySelector("#guncel_fiyat").value;
        const urun_id = form.dataset.urunId;
        const foto = form.dataset.foto;

        console.log({
            guncel_ad,
            guncel_aciklama,
            guncel_kategori_id,
            guncel_stok,
            guncel_fiyat,
            urun_id
        });

        fetch("urun_duzenle.php", {
            method: "POST",

            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },

            body:
                `guncel_ad=${encodeURIComponent(guncel_ad)}` +
                `&guncel_aciklama=${encodeURIComponent(guncel_aciklama)}` +
                `&guncel_kategori_id=${encodeURIComponent(guncel_kategori_id)}` +
                `&guncel_stok=${encodeURIComponent(guncel_stok)}` +
                `&guncel_fiyat=${encodeURIComponent(guncel_fiyat)}` +
                `&urun_id=${encodeURIComponent(urun_id)}`
        })
            .then(response => response.json())
            .then(data => {

                toast(data.mesaj, data.durum);

                if (data.basari) {
                    const card = form.closest(".card");
                    card.innerHTML = `
                     <div class="urun-foto">
                    <img src="urun-img/${foto}">
                </div>

                <div class="urun-adi">
                    <span>${guncel_ad}</span>
                </div>

                <div class="urun-aciklama">
                ${guncel_aciklama}
                </div>

                <div class="urun-kategori-stok">
                    <span>Kategori : ${data.kategori}</span>
                    <span> Kalan Stok : ${guncel_stok}</span>
                </div>

                <div class="urun-fiyat-sil-duzenle">
                    <span class="fiyat">
                ${guncel_fiyat} TL
                    </span>
                    <button class="urun-sil" data-id="${urun_id}">Ürünü Sil</button>
                    <button class="urun-duzenle" data-duzenlenecek-id="${urun_id}"
                        data-foto="${foto}" data-ad="${guncel_ad}"
                        data-aciklama="${guncel_aciklama}"
                        data-kategori="${data.kategori}" data-stok="${guncel_stok}"
                        data-fiyat="${guncel_fiyat}">Ürünü Düzenle</button>
                </div>
                    `;
                }
                else {
                    toast(data.mesaj, data.durum)
                    return;
                }
            })

    });




</script>

<script src="js/toast.js"></script>