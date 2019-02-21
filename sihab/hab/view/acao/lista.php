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
    <div class="cwh-year">Ação</div>
    <div class="cwh-day">Lista</div>

    <?php
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/acao/cadastro" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-plus"></i></a>
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
              $result = $db->prepare("SELECT a.nome, a.data_cadastro, a.id, a.status, u.nome as usuario_pai_nome 
                                 FROM seg_acao a, seg_usuario u WHERE a.usuario_pai_id = u.id ORDER BY upper(a.nome) ASC");
            } else {
              $result = $db->prepare("SELECT a.nome, a.data_cadastro, a.id, a.status, u.nome as usuario_pai_nome 
                                 FROM seg_acao a, seg_usuario u WHERE a.usuario_pai_id = u.id and a.nome like ? ORDER BY upper(a.nome) ASC");
              $result->bindValue(1, "%$busca%");
            }
            $result->execute();
            while ($acao = $result->fetch(PDO::FETCH_ASSOC)) {
              ?> 

              <tr data-row-id="<?= $acao['id']; ?>">
                <td><?= $cont; ?></td>
                <td><?= $acao['id']; ?></td>
                <td><?= ($acao['nome']); ?></td>
                <td><?= ($acao['usuario_pai_nome']); ?></td>
                <td><?= obterDataBRTimestamp($acao['data_cadastro']); ?></td>
                <td><?= status($acao['status']); ?></td>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/acao/lista.js"></script>