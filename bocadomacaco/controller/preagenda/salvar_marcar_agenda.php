
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');
require("../../utils/phpmailer/class.smtp.php");
require("../../utils/phpmailer/class.phpmailer.php");

$db = Conexao::getInstance();

$pre_id = strip_tags(@$_POST['id']);

$demandante = strip_tags(@$_POST['demandante']);
$segmento = strip_tags(@$_POST['segmento']);
$celular = strip_tags(@$_POST['celular']);
$fixo = strip_tags(@$_POST['fixo']);
$email = strip_tags(@$_POST['email']);
$prioridade = strip_tags(@$_POST['prioridade']);
$pauta = strip_tags(@$_POST['pauta']);
$data_agenda = strip_tags(@$_POST['data']);
$hora = strip_tags(@$_POST['hora']);
$local = strip_tags(@$_POST['local']);
$bairro = @$_POST['bairro'];
$quemvai = @$_POST['quemvai'];
$municipio_id = @$_POST['municipio'];

$obs = strip_tags(@$_POST['Observacao']);

//$intermediario = strip_tags(@$_POST['intermediario']);
//$basico = strip_tags(@$_POST['basico']);

$intermediario = 2;
$basico = 3;

$opcao1 = strip_tags(@$_POST['opcao1']); //ATENÇÃO EXTRA A AGENDA
$opcao2 = strip_tags(@$_POST['opcao2']); //SE A AGENDA É RECORRENTE

$periodicidade = strip_tags(@$_POST['periodicidade']);

$grupo = @$_POST['grupo'];

$confirmado = 0;

if (isset($_POST['opcao3'])) {
    $confirmado = 1;
}

$error = false;

$mensagem = "";

