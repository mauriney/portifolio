<?php
@session_start();
include_once ('conf/config.php');
include_once ('assets/plugins/phpmailer/class.smtp.php');
include_once ('assets/plugins/phpmailer/class.phpmailer.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

$_SESSION['hab_candidato_id'] = '';
$_SESSION['hab_candidato_timeout'] = '';

?>
<html>
<head>
<meta charset="UTF-8">
<title>SIHAB :: SISTEMA DE HABITAÇÃO</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<meta name="theme-color" content="#0DCCC0">
<meta name="msapplication-navbutton-color" content="#0DCCC0">
<meta name="apple-mobile-web-app-status-bar-style" content="#0DCCC0">
<!-- STYLE CSS -->
<link href="<?= PORTAL_URL; ?>assets/fontes/stylesheet.css" rel="stylesheet">
<!-- Vendor CSS -->
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/nouislider/distribute/jquery.nouislider.min.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<!-- CSS -->
<link href="<?= PORTAL_URL; ?>assets/css/app.min.1.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/css/app.min.2.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/css/style.css" rel="stylesheet">
<link href="<?= PORTAL_URL; ?>assets/css/cores.css" rel="stylesheet">
</head>
<body class="site">
  <section class="content">
    <div class="imagem"></div>
    <h1>GOVERNO DO ESTADO DO ACRE</h1>
    <h2>SECRETARIA DE ESTADO DE HABITAÇÃO DE INTERESSE SOCIAL - SEHAB</h2>
    <h3>VERIFIQUE A SITUAÇÃO DE SUA INSCRIÇÃO</h3>
    <form action="" id="home_login" name="home_login" method="post">
      <div class="row">
        <div class="col-xs-12 item-form">
          <div class="form-group fg-float">
            <div id="div_cad_unico" class="fg-line">
              <input id="cpf" name="cpf" type="text" class="input-sm form-control fg-input" data-mask="000.000.000-00" />
              <label class="fg-label">CPF</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 item-form">
          <div class="form-group fg-float">
            <div id="div_nis" class="fg-line">
              <input id="senha" name="senha" type="password" class="input-sm form-control fg-input" />
              <label class="fg-label">SENHA</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <button id="anterior" name="anterior" class="btn btn-primary btn-lg bt-acessar waves-effect">ACESSAR</button>
        </div>
      </div>
    </form>
  </section>
  <footer>
    <div class="logo-governo"></div>
    <p>Avenida das Acácias, Zona A, Lote 01, Distrito Industrial – CEP 69917-100 – Rio Branco – ACRE – Telefone +55 68 3229-1211</p>
  </footer>
  <!-- Javascript Libraries -->
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/Waves/dist/waves.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/moment/min/moment.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/salvattore/dist/salvattore.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/input-mask/input-mask.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/flot/jquery.flot.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/flot/jquery.flot.resize.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/flot.curvedlines/curvedLines.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/sparklines/jquery.sparkline.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/js/flot-charts/curved-line-chart.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/js/flot-charts/line-chart.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/js/jquery.form.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/form-wizard/js/tmm_form_wizard_custom.js"></script>
  <script type="text/javascript" src="<?= JS_FOLDER ?>livequery.js"></script>
  <!-- JS UTIL -->
  <script src="<?= PORTAL_URL ?>utils/utils.js" type="text/javascript"></script>
  <script src="<?= PORTAL_URL ?>utils/projeto.utils.js" type="text/javascript"></script>
  <!-- JAVASCRIPT PARA PESQUISA DINÂMICA -->
  <script src="<?= JS_FOLDER ?>pesquisa_dinamica.js"></script>
  <!-- Placeholder for IE9 -->
  <!--[if IE 9 ]>
  <script src="vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
  <![endif]-->
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/chosen/chosen.jquery.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/fileinput/fileinput.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/input-mask/input-mask.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/farbtastic/farbtastic.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
  <!-- <script src="js/functions.js"></script> -->
  <!-- <script src="js/actions.js"></script> -->
  <!-- <script src="js/demo.js"></script> -->
  <script src="<?= PORTAL_URL; ?>assets/js/charts.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/js/functions.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/js/actions.js"></script>
  <!--<script src="<?= PORTAL_URL; ?>assets/js/demo.js"></script>-->
  <!-- JS DO LOGIN -->
  <script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/site/login.js"></script>
</body>
</html>
