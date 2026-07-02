<?php 
require_once 'baglan.php';
session_start();

if(!isset($_SESSION['giris'])){
    exit();
}

$id = $_POST['id'];
$durum = $_POST['durum'];

$sql = $db->prepare("UPDATE siparisler SET Durum = ?  WHERE id = ?");
$sql->execute([$durum , $id]);

echo "ok";
?>