<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$quantidade = 0;

$rs = $db->prepare("SELECT id from hab_origem_demanda");
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
    <div class="cwh-year">Origem de Demanda</div>
    <div class="cwh-day">Lista</div>
    <?php
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/origem_demanda/cadastro" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-plus"></i></a>
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
              <th data-column-id="responsavel">RESPONSÁVEL</th>
              <th data-column-id="data">DATA</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
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
              $result = $db->prepare("SELECT p.nome, p.data_cadastro, p.id, p.status, u.nome as usuario_pai_nome
                                 FROM hab_origem_demanda p, seg_usuario u WHERE p.seg_usuario_pai_id = u.id ORDER BY upper(p.nome) ASC");
            } else {
              $result = $db->prepare("SELECT p.nome, p.data_cadastro, p.id, p.status, u.nome as usuario_pai_nome
                                 FROM hab_origem_demanda p, seg_usuario u WHERE p.seg_usuario_pai_id = u.id ORDER BY upper(p.nome) ASC");
            }
            $result->execute();
            while ($origem_demanda = $result->fetch(PDO::FETCH_ASSOC)) {
              ?>

              <tr data-row-id="<?= $origem_demanda['id']; ?>">
                <td><?= $cont; ?></td>
                <td><?= $origem_demanda['id']; ?></td>
                <td><?= ($origem_demanda['nome']); ?></td>
                <td><?= ($origem_demanda['usuario_pai_nome']); ?></td>
                <td><?= obterDataBRTimestamp($origem_demanda['data_cadastro']); ?></td>
                <td><?= status($origem_demanda['status']); ?></td>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/origem_demanda/lista.js"></script>