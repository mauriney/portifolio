<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (is_numeric(@$_GET['id']) && antiSQL(@$_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT atencao, Assunto, IdPreAgenda, Nome, TelefoneCel, Email, IdPrioridade, prazo, IdSegmento, data_cadastro"
            . " FROM tb_bsc_preagenda"
            . " WHERE IdPreAgenda = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_preagenda = $result->fetch(PDO::FETCH_ASSOC);

    $pre_id = $dados_preagenda['IdPreAgenda'];
    $nome = $dados_preagenda['Nome'];
    $prioridade = $dados_preagenda['IdPrioridade'];
    $segmento = $dados_preagenda['IdSegmento'];
    $data_cadastro = obterDataBRTimestamp($dados_preagenda['data_cadastro']);
    $prazo = obterDataBRTimestamp($dados_preagenda['prazo']);
    $celular = $dados_preagenda['TelefoneCel'];
    $email = $dados_preagenda['Email'];
    $Assunto = $dados_preagenda['Assunto'];
    $opcao = $dados_preagenda['atencao'];
} else {
    $pre_id = "";
    $nome = "";
    $prioridade = "";
    $segmento = "";
    $data_cadastro = "";
    $prazo = "";
    $celular = "";
    $email = "";
    $Assunto = "";
    $opcao = 0;
}
?>

<div class="container conteudo">
    <form id="form_preagenda" action="#" method="post">

        <input type="hidden" id="id" name="id" value="<?php echo $pre_id ?>"/>

        <!-- linha -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <div id="div_nome">
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" value="<?php echo $nome; ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="celular">Celular</label>
                    <div id="div_celular">
                        <input type="text" name="celular" id="celular" class="form-control" placeholder="Celular" value="<?php echo $celular; ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div id="div_email">
                        <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?php echo $email; ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="assunto">Assunto</label>
                    <div id="div_assunto">
                        <textarea id="assunto" name="assunto" class="form-control" placeholder="Assunto"><?php echo $Assunto; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="segmento">Segmento</label>
                    <div id="div_segmento">
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
                                    <option selected="true" label='<?php echo $value['Descricao']; ?>' value='<?php echo $value['IdSegmento']; ?>'><?php echo utf8_encode($value['Descricao']); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option label='<?php echo $value['Descricao']; ?>' value='<?php echo $value['IdSegmento']; ?>'><?php echo utf8_encode($value['Descricao']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-3">
                <div id="prioridade_cor" class="form-group prioridade baixa">
                    <label for="prioridade" class="control-label col-md-4" style="text-align: left;">Prioridade</label>
                    <div class="col-md-8">
                        <select onchange="cores($(this).val())" name="prioridade" id="prioridade" class="form-control">
                            <?php
                            if ($prioridade == 2) {
                                ?>
                                <option value="1">Baixa</option>
                                <option selected="true" value="2">Média</option>
                                <option value="3">Alta</option>
                                <?php
                            } else if ($prioridade == 3) {
                                ?>
                                <option value="1">Baixa</option>
                                <option value="2">Média</option>
                                <option  selected="true" value="3">Alta</option>
                                <?php
                            } else {
                                ?>
                                <option value="2">Média</option>
                                <option value="1">Baixa</option>
                                <option value="3">Alta</option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group prazo">
                    <label for="prazo" class="control-label col-md-3" style="text-align: left;">Prazo</label>
                    <div class="col-md-9">
                        <input type="text" name="prazo" id="prazo" class="form-control" value="<?php echo $prazo; ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group atencao">
                    <div class="row">
                        <div class="col-md-6"><label for="atenção" style="text-align: left;">Atenção extra a esse evento?</label></div>
                        <div class="col-md-3">
                            <?php
                            if ($opcao == 1) {
                                ?>
                                <input checked="true" type="radio" name="opcao" id="opcao" value="1"/>
                                <?php
                            } else {
                                ?>
                                <input type="radio" name="opcao" id="opcao" value="1"/>
                                <?php
                            }
                            ?>
                            <label for="sim">Sim</label>
                        </div>
                        <div class="col-md-3">
                            <?php
                            if ($opcao == 0) {
                                ?>
                                <input checked="true" type="radio" name="opcao" id="opcao" value="0"/>
                                <?php
                            } else {
                                ?>
                                <input type="radio" name="opcao" id="opcao" value="0"/>
                                <?php
                            }
                            ?>
                            <label for="nao">Não</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/preagenda/preagenda-cadastro.js"></script>
<script>
                            $(function() {
                                $('.scroll').perfectScrollbar();
                                // with vanilla JS!
                                Ps.initialize(document.getElementById('scroll'));
                            });
</script>
<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>