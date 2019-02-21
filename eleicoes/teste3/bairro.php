<?php include 'template/top1.php' ?>
<?php include 'template/menu_topo1.php' ?>

<link rel="stylesheet" href="docs/style.css">

<!-- Início do Corpo do Site -->
<div class="content">
    <h1 class="county">Rio Branco</h1>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Bairro</th>
                            <th>Aptos</th>
                            <th>Seção</th>
                            <th class="candidate"><span><img src="assets/img/marcus.jpg" width="25px" alt=""> Marcus A.</span></th>
                            <th class="candidate"><span><img src="assets/img/gladson.jpg" width="25px" alt=""> Gladson C.</span></th>
                            <th class="candidate"><span><img src="assets/img/ullysses.jpg" width="25px" alt=""> C. Ulysses</span></th>
                            <th class="candidate"><span><img src="assets/img/janaina.jpg" width="25px" alt=""> Janaína F.</span></th>
                            <th class="candidate"><span><img src="assets/img/david.jpg" width="25px" alt=""> David H.</span></th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                	   <?php
$result = $db->prepare("SELECT s.id, s.bairro, SUM(r.aptos) AS aptos    
                        FROM secao s
                        LEFT JOIN resultado AS r ON r.zona = s.zona_id AND r.secao = s.secao_numero 
                        GROUP BY s.bairro
                        ORDER BY s.bairro ASC");
                        $result->execute();
                        while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
                            $g_13 = buscar_votos($rs['bairro'], 13);
                            $g_11 = buscar_votos($rs['bairro'], 11);
                            $g_17 = buscar_votos($rs['bairro'], 17);
                            $g_18 = buscar_votos($rs['bairro'], 18);
                            $g_70 = buscar_votos($rs['bairro'], 70);
                            $soma = ($g_13 + $g_11 + $g_17 + $g_18 + $g_70);
						?>
                        <tr>
                            <td><?= $rs['bairro']; ?></td>
                            <td class="text-center"><?= $rs['aptos']; ?></td>
                            <td><?= buscar_secao($rs['bairro']); ?></td>
                            <td class="text-center"><?= $g_13; ?></td>
                            <td class="text-center"><?= $g_11; ?></td>
                            <td class="text-center"><?= $g_17; ?></td>
                            <td class="text-center"><?= $g_18; ?></td>
                            <td class="text-center"><?= $g_70; ?></td>
                            <td class="text-center"><?= $soma; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Fim do Corpo do Site -->

<?php include 'template/footer1.php' ?>

<script type="text/javascript" src="docs/app.js"></script>
