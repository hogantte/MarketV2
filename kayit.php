<?php
require_once 'baglan.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="css/kayit.css">
    <link rel="stylesheet" href="css/toast.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <form method="POST" class="giris-form" id="kayit-form">
        <p class="baslik">Kayıt Ol</p>

        <div class="alan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-mail-icon lucide-mail">
                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                <rect x="2" y="4" width="20" height="16" rx="2" />
            </svg>
            <input type="text" placeholder="Mailiniz" id="kullanici_mail" required>
        </div>

        <div class="alan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="input-ikon">
                <circle cx="12" cy="12" r="4" />
                <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8" />
            </svg>
            <input type="text" placeholder="Kullanıcı Adın" id="kullanici_adi" required>
        </div>

        <div class="alan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="input-ikon">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <input type="password" placeholder="Parolan" id="parola" required>
        </div>

        <div class="alan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="input-ikon">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <input type="password" placeholder="Parolan Tekrar" id="parola_tekrar" required>
        </div>


        <a href="#" class="par-res">Şifrenizimi Unuttunuz?</a>

        <button id="kayit-btn">Kayıt Ol</button>

        <a href="giris.php" class="kayit">Giriş Yap</a>


    </form>

    <div id="toast-container" class="toast-container"></div>
</body>

</html>

<script>
    const form = document.getElementById("kayit-form");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const kullanici_mail = document.getElementById("kullanici_mail").value;
        const kullanici_adi = document.getElementById("kullanici_adi").value;
        const parola = document.getElementById("parola").value;
        const parola_tekrar = document.getElementById("parola_tekrar").value;



        fetch("kayit_onay.php", {
            method: "POST",

            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },

            body: `kullanici_mail=${encodeURIComponent(kullanici_mail)}&kullanici_adi=${encodeURIComponent(kullanici_adi)}&parola=${encodeURIComponent(parola)}&parola_tekrar=${encodeURIComponent(parola_tekrar)}`
        })

            .then(response => response.json())

            .then(data => {

                if (data.basari) {
                    toast(data.mesaj , data.durum);

                    setTimeout(() => {
                        window.location = `giris.php?kullanici_adi=${encodeURIComponent(data.kullanici_adi)}&kayit=basarili`;
                    }, 1000);
                } else{
                    toast(data.mesaj , data.durum);
                }
            });
    });
</script>

<script src="js/toast.js"></script>