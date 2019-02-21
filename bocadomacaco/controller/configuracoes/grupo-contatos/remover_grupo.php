
<?php

@session_start();

require_once("../../../conf/config.php");
require_once('../../../utils/funcoes.php');

$db = Conexao::getInstance();

$id = strip_tags(@$_POST['id']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("DELETE FROM tb_bsc_grupo_email WHERE IdGrupoEmail = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $grupo_id = $db->lastInsertId();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $grupo_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Grupo removido com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar remover o grupo:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

