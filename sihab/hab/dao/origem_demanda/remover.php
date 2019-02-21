<?php

//------------------------------------------------------------------------------
//DATA: 14/10/2016 às 15:50
//NOME: Remover programa
//DESCRIÇÃO: Realiza a remoção de uma programa no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $id = $_POST['id'];

  $stmt = $db->prepare("DELETE FROM hab_origem_demanda WHERE id = ?");
  $stmt->bindValue(1, $id);
  $stmt->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Origem da demanda removida com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar remover a origem da demanda desejada: O programa pode está vinculado a uma demanda especifica";// . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>