
<?php

@session_start();

require_once("../../../conf/config.php");
require_once('../../../utils/funcoes.php');

$db = Conexao::getInstance();

$grupo_id = $_POST['id'];

$nome = strip_tags(@$_POST['nome']);

$error = false;

$mensagem = "";

try {

    //VERIFICA SE O NOME DO GRUPO JÁ FOI INFORMADO
    $id_grupo = pesquisar("IdGrupoEmail", "tb_bsc_grupo_email", "Nome", "=", utf8_decode($nome), "");

    if (is_numeric($id_grupo) && $id_grupo != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>O nome do grupo informado já existe no sistema.</span>";
        $msg['tipo'] = "nome";
    }

    if ($error == false) {
        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {

            $stmt = $db->prepare("UPDATE tb_bsc_grupo_email SET Nome = ? WHERE IdGrupoEmail = ?");

            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->bindValue(2, $grupo_id);
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $grupo_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Grupo atualizado com sucesso.';
            echo json_encode($msg);
            exit();
        } else {
            $stmt = $db->prepare("INSERT INTO tb_bsc_grupo_email (Nome) VALUES (?)");
            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $grupo_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Grupo cadastrado com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar salvar os dados do grupo:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

