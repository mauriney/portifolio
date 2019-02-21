<?php

@session_start();
include_once('../../functions/geral.php');
include_once('../../conf/sistema.php');

$db = Conexao::getInstance();

$error = false;
$msg = "";
$mensagem = "";

$db->beginTransaction();

try {

  $op = $_POST['op'];

  if ($op == 1) {

    $pedidos_itens_ingredientes_id = $_POST['pedidos_itens_ingredientes_id'];

    $sql5 = $db->prepare("DELETE FROM pedidos_itens_ingredientes WHERE id = ?");
    $sql5->bindParam(1, $pedidos_itens_ingredientes_id);
    $sql5->execute();
  } else if ($op == 2) {

    $qtd = $_POST['qtd'];
    $pedidos_item_id = $_POST['pedidos_item_id'];

    $sql5 = $db->prepare("UPDATE pedidos_itens SET quantidade = ? WHERE id = ?");
    $sql5->bindParam(1, $qtd);
    $sql5->bindParam(2, $pedidos_item_id);
    $sql5->execute();
  } else if ($op == 3) {

    $numero_sessao = session_id();
    $produto_id = $_POST['produto_id'];
    $pedido_id = pesquisar_tabela("id", "pedidos", "numero_sessao", "=", $numero_sessao, "AND status = 0");

    $menu_selecionado = $_POST['menu_selecionado'];
    $_SESSION['menu_selecionado'] = $menu_selecionado;

    if (!is_numeric($pedido_id)) {
      $rs = $db->prepare("INSERT INTO pedidos (numero_sessao, cadastro, status) VALUES (?, NOW(), 0)");
      $rs->bindValue(1, $numero_sessao);
      $rs->execute();

      $pedido_id = $db->lastInsertId();
    }

    $rs2 = $db->prepare("INSERT INTO pedidos_itens (pedido_id, produto_id, observacao, quantidade, cadastro, status)
                 VALUES (?, ?, ?, ?, NOW(),1)");
    $rs2->bindValue(1, $pedido_id);
    $rs2->bindValue(2, $produto_id);
    $rs2->bindValue(3, "");
    $rs2->bindValue(4, 1);
    $rs2->execute();
  } else if ($op == 4) {

    $pedidos_itens_id = $_POST['pedidos_itens_id'];
    $ingrediente_id = $_POST['ingrediente_id'];
    $menu_selecionado = $_POST['menu_selecionado'];

    $_SESSION['produto_selecionado'] = $pedidos_itens_id;
    $_SESSION['menu_selecionado'] = $menu_selecionado;

    $rs3 = $db->prepare("INSERT INTO pedidos_itens_ingredientes (pedidos_itens_id, ingrediente_id, cadastro, status)
                 VALUES (?, ?, NOW(),1)");
    $rs3->bindValue(1, $pedidos_itens_id);
    $rs3->bindValue(2, $ingrediente_id);
    $rs3->execute();
  } else if ($op == 5) {//CANCELANDO PEDIDO
    $pedido_id = $_POST['pedido_id'];

    $sql5 = $db->prepare("DELETE FROM pedidos_itens_ingredientes WHERE pedidos_itens_id IN (SELECT id FROM pedidos_itens WHERE pedido_id = ?)");
    $sql5->bindParam(1, $pedido_id);
    $sql5->execute();

    $sql4 = $db->prepare("DELETE FROM pedidos_itens WHERE pedido_id = ?");
    $sql4->bindParam(1, $pedido_id);
    $sql4->execute();

    $sql3 = $db->prepare("DELETE FROM pedidos WHERE id = ?");
    $sql3->bindParam(1, $pedido_id);
    $sql3->execute();
  } else if ($op == 6) {

    $pedido_id = $_POST['pedido_id'];

    $cliente_id = isset($_POST['cliente_id']) && is_numeric($_POST['cliente_id']) ? $_POST['cliente_id'] : NULL;
    $mesa = isset($_POST['mesa']) && is_numeric($_POST['mesa']) ? $_POST['mesa'] : NULL;

    $sql3 = $db->prepare("UPDATE pedidos SET cliente_id = ?, mesa = ?, confirmador = ?, status = 1, guinche = 0 WHERE id = ?");
    $sql3->bindParam(1, $cliente_id);
    $sql3->bindParam(2, $mesa);
    $sql3->bindParam(3, $_SESSION['id']);
    $sql3->bindParam(4, $pedido_id);
    $sql3->execute();

    unset($_SESSION['produto_selecionado']);
  } else if ($op == 7) {

    $pedido_id = $_POST['pedido_id'];

    $sql3 = $db->prepare("UPDATE pedidos SET status = 2, guinche = 2 WHERE id = ?");
    $sql3->bindParam(1, $pedido_id);
    $sql3->execute();

    unset($_SESSION['produto_selecionado']);
  } else if ($op == 8) {

    $pedido_id = $_POST['pedido_id'];
    $numero_mesa = $_POST['numero_mesa'];
    $valor_pagamento = $_POST['valor_pagamento'];
    $valor_troco = $_POST['valor_troco'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $cliente = $_POST['cliente'];
    $vlr_total_dinheiro = $_POST['vlr_total_dinheiro'];

    $bairro_id = $_POST['bairro_id'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $contato = $_POST['contato'];

    $stmt = $db->prepare("UPDATE pedidos 
                              SET bairro_id = ?, endereco = ?, numero = ?, complemento = ?, contato = ?, mesa = ?, valor_pagamento = ?, valor_troco = ?, status = 1, forma_pagamento = ?, cliente_id = ?, vlr_total_dinheiro = ?, vlr_total_pontos = ?, finalizador = ?, guinche = 0   
                              WHERE id = ?");
    $stmt->bindValue(1, $numero_mesa == "" ? $bairro_id : NULL);
    $stmt->bindValue(2, $numero_mesa == "" ? $endereco : NULL);
    $stmt->bindValue(3, $numero_mesa == "" ? $numero : NULL);
    $stmt->bindValue(4, $numero_mesa == "" ? $complemento : NULL);
    $stmt->bindValue(5, $numero_mesa == "" ? $contato : NULL);
    $stmt->bindValue(6, $numero_mesa == "" ? NULL : $numero_mesa);
    $stmt->bindValue(7, valorfloat($valor_pagamento));
    $stmt->bindValue(8, valorfloat($valor_troco));
    $stmt->bindValue(9, $forma_pagamento);
    $stmt->bindValue(10, $cliente == "" ? NULL : $cliente);
    $stmt->bindValue(11, valorfloat($vlr_total_dinheiro));
    $stmt->bindValue(12, 0);
    $stmt->bindValue(13, $_SESSION['id']);
    $stmt->bindValue(14, $pedido_id);
    $stmt->execute();

    unset($_SESSION['produto_selecionado']);
  } else if ($op == 9) {

    $numero_sessao = session_id();
    $cliente_id = $_POST['cliente_id'];

    $sql6 = $db->prepare("UPDATE pedidos SET cliente_id = ? WHERE status = 0 AND numero_sessao = ?");
    $sql6->bindParam(1, $cliente_id);
    $sql6->bindParam(2, $numero_sessao);
    $sql6->execute();
  } else if ($op == 10) {

    $numero_sessao = session_id();
    $mesa_id = $_POST['mesa_id'];

    $sql6 = $db->prepare("UPDATE pedidos SET mesa = ? WHERE status = 0 AND numero_sessao = ?");
    $sql6->bindParam(1, $mesa_id);
    $sql6->bindParam(2, $numero_sessao);
    $sql6->execute();
  } else if ($op == 11) {

    $numero_sessao = session_id();

    $sql6 = $db->prepare("UPDATE pedidos SET cliente_id = NULL WHERE status = 0 AND numero_sessao = ?");
    $sql6->bindParam(1, $numero_sessao);
    $sql6->execute();
  } else if ($op == 12) {

    $numero_sessao = session_id();

    $sql6 = $db->prepare("UPDATE pedidos SET mesa = NULL WHERE status = 0 AND numero_sessao = ?");
    $sql6->bindParam(1, $numero_sessao);
    $sql6->execute();
  }

  $db->commit();

  //MENSAGEM DE SUCESSO
  $msg['id'] = $op;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Solicitação realizada com sucesso.';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar realizar as solicitações desejadas. :" . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>