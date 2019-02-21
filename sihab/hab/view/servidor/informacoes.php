<?php
@session_start();
include_once ('conf/config.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

$id = (!isset($_POST['id']) && isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0));
$param = $GLOBALS['urlParametro'];
$param = $param == '' && $id != '' ? $id : $param;

// NOME BAIRROS
$stmt = $db->prepare("SELECT s.nome 
                      FROM svd_servidor AS s
                      WHERE s.id = ?");
$stmt->bindValue(1, $param);
$stmt->execute();
$servidor = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CADASTRO .::. SECRETARIA DE ESTADO DE HABITAÇÃO - SEHAB</title>
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
    <link href="<?= PORTAL_URL; ?>assets/css/formulario.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/cores.css" rel="stylesheet">
    <!-- Notificação -->
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/location-sel.css" rel="stylesheet" type="text/css" media="screen"/>
  </head>
  <body>
    <div class="transparencia"></div>
    <div id="div_geral" class="content">
      <div class="container">
        <div class="row m-t-20">
          <div class="col-md-6">
            <div class="row">
              <div class="col-xs-2">
                <img src="<?= PORTAL_URL; ?>assets/img/brasao-governo.svg" class="logo" alt="">
              </div>
              <div class="col-xs-10">
                <h2 class="gov">Governo do Estado do Acre</h2>
                <h3 class="sec">Secretaria de Habitação</h3>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="chamamento">
              <p>Edital de Chamamento nº 01/2017</p>
              <p>Manifestação de Interesse</p>
              <p>Programa Habitacional do Servidor Público do Estado do Acre - PHSPAC</p>
            </div>
          </div>
        </div>

        <div class="card m-t-20">
          <div class="card-header color-block bgm-cyan">
            <center><h1 style="color: white; font-weight: bold">AVISO!</h1></center>
          </div>
          <div class="card-body card-padding">
            <br/>
            <center><h3 style="color: #808080"><?= $servidor['nome']; ?></h3></center>
            <p style="color: #000; text-align: center">A habilitação não gera, por si só, direitos ou obrigações aos interessados, mas apenas atesta o cumprimento dos requisitos previstos em lei para adquirir os imóveis do programa de que trata o Edital.</p>
          </div>
        </div><!-- FIM TIMPOLOGIA DO IMÓVEL DE INTERESSE -->  

        <a href="<?= PORTAL_URL; ?>hab/servidor/index"><button type="submit" id="inicio" name="inicio" class="btn btn-success">
            INÍCIO
          </button></a>

        <!-- <button type="submit" id="imprimir" name="imprimir" class="btn btn-success" onclick="print()">
          IMPRIMIR
        </button>-->

        <div class="logo-governo"></div>
        <div class="logo-sihab"></div>
      </div>
    </div>

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
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.js"></script>

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

    <script src="<?= PORTAL_URL; ?>assets/js/functions.js"></script>
    <script src="<?= PORTAL_URL; ?>assets/js/actions.js"></script>

    <!-- Notificação -->
    <script src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/messenger.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/demo/location-sel.js"></script>
    <script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/js/demo/theme-sel.js"></script>

  </body>
</html>