
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$codigo = strip_tags(@$_POST['codigo']);

$error = false;

$mensagem = "";

try {

    if ($error == false) {
        $db->beginTransaction();

        $stmt = $db->prepare("UPDATE x_veiculo_saida SET status = 2, data_chegada = ?, hora_chegada = ? WHERE id = ?");

        $stmt->bindValue(1, obterDataISO());
        $stmt->bindValue(2, obterHora());
        $stmt->bindValue(3, $codigo);

        $stmt->execute();

        $chegada_id = $db->lastInsertId();

        $db->commit();

        //MENSAGEM DE SUCESSO
        @$_SESSION['mensagem'] = "OK";
        $msg['id'] = $chegada_id;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Chegada confirmada com sucesso.';
        echo json_encode($msg);
        exit();
    } else {
        $msg['msg'] = 'error';
        $msg['retorno'] = $mensagem;
        echo json_encode($msg);
        exit();
    }
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar salvar os dados da chegada:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

