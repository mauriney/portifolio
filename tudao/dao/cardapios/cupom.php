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

    $cupom = $_POST['cupom'];

    $valor = pesquisar_tabela("valor", "cupons", "codigo", "=", $cupom, "");
    $pontos = pesquisar_tabela("pontos", "cupons", "codigo", "=", $cupom, "");

    if (is_numeric($valor) && !isset($_SESSION['cupom'])) {

        $_SESSION['cupom'] = $cupom;
        $_SESSION['cupom_valor'] = fdec($valor);
        $_SESSION['cupom_pontos'] = $pontos;

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['valor'] = fdec($valor);
        $msg['pontos'] = $pontos;
        echo json_encode($msg);
        exit();
    } else if (isset($_SESSION['cupom'])) {
        $msg['msg'] = 'error';
        $msg['retorno'] = 'Você já utilizou um cupom de desconto para essa compra.';
        echo json_encode($msg);
        exit();
    } else {
        $msg['msg'] = 'error';
        $msg['retorno'] = 'O cupom de desconto informado é inválido!';
        echo json_encode($msg);
        exit();
    }
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = 'O cupom de desconto informado é inválido!';
    echo json_encode($msg);
    exit();
}
?>