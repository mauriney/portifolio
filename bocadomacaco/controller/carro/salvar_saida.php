
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$veiculo_id = strip_tags(@$_POST['veiculo_id']);
$motorista_id = strip_tags(@$_POST['motorista_id']);

$data_saida = strip_tags(@$_POST['data_saida']);
$hora_saida = strip_tags(@$_POST['horas']);
$data_prevista = strip_tags(@$_POST['data_prevista']);
$hora_prevista = strip_tags(@$_POST['horap']);

$obs = strip_tags(@$_POST['obs']);

$error = false;

$mensagem = "";

try {

    if (convertDataBR2ISO($data_prevista) < convertDataBR2ISO($data_saida)) {
        $error = true;
        $mensagem .= "<span>A data prevista não pode ser menor que a data de saída.</span>";
        $msg['tipo'] = "data_prevista";
    }

    if ($error == false && strtotime($hora_prevista) < strtotime($hora_saida) && convertDataBR2ISO($data_prevista) == convertDataBR2ISO($data_saida)) {
        $error = true;
        $mensagem .= "<span>A hora prevista não pode ser menor que a hora de saída.</span>";
        $msg['tipo'] = "horap";
    }

    if ($error == false) {

        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {
            $stmt = $db->prepare("UPDATE x_veiculo_saida SET veiculo_id = ?, motorista_id = ?, data_saida = ?, hora_saida = ?, hora_prevista = ?, data_prevista = ?, obs = ?, responsavel_id = ?, data_cadastro = NOW() 
                                 WHERE id = ?");

            $stmt->bindValue(1, $veiculo_id);
            $stmt->bindValue(2, $motorista_id);
            $stmt->bindValue(3, convertDataBR2ISO($data_saida));
            $stmt->bindValue(4, $hora_saida);
            $stmt->bindValue(5, $hora_prevista);
            $stmt->bindValue(6, convertDataBR2ISO($data_prevista));
            $stmt->bindValue(7, utf8_decode($obs));
            $stmt->bindValue(8, $_SESSION['id']);
            $stmt->bindValue(9, $_POST['id']);

            $stmt->execute();

            $saida_id = $db->lastInsertId();

            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $saida_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Saída atualizada com sucesso.';
            echo json_encode($msg);
            exit();
        } else {

            $saida_id = pesquisa2("id", "x_veiculo_saida", "veiculo_id = ?", $veiculo_id, "AND motorista_id = ?", $motorista_id, "", "", "", "", "AND status = 1");

            if (!is_numeric($saida_id)) {

                $stmt = $db->prepare("INSERT INTO x_veiculo_saida (veiculo_id, motorista_id, data_saida, hora_saida, hora_prevista, data_prevista, obs, responsavel_id, data_cadastro, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1)");

                $stmt->bindValue(1, $veiculo_id);
                $stmt->bindValue(2, $motorista_id);
                $stmt->bindValue(3, convertDataBR2ISO($data_saida));
                $stmt->bindValue(4, $hora_saida);
                $stmt->bindValue(5, $hora_prevista);
                $stmt->bindValue(6, convertDataBR2ISO($data_prevista));
                $stmt->bindValue(7, utf8_decode($obs));
                $stmt->bindValue(8, $_SESSION['id']);

                $stmt->execute();

                $saida_id = $db->lastInsertId();

                $db->commit();
            }
            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $saida_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Saída confirmada com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar salvar os dados da saída:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

