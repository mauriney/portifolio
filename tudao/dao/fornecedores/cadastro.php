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
    $tipo = strip_tags($_POST['tipo']);
    $nome_fantasia = strip_tags($_POST['nome_fantasia']);
    $razao = strip_tags($_POST['razao']);
    $cnpj = strip_tags($_POST['cnpj']);

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    $id_pessoa = pesquisar_tabela("id", "pessoas", "nome", "=", $nome, "");

    if (is_numeric($id_pessoa) && $id_pessoa != @$id) {
        $error = true;
        $mensagem .= "- O nome do fornecedor informado já existe no sistema.";
        $msg['tipo'] = "nome";
    }

    //VERIFICA SE O E-MAIL INSTITUCIONAL INFORMADO JÁ EXISTE NO SISTEMA
    $id_email = pesquisar_tabela("id", "pessoas", "email", "=", $email, "");

    if (is_numeric($id_email) && $id_email != @$id) {
        $error = true;
        $mensagem .= "\n- O e-mail do fornecedor informado já existe no sistema.";
        $msg['tipo'] = "email";
    }

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    if (!valida_cpf($cpf)) {
        $error = true;
        $mensagem .= "\n- O CPF do fornecedor informado é inválido.";
        $msg['tipo'] = "cpf";
    } else {

        $id_cpf = pesquisar_tabela("id", "pessoas", "cpf", "=", $cpf, "");

        if (is_numeric($id_cpf) && $id_cpf != @$id) {
            $error = true;
            $mensagem .= "\n- O CPF do fornecedor informado já existe no sistema.";
            $msg['tipo'] = "cpf";
        }
    }

    if ($error == false) {

        if (is_numeric($id)) {
            $stmt = $db->prepare("UPDATE pessoas SET nome = ?, razao_social = ?, cnpj = ?, cpf = ?, email = ?, contato1 = ?, contato2 = ?, sexo = ?, cep = ?, bairro = ?, endereco = ?, cidade_id = ?, nascimento = ?, atualizacao = NOW(), responsavel_id = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, $tipo == 1 ? $nome : $nome_fantasia);
            $stmt->bindValue(2, $tipo == 2 ? $razao : NULL);
            $stmt->bindValue(3, $tipo == 2 ? $cnpj : NULL);
            $stmt->bindValue(4, $cpf);
            $stmt->bindValue(5, $email);
            $stmt->bindValue(6, $contato);
            $stmt->bindValue(7, $contato2);
            $stmt->bindValue(8, $sexo);
            $stmt->bindValue(9, $cep);
            $stmt->bindValue(10, $bairro);
            $stmt->bindValue(11, $endereco);
            $stmt->bindValue(12, $cidade);
            $stmt->bindValue(13, convertDataBR2ISO($nascimento));
            $stmt->bindValue(14, $_SESSION['id']);
            $stmt->bindValue(15, $id);
            $stmt->execute();

            $stmt = $db->prepare("UPDATE fornecedores SET responsavel_id = ?, atualizacao = NOW(), tipo = ? 
                            WHERE pessoa_id = ?");
            $stmt->bindValue(1, $_SESSION['id']);
            $stmt->bindValue(2, $tipo);
            $stmt->bindValue(3, $id);
            $stmt->execute();

            //RETORNAR O ID DO FORNECEDOR
            $fornecedor_id = $id;
        } else {
            $stmt = $db->prepare("INSERT INTO pessoas (nome, razao_social, cnpj, cpf, email, contato1, contato2, sexo, cep, bairro, endereco, cidade_id, nascimento) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $tipo == 1 ? $nome : $nome_fantasia);
            $stmt->bindValue(2, $tipo == 2 ? $razao : NULL);
            $stmt->bindValue(3, $tipo == 2 ? $cnpj : NULL);
            $stmt->bindValue(4, $cpf);
            $stmt->bindValue(5, $email);
            $stmt->bindValue(6, $contato);
            $stmt->bindValue(7, $contato2);
            $stmt->bindValue(8, $sexo);
            $stmt->bindValue(9, $cep);
            $stmt->bindValue(10, $bairro);
            $stmt->bindValue(11, $endereco);
            $stmt->bindValue(12, $cidade);
            $stmt->bindValue(13, convertDataBR2ISO($nascimento));
            $stmt->execute();

            //RETORNAR O ID DA PESSOA
            $pessoa_id = $db->lastInsertId();

            $stmt = $db->prepare("INSERT INTO fornecedores (cadastro, atualizacao, status, responsavel_id, pessoa_id, tipo) 
                            VALUES (NOW(), NOW(), 1, ?, ?, ?)");
            $stmt->bindValue(1, $_SESSION['id']);
            $stmt->bindValue(2, $pessoa_id);
            $stmt->bindValue(3, $tipo);
            $stmt->execute();

            //RETORNAR O ID DO FORNECEDOR
            $fornecedor_id = $db->lastInsertId();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $fornecedor_id;
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