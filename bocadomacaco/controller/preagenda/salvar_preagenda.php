
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$nome = strip_tags(@$_POST['nome']);
$segmento = strip_tags(@$_POST['segmento']);
$celular = strip_tags(@$_POST['celular']);
$email = strip_tags(@$_POST['email']);
$prioridade = strip_tags(@$_POST['prioridade']);
$prazo = convertDataBR2ISO(strip_tags(@$_POST['prazo']));
$assunto = strip_tags(@$_POST['assunto']);
$opcao = strip_tags(@$_POST['opcao']);

$error = false;

$mensagem = "";

try {

    if ($error == false) {
        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {

            $stmt = $db->prepare("UPDATE tb_bsc_preagenda SET TelefoneCel = ?, Email = ?, IdPrioridade = ?, prazo = ?, Nome = ?, IdSegmento = ?, Assunto = ?, atencao = ?, IdUsuario = ?, data_cadastro = NOW() WHERE IdPreAgenda = ?");

            $stmt->bindValue(1, $celular);
            $stmt->bindValue(2, utf8_decode($email));
            $stmt->bindValue(3, $prioridade);
            $stmt->bindValue(4, $prazo);
            $stmt->bindValue(5, $nome);
            $stmt->bindValue(6, $segmento);
            $stmt->bindValue(7, $assunto);
            $stmt->bindValue(8, $opcao);
            $stmt->bindValue(9, $_SESSION['id']); //ID DO USUÁRIO RESPONSÁVEL PELO CADASTRO
            $stmt->bindValue(10, $_POST['id']);

            $stmt->execute();

            $preagenda_id = $db->lastInsertId();

            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $preagenda_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Pré-Agenda atualizada com sucesso.';
            echo json_encode($msg);
            exit();
        } else {
            $stmt = $db->prepare("INSERT INTO tb_bsc_preagenda (TelefoneCel, Email, IdPrioridade, prazo, Nome, IdSegmento, Assunto, atencao, IdUsuario, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

            $stmt->bindValue(1, $celular);
            $stmt->bindValue(2, utf8_decode($email));
            $stmt->bindValue(3, $prioridade);
            $stmt->bindValue(4, $prazo);
            $stmt->bindValue(5, $nome);
            $stmt->bindValue(6, $segmento);
            $stmt->bindValue(7, $assunto);
            $stmt->bindValue(8, $opcao);
            $stmt->bindValue(9, $_SESSION['id']); //ID DO USUÁRIO RESPONSÁVEL PELO CADASTRO

            $stmt->execute();
            $preagenda_id = $db->lastInsertId();

            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $preagenda_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Pré-Agenda cadastrada com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar salvar os dados da pré-agenda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

