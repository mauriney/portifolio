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

    if ($op == 1 ) {
        
        $sql3 = $db->prepare("UPDATE mesas SET status = '1' WHERE id = ?");
        $sql3->bindParam(1, $id);
        $sql3->execute();
        
    } else if ($op == 2) {
        
        $sql4 = $db->prepare("UPDATE mesas SET status = '0' WHERE id = ?");
        $sql4->bindParam(1, $id);
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