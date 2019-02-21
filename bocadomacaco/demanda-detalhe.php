<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>
<?php include_once('utils/demanda/funcoes.php'); ?>

<?php
if (is_numeric($_GET['id']) && antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT u.Nome AS responsavel, d.IdUsuario, d.status, d.solicitante, d.celular, d.email, d.prazo, s.descricao as segmento, d.id, d.demanda, d.prioridade, d.cidade_id, d.data_cadastro"
            . " FROM x_demanda d, x_demanda_responsavel r, tb_bsc_segmento s, tb_bsc_usuario u"
            . " WHERE d.id = r.demanda_id AND d.segmento = s.IdSegmento AND d.IdUsuario = u.IdUsuario AND d.id = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_demanda = $result->fetch(PDO::FETCH_ASSOC);

    $demanda_id = $dados_demanda['id'];
    $demanda = utf8_encode($dados_demanda['demanda']);
    $prioridade = $dados_demanda['prioridade'];
    $segmento = utf8_encode($dados_demanda['segmento']);
    $municipio = $dados_demanda['cidade_id'];
    $data_cadastro = obterDataBRTimestamp($dados_demanda['data_cadastro']);
    $prazo = obterDataBRTimestamp($dados_demanda['prazo']);
    $prazo2 = $dados_demanda['prazo'];
    $solicitante = utf8_encode($dados_demanda['solicitante']);
    $celular = $dados_demanda['celular'];
    $email = $dados_demanda['email'];
    $resp = utf8_encode($dados_demanda['responsavel']);

    $status = $dados_demanda['status'];

    $nao = "checked='true'";
    $andamento = "";
    $concluido = "";
    $cancelado = "";
    $sem = "";

    if ($status == 0) {
        $nao = "";
        $andamento = "checked='true'";
        $concluido = "";
        $cancelado = "";
        $sem = "";
    } else if ($status == 1) {
        $nao = "";
        $andamento = "";
        $concluido = "";
        $cancelado = "checked='true'";
        $sem = "";
    } else if ($status == 3) {
        $nao = "";
        $andamento = "";
        $concluido = "checked='true'";
        $cancelado = "";
        $sem = "";
    } else if ($status == 4) {
        $nao = "";
        $andamento = "";
        $concluido = "";
        $cancelado = "";
        $sem = "checked='true'";
    }

    $contador = @$_POST["carre"];
    if ($contador == "") {
        $contador = 1;
    }

    if (isset($_POST['carre'])) {
        $contador+=1;
        echo "<script>document.getElementById('voltar_pagina').setAttribute('onclick', 'window.history.go(-$contador)');</script>";
    }
} else {
    echo "<script 'text/javascript'>window.location = 'demanda-painel.php';</script>";
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
        if ($_SESSION['acesso'] == 1 || $_SESSION['id'] == $dados_demanda['IdUsuario']) {
            ?>
            <a href="demanda-cadastro.php?id=<?php echo $demanda_id; ?>" title="Editar" class="editar"></a>
            <?php
        }
        ?>
        <a href="imprimir-demanda-detalhe.php?id=<?php echo $demanda_id; ?>" target="_blank" title="Imprimir" class="imprimir"></a>
    </div>
    <div class="view">
        <div class="row">
            <div class="col-md-12">

                <span class="status-view"><?php echo status_demanda($status); ?></span>
                <span class="situacao-view <?php echo situacao_demanda2($prazo2, $status); ?>"><?php echo situacao_demanda($prazo2, $status); ?></span>

            </div>
        </div><!-- fim linha -->
        <div class="row">
            <div class="col-md-12">
                <span class="titulo">Demanda</span>
                <span class="conteudo-bd demanda-bd"><?php echo $demanda; ?></span>
            </div>
        </div><!-- fim linha -->
        <div class="row">
            <div class="col-md-3">

                <span class="titulo">Prioridade</span>
                <span class="<?php echo prioridade_cor($prioridade); ?> prioridade-view"><?php echo prioridade($prioridade); ?></span>
            </div>
            <div class="col-md-5">
                <span class="titulo">Segmento</span>
                <span class="conteudo-bd"><?php echo $segmento; ?></span>
            </div>
            <div class="col-md-4">
                <span class="titulo">Município</span>
                <span class="conteudo-bd"><?php echo utf8_encode(nome_cidade_id($municipio)); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <span class="titulo">Cadastrado por</span>
                <span class="conteudo-bd"><?php echo $resp; ?></span>
            </div>
            <div class="col-md-3">
                <span class="titulo">Data de cadastro</span>
                <span class="conteudo-bd"><?php echo $data_cadastro; ?></span>
            </div>
            <div class="col-md-3">
	            <div class="destaque">
	                <span class="titulo">Prazo de conclusão</span>
	                <span class="conteudo-bd"><?php echo $prazo; ?> (<?php echo hoje($prazo); ?>)</span>
	            </div>
            </div>
        </div><!-- fim linha -->
        <div class="row">
            <div class="col-md-5">
                <span class="titulo">Solicitante</span>
                <span class="conteudo-bd"><?php echo $solicitante; ?></span>
            </div>
            <div class="col-md-2">
                <span class="titulo">Celular</span>
                <span class="conteudo-bd"><?php echo $celular; ?></span>
            </div>
            <div class="col-md-5">
                <span class="titulo">E-mail</span>
                <span class="conteudo-bd"><?php echo $email; ?></span>
            </div>
        </div><!-- fim linha -->
        <div class="row">
            <div class="col-md-12">
                <div class="responsavel-view">
                    <span class="titulo">Responsável</span>
                    <?php
                    $result = $db->prepare("SELECT u.Nome as responsavel
                             FROM x_demanda_responsavel r, tb_bsc_usuario u
                             WHERE r.responsavel_id = u.IdUsuario AND r.demanda_id = ? GROUP BY u.IdUsuario");
                    $result->bindValue(1, $demanda_id);
                    $result->execute();
                    while ($responsavel = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <span class="conteudo-bd"><?php echo utf8_encode($responsavel['responsavel']); ?></span>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div><!-- fim linha -->

        <form id="form_detalhe" action="#" method="post">
            <input type="hidden" id="carre" name="carre" value="<?php echo $contador; ?>"/>
            <input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
            <div class="row nada">
                <div class="col-md-12"><h2>Observações</h2></div>

                <div class="col-md-8">
                    <div id="obs_add" class="obs-bd" style="display: none">
                        <div id="div_obs">
                            <textarea id="observacoes" name="observacoes" class="form-control" placeholder="Observações"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-1">
                    <div id="obs_add2" class="obs-bd" style="display: none">
                        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar btn-enviar-obs">Adicionar</button>
                    </div>
                </div>
            </div>
        </form>

        <form id="form_detalhe2" action="#" method="post">
            <input type="hidden" id="carre" name="carre" value="<?php echo $contador; ?>"/>
            <?php
            $sql3 = $db->prepare("SELECT x.responsavel_id , x.id, x.obs, u.Nome as responsavel, x.data_cadastro"
                    . " FROM x_demanda_obs x, tb_bsc_usuario u"
                    . " WHERE x.demanda_id = ? AND x.responsavel_id = u.IdUsuario"
                    . " ORDER BY id ASC");
            $sql3->bindValue(1, $demanda_id);
            $sql3->execute();
            while ($value3 = $sql3->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <input type="hidden" id="id_obs" name="id_obs" value="<?php echo $value3['id']; ?>"/>
                <div class="row nada">
                    <div class="col-md-7">

                        <div id="obs_visualizar_<?php echo $value3['id']; ?>" class="obs-bd" style="display: block">
                            <?php echo utf8_encode($value3['obs']); ?>
                        </div>

                        <div id="obs_editar_<?php echo $value3['id']; ?>" class="obs-bd" style="display: none">
                            <div id="div_obs_<?php echo $value3['id']; ?>">
                                <textarea id="observacoes_<?php echo $value3['id']; ?>" name="observacoes_<?php echo $value3['id']; ?>" class="form-control" placeholder="Observações"><?php echo utf8_encode($value3['obs']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <?php
                    if ($_SESSION['acesso'] == 1 || $_SESSION['id'] == $value3['responsavel_id']) {//SÓ QUEM TEM ACESSO DE ADMINISTRADOR OU O PRÓPRIO USUÁRIO QUE CADASTROU PODEM EDITAR
                        ?>

                        <div class="col-md-1">
                            <div id="obs_visualizar2_<?php echo $value3['id']; ?>" class="obs-bd" style="display: block">
                                <a href="#" title="Editar Observação" class="editar-obs" onclick="visualizar_obs(<?php echo $value3['id']; ?>)">Editar</a>
                            </div>
                            <div id="obs_editar2_<?php echo $value3['id']; ?>" class="obs-bd" style="display: none">
                                <button title="Atualizar Observação" onclick="atualizar_obs(<?php echo $value3['id']; ?>)" type="button" name="enviar" id="enviar" class="btn btn-redondo btn-enviar btn-enviar-obs">Adicionar</button>
                            </div>
                        </div>

                        <?php
                    }
                    ?>

                    <?php
                    if ($_SESSION['acesso'] == 1) {//SÓ QUEM TEM ACESSO DE ADMINISTRADOR QUE PODE REMOVER UMA OBSERVAÇÃO
                        ?>

                        <div class="col-md-1">
                            <div id="obs_visualizar2" class="obs-bd">
                                <a onclick="remover(<?php echo $value3['id']; ?>)" href="#" title="Remover Observação" class="excluir-lista">Remover</a>
                            </div>
                        </div>

                        <?php
                    }
                    ?>

                    <div class="col-md-3">
                        <div class="obs-bd">
                            <span><?php echo utf8_encode($value3['responsavel']); ?></span>
                            <span><?php echo obterDataBRTimestamp($value3['data_cadastro']) . " às " . obterHoraTimestamp($value3['data_cadastro']); ?></span>
                        </div>
                    </div>
                </div><!-- fim linha -->

                <?php
            }
            ?>

        </form>

        <div class="row area-botoes">
            <?php
            if ($_SESSION['acesso'] == 1 || $_SESSION['acesso'] == 2 || $_SESSION['acesso'] == 3 || $_SESSION['id'] == $dados_demanda['IdUsuario']) {
                ?>
                <div class="col-sm-6">
                    <a href="#" title="Mudar Status" class="bt-view mudar-status" data-toggle="modal" data-target="#md-status-demanda"> <i class="icone-view icone-mudar"></i> Mudar Status</a>
                </div>
                <div class="col-sm-6">
                    <a onclick="adicionar()" href="#" title="Adicionar observação" class="bt-view add-observacao"> <i class="icone-view icone-obs"></i> Adicionar observação</a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include("rodape.php") ?>

<!-- Modal -->
<div class="modal fade" id="md-status-demanda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog md-status-demanda" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fechar"></span></button>
                <span>Status da Demanda</span>
            </div>
            <form id="form_demanda" action="#" method="post">
                <input type="hidden" id="carre" name="carre" value="<?php echo $contador; ?>"/>
                <input type="hidden" id="id" name="id" value="<?php echo $demanda_id ?>"/>
                <div class="modal-body">
                    <ul class="status-modal">
                        <li class="col-md-1 col-md-offset-1"></li>
                        <li class="col-md-2"><label class="st-nao-iniciado" for="nao-iniciado">NÃO INICIADO</label><input  <?php echo $nao; ?> type="radio" name="opcao" id="opcao" value="2"/></li>
                        <li class="col-md-2"><label class="st-em-andamento" for="em-andamento">EM ANDAMENTO</label><input <?php echo $andamento; ?> type="radio" name="opcao" id="opcao" value="0"/></li>
                        <li class="col-md-2"><label class="st-concluido" for="concluido">CONCLUÍDO</label><input  <?php echo $concluido; ?> type="radio" name="opcao" id="opcao" value="3"/></li>
                        <li class="col-md-2"><label class="st-concluido" for="sem">SEM SOLUÇÃO    </label><input  <?php echo $sem; ?> type="radio" name="opcao" id="opcao" value="4"/></li>
                        <li class="col-md-2"><label class="st-cancelado" for="cancelado">CANCELADO</label><input  <?php echo $cancelado; ?> type="radio" name="opcao" id="opcao" value="1"/></li>
                        <li class="col-md-1 col-md-offset-1"></li>
                    </ul>
                </div>
                <br style="clear:both"/>
                <div class="modal-footer">
                    <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/demanda/demanda-detalhe.js"></script>
<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>
