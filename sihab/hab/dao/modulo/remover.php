<?php

@session_start();

$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $id = $_POST['id'];

  $stmt = $db->prepare("DELETE FROM seg_modulo WHERE id = ?");
  $stmt->bindValue(1, $id);
  $stmt->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Módulo removido com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar remover o módulo desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>