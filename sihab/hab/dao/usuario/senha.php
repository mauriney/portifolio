<?php

//------------------------------------------------------------------------------
//DATA: 28/10/2016 às 15:00
//NOME: Atualizar Senha
//DESCRIÇÃO: Realiza a atualização de senha do usuário
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$msg = array();

$db->beginTransaction();

try {

  $senha_nova = $_POST['senha_nova'];

  $stmt = $db->prepare("UPDATE seg_usuario SET senha = ? WHERE id = ?");
  $stmt->bindValue(1, sha1($senha_nova));
  $stmt->bindValue(2, $_SESSION['id']);
  $stmt->execute();

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $_SESSION['id'];
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Senha atualizada com sucesso!';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar remover atualizar a senha desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>