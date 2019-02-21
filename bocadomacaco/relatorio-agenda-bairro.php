<?php include("topo.php") ?>
<?php include("menu-lateral.php") ?>

<?php
$contador = @$_POST["carre"];
if ($contador == "") {
    $contador = 1;
}
if (isset($_POST['carre'])) {
    $contador+=1;
    echo "<script>document.getElementById('voltar_pagina').setAttribute('onclick', 'window.history.go(-$contador)');</script>";
}
?>

<!-- Painel de Demandas -->
<div class="container conteudo">
    <form id="formulario_bairro" action="#" method="post">
        <input type="hidden" id="carre" name="carre" value="<?php echo $contador; ?>"/>
        <div class="row">
            <div class="col-md-12">
                <div class="filtro-relatorio">
                    <h2>Agendas por Bairro</h2>
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
                <th>#<th>Bairro
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

                    $sql = $db->prepare("SELECT a.contato_participante as id, c.nome FROM tb_bsc_agenda a 
						LEFT JOIN tb_bsc_contato c ON c.idcontato = a.contato_participante
						WHERE Confirmado = 1 AND a.contato_participante <> 0 $where 
						GROUP BY a.contato_participante");

                    if ($valor1 != "") {
                        $sql->bindValue($contador, $valor1);
                        $contador++;
                    }
                    if ($valor2 != "") {
                        $sql->bindValue($contador, $valor2);
                        $contador++;
                    }

                    $sql->execute();

                    $contatos = array();

                    $cont = 1;
                    while ($f = $sql->fetch(PDO::FETCH_ASSOC)) {
                        $contatos[] = $f['id'];
                        ?>
                    <th><?php echo utf8_encode($f['nome']); ?></th>
                    <?php
                }
                ?>

        <tbody>

            <?php
            $cont = 1;
            $contador = 1;
            $sql2 = $db->prepare("SELECT a.Bairro as id, bai.nome as descr FROM tb_bsc_agenda a 
					LEFT JOIN tb_bsc_bairro bai ON bai.idbairro = a.Bairro
					WHERE Confirmado = 1 $where 
					GROUP BY a.Bairro
					ORDER BY 2,1");

            if ($valor1 != "") {
                $sql2->bindValue($contador, $valor1);
                $contador++;
            }
            if ($valor2 != "") {
                $sql2->bindValue($contador, $valor2);
                $contador++;
            }

            $sql2->execute();
            while ($bairro = $sql2->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td data-th="#"><?php echo number_format($cont, 0, ',', '.') ?>
                    <td data-th="Bairro"><?php echo $bairro['descr'] != '' ? utf8_encode($bairro['descr']) : $bairro['id'] . ' - (Manual)' ?>

                        <?php
                        $seg = $bairro['id'];
                        $total_linha = 0;
                        foreach ($contatos as $c) {
                            $where_con = $c == '' ? 'contato_participante is null' : "contato_participante = ?";

                            $contador = 1;

                            $sql_cand = $db->prepare("SELECT count(*) as total"
                                    . " FROM tb_bsc_agenda a"
                                    . " WHERE Confirmado = 1 $where AND Bairro = ? AND $where_con");

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

                            if ($c != '') {
                                $sql_cand->bindValue($contador, $c);
                                $contador++;
                            }

                            $sql_cand->execute();

                            $f_cand = $sql_cand->fetch(PDO::FETCH_ASSOC);

                            $total = $f_cand['total'];
                            $total_linha += $total;
                            ?>
                        <td data-th=""><?php echo number_format($total, 0, ',', '.') ?>
                            <?php
                            $cont++;
                        }
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

<script type="text/javascript" src="js/relatorio/relatorio-bairro.js"></script>

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

