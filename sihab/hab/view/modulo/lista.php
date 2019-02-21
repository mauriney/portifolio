<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$quantidade = 0;

$rs = $db->prepare("SELECT id from seg_modulo");
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
    <div class="cwh-year">Módulo</div>
    <div class="cwh-day">Lista</div>
    <?php
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/modulo/cadastro" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-plus"></i></a>
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
              <th data-column-id="responsavel">DESCRIÇÃO</th>
              <th data-column-id="versao">VERSÃO</th>
              <th data-column-id="url">URL</th>
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
              $result = $db->prepare("SELECT m.status, m.descricao, m.id, m.data_cadastro, m.url, m.versao, m.nome AS modulo ,m.responsavel_id, u.nome AS usuario 
                                            FROM seg_modulo m 
                                            LEFT JOIN seg_usuario u ON u.id = m.responsavel_id 
                                            WHERE m.responsavel_id = u.id
                                            GROUP BY m.id ORDER BY upper(m.nome) ASC");
            } else {
              $result = $db->prepare("SELECT m.status, m.descricao, m.id, m.data_cadastro, m.url, m.versao, m.nome AS modulo ,m.responsavel_id, u.nome AS usuario 
                                            FROM seg_modulo m 
                                            LEFT JOIN seg_usuario u ON u.id = m.responsavel_id 
                                            WHERE m.responsavel_id = u.id AND m.nome like ?
                                            GROUP BY m.id ORDER BY upper(m.nome) ASC");
              $result->bindValue(1, "%$busca%");
            }
            $result->execute();
            while ($modulo = $result->fetch(PDO::FETCH_ASSOC)) {
              ?> 

              <tr data-row-id="<?= $modulo['id']; ?>">
                <td><?= $cont; ?></td>
                <td><?= $modulo['id']; ?></td>
                <td><?= $modulo['modulo']; ?></td>
                <td><?= $modulo['descricao']; ?></td>
                <td><?= $modulo['versao']; ?></td>
                <td><?= $modulo['url']; ?></td>                
                <td><?= status($modulo['status']); ?></td>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/modulo/lista.js"></script>