
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');
require_once('../../utils/demanda/funcoes.php');

$db = Conexao::getInstance();


if ($_GET['nome'] != "" || $_GET['segmento'] != "" && strlen($_GET['segmento']) > 0) {

    $campo1 = "1";
    $campo2 = "";
    $pesquisa1 = "1";
    $valor1 = "";
    $valor2 = "";
    $pesquisa2 = "";

    if ($_GET['nome'] != "") {
        $campo1 = "a.Nome LIKE ?";
        $valor1 = "%" . $_GET['nome'] . "%";
        $pesquisa1 = "a.Nome LIKE '%" . $_GET['nome'] . "%'";
    }

    if ($_GET['segmento'] != "" && strlen($_GET['segmento']) > 0) {
        $campo2 = "AND s.IdSegmento = ?";
        $valor2 = "" . $_GET['segmento'] . "";
        $pesquisa2 = "AND s.IdSegmento = " . $_GET['segmento'] . "";
    }

    $contador = 1;

    $sql = $db->prepare("SELECT a.*, u.Nome As responsavel_nome, a.IdUsuario AS responsavel, p.Descricao as prioridade, s.Descricao as segmento
                FROM tb_bsc_preagenda a 
 		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade
 		LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.idsegmento
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario  = a.IdUsuario
 		WHERE $campo1 $campo2 AND IdAgenda = 0 ORDER BY prazo ASC, IdPreAgenda DESC");

    if ($valor1 != "") {
        $sql->bindValue($contador, $valor1);
        $contador++;
    }
    if ($valor2 != "") {
        $sql->bindValue($contador, $valor2);
        $contador++;
    }

    $sql->execute();

    $linhas = $sql->rowCount();
} else {
    $linhas = 0;
}

if ($linhas > 0) {

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || $linha['responsavel'] == $_SESSION['id']) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
            $cores = "";
            if ($linha['atencao'] == 1) {
                $cores = "style='background-color: #FFFFD0'";
            }

            echo "<tr>"
            . "<td $cores data-th='#'><span class='container-lista'>" . data_volta($linha['prazo']) . "</sapn>"
            . "<td $cores data-th='Nome'>" . $linha ['Nome'] . ""
            . "<td $cores data-th='Assunto'>" . $linha['Assunto'] . ""
            . "<td $cores $cores data-th='Celular'><span class='container-lista'>" . $linha ['TelefoneCel'] . "</sapn>"
            . "<td $cores data-th='E-mail'><span class='container-lista'>" . $linha['Email'] . "</sapn>"
            . "<td $cores data-th='Segmento'>" . $linha ['segmento'] . ""
            . "<td $cores data-th='Prioridade'><span class='prioridade-lista " . prioridade_cor($linha ['IdPrioridade']) . "-lista'>" . prioridade($linha ['IdPrioridade']) . "</span>"
            . "<td $cores data-th='Registrado'><span class='container-lista'>" . data_volta_hora($linha['data_cadastro']) . "<br/>" . utf8_encode($linha['responsavel_nome']) . "</sapn>"
            . "<td $cores data-th='Link'>"
            . "<ul class='links'>
                <li><a href='marcar-agenda-cadastro.php?id=" . $linha['IdPreAgenda'] . "' title='Agendar' class='add-lista'>Adicionar</a></li>
                <li><a href='preagenda-cadastro.php?id=" . $linha['IdPreAgenda'] . "' title='Editar' class='editar-lista'>Editar</a></li>";

            if ($_SESSION ['acesso'] == 1 || $linha ['responsavel'] == $_SESSION['id']) {//ADMINISTRADOR OU O USUÁRIO QUE CADASTROU
                echo "<li><a href='#' title='Excluir' class='excluir-lista' onclick='remover(" . $linha['IdPreAgenda'] . ")'>Excluir</a></li>";
            }

            echo "</ul>"
            . "</tr>";
        }
    }

    $codigo = "SELECT a.*, u.Nome As responsavel_nome, a.IdUsuario AS responsavel, p.Descricao as prioridade, s.Descricao as segmento
                FROM tb_bsc_preagenda a 
 		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade
 		LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.idsegmento
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario  = a.IdUsuario
 		WHERE $pesquisa1 $pesquisa2 AND IdAgenda = 0 ORDER BY prazo ASC, IdPreAgenda DESC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
} else if ($_GET['nome'] == "" && $_GET['segmento'] == "" || $_GET['nome'] == "" && strlen($_GET['segmento']) == 0) {

    $sql = $db->prepare("SELECT a.*, u.Nome As responsavel_nome, a.IdUsuario AS responsavel, p.Descricao as prioridade, s.Descricao as segmento
                FROM tb_bsc_preagenda a 
 		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade
 		LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.idsegmento
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario  = a.IdUsuario
 		WHERE IdAgenda = 0 ORDER BY prazo ASC, IdPreAgenda DESC");
    $sql->execute();

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || $linha['responsavel'] == $_SESSION['id']) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
            $cores = "";
            if ($linha ['atencao'] == 1) {
                $cores = "style='background-color: #FFFFD0'";
            }

            echo "<tr>"
            . "<td $cores data-th='#'><span class='container-lista'>" . data_volta($linha['prazo']) . "</sapn>"
            . "<td $cores data-th='Nome'>" . $linha['Nome'] . ""
            . "<td $cores data-th='Assunto'>" . $linha['Assunto'] . ""
            . "<td $cores data-th='Celular'><span class='container-lista'>" . $linha ['TelefoneCel'] . "</sapn>"
            . "<td $cores data-th='E-mail'><span class='container-lista'>" . $linha['Email'] . "</sapn>"
            . "<td $cores data-th='Segmento'>" . $linha['segmento'] . ""
            . "<td $cores data-th='Prioridade'><span class='prioridade-lista " . prioridade_cor($linha['IdPrioridade']) . "-lista'>" . prioridade($linha ['IdPrioridade']) . "</span>"
            . "<td $cores data-th='Registrado'><span class='container-lista'>" . data_volta_hora($linha['data_cadastro']) . "<br/>" . utf8_encode($linha['responsavel_nome']) . "</sapn>"
            . "<td $cores data-th='Link'>"
            . "<ul class='links'>
                <li><a href='marcar-agenda-cadastro.php?id=" . $linha['IdPreAgenda'] . "' title='Agendar' class='add-lista'>Adicionar</a></li>
                <li><a href='preagenda-cadastro.php?id=" . $linha['IdPreAgenda'] . "' title='Editar' class='editar-lista'>Editar</a></li>";

            if ($_SESSION['acesso'] == 1 || $linha['responsavel'] == $_SESSION['id']) {//ADMINISTRADOR OU O USUÁRIO QUE CADASTROU
                echo "<li><a href='#' title='Excluir' class='excluir-lista' onclick='remover(" . $linha['IdPreAgenda'] . ")'>Excluir</a></li>";
            }

            echo "</ul>"
            . "</tr>";
        }
    }
    $codigo = "SELECT a.*, u.Nome As responsavel_nome, a.IdUsuario AS responsavel, p.Descricao as prioridade, s.Descricao as segmento
                FROM tb_bsc_preagenda a 
 		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade
 		LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.idsegmento
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario  = a.IdUsuario
 		WHERE IdAgenda = 0 ORDER BY prazo ASC, IdPreAgenda DESC";

    echo '<input type="hidden" id="codigo" name="codigo" value="' . $codigo . '"/>';
}
?>

