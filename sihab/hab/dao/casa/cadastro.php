<?php

//------------------------------------------------------------------------------
//DATA: 14/06/2017 às 17:07
//NOME: Cadastro de casa
//DESCRIÇÃO: Realiza o cadastro de casa no banco de dados
//DESENVOLVEDOR: NIRO LIMA
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
  $endereco = $_POST['endereco'];
  $numero = $_POST['numero'];
  $loteamento_id = $_POST['loteamento_id'];

  if (isset($_POST['ts1'])) {//Ativo
    $status = 1;
  } else {//Inativo
    $status = 0;
  }

  $id_casa = pesquisar_tabela("id", "sort_casa", "nome", "=", $nome, "");

  if (is_numeric($id_casa) && $id_casa != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome da casa informada já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE sort_casa SET nome = ?, endereco = ?, numero = ?, loteamento_id = ?, status = ?, seg_usuario_pai = ?, data_update = NOW() WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $endereco);
      $stmt->bindValue(3, $numero);
      $stmt->bindValue(4, $loteamento_id);
      $stmt->bindValue(5, $status);
      $stmt->bindValue(6, $_SESSION['id']);
      $stmt->bindValue(7, $id);
      $stmt->execute();
      $msg['retorno'] = 'Casa atualizada com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO sort_casa (nome, endereco, numero, loteamento_id, data_cadastro, data_update, status, seg_usuario_pai) VALUES (?, ?, ?, ?, NOW(), NOW(), ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $endereco);
      $stmt->bindValue(3, $numero);
      $stmt->bindValue(4, $loteamento_id);
      $stmt->bindValue(5, $status);
      $stmt->bindValue(6, $_SESSION['id']);
      $stmt->execute();
      $msg['retorno'] = 'Casa cadastrada com sucesso!';
    }

    if ($status == 1) {
      $stmt = $db->prepare("UPDATE snch_loteamento SET apto = 1 WHERE id = ?");
      $stmt->bindValue(1, $loteamento_id);
      $stmt->execute();
    } else {
      if (!is_numeric(pesquisar_tabela("id", "sort_casa", "loteamento_id", "=", $loteamento_id, "AND status = 1"))) {
        $stmt = $db->prepare("UPDATE snch_loteamento SET apto = 0 WHERE id = ?");
        $stmt->bindValue(1, $loteamento_id);
        $stmt->execute();
      }
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
  $msg['retorno'] = "Erro ao tentar cadastrar a casa desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>