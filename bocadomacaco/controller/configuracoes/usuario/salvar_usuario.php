
<?php

@session_start();

require_once("../../../conf/config.php");
require_once('../../../utils/funcoes.php');

$db = Conexao::getInstance();

$usuario_id = $_POST['id'];

$nome = strip_tags(@$_POST['nome']);
$funcao = strip_tags(@$_POST['funcao']);
$email = strip_tags(@$_POST['email']);
$fixo = strip_tags(@$_POST['fixo']);
$celular = strip_tags(@$_POST['celular']);
$nivel = strip_tags(@$_POST['nivel-acesso']);
$status = strip_tags(@$_POST['status-usuario']);
$agenda = strip_tags(@$_POST['receber-agenda']);
$resumo = strip_tags(@$_POST['receber-resumo']);
$login = strip_tags(@$_POST['login']);
$senha = strip_tags(@$_POST['senha']);

$quemvai = strip_tags(@$_POST['quemvai']);
$demanda = strip_tags(@$_POST['demanda']);

$error = false;

$mensagem = "";

try {

    //VERIFICA SE O NOME DO USUÁRIO JÁ FOI INFORMADO
    $id_usuario = pesquisar("IdUsuario", "tb_bsc_usuario", "Nome", "=", $nome, "");

    if (is_numeric($id_usuario) && $id_usuario != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>O nome do usuário informado já existe no sistema.</span>";
        $msg['tipo'] = "nome";
    }

    //VERIFICA SE O NOME DO USUÁRIO JÁ FOI INFORMADO
    $id_login = pesquisar("IdUsuario", "tb_bsc_usuario", "login", "=", $login, "");

    if (is_numeric($id_login) && $id_login != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>O login do usuário informado já existe no sistema.</span>";
        $msg['tipo'] = "login";
    }

    if ($error == false) {
        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {

            $stmt = $db->prepare("UPDATE tb_bsc_usuario SET Nome = ?, Funcao = ?, Email = ?, TelefoneFixo = ?, TelefoneCel = ?, "
                    . "IdAcesso = ?, Status = ?, RecebeEmail = ?, RecebeEmail2 = ?, login = ?, senha = ?, quemvai = ?, Demanda = ?, DataHoraCadastro = NOW()"
                    . " WHERE Idusuario = ?");

            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->bindValue(2, utf8_decode($funcao));
            $stmt->bindValue(3, $email);
            $stmt->bindValue(4, $fixo);
            $stmt->bindValue(5, $celular);
            $stmt->bindValue(6, $nivel);
            $stmt->bindValue(7, $status);
            $stmt->bindValue(8, $agenda);
            $stmt->bindValue(9, $resumo);
            $stmt->bindValue(10, $login);
            $stmt->bindValue(11, md5($senha));
            $stmt->bindValue(12, $quemvai);
            $stmt->bindValue(13, $demanda);
            $stmt->bindValue(14, $usuario_id);
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $usuario_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Usuário atualizado com sucesso.';
            echo json_encode($msg);
            exit();
        } else {
            $stmt = $db->prepare("INSERT INTO tb_bsc_usuario (Nome, Funcao, Email, TelefoneFixo, TelefoneCel, IdAcesso, Status, RecebeEmail, RecebeEmail2, login, senha, quemvai, Demanda, DataHoraCadastro)"
                    . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->bindValue(2, utf8_decode($funcao));
            $stmt->bindValue(3, $email);
            $stmt->bindValue(4, $fixo);
            $stmt->bindValue(5, $celular);
            $stmt->bindValue(6, $nivel);
            $stmt->bindValue(7, $status);
            $stmt->bindValue(8, $agenda);
            $stmt->bindValue(9, $resumo);
            $stmt->bindValue(10, $login);
            $stmt->bindValue(11, md5($senha));
            $stmt->bindValue(12, $quemvai);
            $stmt->bindValue(13, $demanda);
            $stmt->execute();
            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $usuario_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Usuário cadastrado com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar salvar os dados do usuário:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

