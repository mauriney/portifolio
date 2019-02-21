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
    $contato2 = strip_tags($_POST['contato2']);
    $email = strip_tags($_POST['email']);
    $sexo = strip_tags($_POST['sexo']);
    $cep = strip_tags($_POST['cep']);
    $bairro = strip_tags($_POST['bairro']);
    $endereco = strip_tags($_POST['endereco']);
    $cidade = strip_tags($_POST['cidade']);
    $nascimento = strip_tags($_POST['nascimento']);

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    $id_pessoa = pesquisar_tabela("id", "pessoas", "nome", "=", $nome, "");

    if (is_numeric($id_pessoa) && $id_pessoa != @$id) {
        $error = true;
        $mensagem .= "- O nome do funcionário informado já existe no sistema.";
        $msg['tipo'] = "nome";
    }

    //VERIFICA SE O E-MAIL INSTITUCIONAL INFORMADO JÁ EXISTE NO SISTEMA
    $id_email = pesquisar_tabela("id", "pessoas", "email", "=", $email, "");

    if (is_numeric($id_email) && $id_email != @$id) {
        $error = true;
        $mensagem .= "\n- O e-mail do funcionário informado já existe no sistema.";
        $msg['tipo'] = "email";
    }

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    if (!valida_cpf($cpf)) {
        $error = true;
        $mensagem .= "\n- O CPF do funcionário informado é inválido.";
        $msg['tipo'] = "cpf";
    } else {

        $id_cpf = pesquisar_tabela("id", "pessoas", "cpf", "=", $cpf, "");

        if (is_numeric($id_cpf) && $id_cpf != @$id) {
            $error = true;
            $mensagem .= "\n- O CPF do funcionário informado já existe no sistema.";
            $msg['tipo'] = "cpf";
        }
    }

    if ($error == false) {

        if (is_numeric($id)) {

            $stmt = $db->prepare("UPDATE pessoas SET nome = ?, cpf = ?, email = ?, contato1 = ?, contato2 = ?, sexo = ?, cep = ?, bairro = ?, endereco = ?, cidade_id = ?, nascimento = ?, atualizacao = NOW(), responsavel_id = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $cpf);
            $stmt->bindValue(3, $email);
            $stmt->bindValue(4, $contato);
            $stmt->bindValue(5, $contato2);
            $stmt->bindValue(6, $sexo);
            $stmt->bindValue(7, $cep);
            $stmt->bindValue(8, $bairro);
            $stmt->bindValue(9, $endereco);
            $stmt->bindValue(10, $cidade);
            $stmt->bindValue(11, convertDataBR2ISO($nascimento));
            $stmt->bindValue(12, $_SESSION['id']);
            $stmt->bindValue(13, $id);
            $stmt->execute();

            //RETORNAR O ID DO FUNCIONÁRIO
            $funcionario_id = $id;
        } else {
            $stmt = $db->prepare("INSERT INTO pessoas (nome, cpf, email, contato1, contato2, sexo, cep, bairro, endereco, cidade_id, nascimento) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $cpf);
            $stmt->bindValue(3, $email);
            $stmt->bindValue(4, $contato);
            $stmt->bindValue(5, $contato2);
            $stmt->bindValue(6, $sexo);
            $stmt->bindValue(7, $cep);
            $stmt->bindValue(8, $bairro);
            $stmt->bindValue(9, $endereco);
            $stmt->bindValue(10, $cidade);
            $stmt->bindValue(11, convertDataBR2ISO($nascimento));
            $stmt->execute();

            //RETORNAR O ID DA PESSOA
            $pessoa_id = $db->lastInsertId();

            $stmt = $db->prepare("INSERT INTO funcionarios (cadastro, atualizacao, status, responsavel_id, pessoa_id) 
                            VALUES (NOW(), NOW(), 1, ?, ?)");
            $stmt->bindValue(1, $_SESSION['id']);
            $stmt->bindValue(2, $pessoa_id);
            $stmt->execute();

            //RETORNAR O ID DO FUNCIONÁRIO
            $funcionario_id = $db->lastInsertId();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $funcionario_id;
        $msg['msg'] = 'success';
        if (is_numeric($id)) {
            $msg['retorno'] = 'Atualização realizada com sucesso.';
        } else {
            $msg['retorno'] = 'Cadastro realizada com sucesso.';
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