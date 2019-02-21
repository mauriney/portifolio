<?php

//------------------------------------------------------------------------------
//DATA: 20/02/2017 às 17:00
//NOME: Remover loteamento
//DESCRIÇÃO: Realiza a remoção de uma loteamento no banco de dados
// DESENVOLVEDOR: MAURINEY R. DA COSTA
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $id = $_POST['id'];

  $stmt = $db->prepare("DELETE FROM snch_loteamento WHERE id = ?");
  $stmt->bindValue(1, $id);
  $stmt->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Loteamento removida com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar remover a loteamento desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>