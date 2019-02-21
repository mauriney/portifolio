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
    $valor = $_POST['valor'];
    $ingredientes = isset($_POST['ingredientes']) ? $_POST['ingredientes'] : '';
    $categoria = $_POST['categoria'];
    $pontuacao = $_POST['pontuacao'];
    $pontuacao_recebida = $_POST['valor_pontuacao_recebida'];
    $pontuacao_cobrada = $_POST['valor_pontuacao'];
    $destaque = $_POST['destaque'];

    //VERIFICA SE O CPF DIGITADO É VÁLIDO
    $id_produto = pesquisar_tabela("id", "produtos", "nome", "=", $nome, "");

    if (is_numeric($id_produto) && $id_produto != @$id) {
        $error = true;
        $mensagem .= "- O nome do produto informado já existe no sistema.";
        $msg['tipo'] = "nome";
    }

    if ($error == false) {

        if (is_numeric($id)) {

            $stmt = $db->prepare("UPDATE produtos SET nome = ?, destaque = ?, pontuacao_cobrada = ?, pontuacao_recebida = ?, valor = ?, categoria_id = ?, atualizacao = NOW(), responsavel_id = ? 
                                  WHERE id = ?");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $destaque == 1 ? $destaque : 0);
            $stmt->bindValue(3, $pontuacao == 1 ? $pontuacao_cobrada : 0);
            $stmt->bindValue(4, $pontuacao == 1 ? $pontuacao_recebida : 0);
            $stmt->bindValue(5, valorfloat($valor));
            $stmt->bindValue(6, $categoria);
            $stmt->bindValue(7, $_SESSION['id']);
            $stmt->bindValue(8, $id);
            $stmt->execute();

            //RETORNAR O ID DO PRODUTO
            $produto_id = $id;
        } else {
            $stmt = $db->prepare("INSERT INTO produtos (nome, destaque, pontuacao_cobrada, pontuacao_recebida, valor, categoria_id, atualizacao, responsavel_id, cadastro, status) 
                            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), 1)");
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $destaque == 1 ? $destaque : 0);
            $stmt->bindValue(3, $pontuacao == 1 ? $pontuacao_cobrada : 0);
            $stmt->bindValue(4, $pontuacao == 1 ? $pontuacao_recebida : 0);
            $stmt->bindValue(5, valorfloat($valor));
            $stmt->bindValue(6, $categoria);
            $stmt->bindValue(7, $_SESSION['id']);
            $stmt->execute();

            //RETORNAR O ID DO PRODUTO
            $produto_id = $db->lastInsertId();
        }

        //DELETANDO AS REFERENCIAS DO PROJETO PARA INSERIR AS NOVAS
        $stmt5 = $db->prepare("DELETE FROM produtos_ingredientes WHERE produto_id = ?");
        $stmt5->bindValue(1, $produto_id);
        $stmt5->execute();
        //INSERINDO INGREDIENTES
        if (isset($ingredientes) && $ingredientes != NULL && $ingredientes != "") {
            //INSERINDO AS NOVAS REFERÊNCIAS
            foreach ($ingredientes as $values2) {
                $stmt3 = $db->prepare("INSERT INTO produtos_ingredientes (produto_id, ingrediente_id) VALUES (?, ?)");
                $stmt3->bindValue(1, $produto_id);
                $stmt3->bindValue(2, $values2);
                $stmt3->execute();
            }
        }

        //SALVANDO IMAGEM
        if (isset($_SESSION['foto_origin']) && $_SESSION['foto_origin'] != '' && isset($_SESSION['foto_cut']) && $_SESSION['foto_cut'] != '') {

            $foto_original = $_SESSION['foto_origin'];
            $foto_cortada = $_SESSION['foto_cut'];

            if (!file_exists($_SESSION['NOME_SISTEMA'] ."assets/img/produtos/" . "produto_" . $produto_id)) {
                @mkdir($_SESSION['NOME_SISTEMA'] ."assets/img/produtos/" . "produto_" . $produto_id, 0777);
            }

            $caminho = $_SESSION['NOME_SISTEMA'] . "assets/img/produtos/" . "produto_" . $produto_id . "/";

            //SE NÃO HOUVE ERROR
            if (file_exists($_SESSION['NOME_SISTEMA'] . $foto_original)) {
                copy($_SESSION['NOME_SISTEMA'] . $foto_original, $caminho . $_SESSION['foto_origin_nome']);
                unlink($_SESSION['NOME_SISTEMA'] . $foto_original);
            }

            if (file_exists($_SESSION['NOME_SISTEMA'] . $foto_cortada)) {
                copy($_SESSION['NOME_SISTEMA'] . $foto_cortada, $caminho . $_SESSION['foto_cut_nome']);
                unlink($_SESSION['NOME_SISTEMA'] . $foto_cortada);
            }

            if (file_exists($caminho . $_SESSION['foto_origin_nome'])) {
                chmod($caminho . $_SESSION['foto_origin_nome'], 0777);
            }
            if (file_exists($caminho . $_SESSION['foto_cut_nome'])) {
                chmod($caminho . $_SESSION['foto_cut_nome'], 0777);
            }

            //REMOVENDO FOTO ANTIGA
            $sql = $db->prepare("SELECT foto_cortada, foto_original FROM produtos WHERE id = ? AND foto_cortada <> '' AND foto_cortada IS NOT NULL");
            $sql->bindValue(1, $id);
            $sql->execute();
            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                if (file_exists($_SESSION['NOME_SISTEMA'] . $linha['foto_cortada'])) {
                    unlink($_SESSION['NOME_SISTEMA'] . $linha['foto_cortada']);
                }
                if (file_exists($_SESSION['NOME_SISTEMA'] . $linha['foto_original'])) {
                    unlink($_SESSION['NOME_SISTEMA'] . $linha['foto_original']);
                }
            }

            //ADD FOTO NOVA
            $stmt = $db->prepare("UPDATE produtos SET foto_cortada = ?, foto_original = ? WHERE id = ?");
            $stmt->bindValue(1, "assets/img/produtos/produto_" . $produto_id . "/" . $_SESSION['foto_cut_nome']);
            $stmt->bindValue(2, "assets/img/produtos/produto_" . $produto_id . "/" . $_SESSION['foto_origin_nome']);
            $stmt->bindValue(3, $produto_id);
            $stmt->execute();

            //LIMPANDO A SESSION DAS FOTOS
            $_SESSION['foto_origin'] = '';
            $_SESSION['foto_origin_nome'] = '';
            $_SESSION['foto_cut'] = '';
            $_SESSION['foto_cut_nome'] = '';
        }

        $db->commit();

        //MENSAGEM DE SUCESSO
        $msg['id'] = $produto_id;
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