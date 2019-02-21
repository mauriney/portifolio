
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');
require_once('../../utils/demanda/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['demanda'] != "" && strlen($_GET['demanda']) > 0 ||
        $_GET['status'] != "" && strlen($_GET['status']) > 0 ||
        $_GET['situacao'] != "" && strlen($_GET['situacao']) > 0 ||
        $_GET['responsavel'] != "" && strlen($_GET['responsavel']) > 0 ||
        $_GET['fim'] != "" && strlen($_GET['fim']) > 0 ||
        $_GET['inicio'] != "" && strlen($_GET['inicio']) > 0) {

    $campo1 = "1";
    $campo2 = "";
    $campo3 = "";
    $campo4 = "";
    $campo5 = "";
    $campo6 = "";
    $valor1 = "";
    $valor2 = "";
    $valor3 = "";
    $valor4 = "";
    $valor5 = "";
    $valor6 = "";

    $pesquisa1 = "1";
    $pesquisa2 = "";
    $pesquisa3 = "";
    $pesquisa4 = "";
    $pesquisa5 = "";
    $pesquisa6 = "";

    if ($_GET['demanda'] != "" && strlen($_GET['demanda']) > 0) {
        $campo1 = "d.demanda LIKE ?";
        $valor1 = "%" . utf8_decode($_GET['demanda']) . "%";
        $pesquisa1 = "d.demanda LIKE '%" . $_GET['demanda'] . "%'";
    }

    if ($_GET['status'] != "" && strlen($_GET['status']) > 0 && $_GET['status'] != 9999) {
        $campo2 = "AND d.status = ?";
        $valor2 = "" . utf8_encode($_GET['status']) . "";
        $pesquisa2 = "AND d.status = " . $_GET['status'] . "";
    }

    if ($_GET['situacao'] != "" && strlen($_GET['situacao']) > 0 && $_GET['situacao'] != 9999) {

        if ($_GET['situacao'] == 'concluido') {
            $campo3 = "AND d.status = 3";
            $pesquisa3 = "AND d.status = 3";
        } else if ($_GET['situacao'] == 'nao-iniciado') {
            $campo3 = "AND d.status = 1";
            $pesquisa3 = "AND d.status = 1";
        } else if ($_GET['situacao'] == 'no-prazo') {
            $campo3 = "AND d.prazo >= DATE(NOW()) AND d.status <> 3";
            $pesquisa3 = "AND d.prazo >= DATE(NOW()) AND d.status <> 3";
        } else if ($_GET['situacao'] == 'atrasado') {
            $campo3 = "AND d.prazo < DATE(NOW()) AND d.status <> 3";
            $pesquisa3 = "AND d.prazo < DATE(NOW()) AND d.status <> 3";
        }
    }

    if ($_GET['responsavel'] != "" && strlen($_GET['responsavel']) > 0) {
        $campo4 = "AND s.responsavel_id = ?";
        $valor4 = "" . $_GET['responsavel'] . "";
        $pesquisa4 = "AND s.responsavel_id = " . $_GET['responsavel'] . "";
    }

    if ($_GET['fim'] == "" && $_GET['inicio'] != "" && strlen($_GET['inicio']) > 0) {
        $campo5 = "AND d.prazo BETWEEN (?) AND ('2030-12-30')";
        $valor5 = "" . convertDataBR2ISO($_GET['inicio']) . "";
        $pesquisa5 = "AND d.prazo BETWEEN ('" . convertDataBR2ISO($_GET['inicio']) . "') AND ('2030-12-30')";
    }

    if ($_GET['inicio'] == "" && $_GET['fim'] != "" && strlen($_GET['fim']) > 0) {
        $campo6 = "AND d.prazo BETWEEN ('1900-01-01') AND (?)";
        $valor6 = "" . convertDataBR2ISO($_GET['fim']) . "";
        $pesquisa6 = "AND d.prazo BETWEEN ('1900-01-01') AND ('" . convertDataBR2ISO($_GET['fim']) . "')";
    }

    if ($_GET['inicio'] != "" && strlen($_GET['inicio']) > 0 && $_GET['fim'] != "" && strlen($_GET['fim']) > 0) {
        $campo5 = "AND d.prazo BETWEEN (?) AND (?)";
        $valor5 = "" . convertDataBR2ISO($_GET['inicio']) . "";
        $valor6 = "" . convertDataBR2ISO($_GET['fim']) . "";
        $pesquisa6 = "AND d.prazo BETWEEN ('" . convertDataBR2ISO($_GET['inicio']) . "') AND ('" . convertDataBR2ISO($_GET['fim']) . "')";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT d.id, d.demanda, d.prazo, d.status, s.responsavel_id 
                 FROM x_demanda d, x_demanda_responsavel s 
                 WHERE $campo1 $campo2 $campo3 $campo4 $campo5 $campo6 AND d.confirmacao = 1 AND d.id = s.demanda_id GROUP BY d.id");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }
    if ($valor2 != "") {
        $sql->bindValue($contador, $valor2);
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
            . "<td data-th='Responsável'>" . $responsavel . ""
            . "<td data-th='Prazo'>" . obterDataBRTimestamp($linha['prazo']) . ""
            . "<td data-th='Status'><span class='status'>" . status_demanda($linha['status']) . "</span>"
            . "<td data-th='Situação'><span class='situacao " . situacao_demanda2($linha['prazo'], $linha['status']) . "'>" . situacao_demanda($linha['prazo'], $linha['status']) . "</span>"
            . "<td data-th='Link'><a href='demanda-detalhe.php?id=" . $linha['id'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
            . "</tr>";
            $cont++;
        }
    }

    $codigo = "SELECT d.id, d.demanda, d.prazo, d.status, s.responsavel_id 
             FROM x_demanda d, x_demanda_responsavel s 
             WHERE $pesquisa1 $pesquisa2 $pesquisa3 $pesquisa4 $pesquisa5 $pesquisa6 AND d.confirmacao = 1 AND d.id = s.demanda_id GROUP BY d.id";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['demanda'] == "" && $_GET['status'] == "" && $_GET['situacao'] == "" && $_GET['responsavel'] == "" && $_GET['fim'] == "" && $_GET['inicio'] == "" ||
        strlen($_GET['demanda']) == 0 && strlen($_GET['status']) == 0 && strlen($_GET['situacao']) == 0 && strlen($_GET['responsavel']) == 0 && strlen($_GET['fim']) == 0 && strlen($_GET['inicio']) == 0) {

    $sql = $db->prepare("SELECT DATEDIFF(prazo, NOW()) as diasvencimento, id, demanda, prazo, status
				FROM x_demanda
				WHERE status = 0 AND confirmacao = 1 OR status = 2 AND confirmacao = 1 OR status = 3 AND confirmacao = 1 AND DATEDIFF(prazo, NOW()) >= 0
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
            . "<td data-th='Responsável'>" . $responsavel . ""
            . "<td data-th='Prazo'>" . obterDataBRTimestamp($linha['prazo']) . ""
            . "<td data-th='Status'><span class='status'>" . status_demanda($linha['status']) . "</span>"
            . "<td data-th='Situação'><span class='situacao " . situacao_demanda2($linha['prazo'], $linha['status']) . "'>" . situacao_demanda($linha['prazo'], $linha['status']) . "</span>"
            . "<td data-th='Link'><a href='demanda-detalhe.php?id=" . $linha['id'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
            . "</tr>";
            $cont++;
        }
    }

    $codigo = "SELECT DATEDIFF(prazo, NOW()) as diasvencimento, id, demanda, prazo, status
				FROM x_demanda
				WHERE status = 0 AND confirmacao = 1 OR status = 2 AND confirmacao = 1 OR status = 3 AND confirmacao = 1 AND DATEDIFF(prazo, NOW()) >= 0
				ORDER BY diasvencimento, status ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} 
?>

