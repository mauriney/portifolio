<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
$sql = $db->prepare("SELECT * FROM tb_bsc_agenda WHERE Confirmado = 1 ORDER BY DataAgenda, HoraAgenda");
$sql->execute();
$num = $sql->rowCount();
$f = $sql->fetch(PDO::FETCH_ASSOC);
$data = data_volta($f['DataAgenda']);
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <form id="formulario_quantidade_agendas" action="imprimir-quantidade-agendas.php" method="post" target="_blank">
        <div class="row">
            <div class="col-md-3">
                <ul>
                    <li>TOTAL</li>
                    <li><?php echo number_format($num, 0, ',', '.') ?></li>
                    <li>AGENDAS</li>
                    <li>desde <?php echo $data ?></li>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="filtro-relatorio">
                    <h2>Quantidade de Agendas</h2>
                    <h3>Filtro por Período</h3>
                    <!-- linha -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="inicio" id="inicio" class="form-control data" placeholder="Início" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="fim" id="fim" class="form-control data" placeholder="Fim" />
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-buscar">Filtrar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/relatorio/relatorio-quantidade-agendas.js"></script>

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

