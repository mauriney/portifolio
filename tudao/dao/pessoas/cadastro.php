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
    $codigo = $_POST['codigo'];
    $permissoes = isset($_POST['permissoes']) ? $_POST['permissoes'] : NULL;
    $cliente = isset($_POST['cliente']) ? $_POST['cliente'] : NULL;
    $fornecedor = isset($_POST['fornecedor']) ? $_POST['fornecedor'] : NULL;
    $funcionario = isset($_POST['funcionario']) ? $_POST['funcionario'] : NULL;
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : NULL;
    $senha = strip_tags($_POST['senha']);
    $status = strip_tags($_POST['status']);

    if ($error == false) {

        if ($codigo != 1) {
            //ATUALIZANDO PERMISSÕES DE ACESSO AO SISTEMA
            $stmt = $db->prepare("DELETE FROM permissoes WHERE user_id = ?");
            $stmt->bindValue(1, $codigo);
            $stmt->execute();

            if ($permissoes != NULL) {
                foreach ($permissoes as $values) {
                    $stmt3 = $db->prepare("INSERT INTO permissoes (user_id, nivel, sistema) VALUES (?, ?, 1)");
                    $stmt3->bindValue(1, $codigo);
                    $stmt3->bindValue(2, $values);
                    $stmt3->execute();
                }
            }
        }

        //ALTERANDO O STATUS DA PESSOA
        $stmt = $db->prepare("UPDATE pessoas SET status = ? WHERE id = ?");
        $stmt->bindValue(1, $status);
        $stmt->bindValue(2, $codigo);
        $stmt->execute();

        if (isset($senha) && $senha != "") {
            $stmt = $db->prepare("UPDATE pessoas SET senha = ? WHERE id = ?");
            $stmt->bindValue(1, sha1($senha));
            $stmt->bindValue(2, $codigo);
            $stmt->execute();
        }

        if (isset($cliente) && $cliente != NULL) {
            if (!is_numeric(pesquisar_tabela("id", "clientes", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("INSERT INTO clientes (pessoa_id, status, atualizacao, cadastro, responsavel_id) VALUES (?, 1, NOW(), NOW(), ?)");
                $stmt3->bindValue(1, $codigo);
                $stmt3->bindValue(2, $_SESSION['id']);
                $stmt3->execute();
            } else {
                $stmt3 = $db->prepare("UPDATE clientes SET status = 1 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        } else {
            if (is_numeric(pesquisar_tabela("id", "clientes", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("UPDATE clientes SET status = 0 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        }

        if (isset($fornecedor) && $fornecedor != NULL) {
            if (!is_numeric(pesquisar_tabela("id", "fornecedores", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("INSERT INTO fornecedores (pessoa_id, status, atualizacao, cadastro, responsavel_id) VALUES (?, 1, NOW(), NOW(), ?)");
                $stmt3->bindValue(1, $codigo);
                $stmt3->bindValue(2, $_SESSION['id']);
                $stmt3->execute();
            } else {
                $stmt3 = $db->prepare("UPDATE fornecedores SET status = 1 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        } else {
            if (is_numeric(pesquisar_tabela("id", "fornecedores", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("UPDATE fornecedores SET status = 0 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        }

        if (isset($funcionario) && $funcionario != NULL) {
            if (!is_numeric(pesquisar_tabela("id", "funcionarios", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("INSERT INTO funcionarios (pessoa_id, status, atualizacao, cadastro, responsavel_id) VALUES (?, 1, NOW(), NOW(), ?)");
                $stmt3->bindValue(1, $codigo);
                $stmt3->bindValue(2, $_SESSION['id']);
                $stmt3->execute();
            } else {
                $stmt3 = $db->prepare("UPDATE funcionarios SET status = 1 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        } else {
            if (is_numeric(pesquisar_tabela("id", "funcionarios", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("UPDATE funcionarios SET status = 0 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        }

        if (isset($usuario) && $usuario != NULL) {
            if (!is_numeric(pesquisar_tabela("id", "usuarios", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("INSERT INTO usuarios (pessoa_id, status, atualizacao, cadastro, responsavel_id) VALUES (?, 1, NOW(), NOW(), ?)");
                $stmt3->bindValue(1, $codigo);
                $stmt3->bindValue(2, $_SESSION['id']);
                $stmt3->execute();
            } else {
                $stmt3 = $db->prepare("UPDATE usuarios SET status = 1 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        } else {
            if (is_numeric(pesquisar_tabela("id", "usuarios", "pessoa_id", "=", $codigo, ""))) {
                $stmt3 = $db->prepare("UPDATE usuarios SET status = 0 WHERE pessoa_id = ?");
                $stmt3->bindValue(1, $codigo);
                $stmt3->execute();
            }
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $codigo;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Permissão atribuída com sucesso.';
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
    $msg['retorno'] = "Erro ao tentar realizar atribuir a permissão desejada:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>