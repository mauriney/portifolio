
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');
require("../../utils/phpmailer/class.smtp.php");
require("../../utils/phpmailer/class.phpmailer.php");

$db = Conexao::getInstance();

$demanda = strip_tags(@$_POST['demanda']);
$segmento = strip_tags(@$_POST['segmento']);
$municipio = strip_tags(@$_POST['municipio']);
$solicitante = strip_tags(@$_POST['solicitante']);
$celular = strip_tags(@$_POST['celular']);
$email = strip_tags(@$_POST['email']);
$prioridade = strip_tags(@$_POST['prioridade']);
$prazo = convertDataBR2ISO(strip_tags(@$_POST['prazo']));
$quemindicou = strip_tags(@$_POST['quem_indicou']);

$responsavel = @$_POST['responsavel'];

$error = false;

$mensagem = "";

try {

    //VERIFICA SE O NOME DA DEMANDA JÁ FOI INFORMADO
    $id_demanda = pesquisar("id", "tb_bsc_demanda", "descricao", "=", utf8_decode($demanda), "");

    if (is_numeric($id_demanda) && $id_demanda != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>O nome da demanda informada já existe no sistema.</span>";
        $msg['tipo'] = "demanda";
    }

    if ($error == false) {
        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {

            $stmt = $db->prepare("UPDATE x_demanda SET solicitante = ?, cidade_id = ?, celular = ?, email = ?, prioridade = ?, prazo = ?, demanda = ?, segmento = ?, quemindicou = ?, confirmacao = 1 WHERE id = ?");

            $stmt->bindValue(1, utf8_decode($solicitante));
            $stmt->bindValue(2, $municipio);
            $stmt->bindValue(3, $celular);
            $stmt->bindValue(4, utf8_decode($email));
            $stmt->bindValue(5, $prioridade);
            $stmt->bindValue(6, $prazo);
            $stmt->bindValue(7, utf8_decode($demanda));
            $stmt->bindValue(8, $segmento);
            $stmt->bindValue(9, $quemindicou);
            $stmt->bindValue(10, $_POST['id']);

            $stmt->execute();

            $demanda_id = $db->lastInsertId();

            //INSERINDO RESPONSÁVEL
            if (isset($responsavel) && $responsavel != NULL && $responsavel != "") {

                //CRIANDO EMAIL DE DEMANDA
                $email_criado = criar_email_demanda($demanda, $prazo, $responsavel);

                $stmt22 = $db->prepare("DELETE FROM x_demanda_responsavel WHERE demanda_id = ?");
                $stmt22->bindValue(1, $_POST['id']);
                $stmt22->execute();

                foreach ($responsavel as $value) {
                    $stmt2 = $db->prepare("INSERT INTO x_demanda_responsavel (responsavel_id, demanda_id, data_cadastro) VALUES (?, ?, NOW())");
                    $stmt2->bindValue(1, $value);
                    $stmt2->bindValue(2, $_POST['id']);
                    $stmt2->execute();

                    //ENVIO DE EMAIL DA DEMANDA PARA TODOS OS RESPONSÁVEIS
                    if (is_numeric(pesquisar("IdUsuario", "tb_bsc_usuario", $value, "=", "IdUsuario", "AND IdAcesso <> 1"))) {//NÃO ENVIAR PARA OS ADMINISTRADORES, POIS EMBAIXO JÁ VAI SER ENVIADOS PARA TODOS
                        envia_email(pesquisar("Email", "tb_bsc_usuario", $value, "=", "IdUsuario", ""), "Demanda", $email_criado, "Demanda");
                    }
                }
            }

            //ENVIO DE EMAIL DA DEMANDA PARA TODOS OS ADMINISTRADORES DO SISTEMA
            $res = $db->prepare("SELECT Email FROM tb_bsc_usuario WHERE IdAcesso = 1");
            $res->execute();
            while ($admin = $res->fetch(PDO::FETCH_ASSOC)) {
                envia_email($admin['Email'], "Demanda", $email_criado, "Demanda");
            }

            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $demanda_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Demanda atualizada com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar salvar os dados da demanda:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

