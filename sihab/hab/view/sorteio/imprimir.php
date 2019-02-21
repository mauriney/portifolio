<?php
@session_start();
include_once ('conf/config.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

$sorteio_loteamentos = "";

$sql = $db->query("SELECT sl.nome AS loteamento
                   FROM sort_candidato_casa scc
                   LEFT JOIN sort_casa AS sc ON sc.id = scc.casa_id
                   LEFT JOIN snch_loteamento AS sl ON sl.id = sc.loteamento_id
                   LEFT JOIN hab_candidato AS hc ON hc.id = scc.candidato_id
                   LEFT JOIN hab_pessoa AS hb ON hb.id = hc.hab_pessoa_id
                   WHERE 1 AND DAY(scc.data_cadastro) = DAY(NOW()) AND MONTH(scc.data_cadastro) = MONTH(NOW()) AND YEAR(scc.data_cadastro) = YEAR(NOW())
                   GROUP BY sl.id 
                   ORDER BY sl.nome, hb.nome ASC");
while ($loteamento = $sql->fetch(PDO::FETCH_ASSOC)) {
  $sorteio_loteamentos .= "" . $loteamento['loteamento'] . ", ";
}

$tamanho = strlen($sorteio_loteamentos);
@$sorteio_loteamentos[$tamanho - 2] = "";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SORTEIO .::. SECRETARIA DE ESTADO DE HABITAÇÃO - SEHAB</title>
        <!-- STYLE CSS -->
        <link href="<?= PORTAL_URL; ?>assets/fontes/stylesheet.css" rel="stylesheet"/>
        <!-- CSS -->
        <link href="<?= PORTAL_URL; ?>assets/css/app.min.1.css" rel="stylesheet"/>
        <link href="<?= PORTAL_URL; ?>assets/css/app.min.2.css" rel="stylesheet"/>
    </head>
    <body>
        <div id="tabela_impressao">

            <form id="form_sortear" name="form_sortear" action="#" method="post">
                <section class="content">
                    <div class="row">
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            
                            <br>
                            
                            <img src="<?= PORTAL_URL; ?>assets/img/sihab_color.svg" class="img-fundo blur" alt="" style="width: 100%; height: 70px"/>

                            <h1 class="text-center"><?= $sorteio_loteamentos; ?></h1>

                            <h2 class="text-center">SORTEIO REALIZADO EM <?= date('d/m/Y'); ?></h2>
                            
                            <br>

                            <div class="card candidato_table">
                                <div class="table-responsive">
                                    <table id="data-table-basic" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th data-column-id="#" data-type="numeric">#</th>
                                                <th data-column-id="nome">Nome</th>
                                                <th data-column-id="cpf">CPF</th>
                                                <th data-column-id="endereco">Endereço</th>
                                                <th data-column-id="loteamento">Loteamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cont = 0;
                                            $sql = $db->query("SELECT hb.nome AS pessoa, hb.cpf, sc.nome AS casa, sc.endereco, sc.numero, sl.nome AS loteamento
                                                              FROM sort_candidato_casa scc
                                                              LEFT JOIN sort_casa AS sc ON sc.id = scc.casa_id
                                                              LEFT JOIN snch_loteamento AS sl ON sl.id = sc.loteamento_id
                                                              LEFT JOIN hab_candidato AS hc ON hc.id = scc.candidato_id
                                                              LEFT JOIN hab_pessoa AS hb ON hb.id = hc.hab_pessoa_id
                                                              WHERE 1 AND DAY(scc.data_cadastro) = DAY(NOW()) AND MONTH(scc.data_cadastro) = MONTH(NOW()) AND YEAR(scc.data_cadastro) = YEAR(NOW())
                                                              ORDER BY sl.nome, hb.nome ASC");
                                            while ($sorteado = $sql->fetch(PDO::FETCH_ASSOC)) {
                                              $cont++;
                                              ?>
                                              <tr>
                                                  <td><?= $cont; ?></td>
                                                  <td><?= $sorteado['pessoa']; ?></td>
                                                  <td><?= $sorteado['cpf']; ?></td>
                                                  <td><?= $sorteado['endereco'] . ", nº " . ($sorteado['numero'] < 10 ? "0" . $sorteado['numero'] : $sorteado['numero']); ?></td>
                                                  <td><?= $sorteado['loteamento']; ?></td>
                                              </tr>
                                              <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </body>
</html>

<script type="text/javascript">
  window.print();
  window.close();
</script>