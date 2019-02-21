<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT id, nome, status FROM seg_objeto WHERE id = :id");
  $result->bindValue(":id", $id);
  $result->execute();

  $dados_objeto = $result->fetch(PDO::FETCH_ASSOC);

  $objeto_id = $dados_objeto['id'];
  $objeto_nome = ($dados_objeto['nome']);
  $objeto_status = $dados_objeto['status'];
} else {
  $objeto_id = "";
  $objeto_nome = "";
  $objeto_status = 1;
}
?>

<?php include('template/sidebar.php'); ?>

<form id="form_objeto" name="form_objeto" action="#" method="post">

  <section id="content">
    <div class="container">
      <div class="c-header">
        <div class="card icons-demo">
          <div class="card-header cw-header palette-Pink-700 bg">
            <div class="cwh-year">Objeto</div>
            <div class="cwh-day">Cadastro</div>

            <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
          </div>
          <div class="card-body card-padding-sm">
            <div id="cw-body">
              <div class="card-body card-padding">
                <p class="c-black f-500 m-b-5">DADOS DO OBJETO</p>

                <div class="row space-50">
                  <div class="col-md-6">
                    <div class="input-group fg-float">
                      <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                      <div id="div_nome" class="fg-line">
                        <input type="text"  id="nome" name="nome" class="form-control" value="<?= $objeto_nome ?>">
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
              <input id="ts1" name="ts1" type="checkbox" hidden="hidden" <?= ($objeto_status == 1 ? 'checked="checked"' : ''); ?> value="<?= ($objeto_status == 1 ? 1 : 0); ?>">
              <label for="ts1" class="ts-helper"></label>
            </div>
          </div> 
          <div class="col-xs-1">ATIVO</div>                              
        </div>
      </div>
    </div>

  </section>

  <input type="hidden" id="id" name="id" value="<?= $objeto_id ?>"/>

  <div align="center">
    <?php
    if ($objeto_id == "") {
      if (vf_objeto_acao("cadastrar")) {
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Cadastrar</button>
      <?php
      }
    } else {
      if (vf_objeto_acao("editar")){
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Atualizar</button>
      <?php
      }
    }
    ?>
  </div>

</form>

<?php include('template/rodape.php'); ?>

<!-- JS DO OBJETO-CADASTRO -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/objeto/cadastro.js"></script>