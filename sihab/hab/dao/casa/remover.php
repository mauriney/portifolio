<?php

//------------------------------------------------------------------------------
//DATA: 14/06/2017 às 17:00
//NOME: Remover casa
//DESCRIÇÃO: Realiza a remoção de uma casa no banco de dados
//DESENVOLVEDOR: NIRO LIMA
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $id = $_POST['id'];

  $stmt = $db->prepare("DELETE FROM sort_casa WHERE id = ?");
  $stmt->bindValue(1, $id);
  $stmt->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Casa removida com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar remover a casa desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>