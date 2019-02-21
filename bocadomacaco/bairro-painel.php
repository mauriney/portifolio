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

    <form id="formulario_imprimir"  action="#" method="post" target="_blank">
        <div class="botoes-acao">
            <a id="" href="bairro-cadastro.php" title="Adicionar" class="adicionar"></a>
            <a id="mostrar-busca" href="#" title="Filtrar" class="filtrar"></a>
        </div>
        <br/>
        <div id="filtro" class="filtro" style="display: none">
            <!-- linha -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nome">Nome do Bairro</label>
                        <input onkeyup="pesquisa()" type="text" name="nome" id="nome" class="form-control" placeholder="Nome do Bairro" />
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="cadastro">
        <h1>Lista de Bairros</h1>

        <ul class="filtro-letra-bairro">
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

        <table class="tabela tb-bairro">
            <thead>
                <tr>
                    <th>Nome<th>Regional<th>
            <tbody id="pagina">
                <?php
                $sql = $db->prepare("SELECT b.nome, b.idbairro, r.nome as regional"
                        . " FROM tb_bsc_bairro b, tb_bsc_regional r"
                        . " WHERE b.regional = r.idregional"
                        . " GROUP BY b.idbairro ORDER BY b.nome ASC");
                $sql->execute();
                while ($bairro = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td data-th="Nome"><?php echo utf8_encode($bairro['nome']); ?>
                        <td data-th="Regional"><?php echo utf8_encode($bairro['regional']); ?>
                        <td data-th=""><a href="bairro-cadastro.php?id=<?php echo $bairro['idbairro']; ?>" title="Editar" class="editar-lista">Editar</a>	
                            <?php
                        }
                        ?>
        </table>
    </div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/configuracoes/bairro-painel.js"></script>

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

<!-- JS UTIL -->
<script src="utils/utils.js" type="text/javascript"></script>
<script src="utils/projeto.utils.js" type="text/javascript"></script>

