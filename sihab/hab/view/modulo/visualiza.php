<?php include('template/topo.php'); ?>
<?php include('template/sidebar.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null && $param != '' && $param != NULL && $param != 0) {
  $id = $param;
  $result = $db->prepare("SELECT * FROM seg_modulo WHERE id = :id");
  $result->bindValue(":id", $id);
  $result->execute();

  $dados_objeto = $result->fetch(PDO::FETCH_ASSOC);

  $modulo_id = $dados_objeto['id'];
  $modulo_nome = ($dados_objeto['nome']);
  $modulo_status = $dados_objeto['status'];
  $modulo_url = ($dados_objeto['url']);
  $modulo_versao = ($dados_objeto['versao']);
  $modulo_descricao = ($dados_objeto['descricao']);
} else {
  echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "sistema/modulo/lista';</script>";
}
?>

<div class="card icons-demo">
  <div class="card-header cw-header palette-Pink-700 bg">
    <div class="cwh-year">Módulo</div>
    <div class="cwh-day">Informações Básicas</div>
    <?php
      if (vf_objeto_acao("editar")) {
    ?>
    <a href="<?= PORTAL_URL; ?>sistema/modulo/cadastro/<?= $modulo_id; ?>" class="btn palette-Light-Green bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-edit"></i></a>
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
                <dd class="campos-view"><?= $modulo_nome; ?></dd>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <div class="fg-line">
                <dt>Descrição</dt>
                <dd class="textarea-view"><?= $modulo_nome; ?></dd>
              </div>
            </div>
          </div>
        </div> 

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <div class="fg-line">
                <dt>URl</dt>
                <dd class="campos-view"><?= $modulo_url; ?></dd>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <div class="fg-line">
                <dt>Versão</dt>
                <dd class="campos-view"><?= $modulo_versao; ?></dd>
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
$sql = $db->query("SELECT nome, id FROM seg_objeto ORDER BY nome ASC");
while ($objeto = $sql->fetch(PDO::FETCH_ASSOC)) {

  if ($cont % 2 == 0) {//SE O CONTADOR MOD 2 FOR == 0 ELE MUDA DE COR O LAYOUT DO MÓDULO PARA AMARELO
    $cond = "bg-amarelo-escuro";
    $cond2 = "bg-amarelo-escuro-20";
  } else {//SE O CONTADOR MOD 2 FOR DIFERENTE DE 0, ENTÃO ELE MANTE A COR DO MÓDULO PADRÃO AZUL
    $cond = "bg-azul-claro";
    $cond2 = "bg-azul-claro-20";
  }
  ?>
  <div class="acesso-sistema <?= $cond; ?>">
    <div class="row">
      <div class="col-md-6 modulo"><?= $objeto['nome']; ?></div>
    </div>
  </div>
  <div id="div_objetos" class="itens-sistema <?= $cond2; ?>">
    <fieldset>
      <?php
      $sql2 = $db->query("SELECT nome, id FROM seg_acao ORDER BY nome ASC");
      while ($acao = $sql2->fetch(PDO::FETCH_ASSOC)) {

        $checked = '';

        if (check_objeto_acao($modulo_id, $objeto['id'], $acao['id']) && is_numeric($modulo_id))
          $checked = 'checked="true"';
        ?>
        <div class="col-md-2">
          <div class="checkbox check-success">
            <div class="checkbox m-b-15">
              <input <?= $checked; ?> disabled="disabled" type="checkbox" id="acao" name="<?= $objeto['id']; ?>_acao[]" value="<?= $acao['id']; ?>">
              <i class="input-helper"></i>
            </div>
            <label for="acao"><?= ($acao['nome']); ?></label>
          </div>
        </div>
        <?php
      }
      ?>
    </fieldset>
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

</body>   
</div>
</div>
</div>
<?php include('template/rodape.php'); ?>
<!-- JS DO ORGAO-VISUALIZA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/modulo/visualiza.js"></script>