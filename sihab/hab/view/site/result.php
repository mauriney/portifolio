<?php
@session_start();
include_once ('conf/config.php');
include_once ('assets/plugins/phpmailer/class.smtp.php');
include_once ('assets/plugins/phpmailer/class.phpmailer.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

$id = (isset($_SESSION['hab_candidato_id']) && isset($_SESSION['hab_candidato_timeout']) ? ($_SESSION['hab_candidato_id'] != 0 && $_SESSION['hab_candidato_timeout'] >= time() ? $_SESSION['hab_candidato_id'] : 0) : 0);

if ($id == 0) {
  header('Location: ' . PORTAL_URL . 'hab/view/site/home'); /* Redirect browser */
  exit();
}
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
  <body class="applicant-data">
    <header>
      <h1>BENEFICIÁRIO DO PROGRAMA DE HABITAÇÃO</h1>
      <a target="_blank" href="<?= PORTAL_URL; ?>sistema/site/result-impressao" class="print btn palette-Teal-600 bg btn-float waves-effect">
        <i class="zmdi zmdi-print zmdi-hc-fw"></i>
      </a>
      <a href="<?= PORTAL_URL; ?>sistema/site/home" class="logout btn palette-Pink-A400 bg btn-float waves-effect">
        <i class="zmdi zmdi-power zmdi-hc-fw"></i>
      </a>
    </header>
    <section class="content">

      <?php include_once "visualiza.php"; ?>

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
    <script src="<?= PORTAL_URL; ?>hab/js/charts.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/js/functions.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/js/actions.js"></script>
    <!--<script src="<?= PORTAL_URL; ?>assets/js/demo.js"></script>-->
    <script src="<?= PORTAL_URL; ?>hab/js/site/menu_visualiza.js"></script>
    <script src="<?= PORTAL_URL; ?>hab/js/site/visualiza.js"></script>
  </body>
</html>
