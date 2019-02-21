<?php
@session_start();
include_once ('conf/sistema.php');
include_once ('assets/plugins/phpmailer/class.smtp.php');
include_once ('assets/plugins/phpmailer/class.phpmailer.php');
include_once ('functions/geral.php');
include_once ('functions/Url.php');

$db = Conexao::getInstance();

$_SESSION['estabelecimento'] = pesquisar_tabela("nome", "estabelecimento", "id", "=", 1, "");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= TITULOSISTEMA ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Sweetalert --> 
        <link href="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.css" rel="stylesheet">  
        <!-- Bootstrap 3.3.4 -->
        <link href="<?= ASSETS_FOLDER; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />  
        <!-- Theme style -->
        <link href="<?= CSS_FOLDER; ?>AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= CSS_FOLDER; ?>login.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?= CSS_FOLDER; ?>skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <!-- iCheck -->
        <link href="<?= PLUGINS_FOLDER; ?>iCheck/square/blue.css" rel="stylesheet" type="text/css" />
        <!-- Fonte -->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet"> 

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>login/fonts/iconic/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>login/css/util.css">
        <link rel="stylesheet" type="text/css" href="<?= PLUGINS_FOLDER; ?>login/css/main.css">
        <!--===============================================================================================-->

    </head>
    <body class="login-page">