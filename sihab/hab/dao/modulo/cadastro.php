<?php

//------------------------------------------------------------------------------
//DATA: 01/08/2016 às 12:25
//NOME: Cadastro de módulo
//DESCRIÇÃO: Realiza o cadastro de módulo no banco de dados
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
  $descricao = $_POST['descricao'];
  $responsavel = $_POST['responsavel_id'];
  $versao = $_POST['versao'];
  $url = $_POST['url'];

  if (isset($_POST['ts1'])) {//Ativo
    $status = 1;
  } else {//Inativo
    $status = 0;
  }

  $id_modulo = pesquisar_tabela("id", "seg_modulo", "nome", "=", $nome, "");

  if (is_numeric($id_modulo) && $id_modulo != @$_POST['id']) {
    $error = true;
    $mensagem .= "O nome do módulo informada já existe no sistema.";
    $msg['tipo'] = "nome";
  }

  if ($error == false) {
    if (isset($_POST['id']) && $_POST['id'] != 0) {
      $stmt = $db->prepare("UPDATE seg_modulo SET nome = ?, descricao = ?, versao = ?, url = ?, data_cadastro = NOW(), status = ?, responsavel_id = ?, usuario_pai_id = ? WHERE id = ?");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $descricao);
      $stmt->bindValue(3, $versao);
      $stmt->bindValue(4, $url);
      $stmt->bindValue(5, $status);
      $stmt->bindValue(6, $responsavel);
      $stmt->bindValue(7, $_SESSION['id']);
      $stmt->bindValue(8, $id);
      $stmt->execute();

      $array_marcados = Array();

      $cont = 0;
      $sql = $db->query("SELECT nome, id FROM seg_objeto ORDER BY nome ASC");
      while ($objeto = $sql->fetch(PDO::FETCH_ASSOC)) {
        $acoes = @$_POST[$objeto['id'] . '_acao'];
        if (isset($acoes)) {
          foreach ($acoes as $key => $value) {
            $array_marcados[$cont] = pesquisar_tabela("id", "seg_modulo_objeto_acao", "modulo_id", "=", $_POST['id'], "AND objeto_id = " . $objeto['id'] . " AND acao_id = " . $value);
            $cont++;
          }
        }
      }

      //VERIFICANDO OBJETOS_AÇÕES DESMARCADOS QUE ESTEJA VINCULADOS COM SEG_GRUPO_MODULO_OBJETO_ACAO E SEG_USUARIO_MODULO_OBJETO_ACAO
      $stmt = $db->prepare("SELECT id FROM seg_modulo_objeto_acao WHERE modulo_id = ?");
      $stmt->bindValue(1, $_POST['id']);
      $stmt->execute();
      $rsModuloObjetoAcaoOld = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
      //DELETA MODULO_OBJETO_ACAO REMOVIDOS NA EDIÇÃO QUE ESTÃO VINCULADOS COM GRUPO
      foreach ($_POST['modulo_objeto_acao_id'] as $kMOAOld => $vMOAOld) {
        if (!in_array($vMOAOld, $array_marcados) && $vMOAOld != 0) {

          //DELETANDO OS GRUPOS VINCULADOS
          $stmt3 = $db->prepare("DELETE FROM seg_grupo_modulo_objeto_acao WHERE modulo_objeto_acao_id = ?");
          $stmt3->bindValue(1, $vMOAOld);
          $stmt3->execute();

          //DELETANDO OS MODULOS VINCULADOS AO USUÁRIO
          $stmt3 = $db->prepare("DELETE FROM seg_usuario_modulo_objeto_acao WHERE modulo_objeto_acao_id = ?");
          $stmt3->bindValue(1, $vMOAOld);
          $stmt3->execute();

          //DELETANDO OS MODULOS VINCULADOS AO USUÁRIO
          $stmt3 = $db->prepare("DELETE FROM seg_modulo_objeto_acao WHERE id = ?");
          $stmt3->bindValue(1, $vMOAOld);
          $stmt3->execute();
          
        }
      }

      //SALVANDO OS OBJETOS_AÇÕES NOVOS
      $sql = $db->query("SELECT nome, id FROM seg_objeto ORDER BY nome ASC");
      while ($objeto = $sql->fetch(PDO::FETCH_ASSOC)) {
        $acoes = @$_POST[$objeto['id'] . '_acao'];
        if (isset($acoes)) {
          foreach ($acoes as $key => $value) {

            $stmt2 = $db->prepare("INSERT INTO seg_modulo_objeto_acao (modulo_id, objeto_id, acao_id, data_cadastro, usuario_pai_id) 
                                  SELECT ?, ?, ?, NOW(), ? FROM DUAL WHERE NOT EXISTS
                                  (SELECT modulo_id, objeto_id, acao_id FROM seg_modulo_objeto_acao WHERE modulo_id = ? AND objeto_id = ? AND acao_id = ?)");

            $stmt2->bindValue(1, $_POST['id']);
            $stmt2->bindValue(2, $objeto['id']);
            $stmt2->bindValue(3, $value);
            $stmt2->bindValue(4, $_SESSION['id']); //ID DO USUÁRIO LOGADO NO SISTEMA
            $stmt2->bindValue(5, $_POST['id']);
            $stmt2->bindValue(6, $objeto['id']);
            $stmt2->bindValue(7, $value);

            $stmt2->execute();
          }
        }
      }


      $msg['retorno'] = 'Módulo atualizado com sucesso!';
    } else {
      $stmt = $db->prepare("INSERT INTO seg_modulo (nome, descricao, versao, url, data_cadastro, status, responsavel_id, usuario_pai_id) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $descricao);
      $stmt->bindValue(3, $versao);
      $stmt->bindValue(4, $url);
      $stmt->bindValue(5, $status);
      $stmt->bindValue(6, $responsavel);
      $stmt->bindValue(7, $_SESSION['id']);
      $stmt->execute();

      $modulo_id = $db->lastInsertId();

      //SALVANDO O OBJETO_AÇÃO PECORRENDO PELO ARRAY
      $sql = $db->query("SELECT nome, id FROM seg_objeto ORDER BY nome ASC");
      while ($objeto = $sql->fetch(PDO::FETCH_ASSOC)) {
        $acoes = @$_POST[$objeto['id'] . '_acao'];
        if (isset($acoes))
          foreach ($acoes as $key => $value) {
            $stmt2 = $db->prepare("INSERT INTO seg_modulo_objeto_acao (modulo_id, objeto_id, acao_id, data_cadastro, usuario_pai_id) VALUES (?, ?, ?, now(), ?)");

            $stmt2->bindValue(1, $modulo_id);
            $stmt2->bindValue(2, $objeto['id']);
            $stmt2->bindValue(3, $value);
            $stmt2->bindValue(4, $_SESSION['id']); //ID DO USUÁRIO LOGADO NO SISTEMA

            $stmt2->execute();
          }
      }
      $msg['retorno'] = 'Módulo cadastrado com sucesso!';
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
  $msg['retorno'] = "Erro ao tentar cadastrar o módulo desejado:" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>