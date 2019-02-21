<?php
@session_start();
include_once('conf/config.php');
include_once('assets/plugins/phpmailer/class.smtp.php');
include_once('assets/plugins/phpmailer/class.phpmailer.php');
include_once('utils/funcoes.php');
include_once('conf/Url.php');

// VERIFICAÇÕES DE SESSÕES
if (isset($_SESSION['id'])) {
  echo "<script>window.location = '" . PORTAL_URL . "dashboard';</script>";
  exit();
}
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
  <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
  <link href="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">

  <!-- CSS -->
  <link href="<?= PORTAL_URL; ?>assets/plugins/template2/css/app_1.min.css" rel="stylesheet">
  <link href="<?= PORTAL_URL; ?>assets/plugins/template2/css/app_2.min.css" rel="stylesheet">
</head>
<body class="login">

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
    <div class="transparente"></div>

    <!-- Login -->

    <div class="lc-block toggled" id="l-login">
      <div class="logo"></div>
      <div class="lcb-form">
        <form id="form_login" name="form_login" method="post" action="#">
          <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
            <div id="div_login" class="fg-line">
              <input type="text" class="form-control" placeholder="Usuário" id="login" name="login">
            </div>
          </div>

          <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-key zmdi-hc-fw"></i></span>
            <div id="div_senha" class="fg-line">
              <input type="password" class="form-control" placeholder="Senha" id="senha" name="senha">
            </div>
          </div>

          <!--          <div class="checkbox">
                      <label>
                        <input type="checkbox" value="">
                        <i class="input-helper"></i>
                        Mantenha-me conectado
                      </label>
                    </div>-->

          <button type="submit" class="btn btn-login btn-success btn-float"><i class="zmdi zmdi-arrow-forward"></i></button>
        </form>
      </div>

      <div class="lcb-navigation">
        <a style="display: none" href="" data-ma-action="login-switch" data-ma-block="#l-register"><i class="zmdi zmdi-plus"></i> <span>Register</span></a>
        <a href="" data-ma-action="login-switch" data-ma-block="#l-forget-password"><i>?</i> <span>Esqueceu a senha</span></a>
      </div>
    </div>
    <div class="logo-governo"></div>
    <!-- Register -->
    <!--    <div class="lc-block" id="l-register">
          <div class="lcb-form">
            <div class="input-group m-b-20">
              <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
              <div class="fg-line">
                <input type="text" class="form-control" placeholder="Username">
              </div>
            </div>
    
            <div class="input-group m-b-20">
              <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
              <div class="fg-line">
                <input type="text" class="form-control" placeholder="Email Address">
              </div>
            </div>
    
            <div class="input-group m-b-20">
              <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
              <div class="fg-line">
                <input type="password" class="form-control" placeholder="Password">
              </div>
            </div>
            <button type="submit" class="btn btn-login btn-success btn-float"><i class="zmdi zmdi-check"></i></button>
          </div>
    
          <div class="lcb-navigation">
            <a href="" data-ma-action="login-switch" data-ma-block="#l-login"><i class="zmdi zmdi-long-arrow-right"></i> <span>Sign in</span></a>
            <a href="" data-ma-action="login-switch" data-ma-block="#l-forget-password"><i>?</i> <span>Forgot Password</span></a>
          </div>
        </div>-->

    <!-- Forgot Password -->

    <!--    <div class="lc-block" id="l-forget-password">
          <div class="logo"></div>
          <div class="lcb-form">
            <p class="text-left">Para redefinir sua senha digite o seu e-mail cadastrado, que enviaremos uma mensagem para redefinir sua senha de acesso.</p>
            <form id="form_redefinir" name="form_redefinir" method="post" action="#">
              <div class="input-group m-b-20">
                <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                <div id="div_email" class="fg-line">
                  <input type="email" id="email" name="email" class="form-control" placeholder="E-mail">
                </div>
              </div>
              <button type="submit" class="btn btn-login btn-success btn-float"><i class="zmdi zmdi-check"></i></button>
            </form>
          </div>
    
          <div class="lcb-navigation">
            <a href="" data-ma-action="login-switch" data-ma-block="#l-login"><i class="zmdi zmdi-long-arrow-left"></i> <span>Login</span></a>
            <a style="display: none" href="" data-ma-action="login-switch" data-ma-block="#l-register"><i class="zmdi zmdi-plus"></i> <span>Register</span></a>
          </div>
        </div>-->

    <div class="lc-block" id="l-forget-password">
      <div class="logo"></div>
      <div class="lcb-form">
        <p class="text-left">Para redefinir sua senha entre em contato com o administrador através do seguinte e-mail: <b>informatica.sehab@ac.gov.br</b></p>
      </div>

      <div class="lcb-navigation">
        <a href="" data-ma-action="login-switch" data-ma-block="#l-login"><i class="zmdi zmdi-long-arrow-left"></i> <span>Login</span></a>
        <a style="display: none" href="" data-ma-action="login-switch" data-ma-block="#l-register"><i class="zmdi zmdi-plus"></i> <span>Register</span></a>
      </div>
    </div>

  </div>


  <!-- Older IE warning message -->
  <!--[if lt IE 9]>
      <div class="ie-warning">
          <h1 class="c-white">Warning!!</h1>
          <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
          <div class="iew-container">
              <ul class="iew-download">
                  <li>
                      <a href="http://www.google.com/chrome/">
                          <img src="img/browsers/chrome.png" alt="">
                          <div>Chrome</div>
                      </a>
                  </li>
                  <li>
                      <a href="https://www.mozilla.org/en-US/firefox/new/">
                          <img src="img/browsers/firefox.png" alt="">
                          <div>Firefox</div>
                      </a>
                  </li>
                  <li>
                      <a href="http://www.opera.com">
                          <img src="img/browsers/opera.png" alt="">
                          <div>Opera</div>
                      </a>
                  </li>
                  <li>
                      <a href="https://www.apple.com/safari/">
                          <img src="img/browsers/safari.png" alt="">
                          <div>Safari</div>
                      </a>
                  </li>
                  <li>
                      <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                          <img src="img/browsers/ie.png" alt="">
                          <div>IE (New)</div>
                      </a>
                  </li>
              </ul>
          </div>
          <p>Sorry for the inconvenience!</p>
      </div>
  <![endif]-->

  <!-- Javascript Libraries -->
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/Waves/dist/waves.min.js"></script>

  <!-- Placeholder for IE9 -->
  <!--[if IE 9 ]>
      <script src="<?= PORTAL_URL; ?>assets/plugins/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
  <![endif]-->

  <script src="<?= PORTAL_URL; ?>assets/plugins/template2/js/app.min.js"></script>

  <script type="text/javascript" src="<?= JS_FOLDER ?>livequery.js"></script>

  <!-- JS UTIL -->
  <script src="<?= PORTAL_URL ?>utils/utils.js" type="text/javascript"></script>
  <script src="<?= PORTAL_URL ?>utils/projeto.utils.js" type="text/javascript"></script>

  <!-- JS DO LOGIN -->
  <script type="text/javascript" src="<?= PORTAL_URL; ?>hab/js/login.js"></script>

</body>
</html>
