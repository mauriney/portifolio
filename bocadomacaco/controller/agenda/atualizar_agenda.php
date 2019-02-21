
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$id_agenda = strip_tags(@$_POST['id']);
$grupo = @$_POST['grupo'];

try {
    $db->beginTransaction();

    //ADICIONANDO OS CHECKBOX DO GRUPO
    if (is_numeric($id_agenda) && sizeof($grupo) > 0) {

        $stmt44 = $db->prepare("DELETE FROM tb_bsc_agenda_segmento WHERE IdAgenda = ?");
        $stmt44->bindValue(1, $id_agenda);
        $stmt44->execute();

        foreach ($grupo as $value) {
            $stmt4 = $db->prepare("INSERT INTO tb_bsc_agenda_segmento (IdAgenda, IdSegmento) VALUES (?, ?)");
            $stmt4->bindValue(1, $id_agenda);
            $stmt4->bindValue(2, $value);
            $stmt4->execute();
        }
    } else {
        $stmt44 = $db->prepare("DELETE FROM tb_bsc_agenda_segmento WHERE IdAgenda = ?");
        $stmt44->bindValue(1, $id_agenda);
        $stmt44->execute();
    }

    $db->commit();

    //MENSAGEM DE SUCESSO
    @$_SESSION['mensagem'] = "OK";
    $msg['id'] = $id_agenda;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Grupos da agenda atualizada com sucesso.';
    echo json_encode($msg);
    exit();
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar atualizar o grupo da agenda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

