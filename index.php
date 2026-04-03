<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">

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
            <li><a href="#">Hesabım</a></li>
        </ul>
    </div>


    <div class="urunler">
        <div class="card">
            <div class="urun-foto">
                <img src="assets/kangal.jpg" alt="ürün-fotoğrafı">
            </div>

            <div class="urun-adi">
                <span>Aksaray Malaklısı</span>
            </div>
            <div class="urun-aciklama">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, molestias voluptatum ut nam eos eum
                odio sequi cupiditate dicta alias, blanditiis assumenda, modi reprehenderit error temporibus dolore
                magni eaque repudiandae!
            </div>
            <div class="urun-fiyat-satinal">
                <span class="fiyat">200Tl</span>
                <button class="sepete-ekle">
                    <input type="hidden" name="urun_id" value="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</body>

</html>