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
    $valor = $_POST['valor_frete'];
    $pontos = strip_tags($_POST['pontos_frete']);

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    $id_bairro = pesquisar_tabela("id", "bairros", "nome", "=", $nome, "");

    if (is_numeric($id_bairro) && $id_bairro != @$id) {
        $error = true;
        $mensagem .= "- O nome do bairro informado já existe no sistema.";
        $msg['tipo'] = "nome";
    }

    if ($error == false) {

        if (is_numeric($id)) {

            $stmt = $db->prepare("UPDATE bairros SET nome = ?, valor = ?, pontos = ?, atualizacao = NOW(), responsavel_id = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, valorfloat($valor));
            $stmt->bindValue(3, $pontos);
            $stmt->bindValue(4, $_SESSION['id']);
            $stmt->bindValue(5, $id);
            $stmt->execute();

            //RETORNAR O ID DA BAIRRO
            $bairro_id = $id;
        } else {
            $stmt = $db->prepare("INSERT INTO bairros (nome, valor, pontos, atualizacao, responsavel_id, cadastro, status) 
                            VALUES (?, ?, ?, NOW(), ?, NOW(), 1)");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, valorfloat($valor));
            $stmt->bindValue(3, $pontos);
            $stmt->bindValue(4, $_SESSION['id']);
            $stmt->execute();

            //RETORNAR O ID DA BAIRRO
            $bairro_id = $db->lastInsertId();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $bairro_id;
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