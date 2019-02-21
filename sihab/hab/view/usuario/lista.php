<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<?php
$quantidade = 0;

$rs = $db->prepare("SELECT id FROM seg_usuario");
$rs->execute();
$quantidade = $rs->rowCount();

if (isset($_POST['buscar_form'])) {
  $busca = ($_POST['buscar_form']);
} else {
  $busca = "";
}
?>

<div class="card icons-demo">
  <div class="card-header cw-header palette-Purple-500 bg">
    <div class="cwh-year">Usuário</div>
    <div class="cwh-day">Lista</div>
    <?php
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/usuario/cadastro" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-plus"></i></a>
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
              <th data-column-id="orgao">ÓRGÃO</th>
              <th data-column-id="email">E-MAIL</th>
              <th data-column-id="celular">CELULAR</th>
              <th data-column-id="situacao">SITUAÇÃO</th>
              <?php
              if (vf_objeto_acao("editar") ) {
              ?>
              <th data-column-id="acao" data-formatter="commands" data-sortable="false"></th>
              <?php
              }
              ?>
            </tr>
          </thead>
          <tbody>

            <?php
            $cont = 1;
            if ($busca == "") {
              $result = $db->prepare("SELECT o.id AS unidade_organizacional_id, u.online, u.id, u.nome, u.cargo, u.email_institucional, o.sigla as sigla, u.telefone_celular, u.telefone_institucional, u.status
                       FROM seg_usuario u , bsc_unidade_organizacional o 
                       WHERE u.unidade_organizacional_id = o.id ORDER BY u.nome");
            } else {
              $result = $db->prepare("SELECT o.id AS unidade_organizacional_id, u.online, u.id, u.nome, u.cargo, u.email_institucional, o.sigla as sigla, u.telefone_celular, u.telefone_institucional, u.status
                       FROM seg_usuario u , bsc_unidade_organizacional o 
                       WHERE u.nome like ? and u.unidade_organizacional_id = o.id ORDER BY u.nome");
              $result->bindValue(1, "%$busca%");
            }

            $result->execute();
            while ($usuario = $result->fetch(PDO::FETCH_ASSOC)) {
              ?> 

              <tr>
                <td><?= $cont; ?></td>
                <td><?= $usuario['id']; ?></td>
                <td><?= $usuario['nome']; ?></td>
                <td><?= $usuario['sigla']; ?></td>
                <td><?= $usuario['email_institucional']; ?></td>
                <td><?= $usuario['telefone_celular']; ?></td>
                <td><?= status($usuario['status']); ?></td>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/usuario/lista.js"></script>