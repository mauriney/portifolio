<?php
include("topo.php");
include_once('utils/carro/funcoes.php');
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
    <form id="formulario_imprimir" action="imprimir-carro-painel.php" method="post" target="_blank">
        <div class="botoes-acao">
            <a href="carro-cadastro.php" title="Adicionar" class="adicionar"></a>
            <a id="mostrar-busca" href="#" title="Filtrar" class="filtrar"></a>
            <a title="Imprimir" class="imprimir" style="cursor: pointer" onclick="document.getElementById('formulario_imprimir').submit();
                    return false;"></a>
        </div>
        <br/>
        <div id="filtro" class="filtro" style="display: none">
            <!-- linha -->
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="inicio">Início</label>
                        <input onchange="pesquisa()" type="text" name="inicio" id="inicio" class="form-control data" placeholder="Início" />
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fim">Fim</label>
                        <input onchange="pesquisa()" type="text" name="fim" id="fim" class="form-control data" placeholder="Fim" />
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="status">Veículo</label>
                        <select onchange="pesquisa()" name="veiculo" id="veiculo" class="ls-select">
                            <option value="">Escolha o veículo</option>
                            <?php
                            $result3 = $db->prepare("SELECT v.id, v.placa, v.modelo
                             FROM x_veiculo_saida vs
                             LEFT JOIN x_veiculo AS v ON v.id = vs.veiculo_id 
                             GROUP BY v.modelo, v.placa
                             ORDER BY v.modelo ASC");
                            $result3->execute();
                            while ($veiculo = $result3->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo $veiculo['id']; ?>'><?php echo $veiculo['placa'] . " - " . ctexto(utf8_encode($veiculo['modelo'])); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="motorista">Motorista</label>
                        <select onchange="pesquisa()" name="motorista" id="motorista" class="ls-select">
                            <option value="">Escolha o motorista</option>
                            <?php
                            $result4 = $db->prepare("SELECT u.IdUsuario, u.Nome 
                                       FROM x_veiculo_saida vs 
                                       LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = vs.motorista_id 
                                       GROUP BY u.Nome
                                       ORDER BY u.Nome ASC");
                            $result4->execute();
                            while ($motorista = $result4->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value='<?php echo $motorista['IdUsuario']; ?>'><?php echo ctexto(utf8_encode($motorista['Nome'])); ?></option>
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
                    <th>#<th>Motorista<th>Veículo<th>Data Saída<th>Data Prevista Chegada<th>Data Chegada<th>
            <tbody id="pagina">
                <?php
                $cont = 1;
                $sql = $db->prepare("SELECT s.status AS status_chegada, s.id AS saida_id, s.data_chegada, s.hora_chegada, s.data_prevista, s.hora_prevista, s.data_saida, s.hora_saida, v.modelo, u.Nome AS motorista, v.placa 
				FROM x_veiculo_saida s
                                LEFT JOIN x_veiculo AS v ON v.id = s.veiculo_id 
                                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = s.motorista_id 
				WHERE v.status = 1
				ORDER BY v.modelo ASC");
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
                        <td data-th="Motorista"><?php echo utf8_encode($linha['motorista']); ?>
                        <td data-th="Veiculo"><?php echo utf8_encode($linha['placa']) . " - " . utf8_encode($linha['modelo']); ?>
                        <td data-th="Hora"><?php echo obterDataBRTimestamp($linha['data_saida']) . " ÀS " . $linha['hora_saida']; ?>
                        <td data-th="Prevista"><?php echo obterDataBRTimestamp($linha['data_prevista']) . " ÀS " . $linha['hora_prevista']; ?>
                        <td data-th="Chegada">
                            <?php
                            if ($linha['status_chegada'] == 2) {
                                echo obterDataBRTimestamp($linha['data_chegada']) . " ÀS " . $linha['hora_chegada'];
                            } else {
                                if (vf_data_saida(obterDataBRTimestamp($linha['data_saida']), $linha['hora_saida'])) {
                                    ?>
                                    <button onclick="confirmar_chegada('<?php echo utf8_encode($linha['motorista']); ?>', '<?php echo utf8_encode($linha['modelo']); ?>', '<?php echo utf8_encode($linha['placa']); ?>',<?php echo $linha['saida_id']; ?>)" type="button" name="chegada" id="chegada" class="btn-primary">Confirmar Chegada</button>
                                    <?php
                                } else {
                                    ?>
                                    <button disabled="true" type="button" name="chegada" id="chegada" class="btn-group">Confirmar Chegada</button>
                                    <?php
                                }
                            }
                            ?>
                        <td data-th="Link">
                            <?php
                            if ($linha['status_chegada'] == 1) {
                                ?>
                                <a href="carro-cadastro.php?id=<?php echo $linha['saida_id']; ?>" title="Editar Saída" class="visualizar">Editar Saída</a>
                                <?php
                            }
                            ?>
                        </td>
                        <?php
                        $cont++;
                        ?>
                        <?php
                        $codigo = "SELECT s.status AS status_chegada, s.id AS saida_id, s.data_chegada, s.hora_chegada, s.hora_prevista, s.data_prevista, s.data_saida, s.hora_saida, v.modelo, u.Nome AS motorista, v.placa 
				FROM x_veiculo_saida s
                                LEFT JOIN x_veiculo AS v ON v.id = s.veiculo_id 
                                LEFT JOIN tb_bsc_usuario AS u ON u.IdUsuario = s.motorista_id 
				WHERE v.status = 1
				ORDER BY v.modelo ASC";
                        ?>
                <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>"/>
                <?php
            }
            ?>
        </table>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/carro/carro-painel.js"></script>

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

