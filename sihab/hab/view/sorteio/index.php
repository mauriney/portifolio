<?php
@session_start();
include_once ('conf/config.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

$sorteio_loteamentos = "";

$sql = $db->query("SELECT l.id, l.nome, l.apto, c.status
           FROM snch_loteamento l
           LEFT JOIN sort_casa AS c ON c.loteamento_id = l.id
           WHERE l.status = 1 AND l.apto = 1 AND c.status = 1
           GROUP BY l.id
           ORDER BY l.nome ASC");
while ($loteamento = $sql->fetch(PDO::FETCH_ASSOC)) {
  $sorteio_loteamentos .= "" . $loteamento['nome'] . ", ";
}

if ($sorteio_loteamentos != "") {
  $tamanho = strlen($sorteio_loteamentos);
  $sorteio_loteamentos[$tamanho - 2] = ".";
} else {
  $sorteio_loteamentos = "Nenhum loteamento selecionado para o sorteio.";
}

$stmt = $db->prepare("SELECT sc.id
                      FROM sort_casa sc
                      WHERE sc.status = 1
                      ORDER BY sc.nome ASC");
$stmt->execute();
$contador_casas = $stmt->rowCount();

$stmt = $db->prepare("SELECT sca.id
                      FROM sort_candidato_apto sca
                      WHERE sca.status = 1
                      ORDER BY sca.id ASC");
$stmt->execute();
$contador_candidatos = $stmt->rowCount();


$stmt = $db->query("SELECT hb.nome AS pessoa, hb.cpf, sc.nome AS casa, sc.endereco, sc.numero, sl.nome AS loteamento
           FROM sort_candidato_casa scc
           LEFT JOIN sort_casa AS sc ON sc.id = scc.casa_id
           LEFT JOIN snch_loteamento AS sl ON sl.id = sc.loteamento_id
           LEFT JOIN hab_candidato AS hc ON hc.id = scc.candidato_id
           LEFT JOIN hab_pessoa AS hb ON hb.id = hc.hab_pessoa_id
           WHERE 1 AND DAY(scc.data_cadastro) = DAY(NOW()) AND MONTH(scc.data_cadastro) = MONTH(NOW()) AND YEAR(scc.data_cadastro) = YEAR(NOW())
           ORDER BY hb.nome ASC");
$candidato_casa_qtd = $stmt->rowCount();

if ($candidato_casa_qtd > 0 && $contador_casas == 0 && $contador_candidatos == 0) {
  $sorteio_loteamentos = "SORTEIO REALIZADO EM " . date('d/m/Y');
}
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
        <link href="<?= PORTAL_URL; ?>assets/css/lottery.css" rel="stylesheet"/>
        <link href="<?= PORTAL_URL; ?>assets/css/cores.css" rel="stylesheet"/>
        <!-- IMPORTANDO O CSS E JS DO PLUGIN DE SORTEIO -->
        <link rel="stylesheet" href="<?= ASSETS_FOLDER; ?>plugins/jQuery-SlotMachine-master/dist/jquery.slotmachine.css" type="text/css" media="screen" />
        <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jQuery-SlotMachine-master/dist/jquery.slotmachine.js"></script>
    </head>
    <body>
        <div id="tabela_impressao">
            <form id="form_sortear" name="form_sortear" action="#" method="post">
                <section class="content">
                    <div class="logo-governo"></div>
                    <img src="<?= PORTAL_URL; ?>assets/img/sihab_pb.svg" class="logo" alt="">
                    <h1><?= $contador_casas == 0 ? '' : 'SORTEIO - '; ?><?= $sorteio_loteamentos; ?></h1>
                    <div class="row">
                        <div class="col-md-8 col-sm-12 col-xs-12">
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

                        <div class="col-md-4">
                            <div class="home">
                                <h1>CASA <br></h1>
                                <?php
                                if ($contador_casas > 0) {
                                  ?>
                                  <div class="candidate randomizeMachine" id="<?= $contador_candidatos > 0 ? 'machine1' : ''; ?>">
                                      <?php
                                      if ($contador_candidatos > 0) {
                                        $sql = $db->query("SELECT hb.nome AS candidato, hb.cpf
           FROM sort_candidato_apto sca
           LEFT JOIN hab_candidato AS hc ON hc.id = sca.candidato_id
           LEFT JOIN hab_pessoa AS hb ON hb.id = hc.hab_pessoa_id
           WHERE 1
           ORDER BY hb.nome ASC LIMIT 0,3");
                                        while ($candidato = $sql->fetch(PDO::FETCH_ASSOC)) {
                                          ?>
                                          <div class="candidato-nome">
                                              <br/>
                                              <p>
                                                  SORTEIO REALIZADO COM SUCESSO!
                                              </p>
                                              <br/>
                                          </div>
                                          <?php
                                        }
                                      } else {
                                        ?>

                                        <br/>
                                        <p>
                                            NENHUM CANDIDATO APTO AO SORTEIO!
                                        </p>
                                        <br/>

                                        <?php
                                      }
                                      ?>
                                  </div>
                                  <?php
                                } else {
                                  ?>
                                  <div class="candidate randomizeMachine">
                                      <div class="candidato-nome">
                                          <br/>
                                          <p>
                                              <?= $cont == 0 ? '' : 'SORTEIO REALIZADO COM SUCESSO!'; ?>
                                          </p>
                                          <br/>
                                      </div>
                                  </div>
                                  <?php
                                }
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button <?= $contador_casas > 0 && $contador_candidatos > 0 ? '' : 'style="display: none"'; ?> id="ranomizeButton" class="btn btn-success btn-lg" type="submit">SORTEAR CASA</button>
                                </div>
                                <div class="col-md-12 text-center">
                                    <a target="_blank" href="imprimir"><button <?= $contador_casas > 0 || $cont == 0 ? 'style="display: none"' : ''; ?> id="imprimir" class="btn btn-success btn-lg" type="button">Imprimir</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
                <div class="transparencia"></div>
                <img src="<?= PORTAL_URL; ?>assets/img/sorteio/img1.jpg" class="img-fundo blur" alt=""/>
                <div class="logo-governo"></div>
            </form>
        </div>
    </body>
</html>


<!-- Javascript Libraries -->
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/Waves/dist/waves.min.js"></script>
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
<script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>

<script type="text/javascript" src="<?= JS_FOLDER ?>livequery.js"></script>

<!-- JS UTIL -->
<script src="<?= PORTAL_URL ?>utils/utils.js" type="text/javascript"></script>
<script src="<?= PORTAL_URL ?>utils/projeto.utils.js" type="text/javascript"></script>


<script src="<?= PORTAL_URL; ?>assets/js/functions.js"></script>
<script src="<?= PORTAL_URL; ?>assets/js/actions.js"></script>

<!-- Notificação -->
<script src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/messenger.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/demo/location-sel.js"></script>
<script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/demo/theme-sel.js"></script>

<!-- JS DO OBJETO-LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/sorteio/index.js"></script>