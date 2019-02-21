<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
if (@is_numeric($_GET['id']) && antiSQL($_GET['id'] != "Error")) {
    $id = $_GET['id'];
    $result = $db->prepare("SELECT * 
             FROM tb_bsc_contato 
             WHERE idcontato = ? GROUP BY idcontato");
    $result->bindValue(1, $id);
    $result->execute();
    $dados_contato = $result->fetch(PDO::FETCH_ASSOC);
    $contato_id = $dados_contato['idcontato'];
    $nome = utf8_encode($dados_contato['nome']);
    $dia = $dados_contato['dia'];
    $mes = $dados_contato['mes'];
    $instituicao = utf8_encode($dados_contato['instituicao']);
    $departamento = utf8_encode($dados_contato['departamento']);
    $cargo = utf8_encode($dados_contato['cargo']);
    $email = $dados_contato['email'];
    $observacoes = utf8_encode($dados_contato['obs']);
    $referencia = $dados_contato['referencia'];
    $celular_principal = $dados_contato['celular_principal'];
    //INFORMAÇÕES DE ENDEREÇO
    $result2 = $db->prepare("SELECT * FROM tb_bsc_endereco WHERE idcontato = ?");
    $result2->bindValue(1, $id);
    $result2->execute();
    $dados_endereco = $result2->fetch(PDO::FETCH_ASSOC);
    $cep = $dados_endereco['cep'];
    $endereco = $dados_endereco['endereco'];
    $numero = $dados_endereco['numero'];
    $complemento = $dados_endereco['complemento'];
    $bairro = $dados_endereco['bairro'];
    $municipio_id = $dados_endereco['idcidade'];
    $municipio_nome = nome_cidade_id($municipio_id);
    $estado_id = estado_municipio($municipio_id);
    $tipo = $dados_endereco['tipo'];
} else {
    $contato_id = "";
    $celular_principal = "";
    $nome = "";
    $dia = "";
    $mes = "";
    $instituicao = "";
    $departamento = "";
    $cargo = "";
    $email = "";
    $observacoes = "";
    //INFORMAÇÕES DE ENDEREÇO
    $cep = "";
    $endereco = "";
    $numero = "";
    $complemento = "";
    $bairro = "";
    $municipio_id = "";
    $municipio_nome = "";
    $estado_id = "";
    $tipo = "";
    $referencia = "";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <div class="cadastro">
        <form id="form_contato" action="" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo $contato_id ?>"/>
            <!-- linha -->
            <h2>Informações Básicas</h2>

            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <div id="div_nome">
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" value="<?php echo $nome; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="celular_principal">Telefone Celular Principal</label>
                        <div id="div_celular_principal">
                            <input type="text" name="celular_principal" id="celular_principal" class="form-control" placeholder="Celular" value="<?php echo $celular_principal; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="dia">Dia de Nascimento</label>
                        <div id="div_dia">
                            <input type="text" name="dia" id="dia" class="form-control" placeholder="Dia" maxlength="2" value="<?php echo $dia; ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="mes">Mês de Nascimento</label>
                        <div id="div_mes">
                            <input type="text" name="mes" id="mes" class="form-control" placeholder="Mês" maxlength="2" value="<?php echo $mes; ?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="<?php echo $email; ?>"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="referencia">Referência</label>
                        <div id="div_referencia">
                            <select name="referencia" id="referencia" class="ls-select" placeholder="Referência de Contato">
                                <option value="">Escolha a referência</option>
                                <?php
                                $result = $db->prepare("SELECT idcontato, nome 
                             FROM tb_bsc_contato
                             ORDER BY nome ASC");
                                $result->execute();
                                while ($contato = $result->fetch(PDO::FETCH_ASSOC)) {
                                    if ($contato_id != $contato['idcontato']) {
                                        if ($contato['celular_principal'] == "") {
                                            $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
                                            $sql_tel->bindValue(1, $contato['idcontato']);
                                            $sql_tel->execute();
                                            $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);
                                            $telefone = $dados_tel['telefone'];
                                        } else {
                                            $telefone = $contato['celular_principal'];
                                        }

                                        if ($referencia == $contato['idcontato']) {
                                            ?>
                                            <option selected="true" value='<?php echo $contato['idcontato']; ?>'><?php echo utf8_encode($contato['nome']); ?> - <?php echo $telefone; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value='<?php echo $contato['idcontato']; ?>'><?php echo utf8_encode($contato['nome']); ?> - <?php echo $telefone; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

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
                            if ($estado_id == $estado['idestado']) {
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
                    <select id="municipio" name="municipio" placeholder="Município" class="ls-select">
                        <option value="">Escolha primeiro o estado</option>
                        <?php
                        if (is_numeric($municipio_id) && $municipio_nome != "") {
                            ?>
                            <option selected="true" value="<?php echo $municipio_id; ?>"><?php echo utf8_encode($municipio_nome); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Bairro" value="<?php echo $bairro; ?>"/>
                    </div>
                </div>
            </div>

            <!-- linha -->
            <div id="grupos">
                <div class="row">
                    <div class="col-md-12">
                        <div class="grupos">
                            <h3>Segmento(s)</h3>
                            <ul>
                                <li class="col-md-3"><input type="checkbox" id="marcar-todos" name="marcar-todos" onclick="marcar(this)" /> <label for="marcar-todos"> MARCAR TODOS </label></li>
                                <?php
                                $vf_checados = true;
                                $check = "";
                                $result2 = $db->prepare("SELECT IdSegmento, Descricao 
                                             FROM tb_bsc_segmento
                                             ORDER BY Descricao");
                                $result2->execute();
                                while ($grupo = $result2->fetch(PDO::FETCH_ASSOC)) {
                                    if (is_numeric(pesquisa2("idcontato", "tb_bsc_segmento_grupo", "idcontato = ?", $contato_id, "AND idsegmento = ?", $grupo['IdSegmento'], "", "", "", "", ""))) {
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
                            <div id="div_segmento"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <h2>Telefone(s)</h2>

            <?php
            $posicao = 0;
            $residencial = "";
            $comercial = "";
            $celu = "";
            $assistente = "";
            $outros = "";
            $result5 = $db->prepare("SELECT * FROM tb_bsc_telefone WHERE idcontato = ?");
            $result5->bindValue(1, $contato_id);
            $result5->execute();

            if ($result5->rowCount() == 0) {
                ?>
                <table id="origem" class="tabela tb-telefones">
                    <tbody>
                        <tr>
                            <td data-th="">
                                <div class="form-group">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" name="telefone[]" id="telefone" class="form-control" placeholder="Telefone" />
                                </div>
                            <td data-th="">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <select id="telefone_tipo" name="telefone_tipo[]" class="form-control">
                                        <option value="1">Residencial</option>
                                        <option value="2">Comercial</option>
                                        <option value="3">Celular</option>
                                        <option value="4">Assistente</option>
                                        <option value="5">Outros</option>
                                    </select>
                                </div>
                            <td data-th="">
                                <ul class="links tel-links">
                                    <li><a class="add-lista" title="Agendar" style="cursor: pointer" onclick="duplicarCampos();">Adicionar</a></li>
                                    <li><a class="excluir-lista" title="Excluir" style="cursor: pointer" onclick="removerCampos(this);">Excluir</a></li>
                                </ul>
                </table>
                <?php
            }
            ?>

            <div id="destino">
                <?php
                while ($telefone = $result5->fetch(PDO::FETCH_ASSOC)) {

                    if ($telefone['tipo'] == 1) {
                        $residencial = "selected='true'";
                    } else if ($telefone['tipo'] == 2) {
                        $comercial = "selected='true'";
                    } else if ($telefone['tipo'] == 3) {
                        $celu = "selected='true'";
                    } else if ($telefone['tipo'] == 4) {
                        $assistente = "selected='true'";
                    } else if ($telefone['tipo'] == 5) {
                        $outros = "selected='true'";
                    }
                    ?>
                    <table id="origem" class="tabela tb-telefones">
                        <tbody>
                            <tr>
                                <td data-th="">
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" name="telefone[]" id="telefone" class="form-control" placeholder="Telefone" value="<?php echo $telefone['telefone']; ?>"/>
                                    </div>
                                <td data-th="">
                                    <div class="form-group">
                                        <label for="tipo">Tipo</label>
                                        <select id="telefone_tipo" name="telefone_tipo[]" class="form-control">
                                            <option <?php echo $residencial; ?> value="1">Residencial</option>
                                            <option <?php echo $comercial; ?> value="2">Comercial</option>
                                            <option <?php echo $celu; ?> value="3">Celular</option>
                                            <option <?php echo $assistente; ?> value="4">Assistente</option>
                                            <option <?php echo $outros; ?> value="5">Outros</option>
                                        </select>
                                    </div>
                                <td data-th="">
                                    <ul class="links tel-links">
                                        <li><a class="add-lista" title="Agendar" style="cursor: pointer" onclick="duplicarCampos();">Adicionar</a></li>
                                        <li><a class="excluir-lista" title="Excluir" style="cursor: pointer" onclick="removerCampos(this);">Excluir</a></li>
                                    </ul>
                    </table>
                    <?php
                    $posicao++;
                    $residencial = "";
                    $comercial = "";
                    $celu = "";
                    $assistente = "";
                    $outros = "";
                }
                ?>

            </div>

            <h2>Informações Profissionais</h2>

            <!-- linha -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="instituicao">Instituição</label>
                        <input type="text" name="instituicao" id="instituicao" class="form-control" placeholder="Instituição" value="<?php echo $instituicao; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="departamento">Departamento</label>
                        <input type="text" name="departamento" id="departamento" class="form-control" placeholder="Departamento" value="<?php echo $departamento; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cargo">Cargo</label>
                        <input type="text" name="cargo" id="cargo" class="form-control" placeholder="Cargo" value="<?php echo $cargo; ?>"/>
                    </div>
                </div>
            </div>
            <!-- linha -->

            <h2>Endereço(s)</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control" placeholder="CEP" maxlength="10" value="<?php echo $cep; ?>"/>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Endereço" value="<?php echo $endereco; ?>"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" name="numero" id="numero" class="form-control" placeholder="Número" value="<?php echo $numero; ?>"/>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" placeholder="Complemento" value="<?php echo $complemento; ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="tipo">Tipo </label>
                    <select id="tipo" name="tipo" placeholder="Selecione o Tipo de Endereço" class="ls-select">
                        <option value="">Selecione o Tipo de Endereço</option>
                        <?php
                        if ($tipo == 2) {
                            ?>
                            <option value="1">Residencial</option>
                            <option selected="true" value="2">Comercial</option>
                            <?php
                        } else if ($tipo == 1) {
                            ?>
                            <option selected="true" value="1">Residencial</option>
                            <option value="2">Comercial</option>
                            <?php
                        } else {
                            ?>
                            <option value="1">Residencial</option>
                            <option value="2">Comercial</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!-- linha -->

            <h2>Outras Informações</h2>
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" placeholder="Observações"><?php echo $observacoes; ?></textarea>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="clearfix"></div>

            <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-enviar">buscar</button>
        </form>
    </div>
</div>
<?php include("rodape.php") ?>
<script type="text/javascript" src="js/contato/contato-cadastro.js"></script>
<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>