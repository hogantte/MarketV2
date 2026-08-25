<?php
session_start();
session_unset();
session_destroy();

    ?>

<script>
    sessionStorage.setItem("cikisMesaji", "Başarıyla çıkış yaptınız.");
    window.location.href = "index.php";
</script>