<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT id, nome, status FROM seg_acao WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();

  $dados_acao = $result->fetch(PDO::FETCH_ASSOC);

  $acao_id = $dados_acao['id'];
  $acao_nome = ($dados_acao['nome']);
  $acao_status = $dados_acao['status'];
} else {
  $acao_id = "";
  $acao_nome = "";
  $acao_status = 1;
}
?>

<?php include('template/sidebar.php'); ?>

<form id="form_acao" name="form_acao" action="#" method="post">

  <section id="content">
    <div class="container">
      <div class="c-header">
        <div class="card icons-demo">
          <div class="card-header cw-header palette-Pink-700 bg">
            <div class="cwh-year">Ação</div>
            <div class="cwh-day">Cadastro</div>

            <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
          </div>
          <div class="card-body card-padding-sm">
            <div id="cw-body">
              <div class="card-body card-padding">
                <p class="c-black f-500 m-b-5">DADOS DA AÇÃO</p>

                <div class="row space-50">
                  <div class="col-md-6">
                    <div class="input-group fg-float">
                      <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                      <div id="div_nome" class="fg-line">
                        <input type="text"  id="nome" name="nome" class="form-control" value="<?= $acao_nome ?>">
                        <label class="fg-label">Nome</label>
                      </div>
                    </div>
                  </div>
                </div> 
              </div>            
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <p class="c-black f-500 m-b-5">ACESSO AO SISTEMA</p>
      </div>

      <div class="card-body card-padding">                           
        <div class="row m-b-20">
          <div class="col-xs-1">BLOQUEADO</div>
          <div class="col-xs-1 m-b-20">
            <div class="toggle-switch" data-ts-color="blue">
              <label for="ts1" class="ts-label"></label>
              <input id="ts1" name="ts1" type="checkbox" hidden="hidden" <?= ($acao_status == 1 ? 'checked="checked"' : ''); ?> value="<?= ($acao_status == 1 ? 1 : 0); ?>">
              <label for="ts1" class="ts-helper"></label>
            </div>
          </div>
          <div class="col-xs-1">ATIVO</div>                         
        </div>
      </div>
    </div>

  </section>

  <input type="hidden" id="id" name="id" value="<?= $acao_id ?>"/>

  <div align="center">
    <?php
    if ($acao_id == "") {
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Cadastrar</button>
      <?php
      }
    } else {
      if (vf_objeto_acao("cadastrar") || (vf_objeto_acao("editar"))) {
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Atualizar</button>
      <?php
      }
    }
    ?>
  </div>

</form>

<?php include('template/rodape.php'); ?>

<!-- JS DO USUARIO-CADASTRO -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/acao/cadastro.js"></script>