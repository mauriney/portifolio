
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['buscar'] != "" && $_GET['op'] == 100 ||
        $_GET['grupos'] != "" && $_GET['op'] == 100 ||
        $_GET['referencia'] != "" && $_GET['op'] == 100 ||
        $_GET['estado'] != "" && $_GET['op'] == 100 ||
        $_GET['municipio'] != "" && $_GET['op'] == 100 ||
        $_GET['bairro'] != "" && $_GET['op'] == 100 ||
        is_numeric($_GET['op']) && $_GET['op'] > 0 && $_GET['op'] < 100) {

    $campo1 = "1";
    $pesquisa1 = "1";
    $campo2 = "";
    $pesquisa2 = "";
    $campo3 = "";
    $pesquisa3 = "";
    $pesquisa4 = "";
    $campo4 = "";
    $pesquisa5 = "";
    $campo5 = "";
    $pesquisa6 = "";
    $campo6 = "";
    $valor1 = "";
    $valor2 = "";
    $valor3 = "";
    $valor4 = "";
    $valor5 = "";
    $valor6 = "";

    if ($_GET['buscar'] != "" && $_GET['op'] == 100) {
        $campo1 = "nome LIKE ?";
        $valor1 = "%" . utf8_decode($_GET['buscar']) . "%";
        $pesquisa1 = "nome LIKE '%" . $_GET['buscar'] . "%'";
    }

    if ($_GET['grupos'] != "" && $_GET['op'] == 100) {
        $campo2 = "AND idcontato IN (select idcontato FROM tb_bsc_segmento_grupo WHERE idsegmento IN (" . $_GET['grupos'] . "))";
        $pesquisa2 = "AND idcontato IN (select idcontato FROM tb_bsc_segmento_grupo WHERE idsegmento IN (" . $_GET['grupos'] . "))";
    }

    if ($_GET['referencia'] != "" && $_GET['op'] == 100) {
        $campo3 = "AND referencia = ?";
        $valor3 = "" . $_GET['referencia'] . "";
        $pesquisa3 = "AND referencia = " . $_GET['referencia'] . "";
    }

    if ($_GET['estado'] != "" && $_GET['op'] == 100) {
        $campo4 = "AND idcontato IN (SELECT idcontato FROM tb_bsc_endereco WHERE idcidade IN (SELECT idcidade FROM cidade WHERE idestado = ?))";
        $valor4 = "" . $_GET['estado'] . "";
        $pesquisa4 = "AND idcontato IN (SELECT idcontato FROM tb_bsc_endereco WHERE idcidade IN (SELECT idcidade FROM cidade WHERE idestado = " . $_GET['estado'] . "))";
    }

    if ($_GET['municipio'] != "" && $_GET['op'] == 100) {
        $campo5 = "AND idcontato IN (SELECT idcontato FROM tb_bsc_endereco WHERE idcidade = ?)";
        $valor5 = "" . $_GET['municipio'] . "";
        $pesquisa5 = "AND idcontato IN (SELECT idcontato FROM tb_bsc_endereco WHERE idcidade = " . $_GET['municipio'] . ")";
    }

    if ($_GET['bairro'] != "" && $_GET['op'] == 100) {
        $campo6 = "AND idcontato IN (SELECT idcontato FROM tb_bsc_endereco WHERE bairro = ?)";
        $valor6 = "" . utf8_decode($_GET['bairro']) . "";
        $pesquisa6 = "AND idcontato IN (SELECT idcontato FROM tb_bsc_endereco WHERE bairro = '" . $_GET['bairro'] . "')";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT *
                     FROM tb_bsc_contato 
                     WHERE $campo1 $campo2 $campo3 $campo4 $campo5 $campo6 ORDER BY nome ASC");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }

    if ($valor3 != "") {
        $sql->bindValue($contador, $valor3);
        $contador++;
    }

    if ($valor4 != "") {
        $sql->bindValue($contador, $valor4);
        $contador++;
    }

    if ($valor5 != "") {
        $sql->bindValue($contador, $valor5);
        $contador++;
    }

    if ($valor6 != "") {
        $sql->bindValue($contador, $valor6);
        $contador++;
    }

    $sql->execute();

    $qtd = $sql->rowCount();

    echo '<b>Total de Contatos:</b> ' . $qtd . '
            <table class="tabela tb-contato">
                <thead>
                    <tr>
                        <th>Nome<th>Contato<th>E-mail<th>Segmento(s)<th>
                <tbody>';

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        $grupos = "";

        if ($linha['celular_principal'] == "") {
            $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
            $sql_tel->bindValue(1, $linha['idcontato']);
            $sql_tel->execute();
            $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);
            $telefone = $dados_tel['telefone'];
        } else {
            $telefone = $linha['celular_principal'];
        }

        $sql_grupo = $db->prepare("SELECT se.Descricao FROM tb_bsc_segmento_grupo sg 
        LEFT JOIN tb_bsc_segmento se ON se.IdSegmento = sg.idsegmento
        WHERE idcontato = ?");

        $sql_grupo->bindValue(1, $linha['idcontato']);
        $sql_grupo->execute();
        $cont_grupos = 0;

        while ($f_grupo = $sql_grupo->fetch(PDO::FETCH_ASSOC)) {

            if ($cont_grupos == 0) {
                $grupos .= utf8_encode($f_grupo['Descricao']) . '';
            }

            if ($cont_grupos == 1) {
                $grupos .= "&nbsp;<a href='#' title='Segmento(s)' data-toggle='modal' data-target='#modal-contato-" . $linha['idcontato'] . "'><i class='fa fa-plus-circle'></i></a>";
            }

            $cont_grupos++;
        }

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['nome']) . ""
        . "<td data-th='Telefone'><span class='container-lista'>" . $telefone . "</span>"
        . "<td data-th='E-mail'>" . utf8_encode($linha['email']) . ""
        . "<td data-th='Grupo'><span class='container-lista'>" . $grupos . "</span>"
        . "<td data-th='Link'><a href='contato-detalhe.php?id=" . $linha['idcontato'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
        . "</tr>";
    }

    echo '</table>';

    $codigo = "SELECT *
                     FROM tb_bsc_contato 
                     WHERE $pesquisa1 $pesquisa2 $pesquisa3 $pesquisa4 $pesquisa5 $pesquisa6 ORDER BY nome ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['buscar'] == "" && $_GET['op'] == 100 || $_GET['op'] == 0 || $_GET['grupos'] == "" && $_GET['op'] == 100 || $_GET['referencia'] == "" && $_GET['op'] == 100) {

    $sql = $db->prepare("SELECT * FROM tb_bsc_contato ORDER BY nome ASC");
    $sql->execute();

    $qtd = $sql->rowCount();

    echo '<b>Total de Contatos:</b> ' . $qtd . '
            <table class="tabela tb-contato">
                <thead>
                    <tr>
                        <th>Nome<th>Contato<th>E-mail<th>Segmento(s)<th>
                <tbody>';

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        $grupos = "";

        if ($linha['celular_principal'] == "") {
            $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
            $sql_tel->bindValue(1, $linha['idcontato']);
            $sql_tel->execute();
            $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);
            $telefone = $dados_tel['telefone'];
        } else {
            $telefone = $linha['celular_principal'];
        }

        $sql_grupo = $db->prepare("SELECT se.Descricao FROM tb_bsc_segmento_grupo sg 
        LEFT JOIN tb_bsc_segmento se ON se.IdSegmento = sg.idsegmento
        WHERE idcontato = ?");

        $sql_grupo->bindValue(1, $linha['idcontato']);
        $sql_grupo->execute();
        $cont_grupos = 0;

        while ($f_grupo = $sql_grupo->fetch(PDO::FETCH_ASSOC)) {

            if ($cont_grupos == 0) {
                $grupos .= utf8_encode($f_grupo['Descricao']) . '';
            }

            if ($cont_grupos == 1) {
                $grupos .= "&nbsp;<a href='#' title='Segmento(s)' data-toggle='modal' data-target='#modal-contato-" . $linha['idcontato'] . "'><i class='fa fa-plus-circle'></i></a>";
            }

            $cont_grupos++;
        }

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['nome']) . ""
        . "<td data-th='Telefone'><span class='container-lista'>" . $telefone . "</span>"
        . "<td data-th='E-mail'>" . utf8_encode($linha['email']) . ""
        . "<td data-th='Grupo'><span class='container-lista'>" . $grupos . "</span>"
        . "<td data-th='Link'><a href='contato-detalhe.php?id=" . $linha['idcontato'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
        . "</tr>";
    }

    echo '</table>';

    $codigo = "SELECT * FROM tb_bsc_contato ORDER BY nome ASC";
    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
}
?>

