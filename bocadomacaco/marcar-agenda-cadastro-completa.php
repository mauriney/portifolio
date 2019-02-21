<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT a.Observacao, a.recorrente, m.nome as municipio_nome, m.idestado as estado_id, a.IdMunicipio, a.IdSegmento, a.TelefoneCelDem, a.TelefoneFixoDem, a.EmailDemandante, bai.idbairro, a.IdAgenda, Demandante, Contato, a.LocalEvento, DataAgenda, HoraAgenda, Pauta, Confirmado, p.Cor, p.IdPrioridade, atencao, p.Descricao as descr
		FROM tb_bsc_agenda a 
		LEFT JOIN cidade m ON m.idcidade = a.IdMunicipio 
		LEFT JOIN tb_bsc_prioridade p ON p.IdPrioridade = a.IdPrioridade 
		LEFT JOIN tb_bsc_acesso_agenda aa ON aa.IdAgenda = a.IdAgenda 
		LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.bairro
		LEFT JOIN tb_bsc_segmento seg ON seg.IdSegmento = a.IdSegmento
		LEFT JOIN tb_bsc_aviso_agenda avi ON avi.idagenda = a.IdAgenda
		LEFT JOIN tb_bsc_aviso_agenda avi2 ON avi2.idagenda = a.recorrente
		WHERE a.IdAgenda = ? GROUP BY a.IdAgenda ORDER BY DataAgenda, HoraAgenda ASC");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_agenda = $result->fetch(PDO::FETCH_ASSOC);
} else {
    echo "<script 'text/javascript'>window.location = 'agendacompleta-painel.php';</script>";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <div class="cadastro">
        <form id="form_marcar_agenda" action="" method="post">

            <input type="hidden" id="id" name="id" value="<?php echo $dados_agenda['IdAgenda'] ?>"/>

            <h2>Dados do Demandante</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="demandante">Demandante</label>
                        <div id="div_demandante">
                            <input type="text" name="demandante" id="demandante" class="form-control" placeholder="Demandante" value="<?php echo $dados_agenda['Demandante']; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fixo">Telefone Fixo</label>
                        <input type="text" name="fixo" id="fixo" class="form-control" placeholder="Telefone fixo" maxlength="15" value="<?php echo $dados_agenda['TelefoneFixoDem']; ?>"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="text" name="celular" id="celular" class="form-control" placeholder="Celular" maxlength="15" value="<?php echo $dados_agenda['TelefoneCelDem']; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="<?php echo $dados_agenda['EmailDemandante']; ?>"/>
                    </div>
                </div>
            </div>

            <h2>Dados da Agenda</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="pauta">Pauta</label>
                        <textarea id="pauta" name="pauta" class="form-control" placeholder="Pauta"><?php echo $dados_agenda['Pauta']; ?></textarea>
                    </div>
                </div>
            </div><!-- fim linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="segmento">Segmento</label>
                        <select name="segmento" id="segmento" class="ls-select" placeholder="Segmento">
                            <option value="">Escolha o segmento</option>
                            <?php
                            $result = $db->prepare("SELECT IdSegmento, Descricao 
	                             FROM tb_bsc_segmento
	                             ORDER BY Descricao ASC");
                            $result->execute();
                            while ($value = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_agenda['IdSegmento'] == $value['IdSegmento']) {
                                    ?>
                                    <option selected="true" value='<?php echo $value['IdSegmento']; ?>'><?php echo utf8_encode($value['Descricao']); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value='<?php echo $value['IdSegmento']; ?>'><?php echo utf8_encode($value['Descricao']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div><!-- fim linha -->
            <!-- linha -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="data">Data</label>
                        <input type="text" name="data" id="data" class="form-control data" placeholder="Data" maxlength="10" value="<?php echo data_volta($dados_agenda['DataAgenda']); ?>"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <link rel="stylesheet" type="text/css" href="plugins/relogio/dist/bootstrap-clockpicker.min.css">
                        <link rel="stylesheet" type="text/css" href="plugins/relogio/assets/css/github.min.css">
                        <label for="hora">Hora</label>
                        <div class="input-group clockpicker">
                            <input type="text" name="hora" id="hora" class="form-control hora" placeholder="Hora" maxlength="5" value="<?php echo hora($dados_agenda['HoraAgenda']); ?>"/>
                        </div>                   
                        <script type="text/javascript">
                            $('.clockpicker').clockpicker();
                        </script>
                        <script type="text/javascript" src="plugins/relogio/assets/js/jquery.min.js"></script>
                        <script type="text/javascript" src="plugins/relogio/assets/js/bootstrap.min.js"></script>
                        <script type="text/javascript" src="plugins/relogio/dist/bootstrap-clockpicker.min.js"></script>
                        <script type="text/javascript">
                            $('.clockpicker').clockpicker()
                                    .find('input#hora').change(function() {
                                console.log(this.value);
                            });
                            var input = $('#single-input').clockpicker({
                                placement: 'bottom',
                                align: 'left',
                                autoclose: true,
                                'default': 'now'
                            });

                            // Manually toggle to the minutes view
                            $('#check-minutes').click(function(e) {
                                // Have to stop propagation here
                                e.stopPropagation();
                                input.clockpicker('show')
                                        .clockpicker('toggleView', 'minutes');
                            });
                            if (/mobile/i.test(navigator.userAgent)) {
                                $('input#hora').prop('readOnly', true);
                            }
                        </script>
                        <script type="text/javascript" src="plugins/relogio/assets/js/highlight.min.js"></script>
                        <script type="text/javascript">
                            hljs.configure({tabReplace: '    '});
                            hljs.initHighlightingOnLoad();
                        </script>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="local">Local</label>
                        <input type="text" name="local" id="local" class="form-control" placeholder="Local" value="<?php echo $dados_agenda['LocalEvento']; ?>"/>
                    </div>
                </div>
            </div><!-- fim linha -->

            <div class="row">
                <div class="col-md-4">
                    <label for="estado">Estado </label>
                    <select id="estado" name="estado" placeholder="Estado" class="ls-select">
                        <option value="">Escolha o estado</option>
                        <?php
                        $result = $db->prepare("SELECT nome, idestado, sigla 
	                             FROM estado
	                             ORDER BY nome ASC");
                        $result->execute();
                        while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                            if ($dados_agenda['estado_id'] == $estado['idestado']) {
                                ?>
                                <option selected="true" label='<?php echo $estado['sigla']; ?>' value='<?php echo $estado['idestado']; ?>'><?php echo utf8_encode($estado['nome']); ?></option>
                                <?php
                            } else {
                                ?>
                                <option label='<?php echo $estado['sigla']; ?>' value='<?php echo $estado['idestado']; ?>'><?php echo utf8_encode($estado['nome']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="municipio">Município </label>
                    <select onchange="vf_bairro(this.value)" id="municipio" name="municipio" placeholder="Município" class="ls-select">
                        <?php
                        $result3 = $db->prepare("select nome, idcidade from cidade WHERE idestado = ? order by nome ASC");
                        $result3->bindValue(1, estado_municipio($dados_agenda['IdMunicipio']));
                        $result3->execute();
                        while ($municipio = $result3->fetch(PDO::FETCH_ASSOC)) {
                            if ($dados_agenda['IdMunicipio'] == $municipio['idcidade']) {
                                ?>
                                <option selected="true" value="<?php echo $municipio['idcidade']; ?>"><?php echo utf8_encode($municipio['nome']); ?></option>
                                <?php
                            } else {
                                ?>
                                <option label='<?php echo utf8_encode($municipio['nome']); ?>' value='<?php echo $municipio['idcidade']; ?>'><?php echo utf8_encode($municipio['nome']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div id="mostrar-bairro" class="col-md-4" style="display: <?php echo $dados_agenda['IdMunicipio'] == 16 ? 'block' : 'none' ?>">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <select name="bairro" id="bairro" class="ls-select" placeholder="Bairro">
                            <option value="">Escolha o bairro</option>
                            <?php
                            $result = $db->prepare("SELECT idbairro, nome FROM tb_bsc_bairro ORDER by nome");
                            $result->execute();
                            while ($value = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($dados_agenda['idbairro'] == $value['idbairro']) {
                                    ?>
                                    <option selected="true" value='<?php echo $value['idbairro']; ?>'><?php echo utf8_encode($value['nome']); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value='<?php echo $value['idbairro']; ?>'><?php echo utf8_encode($value['nome']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div><!-- fim linha -->

            <h2>Administrador</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group quem-vai">
                        <label for="quemvai">Quem Vai?</label>
                        <div id="div_quemvai">
                            <select name="quemvai[]" id="quemvai" class="ls-select" placeholder="Quem Vai?" multiple>
                                <?php
                                $result = $db->prepare("SELECT idusuario, nome FROM tb_bsc_usuario WHERE quemvai = 1 ORDER by nome");
                                $result->execute();
                                while ($value = $result->fetch(PDO::FETCH_ASSOC)) {
                                    if (is_numeric(pesquisa2("IdAgenda", "x_agenda_participante", "IdAgenda = ?", $dados_agenda['IdAgenda'], "AND IdContato = ?", $value['idusuario'], "", "", "", "", ""))) {
                                        ?>
                                        <option selected="true" value='<?php echo $value['idusuario']; ?>'><?php echo utf8_encode($value['nome']); ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value='<?php echo $value['idusuario']; ?>'><?php echo utf8_encode($value['nome']); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                </div>
            </div>

            <!-- linha -->
            <div class="row">
                <div class="col-md-3">
                    <div id="prioridade_cor" class="form-group prioridade-cadastro baixa">
                        <label for="prioridade" class="control-label col-md-4" style="text-align: left;">Prioridade</label>
                        <div class="col-md-8">
                            <select onchange="cores($(this).val())" name="prioridade" id="prioridade" class="form-control">
                                <?php
                                if ($dados_agenda['IdPrioridade'] == 2) {
                                    ?>
                                    <option value="1">Baixa</option>
                                    <option selected="true" value="2">Média</option>
                                    <option value="3">Alta</option>
                                    <?php
                                } else if ($dados_agenda['IdPrioridade'] == 3) {
                                    ?>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option selected="true" value="3">Alta</option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="atencao-extra">
                        <div class="col-md-6">Atenção extra a essa agenda?</div>
                        <?php
                        $opcao1_sim = '';
                        $opcao1_nao = 'checked="true"';
                        $display = 'style="display:none"';

                        if ($dados_agenda['atencao'] == 1) {
                            $opcao1_sim = 'checked="true"';
                            $opcao1_nao = '';
                            $display = 'style="display:block"';
                        }
                        ?>
                        <div class="col-md-3"><span><input <?php echo $opcao1_sim; ?> type="radio" id="sim1" name="opcao1" value="1"/> <label for="sim"> SIM</label> </span> </div>
                        <div class="col-md-3"><span><input <?php echo $opcao1_nao; ?> type="radio" id="nao1" name="opcao1" value="0"/> <label for="nao"> NÃO</label> </span> </div>
                    </div>
                </div>
                <!--<div class="col-md-5">
                    <div class="atencao-extra">
                        <div class="col-md-5">Níveis que terão acesso a este evento</div>
                        <?php
                        /*$interm = 'checked="true"';
                        $basic = 'checked="true"';

                        if (!is_numeric(pesquisa2("IdAgenda", "tb_bsc_acesso_agenda", "IdAgenda = ?", $dados_agenda['IdAgenda'], " AND IdAcesso = ?", 2, "", "", "", "", ""))) {
                            $interm = '';
                        }
                        if (!is_numeric(pesquisa2("IdAgenda", "tb_bsc_acesso_agenda", "IdAgenda = ?", $dados_agenda['IdAgenda'], " AND IdAcesso = ?", 3, "", "", "", "", ""))) {
                            $basic = '';
                        }*/
                        ?>
                        <div class="col-md-4"><span> <input <?php //echo $interm; ?> type="checkbox" id="intermediario" name="intermediario" value="2" onclick="desmarcar_basico()"/> <label for="intermediario"> Intermediário</label> </span> </div>
                        <div class="col-md-3"><span> <input <?php //echo $basic; ?>  type="checkbox" id="basico" name="basico" value="3"/> <label for="basico"> Básico</label> </span> </div>
                    </div>
                </div>-->
            </div>

            <!-- linha -->
            <div id="obs_agenda" class="row" <?php echo $display; ?>>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Observacao">Observação</label>
                        <textarea id="Observacao" name="Observacao" class="form-control" placeholder="Observação"><?php echo $dados_agenda['Observacao']; ?></textarea>
                    </div>
                </div>
            </div><!-- fim linha -->

            <!-- linha -->
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
                                    if (is_numeric(pesquisa2("IdAgenda", "tb_bsc_agenda_segmento", "IdAgenda = ?", $dados_agenda['IdAgenda'], "AND IdSegmento = ?", $grupo['IdSegmento'], "", "", "", "", ""))) {
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
            <div class="clearfix"></div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="area-recorrente">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="recorrente">
                                    <div class="row">
                                        <div class="col-md-6"><span class="pergunta">Agenda Recorrente?</span></div>
                                        <?php
                                        $opcao2_sim = '';
                                        $opcao2_nao = 'checked="true"';
                                        $periodicidade = "";

                                        if ($dados_agenda['recorrente'] == 1) {
                                            $opcao2_sim = 'checked="true"';
                                            $opcao2_nao = '';
                                            $periodicidade = pesquisa2("data", "tb_bsc_aviso_agenda", "IdAgenda = ?", $dados_agenda['IdAgenda'], "", "", "", "", "", "", "");
                                        }
                                        ?>
                                        <div class="col-md-3"><span><input <?php echo $opcao2_sim; ?> type="radio" id="opcao2" name="opcao2" value="1"/> <label for="sim"> SIM</label></span></div>
                                        <div class="col-md-3"><span><input onchange="perio()" <?php echo $opcao2_nao; ?> type="radio" id="opcao2" name="opcao2" value="0"/> <label for="nao"> NÃO</label></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <div class="periodicidade">
                                    <div class="row">
                                        <div class="col-md-3"><span>Periodicidade</span></div>
                                        <div class="col-md-5"><input type="text" id="periodicidade" name="periodicidade" placeholder="0" class="form-control" value="<?php echo $periodicidade; ?>"/></div>
                                        <div class="col-md-4"><span>Dias</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($_SESSION['acesso'] == 1) {
                        $classe = "confirmar";
                        $conf_checked = '';

                        if ($dados_agenda['Confirmado'] == 1) {
                            $conf_checked = 'checked="true"';
                            $classe = "confirmado-agenda";
                        }
                        ?>

                        <div id="confirmar_cor" class="<?php echo $classe; ?>">
                            <div class="col-md-8">
                                Confirmar a realização desta agenda
                            </div>

                            <div class="col-md-4">
                                <label class="switch switch-flat">
                                    <input <?php echo $conf_checked; ?> onchange="confirmar_cor()" id="opcao3" name="opcao3" class="switch-input" type="checkbox" />
                                    <span class="switch-label" data-on="SIM" data-off="NÃO"></span> 
                                    <span class="switch-handle"></span> 
                                </label>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>

            <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">buscar</button>
        </form>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/agenda/marcar-agenda-cadastro-completa.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>