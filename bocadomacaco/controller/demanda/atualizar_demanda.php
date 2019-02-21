
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$opcao = strip_tags(@$_POST['opcao']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("UPDATE x_demanda SET status = ? WHERE id = ?");

    $stmt->bindValue(1, $opcao);
    $stmt->bindValue(2, $_POST['id']);

    $stmt->execute();

    $demanda_id = $db->lastInsertId();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $demanda_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Status da demanda atualizada com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar atualizar o status da demanda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

