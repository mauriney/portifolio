
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$id = strip_tags(@$_POST['id']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("DELETE FROM tb_bsc_preagenda WHERE IdPreAgenda = ?");

    $stmt->bindValue(1, $id);
    $stmt->execute();

    $preagenda_id = $db->lastInsertId();

    $db->commit();

    //MENSAGEM DE SUCESSO
    $msg['id'] = $preagenda_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Pré-Agenda removida com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar remover a pré-agenda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

