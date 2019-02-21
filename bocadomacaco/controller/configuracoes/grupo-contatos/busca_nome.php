
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

    $sql = $db->prepare("SELECT * FROM tb_bsc_grupo_email 
                     WHERE $campo1 ");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }

    $sql->execute();

    $linhas = $sql->rowCount();
    $cont = 1;
    $vf_vinculacao = true;
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if (is_numeric(pesquisar("idcontato", "tb_bsc_contato_grupo", "idgrupo", "=", $linha['IdGrupoEmail'], ""))) {
            $vf_vinculacao = false;
        }

        if ($cont < 10) {
            $cont = "0" . $cont;
        }

        echo "<tr>"
        . "<td data-th='#'>" . $cont . ""
        . "<td data-th='Nome'>" . utf8_encode($linha['Nome']) . ""
        . "<td data-th=''>"
        . "<ul class='links'>"
        . "<li><a href='grupos-contatos-cadastro.php?id=" . $linha['IdGrupoEmail'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a></li>";
        if ($vf_vinculacao) {
            echo "<li><a href='#' title='Excluir' class='excluir-lista' onclick='remover(" . $linha['IdGrupoEmail'] . ")'>Excluir</a></li>";
        }
        echo " </ul>"
        . "</tr>";
        $vf_vinculacao = true;
        $cont++;
    }
} else if ($_GET['buscar'] == "") {

    $sql = $db->prepare("SELECT * FROM tb_bsc_grupo_email");
    $sql->execute();
    $cont = 1;
    $vf_vinculacao = true;
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if (is_numeric(pesquisar("idcontato", "tb_bsc_contato_grupo", "idgrupo", "=", $linha['IdGrupoEmail'], ""))) {
            $vf_vinculacao = false;
        }

        if ($cont < 10) {
            $cont = "0" . $cont;
        }

        echo "<tr>"
        . "<td data-th='#'>" . $cont . ""
        . "<td data-th='Nome'>" . utf8_encode($linha['Nome']) . ""
        . "<td data-th=''>"
        . "<ul class='links'>"
        . "<li><a href='grupos-contatos-cadastro.php?id=" . $linha['IdGrupoEmail'] . "' title='Visuzalizar' class='visualizar'>Visualizar</a></li>";
        if ($vf_vinculacao) {
            echo "<li><a href='#' title='Excluir' class='excluir-lista' onclick='remover(" . $linha['IdGrupoEmail'] . ")'>Excluir</a></li>";
        }
        echo " </ul>"
        . "</tr>";
        $vf_vinculacao = true;
        $cont++;
    }
}
?>

