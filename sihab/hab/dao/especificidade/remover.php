<?php

//------------------------------------------------------------------------------
//DATA: 17/10/2016 às 15:00
//NOME: Remover especificidade
//DESCRIÇÃO: Realiza a remoção de uma especificidade no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $id = $_POST['id'];

  $stmt = $db->prepare("DELETE FROM hab_especificidade WHERE id = ?");
  $stmt->bindValue(1, $id);
  $stmt->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Especificidade removido com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar remover a especificidade desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>