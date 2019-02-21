<?php

@session_start();
include_once ('conf/sistema.php');
include_once ('functions/geral.php');
include_once ('functions/Url.php');

if (isset($_SESSION['id'])) {
    $sessao = $_SESSION['id'];
} else {
    $sessao = 0;
}
$idsessao = session_id();
$db = Conexao::getInstance();

$atualizar = $db->prepare("UPDATE pessoas SET online = 0 WHERE id = ?");
$atualizar->bindValue(1, $sessao);
$atualizar->execute();

session_unset();
session_destroy();

echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "login.php';</script>";
?>
