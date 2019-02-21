<?php
@session_start();
include_once ('conf/config.php');
include_once ('assets/plugins/phpmailer/class.smtp.php');
include_once ('assets/plugins/phpmailer/class.phpmailer.php');
include_once ('utils/funcoes.php');
include_once ('conf/Url.php');

$db = Conexao::getInstance();

vf_session();
//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE VISUALIZAR A PÁGINA
vf_permissao_pagina("visualizar");
//VERIFICA SE O USUÁRIO ESTÁ LOGADO PELA SESSION
vf_usuario_login();
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= TITULOSISTEMA ?></title>
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
  <!-- Notificação -->
  <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen"/>
  <link href="<?= ASSETS_FOLDER; ?>plugins/jquery-notifications/css/location-sel.css" rel="stylesheet" type="text/css" media="screen"/>

</head>
<body data-ma-header="verde-claro">
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
  <header id="header" class="media">
    <div class="pull-left h-logo">
      <a href="<?= PORTAL_URL; ?>dashboard" class="hidden-xs logo-system"> SISHAB </a>
      <div class="menu-collapse" data-ma-action="sidebar-open" data-ma-target="main-menu">
        <div class="mc-wrap">
          <div class="mcw-line top palette-White bg"></div>
          <div class="mcw-line center palette-White bg"></div>
          <div class="mcw-line bottom palette-White bg"></div>
        </div>
      </div>
    </div>
    <ul class="pull-right h-menu">
      <li class="hm-search-trigger">
        <a href="" data-ma-action="search-open">
          <i class="hm-icon zmdi zmdi-search"></i>
        </a>
      </li>
      <!--      <li class="dropdown hidden-xs hidden-sm h-apps">-->
      <!--        <a data-toggle="dropdown" href="">-->
      <!--          <i class="hm-icon zmdi zmdi-apps"></i>-->
      <!--        </a>-->
      <!--        <ul class="dropdown-menu pull-right">-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="palette-Red-400 bg zmdi zmdi-calendar"></i>-->
      <!--              <small>Calendar</small>-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="palette-Green-400 bg zmdi zmdi-file-text"></i>-->
      <!--              <small>Files</small>-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="palette-Light-Blue bg zmdi zmdi-email"></i>-->
      <!--              <small>Mail</small>-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="palette-Orange-400 bg zmdi zmdi-trending-up"></i>-->
      <!--              <small>Analytics</small>-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="palette-Purple-300 bg zmdi zmdi-view-headline"></i>-->
      <!--              <small>News</small>-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="palette-Blue-Grey bg zmdi zmdi-image"></i>-->
      <!--              <small>Gallery</small>-->
      <!--            </a>-->
      <!--          </li>-->
      <!--        </ul>-->
      <!--      </li>-->
      <!--      <li class="dropdown hidden-xs">-->
      <!--        <a data-toggle="dropdown" href="">-->
      <!--          <i class="hm-icon zmdi zmdi-more-vert"></i>-->
      <!--        </a>-->
      <!--        <ul class="dropdown-menu dm-icon pull-right">-->
      <!--          <li class="hidden-xs">-->
      <!--            <a data-action="fullscreen" href="">-->
      <!--              <i class="zmdi zmdi-fullscreen"></i>-->
      <!--              Toggle Fullscreen-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a data-action="clear-localstorage" href="">-->
      <!--              <i class="zmdi zmdi-delete"></i>-->
      <!--              Clear Local Storage-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="zmdi zmdi-face"></i>-->
      <!--              Privacy Settings-->
      <!--            </a>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <a href="">-->
      <!--              <i class="zmdi zmdi-settings"></i>-->
      <!--              Other Settings-->
      <!--            </a>-->
      <!--          </li>-->
      <!--        </ul>-->
      <!--      </li>-->
      <?php
      $qtd_pendencias = vf_pendencias();
      ?>
      <li class="hm-alerts" data-user-alert="sua-messages" data-ma-action="sidebar-open" data-ma-target="user-alerts">
        <a href="">
          <i class="hm-icon zmdi zmdi-notifications"></i>
          <?= $qtd_pendencias == 0 ? '' : '<span class="notification">' . $qtd_pendencias . '</span>'; ?>
        </a>
      </li>
      <li class="dropdown hm-profile">
        <a data-toggle="dropdown" href="">
          <?php
          if ($_SESSION['foto'] != "") {
            ?>
            <img src="<?= $_SESSION['foto']; ?>" alt="">
            <?php
          } else {
            ?>
            <img src="<?= PORTAL_URL; ?>assets/img/profile-pics/picture.jpg" alt="">
            <?php
          }
          ?>
        </a>
        <ul class="dropdown-menu pull-right dm-icon">
          <li>
            <a href="<?= PORTAL_URL; ?>sistema/usuario/visualiza/<?= $_SESSION['id']; ?>">
              <i class="zmdi zmdi-account"></i>
              Ver Perfil
            </a>
          </li>
          <li>
            <a href="<?= PORTAL_URL; ?>sistema/usuario/senha/<?= $_SESSION['id']; ?>">
              <i class="zmdi zmdi-key"></i>
              Trocar a Senha
            </a>
          </li>
          <!--          <li>
                      <a href="">
                        <i class="zmdi zmdi-input-antenna"></i>
                        Pconfigurações de privacidade
                      </a>
                    </li>
                    <li>
                      <a href="">
                        <i class="zmdi zmdi-settings"></i>
                        Configuraçãoes
                      </a>
                    </li>-->
          <li>
            <a href="<?= PORTAL_URL; ?>logout">
              <i class="zmdi zmdi-power"></i>
              Sair
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <div class="media-body h-search">
      <form id="form_busca" name="form_busca" method="post" action="#" class="p-relative">
        <input type="text" class="hs-input" placeholder="Buscar Candidato" id="buscar_form" name="buscar_form" type="text" class="form-control busca-normal" OnKeyUp="pesquisa(this.value, '<?= $GLOBALS['urlPasta']; ?>', '<?= $GLOBALS['urlArquivo']; ?>')" value="<?= @$_POST['buscar_form']; ?>">
        <i class="zmdi zmdi-search hs-reset" data-ma-action="search-clear"></i>
      </form>
    </div>
  </header>
  <header id="header" class="media header-scroll">
    <div class="pull-left h-logo">
      <a href="<?= PORTAL_URL; ?>dashboard" class="hidden-xs"> SISHAB </a>
      <div class="menu-collapse" data-ma-action="sidebar-open" data-ma-target="main-menu">
        <div class="mc-wrap">
          <div class="mcw-line top palette-White bg"></div>
          <div class="mcw-line center palette-White bg"></div>
          <div class="mcw-line bottom palette-White bg"></div>
        </div>
      </div>
    </div>
    <ul class="pull-right h-menu">
      <li class="hm-search-trigger">
        <a href="" data-ma-action="search-open">
          <i class="hm-icon zmdi zmdi-search"></i>
        </a>
      </li>
      <li class="dropdown hidden-xs hidden-sm h-apps">
        <a data-toggle="dropdown" href="">
          <i class="hm-icon zmdi zmdi-apps"></i>
        </a>
        <ul class="dropdown-menu pull-right">
          <li>
            <a href="">
              <i class="palette-Red-400 bg zmdi zmdi-calendar"></i>
              <small>Calendar</small>
            </a>
          </li>
          <li>
            <a href="">
              <i class="palette-Green-400 bg zmdi zmdi-file-text"></i>
              <small>Files</small>
            </a>
          </li>
          <li>
            <a href="">
              <i class="palette-Light-Blue bg zmdi zmdi-email"></i>
              <small>Mail</small>
            </a>
          </li>
          <li>
            <a href="">
              <i class="palette-Orange-400 bg zmdi zmdi-trending-up"></i>
              <small>Analytics</small>
            </a>
          </li>
          <li>
            <a href="">
              <i class="palette-Purple-300 bg zmdi zmdi-view-headline"></i>
              <small>News</small>
            </a>
          </li>
          <li>
            <a href="">
              <i class="palette-Blue-Grey bg zmdi zmdi-image"></i>
              <small>Gallery</small>
            </a>
          </li>
        </ul>
      </li>
      <li class="dropdown hidden-xs">
        <a data-toggle="dropdown" href="">
          <i class="hm-icon zmdi zmdi-more-vert"></i>
        </a>
        <ul class="dropdown-menu dm-icon pull-right">
          <li class="hidden-xs">
            <a data-action="fullscreen" href="">
              <i class="zmdi zmdi-fullscreen"></i>
              Toggle Fullscreen
            </a>
          </li>
          <li>
            <a data-action="clear-localstorage" href="">
              <i class="zmdi zmdi-delete"></i>
              Clear Local Storage
            </a>
          </li>
          <li>
            <a href="">
              <i class="zmdi zmdi-face"></i>
              Privacy Settings
            </a>
          </li>
          <li>
            <a href="">
              <i class="zmdi zmdi-settings"></i>
              Other Settings
            </a>
          </li>
        </ul>
      </li>
      <li class="hm-alerts" data-user-alert="sua-messages" data-ma-action="sidebar-open" data-ma-target="user-alerts">
        <a href="">
          <i class="hm-icon zmdi zmdi-notifications"></i>
          <?= $qtd_pendencias == 0 ? '' : '<span class="notification">' . $qtd_pendencias . '</span>'; ?>
        </a>
      </li>
      <li class="dropdown hm-profile">
        <a data-toggle="dropdown" href="">


          <?php
          if ($_SESSION['foto'] != "") {
            ?>
            <img src="<?= $_SESSION['foto']; ?>" alt="">
            <?php
          } else {
            ?>
            <img src="<?= PORTAL_URL; ?>assets/img/profile-pics/picture.jpg" alt="">
            <?php
          }
          ?>
        </a>
        <ul class="dropdown-menu pull-right dm-icon">
          <li>
            <a href="<?= PORTAL_URL; ?>sistema/usuario/visualiza/<?= $_SESSION['id']; ?>">
              <i class="zmdi zmdi-account"></i>
              Ver Perfil
            </a>
          </li>
<!--          <li>
            <a href="">
              <i class="zmdi zmdi-input-antenna"></i>
              Pconfigurações de privacidade
            </a>
          </li>-->
          <li>
            <a href="">
              <i class="zmdi zmdi-settings"></i>
              Configuraçãoes
            </a>
          </li>
          <li>
            <a href="<?= PORTAL_URL; ?>logout">
              <i class="zmdi zmdi-time-restore"></i>
              Sair
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <div class="media-body h-search">
      <form id="form_busca" name="form_busca" method="post" action="#" class="p-relative">
        <input type="text" class="hs-input" placeholder="Buscar" id="buscar_form" name="buscar_form" type="text" placeholder="Buscar" class="form-control busca-normal" OnKeyUp="pesquisa(this.value, '<?= $GLOBALS['urlPasta']; ?>', '<?= $GLOBALS['urlArquivo']; ?>')" value="<?= @$_POST['buscar_form']; ?>">
        <i class="zmdi zmdi-search hs-reset" data-ma-action="search-clear"></i>
      </form>
    </div>
  </header>
  <section id="main">