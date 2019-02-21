
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$id = strip_tags(@$_POST['id']);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("DELETE FROM tb_bsc_agenda WHERE IdAgenda = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $agenda_id = $db->lastInsertId();

    $stmt23 = $db->prepare("DELETE FROM tb_bsc_acesso_agenda WHERE IdAgenda = ?");
    $stmt23->bindValue(1, $id);
    $stmt23->execute();

    $stmt44 = $db->prepare("DELETE FROM tb_bsc_agenda_segmento WHERE IdAgenda = ?");
    $stmt44->bindValue(1, $id);
    $stmt44->execute();

    $stmt77 = $db->prepare("DELETE FROM x_agenda_participante WHERE IdAgenda = ?");
    $stmt77->bindValue(1, $id);
    $stmt77->execute();

    $stmt5 = $db->prepare("DELETE FROM tb_bsc_aviso_agenda WHERE IdAgenda = ?");
    $stmt5->bindValue(1, $id);
    $stmt5->execute();

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $agenda_id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Agenda removida com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar remover a agenda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

