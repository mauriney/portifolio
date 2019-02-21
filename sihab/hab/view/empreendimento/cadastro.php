<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT id, nome, status, numero_apf FROM snch_apf WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();

  $dados_empreendimento = $result->fetch(PDO::FETCH_ASSOC);

  $empreendimento_id = $dados_empreendimento['id'];
  $empreendimento_nome = ($dados_empreendimento['nome']);
  $empreendimento_status = $dados_empreendimento['status'];
  $empreendimento_numero_apf = $dados_empreendimento['numero_apf'];
} else {
  $empreendimento_id = "";
  $empreendimento_nome = "";
  $empreendimento_status = "";
  $empreendimento_numero_apf = "";
}
?>

<?php include('template/sidebar.php'); ?>

<form id="form_empreendimento" name="form_empreendimento" action="#" method="post">

  <section id="content">
    <div class="container">
      <div class="c-header">
        <div class="card icons-demo">
          <div class="card-header cw-header palette-Pink-700 bg">
            <div class="cwh-year">Empreendimento</div>
            <div class="cwh-day">Cadastro</div>

            <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
          </div>
          <div class="card-body card-padding-sm">
            <div id="cw-body">
              <div class="card-body card-padding">
                <p class="c-black f-500 m-b-5">DADOS DO EMPREENDIMENTO</p>

                <div class="row space-50">
                  <div class="col-md-6">
                    <div class="input-group fg-float">
                      <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                      <div id="div_nome" class="fg-line">
                        <input type="text"  id="nome" name="nome" class="form-control" value="<?= $empreendimento_nome ?>">
                        <label class="fg-label">Nome</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="checkbox m-b-15">
                      <label>
                        <input id="apf_sim" name="apf" type="checkbox" value="1">                      
                        <i class="input-helper"></i>
                        Possui Nº APF?
                      </label>
                    </div>
                  </div>
                  <div id="apf_sim_nao" style='display:none' class="row space-t-10">
                  <div class="col-md-4">
                    <div class="input-group fg-float">
                      <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                      <div id="div_numero_apf" class="fg-line">
                        <input type="text"  id="numero_apf" name="numero_apf" class="form-control" value="<?= $empreendimento_numero_apf ?>">
                        <label class="fg-label">Numero APF</label>
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
              <input id="ts1" name="ts1" type="checkbox" hidden="hidden" <?= ($empreendimento_status == 1 || $empreendimento_status == "" ? 'checked="checked"' : ''); ?> value="<?= ($empreendimento_status == 1 ? 1 : 0); ?>">
              <label for="ts1" class="ts-helper"></label>
            </div>
          </div>
          <div class="col-xs-1">ATIVO</div> 
        </div>
      </div>
    </div>

  </section>

  <input type="hidden" id="id" name="id" value="<?= $empreendimento_id ?>"/>

  <div align="center">
    <?php
    if ($empreendimento_id == "") {
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/empreendimento/cadastro.js"></script>