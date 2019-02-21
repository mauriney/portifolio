
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['modelo'] != "" || $_GET['placa'] != "" || $_GET['cor'] != "") {

    $campo1 = "";
    $campo2 = "";
    $campo3 = "";

    $valor1 = "";
    $valor2 = "";
    $valor3 = "";

    $pesquisa1 = "";
    $pesquisa2 = "";
    $pesquisa3 = "";

    if ($_GET['modelo'] != "") {
        $campo1 = "AND modelo = ?";
        $valor1 = "" . utf8_decode($_GET['modelo']) . "";
        $pesquisa1 = "AND modelo = '" . $_GET['modelo'] . "'";
    }

    if ($_GET['placa'] != "") {
        $campo2 = "AND placa = ?";
        $valor2 = "" . utf8_decode($_GET['placa']) . "";
        $pesquisa2 = "AND placa = '" . $_GET['placa'] . "'";
    }

    if ($_GET['cor'] != "") {
        $campo3 = "AND cor = ?";
        $valor3 = "" . utf8_decode($_GET['cor']) . "";
        $pesquisa3 = "AND cor = '" . $_GET['cor'] . "'";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT id, modelo, placa, cor
				FROM x_veiculo 
				WHERE status = 1 $campo1 $campo2 $campo3
				ORDER BY modelo ASC");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }
    if ($valor2 != "") {
        $sql->bindValue($contador, $valor2);
        $contador++;
    }
    if ($valor3 != "") {
        $sql->bindValue($contador, $valor3);
        $contador++;
    }

    $sql->execute();

    $linhas = 1;
} else {
    $linhas = 0;
}

if ($linhas > 0) {

    $cont = 1;
    $status = "";

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($cont < 10) {
            $cont = "0" . $cont;
        }

        echo "<tr>"
        . "<td data-th='#'>$cont</td>"
        . "<td data-th='Modelo'>" . utf8_encode($linha['modelo']) . ""
        . "<td data-th='Placa'>" . utf8_encode($linha['placa']) . ""
        . "<td data-th='Cor'>" . utf8_encode($linha['cor']) . ""
        . "<td data-th='Link'><a href='veiculo-cadastro.php?id=" . $linha['id'] . "' title='Editar Veículo' class='visualizar'>Editar Veículo</a>"
        . "</tr>";
        $cont++;
    }

    $codigo = "SELECT id, modelo, placa, cor
				FROM x_veiculo 
				WHERE status = 1 $pesquisa1 $pesquisa2 $pesquisa3
				ORDER BY modelo ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['modelo'] == "" || $_GET['placa'] == "" || $_GET['cor'] == "") {

    $sql = $db->prepare("SELECT id, modelo, placa, cor
				FROM x_veiculo 
				WHERE status = 1
				ORDER BY modelo ASC");
    $sql->execute();

    $cont = 1;
    $status = "";

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($cont < 10) {
            $cont = "0" . $cont;
        }

        echo "<tr>"
        . "<td data-th='#'>$cont</td>"
        . "<td data-th='Modelo'>" . utf8_encode($linha['modelo']) . ""
        . "<td data-th='Placa'>" . utf8_encode($linha['placa']) . ""
        . "<td data-th='Cor'>" . utf8_encode($linha['cor']) . ""
        . "<td data-th='Link'><a href='veiculo-cadastro.php?id=" . $linha['id'] . "' title='Editar Veículo' class='visualizar'>Editar Veículo</a>"
        . "</tr>";
        $cont++;
    }

    $codigo = "SELECT id, modelo, placa, cor
				FROM x_veiculo 
				WHERE status = 1
				ORDER BY modelo ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
}
?>

