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

    $item_id = $_POST['item_id'];
    $qtd = $_POST['qtd'];
    $op = $_POST['op'];

    if ($op == 1) {

        $sql3 = $db->prepare("UPDATE pedidos_itens SET quantidade = ? WHERE id = ?");
        $sql3->bindParam(1, $qtd);
        $sql3->bindParam(2, $item_id);
        $sql3->execute();
    } else if ($op == 2) {

        $sql5 = $db->prepare("DELETE FROM pedidos_itens_ingredientes WHERE pedidos_itens_id = ?");
        $sql5->bindParam(1, pesquisar_tabela("id", "pedidos_itens", "id", "=", $item_id, ""));
        $sql5->execute();

        $sql4 = $db->prepare("DELETE FROM pedidos_itens WHERE id = ?");
        $sql4->bindParam(1, $item_id);
        $sql4->execute();
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