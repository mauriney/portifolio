
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');
require_once('../../utils/agenda/funcoes.php');

$db = Conexao::getInstance();


if (is_numeric($_GET['op']) && $_GET['op'] > 0) {

    $campo1 = "";

    if ($_GET['op'] == 1) {//HOJE
        $campo1 = "AND mes = MONTH(CURDATE()) AND dia = DAY(CURDATE())";
    } else if ($_GET['op'] == 2) {//DA SEMANA
        $campo1 = "AND WEEKOFYEAR(CONCAT(YEAR(NOW()),'-',mes,'-',dia )) = WEEKOFYEAR(NOW())";
    } else if ($_GET['op'] == 3) {//DO MÊS
        $campo1 = "AND mes = MONTH(CURDATE()) AND dia >= DAY(NOW())";
    } else if ($_GET['op'] == 4) {//DO ANO
        $campo1 = "AND ((mes = MONTH(NOW()) AND dia >= DAY(NOW())) OR mes > MONTH(NOW()) )";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT idcontato, nome, email, dia, mes
		FROM tb_bsc_contato
		WHERE 1 $campo1 ORDER BY nome");

    $sql->execute();

    $linhas = $sql->rowCount();

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['nome']) . ""
        . "<td data-th='Dia'>" . $linha['dia'] . ""
        . "<td data-th='Mes'>" . getMes($linha['mes']) . ""
        . "<td data-th='E-mail'>" . $linha['email'] . ""
        . "<td data-th='Link'><a href='contato-detalhe.php?id=" . $linha['idcontato'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
        . "</tr>";
    }

    $codigo = "SELECT idcontato, nome, email, dia, mes
		FROM tb_bsc_contato
		WHERE 1 $campo1 ORDER BY nome";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['op'] == 0) {

    $sql = $db->prepare("SELECT idcontato, nome, email, dia, mes 
		FROM tb_bsc_contato
		WHERE 1 ORDER BY nome");
    $sql->execute();

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['nome']) . ""
        . "<td data-th='Dia'>" . $linha['dia'] . ""
        . "<td data-th='Mes'>" . getMes($linha['mes']) . ""
        . "<td data-th='E-mail'>" . $linha['email'] . ""
        . "<td data-th='Link'><a href='contato-detalhe.php?id=" . $linha['idcontato'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a>"
        . "</tr>";
    }

    $codigo = "SELECT idcontato, nome, email, dia, mes 
		FROM tb_bsc_contato
		WHERE 1 ORDER BY nome";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
}
?>

