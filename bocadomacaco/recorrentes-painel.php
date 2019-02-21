<?php
include("topo.php");
include_once('utils/preagenda/funcoes.php');
include("menu-lateral.php");
?>

<?php
$contador = @$_POST["carre"];
if ($contador == "") {
    $contador = 1;
}
if (isset($_POST['carre'])) {
    $contador+=1;
    echo "<script>document.getElementById('voltar_pagina').setAttribute('onclick', 'window.history.go(-$contador)');</script>";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">

    <?php
    //CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
    if (isset($_POST['mensagem']) && @$_SESSION['mensagem'] == "OK") {
        @$_SESSION['mensagem'] = "NO";
        echo '<div id="div_success" class="alert alert-success" style="display: none;"><button class="close" data-dismiss="alert"></button><b>Sucesso:</b> ' . $_POST['mensagem'] . '</div>';
    }
    ?>

    <div class="cadastro">
        <h1>Agendas Recorrente</h1>
        <table class="tabela tb-recorrente">
            <thead>
                <tr>
                    <th>#<th>Pauta<th>Demandante<th>Tempo de Retorno<th>
            <tbody>
                <?php
                $cont = 1;

                $sql = $db->prepare("SELECT avi.data as periodicidade, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, m.NomeMunicipio, a.IdMunicipio
		FROM tb_bsc_agenda a 
		LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio 
		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
		LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
		LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
		LEFT JOIN tb_bsc_segmento seg ON seg.IdSegmento = a.IdSegmento
		LEFT JOIN tb_bsc_aviso_agenda avi ON avi.idagenda = a.IdAgenda
		LEFT JOIN tb_bsc_aviso_agenda avi2 ON avi2.idagenda = a.recorrente 
                WHERE a.recorrente = 1 GROUP BY a.IdAgenda ORDER BY DataAgenda, HoraAgenda ASC");

                $sql->execute();
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

                    if ($cont < 10) {
                        $cont = "0" . $cont;
                    }
                    ?>
                    <tr>
                        <td data-th="#"><?php echo $cont; ?>
                        <td data-th="Pauta"><?php echo $linha['Pauta']; ?>
                        <td data-th="Solicitante"><?php echo $linha['Demandante']; ?>
                        <td data-th="Tempo de Retorno"><span class="container-lista"><?php echo $linha['periodicidade']; ?> Dia(s)</span>
                        <td data-th="">
                            <ul class="links">
                                <li><a href="marcar-agenda-cadastro-completa.php?id=<?php echo $linha['IdAgenda']; ?>" title="Editar" class="editar-lista">Editar</a></li>
                                <li><a href="#" title="Excluir" class="excluir-lista" onclick="remover(<?php echo $linha['IdAgenda']; ?>, <?php echo $contador; ?>)">Excluir</a></li>
                            </ul>
                            <?php
                            $cont++;
                        }
                        ?>
        </table>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/recorrente-painel.js"></script>

<script>

                                    var headertext = [];
                                    var headers = document.querySelectorAll(".tabela th"),
                                            tablerows = document.querySelectorAll(".tabela th"),
                                            tablebody = document.querySelector(".tabela tbody");
                                    for (var i = 0; i < headers.length; i++) {
                                        var current = headers[i];
                                        headertext.push(current.textContent.replace(/\r?\n|\r/, ""));
                                    }
                                    for (var i = 0, row; row = tablebody.rows[i]; i++) {
                                        for (var j = 0, col; col = row.cells[j]; j++) {
                                            col.setAttribute("data-th", headertext[j]);
                                        }
                                    }
</script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>

