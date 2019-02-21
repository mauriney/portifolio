<?php

@session_start();
include_once('../functions/geral.php');
include_once('../conf/sistema.php');

$db = Conexao::getInstance();

$msg = "";

$db->beginTransaction();

try {

  $codigo = $_POST['codigo'];
  $senha = $_POST['senha'];

  $id_usuario = pesquisar_tabela("id", "recupera_senha", "codigo", "=", $codigo, "");
  $email_usuario = pesquisar_tabela("email", "recupera_senha", "codigo", "=", $codigo, "");

  $stmt = $db->prepare("UPDATE pessoas SET senha = ? WHERE email = ?");
  $stmt->bindValue(1, sha1($senha));
  $stmt->bindValue(2, $email_usuario);
  $stmt->execute();

  $stmt = $db->prepare("UPDATE recupera_senha SET alterada = NOW(), data_update = NOW() WHERE email = ? AND alterada IS NULL");
  $stmt->bindValue(1, $email_usuario);
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