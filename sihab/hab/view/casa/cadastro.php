<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT id, nome, endereco, numero, loteamento_id, status FROM sort_casa WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();

  $dados_casa = $result->fetch(PDO::FETCH_ASSOC);

  $casa_id = $dados_casa['id'];
  $casa_nome = $dados_casa['nome'];
  $casa_endereco = $dados_casa['endereco'];
  $casa_numero = $dados_casa['numero'];
  $casa_loteamento_id = $dados_casa['loteamento_id'];
  $casa_status = $dados_casa['status'];
} else {
  $casa_id = "";
  $casa_nome = "";
  $casa_endereco = "";
  $casa_numero = "";
  $casa_loteamento_id = "";
  $casa_status = 0;
}
?>

<?php include('template/sidebar.php'); ?>

<form id="form_casa" name="form_asa" action="#" method="post">

  <section id="content">
    <div class="container">
      <div class="c-header">
        <div class="card icons-demo">
          <div class="card-header cw-header palette-Pink-700 bg">
            <div class="cwh-year">Casa</div>
            <div class="cwh-day">Cadastro</div>

            <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
          </div>
          <div class="card-body card-padding-sm">
            <div id="cw-body">
              <div class="card-body card-padding">
                <p class="c-black f-500 m-b-5">DADOS DA CASA</p>

                <div class="row space-50">
                  <div class="col-md-6 item-form">
                    <div class="form-group form-group fg-float">
                      <div id="div_nome" class="fg-line">
                        <input type="text"  id="nome" name="nome" class="form-control" value="<?= $casa_nome; ?>">
                        <label class="fg-label">NOME DA CASA</label>
                      </div>
                    </div>
                  </div>
                  <div id="div_loteamento" class="col-md-6 sel-form">
                    <label>LOTEAMENTO</label>
                    <select id="loteamento_id" name="loteamento_id" class="selectpicker" data-live-search="true">
                      <option value="">ESCOLHA O LOTEAMENTO</option>
                      <?php
                      $result4 = $db->prepare("SELECT * FROM snch_loteamento ORDER BY nome ASC");
                      $result4->execute();
                      while ($loteamento = $result4->fetch(PDO::FETCH_ASSOC)) {
                        if ($casa_loteamento_id == $loteamento['id']) {
                          ?>
                          <option selected="true" value="<?= $loteamento['id']; ?>"><?= $loteamento['nome']; ?></option>
                          <?php
                        } else {
                          ?>
                          <option value="<?= $loteamento['id']; ?>"><?= $loteamento['nome']; ?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div> 
                <div class="row space-50">
                  <div class="col-md-6 item-form">
                    <div class="form-group form-group fg-float">
                      <div id="div_endereco" class="fg-line">
                        <input id="endereco" name="endereco" type="text" class="input-sm form-control fg-input" value="<?= $casa_endereco; ?>">
                        <label class="fg-label">ENDEREÇO</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 item-form">
                    <div class="has-feedback form-group fg-float">
                      <span class="zmdi zmdi-keyboard form-control-icons"></span>
                      <div id="div_numero" class="fg-line">
                        <input id="numero" name="numero" type="text" class="input-sm form-control fg-input" value="<?= $casa_numero; ?>" data-mask="#####">
                        <label class="fg-label">NÚMERO</label>
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
        <p class="c-black f-500 m-b-5">APTO AO SORTEIO</p>
      </div>

      <div class="card-body card-padding">                           
        <div class="row m-b-20">
          <div class="col-xs-1">NÃO</div>
          <div class="col-xs-1 m-b-20">
            <div class="toggle-switch" data-ts-color="blue">
              <label for="ts1" class="ts-label"></label>
              <input id="ts1" name="ts1" type="checkbox" hidden="hidden" <?= ($casa_status == 1 ? 'checked="checked"' : ''); ?> value="<?= ($casa_status == 1 ? 1 : 0); ?>">
              <label for="ts1" class="ts-helper"></label>
            </div>
          </div>
          <div class="col-xs-1">SIM</div>                         
        </div>
      </div>
    </div>

  </section>

  <input type="hidden" id="id" name="id" value="<?= $casa_id ?>"/>

  <div align="center">
    <?php
    if ($casa_id == "") {
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/casa/cadastro.js"></script>