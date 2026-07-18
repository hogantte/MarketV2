<?php 
include_once 'baglan.php';

if ($_POST) {
    $kullanici_mail = $_POST["kullanici_mail"];
    $kullanici_adi = $_POST["kullanici_adi"];
    $parola = $_POST["parola"];
    $parola_tekrar = $_POST["parola_tekrar"];


    if(empty($kullanici_mail) || empty($kullanici_adi) || empty($parola)){
        echo json_encode([
            "basari" => false,
            "mesaj" => "Alanları Boş Bırakmayın",
            "durum" => "hata"
        ]);
    }else if($parola != $parola_tekrar){
        echo json_encode([
            "basari" => false,
            "mesaj" => "Parolalar Uyuşmuyor",
            "durum" => "hata"
        ]);
    }
    else{
        $kontrol = $db->prepare("SELECT COUNT(*) FROM kullanicilar WHERE kullanici_adi = ? OR kullanici_mail = ?");
        $kontrol->execute([$kullanici_adi, $kullanici_mail]);
        $sayi = $kontrol->fetchColumn();
        if ($sayi > 0) {
            echo json_encode([
                "basari" => false,
                "mesaj" => "Mail Veya Kullanıcı Adı Kullanımda",
                "durum" => "hata"
            ]);
        } else{
            $hasliParola = password_hash($parola , PASSWORD_DEFAULT);

            $kaydet = $db->prepare("INSERT INTO kullanicilar (kullanici_mail , kullanici_adi , kullanici_parola) VALUES (? , ? ,?) ");
            $sonuc = $kaydet->execute([$kullanici_mail , $kullanici_adi , $hasliParola]);

            echo json_encode([
                "basari" => true,
                "mesaj"  => "Kayıt Başarılı Aktarılıyorsunuz",
                "durum" => "basari",
                "kullanici_adi" => $kullanici_adi
            ]);
        }
    }
}
?>