<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT *"
            . " FROM tb_bsc_preagenda"
            . " WHERE IdPreAgenda = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_agenda = $result->fetch(PDO::FETCH_ASSOC);

    $id_pre_agenda = $dados_agenda['IdPreAgenda'];
    $demandante = $dados_agenda['Nome'];
    $fixo = "";
    $celular = $dados_agenda['TelefoneCel'];
    $email = $dados_agenda['Email'];
    $pauta = $dados_agenda['Assunto'];
    $segmento = $dados_agenda['idsegmento'];
    $local = "";
    $bairro = "";
} else {
    $id_pre_agenda = "";
    $demandante = "";
    $fixo = "";
    $celular = "";
    $email = "";
    $pauta = "";
    $segmento = "";
    $local = "";
    $bairro = "";
    $dados_agenda = 0;
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <div class="cadastro">
        <form id="form_marcar_agenda" action="" method="post">

            <input type="hidden" id="id" name="id" value="<?php echo $id_pre_agenda ?>"/>

            <h2>Dados do Demandante</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="demandante">Demandante</label>
                        <div id="div_demandante">
                            <input type="text" name="demandante" id="demandante" class="form-control" placeholder="Demandante" value="<?php echo $demandante; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fixo">Telefone Fixo</label>
                        <input type="text" name="fixo" id="fixo" class="form-control" placeholder="Telefone fixo" maxlength="15" value="<?php echo $fixo; ?>"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="text" name="celular" id="celular" class="form-control" placeholder="Celular" maxlength="15" value="<?php echo $celular; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="<?php echo $email; ?>"/>
                    </div>
                </div>
            </div>

            <h2>Dados da Agenda</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="pauta">Pauta</label>
                        <textarea id="pauta" name="pauta" class="form-control" placeholder="Pauta"><?php echo $pauta; ?></textarea>
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
                                if ($segmento == $value['IdSegmento']) {
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
                        <div id="div_data_agenda">
                        <input type="text" name="data" id="data" class="form-control data" placeholder="Data" maxlength="10" />
                    </div>
                </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <link rel="stylesheet" type="text/css" href="plugins/relogio/dist/bootstrap-clockpicker.min.css">
                        <link rel="stylesheet" type="text/css" href="plugins/relogio/assets/css/github.min.css">
                        <label for="hora">Hora</label>
                        <div id="div_hora" class="input-group clockpicker">
                            <input type="text" name="hora" id="hora" class="form-control hora" placeholder="Hora" maxlength="5" />
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
                        <input type="text" name="local" id="local" class="form-control" placeholder="Local" <?php echo $local; ?>/>
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
                            if ($estado['idestado'] == 1) {
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
                        <option value="">Escolha primeiro o estado</option>
                        <?php
                        $result2 = $db->prepare("SELECT nome, idcidade 
	                             FROM cidade WHERE idestado = 1
	                             ORDER BY nome ASC");
                        $result2->execute();
                        while ($cidade = $result2->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <option value='<?php echo $cidade['idcidade']; ?>'><?php echo utf8_encode($cidade['nome']); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div id="mostrar-bairro" class="col-md-4" style="display: none">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <select name="bairro" id="bairro" class="ls-select" placeholder="Bairro">
                            <option value="">Escolha o bairro</option>
                            <?php
                            $result = $db->prepare("SELECT idbairro, nome FROM tb_bsc_bairro ORDER by nome");
                            $result->execute();
                            while ($value = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($bairro == $value['idbairro']) {
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
                                    ?>
                                    <option value='<?php echo $value['idusuario']; ?>'><?php echo utf8_encode($value['nome']); ?></option>
                                    <?php
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
                                <option value="2">Média</option>
                                <option value="1">Baixa</option>
                                <option value="3">Alta</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="atencao-extra">
                        <div class="col-md-6">Atenção extra a essa agenda?</div>
                        <div class="col-md-3"><span> <input type="radio" id="sim1" name="opcao1" value="1"/> <label for="sim"> SIM</label> </span> </div>
                        <div class="col-md-3"><span> <input checked="true" type="radio" id="nao1" name="opcao1" value="0"/> <label for="nao"> NÃO</label> </span> </div>
                    </div>
                </div>
                <!--<div class="col-md-5">
                    <div class="atencao-extra">
                        <div class="col-md-5">Níveis que terão acesso a este evento</div>
                        <div class="col-md-4"><span> <input checked="true" type="checkbox" id="intermediario" name="intermediario" value="2"/> <label for="intermediario"> Intermediário</label> </span> </div>
                        <div class="col-md-3"><span> <input checked="true" type="checkbox" id="basico" name="basico" value="3"/> <label for="basico"> Básico</label> </span> </div>
                    </div>
                </div>-->
            </div>

            <!-- linha -->
            <div id="obs_agenda" class="row" style="display: none">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Observacao">Observação</label>
                        <textarea id="Observacao" name="Observacao" class="form-control" placeholder="Observação"></textarea>
                    </div>
                </div>
            </div><!-- fim linha -->

            <!-- linha -->
            <div id="grupos">
                <div class="row">
                    <div class="col-md-12">
                        <div class="grupos">
                            <h3>Grupo de segmentos que receberão este evento por e-mail</h3>
                            <ul>
                                <li class="col-md-3"><input type="checkbox" id="marcar-todos" name="marcar-todos" onclick="marcar(this)"/> <label for="marcar-todos"> MARCAR TODOS </label></li>
                                <?php
                                $cont = 1;
                                $result2 = $db->prepare("SELECT IdSegmento, Descricao FROM tb_bsc_segmento ORDER BY Descricao");
                                $result2->execute(); //..........
                                while ($grupo = $result2->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                    <li class="col-md-3"><input onclick="vf(this)" type="checkbox" id="grupo" name="grupo[]" value="<?php echo $grupo['IdSegmento']; ?>" /> <label for="marcar-todos"> <?php echo utf8_encode($grupo['Descricao']); ?> </label></li>
                                    <?php
                                    $cont++;
                                }
                                ?></ul>
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
                                        <div class="col-md-3"><span><input type="radio" id="opcao2" name="opcao2" value="1"/> <label for="sim"> SIM</label></span></div>
                                        <div class="col-md-3"><span><input checked="true" type="radio" id="opcao2" name="opcao2" value="0"/> <label for="nao"> NÃO</label></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <div class="periodicidade">
                                    <div class="row">
                                        <div class="col-md-3"><span>Periodicidade</span></div>
                                        <div class="col-md-5"><input type="text" id="periodicidade" name="periodicidade" placeholder="0" class="form-control" /></div>
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
            <?php
            if ($_SESSION['acesso'] == 1) {
                echo "
                    <div class='row'>
                        <div class='col-md-12'>
                            <div id='confirmar_cor' class='confirmar'>
                                <div class='col-md-8'>
                                    Confirmar a realização desta agenda
                                </div>
                                <div class='col-md-4'>
                                    <label class='switch switch-flat'>
                                        <input onchange='confirmar_cor()' id='opcao3' name='opcao3' class='switch-input' type='checkbox' />
                                        <span class='switch-label' data-on='SIM' data-off='NÃO'></span>
                                        <span class='switch-handle'></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='clearfix'></div> ";
            }?>

            <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">buscar</button>
        </form>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/preagenda/marcar-agenda-cadastro.js"></script>

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>