<?php
session_start();
include_once 'baglan.php';

$durum = "";

if($_POST){
    $kullanici_adi = htmlspecialchars(trim($_POST["kullanici_adi"]));
    $parola = $_POST["kullanici_parola"];

    if(empty($kullanici_adi) || empty($parola)){
        $durum = "Lütfen Bütün Alanları Doldurunuz";
    } else{
        $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
        $sorgu->execute([$kullanici_adi]);
        $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);
        

        if($kullanici && password_verify($parola , $kullanici["kullanici_parola"])){
            $_SESSION["kullanici_id"] = $kullanici["id"];
            $_SESSION["kullanici_adi"] = $kullanici["kullanici_adi"];
            $_SESSION["giris"] = true;

            header("Location: index.php");
            exit();
        }else{
            $durum = "Kullanıcı Adı Veya Parola Hatalı!";
        }
    }


}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="css/giris.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <form method="POST" class="giris-form">
        <span class="durum"><?= $durum ?></span>
        <p class="baslik">Giriş Yap</p>

        <div class="alan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="input-ikon">
                <circle cx="12" cy="12" r="4" />
                <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8" />
            </svg>
            <input type="text" placeholder="Kullanıcı Adın" name="kullanici_adi" required>
        </div>

        <div class="alan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="input-ikon">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <input type="password" placeholder="Parolan" name="kullanici_parola" required>
        </div>
        <a href="#" class="par-res">Şifrenizimi Unuttunuz?</a>

        <button >Giriş Yap</button>

        <a href="kayit.php" class="kayit">Kayıt Ol</a>


    </form>


</body>

</html>