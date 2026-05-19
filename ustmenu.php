<div class="ustmenu">

    <a href="index.php">

        <div class="logo"><img src="assets/logo.svg" alt="H" width="32px" height="32px"></div>

        <span>Market</span>

        <span style="color: aliceblue; font-size: 23px;">V2</span>

    </a>

    <ul class="ust-linkler">

        <li><a href="urunler.php">Ürünler</a></li>

        <li>

            <?php if (isset($_SESSION['sepet']) && count($_SESSION['sepet']) > 0): ?>
                <span class="seppet-adet">
                    <?php
                    $toplam_adet = 0;
                    foreach ($_SESSION['sepet'] as $urun) {
                        $toplam_adet += $urun['adet'];
                    }
                    echo $toplam_adet;
                    ?>
                </span>
            <?php endif; ?>

            <a href="sepet.php">Sepet</a>

        </li>

        <li><a href="hesabim.php">Hesabım</a></li>

    </ul>

</div>