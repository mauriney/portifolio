<?php

@session_start();

include_once('conf/config.php');
include_once('utils/funcoes.php');

$db = Conexao::getInstance();

// VERIFICAÇÕES DE SESSÕES
if (isset($_SESSION['usuario'])) {
    echo "<script>window.location = '" . PORTAL_URL . "index.php';</script>";
    exit();
}
//VARIAVEL UNIVERSAL
$msg = '';
$success = 0;
if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    $email = $_POST['email'];
    $idusuario = $_POST['idusuario'];
    $senha = $_POST['senha'];
    //PEGAR OS DADOS DE REDEFINICÃO DA SENHA DE ACORDO O CODIGO INFORMADO
    $sql = $db->prepare("SELECT ( expira < NOW() ) AS menor FROM x_recuperarsenha WHERE email = ? AND codigo = ? AND alterada IS NULL");
    $sql->bindValue(1, $email);
    $sql->bindValue(2, $codigo);
    $sql->execute();

    $dadosDefinicao = $sql->fetch(PDO::FETCH_ASSOC);
    //PEGAR O TOTAL DE DADOS RETORNADOS
    $totalresultado = $sql->rowCount();

    if ($dadosDefinicao['menor'] == 1) {
        $msg['msg'] = 'error';
        $msg['retorno'] = "Seu código expirou. Faça uma nova solicitação, <a href='esqueci-minha-senha.php'>aqui</a>";
        echo json_encode($msg);
        exit();
    } else {

        $senha = md5($_POST['senha']);

        $stmt = $db->prepare("UPDATE tb_bsc_usuario SET senha = ? WHERE IdUsuario = ?");
        $stmt->bindValue(1, $senha);
        $stmt->bindValue(2, $idusuario);
        $stmt->execute();
        $atualiza = $db->prepare("UPDATE x_recuperarsenha SET alterada = NOW() WHERE email = ? AND codigo = ?");
        $atualiza->bindValue(1, $email);
        $atualiza->bindValue(2, $codigo);
        $atualiza->execute();

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Senha alterada com sucesso.';
        echo json_encode($msg);
        exit();
    }//END IF
}//END IF 
?>  