<?php

@session_start();

$error = false;
$msg = "";

try {

    unset($_SESSION['cupom']);
    unset($_SESSION['cupom_valor']);
    unset($_SESSION['cupom_pontos']);

    //MENSAGEM DE SUCESSO
    $msg['msg'] = 'success';
    echo json_encode($msg);
    exit();
    
} catch (PDOException $e) {
    $msg['msg'] = 'error';
    $msg['retorno'] = 'Erro ao tentar remover o cupom de desconto. Por favor contate o administrador.';
    echo json_encode($msg);
    exit();
}
?>