
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$id_agenda = strip_tags(@$_POST['id']);

$senha_atual = md5(strip_tags(@$_POST['senha-atual']));
$nova_senha = md5(strip_tags(@$_POST['nova-senha']));

$error = false;

$mensagem = "";

try {

    $usuario = pesquisar("IdUsuario", "tb_bsc_usuario", "senha", "=", $senha_atual, "");

    if (!is_numeric($usuario)) {
        $error = true;
        $mensagem .= "<span>A senha atual é inválida.</span>";
        $msg['tipo'] = "senha-atual";
    }


    if ($error == false) {
        $db->beginTransaction();

        $stmt = $db->prepare("UPDATE tb_bsc_usuario SET senha = ? WHERE IdUsuario = ?");
        $stmt->bindValue(1, $nova_senha);
        $stmt->bindValue(2, $_SESSION['id']);

        $stmt->execute();
        $usuario_id = $db->lastInsertId();

        $db->commit();

        //MENSAGEM DE SUCESSO
        @$_SESSION['mensagem'] = "OK";
        $msg['id'] = $usuario_id;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Senha atualizada com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar atualizar a senha:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

