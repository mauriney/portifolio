<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 15:00
//NOME: Desistência de Candidato
//DESCRIÇÃO: Realiza a desistência de um candidato
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $id = $_POST['id'];

  $casa_id = pesquisar_tabela("casa_id", "sort_candidato_casa", "id", "=", $id, "");

  $stmt = $db->prepare("UPDATE sort_candidato_casa SET status = 2 WHERE id = ?");
  $stmt->bindValue(1, $id);
  $stmt->execute();

  $stmt2 = $db->prepare("UPDATE sort_casa SET status = 0 WHERE id = ?");
  $stmt2->bindValue(1, $casa_id);
  $stmt2->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Ação realizada com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar realizar a ação desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>