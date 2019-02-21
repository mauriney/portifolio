
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$modelo = strip_tags(@$_POST['modelo']);
$placa = strip_tags(@$_POST['placa']);
$cor = strip_tags(@$_POST['cor']);

$error = false;

$mensagem = "";

try {

    $id_veiculo = pesquisar("id", "x_veiculo", "placa", "=", $placa, "");

    if (is_numeric($id_veiculo) && is_numeric($id_veiculo) && $id_veiculo != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>A placa informada já existe no sistema.</span>";
        $msg['tipo'] = "placa";
    }

    if ($error == false) {

        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {
            $stmt = $db->prepare("UPDATE x_veiculo SET modelo = ?, placa = ?, cor = ?, responsavel_id = ?, data_cadastro = NOW()  
                 WHERE id = ?");

            $stmt->bindValue(1, utf8_decode($modelo));
            $stmt->bindValue(2, utf8_decode($placa));
            $stmt->bindValue(3, utf8_decode($cor));
            $stmt->bindValue(4, $_SESSION['id']);
            $stmt->bindValue(5, $_POST['id']);

            $stmt->execute();

            $veiculo_id = $db->lastInsertId();

            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $veiculo_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Veículo atualizado com sucesso.';
            echo json_encode($msg);
            exit();
        } else {
            $stmt = $db->prepare("INSERT INTO x_veiculo (modelo, placa, cor, responsavel_id, data_cadastro, status) 
                 VALUES (?, ?, ?, ?, NOW(), 1)");

            $stmt->bindValue(1, utf8_decode($modelo));
            $stmt->bindValue(2, utf8_decode($placa));
            $stmt->bindValue(3, utf8_decode($cor));
            $stmt->bindValue(4, $_SESSION['id']);

            $stmt->execute();

            $veiculo_id = $db->lastInsertId();

            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $veiculo_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Veículo cadastrado com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar salvar os dados do veículo:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

