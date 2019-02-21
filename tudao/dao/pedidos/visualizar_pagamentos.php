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
    $cliente_id = pesquisar_tabela("cliente_id", "pedidos", "id", "=", $id, "");
    $pontos_atuais = pesquisar_tabela("pontos", "clientes", "id", "=", $cliente_id, "");
    $vlr_total_pontos = pesquisar_tabela("vlr_total_pontos", "pedidos", "id", "=", $id, "");
    $forma_pagamento = pesquisar_tabela("forma_pagamento", "pedidos", "id", "=", $id, "");

    if ($forma_pagamento == 3 && $pontos_atuais < $vlr_total_pontos) {
        $mensagem = "A quantidade de pontos disponíveis do cliente é inferior a quantidade total de pontos solicitada para finalizar essa compra.";
        $error = true;
    }

    if ($error == false) {

        $sql3 = $db->prepare("UPDATE pedidos SET status = '4', finalizador = ? WHERE id = ?");
        $sql3->bindParam(1, $_SESSION['id']);
        $sql3->bindParam(2, $id);
        $sql3->execute();

        if ($forma_pagamento == 3) {
            $stmt = $db->prepare("UPDATE clientes 
                                  SET pontos = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, ($pontos_atuais - $vlr_total_pontos) + carregar_pontos_ganhos_cliente($id));
            $stmt->bindValue(2, $cliente_id);
            $stmt->execute();
        } else {
            $stmt = $db->prepare("UPDATE clientes SET pontos = ? WHERE id = ?");
            $stmt->bindValue(1, ($pontos_atuais + carregar_pontos_ganhos_cliente($id)));
            $stmt->bindValue(2, $cliente_id);
            $stmt->execute();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $id;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Solicitação realizada com sucesso.';
        echo json_encode($msg);
        exit();
    } else {
        $msg['msg'] = 'error';
        $msg['retorno'] = $mensagem;
        echo json_encode($msg);
    }
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar realizar as solicitações desejadas. :" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>