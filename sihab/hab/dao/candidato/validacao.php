<?php

//------------------------------------------------------------------------------
//DATA: 19/10/2016 às 17:00
//NOME: Validação do formulário de candidato
//DESCRIÇÃO: Realiza a validação dos dados do candidato a ser cadastrado
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

    $stmt = $db->prepare("UPDATE hab_candidato SET validacao = ?, situacao = 3 WHERE id = ?");
    $stmt->bindValue(1, $validacao);
    $stmt->bindValue(2, $candidato_id);
    $stmt->execute();

    $stmt = $db->prepare("INSERT INTO hab_candidato_situacao (hab_candidato_id, seg_usuario_pai_id, hab_tipo_situacao_id, data_cadastro, status, data_update) VALUES (?, ?, ?, NOW(), 1, NOW())");
    $stmt->bindValue(1, $candidato_id);
    $stmt->bindValue(2, $_SESSION['id']);
    $stmt->bindValue(3, 3);
    $stmt->execute();

    $msg['retorno'] = 'Formulário validado com sucesso!';

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
  $msg['retorno'] = "Erro ao tentar validar o formulário:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>