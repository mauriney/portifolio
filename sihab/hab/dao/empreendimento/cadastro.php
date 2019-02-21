<?php

//------------------------------------------------------------------------------
//DATA: 05/10/2016 às 16:00
//NOME: Cadastro de empreendimento
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
  $nome = $_POST['nome'];
  $numero_apf = $_POST['numero_apf'] == NULL ? NULL : $_POST['numero_apf'];


  if (isset($_POST['ts1'])) {//Ativo
    $status = 1;
  } else {//Inativo
    $status = 0;
  }

  // Verifica se o nome do apf já está cadastrado
  $id_empreendimento_nome = pesquisar_tabela("id", "snch_apf", "nome", "=", $nome, "");

  if (is_numeric($id_empreendimento_nome) && $id_empreendimento_nome != $_POST['id']) {
    $error = true;
    $mensagem .= "O nome do empreendimento informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }


  // Verifica se o numero do apf já está cadastrado
  // $id_empreendimento_apf = pesquisar_tabela("id", "snch_apf", "numero_apf", "=", $numero_apf, "");

  // if (is_numeric($id_empreendimento_apf) && $id_empreendimento_apf != $_POST['id']) {
  //   $error = true;
  //   $mensagem .= $_POST['id']."O numero do empreendimento informado já existe no sistema.".$id_empreendimento_apf;
  //   $msg['tipo'] = "numero_apf";
  // }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE snch_apf SET nome = ?, status = ?, numero_apf = ?, data_update = NOW() WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $numero_apf);
      $stmt->bindValue(4, $id);
      $stmt->execute();
      $msg['retorno'] = 'Empreendimento atualizado com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO snch_apf (nome, data_cadastro, status, numero_apf, seg_usuario_pai_id) VALUES (?, NOW(), ?, ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $numero_apf);
      $stmt->bindValue(4, $_SESSION['id']);
      $stmt->execute();
      $msg['retorno'] = 'Empreendimento cadastrado com sucesso!';
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
  $msg['retorno'] = "Erro ao tentar cadastrar o empreendimento desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>