
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['motorista'] != "" && $_GET['motorista'] != 0 ||
        $_GET['veiculo'] != "" && $_GET['veiculo'] != 0 ||
        $_GET['fim'] != "" && strlen($_GET['fim']) > 0 ||
        $_GET['inicio'] != "" && strlen($_GET['inicio']) > 0) {

    $campo1 = "";
    $campo2 = "";
    $campo3 = "";
    $campo4 = "";

    $valor1 = "";
    $valor2 = "";
    $valor3 = "";
    $valor4 = "";

    $pesquisa1 = "";
    $pesquisa2 = "";
    $pesquisa3 = "";
    $pesquisa4 = "";

    if ($_GET['fim'] == "" && $_GET['inicio'] != "" && strlen($_GET['inicio']) > 0) {
        $campo1 = "AND s.data_saida BETWEEN (?) AND ('2030-12-30')";
        $valor1 = "" . convertDataBR2ISO($_GET['inicio']) . "";
        $pesquisa5 = "AND s.data_saida BETWEEN ('" . convertDataBR2ISO($_GET['inicio']) . "') AND ('2030-12-30')";
    }

    if ($_GET['inicio'] == "" && $_GET['fim'] != "" && strlen($_GET['fim']) > 0) {
        $campo2 = "AND s.data_saida BETWEEN ('1900-01-01') AND (?)";
        $valor2 = "" . convertDataBR2ISO($_GET['fim']) . "";
        $pesquisa2 = "AND s.data_saida BETWEEN ('1900-01-01') AND ('" . convertDataBR2ISO($_GET['fim']) . "')";
    }

    if ($_GET['inicio'] != "" && strlen($_GET['inicio']) > 0 && $_GET['fim'] != "" && strlen($_GET['fim']) > 0) {
        $campo1 = "AND s.data_saida BETWEEN (?) AND (?)";
        $valor1 = "" . convertDataBR2ISO($_GET['inicio']) . "";
        $valor2 = "" . convertDataBR2ISO($_GET['fim']) . "";
        $pesquisa2 = "AND s.data_saida BETWEEN ('" . convertDataBR2ISO($_GET['inicio']) . "') AND ('" . convertDataBR2ISO($_GET['fim']) . "')";
    }

    if ($_GET['veiculo'] != "" && $_GET['veiculo'] != 0) {
        $campo3 = "AND v.id = ?";
        $valor3 = "" . $_GET['veiculo'] . "";
        $pesquisa3 = "AND v.id = " . $_GET['veiculo'] . "";
    }

    if ($_GET['motorista'] != "" && $_GET['motorista'] != 0) {
        $campo4 = "AND u.IdUsuario = ?";
        $valor4 = "" . $_GET['motorista'] . "";
        $pesquisa4 = "AND u.IdUsuario = " . $_GET['motorista'] . "";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT s.status AS status_chegada, s.id AS saida_id, s.data_chegada, s.hora_chegada, s.hora_prevista, s.data_prevista, s.data_saida, s.hora_saida, v.modelo, u.Nome AS motorista, v.placa 
				FROM x_veiculo_saida s
                                LEFT JOIN x_veiculo AS v ON v.id = s.veiculo_id 
                                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = s.motorista_id 
				WHERE 1 $campo1 $campo2 $campo3 $campo4 AND v.status = 1
				ORDER BY v.modelo ASC");

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
    if ($valor4 != "") {
        $sql->bindValue($contador, $valor4);
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
        . "<td data-th='Motorista'>" . utf8_encode($linha['motorista']) . ""
        . "<td data-th='Veiculo'>" . (utf8_encode($linha['placa']) . " - " . utf8_encode($linha['modelo'])) . ""
        . "<td data-th='Hora'>" . obterDataBRTimestamp($linha['data_saida']) . " ÀS " . $linha['hora_saida'] . ""
        . "<td data-th='Prevista'>" . obterDataBRTimestamp($linha['data_prevista']) . " ÀS " . $linha['hora_prevista'] . ""
        . "<td data-th='Chegada'>";

        if ($linha['status_chegada'] == 2) {
            echo obterDataBRTimestamp($linha['data_chegada']) . " ÀS " . $linha['hora_chegada'];
        } else {
            echo '<button onclick="confirmar_chegada(' . "'" . utf8_encode($linha['motorista']) . "'" . ',' . "'" . utf8_encode($linha['modelo']) . "'" . ',' . "'" . utf8_encode($linha['placa']) . "'" . ',' . $linha['saida_id'] . ')" type="button" name="chegada" id="chegada" class="btn-primary">Confirmar Chegada</button>';
        }
        echo '<td data-th="Link">';
        if ($linha['status_chegada'] == 1) {
            echo '<a href="carro-cadastro.php?id=' . $linha['saida_id'] . '" title="Editar Veículo" class="visualizar">Editar Veículo</a>';
        }
        echo '</td>';

        echo "</tr>";
        $cont++;
    }

    $codigo = "SELECT s.status AS status_chegada, s.id AS saida_id, s.data_chegada, s.hora_chegada, s.hora_prevista, s.data_prevista, s.data_saida, s.hora_saida, v.modelo, u.Nome AS motorista, v.placa 
				FROM x_veiculo_saida s
                                LEFT JOIN x_veiculo AS v ON v.id = s.veiculo_id 
                                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = s.motorista_id 
				WHERE 1 $pesquisa1 $pesquisa2 $pesquisa3 $pesquisa4 AND v.status = 1
				ORDER BY v.modelo ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['fim'] == "" && $_GET['inicio'] == "") {

    $sql = $db->prepare("SELECT s.status AS status_chegada, s.id AS saida_id, s.data_chegada, s.hora_chegada, s.hora_prevista, s.data_prevista, s.data_saida, s.hora_saida, v.modelo, u.Nome AS motorista, v.placa 
				FROM x_veiculo_saida s
                                LEFT JOIN x_veiculo AS v ON v.id = s.veiculo_id 
                                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = s.motorista_id 
				WHERE v.status = 1
				ORDER BY v.modelo ASC");
    $sql->execute();

    $cont = 1;
    $status = "";

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($cont < 10) {
            $cont = "0" . $cont;
        }

        echo "<tr>"
        . "<td data-th='#'>$cont</td>"
        . "<td data-th='Motorista'>" . utf8_encode($linha['motorista']) . ""
        . "<td data-th='Veiculo'>" . (utf8_encode($linha['placa']) . " - " . utf8_encode($linha['modelo'])) . ""
        . "<td data-th='Hora'>" . obterDataBRTimestamp($linha['data_saida']) . " ÀS " . $linha['hora_saida'] . ""
        . "<td data-th='Prevista'>" . obterDataBRTimestamp($linha['data_prevista']) . " ÀS " . $linha['hora_prevista'] . ""
        . "<td data-th='Chegada'>";

        if ($linha['status_chegada'] == 2) {
            echo obterDataBRTimestamp($linha['data_chegada']) . " ÀS " . $linha['hora_chegada'];
        } else {
            echo '<button onclick="confirmar_chegada(' . "'" . utf8_encode($linha['motorista']) . "'" . ',' . "'" . utf8_encode($linha['modelo']) . "'" . ',' . "'" . utf8_encode($linha['placa']) . "'" . ',' . $linha['saida_id'] . ')" type="button" name="chegada" id="chegada" class="btn-primary">Confirmar Chegada</button>';
        }
        echo '<td data-th="Link">';
        if ($linha['status_chegada'] == 1) {
            echo '<a href="carro-cadastro.php?id=' . $linha['saida_id'] . '" title="Editar Veículo" class="visualizar">Editar Veículo</a>';
        }
        echo '</td>';

        echo "</tr>";
        $cont++;
    }

    $codigo = "SELECT s.status AS status_chegada, s.id AS saida_id, s.data_chegada, s.hora_chegada, s.hora_prevista, s.data_prevista, s.data_saida, s.hora_saida, v.modelo, u.Nome AS motorista, v.placa 
				FROM x_veiculo_saida s
                                LEFT JOIN x_veiculo AS v ON v.id = s.veiculo_id 
                                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = s.motorista_id 
				WHERE v.status = 1
				ORDER BY v.modelo ASC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
}
?>

