<?php

@session_start();
include_once('../../functions/geral.php');
include_once('../../conf/sistema.php');

$db = Conexao::getInstance();

$error = false;
$msg = "";
$mensagem = "";

$db->beginTransaction();

try {

  $id = $_POST['id'];
  $funcionario = $_POST['funcionario'] == "" || $_POST['funcionario'] == NULL ? NULL : $_POST['funcionario'];

  if ($funcionario != NULL) {
    $sql3 = $db->prepare("UPDATE pedidos SET funcionario_id = ?, status = '3', confirmador = ? WHERE id = ?");
    $sql3->bindParam(1, $funcionario);
    $sql3->bindParam(2, $_SESSION['id']);
    $sql3->bindParam(3, $id);
    $sql3->execute();
  } else {
    $sql3 = $db->prepare("UPDATE pedidos SET funcionario_id = NULL, status = '4', confirmador = ? WHERE id = ?");
    $sql3->bindParam(1, $_SESSION['id']);
    $sql3->bindParam(2, $id);
    $sql3->execute();
  }

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Solicitação realizada com sucesso.';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar realizar as solicitações desejadas. :" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>