
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$codigo = strip_tags(@$_POST['codigo']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("DELETE FROM x_demanda_obs WHERE id = ?");

    $stmt->bindValue(1, $codigo);
    $stmt->execute();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $codigo;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Observação removida com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar remover a observação da demanda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

