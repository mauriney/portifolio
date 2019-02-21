<?php
@session_start();
include_once ('conf/config.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SORTEIO .::. SECRETARIA DE ESTADO DE HABITAÇÃO - SEHAB</title>
    <!-- STYLE CSS -->
    <link href="<?= PORTAL_URL; ?>assets/fontes/stylesheet.css" rel="stylesheet">
    <!-- CSS -->
    <link href="<?= PORTAL_URL; ?>assets/css/app.min.1.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/app.min.2.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/servidor.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/css/cores.css" rel="stylesheet">
    <!-- IMPORTANDO O CSS E JS DO PLUGIN DE SORTEIO -->
    <link rel="stylesheet" href="<?= ASSETS_FOLDER; ?>plugins/jQuery-SlotMachine-master/dist/jquery.slotmachine.css" type="text/css" media="screen" />
    <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="<?= ASSETS_FOLDER; ?>plugins/jQuery-SlotMachine-master/dist/jquery.slotmachine.js"></script>

    <!-- Vendor CSS -->
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet">
    <!-- Notificação -->
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/location-sel.css" rel="stylesheet" type="text/css" media="screen"/>

  </head>
  <body>
    <div class="transparencia"></div>
    <div class="content">
      <div class="container">
        <img src="<?= PORTAL_URL; ?>assets/img/brasao-governo.svg" class="logo" alt="">
        <h2>Governo do Estado do Acre</h2>
        <h3>Secretaria de Habitação</h3>
        <br>
        <h2>
          <p>
          <p>
            O <b>Programa Habitacional do Servidor Público do Estado do Acre-PHSPAC</b> foi instituído pela Lei 3.087, de 23 de dezembro de 2015, que conferiu à Secretaria de Habitação e Interesse Social – SEHAB, a responsabilidade pela habilitação geral dos servidores públicos estaduais que têm interesse em adquirir lotes urbanizados ou unidades habitacionais.
          </p>
          <p>
            Neste sentido, o formulário anexo tem o objetivo de compor um banco de dados de  servidores público estaduais que não possuem casa própria, e que têm interesse em adquirí-la por meio de programas/iniciativas, ofertados ou apoiados pelo Governo do Estado do Acre, com valores abaixo do mercado.
          </p><p>
            É importante destacar que os imóveis serão vendidos, com a intermediação de uma instituição bancária financiadora.
          </p><p>
            O preenchimento do formulário não gera direitos ao servidor, como também não gera obrigações por parte do Governo do Estado, uma vez que se configura como uma pesquisa de intenção.
          </p>

        </h2>
        <p class="assinatura" style="font-size: 18px"><b>Janaína Guedes Bezerra Dourado</b><br>
          Secretária de Estado de Habitação de Interesse Social


          <a class="edital" href="../../../../anexos/sishab/docs/EDITAL_DE_CHAMAMENTO_No_01-2017-MANIFESTACAO_DE_INTERESSES_PHSPAC.pdf" target="_blank">EDITAL DE CHAMAMENTO PÚBLICO Nº 01/2017</a>
          <a class="edital" href="../../../../anexos/sishab/docs/EDITAL_DE_CHAMAMENTO_No_02-2017-MANIFESTACAO_DE_INTERESSES_PHSPAC.pdf" target="_blank">EDITAL DE CHAMAMENTO PÚBLICO Nº 02/2017 (PRORROGAÇÃO)</a>

          <br><br><br><br>


        <div class="footer">
          <div class="logo-governo"></div>
          <div class="logo-sihab"></div>
          <a href="<?= PORTAL_URL; ?>hab/servidor/formulario" class="butao btn btn-success">ACESSE AQUI O FORMULÁRIO</a>
        </div>
        <div class="logo-governo"></div>
        <div class="logo-sihab"></div>
      </div>
    </div>
  </body>
</html>

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

<script type="text/javascript">
//  swal({
//    title: "Aviso",
//    text: "O formulário de cadastro estará disponível às 10:00 horas",
//    type: "success",
//    showCancelButton: false,
//    confirmButtonColor: "#8CD4F5",
//    confirmButtonText: "OK",
//    closeOnConfirm: false
//  });
</script>