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

  $casas = isset($_POST['casas']) ? $_POST['casas'] : '';

  $stmt = $db->prepare("UPDATE snch_loteamento SET apto = 0 WHERE 1");
  $stmt->execute();

  $stmt = $db->prepare("UPDATE sort_casa SET status = 0 WHERE 1 AND status <> 2");
  $stmt->execute();

  if ($casas != "") {
    foreach ($casas as $kCasa => $vCasa) {

      $stmt = $db->prepare("UPDATE sort_casa SET status = 1 WHERE id = ?");
      $stmt->bindValue(1, $vCasa);
      $stmt->execute();

      $stmt2 = $db->prepare("UPDATE snch_loteamento SET apto = 1 WHERE id = ?");
      $stmt2->bindValue(1, pesquisar_tabela("loteamento_id", "sort_casa", "id", "=", $vCasa, ""));
      $stmt2->execute();
    }
  }

  $msg['retorno'] = 'Loteamentos e casas atualizados com sucesso!';

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = 1;
  $msg['msg'] = 'success';

  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar atualizar os loteamentos e casas:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>