<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$quantidade = 0;

$rs = $db->prepare("SELECT id from sort_casa");
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
    <div class="cwh-year">Casa</div>
    <div class="cwh-day">Lista</div>

    <?php
    if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
      ?>
      <a href="<?= PORTAL_URL; ?>sistema/casa/cadastro" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-plus"></i></a>
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
              <th data-column-id="numero">NÚMERO</th>
              <th data-column-id="endereco">ENDEREÇO</th>
              <th data-column-id="loteamento">LOTEAMENTO</th>
              <th data-column-id="responsavel">RESPONSÁVEL</th>
              <th data-column-id="situacao">APTO AO SORTEIO</th>
              <?php
              if (vf_objeto_acao("excluir") && vf_objeto_acao("editar")) {
                ?>
                <th data-column-id="acao" data-formatter="geral" data-sortable="false"></th>
                <?php
              } elseif (vf_objeto_acao("excluir")) {
                ?>
                <th data-column-id="acao" data-formatter="exclui" data-sortable="false"></th>
                <?php
              } elseif (vf_objeto_acao("editar")) {
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
              $result = $db->prepare("SELECT c.nome, c.id, c.status, c.endereco, c.numero, u.nome as usuario_pai_nome, sl.nome AS loteamento 
                                 FROM sort_casa c
                                 LEFT JOIN seg_usuario AS u ON u.id = c.seg_usuario_pai
                                 LEFT JOIN snch_loteamento AS sl ON sl.id = c.loteamento_id
                                 WHERE 1
                                 ORDER BY upper(c.nome) ASC");
            } else {
              $result = $db->prepare("SELECT c.nome, c.id, c.status, c.endereco, c.numero, u.nome as usuario_pai_nome, sl.nome AS loteamento 
                                 FROM sort_casa c
                                 LEFT JOIN seg_usuario AS u ON u.id = c.seg_usuario_pai
                                 LEFT JOIN snch_loteamento AS sl ON sl.id = c.loteamento_id
                                 WHERE c.nome like ?
                                 ORDER BY upper(c.nome) ASC");
              $result->bindValue(1, "%$busca%");
            }
            $result->execute();
            while ($casa = $result->fetch(PDO::FETCH_ASSOC)) {
              ?> 

              <tr id="casa_tr_<?= $casa['id']; ?>" data-row-id="<?= $casa['id']; ?>" rel="<?= $casa['status']; ?>" sorteio="<?= pesquisar_tabela("id", "sort_candidato_casa", "casa_id", "=", $casa['id'], ""); ?>">
                <td><?= $cont; ?></td>
                <td><?= $casa['id']; ?></td>
                <td><?= $casa['nome']; ?></td>
                <td><?= $casa['numero']; ?></td>
                <td><?= $casa['endereco']; ?></td>
                <td><?= $casa['loteamento']; ?></td>
                <td><?= $casa['usuario_pai_nome']; ?></td>
                <td><?= status_casa($casa['status']); ?></td>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/casa/lista.js"></script>