<?php
include("topo.php");
include_once('utils/agenda/funcoes.php');
include("menu-lateral.php");
?>

<?php
if (is_numeric($_GET['id']) && antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT a.Observacao, u.Nome AS responsavel, a.IdUsuario, a.DataHoraCadastro, a.TelefoneCelDem, a.TelefoneFixoDem, a.EmailDemandante, bai.nome as Bairro, a.IdAgenda, Demandante, Contato, LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr, m.NomeMunicipio, a.IdMunicipio
		FROM tb_bsc_agenda a 
		LEFT JOIN tb_bsc_municipios m ON m.IdMunicipio = a.IdMunicipio 
		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
		LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
		LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
		LEFT JOIN tb_bsc_segmento seg ON seg.IdSegmento = a.IdSegmento
		LEFT JOIN tb_bsc_aviso_agenda avi ON avi.idagenda = a.IdAgenda
		LEFT JOIN tb_bsc_aviso_agenda avi2 ON avi2.idagenda = a.recorrente 
                LEFT JOIN tb_bsc_usuario u ON u.IdUsuario = a.IdUsuario 
		WHERE a.IdAgenda = ? GROUP BY a.IdAgenda ORDER BY DataAgenda, HoraAgenda ASC");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_agenda = $result->fetch(PDO::FETCH_ASSOC);


    $contador = @$_POST["carre"];
    if ($contador == "") {
        $contador = 1;
    }

    if (isset($_POST['carre'])) {
        $contador+=1;
        echo "<script>document.getElementById('voltar_pagina').setAttribute('onclick', 'window.history.go(-$contador)');</script>";
    }
} else {
    echo "<script 'text/javascript'>window.location = 'agendacompleta-painel.php';</script>";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo-bd">

    <?php
//CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
    if (isset($_POST['mensagem']) && @$_SESSION['mensagem'] == "OK") {
        @$_SESSION['mensagem'] = "NO";
        echo '<div id="div_success" class="alert alert-success" style="display: none;"><button class="close" data-dismiss="alert"></button><b>Sucesso:</b> ' . $_POST['mensagem'] . '</div>';
    }
    ?>

    <div class="botoes-acao">
        <?php
        if ($_SESSION['acesso'] == 1 || $dados_agenda['IdUsuario'] == $_SESSION['id']) {//ADMINISTRADOR OU O USUÁRIO QUE CADASTROU
            ?>
            <a href="marcar-agenda-cadastro-completa.php?id=<?php echo $dados_agenda['IdAgenda']; ?>" title="Editar" class="editar"></a>
            <a href="#" title="Exluir" class="excluir" onclick="remover(<?php echo $dados_agenda['IdAgenda']; ?>)"></a>
            <a href="#" data-toggle="modal" data-target="#compartilhar_<?php echo $dados_agenda['IdAgenda']; ?>" title="Compartilhar" class="compartilhar"></a>
            <?php
        }
        ?>
        <a target="_blank" href="imprimir-agendacompleta-detalhe.php?id=<?php echo $dados_agenda['IdAgenda']; ?>" title="Imprimir" class="imprimir"></a>
    </div>

    <div class="view">
        <div class="row">
            <div class="col-md-12">
                <h1 class="data-extenso"><?php echo dataExtensoSemAno(data_volta($dados_agenda['DataAgenda'])) ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"><span class="hora-agenda"><?php echo hora($dados_agenda['HoraAgenda']); ?></span></div>

            <div class="col-md-3"><span class="confirmacao-evento <?php echo $dados_agenda['Confirmado'] == 1 ? 'sim' : 'nao'; ?>-lista"><?php echo $dados_agenda['Confirmado'] == 1 ? 'EVENTO CONFIRMADO' : 'EVENTO NÃO CONFIRMADO'; ?></span></div>

            <div class="col-md-3"><span class="prioridade-agenda <?php echo prioridade_cor($dados_agenda['IdPrioridade']); ?>"><?php echo prioridade($dados_agenda['IdPrioridade']) . " PRIORIDADE"; ?></span></div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <span class="titulo">Pauta</span>
                <span class="conteudo-bd"><?php echo ctexto($dados_agenda['Pauta'], "min"); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <span class="titulo">Local</span>
                <span class="conteudo-bd"><?php echo ctexto($dados_agenda['LocalEvento'], "min"); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <span class="titulo">Bairro</span>
                <span class="conteudo-bd"><?php echo utf8_encode(ctexto($dados_agenda['Bairro'], "min")); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <span class="titulo">Quem Vai</span>
                <span class="conteudo-bd"><?php echo ctexto(utf8_encode(agenda_participantes($dados_agenda['IdAgenda'])), "min"); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <span class="titulo">Solicitante</span>
                <span class="conteudo-bd"><?php echo ctexto($dados_agenda['Demandante'], "min"); ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Telefone(s)</span>
                <span class="conteudo-bd"><?php echo $dados_agenda['TelefoneFixoDem']; ?> <?php
                    if ($dados_agenda['TelefoneFixoDem'] != "") {
                        echo '|';
                    }
                    ?>
                    <?php echo $dados_agenda['TelefoneCelDem']; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">E-mail</span>
                <span class="conteudo-bd"><?php echo $dados_agenda['EmailDemandante']; ?></span>
            </div>
        </div>

        <?php
        if ($dados_agenda['Observacao'] != "") {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <span class="titulo">Observação</span>
                    <span class="conteudo-bd"><?php echo ctexto(utf8_encode($dados_agenda['Observacao'])); ?></span>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="row bg-destaque">
            <div class="col-md-4">
                <span class="titulo">Cadastrado Por</span>
                <span class="conteudo-bd"><?php echo ctexto(utf8_encode($dados_agenda['responsavel'])); ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Data de Cadastro</span>
                <span class="conteudo-bd"><?php echo obterDataBRTimestamp($dados_agenda['DataHoraCadastro']) . " às " . obterHoraTimestamp($dados_agenda['DataHoraCadastro']); ?></span>
            </div>
        </div>
    </div>
</div>

<br/>

<?php include("rodape.php") ?>

<!-- Modal -->
<div class="modal fade" id="compartilhar_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog md-grupo" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fechar"></span></button>
                <span>Enviar a Agenda para Grupos de Contato</span>
            </div>
            <form id="form_agenda" action="#" method="post">
                <input type="hidden" id="carre" name="carre" value="<?php echo $contador; ?>"/>
                <input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
                <div class="modal-body">
                    <div id="grupos">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="grupos">
                                    <h3>Grupos de segmentos que receberão este evento no e-mail</h3>
                                    <ul>
                                        <li class="col-md-3"><input type="checkbox" id="marcar-todos" name="marcar-todos" onclick="marcar(this)"/> <label for="marcar-todos"> MARCAR TODOS </label></li>
                                        <?php
                                        $vf_checados = true;
                                        $check = "";
                                        $result2 = $db->prepare("SELECT IdSegmento, Descricao"
                                        . " FROM tb_bsc_segmento ORDER BY Descricao");
                                        $result2->execute();
                                        while ($grupo = $result2->fetch(PDO::FETCH_ASSOC)) {
                                            if (is_numeric(pesquisa2("IdAgenda", "tb_bsc_agenda_segmento", "IdAgenda = ?", $id, "AND IdSegmento = ?", $grupo['IdSegmento'], "", "", "", "", ""))) {
                                                $check = "checked='true'";
                                            } else {
                                                $check = "";
                                                $vf_checados = false;
                                            }
                                            ?>
                                            <li class="col-md-3"><input <?php echo $check; ?> onclick="vf(this)" type="checkbox" id="grupo" name="grupo[]" value="<?php echo $grupo['IdSegmento']; ?>" /> <label for="marcar-todos"> <?php echo utf8_encode($grupo['Descricao']); ?> </label></li>
                                            <?php
                                        }
                                        ?>
                                        <input type="hidden" id="vf_checados" name="vf_checados" value="<?php echo $vf_checados; ?>"/>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/agenda/agendacompleta-detalhe.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>
