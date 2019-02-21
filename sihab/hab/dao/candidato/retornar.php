<?php

//------------------------------------------------------------------------------
//DATA: 20/10/2016 às 17:00
//NOME: Retornar para correções formulário de candidato
//DESCRIÇÃO: Retorna o formulário para correções de dados em anexo
//------------------------------------------------------------------------------
@session_start();

$db = Conexao::getInstance();

$error = false;
$msg = array();
$mensagem = "";

$candidato_id = $_POST['candidato_id'];
$validacao = $_POST['validacao'];

$db->beginTransaction();

try {

  if ($error == false) {

    $stmt = $db->prepare("UPDATE hab_candidato SET validacao = ?, situacao = 4 WHERE id = ?");
    $stmt->bindValue(1, $validacao);
    $stmt->bindValue(2, $candidato_id);
    $stmt->execute();

    $stmt = $db->prepare("INSERT INTO hab_candidato_situacao (hab_candidato_id, seg_usuario_pai_id, hab_tipo_situacao_id, data_cadastro, status, data_update) VALUES (?, ?, ?, NOW(), 1, NOW())");
    $stmt->bindValue(1, $candidato_id);
    $stmt->bindValue(2, $_SESSION['id']);
    $stmt->bindValue(3, 4);
    $stmt->execute();

    $msg['retorno'] = 'Formulário retornado para correções dos dados com sucesso!';

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
  $msg['retorno'] = "Erro ao tentar retornar o formulário:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>