
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();

$contato_id = $_POST['id'];

$nome = strip_tags(@$_POST['nome']);
$dia = strip_tags(@$_POST['dia']);
$mes = strip_tags(@$_POST['mes']);
$instituicao = strip_tags(@$_POST['instituicao']);
$departamento = strip_tags(@$_POST['departamento']);
$cargo = strip_tags(@$_POST['cargo']);
$email = strip_tags(@$_POST['email']);
$celular_principal = strip_tags(@$_POST['celular_principal']);
$cep = strip_tags(@$_POST['cep']);
$endereco = strip_tags(@$_POST['endereco']);
$numero = strip_tags(@$_POST['numero']);
$complemento = strip_tags(@$_POST['complemento']);
$bairro = strip_tags(@$_POST['bairro']);
$municipio = strip_tags(@$_POST['municipio']);
$tipo = strip_tags(@$_POST['tipo']);
$observacoes = strip_tags(@$_POST['observacoes']);

$grupo = @$_POST['grupo'];
$telefone = @$_POST['telefone'];
$telefone_tipo = @$_POST['telefone_tipo'];

$referencia = @$_POST['referencia'];

$error = false;

$mensagem = "";

try {

    //VERIFICA SE O O CELULAR PRINCIPAL DA DEMANDA JÁ FOI INFORMADO
    $id_contato2 = pesquisar("idcontato", "tb_bsc_contato", "celular_principal", "=", $celular_principal, "");

    if ($celular_principal != "" && $id_contato2 != @$_POST['id'] && $id_contato2 != "") {
        $error = true;
        $mensagem .= "<span>O celular principal informado já existe no sistema.</span>";
        $msg['tipo'] = "celular";
    }

    //VERIFICA SE O NOME DA DEMANDA JÁ FOI INFORMADO
    $id_contato = pesquisar("idcontato", "tb_bsc_contato", "nome", "=", $nome, "");

    if (is_numeric($id_contato) && $id_contato != @$_POST['id']) {
        $error = true;
        $mensagem .= "<span>O nome informado já existe no sistema.</span>";
        $msg['tipo'] = "nome";
    }

    if ($error == false) {
        $db->beginTransaction();

        if (isset($_POST['id']) && $_POST['id'] != '') {

            $stmt = $db->prepare("UPDATE tb_bsc_contato SET nome = ?, dia = ?, instituicao = ?, departamento = ?, cargo = ?, email = ?, celular_principal = ?, obs = ?, referencia = ?, mes = ?, data_cadastro = NOW()  WHERE idcontato = ?");

            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->bindValue(2, $dia);
            $stmt->bindValue(3, utf8_decode($instituicao));
            $stmt->bindValue(4, utf8_decode($departamento));
            $stmt->bindValue(5, utf8_decode($cargo));
            $stmt->bindValue(6, $email);
            $stmt->bindValue(7, $celular_principal);
            $stmt->bindValue(8, utf8_decode($observacoes));
            $stmt->bindValue(9, $referencia);
            $stmt->bindValue(10, $mes);
            $stmt->bindValue(11, $contato_id);

            $stmt->execute();

            if (is_numeric($contato_id)) {
                $stmt2 = $db->prepare("UPDATE tb_bsc_endereco SET idcidade = ?, tipo = ?, cep = ?, endereco = ?, numero = ?, complemento = ?, bairro = ? WHERE idcontato = ?");

                $stmt2->bindValue(1, $municipio);
                $stmt2->bindValue(2, $tipo);
                $stmt2->bindValue(3, $cep);
                $stmt2->bindValue(4, $endereco);
                $stmt2->bindValue(5, $numero);
                $stmt2->bindValue(6, $complemento);
                $stmt2->bindValue(7, $bairro);
                $stmt2->bindValue(8, $contato_id);
                $stmt->bindValue(9, $_POST['id']);

                $stmt2->execute();
            }

            //ADICIONANDO OS CHECKBOX
            if (is_numeric($contato_id) && sizeof($grupo) > 0) {

                $stmt3 = $db->prepare("DELETE FROM tb_bsc_segmento_grupo WHERE idcontato = ?");
                $stmt3->bindValue(1, $contato_id);
                $stmt3->execute();

                foreach ($grupo as $value) {
                    $stmt4 = $db->prepare("INSERT INTO tb_bsc_segmento_grupo (idcontato, idsegmento) VALUES (?, ?)");
                    $stmt4->bindValue(1, $contato_id);
                    $stmt4->bindValue(2, $value);
                    $stmt4->execute();
                }
            }

            if (is_numeric($contato_id) && sizeof($telefone) > 0 && sizeof($telefone_tipo) > 0) {

                $stmt44 = $db->prepare("DELETE FROM tb_bsc_telefone WHERE idcontato = ?");
                $stmt44->bindValue(1, $contato_id);
                $stmt44->execute();

                foreach ($telefone as $key => $value) {
                    $stmt4 = $db->prepare("INSERT INTO tb_bsc_telefone (idcontato, telefone, tipo) VALUES (?, ?, ?)");
                    $stmt4->bindValue(1, $contato_id);
                    $stmt4->bindValue(2, $value);
                    $stmt4->bindValue(3, $telefone_tipo[$key]);
                    $stmt4->execute();
                }
            }

            $db->commit();

            $contato_id = $db->lastInsertId();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $contato_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Contato atualizada com sucesso.';
            echo json_encode($msg);
            exit();
        } else {
            $stmt = $db->prepare("INSERT INTO tb_bsc_contato (nome, dia, instituicao, departamento, cargo, email, celular_principal, obs, referencia, mes, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

            $stmt->bindValue(1, utf8_decode($nome));
            $stmt->bindValue(2, $dia);
            $stmt->bindValue(3, utf8_decode($instituicao));
            $stmt->bindValue(4, utf8_decode($departamento));
            $stmt->bindValue(5, utf8_decode($cargo));
            $stmt->bindValue(6, $email);
            $stmt->bindValue(7, $celular_principal);
            $stmt->bindValue(8, utf8_decode($observacoes));
            $stmt->bindValue(9, $referencia);
            $stmt->bindValue(10, $mes);

            $stmt->execute();
            $contato_id = $db->lastInsertId();

            if (is_numeric($contato_id)) {
                $stmt2 = $db->prepare("INSERT INTO tb_bsc_endereco (idcidade, tipo, cep, endereco, numero, complemento, bairro, idcontato) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt2->bindValue(1, $municipio);
                $stmt2->bindValue(2, $tipo);
                $stmt2->bindValue(3, $cep);
                $stmt2->bindValue(4, $endereco);
                $stmt2->bindValue(5, $numero);
                $stmt2->bindValue(6, $complemento);
                $stmt2->bindValue(7, $bairro);
                $stmt2->bindValue(8, $contato_id);

                $stmt2->execute();
            }

            //ADICIONANDO OS CHECKBOX
            if (is_numeric($contato_id) && sizeof($grupo) > 0) {
                foreach ($grupo as $value) {
                    $stmt3 = $db->prepare("INSERT INTO tb_bsc_segmento_grupo (idcontato, idsegmento) VALUES (?, ?)");
                    $stmt3->bindValue(1, $contato_id);
                    $stmt3->bindValue(2, $value);
                    $stmt3->execute();
                }
            }

            if (is_numeric($contato_id) && sizeof($telefone) > 0 && sizeof($telefone_tipo) > 0) {
                foreach ($telefone as $key => $value) {
                    $stmt4 = $db->prepare("INSERT INTO tb_bsc_telefone (idcontato, telefone, tipo) VALUES (?, ?, ?)");
                    $stmt4->bindValue(1, $contato_id);
                    $stmt4->bindValue(2, $value);
                    $stmt4->bindValue(3, $telefone_tipo[$key]);
                    $stmt4->execute();
                }
            }

            $db->commit();

            //MENSAGEM DE SUCESSO
            @$_SESSION['mensagem'] = "OK";
            $msg['id'] = $contato_id;
            $msg['msg'] = 'success';
            $msg['retorno'] = 'Contato cadastrado com sucesso.';
            echo json_encode($msg);
            exit();
        }
    } else {
        $msg['msg'] = 'error';
        $msg['retorno'] = $mensagem;
        echo json_encode($msg);
        exit();
    }
} catch (PDOException $e) {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = "Erro ao tentar salvar os dados do contato:" . $e->getMessage();
    echo json_encode($msg);
    exit();
}
?>

