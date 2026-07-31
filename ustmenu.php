<div class="ustmenu">

    <a href="index.php">

        <div class="logo"><img src="assets/logo.svg" alt="H" width="32px" height="32px"></div>

        <span>Market</span>

        <span style="color: aliceblue; font-size: 23px;">V2</span>

    </a>

    <ul class="ust-linkler">

        <li><a href="urunler.php">Ürünler</a></li>

        <li>


            <?php
            $toplam_adet = 0;
            if (isset($_SESSION['sepet'])) {
                foreach ($_SESSION['sepet'] as $urun) {
                    $toplam_adet = $toplam_adet += $urun['adet'];
                }

            }
            ?>
            <span class="seppet-adet" id="sepet-adet">
            <?php if($toplam_adet > 0) : ?>
                <?= $toplam_adet ?>

            <?php endif ?>
            </span>

            <a href="sepet.php">Sepet</a>

        </li>

        <li><a href="hesabim.php">Hesabım</a></li>

    </ul>

</div>