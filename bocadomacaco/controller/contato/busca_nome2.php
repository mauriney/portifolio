
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['letra'] != "") {

    $campo1 = "1";
    $pesquisa1 = "1";
    $valor1 = "";

    if ($_GET['letra'] != "") {
        $campo1 = "nome LIKE ?";
        $valor1 = "" . utf8_decode($_GET['letra']) . "%";
        $pesquisa1 = "nome LIKE '" . $_GET['letra'] . "%'";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT *
                     FROM tb_bsc_contato 
                     WHERE $campo1 ORDER BY nome ASC");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }

    $sql->execute();

    $linhas = $sql->rowCount();
} else {
    $linhas = 0;
}

if ($linhas > 0) {

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
        . "<td data-th='Link'><a href='#' title='Visuzalizar' class='visualizar'>Visualizar</a>"
        . "</tr>";
    }

    echo '</table>';

    $codigo = "SELECT * 
                     FROM tb_bsc_contato 
                     WHERE $pesquisa1 ORDER BY nome ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['letra'] == "") {

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
        . "<td data-th='Link'><a href='#' title='Visuzalizar' class='visualizar'>Visualizar</a>"
        . "</tr>";
    }

    echo '</table>';

    $codigo = "SELECT * FROM tb_bsc_contato ORDER BY nome ASC";
    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
}
?>

