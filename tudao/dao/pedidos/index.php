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

    $id = $_POST['id'];
    $op = $_POST['op'];
    $cliente_id = pesquisar_tabela("cliente_id", "pedidos", "id", "=", $id, "");
    $forma_pagamento = pesquisar_tabela("forma_pagamento", "pedidos", "id", "=", $id, "");
    $pontos_atuais = pesquisar_tabela("pontos", "clientes", "id", "=", $cliente_id, "");
    $pedido_status = pesquisar_tabela("status", "pedidos", "id", "=", $id, "");
    $vlr_total_pontos = pesquisar_tabela("vlr_total_pontos", "pedidos", "id", "=", $id, "");

    if ($op == 1) {

        $sql3 = $db->prepare("UPDATE pedidos SET funcionario_id = NULL, confirmador = NULL, finalizador = NULL, status = '1' WHERE id = ?");
        $sql3->bindParam(1, $id);
        $sql3->execute();
    } else if ($op == 2) {

        $sql4 = $db->prepare("UPDATE pedidos SET status = '5' WHERE id = ?");
        $sql4->bindParam(1, $id);
        $sql4->execute();

        if ($pedido_status == 3) {
            $stmt = $db->prepare("UPDATE clientes SET pontos = ? WHERE id = ?");
            $stmt->bindValue(1, ($pontos_atuais + $vlr_total_pontos) - carregar_pontos_ganhos_cliente($id));
            $stmt->bindValue(2, $cliente_id);
            $stmt->execute();
        } else {
            $stmt = $db->prepare("UPDATE clientes SET pontos = ? WHERE id = ?");
            $stmt->bindValue(1, ($pontos_atuais - carregar_pontos_ganhos_cliente($id)));
            $stmt->bindValue(2, $cliente_id);
            $stmt->execute();
        }
    }

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['id'] = $id;
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