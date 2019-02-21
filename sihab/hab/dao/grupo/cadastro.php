<?php

//------------------------------------------------------------------------------
//DATA: 01/08/2016 às 15:20
//NOME: Cadastro de grupo
//DESCRIÇÃO: Realiza o cadastro de grupo no banco de dados
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

  $id_acao = pesquisar_tabela("id", "seg_grupo", "nome", "=", $nome, "");

  if (is_numeric($id) && $id != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome do grupo informada já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE seg_grupo SET nome = ?, status = ? WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $id);
      $stmt->execute();
      $msg['retorno'] = 'Grupo atualizado com sucesso!';

      //DELETANDO OS OBJETOS_AÇÕES ANTERIORES
      $stmt3 = $db->prepare("Delete from seg_grupo_modulo_objeto_acao where grupo_id = ?");
      $stmt3->bindValue(1, $_POST['id']);
      $stmt3->execute();

      //SALVANDO O OBJETO_AÇÃO PECORRENDO PELO ARRAY
      $sql = $db->query("select modulo_id, objeto_id, acao_id from seg_modulo_objeto_acao");
      while ($modulo = $sql->fetch(PDO::FETCH_ASSOC)) {
        $acoes = @$_POST[$modulo['modulo_id'] . '_' . $modulo['objeto_id'] . '_acao'];
        if (isset($acoes))
          foreach ($acoes as $key => $value) {
            $stmt2 = $db->prepare("INSERT INTO seg_grupo_modulo_objeto_acao (modulo_objeto_acao_id, grupo_id, data_cadastro) VALUES (?, ?, now())");
            $stmt2->bindValue(1, $value);
            $stmt2->bindValue(2, $_POST['id']);
            $stmt2->execute();
          }
      }
    } else {
      $stmt = $db->prepare("INSERT INTO seg_grupo (nome, data_cadastro, status, usuario_pai_id) VALUES (?, NOW(), ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $_SESSION['id']);
      $stmt->execute();
      $msg['retorno'] = 'Grupo cadastrada com sucesso!';

      $grupo_id = $db->lastInsertId();

      //SALVANDO O OBJETO_AÇÃO PECORRENDO PELO ARRAY
      $sql = $db->query("select modulo_id, objeto_id, acao_id from seg_modulo_objeto_acao");
      while ($modulo = $sql->fetch(PDO::FETCH_ASSOC)) {
        $acoes = @$_POST[$modulo['modulo_id'] . "_" . $modulo['objeto_id'] . '_acao'];
        if (isset($acoes))
          foreach ($acoes as $key => $value) {
            $stmt2 = $db->prepare("INSERT INTO seg_grupo_modulo_objeto_acao (modulo_objeto_acao_id, grupo_id, data_cadastro) VALUES (?, ?, now())");
            $stmt2->bindValue(1, $value);
            $stmt2->bindValue(2, $grupo_id);
            $stmt2->execute();
          }
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
  $msg['retorno'] = "Erro ao tentar cadastrar o grupo desejada:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>