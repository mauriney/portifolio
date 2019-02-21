
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$obs_id = strip_tags($_POST['codigo']);
$obs = strip_tags(@$_POST["obs"]);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("UPDATE x_demanda_obs SET obs = ?, responsavel_id = ?, data_cadastro = NOW() WHERE id = ?");

    $stmt->bindValue(1, utf8_decode($obs));
    $stmt->bindValue(2, $_SESSION['id']);
    $stmt->bindValue(3, $obs_id);

    $stmt->execute();

    $obs_id = $db->lastInsertId();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $obs_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Observação da demanda atualizada com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar atualizar a observação da demanda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

