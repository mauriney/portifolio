
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

    $numero_sessao = session_id();
    $forma_pagamento = $_POST['forma_pagamento'];

    $bairro_id = $_POST['bairro_id'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $contato = $_POST['contato'];
    $numero_mesa = $_POST['numero_mesa'];

    $valor_pagamento = $_POST['valor_pagar'];
    $valor_troco = $_POST['valor_troco'];

    $vlr_total_dinheiro = $_POST['vlr_total_dinheiro'];
    $vlr_total_pontos = $_POST['vlr_total_pontos'];

    $cupom = $_POST['cupom'];

    $pontos_disponiveis = pesquisar_tabela("pontos", "clientes", "id", "=", $_SESSION['cliente'], "");

    if ($forma_pagamento == 3 && $pontos_disponiveis < $vlr_total_pontos) {
        $mensagem = "A sua quantidade de pontos disponíveis é inferior a quantidade total de pontos solicitada para finalizar essa compra.";
        $error = true;
    }

    if ($error == false) {

        if ($forma_pagamento == 3) {
            $pontos_ganhos = carregar_pontos_ganhos($numero_sessao);
        }

        $stmt = $db->prepare("UPDATE pedidos 
                              SET bairro_id = ?, endereco = ?, numero = ?, complemento = ?, contato = ?, mesa = ?, valor_pagamento = ?, valor_troco = ?, status = 1, forma_pagamento = ?, cliente_id = ?, vlr_total_dinheiro = ?, vlr_total_pontos = ?  
                              WHERE numero_sessao = ? AND status = 0");
        $stmt->bindValue(1, $numero_mesa == "" ? $bairro_id : NULL);
        $stmt->bindValue(2, $numero_mesa == "" ? $endereco : NULL);
        $stmt->bindValue(3, $numero_mesa == "" ? $numero : NULL);
        $stmt->bindValue(4, $numero_mesa == "" ? $complemento : NULL);
        $stmt->bindValue(5, $numero_mesa == "" ? $contato : NULL);
        $stmt->bindValue(6, $numero_mesa == "" ? NULL : $numero_mesa);
        $stmt->bindValue(7, valorfloat($valor_pagamento));
        $stmt->bindValue(8, valorfloat($valor_troco));
        $stmt->bindValue(9, $forma_pagamento);
        $stmt->bindValue(10, $_SESSION['cliente']);
        $stmt->bindValue(11, valorfloat($vlr_total_dinheiro));
        $stmt->bindValue(12, $vlr_total_pontos);
        $stmt->bindValue(13, $numero_sessao);
        $stmt->execute();

        if ($cupom != "") {
            $cupom_id = pesquisar_tabela("id", "cupons", "codigo", "=", $cupom, "");

            $stmt = $db->prepare("UPDATE pedidos 
                                  SET cupom_id = ?  
                                  WHERE numero_sessao = ? AND status = 0");
            $stmt->bindValue(1, $cupom_id);
            $stmt->bindValue(2, $numero_sessao);
            $stmt->execute();

            $stmt = $db->prepare("UPDATE cupons 
                                  SET status = 0  
                                  WHERE id = ?");
            $stmt->bindValue(1, $cupom_id);
            $stmt->execute();
        }

        if ($forma_pagamento == 3) {

            $stmt = $db->prepare("UPDATE clientes 
                                  SET pontos = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, ($pontos_disponiveis - $vlr_total_pontos) + $pontos_ganhos);
            $stmt->bindValue(2, $_SESSION['cliente']);
            $stmt->execute();
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $numero_sessao;
        $msg['msg'] = 'success';
        $msg['retorno'] = 'Pedido enviado com sucesso.';

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
    $msg['retorno'] = "Erro ao tentar realizar o envio do pedido:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>