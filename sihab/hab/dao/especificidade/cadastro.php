<?php

//------------------------------------------------------------------------------
//DATA: 29/07/2016 às 15:00
//NOME: Cadastro de especificidade
//DESCRIÇÃO: Realiza o cadastro de ação no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$db->beginTransaction();

try {

  $id = $_POST['id'];
  $programa = $_POST['programa'];
  $nome = $_POST['nome'];

  if (isset($_POST['ts1'])) {//Ativo
    $status = 1;
  } else {//Inativo
    $status = 0;
  }

  $id_subprograma = pesquisar_tabela("id", "hab_especificidade", "nome", "=", $nome, "");

  if (is_numeric($id_subprograma) && $id_subprograma != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome do subprograma informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE hab_especificidade SET hab_programa_id = ?, nome = ?, status = ?, data_update = NOW() WHERE id = ?");
      $stmt->bindValue(1, $programa);
      $stmt->bindValue(2, $nome);
      $stmt->bindValue(3, $status);
      $stmt->bindValue(4, $id);
      $stmt->execute();
      $msg['retorno'] = 'Especificidade atualizado com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO hab_especificidade (hab_programa_id, nome, data_cadastro, status, seg_usuario_pai_id) VALUES (?, ?, NOW(), ?, ?)");
      $stmt->bindValue(1, $programa);
      $stmt->bindValue(2, $nome);
      $stmt->bindValue(3, $status);
      $stmt->bindValue(4, $_SESSION['id']);
      $stmt->execute();
      $msg['retorno'] = 'Especificidade cadastrado com sucesso!';
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
  $msg['retorno'] = "Erro ao tentar cadastrar o Especificidade desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>