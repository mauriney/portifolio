
<?php

@session_start();

require_once("../../../conf/config.php");
require_once('../../../utils/funcoes.php');

$db = Conexao::getInstance();

$segmento_id = $_POST['id'];

$nome = strip_tags(@$_POST['nome']);

$error = false;

$mensagem = "";

try {

    //VERIFICA SE O NOME DO SEGMENTO JÁ FOI INFORMADO
    $id_segmento = pesquisar("IdSegmento", "tb_bsc_segmento", "Descricao", "=", utf8_decode($nome), "");

    if (is_numeric($id_segmento) && $id_segmento != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>O nome do segmento informado já existe no sistema.</span>";
        $msg['tipo'] = "nome";
    }

    if ($error == false) {
        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {

            $stmt = $db->prepare("UPDATE tb_bsc_segmento SET Descricao = ?, IdUsuario = ? WHERE IdSegmento = ?");

            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->bindValue(2, $_SESSION['id']);
            $stmt->bindValue(3, $segmento_id);
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $segmento_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Segmento atualizado com sucesso.';
            echo json_encode($msg);
            exit();
        } else {
            $stmt = $db->prepare("INSERT INTO tb_bsc_segmento (Descricao, IdUsuario) VALUES (?, ?)");
            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->bindValue(2, $_SESSION['id']);
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $segmento_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Segmento cadastrado com sucesso.';
            echo json_encode($msg);
            exit();
        }
    } else {
        $msg['msg'] = 'error';
        $msg['retorno'] = $mensagem;
        echo json_encode($msg);
        exit();
    }
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar salvar os dados do segmento:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

