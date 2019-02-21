<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<!-- Painel de Demandas -->
<div class="container conteudo">

    <?php
    //CASO TENHA MENSAGEM VIA POST, ENTÃO MOSTRAR NA TELA AO USUÁRIO
    if (isset($_POST['mensagem']) && @$_SESSION['mensagem'] == "OK") {
        @$_SESSION['mensagem'] = "NO";
        echo '<div id="div_success" class="alert alert-success" style="display: none;"><button class="close" data-dismiss="alert"></button><b>Sucesso:</b> ' . $_POST['mensagem'] . '</div>';
    }
    ?>

    <div class="botoes-acao">
        <a href="contato-cadastro.php" title="Adicionar" class="adicionar"></a>
        <a id="mostrar-busca" href="#" title="Filtrar" class="filtrar"></a>
        <a title="Imprimir" class="imprimir" style="cursor: pointer" onclick="document.getElementById('formulario_imprimir').submit();
                return false;"></a>
    </div>

    <div id="filtro" class="filtro" style="display: none">
        <form action="" method="post">
            <!-- linha -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input onkeyup="pesquisa(100)" type="text" name="nome" id="nome" class="form-control" placeholder="Nome" />
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="referencia">Referência</label>
                    <div id="div_referencia">
                        <select onchange="pesquisa(100)" name="referencia" id="referencia" class="ls-select" placeholder="Referência de contato">
                            <?php
                            $result = $db->prepare("SELECT idcontato, nome 
                             FROM tb_bsc_contato
                             ORDER BY nome ASC");
                            $result->execute();
                            while ($referencia = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo $referencia['idcontato']; ?>'><?php echo utf8_encode($referencia['nome']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="estado">Estado </label>
                    <select onchange="pesquisa(100)" id="estado" name="estado" placeholder="Estado" class="ls-select">
                        <?php
                        $result = $db->prepare("SELECT nome, idestado, sigla 
                             FROM estado
                             ORDER BY nome ASC");
                        $result->execute();
                        while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <option value='<?php echo $estado['idestado']; ?>'><?php echo utf8_encode($estado['nome']); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="municipio">Município </label>
                    <select onchange="pesquisa(100)" id="municipio" name="municipio" placeholder="Município" class="ls-select">
                        <option value="">Escolha primeiro o estado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <select onchange="pesquisa(100)" id="bairro" name="bairro" placeholder="Bairro" class="ls-select">
                            <?php
                            $result = $db->prepare("SELECT bairro  
                             FROM tb_bsc_endereco WHERE bairro <> ''
                             GROUP BY bairro
                             ORDER BY bairro ASC");
                            $result->execute();
                            while ($bairro = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo utf8_encode($bairro['bairro']); ?>'><?php echo utf8_encode($bairro['bairro']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="municipio">Segmento(s)</label>
                        <select onchange="pesquisa(100)" placeholder="Segmento" name="grupos" id="grupos" class="select2-container ls-select" multiple="true">
                            <?php
                            $sqlGrupo = $db->prepare("SELECT * FROM tb_bsc_segmento ORDER BY Descricao");
                            $sqlGrupo->execute();
                            while ($fGrupo = $sqlGrupo->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value="<?php echo $fGrupo['IdSegmento'] ?>"><?php echo ctexto(utf8_encode($fGrupo['Descricao'])); ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <ul class="filtro-letra">
        <li><a href="#" onclick="pesquisa2('')">#</a></li>
        <li><a href="#" onclick="pesquisa2('A')">A</a></li>
        <li><a href="#" onclick="pesquisa2('B')">B</a></li>
        <li><a href="#" onclick="pesquisa2('C')">C</a></li>
        <li><a href="#" onclick="pesquisa2('D')">D</a></li>
        <li><a href="#" onclick="pesquisa2('E')">E</a></li>
        <li><a href="#" onclick="pesquisa2('F')">F</a></li>
        <li><a href="#" onclick="pesquisa2('G')">G</a></li>
        <li><a href="#" onclick="pesquisa2('H')">H</a></li>
        <li><a href="#" onclick="pesquisa2('I')">I</a></li>
        <li><a href="#" onclick="pesquisa2('J')">J</a></li>
        <li><a href="#" onclick="pesquisa2('K')">K</a></li>
        <li><a href="#" onclick="pesquisa2('L')">L</a></li>
        <li><a href="#" onclick="pesquisa2('M')">M</a></li>
        <li><a href="#" onclick="pesquisa2('N')">N</a></li>
        <li><a href="#" onclick="pesquisa2('O')">O</a></li>
        <li><a href="#" onclick="pesquisa2('P')">P</a></li>
        <li><a href="#" onclick="pesquisa2('Q')">Q</a></li>
        <li><a href="#" onclick="pesquisa2('R')">R</a></li>
        <li><a href="#" onclick="pesquisa2('S')">S</a></li>
        <li><a href="#" onclick="pesquisa2('T')">T</a></li>
        <li><a href="#" onclick="pesquisa2('U')">U</a></li>
        <li><a href="#" onclick="pesquisa2('V')">V</a></li>
        <li><a href="#" onclick="pesquisa2('W')">W</a></li>
        <li><a href="#" onclick="pesquisa2('Y')">Y</a></li>
        <li><a href="#" onclick="pesquisa2('X')">X</a></li>
        <li><a href="#" onclick="pesquisa2('Z')">Z</a></li>
    </ul>

    <form id="formulario_imprimir" action="imprimir-contato-painel.php" method="post" target="_blank">
        <div id="pagina">
            <?php
            $sql = $db->prepare("SELECT * FROM tb_bsc_contato ORDER BY nome ASC");
            $sql->execute();
            $qtd = $sql->rowCount();
            ?>
            <b>Total de Contatos:</b> <?php echo $qtd; ?>
            <table class="tabela tb-contato">
                <thead>
                    <tr>
                        <th>Nome<th>Contato<th>E-mail<th>Segmento(s)<th>
                <tbody>

                    <?php
                    $codigo = "SELECT * FROM tb_bsc_contato";
                    ?>
                <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>"/>

                <?php
                $grupos = "";
                $cont = 1;
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

                    $grupos = "";

                    if ($linha['celular_principal'] == "") {
                        $sql_tel = $db->prepare("SELECT telefone FROM tb_bsc_telefone WHERE idcontato = ?");
                        $sql_tel->bindValue(1, $linha['idcontato']);
                        $sql_tel->execute();
                        $dados_tel = $sql_tel->fetch(PDO::FETCH_ASSOC);
                        $telefone = $dados_tel['telefone'];
                    } else {
                        $telefone = $linha['celular_principal'];
                    }

                    $sql_grupo = $db->prepare("SELECT se.Descricao FROM tb_bsc_segmento_grupo sg 
        LEFT JOIN tb_bsc_segmento se ON se.IdSegmento = sg.idsegmento
        WHERE idcontato = ?");

                    $sql_grupo->bindValue(1, $linha['idcontato']);
                    $sql_grupo->execute();
                    $cont_grupos = 0;

                    while ($f_grupo = $sql_grupo->fetch(PDO::FETCH_ASSOC)) {

                        if ($cont_grupos == 0) {
                            $grupos .= utf8_encode($f_grupo['Descricao']) . '';
                        }

                        if ($cont_grupos == 1) {
                            $grupos .= "&nbsp;<a href='#' title='Segmento(s)' data-toggle='modal' data-target='#modal-contato-" . $linha['idcontato'] . "'><i class='fa fa-plus-circle'></i></a>";
                        }

                        $cont_grupos++;
                    }
                    ?>
                    <tr>
                        <td data-th="Nome"><?php echo utf8_encode($linha['nome']); ?>
                        <td data-th="Telefone"><span class="container-lista"><?php echo $telefone; ?></span>
                        <td data-th="E-mail"><?php echo utf8_encode($linha['email']); ?>
                        <td data-th="Grupo"><span class="container-lista"><?php echo $grupos; ?></span>
                        <td data-th="Link"><a href="contato-detalhe.php?id=<?php echo $linha['idcontato']; ?>" title="Visuzalizar" class="visualizar">Visualizar</a>
                            <?php
                        }
                        ?>
            </table>
        </div>
    </form>
</div>

<?php include("rodape.php") ?>
<?php include("modal-contato-painel.php") ?>

<script type="text/javascript" src="js/contato/contato-painel.js"></script>

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

