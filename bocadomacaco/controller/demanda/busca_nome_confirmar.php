
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');
require_once('../../utils/demanda/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['demanda'] != "" && strlen($_GET['demanda']) > 0 ||
        $_GET['fim'] != "" && strlen($_GET['fim']) > 0 ||
        $_GET['inicio'] != "" && strlen($_GET['inicio']) > 0) {

    $campo1 = "1";
    $campo5 = "";
    $campo6 = "";
     
    $valor1 = "";
    $valor5 = "";
    $valor6 = "";

    $pesquisa1 = "1";
    $pesquisa5 = "";
    $pesquisa6 = "";

    if ($_GET['demanda'] != "" && strlen($_GET['demanda']) > 0) {
        $campo1 = "demanda LIKE ?";
        $valor1 = "%" . utf8_decode($_GET['demanda']) . "%";
        $pesquisa1 = "demanda LIKE '%" . $_GET['demanda'] . "%'";
    }

    if ($_GET['fim'] == "" && $_GET['inicio'] != "" && strlen($_GET['inicio']) > 0) {
        $campo5 = "AND prazo BETWEEN (?) AND ('2030-12-30')";
        $valor5 = "" . convertDataBR2ISO($_GET['inicio']) . "";
        $pesquisa5 = "AND prazo BETWEEN ('" . convertDataBR2ISO($_GET['inicio']) . "') AND ('2030-12-30')";
    }

    if ($_GET['inicio'] == "" && $_GET['fim'] != "" && strlen($_GET['fim']) > 0) {
        $campo6 = "AND prazo BETWEEN ('1900-01-01') AND (?)";
        $valor6 = "" . convertDataBR2ISO($_GET['fim']) . "";
        $pesquisa6 = "AND prazo BETWEEN ('1900-01-01') AND ('" . convertDataBR2ISO($_GET['fim']) . "')";
    }

    if ($_GET['inicio'] != "" && strlen($_GET['inicio']) > 0 && $_GET['fim'] != "" && strlen($_GET['fim']) > 0) {
        $campo5 = "AND prazo BETWEEN (?) AND (?)";
        $valor5 = "" . convertDataBR2ISO($_GET['inicio']) . "";
        $valor6 = "" . convertDataBR2ISO($_GET['fim']) . "";
        $pesquisa6 = "AND prazo BETWEEN ('" . convertDataBR2ISO($_GET['inicio']) . "') AND ('" . convertDataBR2ISO($_GET['fim']) . "')";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT *"
            . " FROM x_demanda"
            . " WHERE $campo1 $campo5 $campo6 AND confirmacao = 0 GROUP BY id");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
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

    $linhas = $sql->rowCount();
} else {
    $linhas = 0;
}

if ($linhas > 0) {

    $cont = 1;
    $status = "";

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || responsavel_demanda_id($linha['id'], $_SESSION['id'])) {

            $responsavel = responsavel_demanda($linha['id']);

            if ($cont < 10) {
                $cont = "0" . $cont;
            }

            echo "<tr>"
            . "<td data-th='#'>$cont</td>"
            . "<td data-th='Demanda'>" . utf8_encode($linha['demanda']) . ""
            . "<td data-th='Prazo'>" . obterDataBRTimestamp($linha['prazo']) . ""
            . "<td data-th='Link'><a href='demanda-confirmar.php?id=" . $linha['id'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
            . "</tr>";
            $cont++;
        }
    }

    $codigo = "SELECT *"
            . " FROM x_demanda"
            . " WHERE $pesquisa1 $pesquisa5 $pesquisa6 AND confirmacao = 0 GROUP BY id";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['demanda'] == "" && $_GET['fim'] == "" && $_GET['inicio'] == "" ||
        strlen($_GET['demanda']) == 0 &&  strlen($_GET['fim']) == 0 && strlen($_GET['inicio']) == 0) {

    $sql = $db->prepare("SELECT DATEDIFF(prazo, NOW()) as diasvencimento, id, demanda, prazo, status
				FROM x_demanda
				WHERE confirmacao = 0
				ORDER BY diasvencimento, status ASC");
    $sql->execute();

    $cont = 1;
    $status = "";

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || responsavel_demanda_id($linha['id'], $_SESSION['id'])) {

            $responsavel = responsavel_demanda($linha['id']);

            if ($cont < 10) {
                $cont = "0" . $cont;
            }

            echo "<tr>"
            . "<td data-th='#'>$cont</td>"
            . "<td data-th='Demanda'>" . utf8_encode($linha['demanda']) . ""
            . "<td data-th='Prazo'>" . obterDataBRTimestamp($linha['prazo']) . ""
            . "<td data-th='Link'><a href='demanda-confirmar.php?id=" . $linha['id'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
            . "</tr>";
            $cont++;
        }
    }

    $codigo = "SELECT DATEDIFF(prazo, NOW()) as diasvencimento, id, demanda, prazo, status
				FROM x_demanda
				WHERE confirmacao = 0
				ORDER BY diasvencimento, status ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
}
?>

