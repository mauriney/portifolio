
<?php

@session_start();

require_once("../../../conf/config.php");
require_once('../../../utils/funcoes.php');

$db = Conexao::getInstance();

$bairro_id = $_POST['id'];

$nome = utf8_decode(strip_tags(@$_POST['nome']));
$regional = strip_tags(@$_POST['regional']);

$error = false;

$mensagem = "";

try {

    //VERIFICA SE O NOME DO SEGMENTO JÁ FOI INFORMADO
    $id_bairro = pesquisar("idbairro", "tb_bsc_bairro", "nome", "=", $nome, "");

    if (is_numeric($id_bairro) && $id_bairro != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>O nome do bairro informado já existe no sistema.</span>";
        $msg['tipo'] = "nome";
    }

    if ($error == false) {
        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {

            $stmt = $db->prepare("UPDATE tb_bsc_bairro SET nome = ?, regional = ? WHERE idbairro = ?");

            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $regional);
            $stmt->bindValue(3, $bairro_id);
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $bairro_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Bairro atualizado com sucesso.';
            echo json_encode($msg);
            exit();
        } else {
            $stmt = $db->prepare("INSERT INTO tb_bsc_bairro (nome, regional) VALUES (?, ?)");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $regional);
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $bairro_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Bairro cadastrado com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar salvar os dados do bairro:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

