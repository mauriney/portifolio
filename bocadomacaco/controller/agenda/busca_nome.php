
<?php

@session_start();

require_once("../../conf/config.php");
require_once('../../utils/funcoes.php');
require_once('../../utils/agenda/funcoes.php');

$db = Conexao::getInstance();

$dia = isset($_REQUEST['dia']) ? $_REQUEST['dia'] : 'hoje';
$opd = $dia == 'hoje' ? date('d') : date('d') + 1;
$dd = date('Y-m-d', mktime(0, 0, 0, date('m'), $opd, date('Y')));

if ($_GET['id'] != "" && $_GET['id'] > 0) {

    $sql = $db->prepare("SELECT a.IdUsuario, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, a.IdMunicipio
                    FROM tb_bsc_agenda a 
                    LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio 
                    LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
                    LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
                    LEFT JOIN tb_bsc_contato cont ON cont.idcontato = a.contato_participante 
                    LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
                    LEFT JOIN x_agenda_participante AS pa ON pa.IdAgenda = a.IdAgenda 
                    LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = pa.IdContato 
                    WHERE a.DataAgenda = ? AND u.IdUsuario = ? AND a.Confirmado = 1
                    GROUP BY a.IdAgenda ORDER BY a.HoraAgenda ASC");
    $sql->bindValue(1, $dd);
    $sql->bindValue(2, $_GET['id']);
    $sql->execute();
    $linhas = $sql->rowCount();
} else {
    $linhas = 0;
}

if ($linhas > 0) {

    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || $linha['IdUsuario'] == $_SESSION['id']) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
            $participante = utf8_encode(agenda_participantes($linha['IdAgenda']));

            echo "<tr>"
            . "<td data-th='#'>" . hora($linha['HoraAgenda']) . ""
            . "<td data-th='Pauta'>" . $linha['Pauta'] . ""
            . "<td data-th='Solicitante'>" . $linha['Demandante'] . ""
            . "<td data-th='Local'>" . $linha['LocalEvento'] . ""
            . "<td data-th='Quem Vai'>" . $participante . ""
            . "<td data-th='Prioridade'><span class='prioridade-lista " . prioridade_cor($linha['IdPrioridade']) . "-lista'>" . prioridade($linha['IdPrioridade']) . "</span>"
            . "</tr>";
        }
    }
} else if ($_GET['id'] == "" && $_GET['id'] == 0) {

    $sql = $db->prepare("SELECT a.IdUsuario, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, a.IdMunicipio
                    FROM tb_bsc_agenda a 
                    LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio 
                    LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
                    LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
                    LEFT JOIN tb_bsc_contato cont ON cont.idcontato = a.contato_participante 
                    LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
                    WHERE DataAgenda = ? AND Confirmado = 1
                    GROUP BY a.IdAgenda ORDER BY HoraAgenda ASC");

    $sql->bindValue(1, $dd);
    $sql->execute();
    while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

        if ($_SESSION['acesso'] == 1 || $linha['IdUsuario'] == $_SESSION['id']) {//MOSTRA SÓ AS AGENDAS DO RESPONSÁVEL OU MOSTRA TODAS PARA O ADMINISTRADOR
            $participante = utf8_encode(agenda_participantes($linha['IdAgenda']));

            echo "<tr>"
            . "<td data-th='#'>" . hora($linha['HoraAgenda']) . ""
            . "<td data-th='Pauta'>" . $linha['Pauta'] . ""
            . "<td data-th='Solicitante'>" . $linha['Demandante'] . ""
            . "<td data-th='Local'>" . $linha['LocalEvento'] . ""
            . "<td data-th='Quem Vai'>" . $participante . ""
            . "<td data-th='Prioridade'><span class='prioridade-lista " . prioridade_cor($linha['IdPrioridade']) . "-lista'>" . prioridade($linha['IdPrioridade']) . "</span>"
            . "</tr>";
        }
    }
}
?>

