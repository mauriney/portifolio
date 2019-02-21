
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$id = strip_tags(@$_POST['id']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("DELETE FROM tb_bsc_contato WHERE idcontato = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $contato_id = $db->lastInsertId();

    $stmt23 = $db->prepare("DELETE FROM tb_bsc_endereco WHERE idcontato = ?");
    $stmt23->bindValue(1, $id);
    $stmt23->execute();

    $stmt44 = $db->prepare("DELETE FROM tb_bsc_contato_grupo WHERE idcontato = ?");
    $stmt44->bindValue(1, $id);
    $stmt44->execute();

    $stmt77 = $db->prepare("DELETE FROM tb_bsc_telefone WHERE idcontato = ?");
    $stmt77->bindValue(1, $id);
    $stmt77->execute();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $contato_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Contato removido com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar remover o contato:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

