<?php

@session_start();

include_once('conf/config.php');
$db = Conexao::getInstance();

$msg = "";

try {
    //PEGAR DADOS DE LOGIN
    $idsessao = session_id();
    $ip = $_SERVER['REMOTE_ADDR'];
    $login = strip_tags($_POST['login']);
    $senha = md5(strip_tags($_POST['senha']));

    //SQL PARA VERIFICAÇÃO DE LOGIN EXISTENTE
    $result = $db->prepare("SELECT *
			    FROM tb_bsc_usuario WHERE login = ?");
    $result->bindValue(1, $login);
    $result->execute();
    $num = $result->rowCount();

    if ($num > 0) {
        //PEGA OS DADOS DO USUARIO, CASO TENHA ACESSO
        $dadosUsuario = $result->fetch(PDO::FETCH_ASSOC);

        //VERIFICA SE A SENHA INFORMADA É IGUAL DO USUARIO
        if ($senha == $dadosUsuario['senha']) {

            if ($dadosUsuario['Status'] == 1) {

                //CRIAR O TIMEOUT DA SESSÃO PARA EXPIRAR
                $_SESSION['timeout'] = time();
                $_SESSION['mensagem'] = "NO";
                $_SESSION['nome'] = utf8_encode($dadosUsuario['Nome']);
                $_SESSION['id'] = $dadosUsuario['IdUsuario'];
                $_SESSION['email'] = $dadosUsuario['Email'];
                $_SESSION['acesso'] = $dadosUsuario['IdAcesso'];
                $_SESSION['funcao'] = utf8_encode($dadosUsuario['Funcao']);
                $_SESSION['demanda'] = $dadosUsuario['Demanda'];

                //BUSCAR NAVEGADOR E SO
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
                    $browser_version = $matched[1];
                    $browser = 'IE';
                } elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
                    $browser_version = $matched[1];
                    $browser = 'Opera';
                } elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
                    $browser_version = $matched[1];
                    $browser = 'Firefox';
                } elseif (preg_match('|Chrome/([0-9\.]+)|', $useragent, $matched)) {
                    $browser_version = $matched[1];
                    $browser = 'Chrome';
                } elseif (preg_match('|Safari/([0-9\.]+)|', $useragent, $matched)) {
                    $browser_version = $matched[1];
                    $browser = 'Safari';
                } else {
                    $browser_version = 0;
                    $browser = 'Desconhecido';
                }

                $separa = explode(";", $useragent);
                $so = $separa[1];

                //DELETANDO CONEXÕES DO USUÁRIO LOGADO
                $del = $db->prepare("DELETE FROM info_login WHERE idusuario = ?");
                $del->bindValue(1, $dadosUsuario['IdUsuario']);
                $del->execute();

                //INSERINDO NA TABELA LOGIN
                $insert = $db->prepare("INSERT INTO info_login (idusuario, login, logout, idsessao, ipusuario, navegador, sistema)"
                        . " VALUES (?, NOW(), null, ?, ?, ?, ?)");

                $insert->bindValue(1, $dadosUsuario['IdUsuario']);
                $insert->bindValue(2, $idsessao);
                $insert->bindValue(3, $ip);
                $insert->bindValue(4, "$browser $browser_version");
                $insert->bindValue(5, $so);

                $insert->execute();

                //MENSAGEM DE SUCESSO
                $msg['id'] = $dadosUsuario['IdUsuario'];
                $msg['msg'] = 'success';
                $msg['retorno'] = 'Login efetuado com sucesso.';
                echo json_encode($msg);
                exit();
            } else {
                $msg['msg'] = 'error';
                $msg['retorno'] = 'Você não tem permissão de acesso ao sistema!';
                echo json_encode($msg);
                exit();
            }
        } else {
            $msg['msg'] = 'error';
            $msg['retorno'] = 'O usuário ou a senha inseridos estão incorretos.';
            echo json_encode($msg);
            exit();
        }
    } else {
        $msg['msg'] = 'error';
        $msg['retorno'] = 'O usuário ou a senha inseridos estão incorretos.';
        echo json_encode($msg);
        exit();
    }
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar efetuar o login. :" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>