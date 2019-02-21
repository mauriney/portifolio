
<?php

@session_start();

require_once("../../../conf/config.php");
require_once('../../../utils/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['letra'] != "") {

    $campo1 = "1";
    $valor1 = "";

    if ($_GET['letra'] != "") {
        $campo1 = "b.nome LIKE ?";
        $valor1 = "" . utf8_decode($_GET['letra']) . "%";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT b.nome, b.idbairro, r.nome as regional"
            . " FROM tb_bsc_bairro b, tb_bsc_regional r"
            . " WHERE $campo1 AND b.regional = r.idregional"
            . " GROUP BY b.idbairro ORDER BY b.nome ASC");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }

    $sql->execute();

    $linhas = $sql->rowCount();

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['nome']) . ""
        . "<td data-th='Regional'>" . utf8_encode($linha['regional']) . ""
        . "<td data-th=''><a href='bairro-cadastro.php?id=" . $linha['idbairro'] . "' title='Editar' class='editar-lista'>Editar</a>"
        . "</tr>";
    }
} else if ($_GET['letra'] == "") {

    $sql = $db->prepare("SELECT b.nome, b.idbairro, r.nome as regional"
            . " FROM tb_bsc_bairro b, tb_bsc_regional r"
            . " WHERE b.regional = r.idregional"
            . " GROUP BY b.idbairro ORDER BY b.nome ASC");
    $sql->execute();

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['nome']) . ""
        . "<td data-th='Regional'>" . utf8_encode($linha['regional']) . ""
        . "<td data-th=''><a href='bairro-cadastro.php?id=" . $linha['idbairro'] . "' title='Editar' class='editar-lista'>Editar</a>"
        . "</tr>";
    }
}
?>

