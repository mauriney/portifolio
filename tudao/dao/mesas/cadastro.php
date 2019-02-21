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
    $numero = strip_tags($_POST['numero']);

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    $id_mesa = pesquisar_tabela("id", "mesas", "numero", "=", $numero, "");

    if (is_numeric($id_mesa) && $id_mesa != @$id) {
        $error = true;
        $mensagem .= "- O número da mesa informada já existe no sistema.";
        $msg['tipo'] = "numero";
    }

    if ($error == false) {

        if (is_numeric($id)) {

            $stmt = $db->prepare("UPDATE mesas SET numero = ?, atualizacao = NOW(), responsavel_id = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, $numero);
            $stmt->bindValue(2, $_SESSION['id']);
            $stmt->bindValue(3, $id);
            $stmt->execute();

            //RETORNAR O ID DA MESA
            $mesa_id = $id;
        } else {
            $stmt = $db->prepare("INSERT INTO mesas (numero, atualizacao, responsavel_id, cadastro, status) 
                            VALUES (?, NOW(), ?, NOW(), 1)");
            $stmt->bindValue(1, $numero);
            $stmt->bindValue(2, $_SESSION['id']);
            $stmt->execute();

            //RETORNAR O ID DA MESA
            $mesa_id = $db->lastInsertId();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $mesa_id;
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