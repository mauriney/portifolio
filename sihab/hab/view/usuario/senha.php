<?php include('template/topo.php'); ?>

<?php
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0 ) );
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

if ($param != null || $param != '' || $param != NULL) {
  $id = $param;
  $result = $db->prepare("SELECT * FROM seg_usuario WHERE id = ?");
  $result->bindValue(1, $_SESSION['id']);
  $result->execute();

  $dados_usuario = $result->fetch(PDO::FETCH_ASSOC);

  $senha_antiga = $dados_usuario['senha'];
}
?>

<?php include('template/sidebar.php'); ?>
<form id="form_acesso" name="form_acesso" action="#" method="post">
  <div class="card icons-demo">
    <div class="card-header cw-header palette-Purple-500 bg">
      <div class="cwh-year">Usuário</div>
      <div class="cwh-day">Alteração de Senha</div>
      <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>
    </div>

    <section id="content">
      <div class="container">
        <div> <!-- Painel 1° dados pessoais-->
          <div class="card-body card-padding">
            <p class="c-black f-500 m-b-15">DADOS DE ACESSO</p>
            <div class="row">
              <div class="col-md-12">


                <input type="hidden" id="senha_antiga" name="senha_antiga" value="<?= $senha_antiga ?>"/>

                <div class="row space-t-15">
                  <div class="col-md-4">
                    <div class="form-group fg-float">
                      <div id="div_senha_atual" class="fg-line">
                        <input type="password"  id="senha_atual" name="senha_atual" class="form-control" value="">
                        <label for="senha_atual" class="fg-label"> SENHA ATUAL</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 item-form">
                    <div class="has-feedback form-group fg-float">
                      <span class="zmdi zmdi-card form-control-icons"></span>
                      <div id="div_senha_nova" class="fg-line">
                        <input type="password" id="senha_nova" name="senha_nova" class="form-control" value="">
                        <label class="fg-label">NOVA SENHA</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 item-form">
                    <div class="has-feedback form-group fg-float">
                      <span class="zmdi zmdi-calendar form-control-icons"></span>
                      <div id="div_senha_confirma" class="fg-line">
                        <input type="password" id="senha_confirma" name="senha_confirma" class="form-control" value="">
                        <label class="fg-label">CONFIRMAÇÃO DA NOVA SENHA</label>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>    
      </div>
    </section>
  </div>

  <div align="center">
    <button type="submit" class="btn btn-primary btn-lg"><i class="zmdi zmdi-cloud-upload"></i> Atualizar</button>
  </div>

</form>

<?php include('template/rodape.php'); ?>

<!-- JS DO OBJETO-LISTA -->
<script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/usuario/senha.js"></script>