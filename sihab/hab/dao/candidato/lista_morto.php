<?php

//------------------------------------------------------------------------------
//DATA: 12/08/2016 às 15:00
//NOME: Cadastro de candidato
//DESCRIÇÃO: Realiza o cadastro de candidatos no banco de dados
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$db->beginTransaction();

try {

  $candidato_id = $_POST['candidato_id'];
  $opcao = isset($_POST['opcao']) ? $_POST['opcao'] : 1;

  if ($error == false) {

    if ($opcao == 0) {
//------------------------------------------------ CADASTRAR APTO ----------------------------------------------------------------------
      //CADASTRO DE CANDIDATO APTO
      $stmt = $db->prepare("INSERT INTO sort_candidato_apto (candidato_id, seg_usuario_pai, data_cadastro, data_update, status)
                 VALUES (?, ?, NOW(), NOW(), 1)");
      $stmt->bindValue(1, $candidato_id);
      $stmt->bindValue(2, $_SESSION['id']);
      $stmt->execute();
    } else {
//------------------------------------------------ REMOVENDO CANDIDATO APTO ------------------------------------------------------------
      //DELETEANDO CANDIDATO APTO
      $stmt = $db->prepare("DELETE FROM sort_candidato_apto WHERE candidato_id = ?");
      $stmt->bindValue(1, $candidato_id);
      $stmt->execute();
    }
//--------------------------------------------------------------------------------------------------------------------------------------
    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['id'] = $candidato_id;
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
  $msg['retorno'] = "Erro ao tentar cadastrar ou atualizar a primeira etapa do formulário de candidato:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>