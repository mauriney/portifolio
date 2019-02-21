<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$quantidade = 0;

$rs = $db->prepare("SELECT id from snch_apf");
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
    <div class="cwh-year">Empreendimento</div>
    <div class="cwh-day">Lista</div>

    <?php
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/empreendimento/cadastro" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-plus"></i></a>
    <?php
      }
    ?>
  </div>
  <div class="card-body card-padding-sm">
    <div id="cw-body">

      <div id="pagina" class="table-responsive">
        <table id="data-table-basic" class="table table-striped">
          <thead>
            <tr>
              <th data-column-id="#" data-type="numeric">#</th>
              <th data-column-id="id" data-type="numeric">ID</th>
              <th data-column-id="nome">NOME</th>
              <th data-column-id="numero_apf">NUMERO APF</th>
              <th data-column-id="data">DATA CADASTRO</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
              <!-- <th data-column-id="acao" data-formatter="commands" data-sortable="false">AÇÃO</th> -->
              <?php
              if (vf_objeto_acao("excluir") && vf_objeto_acao("editar") ) {
              ?>
              <th data-column-id="acao" data-formatter="geral" data-sortable="false"></th>
              <?php
              } elseif (vf_objeto_acao("excluir") ) {
              ?>
              <th data-column-id="acao" data-formatter="exclui" data-sortable="false"></th>
              <?php
              } elseif (vf_objeto_acao("editar") ) {
              ?>
              <th data-column-id="acao" data-formatter="edita" data-sortable="false"></th>
              <?php
              }
              ?>
            </tr>
          </thead>
          <tbody>

            <?php
            $cont = 1;
            if ($busca == "") {
              $result = $db->prepare("SELECT id, nome, status, numero_apf, data_cadastro
                                      FROM snch_apf
                                      WHERE id > 0
                                      ORDER BY id ASC");
            } else {
              $result = $db->prepare("SELECT id, nome, status, numero_apf, data_cadastro
                                      FROM snch_apf
                                      WHERE id > 0
                                      ORDER BY id ASC");
              $result->bindValue(1, "%$busca%");
            }
            $result->execute();
            while ($empreendimento = $result->fetch(PDO::FETCH_ASSOC)) {
              ?>

              <tr data-row-id="<?= $empreendimento['id']; ?>">
                <td><?= $cont; ?></td>
                <td><?= $empreendimento['id']; ?></td>
                <td><?= ($empreendimento['nome']); ?></td>
                <td><?= ($empreendimento['numero_apf']); ?></td>
                <td><?= obterDataBRTimestamp($empreendimento['data_cadastro']); ?></td>
                <td><?= status($empreendimento['status']); ?></td>
                <td></td>
              </tr>

              <?php
              $cont++;
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/empreendimento/lista.js"></script>