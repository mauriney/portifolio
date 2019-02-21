<?php

@session_start();

include_once('../../utils/funcoes.php');
include_once('../../conf/config.php');
$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $id_usuario = $_SESSION['redefinir_id'];
  
  $senha = $_POST['senha'];

  $stmt = $db->prepare("UPDATE seg_usuario SET senha = ? WHERE id = ?");
  $stmt->bindValue(1, sha1($senha));
  $stmt->bindValue(2, $id_usuario);
  $stmt->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id_usuario;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Senha alterada com sucesso.';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar alterar a senha:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>