<?php include('template/topo.php'); ?>
<?php include('template/sidebar.php'); ?>
<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null && $param != '' && $param != NULL && $param != 0) {
  $id = $param;
  $result = $db->prepare("SELECT * FROM seg_grupo WHERE id = :id");
  $result->bindValue(":id", $id);
  $result->execute();

  $dados_objeto = $result->fetch(PDO::FETCH_ASSOC);

  $grupo_id = $dados_objeto['id'];
  $grupo_nome = ($dados_objeto['nome']);
  $grupo_status = $dados_objeto['status'];
} else {
  echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "sistema/modulo/lista';</script>";
}
?>

<div class="card icons-demo">
  <div class="card-header cw-header palette-Pink-700 bg">
    <div class="cwh-year">Grupo de acesso</div>
    <div class="cwh-day">Informações Básicas</div>
    <?php
      if (vf_objeto_acao("editar")) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/grupo/cadastro/<?= $grupo_id; ?>" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-edit"></i></a>
    <?php
    }
    ?>
  </div>

  <section id="content">
    <div class="container">
      <div class="card-body card-padding">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <div class="fg-line">
                <dt>Nome</dt>
                <dd class="campos-view"><?= $grupo_nome; ?></dd>
              </div>
            </div>
          </div>
        </div>        

      </div>
    </div>
  </section>
</div>

<?php
$cont = 1;
$cond = "bg-azul-claro"; //bg-amarelo-escuro é o padrão
$cond2 = "bg-azul-claro-20"; //bg-azul-claro-20 é o padrão
$sql = $db->prepare("SELECT moa.modulo_id, m.nome 
                             FROM seg_modulo_objeto_acao moa, seg_modulo m 
                             WHERE moa.modulo_id = m.id 
                             GROUP BY moa.modulo_id
                             ORDER BY moa.modulo_id ASC");
$sql->execute();
while ($modulo = $sql->fetch(PDO::FETCH_ASSOC)) {
  if ($cont % 2 == 0) {//SE O CONTADOR MOD 2 FOR == 0 ELE MUDA DE COR O LAYOUT DO MÓDULO PARA AMARELO
    $cond = "bg-amarelo-escuro";
    $cond2 = "bg-amarelo-escuro-20";
  } else {//SE O CONTADOR MOD 2 FOR DIFERENTE DE 0, ENTÃO ELE MANTE A COR DO MÓDULO PADRÃO AZUL
    $cond = "bg-azul-claro";
    $cond2 = "bg-azul-claro-20";
  }
  ?>
  <div class="card">
    <div class="acesso-sistema <?= $cond; ?>">
      <div class="row">
        <div class="col-md-6 modulo"><?= ($modulo['nome']); ?></div>
      </div>
    </div>
    <div id="div_objetos" class="itens-sistema <?= $cond2; ?>">
      <fieldset class="espacamento">

        <div class="organizador">
          <div class="checkbox check-success">
            <div class="checkbox m-b-15">
              <input type="checkbox" name="todos" id="todos" value="todos" onclick="marcar(this);" disabled="disabled" />
              <i class="input-helper"></i>
            </div>
            <label for="checkbox2"> Todos </label>
          </div>


          <?php
          $sql2 = $db->prepare("SELECT moa.objeto_id, o.nome 
                                      FROM seg_modulo_objeto_acao moa, seg_objeto o
                                      WHERE moa.objeto_id = o.id and moa.modulo_id = ?
                                      GROUP BY moa.objeto_id
                                      ORDER BY o.nome");
          $sql2->bindValue(1, $modulo['modulo_id']);
          $sql2->execute();
          while ($objeto = $sql2->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <fieldset class="espacamento">
              <h2><?= ($objeto['nome']); ?></h2>
              <?php
              $sql3 = $db->prepare("SELECT moa.acao_id, a.nome, moa.id as modulo_objeto_acao_id 
                                          FROM seg_modulo_objeto_acao moa, seg_acao a
                                          WHERE moa.acao_id = a.id and moa.modulo_id = ? and moa.objeto_id = ?
                                          ORDER BY a.nome");
              $sql3->bindValue(1, $modulo['modulo_id']);
              $sql3->bindValue(2, $objeto['objeto_id']);
              $sql3->execute();
              while ($acao = $sql3->fetch(PDO::FETCH_ASSOC)) {

                $checked = '';

                if (grupo_modulo_objeto_acao($acao['modulo_objeto_acao_id'], $grupo_id) && is_numeric($grupo_id))
                  $checked = 'checked="true"';
                ?>
                <div class="col-md-2">
                  <div class="checkbox check-success">
                    <div class="checkbox m-b-15">
                      <input <?= $checked; ?> class="marcar" type="checkbox" id="acao" name="<?= $modulo['modulo_id']; ?>_<?= $objeto['objeto_id']; ?>_acao[]" value="<?= $acao['modulo_objeto_acao_id']; ?>" disabled="disabled" />
                      <i class="input-helper"></i>
                    </div>
                    <label for="checkbox2"> <?= ($acao['nome']); ?> </label>
                  </div>
                </div>
                <?php
              }
              ?>
            </fieldset>
            <?php
          }
          ?>
        </div>
      </fieldset>
    </div>
  </div>
  <?php
  $cont++;
}
?>

<!-- Page Loader -->
<div class="page-loader palette-Teal bg">
  <div class="preloader pl-xl pls-white">
    <svg class="pl-circular" viewBox="25 25 50 50">
    <circle class="plc-path" cx="50" cy="50" r="20"/>
    </svg>
  </div>
</div>

<?php include('template/rodape.php'); ?>
<!-- JS DO GRUPO-VISUALIZA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/grupo/visualiza.js"></script>