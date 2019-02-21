<?php

@session_start();
include_once('../../functions/geral.php');
include_once('../../conf/sistema.php');

$db = Conexao::getInstance();

$error = false;
$msg = "";
$mensagem = "";

$db->beginTransaction();

try {

    //PEGAR DADOS DE LOGIN
    $id = strip_tags($_POST['codigo']);
    $nome = strip_tags($_POST['nome']);
    $cpf = strip_tags($_POST['cpf']);
    $contato = strip_tags($_POST['contato']);
    $email = strip_tags($_POST['email']);
    $senha = strip_tags(sha1($_POST['senha']));

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    $id_usuario = pesquisar_tabela("id", "pessoas", "nome", "=", $nome, "");

    if (is_numeric($id_usuario) && $id_usuario != @$id) {
        $error = true;
        $mensagem .= "- O nome do usuário informado já existe no sistema.";
        $msg['tipo'] = "nome";
    }

    //VERIFICA SE O E-MAIL INSTITUCIONAL INFORMADO JÁ EXISTE NO SISTEMA
    $id_email = pesquisar_tabela("id", "pessoas", "email", "=", $email, "");

    if (is_numeric($id_email) && $id_email != @$id) {
        $error = true;
        $mensagem .= "\n- O e-mail do usuário informado já existe no sistema.";
        $msg['tipo'] = "email";
    }

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    if (!valida_cpf($cpf)) {
        $error = true;
        $mensagem .= "\n- O CPF do usuário informado é inválido.";
        $msg['tipo'] = "cpf";
    } else {

        $id_cpf = pesquisar_tabela("id", "pessoas", "cpf", "=", $cpf, "");

        if (is_numeric($id_cpf) && $id_cpf != @$id) {
            $error = true;
            $mensagem .= "\n- O CPF do usuário informado já existe no sistema.";
            $msg['tipo'] = "cpf";
        }
    }

    if ($error == false) {

        if (is_numeric($id)) {

            $stmt = $db->prepare("UPDATE pessoas SET nome = ?, cpf = ?, email = ?, contato1 = ?, responsavel_id = ?, atualizacao = NOW() 
                                  WHERE id = ?");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $cpf);
            $stmt->bindValue(3, $email);
            $stmt->bindValue(4, $contato);
            $stmt->bindValue(5, $_SESSION['id']);
            $stmt->bindValue(6, $id);
            $stmt->execute();

            //RETORNAR O ID DO USUARIO
            $usuario_id = $id;
        } else {
            $stmt = $db->prepare("INSERT INTO pessoas (nome, cpf, email, contato1, cadastro, status) 
                            VALUES (?, ?, ?, ?, NOW(), 2)");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $cpf);
            $stmt->bindValue(3, $email);
            $stmt->bindValue(4, $contato);
            $stmt->execute();

            //RETORNAR O ID DA PESSOA
            $pessoa_id = $db->lastInsertId();

            $stmt = $db->prepare("INSERT INTO usuarios (cadastro, atualizacao, status, responsavel_id, pessoa_id) 
                            VALUES (NOW(), NOW(), 1, 1, ?)");
            $stmt->bindValue(1, $pessoa_id);
            $stmt->execute();

            //RETORNAR O ID DO USUARIO
            $usuario_id = $db->lastInsertId();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $usuario_id;
        $msg['msg'] = 'success';
        if (is_numeric($id)) {
            $msg['retorno'] = 'Atualização realizada com sucesso.';
        } else {
            $msg['retorno'] = 'Cadastro realizada com sucesso. Por favor aguarde agora pela validação dos dados para acessar o sistema.';
        }
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
    $msg['retorno'] = "Erro ao tentar realizar o cadastro:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>