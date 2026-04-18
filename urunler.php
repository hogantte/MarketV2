<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/urunler.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>

    <p class="baslik">Ürünleriniz</p>

    <div class="urunler">


        <form action="" method="post" class="ekle_card">
            <p>Ürün Ekle</p>
            <input type="text" placeholder="Ürünün İsmi" required class="inputlar" name="urun_adi">
            <textarea name="urun_aciklama" id="" placeholder="Ürünün Açıklaması" rows="4"></textarea>
            <input type="number" step="0.01" placeholder="Ürünün Fiyatı" required class="inputlar" name="urun_fiyat">
            <input type="number" placeholder="Stok Adedi" required class="inputlar" name="urun_fiyat">

            <select name="kategori_id" required>
                <option value="">Kategori Seçin</option>
                <?php foreach ($kategoriler as $kategori): ?>
                    <option value="<?= $kategori['kategori_id'] ?>">
                        <?= $kategori['kategori_ad'] ?>
                    </option>
                <?php endforeach; ?>
            </select>


            <div class="urun-foto-div">
                <input type="file" name="urun_foto" id="urun-foto" accept="image/*" required name="urun_gorsel">
                <label for="urun-foto" class="urun-foto-label">
                    <span>Ürününüzün Görselini Yükleyin</span>
                </label>
            </div>

            <button class="ekle-btn"><span class="ekle-span">Ürünü Ekle</span><span class="icon"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </span>
            </button>




        </form>



        <div class="card">
            <div class="urun-foto">
                <img src="assets/kangal.jpg" alt="ürün-gorseli">
            </div>

            <div class="urun-adi">
                <span>Aksaray Malaklısı</span>
            </div>
            <div class="urun-aciklama">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, molestias voluptatum ut nam eos eum
                odio sequi cupiditate dicta alias, blanditiis assumenda, modi reprehenderit error temporibus dolore
                magni eaque repudiandae!
            </div>
            <div class="urun-fiyat-sil-duzenle">
                <span class="fiyat">200.00Tl</span>
                <a href="#">Ürünü Sil</a>
                <a href="#">Ürünü Düzenle</a>
            </div>
        </div>


    </div>

</body>

</html>