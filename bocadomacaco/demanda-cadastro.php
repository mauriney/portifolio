<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && @antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT quemindicou, segmento, solicitante, celular, email, prazo, id, demanda, prioridade, cidade_id, data_cadastro 
             FROM x_demanda 
             WHERE id = ?");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_demanda = $result->fetch(PDO::FETCH_ASSOC);

    $demanda_id = $dados_demanda['id'];
    $demanda = utf8_encode($dados_demanda['demanda']);
    $prioridade = $dados_demanda['prioridade'];
    $segmento = $dados_demanda['segmento'];
    $municipio_id = $dados_demanda['cidade_id'];
    $estado_id = estado_municipio($municipio_id);
    $data_cadastro = obterDataBRTimestamp($dados_demanda['data_cadastro']);
    $prazo = obterDataBRTimestamp($dados_demanda['prazo']);
    $solicitante = utf8_encode($dados_demanda['solicitante']);
    $celular = $dados_demanda['celular'];
    $email = $dados_demanda['email'];
    $quemindicou = $dados_demanda['quemindicou'];
} else {
    $demanda_id = "";
    $demanda = "";
    $prioridade = "";
    $segmento = "";
    $municipio_id = "";
    $data_cadastro = "";
    $prazo = "";
    $solicitante = "";
    $celular = "";
    $email = "";
    $quemindicou = "";
}
?>

