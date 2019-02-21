<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<!-- Painel de Demandas -->
<div class="container conteudo">

    <div class="botoes-acao">
        <a href="contato-cadastro.php" title="Adicionar" class="adicionar"></a>
        <a title="Imprimir" class="imprimir" style="cursor: pointer" onclick="document.getElementById('formulario_imprimir').submit();
                return false;"></a>
    </div>
    <div id="filtro" class="filtro">
        <form action="" method="post">
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <ul class="filtro-aniversario">
                        <li class="col-md-2"><a onclick="pesquisa(0)" id="aniv_todos" href="#" title="Todos">Todos</a></li>
                        <li class="col-md-2"><a onclick="pesquisa(1)" id="aniv_outro" href="#" title="Hoje">HOJE</a></li>
                        <li class="col-md-2"><a onclick="pesquisa(2)" id="aniv_outro" href="#" title="Prioritários">SEMANA</a></li>
                        <li class="col-md-2"><a onclick="pesquisa(3)" id="aniv_outro" href="#" title="Comuns">MÊS</a></li>
                        <li class="col-md-2"><a onclick="pesquisa(4)" id="aniv_outro" href="#" title="Comuns">ANO</a></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <form id="formulario_imprimir" action="imprimir-aniversario-painel.php" method="post" target="_blank">
        <table class="tabela tb-contato">
            <thead>
                <tr>
                    <th>Nome<th>Dia de Nascimento<th>Mês de Nascimento<th>E-mail<th>
            <tbody id="pagina">
                <?php
                $codigo = "SELECT idcontato, nome, email, diia, mes
		FROM tb_bsc_contato
		ORDER BY nome";
                ?>
            <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>"/>

            <?php
            $grupos = "";
            $cont = 1;
            $sql = $db->prepare("SELECT idcontato, nome, email, dia, mes
		FROM tb_bsc_contato
		ORDER BY nome");
            $sql->execute();
            while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td data-th="Nome"><?php echo utf8_encode($linha['nome']); ?>
                    <td data-th="Data"><?php echo $linha['dia']; ?>
                    <td data-th="Mes"><?php echo getMes($linha['mes']); ?>
                    <td data-th="E-mail"><?php echo $linha['email']; ?>
                    <td data-th="Link"><a href="contato-detalhe.php?id=<?php echo $linha['idcontato']; ?>" title="Visuzalizar" class="visualizar">Visualizar</a>
                        <?php
                    }
                    ?>
        </table>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/aniversariante/aniversario-painel.js"></script>

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

