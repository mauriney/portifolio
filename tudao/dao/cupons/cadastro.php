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
    $id = strip_tags($_POST['id']);
    $codigo = strip_tags($_POST['codigo']);
    $pontos = strip_tags($_POST['pontos']);
    $valor = strip_tags($_POST['valor']);

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    $id_cupom = pesquisar_tabela("id", "cupons", "codigo", "=", $codigo, "");

    if (is_numeric($id_cupom) && $id_cupom != @$id) {
        $error = true;
        $mensagem .= "- O código do cupom informado já existe no sistema.";
        $msg['tipo'] = "nome";
    }

    if ($error == false) {

        if (is_numeric($id)) {

            $stmt = $db->prepare("UPDATE cupons SET codigo = ?, valor = ?, pontos = ?, atualizacao = NOW(), responsavel_id = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, $codigo);
            $stmt->bindValue(2, valorfloat($valor));
            $stmt->bindValue(3, $pontos);
            $stmt->bindValue(4, $_SESSION['id']);
            $stmt->bindValue(5, $id);
            $stmt->execute();

            //RETORNAR O ID DA CATEGORIA
            $cupom_id = $id;
        } else {
            $stmt = $db->prepare("INSERT INTO cupons (codigo, valor, pontos, atualizacao, responsavel_id, cadastro, status) 
                            VALUES (?, ?, ?, NOW(), ?, NOW(), 1)");
            $stmt->bindValue(1, $codigo);
            $stmt->bindValue(2, valorfloat($valor));
            $stmt->bindValue(3, $pontos);
            $stmt->bindValue(4, $_SESSION['id']);
            $stmt->execute();

            //RETORNAR O ID DA CUPOM
            $cupom_id = $db->lastInsertId();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $cupom_id;
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