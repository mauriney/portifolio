<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT id, nome, hab_programa_id, status FROM hab_especificidade WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();

  $dados_especificidade = $result->fetch(PDO::FETCH_ASSOC);

  $especificidade_id = $dados_especificidade['id'];
  $especificidade_nome = ($dados_especificidade['nome']);
  $especificidade_programa = $dados_especificidade['hab_programa_id'];
  $especificidade_status = $dados_especificidade['status'];
} else {
  $especificidade_id = "";
  $especificidade_nome = "";
  $especificidade_programa = "";
  $especificidade_status = 1;
}
?>

<?php include('template/sidebar.php'); ?>

<form id="form_especificidade" name="form_especificidade" action="#" method="post">

  <section id="content">
    <div class="container">
      <div class="c-header">
        <div class="card icons-demo">
          <div class="card-header cw-header palette-Pink-700 bg">
            <div class="cwh-year">Especificidade</div>
            <div class="cwh-day">Cadastro</div>

            <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
          </div>
          <div class="card-body card-padding-sm">
            <div id="cw-body">
              <div class="card-body card-padding">
                <p class="c-black f-500 m-b-5">DADOS DA ESPECIFICIDADE</p>

                <div class="row space-50">
                  <div class="col-md-6">
                    <div class="input-group fg-float">
                      <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                      <div id="div_nome" class="fg-line">
                        <input type="text"  id="nome" name="nome" class="form-control" value="<?= $especificidade_nome ?>">
                        <label class="fg-label">Nome</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div id="div_programa" class="input-groud fg-float">
                      <select id="programa" name="programa" placeholder="Programa" class="selectpicker" data-live-search="true">
                        <p class="f-500 m-b-15 c-black">ESCOLHA A ORIGEM DA DEMANDA QUE SERÁ VINCULADA</p>
                        <option value="">ESCOLHA A ORIGEM DA DEMANDA QUE SERÁ VINCULADA</option>
                        <?php
                        $result = $db->prepare("SELECT id, nome FROM hab_origem_demanda WHERE status = 1 ORDER BY nome ASC");
                        $result->execute();
                        while ($especificidade_programa = $result->fetch(PDO::FETCH_ASSOC)) {
                          if ($dados_subprograma['hab_programa_id'] == $especificidade_programa['id']) {
                            ?>
                            <option selected='true' value='<?= $especificidade_programa['id']; ?>'><?= $especificidade_programa['nome']; ?></option>
                            <?php
                          } else {
                            ?>
                            <option value='<?= $especificidade_programa['id']; ?>'><?= $especificidade_programa['nome']; ?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
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
              <input id="ts1" name="ts1" type="checkbox" hidden="hidden" <?= ($especificidade_status == 1 ? 'checked="checked"' : ''); ?> value="<?= ($especificidade_status == 1 ? 1 : 0); ?>">
              <label for="ts1" class="ts-helper"></label>
            </div>
          </div>
          <div class="col-xs-1">ATIVO</div> 
        </div>
      </div>
    </div>

  </section>

  <input type="hidden" id="id" name="id" value="<?= $especificidade_id ?>"/>

  <div align="center">
    <?php
    if ($especificidade_id == "") {
      if (vf_objeto_acao("cadastrar")) {
      ?>
      <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Cadastrar</button>
      <?php
      }
    } else {
      if (vf_objeto_acao("editar")) {
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/especificidade/cadastro.js"></script>