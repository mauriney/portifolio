<?php

@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

  $db->beginTransaction();

try {

  $id = $_POST['id'];
  $nome = $_POST['nome'];

  if (isset($_POST['ts1'])) {//Ativo
    $status = 1;
  } else {//Inativo
    $status = 0;
  }

  $id_objeto = pesquisar_tabela("id", "seg_objeto", "nome", "=", $nome, "");

  if (is_numeric($id_objeto) && $id_objeto != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome do Objeto informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE seg_objeto SET nome = ?, status = ? WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $id);
      $stmt->execute();
      $msg['retorno'] = 'Objeto atualizado com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO seg_objeto (nome, data_cadastro, status, usuario_pai_id) VALUES (?, NOW(), ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();
      $msg['retorno'] = 'Objeto cadastrado com sucesso!';
    }

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['id'] = $id;
    $msg['msg'] = 'success';

    echo json_encode($msg);
    exit();
  } else {
    $msg['msg'] = 'error';
    $msg['retorno'] = $mensagem;
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar cadastrar o objeto desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>