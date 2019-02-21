<?php

@session_start();
include_once('conf/config.php');
include_once('utils/funcoes.php');
include_once('conf/Url.php');

if (isset($_SESSION['id'])) {
  $sessao = $_SESSION['id'];
} else {
  $sessao = 0;
}
$idsessao = session_id();
$db = Conexao::getInstance();

$sair = $db->prepare("UPDATE seg_sessao SET data_logout = NOW() WHERE usuario_id = ?");
$sair->bindValue(1, $sessao);
$sair->execute();

$atualizar = $db->prepare("UPDATE seg_usuario SET online = 0 WHERE id = ?");
$atualizar->bindValue(1, $sessao);
$atualizar->execute();

session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title><?= TITULOSISTEMA ?></title>
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
  </head>
  <body>
    <?php
    echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "login';</script>";
    ?>
  </body>
</html>