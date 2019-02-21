<?php
@session_start();
include_once('conf/config.php');
include_once('assets/plugins/phpmailer/class.smtp.php');
include_once('assets/plugins/phpmailer/class.phpmailer.php');
include_once('utils/funcoes.php');
include_once('conf/Url.php');

// VERIFICAÇÕES DE SESSÕES
$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = @$GLOBALS['urlPasta'];
$param = $param == '' && $id != '' ? $id : $param;
$codigo = $param;

if (!is_numeric(pesquisar_tabela("id", "seg_recupera_senha", "codigo", "=", $codigo, "AND alterada IS NULL AND expira >= NOW()"))) {
  echo "<script language='javaScript'>window.location.href='" . PORTAL_URL . "login.php'</script>";
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= TITULOSISTEMA ?></title>

  <!-- Vendor CSS -->
  <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet">
  <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
  <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">

  <!-- CSS -->
  <link href="<?= PORTAL_URL; ?>assets/plugins/Template2/css/app_1.min.css" rel="stylesheet">
  <link href="<?= PORTAL_URL; ?>assets/plugins/Template2/css/app_2.min.css" rel="stylesheet">
</head>
<body>

  <!-- Page Loader -->
  <div id="div_loader" class="palette-Teal bg loader-geral" style="display: none">
    <div class="card-body card-padding">
      <div class="preloader pl-xxl">
        <svg class="pl-circular" viewBox="25 25 50 50">
        <circle class="plc-path" cx="50" cy="50" r="20" />
        </svg>
      </div>
    </div>
  </div>

  <div class="login-content">
    <div class="lc-block toggled" id="l-login">
      <div class="logo"></div>
      <div class="lcb-form">
        <p class="text-left">Digite sua nova senha de acesso ao sistema com no mínimo 6 digitos, 1 letra, 1 número e 1 caractere especial.</p>
        <form id="form_recuperar" name="form_recuperar" method="post" action="#">
          <input type="hidden" id="codigo" name="codigo" value="<?= $codigo; ?>"/>
          <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
            <div id="div_senha" class="fg-line">
              <input type="password" class="form-control" placeholder="Senha" id="senha" name="senha">
            </div>
          </div>
          <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
            <div id="div_conf_senha" class="fg-line">
              <input type="password" class="form-control" placeholder="Confirmação de Senha" id="conf_senha" name="conf_senha">
            </div>
          </div>
          <button type="submit" class="btn btn-login btn-success btn-float"><i class="zmdi zmdi-check"></i></button>
        </form>
      </div>

      <div class="lcb-navigation">
        <a id="tela_login" href="" data-ma-action="login-switch" data-ma-block="#l-login"><i class="zmdi zmdi-long-arrow-left"></i> <span>Login</span></a>
      </div>
    </div>
  </div>

  <!-- Javascript Libraries -->
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/Waves/dist/waves.min.js"></script>

  <!-- Placeholder for IE9 -->
  <!--[if IE 9 ]>
      <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
  <![endif]-->

  <script src="<?= PORTAL_URL; ?>assets/plugins/Template2/js/app.min.js"></script>

  <script type="text/javascript" src="<?= JS_FOLDER ?>livequery.js"></script>

  <!-- JS UTIL -->
  <script src="<?= PORTAL_URL ?>utils/utils.js" type="text/javascript"></script>
  <script src="<?= PORTAL_URL ?>utils/projeto.utils.js" type="text/javascript"></script>

  <!-- JS DO LOGIN -->
  <script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/recuperar_senha.js"></script>

</body>
</html>
