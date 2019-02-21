<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>


<?php
$quantidade = 0;

$rs = $db->prepare("SELECT id from seg_acao");
$rs->execute();
$quantidade = $rs->rowCount();

if (isset($_POST['buscar_form'])) {
  $busca = ($_POST['buscar_form']);
} else {
  $busca = "";
}
?>

<div class="card icons-demo">
  <div class="card-header cw-header palette-Pink-700 bg">
    <div class="cwh-year">Resultados dos Sorteios</div>
    <div class="cwh-day">Lista</div>
  </div>
  <div class="card-body card-padding-sm">
    <div id="cw-body">

      <br/>
      <form id="form_resultado" name="form_resultado" action="#" method="post">
        <div class="row" style="margin-bottom: -15px">
          <div class="col-md-5"></div>
          <div class="col-md-2">
            <select id="data_cadastro" name="data_cadastro" class="selectpicker" data-live-search="true">
              <option value="">ESCOLHA A DATA DO SORTEIO</option>
              <?php
              $result = $db->prepare("SELECT DATE_FORMAT(scc.data_cadastro, '%Y-%m-%d') AS data_cadastro
           FROM sort_candidato_casa scc
           LEFT JOIN sort_casa AS sc ON sc.id = scc.casa_id
           LEFT JOIN snch_loteamento AS sl ON sl.id = sc.loteamento_id
           LEFT JOIN hab_candidato AS hc ON hc.id = scc.candidato_id
           LEFT JOIN hab_pessoa AS hb ON hb.id = hc.hab_pessoa_id
           WHERE 1
           GROUP BY data_cadastro
           ORDER BY data_cadastro DESC");
              $result->execute();
              while ($data_cadastro = $result->fetch(PDO::FETCH_ASSOC)) {
                if (isset($_POST['data_cadastro']) && $_POST['data_cadastro'] == $data_cadastro['data_cadastro']) {
                  ?>
                  <option selected="true" value='<?= $data_cadastro['data_cadastro']; ?>'><?= obterDataBRTimestamp($data_cadastro['data_cadastro']); ?></option>
                  <?php
                } else {
                  ?>
                  <option value='<?= $data_cadastro['data_cadastro']; ?>'><?= obterDataBRTimestamp($data_cadastro['data_cadastro']); ?></option>
                  <?php
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-5"></div>
        </div>
      </form>

      <div id="pagina" class="table-responsive">
        <table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="#">#</th>
              <th data-column-id="id">ID</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="cpf">CPF</th>
              <th data-column-id="endereco">ENDEREÇO</th>
              <th data-column-id="loteamento">LOTEAMENTO</th>
              <th data-column-id="status">STATUS</th>
              <?php
              if (vf_objeto_acao("excluir")) {
                ?>
                <th data-column-id="acao" data-formatter="exclui" data-sortable="false">AÇÃO</th>
                <?php
              }
              ?>
            </tr>
          </thead>
          <tbody>

            <?php
            if (isset($_POST['data_cadastro'])) {
              $data_cadastro = "";
              $cont = 0;
              $sql = $db->prepare("SELECT scc.id, scc.status, scc.data_cadastro, hb.nome AS pessoa, hb.cpf, sc.nome AS casa, sc.endereco, sc.numero, sl.nome AS loteamento
           FROM sort_candidato_casa scc
           LEFT JOIN sort_casa AS sc ON sc.id = scc.casa_id
           LEFT JOIN snch_loteamento AS sl ON sl.id = sc.loteamento_id
           LEFT JOIN hab_candidato AS hc ON hc.id = scc.candidato_id
           LEFT JOIN hab_pessoa AS hb ON hb.id = hc.hab_pessoa_id
           WHERE 1 AND DATE_FORMAT(scc.data_cadastro, '%Y-%m-%d') = ?
           ORDER BY hb.nome ASC");
              $sql->bindValue(1, $_POST['data_cadastro']);
              $sql->execute();
              while ($sorteado = $sql->fetch(PDO::FETCH_ASSOC)) {
                $cont++;
                ?>
                <tr>
                  <td><?= $cont; ?></td>
                  <td><?= $sorteado['id']; ?></td>
                  <td><?= $sorteado['pessoa']; ?></td>
                  <td><?= $sorteado['cpf']; ?></td>
                  <td><?= $sorteado['endereco'] . ", nº " . $sorteado['numero']; ?></td>
                  <td><?= $sorteado['loteamento']; ?></td>
                  <td><?= $sorteado['status'] == 2 ? 'Desistência' : 'Ativo'; ?></td>
                  <td></td>
                </tr>
                <?php
              }
            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include('template/rodape.php'); ?>

<!-- JS DO OBJETO-LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/sorteio/resultado.js"></script>