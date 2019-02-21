<?php
include("topo.php");
include_once('utils/demanda/funcoes.php');
include("menu-lateral.php");
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
    <form id="formulario_imprimir" action="imprimir-demanda-painel.php" method="post" target="_blank">
        <div class="botoes-acao">

            <?php
            //NIRO
            if ($_SESSION['acesso'] == 1) {
                echo "<a href='demanda-painel-confirmar.php' title='Confirmar Demanda' class='confirmar-demanda'> <span class='contador-demanda'>" . pesquisa2("count(id)", "x_demanda", "confirmacao = ?", 0, "", "", "", "", "", "", "") . "</span></a>";
            }
                echo "<a href='demanda-cadastro.php' title='Adicionar' class='adicionar'></a>";

            ?>

            <a id="mostrar-busca" href="#" title="Filtrar" class="filtrar"></a>
            <a title="Imprimir" class="imprimir" style="cursor: pointer" onclick="document.getElementById('formulario_imprimir').submit();
                    return false;"></a>
        </div>
        <br/>
        <div id="filtro" class="filtro" style="display: none">
            <!-- linha -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nome">Demanda</label>
                        <input onkeyup="pesquisa()" type="text" name="nome" id="nome" class="form-control" />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select onchange="pesquisa()" name="status" id="status" class="ls-select" placeholder="Status">
                            <option value="2">Não Inciado</option>
                            <option value="0">Em Andamento</option>
                            <option value="3">Concluído</option>
                            <option value="4">Sem Solução</option>
                            <option value="1">Cancelado</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="situacao">Situação</label>
                        <select onchange="pesquisa()" name="situacao" id="situacao" class="ls-select" placeholder="Situação">
                            <option value="no-prazo">No Prazo</option>
                            <option value="concluido">Concluído</option>
                            <option value="atrasado">Atrasado</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- linha -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="responsavel">Responsável</label>
                        <select onchange="pesquisa()" name="responsavel" id="responsavel" class="ls-select" placeholder="Responsável">
                            <?php
                            $result3 = $db->prepare("SELECT nome, IdUsuario 
                             FROM tb_bsc_usuario
                             ORDER BY nome ASC");
                            $result3->execute();
                            while ($responsavel = $result3->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo $responsavel['IdUsuario']; ?>'><?php echo ctexto(utf8_encode($responsavel['nome'])); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inicio">Início</label>
                        <input onchange="pesquisa()" type="text" name="inicio" id="inicio" class="form-control data" placeholder="Início" />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fim">Fim</label>
                        <input onchange="pesquisa()" type="text" name="fim" id="fim" class="form-control data" placeholder="Fim" />
                    </div>
                </div>
            </div>

        </div>

        <table class="tabela">
            <thead>
                <tr>
                    <th>#<th>Demanda<th>Responsável<th>Prazo<th>Status<th>Situação<th>
            <tbody id="pagina">
                <?php
                $cont = 1;
                $sql = $db->prepare("SELECT DATEDIFF(prazo, NOW()) as diasvencimento, id, demanda, prazo, status
				FROM x_demanda
				WHERE status = 0 AND confirmacao = 1 OR status = 2 AND confirmacao = 1 OR status = 3 AND confirmacao = 1 AND DATEDIFF(prazo, NOW()) >= 0
				ORDER BY diasvencimento, status ASC");
                $sql->execute();
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                    if ($_SESSION['acesso'] == 1 || responsavel_demanda_id($linha['id'], $_SESSION['id'])) {

                        $responsavel = responsavel_demanda($linha['id']);
                        ?>
                        <tr>
                            <td data-th="#">
                                <?php
                                if ($cont < 10) {
                                    echo "0" . $cont;
                                } else {
                                    echo $cont;
                                }
                                ?>
                            <td data-th="Demanda"><?php echo utf8_encode($linha['demanda']); ?>
                            <td data-th="Responsável"><?php echo $responsavel; ?>
                            <td data-th="Prazo"><?php echo obterDataBRTimestamp($linha['prazo']); ?>
                            <td data-th="Status"><span class="status"><?php echo status_demanda($linha['status']); ?></span>
                            <td data-th="Situação"><span class="situacao <?php echo situacao_demanda2($linha['prazo'], $linha['status']); ?>"><?php echo situacao_demanda($linha['prazo'], $linha['status']); ?></span>
                            <td data-th="Link"><a href="demanda-detalhe.php?id=<?php echo $linha['id']; ?>" title="Visuzalizar" class="visualizar">Visualizar</a>
                                <?php
                                $cont++;
                            }
                            ?>
                            <?php
                            $codigo = "SELECT DATEDIFF(prazo, NOW()) as diasvencimento, id, demanda, prazo, status
				FROM x_demanda
				WHERE status = 0 AND confirmacao = 1 OR status = 2 AND confirmacao = 1 OR status = 3 AND confirmacao = 1 AND DATEDIFF(prazo, NOW()) >= 0
				ORDER BY diasvencimento, status ASC";
                            ?>
                            <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>"/>
                            <?php
                        }
                        ?>
        </table>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/demanda/demanda-painel.js"></script>

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

