
<?php

@session_start();

require_once("../../../conf/config.php");
require_once('../../../utils/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['buscar'] != "") {

    $campo1 = "1";
    $valor1 = "";

    if ($_GET['buscar'] != "") {
        $campo1 = "Nome LIKE ?";
        $valor1 = "%" . utf8_decode($_GET['buscar']) . "%";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT * FROM tb_bsc_usuario 
                     WHERE $campo1 ");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }

    $sql->execute();

    $linhas = $sql->rowCount();
    $cont = 1;

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['Nome']) . ""
        . "<td data-th='Login'>" . $linha['login'] . ""
        . "<td data-th='Situação'>" . status($linha['Status']) . ""
        . "<td data-th='Nível'>" . nivel($linha['IdAcesso']) . ""
        . "<td data-th=''>"
        . "<a href='usuario-cadastro.php?id=" . $linha['IdUsuario'] . "' title='Editar' class='editar-lista'>Editar</a>"
        . "</tr>";
        $cont++;
    }
} else if ($_GET['buscar'] == "") {

    $sql = $db->prepare("SELECT * FROM tb_bsc_usuario");
    $sql->execute();
    $cont = 1;

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        echo "<tr>"
        . "<td data-th='Nome'>" . utf8_encode($linha['Nome']) . ""
        . "<td data-th='Login'>" . $linha['login'] . ""
        . "<td data-th='Situação'>" . status($linha['Status']) . ""
        . "<td data-th='Nível'>" . nivel($linha['IdAcesso']) . ""
        . "<td data-th=''>"
        . "<a href='usuario-cadastro.php?id=" . $linha['IdUsuario'] . "' title='Editar' class='editar-lista'>Editar</a>"
        . "</tr>";
        $cont++;
    }
}
?>

