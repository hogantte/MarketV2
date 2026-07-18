<?php
session_start();
include_once 'baglan.php';


if ($_POST) {

    $kullanici_adi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    if (empty($kullanici_adi) || empty($sifre)) {
        echo json_encode([
            "basari" => false,
            "mesaj" => "Alanları boş bırakmayın"
        ]);
    } else {
        $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
        $sorgu->execute([$kullanici_adi]);
        $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

        if ($kullanici && password_verify($sifre, $kullanici["kullanici_parola"])) {
            $_SESSION["kullanici_id"] = $kullanici['id'];
            $_SESSION["kullanici_adi"] = $kullanici['kullanici_adi'];
            $_SESSION["giris"] = true;

            echo json_encode([
                "basari" => true,
                "mesaj" => "Giriş Başarılı Aktarılıyorsunuz"
            ]);
        } else {

            echo json_encode([
                "basari" => false,
                "mesaj" => "Bilgiler Hatalı"
            ]);
        }

    }

}
?>