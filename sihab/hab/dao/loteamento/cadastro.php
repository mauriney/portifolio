<?php

//------------------------------------------------------------------------------
//DATA: 20/02/2017 às 17:07
//NOME: Cadastro de loteamento
//DESCRIÇÃO: Realiza o cadastro de loteamento no banco de dados
// DESENVOLVEDOR: MAURINEY R. DA COSTA
//------------------------------------------------------------------------------
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

  $id_loteamento = pesquisar_tabela("id", "snch_loteamento", "nome", "=", $nome, "");

  if (is_numeric($id_loteamento) && $id_loteamento != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome do loteamento informada já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE snch_loteamento SET nome = ?, status = ? WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $id);
      $stmt->execute();
      $msg['retorno'] = 'Loteamento atualizado com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO snch_loteamento (nome, data_cadastro, status, seg_usuario_pai_id) VALUES (?, NOW(), ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();
      $msg['retorno'] = 'Loteamento cadastrada com sucesso!';
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
  $msg['retorno'] = "Erro ao tentar cadastrar a loteamento desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>