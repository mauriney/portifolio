<?php

@session_start();

include_once('conf/config.php');

$idsessao = session_id();
$db = Conexao::getInstance();
$atualizar = $db->prepare("DELETE FROM info_login WHERE idusuario = ? AND idsessao = ?");
$atualizar->bindValue(1, $_SESSION['id']);
$atualizar->bindValue(2, $idsessao);
$atualizar->execute();
session_unset();
session_destroy();

header("Location: " . PORTAL_URL . "index.php");
?>
 