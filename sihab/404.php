<?php
// session_start();
// INCLUDE CONEXÃO
include_once "conf/config.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>
      <?= TITULOSISTEMA ?>
    </title>

    <!-- Vendor CSS -->
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="<?= PORTAL_URL; ?>assets/plugins/Template2/css/app_1.min.css" rel="stylesheet">
    <link href="<?= PORTAL_URL; ?>assets/plugins/Template2/css/app_2.min.css" rel="stylesheet">

  </head>
  <body class="menubar-hoverable header-fixed ">

    <div id="content">
      <!-- BEGIN 404 MESSAGE -->
      <section>
        <div class="section-body contain-lg">
          <div class="row">
            <div class="col-lg-12 text-center">
              <h1><img src="<?= PORTAL_URL . 'imagens/ass-page-error.svg' ?>" width="500" height="120"></h1>
              <h1><span class="text-xxxl text-light">404 <i class="fa fa-search-minus text-primary"></i></span></h1>
              <h2 class="text-light">Ops! Página não encontrada</h2>
            </div><!--end .col -->
          </div><!--end .row -->
        </div><!--end .section-body -->
      </section>
      <!-- END 404 MESSAGE -->
    </div>

  </body>
</html>