<div class="container conteudo">
    <form id="form_demanda" action="#" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo $demanda_id ?>"/>
        <!-- linha -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="demanda">Demanda</label>
                    <div id="div_demanda">
                        <textarea name="demanda" id="demanda" class="form-control" placeholder="Demanda"><?php echo $demanda; ?></textarea>
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
                            while ($seg = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($segmento == $seg['IdSegmento']) {
                                    ?>
                                    <option selected="true" label='<?php echo $seg['Descricao']; ?>' value='<?php echo $seg['IdSegmento']; ?>'><?php echo utf8_encode($seg['Descricao']); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option label='<?php echo $seg['Descricao']; ?>' value='<?php echo $seg['IdSegmento']; ?>'><?php echo utf8_encode($seg['Descricao']); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <label for="estado">Estado </label>
                <div id="div_estado">
                    <select id="estado" name="estado" placeholder="Estado" class="ls-select">
                        <option value="">Escolha o estado</option>
                        <?php
                        $result = $db->prepare("SELECT nome, idestado, sigla 
                             FROM estado
                             ORDER BY nome ASC");
                        $result->execute();
                        while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                            if ($estado_id == $estado['idestado']) {
                                ?>
                                <option selected="true" label='<?php echo $estado['sigla']; ?>' value='<?php echo $estado['idestado']; ?>'><?php echo utf8_encode($estado['nome']); ?></option>
                                <?php
                            } else if ($estado['idestado'] == 1) {
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
            </div>

            <div class="col-md-4">
                <label for="municipio">Município </label>
                <div id="div_municipio">
                    <select id="municipio" name="municipio" placeholder="Município" class="ls-select">
                        <option value="">Escolha primeiro o estado</option>
                        <?php
                        if (is_numeric($municipio_id) && $municipio_id != "") {
                            $result2 = $db->prepare("select nome, idcidade from cidade WHERE idestado = ? order by nome ASC");
                            $result2->bindValue(1, $estado_id);
                            $result2->execute();
                            while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                                if ($municipio_id == $municipio['idcidade']) {
                                    ?>
                                    <option selected="true" label='<?php echo utf8_encode($municipio['nome']); ?>' value='<?php echo $municipio['idcidade']; ?>'><?php echo utf8_encode($municipio['nome']); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option label='<?php echo utf8_encode($municipio['nome']); ?>' value='<?php echo $municipio['idcidade']; ?>'><?php echo utf8_encode($municipio['nome']); ?></option>
                                    <?php
                                }
                            }
                        } else {
                            $result2 = $db->prepare("select nome, idcidade from cidade WHERE idestado = 1 order by nome ASC");
                            $result2->execute();
                            while ($municipio = $result2->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option  label='<?php echo utf8_encode($municipio['nome']); ?>' value='<?php echo $municipio['idcidade']; ?>'><?php echo utf8_encode($municipio['nome']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <!-- linha -->
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="solicitante">Solicitante</label>
                    <input type="text" name="solicitante" id="solicitante" class="form-control" placeholder="Solicitante" value="<?php echo $solicitante; ?>"/>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="celular">Celular</label>
                    <input type="text" name="celular" id="celular" class="form-control" placeholder="Celular" value="<?php echo $celular; ?>"/>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?php echo $email; ?>"/>
                </div>
            </div>
        </div>
        <br />
        <!-- linha -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="quem_indicou">Indicador Por</label>
                    <select name="quem_indicou" id="quem_indicou" class="ls-select" placeholder="Indicador Por">
                        <option value="">Escolha quem indicou</option>
                        <?php
                        $result = $db->prepare("SELECT idcontato, nome 
                             FROM tb_bsc_contato
                             ORDER BY nome ASC");
                        $result->execute();
                        while ($contato = $result->fetch(PDO::FETCH_ASSOC)) {

                            $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
                            $sql_tel->bindValue(1, $contato['idcontato']);
                            $sql_tel->execute();
                            $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);

                            if ($quemindicou == $contato['idcontato']) {
                                ?>
                                <option selected="true" value='<?php echo $contato['idcontato']; ?>'><?php echo utf8_encode($contato['nome']); ?> - <?php echo $dados_tel['telefone']; ?></option>
                                <?php
                            } else {
                                ?>
                                <option value='<?php echo $contato['idcontato']; ?>'><?php echo utf8_encode($contato['nome']); ?> - <?php echo $dados_tel['telefone']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
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
            <div class="col-md-3">
                <div class="form-group prazo">
                    <label for="prazo" class="control-label col-md-3" style="text-align: left;">Prazo</label>
                    <div class="col-md-9">
                        <div id="div_prazo">
                            <input type="text" name="prazo" id="prazo" class="form-control" value="<?php echo $prazo; ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- linha -->
        <!--<div class="row">
            <div class="col-md-12">
                <div class="form-group responsavel">
                    <label for="responsavel">Responsável</label>
                    <div id="div_responsavel">
                        <select name="responsavel[]" id="responsavel" class="ls-select" multiple>

                            <?php
                            /*$result1 = $db->prepare("SELECT u.nome, u.IdUsuario 
                             FROM x_demanda_responsavel r
                             LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = r.responsavel_id 
                             WHERE r.demanda_id = ? AND u.demanda = 1
                             GROUP BY u.IdUsuario 
                             ORDER BY u.nome ASC");
                            $result1->bindValue(1, $demanda_id);
                            $result1->execute();
                            while ($responsavel1 = $result1->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option selected="true" label='<?php echo $responsavel1['nome']; ?>' value='<?php echo $responsavel1['IdUsuario']; ?>'><?php echo ctexto(utf8_encode($responsavel1['nome'])); ?></option>
                                <?php
                            }

                            $result2 = $db->prepare("SELECT nome, IdUsuario 
                             FROM tb_bsc_usuario 
                             WHERE demanda = 1 AND IdUsuario NOT IN (SELECT responsavel_id FROM x_demanda_responsavel WHERE demanda_id = ?)
                             GROUP BY IdUsuario 
                             ORDER BY nome ASC");
                            $result2->bindValue(1, $demanda_id);
                            $result2->execute();
                            while ($responsavel2 = $result2->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option label='<?php echo $responsavel2['nome']; ?>' value='<?php echo $responsavel2['IdUsuario']; ?>'><?php echo ctexto(utf8_encode($responsavel2['nome'])); ?></option>
                                <?php
                            }*/
                            ?>

                        </select>
                    </div>
                </div>
            </div>
        </div>-->
        <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">Enviar</button>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/demanda/demanda-cadastro.js"></script>
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