<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <form id="formulario_segmento" action="#" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="filtro-relatorio">
                    <h2>Agendas por Segmentos</h2>
                    <h3>Filtro por Período</h3>
                    <!-- linha -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="inicio" id="inicio" class="form-control data" placeholder="Início" value="<?php echo @$_POST['inicio']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="fim" id="fim" class="form-control data" placeholder="Fim" value="<?php echo @$_POST['fim']; ?>"/>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="enviar" id="enviar" class="btn btn-redondo btn-buscar">Filtrar</button>
                </div>
            </div>
        </div>
    </form>

    <table class="tabela tb-geral tb-segmentos">
        <thead>
            <tr>
                <th>#<th>Segmento<th>Quantidade
        <tbody>
            <?php
            $inicial = @$_POST['inicio'] != '' ? formata_data(@$_POST['inicio']) : '';
            $final = @$_POST['fim'] != '' ? formata_data(@$_POST['fim']) : '';

            $where = '';

            $contador = 1;
            $valor1 = "";
            $valor2 = "";

            if ($inicial != '') {
                if ($final != '') {
                    $where = "AND a.DataAgenda BETWEEN (?) AND (?)";
                    $valor1 = "" . convertDataBR2ISO($_POST['inicio']) . "";
                    $valor2 = "" . convertDataBR2ISO($_POST['fim']) . "";
                } else {
                    $where = "AND a.DataAgenda >= ?";
                    $valor1 = "" . convertDataBR2ISO($_POST['inicio']) . "";
                }
            } else {
                if ($final != '') {
                    $where = "AND a.DataAgenda <= ?";
                    $valor1 = "" . convertDataBR2ISO($_POST['fim']) . "";
                }
            }

            $sql2 = $db->prepare("SELECT a.IdSegmento as id, s.Descricao as descr FROM tb_bsc_agenda a 
					LEFT JOIN tb_bsc_segmento s ON s.IdSegmento = a.IdSegmento
					WHERE a.Confirmado = 1 AND s.Descricao <> '' $where 
					GROUP BY a.IdSegmento");

            if ($valor1 != "") {
                $sql2->bindValue($contador, $valor1);
                $contador++;
            }
            if ($valor2 != "") {
                $sql2->bindValue($contador, $valor2);
                $contador++;
            }

            $sql2->execute();
            $cont = 1;
            while ($segmento = $sql2->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td data-th="#"><?php echo number_format($cont, 0, ',', '.') ?>
                    <td data-th="Segmento"><?php echo $segmento['descr']; ?>
                        <?php
                        $contador = 1;
                        $seg = $segmento['id'];
                        $total_linha = 0;
                        $sql_cand = $db->prepare("SELECT count(*) as total"
                                . " FROM tb_bsc_agenda a"
                                . " WHERE a.Confirmado = 1 $where AND a.IdSegmento = ?");

                        if ($valor1 != "") {
                            $sql_cand->bindValue($contador, $valor1);
                            $contador++;
                        }
                        if ($valor2 != "") {
                            $sql_cand->bindValue($contador, $valor2);
                            $contador++;
                        }

                        $sql_cand->bindValue($contador, $seg);
                        $contador++;

                        $sql_cand->execute();
                        $total = $sql_cand->rowCount();
                        $f_cand = $sql_cand->fetch(PDO::FETCH_ASSOC);
                        ?>
                    <td data-th="Quantidade"><?php echo number_format($total, 0, ',', '.') ?>
                        <?php
                        $cont++;
                    }
                    ?>
    </table>
    <!--
            <div class="row">
                    <div class="col-md-4">TOTAL</div>
                    <div class="col-md-4">600</div>
                    <div class="col-md-4">31,5</div>
            </div>
    -->
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/relatorio/relatorio-segmento.js"></script>

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

