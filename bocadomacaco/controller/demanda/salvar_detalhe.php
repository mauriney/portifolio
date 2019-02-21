
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$demanda_id = strip_tags($_POST['id']);
$obs = strip_tags(@$_POST['observacoes']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("INSERT INTO x_demanda_obs (demanda_id, obs, responsavel_id, data_cadastro) VALUES (?, ?, ?, NOW())");

    $stmt->bindValue(1, $demanda_id);
    $stmt->bindValue(2, utf8_decode($obs));
    $stmt->bindValue(3, $_SESSION['id']);

    $stmt->execute();

    $obs_id = $db->lastInsertId();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $obs_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Observação da demanda adicionada com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar adicionar a observação da demanda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

