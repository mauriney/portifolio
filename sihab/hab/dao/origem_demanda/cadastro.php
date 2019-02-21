<?php

//------------------------------------------------------------------------------
//DATA: 14/10/2016 às 11:20
//NOME: Cadastro de programa
//DESCRIÇÃO: Realiza o cadastro do programa no banco de dados
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

  $id_origem_demanda = pesquisar_tabela("id", "hab_origem_demanda", "nome", "=", $nome, "");

  if (is_numeric($id_origem_demanda) && $id_origem_demanda != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome da origem da demanda informada já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE hab_origem_demanda SET nome = ?, status = ?, data_update = NOW() WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $id);
      $stmt->execute();
      $msg['retorno'] = 'Origem da demanda atualizado com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO hab_origem_demanda (nome, data_cadastro, status, seg_usuario_pai_id) VALUES (?, NOW(), ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();
      $msg['retorno'] = 'Origem da demanda cadastrado com sucesso!';
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
  $msg['retorno'] = "Erro ao tentar cadastrar a origem da demanda desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>