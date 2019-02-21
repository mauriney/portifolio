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
    <form id="formulario_imprimir" action="imprimir-veiculo-painel.php" method="post" target="_blank">
        <div class="botoes-acao">
            <a href="veiculo-cadastro.php" title="Adicionar" class="adicionar"></a>
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
                        <label for="modelo">Modelo</label>
                        <select onchange="pesquisa()" name="modelo" id="modelo" class="ls-select">
                            <option value="">Escolha o modelo</option>
                            <?php
                            $result3 = $db->prepare("SELECT id, modelo
				FROM x_veiculo
				WHERE status = 1
                                GROUP BY modelo
				ORDER BY modelo ASC");
                            $result3->execute();
                            while ($modelo = $result3->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo utf8_encode($modelo['modelo']); ?>'><?php echo utf8_encode($modelo['modelo']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="placa">Placa</label>
                        <select onchange="pesquisa()" name="placa" id="placa" class="ls-select">
                            <option value="">Escolha a placa</option>
                            <?php
                            $result3 = $db->prepare("SELECT placa
				FROM x_veiculo
				WHERE status = 1
                                GROUP BY placa
				ORDER BY placa ASC");
                            $result3->execute();
                            while ($placa = $result3->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo utf8_encode($placa['placa']); ?>'><?php echo $placa['placa']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cor">Cor</label>
                        <select onchange="pesquisa()" name="cor" id="cor" class="ls-select">
                            <option value="">Escolha a cor</option>
                            <?php
                            $result4 = $db->prepare("SELECT cor
				FROM x_veiculo
				WHERE status = 1
                                GROUP BY cor
				ORDER BY cor ASC");
                            $result4->execute();
                            while ($cor = $result4->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo utf8_encode($cor['cor']); ?>'><?php echo utf8_encode($cor['cor']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <table class="tabela">
            <thead>
                <tr>
                    <th style="float:left">#<th>Modelo<th>Placa<th>Cor<th>
            <tbody id="pagina">
                <?php
                $cont = 1;
                $sql = $db->prepare("SELECT id, modelo, placa, cor
				FROM x_veiculo 
				WHERE status = 1
				ORDER BY modelo ASC");
                $sql->execute();
                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
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
                        <td data-th="Modelo"><?php echo utf8_encode($linha['modelo']); ?>
                        <td data-th="Placa"><?php echo utf8_encode($linha['placa']); ?>
                        <td data-th="Cor"><?php echo utf8_encode($linha['cor']); ?>
                        <td data-th="Link"><a href="veiculo-cadastro.php?id=<?php echo $linha['id']; ?>" title="Editar Veículo" class="visualizar">Editar Veículo</a>
                            <?php
                            $cont++;
                            ?>
                            <?php
                            $codigo = "SELECT id, modelo, placa, cor
				FROM x_veiculo 
				WHERE status = 1
				ORDER BY modelo ASC";
                            ?>
                            <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>"/>
                            <?php
                        }
                        ?>
        </table>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/carro/veiculo-painel.js"></script>

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

