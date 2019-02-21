<?php

@session_start();
include_once('../functions/geral.php');
include_once('../conf/sistema.php');

$db = Conexao::getInstance();

$msg = "";

$db->beginTransaction();

try {

//PEGAR DADOS DE LOGIN
    $email = strip_tags($_POST['email']);
    $senha = strip_tags(sha1($_POST['senha']));

//SQL PARA VERIFICAÇÃO DE LOGIN EXISTENTE
    $result = $db->prepare("SELECT p.senha, p.id AS pessoa_id, p.nome, p.status, p.cadastro
			  FROM pessoas p
			  WHERE p.email = ?");
    $result->bindParam(1, $email);
    $result->execute();

    $num = $result->rowCount();

    if ($num > 0) {
//PEGA OS DADOS DO USUARIO, CASO TENHA ACESSO
        $dadosUsuario = $result->fetch(PDO::FETCH_ASSOC);

//VERIFICA SE A SENHA INFORMADA É IGUAL DO USUARIO
        if ($senha == $dadosUsuario['senha']) {

            if ($dadosUsuario['status'] == 1) {

                $id = $dadosUsuario['pessoa_id'];

//CRIAR O TIMEOUT DA SESSÃO PARA EXPIRAR
                $_SESSION['timeout'] = time();

//CRIAR AS SESSÕES DO USUARIO
                $_SESSION['id'] = $id;
                $_SESSION['nome'] = $dadosUsuario['nome'];
                $_SESSION['login'] = $email;
                $_SESSION['cadastro'] = $dadosUsuario['cadastro'];
                $_SESSION['cliente'] = pesquisar_tabela("id", "clientes", "pessoa_id", "=", $id, "");
                $_SESSION['fornecedor'] = pesquisar_tabela("id", "fornecedores", "pessoa_id", "=", $id, "");
                $_SESSION['funcionario'] = pesquisar_tabela("id", "funcionarios", "pessoa_id", "=", $id, "");
                $_SESSION['usuario'] = pesquisar_tabela("id", "usuarios", "pessoa_id", "=", $id, "");
                $_SESSION['estabelecimento'] = pesquisar_tabela("nome", "estabelecimento", "id", "=", 1, "");

                $atualizar = $db->prepare("UPDATE pessoas SET online = 1 WHERE id = ?");
                $atualizar->bindValue(1, $dadosUsuario['pessoa_id']);
                $atualizar->execute();

                $db->commit();

//MENSAGEM DE SUCESSO
                $msg['id'] = $id;
                $msg['msg'] = 'success';
                $msg['retorno'] = 'Login efetuado com sucesso.';
                echo json_encode($msg);
                exit();
            } else {
                $msg['msg'] = 'error';
                $msg['retorno'] = 'Você não tem permissão de acesso ao sistema.';
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
    $msg['retorno'] = "Erro ao tentar efeturar o login. :" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>