try {

    if ($error == false) {
        $db->beginTransaction();

        $stmt = $db->prepare("INSERT INTO tb_bsc_agenda (Demandante, IdSegmento, TelefoneCelDem, TelefoneFixoDem, EmailDemandante, IdPrioridade, Pauta, atencao, DataAgenda, HoraAgenda, LocalEvento, Bairro, "
                . "IdMunicipio, recorrente, Confirmado, IdUsuario, Observacao, DataHoraCadastro)"
                . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bindValue(1, $demandante);
        $stmt->bindValue(2, $segmento);
        $stmt->bindValue(3, $celular);
        $stmt->bindValue(4, $fixo);
        $stmt->bindValue(5, $email);
        $stmt->bindValue(6, $prioridade);
        $stmt->bindValue(7, $pauta);
        $stmt->bindValue(8, $opcao1);
        $stmt->bindValue(9, convertDataBR2ISO($data_agenda));
        $stmt->bindValue(10, $hora);
        $stmt->bindValue(11, $local);
        $stmt->bindValue(12, $bairro);
        $stmt->bindValue(13, $municipio_id);
        $stmt->bindValue(14, $opcao2);
        $stmt->bindValue(15, $confirmado);
        $stmt->bindValue(16, $_SESSION['id']); //ID DO USUÁRIO RESPONSÁVEL PELO CADASTRO
        $stmt->bindValue(17, $obs);

        $stmt->execute();
        $agenda_id = $db->lastInsertId();

        //CRIAÇÃO DO EMAIL DE AGENDA
        $email_criado = criar_email_agenda($pauta, $local, $bairro, convertDataBR2ISO($data_agenda), $hora, $confirmado);


        //INSERIR NÍVEL DE ACESSO INTERMEDIÁRIO
        if ($intermediario == 2 && is_numeric($agenda_id)) {
            $stmt2 = $db->prepare("INSERT INTO tb_bsc_acesso_agenda (IdAcesso, IdAgenda)"
                    . " VALUES (?, ?)");
            $stmt2->bindValue(1, $intermediario);
            $stmt2->bindValue(2, $agenda_id);
            $stmt2->execute();
        }
        //INSERINDO NÍVEL DE ACESSO BÁSCIO
        if ($basico == 3 && is_numeric($agenda_id)) {
            $stmt3 = $db->prepare("INSERT INTO tb_bsc_acesso_agenda (IdAcesso, IdAgenda)"
                    . " VALUES (?, ?)");
            $stmt3->bindValue(1, $basico);
            $stmt3->bindValue(2, $agenda_id);
            $stmt3->execute();
        }

        //ADICIONANDO OS CHECKBOX DO GRUPO
        if (is_numeric($agenda_id) && sizeof($grupo) > 0) {
            foreach ($grupo as $value) {
                $stmt4 = $db->prepare("INSERT INTO tb_bsc_agenda_segmento (IdAgenda, IdSegmento) VALUES (?, ?)");
                $stmt4->bindValue(1, $agenda_id);
                $stmt4->bindValue(2, $value);
                $stmt4->execute();
            }
        }

        //ADICIONANDO OS PARTICIPANTES DA AGENDA
        if (is_numeric($agenda_id) && sizeof($quemvai) > 0) {
            foreach ($quemvai as $value) {
                $stmt7 = $db->prepare("INSERT INTO x_agenda_participante (IdAgenda, IdContato) VALUES (?, ?)");
                $stmt7->bindValue(1, $agenda_id);
                $stmt7->bindValue(2, $value);
                $stmt7->execute();
            }
        }

        //ENVIO DE EMAIL PARA QUEM VAI
        if (is_numeric($agenda_id) && sizeof($quemvai) > 0) {
            foreach ($quemvai as $value2) {
                //ENVIO DE EMAIL DA AGENDA
                if (is_numeric(pesquisar("IdUsuario", "tb_bsc_usuario", $value2, "=", "IdUsuario", "AND IdAcesso <> 1"))) {//NÃO ENVIAR PARA OS ADMINISTRADORES, POIS EMBAIXO JÁ VAI SER ENVIADOS PARA TODOS
                    if ($confirmado == 1) {
                        envia_email(pesquisar("Email", "tb_bsc_usuario", $value2, "=", "IdUsuario", ""), "Agenda", $email_criado, "Agenda");
                    } else {
                        envia_email(pesquisar("Email", "tb_bsc_usuario", $value2, "=", "IdUsuario", ""), "Agenda", $email_criado, "Agenda");
                    }
                }
            }
        }

        //ENVIO DE EMAIL DA DEMANDA PARA TODOS OS ADMINISTRADORES DO SISTEMA
        $res = $db->prepare("SELECT Email FROM tb_bsc_usuario WHERE IdAcesso = 1");
        $res->execute();
        while ($admin = $res->fetch(PDO::FETCH_ASSOC)) {
            if ($confirmado == 1) {
                envia_email($admin['Email'], "Agenda", $email_criado, "Agenda");
            } else {
                envia_email($admin['Email'], "Agenda", $email_criado, "Agenda");
            }
        }

        //INSERINDO A PERIODICIDADE CASO TENHA
        if ($opcao2 == 1 && $periodicidade != "" && is_numeric($agenda_id)) {

            $stmt5 = $db->prepare("DELETE FROM tb_bsc_aviso_agenda WHERE IdAgenda = ?");
            $stmt5->bindValue(1, $agenda_id);
            $stmt5->execute();

            $stmt55 = $db->prepare("INSERT INTO tb_bsc_aviso_agenda (data, IdAgenda)"
                    . " VALUES (?, ?)");
            $stmt55->bindValue(1, $periodicidade);
            $stmt55->bindValue(2, $agenda_id);
            $stmt55->execute();
        }

        //ATUALIZANDO PRÉ-AGENDA
        if (is_numeric($pre_id) && is_numeric($agenda_id)) {
            $stmt6 = $db->prepare("UPDATE tb_bsc_preagenda SET IdAgenda = ? WHERE IdPreAgenda = ?");
            $stmt6->bindValue(1, $agenda_id);
            $stmt6->bindValue(2, $pre_id);
            $stmt6->execute();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        @$_SESSION['mensagem'] = "OK";
        $msg['id'] = $agenda_id;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Agenda marcada com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar marcar a agenda desejada:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

