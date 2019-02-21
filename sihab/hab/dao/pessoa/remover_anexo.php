<?php

$db = Conexao::getInstance();

$pessoa_id = @$_POST['pessoa_id'];
$campo = @$_POST['campo'];

$error = false;

$mensagem = "";
$msg = array();

$db->beginTransaction();

try {

  $stmt = $db->prepare("UPDATE hab_pessoa_anexo SET $campo = NULL WHERE hab_pessoa_id = ?");
  $stmt->bindValue(1, $pessoa_id);
  $stmt->execute();
  $db->commit();

  //MENSAGEM DE REMOVIDA
  $msg['id'] = $anexoId;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Anexo removida com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar remover:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>


