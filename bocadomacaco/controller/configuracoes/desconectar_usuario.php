
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$id = strip_tags(@$_POST['id']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("DELETE FROM info_login WHERE idusuario = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $usuario_id = $db->lastInsertId();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $usuario_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Usuário desconectador com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar desconectar o usuário:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

